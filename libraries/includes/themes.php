<?php
//pr($currentUser -> coreAccess);
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}
if (isset($currentUser -> coreAccess['themes']) && $currentUser -> coreAccess['themes'] == 'hidden') {
    eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
    exit;
}

!isset($currentUser -> coreAccess['themes']) || $currentUser -> coreAccess['themes'] == 'change' ? $_change_ = 1 : $_change_ = 0;
$smarty -> assign("_change_", $_change_);

$loadScripts[] = 'scriptaculous/dragdrop';
$loadScripts[] = 'includes/themes';
$loadScripts[] = 'includes/entity';

$themeSettingsTools = array(array('text' => _APPEARANCE, 'image' => "16x16/layout.png", 'href' => basename($_SERVER['PHP_SELF']).'?ctg=system_config&op=appearance'));
$smarty -> assign ("T_APPEARANCE_LINK", $themeSettingsTools);

try {
    try {
        $currentSetTheme = new themes($GLOBALS['configuration']['theme']);
    } catch (Exception $e) {
        //MagesterConfiguration :: setValue('theme', 1);
        $currentSetTheme = new themes('default');
    }

    !isset($currentUser -> coreAccess['themes']) || $currentUser -> coreAccess['themes'] == 'change' ? $_change_ = 1 : $_change_ = 0;
    $smarty -> assign("_change_", $_change_);

    $themes = themes :: getAll("themes");
 $allBrowsers = themes :: $browsers;
    $usedBrowsers = array();

    foreach ($themes as $value) {
        $themeNames[] = $value['name'];
    }

    $filesystem = new FileSystemTree(G_THEMESPATH, true);
    foreach (new MagesterDirectoryOnlyFilterIterator(new ArrayIterator($filesystem -> tree)) as $key => $value) {
        //Automatically import themes that don't have an equivalent database representation
        if (!in_array($value['name'], $themeNames)) {
            try {
                $file = new MagesterFile($value['path']."/theme.xml");
                $xmlValues = themes :: parseFile($file);
                $newTheme = themes :: create($xmlValues);
                //$themes[$newTheme -> themes['id']] = $newTheme -> themes;
            } catch (Exception $e) {/*Don't halt for themes that can't be processed*/}
        }
    }
    $themes = themes :: getAll("themes");

    foreach ($themes as $value) {
        $themeNames[] = $value['name'];
        //$browserThemes[$value['id']] = $value['options']['browsers'];
     foreach ($allBrowsers as $browser => $foo) {
         if (isset($value['options']['browsers'][$browser])) {
             $usedBrowsers[$browser] = $value['id'];
             unset($allBrowsers[$browser]);
         }
     }
    }
    foreach ($allBrowsers as $key => $foo) {
        $currentSetTheme -> options['browsers'][$key] = 1;
        $themes[$currentSetTheme -> themes['id']]['options']['browsers'][$key] = 1;
    }

    $legalValues = array_merge(array_keys($themes), $themeNames);

    if (!isset($_GET['theme'])) {
        $_GET['theme'] = $currentSetTheme -> {$currentSetTheme -> entity}['id'];
    }
    $smarty -> assign("T_LAYOUT_SETTINGS", $currentSetTheme);

    if ((!isset($currentSetTheme -> remote) || !$currentSetTheme -> remote) && is_dir(G_EXTERNALPATH)) {
        /********** CMS / External pages from here over ************/
        $default_page = $GLOBALS['configuration']['cms_page'];
        $filesystem = new FileSystemTree(G_EXTERNALPATH);
        $pages = array();
        foreach (new MagesterFileTypeFilterIterator(new ArrayIterator($filesystem -> tree), array('php')) as $key => $value) {
            $pages[] = basename($key, '.php');
        }
        $smarty -> assign('T_CMS_PAGES', $pages);
        $smarty -> assign('T_DEFAULT_PAGE', $default_page);

        try {
            if ($_change_ && isset($_GET['delete']) && in_array($_GET['delete'], $pages)) {
                if (unlink (G_EXTERNALPATH."".$_GET['delete'].".php")) {
                    if ($GLOBALS['configuration']['page'] == $_GET['delete']) {
                        MagesterConfiguration :: setValue("cms_page", "");
                    }
                    exit;
                } else {
                    throw new Exception(_PAGECOULDNOTBEDELETED);
                }
            } elseif ($_change_ && isset($_GET['use_none'])) {
                MagesterConfiguration :: setValue("cms_page", "");
                exit;
            } elseif ($_change_ && isset($_GET['set_page'])) {
                if (!in_array($_GET['set_page'], $pages)) {
                    throw new Exception (_INVALIDPAGE);
                }
                MagesterConfiguration :: setValue('cms_page', $_GET['set_page']);
                echo 1;//means a page was set, used for putting the green pin.
                exit;
            }
        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }

        if (isset($_GET['add_page']) || (isset($_GET['edit_page']) && in_array($_GET['edit_page'], $pages) && eF_checkParameter($_GET['edit_page'], 'filename'))) {
            if (isset($currentUser -> coreAccess['cms']) && $currentUser -> coreAccess['cms'] != 'change') {
                eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
            }
            $load_editor = true;
            isset($_GET['edit_page']) ? $post_target = '&edit_page='.$_GET['edit_page'] : $post_target = '&add_page=1';

            $form = new HTML_QuickForm("add_page_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=themes&theme=".$currentSetTheme -> {$currentSetTheme -> entity}['id']."&tab=external".$post_target, "", null, true);
            $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
            $form -> addElement('text', 'name', _FILENAME, 'class = "inputText"');
            $form -> addRule('name', _THEFIELD.' '._FILENAME.' '._ISMANDATORY, 'required', null, 'client');
            $form -> addRule('name', _INVALIDFIELDDATA, 'checkParameter', 'text');
            $form -> addElement('textarea', 'page', _PAGECONTENT, 'id="editor_cms_data" class = "inputContentTextarea templateEditor" style = "width:100%;height:30em;"');

            if (isset($_GET['edit_page'])) {
                $pageContent = file_get_contents(G_EXTERNALPATH."".$_GET['edit_page'].".php");
                $defaults['name'] = $_GET['edit_page'];
                $defaults['page'] = preg_replace("/.*<<<EOT(.*)EOT.*/s", "\$1", $pageContent);//, false, $matches);
                $form -> setDefaults($defaults);
            } else {
                $defaults['page'] = '<a href="'.G_SERVERNAME.'index.php?index_page">'._MAGESTERLOGIN.'</a>';
                $form -> setDefaults($defaults);
            }
            $form -> addElement('submit', 'submit_cms', _SUBMIT, 'class = "flatButton"');

            if ($form -> isSubmitted() && $form -> validate()) {
                $values = $form -> exportValues();
                $filename = G_EXTERNALPATH.$values['name'].'.php';
                if (is_file(G_ADMINPATH.'cms_templates/default_template.php')) {
                    $defaultContent = file_get_contents(G_ADMINPATH.'cms_templates/default_template.php');
                    $newContent = preg_replace("/put_content_here/", $values['page'], $defaultContent);
                } else {
                    $newContent = $values['page'];
                }
                file_put_contents($filename, $newContent);
                chmod($filename, 0644);
                try {
                    eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=themes&theme=".$currentSetTheme -> {$currentSetTheme -> entity}['id']."&tab=external&message=".urlencode(_SUCCESFULLYADDEDPAGE)."&message_type=success");
                } catch (Exception $e) {
                    $message = $e -> getMessage().'('.$e -> getCode().')';
                    $message_type = 'failure';
                }
            }

            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);

            $form -> setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);
            $form -> setRequiredNote(_REQUIREDNOTE);
            $form -> accept($renderer);
            $smarty -> assign('T_CMS_FORM', $renderer -> toArray());

            $basedir = G_EXTERNALPATH;
            try {
                $filesystem = new FileSystemTree($basedir);
                $filesystem -> handleAjaxActions($currentUser);

                if (isset($_GET['edit_page'])) {
                    $url = basename($_SERVER['PHP_SELF']).'?ctg=themes&theme='.$currentSetTheme -> {$currentSetTheme -> entity}['id'].'&tab=external&edit_page='.$_GET['edit_page'];
                } else {
                    $url = basename($_SERVER['PHP_SELF']).'?ctg=themes&theme='.$currentSetTheme -> {$currentSetTheme -> entity}['id'].'&tab=external&add_page=1';
                }
                $options = array('share' => false);

                include 'file_manager.php';
            } catch (Exception $e) {
             handleNormalFlowExceptions($e);
            }
        }
    }
    /************ Change logo/favicon part *************/
    if (isset($currentSetTheme -> remote) && $currentSetTheme -> remote) {
        $smarty -> assign ("T_REMOTE_THEME", 1);
    } else {
    }


    /*Layout part from here over*/
    if (isset($_GET['theme_layout']) && in_array($_GET['theme_layout'], $legalValues)) {
        $layoutTheme = new themes($_GET['theme_layout']);
    } else {
        $layoutTheme = $currentSetTheme;
    }
    $smarty -> assign("T_LAYOUT_THEME", $layoutTheme);
    isset($layoutTheme -> layout['custom_blocks']) && is_array($layoutTheme -> layout['custom_blocks']) ? $customBlocks = $layoutTheme -> layout['custom_blocks'] : $customBlocks = array();

    if (isset($_GET['add_block']) || isset($_GET['edit_header']) || isset($_GET['edit_footer']) || (isset($_GET['edit_block']) && in_array($_GET['edit_block'], array_keys($customBlocks)))) {

        $basedir = G_EXTERNALPATH;

        try {
            if (!is_dir($basedir) && !mkdir($basedir, 0755)) {
                throw new MagesterFileException(_COULDNOTCREATEDIRECTORY.': '.$fullPath, MagesterFileException :: CANNOT_CREATE_DIR);
            }
            $smarty -> assign("T_EDITOR_PATH", $basedir); //This is used for the browse.php method to know where to look

            $filesystem = new FileSystemTree($basedir);
            $filesystem -> handleAjaxActions($currentUser);

            if (isset($_GET['edit_block'])) {
                $url = basename($_SERVER['PHP_SELF']).'?ctg=themes&theme='.$layoutTheme -> {$layoutTheme -> entity}['id'].'&edit_block='.$_GET['edit_block'];
            } else {
                $url = basename($_SERVER['PHP_SELF']).'?ctg=themes&theme='.$layoutTheme -> {$layoutTheme -> entity}['id'].'&add_block=1';
            }
            $options = array('share' => false);
            $filesystemIterator = new MagesterFileOnlyFilterIterator(new MagesterNodeFilterIterator(new ArrayIterator($filesystem -> tree)));
            foreach ($filesystemIterator as $key => $value) {
       $value['id'] == -1 ? $identifier = $value['path'] : $identifier = $value['id'];
     $value -> offsetSet(_INSERT, '<img src = "images/16x16/arrow_right.png" alt = "'._INSERTEDITOR.'" title = "'._INSERTEDITOR.'" class = "ajaxHandle" onclick = "insert_editor(this, $(\'span_'.urlencode($identifier).'\').innerHTML)" />');
   }
   $extraColumns = array(_INSERT);
            //$extraFileTools = array(array('image' => 'images/16x16/arrow_right.png', 'title' => _INSERTEDITOR, 'action' => 'insert_editor'));
            include 'file_manager.php';

        } catch (Exception $e) {
         handleNormalFlowExceptions($e);
        }


        //These are the entities that will be automatically replaced in custom header/footer
        $systemEntities = array('logo.png',
            '#siteName',
           '#siteMoto',
           '#languages',
           '#path',
            '#version');
        $smarty -> assign("T_SYSTEM_ENTITIES", $systemEntities);
        //And these are the replacements of the above entities
        $systemEntitiesReplacements = array('{$T_LOGO}',
                    '{$T_CONFIGURATION.site_name}',
           '{$T_CONFIGURATION.site_motto}',
           '{$smarty.capture.header_language_code}',
           '{$title}',
                 '{$smarty.const.G_VERSION_NUM}');

        $load_editor = true;

        $layout_form = new HTML_QuickForm("add_block_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=themes&theme=".$layoutTheme -> {$layoutTheme -> entity}['id'].(isset($_GET['edit_block']) ? '&edit_block='.$_GET['edit_block'] : '&add_block=1'), "", null, true);
        $layout_form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');

        $layout_form -> addElement('text', 'title', _BLOCKTITLE, 'class = "inputText"');
        $layout_form -> addElement('textarea', 'content', _BLOCKCONTENT, 'id="editor_data" class = "mceEditor" style = "width:100%;height:300px;"');
        $layout_form -> addElement('submit', 'submit_block',_SAVE, 'class = "flatButton"');
        $layout_form -> addRule('title', _THEFIELD.' "'._BLOCKTITLE.'" '._ISMANDATORY, 'required', null, 'client');

        if (isset($_GET['edit_block'])) {
            $customBlocks[$_GET['edit_block']]['content'] = file_get_contents($basedir.$customBlocks[$_GET['edit_block']]['name'].'.tpl');
            $layout_form -> setDefaults($customBlocks[$_GET['edit_block']]);
            $layout_form -> freeze(array('name'));
        }

        if ($layout_form -> isSubmitted() && $layout_form -> validate()) {
            $values = $layout_form -> exportValues();
            if (isset($_GET['edit_block'])) { // not rename blocks by editing. It created many unused files
             $values['name'] = $customBlocks[$_GET['edit_block']]['name'];
            } else {
             $values['name'] = time(); //Use the timestamp as name
            }
            $block = array('name' => $values['name'],
                     'title' => $values['title']);
            file_put_contents($basedir.$values['name'].'.tpl', $values['content']);

            if (isset($_GET['edit_block'])) {
                $customBlocks[$_GET['edit_block']] = $block;
            } else {
                sizeof($customBlocks) > 0 ? $customBlocks[] = $block : $customBlocks = array($block);
            }
            $layoutTheme -> layout['custom_blocks'] = $customBlocks;
            $layoutTheme -> persist();

            eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=themes&theme=".$layoutTheme -> {$layoutTheme -> entity}['id']);
        }

        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $layout_form -> accept($renderer);
        $smarty -> assign('T_ADD_BLOCK_FORM', $renderer -> toArray());

    } else {
        $form = new HTML_QuickForm("import_settings_form", "post", basename($_SERVER['PHP_SELF']).'?ctg=themes&theme='.$layoutTheme -> {$layoutTheme -> entity}['id'], "", null, true);

        $form -> addElement('file', 'file_upload', _SETTINGSFILE, 'class = "inputText"'); //Lesson file
        $form -> setMaxFileSize(FileSystemTree :: getUploadMaxSize() * 1024); //getUploadMaxSize returns size in KB
        $form -> addElement('submit', 'submit_import', _SUBMIT, 'class = "flatButton"');

        $smarty -> assign("T_MAX_FILESIZE", FileSystemTree :: getUploadMaxSize());

        if ($form -> isSubmitted() && $form -> validate()) {
            try {
                $values = $form -> exportValues();
                $filesystem = new FileSystemTree(G_EXTERNALPATH);
                $uploadedFile = $filesystem -> uploadFile('file_upload', G_EXTERNALPATH);
                $uploadedFile -> uncompress();
                $uploadedFile -> delete();

                $settings = file_get_contents(G_EXTERNALPATH.'layout_settings.php.inc');
                if ($settings = unserialize($settings)) {
                    $layoutTheme -> layout = $settings;
                    $layoutTheme -> persist();
                }

                eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=themes&theme=".$layoutTheme -> {$layoutTheme -> entity}['id']."&message=".rawurlencode(_SETTINGSIMPORTEDSUCCESFULLY)."&message_type=success");
                //$message      = _SETTINGSIMPORTEDSUCCESFULLY;
                //$message_type = 'success';
            } catch (Exception $e) {
             handleNormalFlowExceptions($e);
            }
        }

        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $form -> accept($renderer);
        $smarty -> assign('T_IMPORT_SETTINGS_FORM', $renderer -> toArray());


        $blocks = array('login' => _LOGINENTRANCE,
                        'online' => _USERSONLINE,
                        'lessons' => _LESSONS,
                        'selectedLessons' => _SELECTEDLESSONS,
                        'news' => _SYSTEMNEWS,
                        'links' => _MENU);

        foreach ($customBlocks as $key => $block) {
            $blocks[$key] = htmlspecialchars($block['title'], ENT_QUOTES);
        }
        $smarty -> assign("T_BLOCKS", json_encode($blocks));
        $currentPositions = $layoutTheme -> layout['positions'] ? $layoutTheme -> layout['positions'] : false;
        $smarty -> assign("T_POSITIONS", json_encode($currentPositions));
//pr($layoutTheme);
//pr($layoutTheme -> layout);
        try {
            if (isset($_GET['ajax']) && $_GET['ajax'] == 'set_layout') {

                parse_str($_POST['leftList']);
                parse_str($_POST['centerList']);
                parse_str($_POST['rightList']);

                !isset($leftList) ? $leftList = array() : null;
                !isset($centerList) ? $centerList = array() : null;
                !isset($rightList) ? $rightList = array() : null;

                array_pop($leftList);array_pop($rightList);array_pop($centerList); //Remove emmpty values, that are the 'bogus' li element

                $layoutTheme -> layout['positions']['leftList'] = $leftList;
                $layoutTheme -> layout['positions']['centerList'] = $centerList;
                $layoutTheme -> layout['positions']['rightList'] = $rightList;
                $layoutTheme -> layout['positions']['layout'] = $_POST['layout'];

                $layoutTheme -> persist();

                echo "set";
                exit;
            } elseif (isset($_GET['ajax']) && $_GET['ajax'] == 'reset_layout') {
                $layoutTheme -> applySettings('layout');
                echo "reset";
                exit;
            } elseif (isset($_GET['delete_block'])) {
                //Remove the block's file
                if (is_file($file = G_EXTERNALPATH.$customBlocks[$_GET['delete_block']]['name'].'.tpl')) {
                 $file = new MagesterFile($file);
                 $file -> delete();
                }

                //Remove the block from the custom blocks list
                unset($customBlocks[$_GET['delete_block']]);
                $layoutTheme -> layout['custom_blocks'] = $customBlocks;

                //Remove the deleted block from any position it may occupy
                foreach ($layoutTheme -> layout['positions'] as $key => $value) {
                 if (is_array($value) && ($offset = array_search($_GET['delete_block'], $value)) !== false) {
                     array_splice($layoutTheme -> layout['positions'][$key], $offset, 1);
                 }
                }

                $layoutTheme -> persist();
                exit;
            } elseif (isset($_GET['toggle_block'])) {
                if (isset($layoutTheme -> layout['positions']['enabled'][$_GET['toggle_block']])) {
                   unset($layoutTheme -> layout['positions']['enabled'][$_GET['toggle_block']]);
                   echo json_encode(array('enabled' => false));
                } else {
                    $layoutTheme -> layout['positions']['enabled'][$_GET['toggle_block']] = true;
                    echo json_encode(array('enabled' => true));
                }
                //pr($layoutTheme -> layout['positions']);
                $layoutTheme -> persist();
                //pr($_GET['toggle_block']);
                exit;
            } elseif (isset($_GET['export_layout'])) {
                file_put_contents(G_EXTERNALPATH.'layout_settings.php.inc', serialize($layoutTheme -> layout));

                $directory = new MagesterDirectory(G_EXTERNALPATH);
                $tempDir = $currentUser -> getDirectory().'temp/';
                if (!is_dir($tempDir) && !mkdir($tempDir, 0755)) {
                    throw new MagesterFileException(_COULDNOTCREATEDIRECTORY.': '.$tempDir, MagesterFileException :: CANNOT_CREATE_DIR);
                }
                //pr($tempDir.'layout.zip');debug();
                $file = $directory -> compress(false, false);
                $file -> rename($tempDir.$layoutTheme -> {$layoutTheme -> entity}['name'].'_layout.zip', true);

                echo json_encode(array('file' => $file['path']));
                exit;
            }
        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }
    }

    //Themes list and add/edit/delete operations
    $smarty -> assign("T_THEMES", $themes);
    $smarty -> assign("T_CURRENT_THEME", $currentSetTheme);
    $smarty -> assign("T_MAX_FILESIZE", FileSystemTree :: getUploadMaxSize());
    $smarty -> assign("T_BROWSERS", themes :: $browsers);

    $entityName = 'themes';
    require 'entity.php';

    if (isset($_GET['set_browser']) && in_array($_GET['set_browser'], $legalValues) && isset($_GET['browser']) && in_array($_GET['browser'], array_keys(themes :: $browsers))) {
        try {
            $theme = new themes($_GET['set_browser']);
            foreach ($themes as $key => $value) {
                $value = new themes($value['id']);
                unset($value -> options['browsers'][$_GET['browser']]);
                $value -> persist();
            }
            $theme -> options['browsers'][$_GET['browser']] = 1;
            $theme -> persist();
            $url = '';
            if (detectBrowser() == $_GET['browser']) {
             if ($theme -> options['sidebar_interface'] > 0) {
              $url = basename($_SERVER['PHP_SELF']).'?ctg=themes';
             } else {
                 $url = basename($_SERVER['PHP_SELF'], '.php').'page.php?ctg=themes';
             }
            }
            echo json_encode(array('status' => 1, 'browser' => $_GET['browser'], 'url' => $url));

        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }
        exit;
    }
    if (isset($_GET['set_theme']) && in_array($_GET['set_theme'], $legalValues)) {
        try {
            $cacheTree = new FileSystemTree(G_THEMECACHE, true);
            foreach (new MagesterDirectoryOnlyFilterIterator($cacheTree -> tree) as $value) {
                $value -> delete();
            }
            MagesterConfiguration::setValue('theme', $_GET['set_theme']);
            foreach ($themes as $key => $value) {
                $value = new themes($value['id']);
                unset($value -> options['browsers']);
                $value -> persist();
            }
            $theme = new themes($_GET['set_theme']);
            if ($theme -> options['sidebar_interface'] > 0) {
                echo basename($_SERVER['PHP_SELF']).'?ctg=themes';
            } else {
                echo basename($_SERVER['PHP_SELF'], '.php').'page.php?ctg=themes';
            }

            if (!isset($_GET['ajax'])) {
                eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=themes");
            }

        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }
        exit;
    }
    if (isset($_GET['reset_theme']) && $_GET['reset_theme'] == $currentSetTheme -> {$currentSetTheme -> entity}['id']) {
        try {
            $currentSetTheme -> applySettings();
        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }
        exit;
    }
    if (isset($_GET['export_theme']) && in_array($_GET['export_theme'], $legalValues)) {
        try {
            $theme = new themes($_GET['export_theme']);
            if ($theme -> options['locked']) {
                throw new MagesterThemesException(_THEMELOCKED, MagesterThemesException::THEME_LOCKED);
            }
            $file = $theme -> export();
            echo $file['path'];
            //$theme -> applySettings();
            //MagesterConfiguration::setValue('theme', $_GET['set_theme']);
        } catch (Exception $e) {
         handleAjaxExceptions($e);
        }
        exit;
    }
} catch (Exception $e) {
 handleNormalFlowExceptions($e);
}
