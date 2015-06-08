<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class TranslateModule extends SysclassModule implements IBlockProvider, ISectionMenu, ILinkable, IBreadcrumbable, IActionable
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'translate.edit.dialog' => function($data, $self) {
                $self->putComponent("modal");
                $self->putModuleScript("dialog.translate.edit");
                $self->putSectionTemplate("foot", "dialogs/edit.token");

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
                if ($value['code'] == $userLanguageCode) {
                    $value['selected'] = true;
                    break;
                }
            }

            $items[] = array(
                'link'  => $this->getBasePath() . "view/token",
                'text'  => self::$t->translate("Review translation")
            );

            $this->putSectionTemplate("translate-menu", "menu/language.switch");

            $menuItem = array(
                'icon'      => 'globe',
                'notif'     => count($items),
                'link'  => array(
                    'link'  => $this->getBasePath() . "change",
                    'text'  => self::$t->translate('Languages')
                ),
                'type'      => 'language',
                'items'     => $items,
                'extended'  => false,
                'template'  => "translate-menu"
            );

            return $menuItem;
        }
        return false;
    }

    /* ILinkable */
    public function getLinks() {
        $data = $this->getItemsAction();
        //if ($this->getCurrentUser(true)->getType() == 'administrator') {
            return array(
                'administration' => array(
                    array(
                        'count' => count($data),
                        'text'  => self::$t->translate('Languages'),
                        'icon'  => 'fa fa-language',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        //}
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            ),
            array(
                'icon'  => 'icon-globe',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Languages")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text' => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text' => self::$t->translate("New Language"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text' => self::$t->translate("Edit Language"));
                break;
            }
            case "view/token" : {
                $breadcrumbs[] = array('text' => self::$t->translate("View Translations"));
            }
        }
        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'  => self::$t->translate('Add Language'),
                    'link'  => $this->getBasePath() . "add",
                    'icon'  => 'icon-plus'
                ),
                array(
                    'separator' => true
                ),
                array(
                    'text'  => self::$t->translate("Review translation"),
                    'link'  => $this->getBasePath() . "view/token",
                    'icon'  => 'icon-reorder'
                )
            ),
            'view/token'  => array(
                array(
                    'text'  => self::$t->translate('Add Language'),
                    'link'  => $this->getBasePath() . "add",
                    'class' => 'btn-primary',
                    'icon'  => 'icon-plus'
                )
            )
        );



        return $actions[$request];
    }


    /**
     * [ add a description ]
     *
     * @url PUT /change/:language_code
     */
    public function changeLanguageAction($language_code)
    {
        if (self::$t->setUserLanguageCode($language_code)) {
            // REDIRECT USER BY JAVASCRIPT.
            return $this->createRedirectResponse(null);
        }
        // RETURN A INVALID REQUEST ERROR
        return $this->invalidRequestError();
    }

    /**
     * View Translations Tables
     *
     * @url GET /view
     */
    public function viewPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        // SHOW ANNOUCEMENTS BASED ON USER TYPE
        //if ($currentUser->getType() == 'administrator') {
            $this->putItem("page_title", self::$t->translate('Languages'));
            $this->putItem("page_subtitle", self::$t->translate('View system languages'));

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
     * Add a new Language Translation
     *
     * @url GET /add
     */
    public function addPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        $this->putComponent("select2", "validation");

        $country_codes = $this->model("i18n/country")->getItems();
        $this->putItem("country_codes", $country_codes);

        $bingTranslationsCodes = $this->model("bing/translate")->getTranslationsNames();
        $this->putItem("language_codes", $bingTranslationsCodes);

        $this->putModuleScript("models.translate");
        $this->putModuleScript("views.translate.add");

        $this->putItem("page_title", self::$t->translate('Languages'));
        $this->putItem("page_subtitle", self::$t->translate('View system languages'));

        //return array_values($news);
        $this->display("form.tpl");
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        $currentUser    = $this->getCurrentUser(true);

        $editItem = $this->model("translate")->getItem($id);
        // TODO CHECK PERMISSION FOR OBJECT

        $this->putComponent("select2", "validation");

        $country_codes = $this->model("i18n/country")->getItems();
        $this->putItem("country_codes", $country_codes);

        $bingTranslationsCodes = $this->model("bing/translate")->getTranslationsNames();
        $this->putItem("language_codes", $bingTranslationsCodes);

        // TODO CREATE MODULE BLOCKS, WITH COMPONENT, CSS, JS, SCRIPTS AND TEMPLATES LISTS TO INSERT
        // Ex:
        // $this->putBlock("block-name") or $this->putCrossModuleBlock("permission", "block-name")
        $this->putBlock("permission.add");

        $this->putModuleScript("models.translate");
        $this->putModuleScript("views.translate.edit", array('id' => $id));

        $this->putItem("page_title", self::$t->translate('Languages'));
        $this->putItem("page_subtitle", self::$t->translate('View system languages'));

        $this->putItem("form_action", $_SERVER['REQUEST_URI']);
        //$this->putItem("entity", $editItem);

        //return array_values($news);
        $this->display("form.tpl");
    }

    /**
     * Get all translation visible to the current user
     *
     * @url GET /item/me/:id
     */
    public function getItemAction($id) {

        $editItem = $this->model("translate")->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("translate");
            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("Language saved with success"),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("It was not possible to complete your request. Invalid data."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("translate");
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Language updated with success"), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("It was not possible to complete your request. Invalid data."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }



    /**
     * [ add a description ]
     *
     * @url GET /view/token
     */
    public function viewTokensPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        // SHOW ANNOUCEMENTS BASED ON USER TYPE
        //if ($currentUser->getType() == 'administrator') {
            $this->putItem("page_title", self::$t->translate('Translations'));
            $this->putItem("page_subtitle", self::$t->translate('Review translated terms'));

            //$this->putComponent("bootbox");

            $this->putComponent("select2");
            $this->putComponent("data-tables");



            $this->putModuleScript("models.translate");
            $this->putModuleScript("views.translate.view.token");
            $this->putBlock("translate.edit.dialog");

            $languages = self::$t->getItems();

            $this->putItem("languages", $languages);

            $this->putData(array(
                'user_language'     => self::$t->getUserLanguageCode(),
                'system_language'   => self::$t->getSystemLanguageCode()
            ));

            $this->display("view.token.tpl");
        //} else {
        //    $this->redirect($this->getSystemUrl('home'), "", 401);
        //}
    }

    /**
     * [ add a description ]
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
                        'language_code'   => $systemLang,
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
     * Translate a simple term on desired backend
     *
     * @url GET /tt/:from/:to/
     */
    public function doTranslateAction($from, $to) {
        // TODO CREATE MULTIPLE TRANSLATIONS BACKENDS
        $langCodes = $this->model("translate")->getDisponibleLanguagesCodes();

        if (in_array($from, $langCodes) && in_array($to, $langCodes)) {
            // VALIDATE TOKEN
            $bingTranslateModel = $this->model("bing/translate");

            $translatedTerm = $bingTranslateModel->translateText($_GET['st'], $from, $to);

            $data = array(
                "token"         => $_GET['tk'],
                "text"          => (string) $translatedTerm,
                "language_code" => $to,
                "srclang"       => $from,
                "dstlang"       => $to
            );

            return $data;

        } else {
            return $this->invalidRequestError();
        }
    }

    /**
     * Translate a simple term on desired backend
     *
     * @url GET /ttall/:from/:to/
     * @url GET /ttall/:from/:to/:force
     */
    public function doTranslateAllAction($from, $to, $force = true) {
        if ($force === 'false' || $force === "0") {
            $force = false;
        } else {
            $force = true;
        }

        // TODO CREATE MULTIPLE TRANSLATIONS BACKENDS
        $langCodes = $this->model("translate")->getDisponibleLanguagesCodes();

        if (in_array($from, $langCodes) && in_array($to, $langCodes)) {
            // VALIDATE TOKEN
            $bingTranslateModel = $this->model("bing/translate");
            $translateTokensModel = $this->model("translate/tokens");

            // GET ALL TOKENS FROM SRC LANG
            $translateTokens = $translateTokensModel->cache(false)->getAssociativeLanguageTokens($from);
            $translateTokens = array_values($translateTokens);

            //$translateTokens = array_slice($translateTokens, 0 , 5, true);

            $translatedTerms = $bingTranslateModel->translateArray($translateTokens, $from, $to);

            foreach($translatedTerms as $token => $term) {
                $data = array(
                    "token"         => $token,
                    "text"          => $term,
                    "language_code" => $to,
                    "srclang"       => $from,
                    "dstlang"       => $to
                );
                // ADD THIS TOKEN
                $translateTokensModel->addToken($data, $force);

            }
            $response = $this->createAdviseResponse(self::$t->translate("Translation from '%s' to '%s' successfully done!", array($from, $to)), "success");

            $response['data'] = $translatedTerms;
            return $response;
        } else {
            return $this->invalidRequestError();
        }
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

            return $this->createAdviseResponse(self::$t->translate("Translation saved!"), "success");

        }
        return $this->invalidRequestError();
    }

    /**
     * Get all languages provided by the system
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
        $tokensModel = $this->model("translate");

        $itemsData = $tokensModel->getItems();

        if ($datatable === 'datatable') {
            $itemsData = array_values($itemsData);
            foreach($itemsData as $key => $item) {
                $itemsData[$key]['country_code'] = $this->translateHttpResource(sprintf(
                    "img/flags/%s.png",
                    strtolower($item['country_code'])
                ));

                $itemsData[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . "edit/" . $item['id'],
                        'class' => 'btn-sm btn-primary tooltips',
                        'attrs'  => array(
                            "data-placement"        => "top",
                            'data-original-title'   => "Edit Language"
                        )
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

    // TODO MOVE THIS FUNCTION TO FRAMWORK (ALIAS TO {Plico_GetResource file=""})

    /**
     * Get all tokens processed by the system
     *
     * @url GET /items/token
     * @url GET /items/token/:datatable
     */
    public function getItemsTokenAction($datatable)
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
                        //'link'  => "#translate-edit-token-modal",
                        'class' => 'btn-sm btn-primary tooltips',
                        'attrs'  => array(
                            "data-placement"        => "top",
                            'data-original-title'   => "Human Translation"
                        )
                    ),
                    'translate-windows'  => array(
                        'icon'  => 'icon-reorder',
                        //'link'  => "#translate-edit-token-modal",
                        'class' => 'btn-sm btn-info tooltips',
                        'attrs'  => array(
                            "data-placement"        => "top",
                            'data-original-title'   => "Eletronic Translation"
                        )
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
