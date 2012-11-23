<?php

class module_billboard extends MagesterExtendedModule
{
    // Mandatory functions required for module function
    public function getName()
    {
        return "BILLBOARD";
    }

    public function getPermittedRoles()
    {
        return array("professor","student");
    }

    public function isLessonModule()
    {
        return true;
    }

    public function loadMainBillboardBlock($blockIndex = null)
    {
        // Get smarty variable
        $smarty = $this -> getSmartyVar();
		$currentUser = $this->getCurrentuser();
    	$xuserModule = $this->loadModule("xuser");

    	if ($xuserModule->getExtendedTypeID($currentUser) == 'polo') {
    		return false;
    	}

		$courseIds == array();
		foreach ($currentUser->getUserCourses() as $courseObject) {
			$courseIds[] = $courseObject->course['id'];
		}

        $billboard = eF_getTableData("module_billboard", "*", "lessons_ID = -2");

		if (count($courseIds) > 0) {
			$billboard = eF_getTableData("module_billboard", "*", sprintf("course_id IN (%s)", implode(", ", $courseIds)));
			if ($currentCourse = $this -> getCurrentCourse()) {
				if (in_array($currentCourse->course['id'], $currentCourse)) {
					$billboard = eF_getTableData("module_billboard", "*", "course_id = " . $currentCourse->course['id']);
				}
			}
		}
        // Only professors may edit
/*
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> user['user_type']== 'professor') {
            $inner_table_options = array(array('text' => _BILLBOARD_EDITBILLBOARD,   'image' => $this -> moduleBaseLink."images/edit.png", 'href' => $this -> moduleBaseUrl));
            $smarty -> assign("T_BILLBOARD_INNERTABLE_OPTIONS", $inner_table_options);
        }
*/
		if (sizeof($billboard) > 0) {
            $smarty -> assign("T_BILLBOARD_DATA", $billboard);
        } else {
          //  $smarty -> assign("T_BILLBOARD_INNERTABLE", '<table width="400px"><tr><td class = "emptyCategory">'._BILLBOARD_EMPTY.'</td></tr></table>');
        }

		$this->getParent()->appendTemplate(array(
			'title'			=> __BILLBOARD_TITLE,
			'template'		=> $this->moduleBaseDir . 'templates/blocks/billboard.main_billboard.tpl',
			'contentclass'	=> 'blockContents'
	   	), $blockIndex);

	   	$this->injectJS("jwplayer/jwplayer");

		foreach ($billboard as $item) {
		   	if (!empty($item['scripts'])) {
		   		$this->injectScript(
		   			$item['scripts']
		   		);
			}
		}

	   	$this->assignSmartyModuleVariables();

        return true;
    }

    // Optional functions
    // What should happen on installing the module
    public function onInstall()
    {
        eF_executeNew("drop table if exists module_billboard");

        return eF_executeNew("CREATE TABLE module_billboard (
                          lessons_ID int(11) not null,
                          data longtext default NULL,
                          PRIMARY KEY  (lessons_ID)
                        ) DEFAULT CHARSET=utf8;");
    }

    // And on deleting the module
    public function onUninstall()
    {
        return eF_executeNew("DROP TABLE module_billboard;");
    }

    // On deleting a lesson
    public function onDeleteLesson($lessonId)
    {
        return eF_deleteTableData("module_billboard", "lessons_ID=".$lessonId);
    }

    // On exporting a lesson
    public function onExportLesson($lessonId)
    {
        $data = eF_getTableData("module_billboard", "*","lessons_ID=".$lessonId);

        return $data;
    }

    // On importing a lesson
    public function onImportLesson($lessonId, $data)
    {
        // Change all external content links to the folder of the newly imported lesson
        if (strpos($data[0]['data'],"lessons/".$data[0]['lessons_ID']."/")) {
            $data[0]['data'] = str_replace("lessons/".$data[0]['lessons_ID']."/", "lessons/".$lessonId."/", $data[0]['data']."/");
        } elseif (strpos($data[0]['data'],"lessons\\".$data[0]['lessons_ID']."\\")) {
            $data[0]['data'] = str_replace("lessons\\".$data[0]['lessons_ID']."\\", "lessons\\".$lessonId."\\", $data[0]['data']."\\");
        }
        $data[0]['lessons_ID'] = $lessonId;
        eF_insertOrupdateTableData("module_billboard",$data[0], "lessons_ID=$lessonId");

        return true;
    }

