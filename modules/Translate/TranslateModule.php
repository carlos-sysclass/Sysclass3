<?php
namespace Sysclass\Modules\Translate;

use Sysclass\Models\I18n\Language,
    Sysclass\Models\I18n\Tokens,
    Sysclass\Models\I18n\CountriesStatic as Countries,
    Phalcon\Mvc\Model\Resultset,
    Sysclass\Services\Queue\AsyncCall;

/**
 * @RoutePrefix("/module/translate")
 */
class TranslateModule extends \SysclassModule implements \IBlockProvider, \ISectionMenu, \ILinkable, \IBreadcrumbable, \IActionable
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'translate.edit.dialog' => function($data, $self) {
                $self->putComponent("modal");
                $self->putModuleScript("dialog.translate.edit");
                $self->putSectionTemplate("foot", "dialogs/edit.token");

                return true;

            },
            'translate.page.editor' => function($data, $self) {
                $self->putModuleScript("translate.page.editor");

                return true;
            }
        );
    }

    /* ISectionMenu */
    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {
            if ($this->acl->isUserAllowed($this->user, "translate", "edit")) {
                $menuItem = array(
                    //'id'        => "enroll-topbar-menu",
                    'icon'      => ' fa fa-globe',
                    'text'      => $this->translate->translate('Translation'),
                    'className' => 'btn-info',
                    'link' => $this->getBasePath() . "view/token",
                    'type'      => '',
                );

                return $menuItem;
            }
        }
        return false;
    }

    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, "Translate", "View")) {
            $count = Language::count();

            return array(
                'administration' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Languages'),
                        'icon'  => 'fa fa-globe',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-globe',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Languages")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text' => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text' => $this->translate->translate("New language"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array('text' => $this->translate->translate("Edit language"));
                break;
            }
            case "view/token" : {
                $breadcrumbs[] = array('text' => $this->translate->translate("View translations"));
            }
        }
        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        /**
          * @todo Create and load the module actions from config.yml (actions put on side bar and datatables)
         */
        $actions = [
            'translate-create' => [
                'text'  => $this->translate->translate('Add language'),
                'link'  => $this->getBasePath() . "add",
                'class' => 'btn-primary',
                'icon'  => 'fa fa-plus-square'
            ]/*,
            'translate-token-edit' => [
                'text'  => $this->translate->translate("Edit translation"),
                'link'  => $this->getBasePath() . "view/token",
                'icon'  => 'icon-reorder'
            ],
            'separator' => [
                'separator' => true
            ]
            */
        ];

        $barActions = array(
            'view'  => [],
            'view/token' => []
        );

        if ($this->acl->isUserAllowed($this->user, "translate", "create")) {
            $barActions['view'][] = $actions['translate-create'];
            $barActions['view/token'][] = $actions['translate-create'];
        }
        /*
        if ($this->acl->isUserAllowed($this->user, "translate", "edit")) {
            if (count($barActions['view']) > 0) {
                $barActions['view'] = $action['separator'];
            }
            $barActions['view'][] = $actions['translate-token-edit'];
        }
        */
        return $barActions[$request];
    }


    /**
     * [ add a description ]
     *
     * @Get("/session_tokens")
     */
    public function getSessionTokensRequest()
    {
        $this->response->setContentType('application/json', 'UTF-8');
        $session_tokens = $this->translate->getTranslatedTokens();

        $this->response->setJsonContent(array(
            'srclang' => $this->translate->getSource(),
            'tokens' => $session_tokens
        ));
    }

    


    /**
     * [ add a description ]
     *
     * @Put("/change/{language_code}")
     */
    public function changeLanguageRequest($language_code)
    {
       // REDIRECT USER BY JAVASCRIPT.
        $this->session->set("session_language", $language_code);
        $this->response->setJsonContent($this->createRedirectResponse(null));
        return true;
    }

    /**
     * View Translations Tables
     *
     * @url GET /view
     */
    /*
    public function viewPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        // SHOW ANNOUCEMENTS BASED ON USER TYPE
        //if ($currentUser->getType() == 'administrator') {
            $this->putItem("page_title", $this->translate->translate('Languages'));
            $this->putItem("page_subtitle", $this->translate->translate('View system languages'));

            $this->putComponent("select2");
            $this->putComponent("data-tables");

            $this->putModuleScript("models.translate");
            $this->putModuleScript("views.translate.view");
            $this->putBlock("translate.edit.dialog");

            $languages = $this->translate->getItems();

            $this->putItem("languages", $languages);

            $this->putData(array(
                'user_language'     => $this->translate->getUserLanguageCode(),
                'system_language'   => $this->translate->getSystemLanguageCode()
            ));

            $this->display("view.tpl");
        //} else {
        //    $this->redirect($this->getSystemUrl('home'), "", 401);
        //}
    }
    */
    /**
     * Add a new language translation
     *
     * @Get("/add")
     */
    public function addPage()
    {
        $country_codes = Countries::find();
        $this->putItem("country_codes", $country_codes->toArray());

        $bingTranslationsCodes = $this->model("bing/translate")->getTranslationsNames();
        $this->putItem("language_codes", $bingTranslationsCodes);

        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     */
    
    public function editPage($id)
    {
        $country_codes = Countries::find();
        $this->putItem("country_codes", $country_codes->toArray());

        $bingTranslationsCodes = $this->model("bing/translate")->getTranslationsNames();
        $this->putItem("language_codes", $bingTranslationsCodes);
        
        parent::editPage($id);
    }

    public function afterModelCreate($evt, $model, $data) {
        $task = new AsyncCall("translate", "translateTokens", array(
            $this->translate->getSystemLanguageCode(),
            $model->code
        ));
        $this->queue->send($task);
        return true;
    }

    public function afterModelUpdate($evt, $model, $data) {
        $task = new AsyncCall("translate", "translateTokens", array(
            $this->translate->getSystemLanguageCode(),
            $model->code
        ));
        $this->queue->send($task);
        return true;
    }
    /**
     * Get all translation visible to the current user
     *
     * @url GET /item/me/:id
     */
    /*
    public function getItemAction($id) {

        $editItem = $this->model("translate")->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        return $editItem;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    /*
    public function addItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("translate");
            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    $this->translate->translate("Language saved."),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("It was not possible to complete your request. Invalid data."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url PUT /item/me/:id
     */
    /*
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("translate");
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Language updated."), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("It was not possible to complete your request. Invalid data."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */


    /**
     * [ add a description ]
     *
     * @Get("/view/token")
     */
    public function viewTokensPage()
    {
        $currentUser    = $this->getCurrentUser(true);

        // SHOW ANNOUCEMENTS BASED ON USER TYPE
        //if ($currentUser->getType() == 'administrator') {
            $this->putItem("page_title", $this->translate->translate('Translations'));
            $this->putItem("page_subtitle", $this->translate->translate('Review translation'));

            //$this->putComponent("bootbox");

            $this->putComponent("select2");
            $this->putComponent("data-tables");



            $this->putModuleScript("models.translate");
            $this->putModuleScript("views.translate.view.token");
            $this->putBlock("translate.edit.dialog");

            $languages = Language::find();

            $this->putItem("languages", $languages->toArray());

            $this->putData(array(
                'user_language'     => $this->translate->getSource(),
                'system_language'   => $this->translate->getSystemLanguageCode()
            ));

            $this->display("view.token.tpl");
        //} else {
        //    $this->redirect($this->getSystemUrl('home'), "", 401);
        //}
    }

    /**
     * [ add a description ]
     *
     * @Get("/refresh")
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
        $systemLang = $this->translate->getSystemLanguageCode();
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
     * @Get("/tt/{from}/{to}")
     */
    public function doTranslateRequest($from, $to) {
        // TODO CREATE MULTIPLE TRANSLATIONS BACKENDS
        $langCodes = $this->model("translate")->getDisponibleLanguagesCodes();

        if (in_array($from, $langCodes) && in_array($to, $langCodes)) {
            // VALIDATE TOKEN
            $translatedTerm = $this->translate->translateText($from, $to, $_GET['st']);

            $tokensModel = new Tokens();

            $tokensModel->assign(array(
                'token'         => $_GET['st'],
                'text'          => (string) $translatedTerm,
                'language_code' => $to,
                'edited'        => 0,
            ));

            //$tokensModel->save();

            return $tokensModel->toArray();

        } else {
            return $this->invalidRequestError();
        }
    }

    /**
     * Translate a simple term on desired backend
     *
     * @Get("/ttall/{from}/{to}")
     * @Get("/ttall/{from}/{to}/{force}")
     */
    public function doTranslateAllRequest($from, $to, $force = true) {
        if ($force === 'false' || $force === "0") {
            $force = false;
        } else {
            $force = true;
        }

        // TODO CREATE MULTIPLE TRANSLATIONS BACKENDS
        $langCodes = $this->model("translate")->getDisponibleLanguagesCodes();

        if (in_array($from, $langCodes) && in_array($to, $langCodes)) {
            $sourcesTokens = Tokens::find(array(
                'columns' => 'text',
                'conditions' => "language_code = ?0 AND token NOT IN (
                    SELECT token FROM Sysclass\Models\I18n\Tokens WHERE 
                        language_code = ?1 AND 
                        (edited = 1 OR (UNIX_TIMESTAMP() - timestamp) < 300)
                )",
                'bind' => array($from, $to)
            ));
            $sourcesTokens = array_column($sourcesTokens->toArray(), 'text');

            $translatedTerms = $this->translate->translateTokens($from, $to, $sourcesTokens);

            foreach($translatedTerms as $token => $term) {
                $tokensModel = new Tokens();

                $tokensModel->assign(array(
                    'token'         => $token,
                    'text'          => $term,
                    'language_code' => $to
                ));

                $tokensModel->save();
                // ADD THIS TOKEN
            }
            $response = $this->createAdviseResponse($this->translate->translate("Translation from '%s' to '%s' completed.", array($from, $to)), "success");

            $response['data'] = $translatedTerms;

            return $response;
        } else {
            return $this->invalidRequestError();
        }
    }

    /**
     * Get all tokens processed by the system
     *
     * @Post("/datasource/token")
     */
    public function insertTokenTranslationRequest()
    {
        $data = $this->getHttpData();

        // TODO CHECK PERMISSIONS
        $langCodes = $this->model("translate")->getDisponibleLanguagesCodes();

        if (in_array($data['language_id'], $langCodes)) {
            //var_dump($data);
            $tokensModel = new Tokens();

            $tokensModel->assign(array(
                'token'         => $data['token'],
                'text'          => $data['text'],
                'language_code' => $data['dstlang'],
                'edited'        => 1
            ));

            return $this->createAdviseResponse($this->translate->translate("Translation saved."), "success");

        }
        return $this->invalidRequestError();
    }

    /**
     * Get all languages provided by the system
     *
     * @Get("/datasources/me")
     * @Get("/datasources/me/{datatable}")
     */
    /*
    public function getItemsRequest($datatable)
    {
        //$currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        //$newsItens = $this->model("news")->getItems();

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
                    ),
                    'remove'  => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    )
                );
            }

            $result = array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($itemsData),
                'iTotalDisplayRecords'  => count($itemsData),
                'aaData'                => array_values($itemsData)
            );

            $this->response->setJsonContent($result);
            return true;
        }
        $this->response->setJsonContent(array_values($itemsData));
        return true;
    }
    */

    // TODO MOVE THIS FUNCTION TO FRAMWORK (ALIAS TO {Plico_GetResource file=""})

    /**
     * Get all tokens processed by the system
     *
     * @Get("/datasources/token")
     * @Get("/datasources/token/{datatable}")
     */
    public function getItemsTokenRequest($datatable)
    {
        //$currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        //$newsItens = $this->model("news")->getItems();

        $tokensModel = $this->model("translate/tokens");

        $itemsData = $tokensModel->getItemsGroupByToken();

        if ($datatable === 'datatable') {
            $itemsData = array_values($itemsData);
            foreach($itemsData as $key => $item) {
                $itemsData[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'fa fa-pencil', 
                        //'link'  => "#translate-edit-token-modal",
                        'class' => 'btn-sm btn-primary tooltips',
                        'attrs'  => array(
                            "data-placement"        => "top",
                            'data-original-title'   => "Edit"
                        )
                    ),
                    'translate-windows'  => array(
                        'icon'  => 'fa fa-reorder',
                        //'link'  => "#translate-edit-token-modal",
                        'class' => 'btn-sm btn-info tooltips',
                        'attrs'  => array(
                            "data-placement"        => "top",
                            'data-original-title'   => "Translation"
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
