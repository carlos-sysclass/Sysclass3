<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class TranslateModule extends SysclassModule implements IBlockProvider, ISectionMenu
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'translate.edit.dialog' => function($data, $self) {
                $this->putComponent("modal");
                $this->putModuleScript("dialog.translate.edit");
                $this->putSectionTemplate("foot", "dialogs/edit.token");

                return true;
                
            }
        );
    }

    /* ISectionMenu */
    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {

            $this->putModuleScript("models.translate");
            $this->putModuleScript("menu.translate");


            $currentUser = $this->getCurrentUser();

            $items = self::$t->getItems();

            $userLanguageCode =  self::$t->getUserLanguageCode();

            foreach($items as $key => &$value) {
                if ($value['id'] == $userLanguageCode) {
                    $value['selected'] = true;
                    break;
                }
            }

            $menuItem = array(
                'icon'      => 'globe',
                'notif'     => count($items),
                'link'  => array(
                    'link'  => $this->getBasePath() . "change",
                    'text'  => self::$t->translate('Languages')
                ),
                'type'      => 'language',
                'items'     => $items,
                'extended'  => false
            );

            return $menuItem;
        }
        return false;
    }

    /**
     * Module Entry Point
     *
     * @url PUT /change/:language_id
     */
    public function changeLanguageAction($language_id)
    {
        if (self::$t->setUserLanguageCode($language_id)) {
            // REDIRECT USER BY JAVASCRIPT.
            return $this->createRedirectResponse(null);
        }
        // RETURN A INVALID REQUEST ERROR
        return $this->invalidRequestError();
    }

    /**
     * Module Entry Point
     *
     * @url GET /view
     */
    public function viewPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        // SHOW ANNOUCEMENTS BASED ON USER TYPE
        //if ($currentUser->getType() == 'administrator') {
            $this->putItem("page_title", self::$t->translate('Translate Table'));
            $this->putItem("page_subtitle", self::$t->translate('Review translated terms'));

            $this->putComponent("select2");
            $this->putComponent("data-tables");

            $this->putModuleScript("models.translate");
            $this->putModuleScript("views.translate.view");
            $this->putBlock("translate.edit.dialog");

            $languages = self::$t->getItems();

            $this->putItem("languages", $languages);

            $this->putData(array(
                'user_language'     => self::$t->getUserLanguageCode(),
                'system_language'   => self::$t->getSystemLanguageCode()
            ));
            
            $this->display("view.tpl");
        //} else {
        //    $this->redirect($this->getSystemUrl('home'), "", 401);
        //}
    }

    /**
     * Get all seasons from selected(s) course(s)
     *
     * @url GET /refresh
     */
    public function refreshTokensTable() {
        // GRAB ALL TEMPLATES PATHS AND
        //var_dump($this, $this->theme);
        $directories = $this->getSmarty()->getTemplateDir();
        // GETTING MODULES TEMPLATES
        $plicolib = PlicoLib::instance();
        $coreModDirectories = $plicolib->getArray("path/core-modules");
        $modDirectories = $plicolib->getArray("path/modules");

        $directories = array_merge($directories, $coreModDirectories, $modDirectories);

        $files = array();
        foreach($directories as $dir) {
            $files = array_merge($files, $this->getDirectoryTemplateFiles($dir));
        }

        $tokens = array();
        $systemLang = self::$t->getSystemLanguageCode();
        //echo "<pre>";
        foreach($files as $file) {
            $matches = array();
            preg_match_all(
                "/\{translateToken value=\"([\w[:space:]\.,!?'\(\)]+)\"\}/",
                file_get_contents($file),
                $matches,
                PREG_SET_ORDER
            );
            //var_dump($file);
            if (count($matches) > 0) {

                //var_dump($matches);
                foreach($matches as $match) {
                    
                    $tokens[] = array(
                        'language_id'   => $systemLang,
                        //'filepath'      => $file,
                        'token'          => $match[1],
                        'text'          => $match[1]
                    );
                }
            }
        }
        $tokensModel = $this->model("translate/tokens");
        return $tokensModel->updateSystemTokens($tokens);
     }

     private function getDirectoryTemplateFiles($dir) {
        $files = glob($dir . "*.tpl");
       
        $directories = glob($dir . "*", GLOB_ONLYDIR + GLOB_MARK);
        foreach($directories as $dir) {
            $files = array_merge($files, $this->getDirectoryTemplateFiles($dir));
        }
        return array_values($files);
     }


    /**
     * Get all tokens processed by the system
     *
     * @url POST /item/token
     */
    public function insertTokenTranslationAction()
    {
        $data = $this->getHttpData(func_get_args());

        // TODO CHECK PERMISSIONS
        $langCodes = $this->model("translate")->getDisponibleLanguagesCodes();
        if (in_array($data['language_id'], $langCodes)) {
            //var_dump($data);
            $this->model("translate/tokens")->addToken($data, true);

            return $this->createAdviseResponse(self::$t->translate("Token successfully updated!"), "success");

        }
        return $this->invalidRequestError();
    }

    /**
     * Get all tokens processed by the system
     *
     * @url GET /items/me
     * @url GET /items/me/:datatable
     */
    public function getItemsAction($datatable)
    {
        //$currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        //$newsItens = $this->model("news")->getItems();

        //$news = $this->module("permission")->checkRules($newsItens, "news", 'permission_access_mode');
        $tokensModel = $this->model("translate/tokens");

        $itemsData = $tokensModel->getItemsGroupByToken();
        
        if ($datatable === 'datatable') {
            $itemsData = array_values($itemsData);
            foreach($itemsData as $key => $item) {
                $itemsData[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => "#translate-edit-token-modal",
                        'class' => 'btn-sm btn-primary'/*,
                        'attrs'  => array(
                            'data-toggle' => "modal"
                        )
                        */
                    )
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($itemsData),
                'iTotalDisplayRecords'  => count($itemsData),
                'aaData'                => array_values($itemsData)
            );
        }
        return array_values($itemsData);
    }
}