    public function getLessonCenterLinkInfo()
    {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
            return array('title' => _BILLBOARD,
                         'image' => $this -> moduleBaseDir . 'images/note_pinned32.png',
                         'link'  => $this -> moduleBaseUrl);
        }
    }

    public function getSidebarLinkInfo()
    {
        $currentUser = $this -> getCurrentUser();

        if ($currentUser -> getRole($this -> getCurrentLesson()) == 'professor') {
            $link_of_menu_clesson = array (array ('id' => 'main_link_id',
                                                  'title' => _BILLBOARD,
                                                  'image' => $this -> moduleBaseDir . 'images/note_pinned16',
                                                  '_magesterExtensions' => '1',
                                                  'link'  => $this -> moduleBaseUrl));

            return array ( "current_lesson" => $link_of_menu_clesson);
        }
    }

    public function getNavigationLinks()
    {
		$currentLesson = $this -> getCurrentLesson();
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == 'professor') {
            return array (	array ('title' => _MYLESSONS, 'onclick'  => "location='professor.php?ctg=lessons';top.sideframe.hideAllLessonSpecific();"),
							array ('title' => $currentLesson -> lesson['name'], 'link'  => "professor.php?ctg=control_panel"),
							array ('title' => _BILLBOARD, 'link'  => $this -> moduleBaseUrl));
        }
    }

    public function getLinkToHighlight()
    {
        return 'main_link_id';
    }

    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule()
    {
        // Get smarty variable
        $smarty = $this -> getSmartyVar();

        //This could become a module function...
        global $load_editor;
        $load_editor = true;
        //$smarty -> assign("T_HEADER_EDITOR", $load_editor);

        $form = new HTML_QuickForm("billboard_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
        $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');                   //Register this rule for checking user input with our function, eF_checkParameter

        $form -> addElement('textarea', 'data', _BILLBOARDCONTENT, 'class = "inputContentTextarea mceEditor" style = "width:100%;height:300px;"');      //The unit content itself
        $form -> addElement('submit', 'submit_billboard', _SUBMIT, 'class = "flatButton"');

        $currentLesson = $this -> getCurrentLesson();
        $currentUser = $this -> getCurrentUser();

        if (is_null($currentLesson)) {
        	$billboardID = -1;
        	$this->loadModule("xuser");

        	if ($this->modules['xuser']->getExtendedTypeId($currentUser) != 'professor') {
        		$this -> setMessageVar(__BILLBOARD_YOU_DONT_HAVE_PERMISSION_TO_CHANGE, 'failure');
        		return false;
        	}

        } else {
        	$billboardID = $currentLesson -> lesson['id'];
        }

        $billboard = eF_getTableData("module_billboard", "*", "lessons_ID=".$billboardID);
        $form -> setDefaults(array('data' => $billboard[0]['data']));

        if ($form -> isSubmitted() && $form -> validate()) {
            $fields = array('lessons_ID' => $billboardID,
                            'data'     => $form -> exportValue('data'));

            if ($billboard[0]['data'] != "") {
                if (eF_updateTableData("module_billboard", $fields, "lessons_ID=".$billboardID)) {
                    eF_redirect("professor.php?ctg=control_panel&message=".urlencode(_BILLBOARD_SUCCESFULLYUPDATEDBILLBOARDENTRY)."&message_type=success");
                } else {
                    $this -> setMessageVar(_BILLBOARD_PROBLEMUPDATINGBILLBOARDENTRY, 'failure');
                }
            } else {
                if (eF_insertTableData("module_billboard", $fields)) {
                    eF_redirect("professor.php?ctg=control_panel&message=".urlencode(_BILLBOARD_SUCCESFULLYUPDATEDBILLBOARDENTRY)."&message_type=success");
                    //eF_redirect("".$this -> moduleBaseUrl."&message="._BILLBOARD_SUCCESFULLYINSERTEDBILLBOARDENTRY."&message_type=success");
                } else {
                    $this -> setMessageVar(_BILLBOARD_PROBLEMINSERTINGBILLBOARDENTRY, 'failure');
                }
            }
        }

        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $form -> accept($renderer);
        $smarty -> assign('T_BILLBOARD_FORM', $renderer -> toArray());
        $smarty -> assign("T_BILLBOARD", $billboard[0]['data']);

        return true;

    }

    public function getSmartyTpl()
    {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_BILLBOARD_MODULE_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_BILLBOARD_MODULE_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_BILLBOARD_MODULE_BASELINK", $this -> moduleBaseLink);

        return $this -> moduleBaseDir . "module.tpl";
    }

    /* CURRENT-LESSON ATTACHED MODULE PAGES */
    public function getLessonModule()
    {
        // Get smarty variable
        $smarty = $this -> getSmartyVar();
        $currentLesson = $this -> getCurrentLesson();

        $billboard = eF_getTableData("module_billboard", "*", "lessons_ID=".$currentLesson -> lesson['id']);

        // Only professors may edit
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == 'professor') {
            $inner_table_options = array(array('text' => _BILLBOARD_EDITBILLBOARD,   'image' => $this -> moduleBaseLink."images/edit.png", 'href' => $this -> moduleBaseUrl));
            $smarty -> assign("T_BILLBOARD_INNERTABLE_OPTIONS", $inner_table_options);
        }

        if (sizeof($billboard)) {
            $smarty -> assign("T_BILLBOARD_INNERTABLE", $billboard[0]['data']);
        } else {
            $smarty -> assign("T_BILLBOARD_INNERTABLE", '<table width="400px"><tr><td class = "emptyCategory">'._BILLBOARD_EMPTY.'</td></tr></table>');
        }

        return true;
    }

    public function getLessonSmartyTpl()
    {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_BILLBOARD_MODULE_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_BILLBOARD_MODULE_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_BILLBOARD_MODULE_BASELINK" , $this -> moduleBaseLink);

        $currentUser = $this -> getCurrentUser();

        $smarty -> assign("T_USERLESSONTYPE", $currentUser -> getRole($this -> getCurrentLesson()));

        return $this -> moduleBaseDir . "module_InnerTable.tpl";
    }

    /* CURRENT-LESSON ATTACHED MODULE PAGES */
    public function getDashboardModule()
    {
        // Get smarty variable
        $smarty = $this -> getSmartyVar();
        $currentLesson = $this -> getCurrentLesson();

        $billboard = eF_getTableData("module_billboard", "*", "lessons_ID = -1");

        // Only professors may edit
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> user['user_type']== 'professor') {
            $inner_table_options = array(array('text' => _BILLBOARD_EDITBILLBOARD,   'image' => $this -> moduleBaseLink."images/edit.png", 'href' => $this -> moduleBaseUrl));
            $smarty -> assign("T_BILLBOARD_INNERTABLE_OPTIONS", $inner_table_options);
        }

        if (sizeof($billboard) > 0) {
            $smarty -> assign("T_BILLBOARD_INNERTABLE", $billboard[0]['data']);
        } else {
            $smarty -> assign("T_BILLBOARD_INNERTABLE", '<table width="400px"><tr><td class = "emptyCategory">'._BILLBOARD_EMPTY.'</td></tr></table>');
        }

        return true;
    }

    public function getDashboardSmartyTpl()
    {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_BILLBOARD_MODULE_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_BILLBOARD_MODULE_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_BILLBOARD_MODULE_BASELINK" , $this -> moduleBaseLink);

        $currentUser = $this -> getCurrentUser();

        $smarty -> assign("T_USERLESSONTYPE", $currentUser -> user['user_type']);

        return $this -> moduleBaseDir . "module_InnerTable.tpl";
    }
}
