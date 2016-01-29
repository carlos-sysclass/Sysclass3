<?php
namespace Sysclass\Modules\Users;

use Phalcon\DI,
    Phalcon\Mvc\Model\Message,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\Group,
    Sysclass\Models\Users\UsersGroups,
    Sysclass\Models\I18n\Language,
    Sysclass\Services\I18n\Timezones,
    Sysclass\Services\Authentication\Exception as AuthenticationException;

/**
 * @RoutePrefix("/module/users")
 */
class UsersModule extends \SysclassModule implements \ILinkable, \IBlockProvider, \IBreadcrumbable, \IActionable, \IPermissionChecker, \IWidgetContainer
{
    /*
    public function getSummary() {

        $user = $this->getCurrentUser(true);


        return array(
            'type'  => 'success',
            'count' => '<i class="fa fa-mortar-board"></i>',
            'text'  => "ID : " . str_pad($user->id, 11, "0", STR_PAD_LEFT)
        );
    }
    */
   
    /* ILinkable */
    public function getLinks() {
        if ($this->acl->isUserAllowed(null, $this->module_id, "View")) {

            $total_itens = User::count("active = 1");

            return array(
                'users' => array(
                    array(
                        'count' => $total_itens,
                        'text'  => $this->translate->translate('Users'),
                        'icon'  => 'fa fa-user',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'users.list.table' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("data-tables");
                $self->putScript("scripts/utils.datatables");

                $block_context = $self->getConfig("blocks\\users.list.table\context");
                $self->putItem("users_block_context", $block_context);

                $self->putSectionTemplate("users", "blocks/table");

                return true;

            },
            'users.select.dialog' =>  function($data, $self) {
                if (is_array($data) && array_key_exists('special-filters', $data)) {

                    $self->putItem("load_by_ajax", false);
                    $users = User::specialFind($data['special-filters']);

                    $self->putItem("dialog_user_select_users", $users);


                } else {
                    $self->putItem("load_by_ajax", true);
                }
                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");
                $self->putModuleScript("dialogs.users.select");



                //$block_context = $self->getConfig("blocks\\users.list.table\context");
                //$self->putItem("users_block_context", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/select");

                return true;
            }
        );
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users")
                );
                return $breadcrumbs;
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("New User"));
                return $breadcrumbs;
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit User"));
                return $breadcrumbs;
                break;
            }
        }
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'viewgetBreadcrumb'  => array(
                array(
                    'text'      => $this->translate->translate('New User'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )/*,
                array(
                    'separator' => true,
                ),
                array(
                    'text'      => 'Add New 2',
                    'link'      => $this->getBasePath() . "add",
                    //'class'       => "btn-primary",
                    //'icon'      => 'icon-plus'
                )*/
            )
        );

        return $actions[$request];
    }

	/* IWidgetContainer */
    /**
     * [getWidgets description]
     * @param  array  $widgetsIndexes [description]
     * @return [type]                 [description]
     * @implemen
     */

	public function getWidgets($widgetsIndexes = array()) {
		if (in_array('users.overview', $widgetsIndexes)) {
			$currentUser    = $this->getCurrentUser(true);

			$modules = $this->getModules("ISummarizable");

			//var_dump(array_keys($modules));
			//exit;

            $userDetails = $currentUser->toFullArray(array('Avatars', 'Courses'));

			//$userDetails = MagesterUserDetails::getUserDetails($currentUser->user['login']);
			//$userDetails = array_merge($currentUser->toArray(), $userDetails);

			$data = array();
			$data['user_details'] = $userDetails;
			$data['notification'] = array();

			foreach($modules as $key => $mod) {
				$notif = $mod->getSummary();
				if (is_array($notif)) {
					$data['notification'][$key] = $mod->getSummary();
				}
			}

			$data['notification'] = $this->module("dashboard")
				->sortModules("users.overview.notification.order", $data['notification']);

			$this->putModuleScript("users");

			return array(
				'users.overview' => array(
					'id'        => 'users-panel',
					'type'      => 'users',
					//'title' 	=> 'User Overview',
					'template'	=> $this->template("widgets/overview"),
					'panel'		=> true,
					'data'      => $data
					//'box'       => 'blue'
				)
			);
		}
	}


    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    public function addPage($id)
    {
        $languages = Language::find("active = 1");
        $this->putitem("languages", $languages->toArray());

        $groups = Group::find("active = 1");
        $this->putItem("groups", $groups->toArray());

        parent::addPage();
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     * @allow(resource=users, action=edit)
     */
    public function editPage($id)
    {
        $languages = Language::find("active = 1");
        $this->putitem("languages", $languages->toArray());

        $groups = Group::find("active = 1");
        $this->putItem("groups", $groups->toArray());

        parent::editPage($id);
    }



    public function beforeModelCreate($evt, $model, $data) {
        if (
            array_key_exists('new-password', $data) &&
            !empty($data['new-password'])
        ) {
            // CHECK PASSWORD
            $di = DI::getDefault();

            // DEFINE AUTHENTICATION BACKEND
            $model->password = $this->authentication->hashPassword($data['new-password'], $model);
        }

        if (is_null($userModel->backend)) {
            $model->backend = strtolower($this->configuration->get("default_auth_backend"));
        }

        return true;
    }

    public function afterModelCreate($evt, $model, $data) {
        if (array_key_exists('usergroups', $data) && is_array($data['usergroups']) ) {
            foreach($data['usergroups'] as $group) {
                $userGroup = new UsersGroups();
                $userGroup->user_id = $model->id;
                $userGroup->group_id = $group['id'];
                $userGroup->save();
            }
        }

        return true;
    }


    public function beforeModelUpdate($evt, $model, $data) {
        if (
            array_key_exists('new-password', $data) &&
            array_key_exists('new-password-confirm', $data) &&
            (!empty($data['new-password']) || !empty($data['new-password-confirm']))
        ) {
            if ($data['new-password'] === $data['new-password-confirm']) {
                // CHECK PASSWORD
                if ($this->acl->isUserAllowed(null, "users", "change-password")) {
                    // DEFINE AUTHENTICATION BACKEND
                    if (
                        array_key_exists('old-password', $data) &&
                        !empty($data['old-password']) &&
                        $this->authentication->checkPassword($data['old-password'], $model)
                    ) {
                        $model->password = $this->authentication->hashPassword($data['new-password'], $model);
                    } else {
                        $message = new Message(
                            "Please provide your current password",
                            "password",
                            "warning"
                        );
                        $model->appendMessage($message);

                        return false;
                    }
                }
            } else {
                $message = new Message(
                    "Password confimation does not match",
                    "password",
                    "warning"
                );
                $model->appendMessage($message);

                return false;
            }
        } else {
            // NO PASSWD CHANGE, JUST LET HIM GO.. (BECAUSE ITS UPDATING SOME ANOTHER INFO)
        }

        if (array_key_exists('avatar', $data) && is_array($data['avatar']) ) {
            $userAvatarModel = new \Sysclass\Models\Users\UserAvatar();
            $userAvatarModel->assign($data['avatar']);
            $model->avatar = $userAvatarModel;
        }

        return true;
    }

    public function afterModelUpdate($evt, $model, $data) {
        if (array_key_exists('usergroups', $data) && is_array($data['usergroups']) ) {
            UsersGroups::find("user_id = {$userModel->id}")->delete();
            
            foreach($data['usergroups'] as $group) {
                $userGroup = new UsersGroups();
                $userGroup->user_id = $userModel->id;
                $userGroup->group_id = $group['id'];
                $userGroup->save();
            }
        }
    }

    protected function getDatatableItemOptions() {
        if ($this->request->hasQuery('block')) {
            return array(
                /*
                'check'  => array(
                    'icon'  => 'icon-check',
                    'link'  => $baseLink . 'block/%id$s',
                    'class' => 'btn-sm btn-danger'
                )
                */
                'check'  => array(
                    //'icon'        => 'icon-check',
                    //'link'        => $baseLink . "block/" . $item['id'],
                    //'text'            => $this->translate->translate('Disabled'),
                    //'class'       => 'btn-sm btn-danger',
                    'type'          => 'switch',
                    //'state'           => 'disabled',
                    'attrs'         => array(
                        'data-on-color' => "success",
                        'data-on-text' => $this->translate->translate('YES'),
                        'data-off-color' =>"danger",
                        'data-off-text' => $this->translate->translate('NO')
                    )
                )
            );
        } else {
            return parent::getDatatableItemOptions();
        }
    }

    protected function getDatatableSingleItemOptions($item) {
        if (!$this->request->hasQuery('block') && $item->pending == 1) {
            return array(
                'aprove' => array(
                    'icon'  => 'fa fa-lock',
                    //'link'  => $baseLink . "block/" . $item['id'],
                    'class' => 'btn-sm btn-info datatable-actionable tooltips',
                    'attrs' => array(
                        'data-datatable-action' => "aprove",
                        'data-original-title' => 'Aprove User'
                    )
                )
            );
        }
        return false;
    }

    protected function isUserAllowed($action, $args) {
        $allowed = parent::isUserAllowed($action);
        if (!$allowed) {
            switch($action) {
                case "edit" : {
                    // ALLOW IF THE USER IS UPDATING HIMSELF
                    return $this->_args['id'] == $this->getCurrentUser(true)->id;
                }
            }
        }
        return $allowed;
    }

    /**
     * [ add a description ]
     *
     * @Get("/item/me/{id}")
    */
    /*
    public function getItemRequest($id) {
        $request = $this->getMatchedUrl();

        // TODO: CHECK PERMISSIONS

        if (strpos($request, "groups/") === 0) {
            $editItem = $this->model("users/groups/collection")->getItem($id);
        } else {
            $editItem = \Sysclass\Models\Users\User::findFirstById($id);

            return $editItem->toFullArray(array('Avatars', 'UserGroups'));
        }
        return $editItem;
    }
    */

    /**
     * [ add a description ]
     *
     * @Post("/item/me")
     */
    /*
    public function addItemRequest($id)
    {
        $request = $this->getMatchedUrl();

        if ($userData = $this->getCurrentUser()) {
            //$itemModel = $this->model("user/item");
            // TODO CHECK IF CURRENT USER CAN DO THAT
            $data = $this->getHttpData(func_get_args());
            $userModel = new User();
            $userModel->assign($data);

            $di = DI::getDefault();

            if ($userModel->save()) {
                if (array_key_exists('usergroups', $data) && is_array($data['usergroups']) ) {
                    //UsersGroups::find("user_id = {$userModel->id}")->delete();
                    
                    foreach($data['usergroups'] as $group) {
                        $userGroup = new UsersGroups();
                        $userGroup->user_id = $userModel->id;
                        $userGroup->group_id = $group['id'];
                        $userGroup->save();
                    }
                }


                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $userModel->id,
                    $this->translate->translate("User created with success"),
                    "success"
                );
            } else {
                $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                return array_merge($response, $userModel->toFullArray());
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
   

    /*
    public function beforeModelDelete($evt, $model) {
    }
    */

    /**
     * [ add a description ]
     *
     * @Put("/item/me/{id}")
     */
    /*
    public function setItemRequest($id)
    {
        //$request = $this->getMatchedUrl();
        $ACL = \Phalcon\DI::getDefault()->get("acl");
        $allowed = $ACL->isUserAllowed(null, "users", "edit");

        if ($userModel = $this->getCurrentUser(true)) {
            $data = $this->getHttpData(func_get_args());

            if ($userModel->id == $id || $allowed) {
                if ($userModel->id == $id) {
                } else {
                    $userModel = new \Sysclass\Models\Users\User();
                }
                //unset($data['password']);
                $userModel->assign($data);
                
                // CHECK FOR PASSWORD CHANGING
                if (
                    array_key_exists('new-password', $data) &&
                    array_key_exists('new-password-confirm', $data) &&
                    !empty($data['new-password']) &&
                    !empty($data['new-password-confirm']) &&
                    $data['new-password'] === $data['new-password-confirm']
                ) {
                    // CHECK PASSWORD
                    if ($ACL->isUserAllowed(null, "users", "change-password")) {
                        // DEFINE AUTHENTICATION BACKEND
                        $AUTH = DI::getDefault()->get("authentication");

                        if (
                            array_key_exists('old-password-confirm', $data) &&
                            !empty($data['old-password'])
                        ) {
                            $continue = $AUTH->checkPassword($data['old-password'], $userModel);
                        } else {
                            $continue = true;
                        }

                        $userModel->password = $AUTH->hashPassword($data['new-password'], $userModel);
                    }
                }

                if (array_key_exists('avatar', $data) && is_array($data['avatar']) ) {
                    $userAvatarModel = new \Sysclass\Models\Users\UserAvatar();
                    $userAvatarModel->assign($data['avatar']);
                    $userModel->avatar = $userAvatarModel;
                }
                
                if ($userModel->save()) {
                    if (array_key_exists('usergroups', $data) && is_array($data['usergroups']) ) {
                        UsersGroups::find("user_id = {$userModel->id}")->delete();
                        
                        foreach($data['usergroups'] as $group) {
                            $userGroup = new UsersGroups();
                            $userGroup->user_id = $userModel->id;
                            $userGroup->group_id = $group['id'];
                            $userGroup->save();
                        }
                    }

                    if ($_GET['redirect'] == "1") {
                        $response = $this->createRedirectResponse(
                            null,
                            $this->translate->translate("User updated with success"),
                            "success"
                        );    
                    } else {
                        $response = $this->createAdviseResponse($this->translate->translate("User updated with success"), "success");
                    }
                    return array_merge($response, $userModel->toFullArray('UserGroups'));
                } else {
                    $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warninig");
                    return array_merge($response, $userModel->toFullArray());
                }
            } else {
                return $this->invalidRequestError($this->translate->translate("You don't have the permission to update these info."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */

    /**
     * [ add a description ]
     *
     * @Put("/item/agreement/{id}")
     * @todo MOVE TO AGREEMENT CONTROLLER
     */
    public function setAgreementRequest($id)
    {
        if ($userModel = $this->getCurrentUser(true)) {
            $data = $this->getHttpData(func_get_args());

            //$depinject = Phalcon\DI::getDefault();

            if ($userModel->id == $id || $this->acl->isUserAllowed(null, "Users", "Edit")) {
                if ($userModel->id != $id) {
                    $userModel = User::findFirstById($id);
                }

                $userModel->viewed_license = is_array($data['viewed_license']) ? reset($data['viewed_license']) : $data['viewed_license'];

                // CHECK FOR PASSWORD CHANGING
                if ($userModel->update()) {
                    if ($userModel->viewed_license == 1) {
                        return $this->createRedirectResponse(
                            "/dashboard",
                            $this->translate->translate("You agreed within the license. Thanks for using Sysclass"),
                            "success"
                        );
                    } else {
                        $di = DI::getDefault();
                        $di->get("authentication")->logout($userModel);
                        $message = $this->translate->translate("You cannot access the system before you accepted ther terms of use.");
                        $message_type = 'warning';
                        return $this->createRedirectResponse(
                            "/login", $message, $message_type
                        );
                    }
                } else {

                    $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warninig");
                    return array_merge($response, $userModel->toFullArray());
                }
            } else {
                return $this->invalidRequestError($this->translate->translate("You don't have the permission to update these info."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    

    /**
     * [ add a description ]
     *
     * @Delete("/item/me/{id}")
     */
    /*
    public function deleteItemRequest($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("user/item");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("User removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     * 
     * @Get("/items/me")
     * @Get("/items/me/{type}")
     */
    /*
    public function getItemsRequest($type)
    {

        $currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRS = User::find();
        foreach($modelRS as $key => $item) {
            $items[$key] = $item->toArray();
            //$news[$key]['user'] = $item->getUser()->toArray();;
        }

        if ($type === 'datatable') {
            $items = array_values($items);
            $baseLink = $this->getBasePath();

            foreach($items as $key => $item) {
                // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                if (array_key_exists('block', $_GET)) {
                    $items[$key]['options'] = array(
                        'check'  => array(
                            'icon'  => 'icon-check',
                            'link'  => $baseLink . "block/" . $item['id'],
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                } else {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'icon-edit',
                            'link'  => $baseLink . "edit/" . $item['id'],
                            'class' => 'btn-sm btn-primary'
                        ),

                    );
                    if ($item['pending'] == 1) {
                        $items[$key]['options']['aprove'] = array(
                            'icon'  => 'fa fa-lock',
                            //'link'  => $baseLink . "block/" . $item['id'],
                            'class' => 'btn-sm btn-info datatable-actionable tooltips',
                            'attrs' => array(
                                'data-datatable-action' => "aprove",
                                'data-original-title' => 'Aprove User'
                            )
                        );
                    }

                    $items[$key]['options']['remove'] = array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    );
                }
            }
            $this->response->setContentType('application/json', 'UTF-8');
            $this->response->setJsonContent(array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            ));
            //$this->view->disable();
            //return $response;

            
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }
        return array_values($items);
    }
    */

	/**
	 * [ add a description ]
	 *
     * @Get("/profile")
	 */
	public function profilePage()
	{
		//$this->redirect($this->getSystemUrl("home"), $this->translate->translate("The profile change is disabled on demo enviroment!"), "warning");
		//exit;
        //echo $this->translate->translate("Select...");
        
		$currentUser    = $this->getCurrentUser(true);


        $this->createClientContext("edit", array('entity_id' => $currentUser->id));


		// PUT HERE CHAT MODULE (CURRENTLY TUTORIA)
        /*
		$this->putComponent("select2");
		$this->putComponent("datepicker");
		$this->putComponent("pwstrength");
        
        */
		// GET SUMMARY
		$summaryModules = $this->getModules("ISummarizable");
		$summary = array();
		foreach($summaryModules as $key => $mod) {
			$summary[$key] = $mod->getSummary();
		}
		$summary = $this->module("layout")->sortModules("users.overview.notification.order", $summary);

        //$this->putModuleScript("views.profile");

        $this->putBlock("dropbox.upload");

		//$languages = MagesterSystem :: getLanguages(true, true);
        /*
		$timezones = sC_getTimezones();

		$userDetails = MagesterUserDetails::getUserDetails($currentUser->user['login']);
		$edit_user = array_merge($currentUser->user, $userDetails);

		$date_fmt = $this->module("settings")->get("php_date_fmt");
		$edit_user['data_nascimento'] = date_create_from_format("Y-m-d", $edit_user['data_nascimento'])->format($date_fmt);

		//$userPolo = $currentUser->getUserPolo();

		$constraints = array('active' => true, 'return_objects' => false);
		$constraints['required_fields'] = array('location', 'active_in_course', 'user_type', 'completed', 'score', 'has_course', 'num_lessons');
		//$userCourses = $currentUser->getUserCoursesAggregatingResults($constraints);

		$this->putData(array(
			'languages' 	=> $languages,
			'timezones' 	=> $timezones,
			'summary'   	=> $summary,
			'edit_user' 	=> $edit_user,
			//'user_polo' 	=> $userPolo,
            'user_polo'     => array(),
			//'user_courses'	=> $userCourses
            'user_courses'  => array()
		));

		$form_actions = array(
			'personal'  => $this->getBasePath() . 'profile/personal',
			'password'  => $this->getBasePath() . 'profile/password'
		);
		$this->putItem("FORM_ACTIONS", $form_actions);
        */

        /*
        $this->putModuleScript("models.translate");
        $this->putModuleScript("menu.translate");

        $currentUser = $this->getCurrentUser();
        */
        //$this->putScript("menu.translate");

        $languageRS = Language::find();

        $userLanguageCode =  $this->translate->getSource();
        $items = array();

        foreach($languageRS as $key => $value) {
            $items[$key] = $value->toArray();
            if ($value->code == $userLanguageCode) {
                $current = $value->toArray();
            } else {
                $items[$key] = $value->toArray();
            }
        }
        /*
        $items[] = array(
            'link'  => $this->getBasePath() . "view/token",
            'text'  => $this->translate->translate("Review translation")
        );
        */

        //$this->putSectionTemplate("translate-menu", "menu/language.switch");
        //
        $menuItem = $current;
        $menuItem['items'] = $items;
        /*
        $menuItem = array(
            'icon'      => 'globe',
            'notif'     => count($items),
            'link'  => array(
                'link'  => $this->getBasePath() . "change",
                'text'  => $this->translate->translate('Languages')
            ),
            'type'      => 'language',
            'items'     => $items,
            'extended'  => false,
            'template'  => "translate-menu"
        );
    
        var_dump($menuItem);
        exit;
        */
        $this->putItem("LANGUAGE_MENU", $menuItem);

        //$languages = Language::find();
        $this->putitem("languages", $languageRS->toArray());

        $timezones = Timezones::findAll();
        $this->putitem("timezones", $timezones);

        $this->putItem("edit_user", $currentUser->toFullArray(array('Avatars')));
        //var_dump($currentUser->toFullArray(array('Avatars')));
        //exit;
		//$this->putCss("css/pages/profile");
		$this->display("profile.tpl");
	}


/**
     * [ add a description ]
     *
     * @Get("/check-login")
     */
    public function checkLoginRequest()
    {
        if ($currentUser    = $this->getCurrentUser(true)) {
            $login = $_GET['login'];

            /*
            $exists = User::count(array(
                "login = ?0 AND login <> ?1",
                "bind" => array($login, $currentUser->login)
            ));
            */
            $exists = User::count(array(
                "login = ?0",
                "bind" => array($login)
            ));

            $this->response->setContentType('application/json', 'UTF-8');
            $this->response->setJsonContent($exists == 0);

            return $exists == 0;
        } else {
            return $this->invalidRequestError();
        }
    }




	/**
	 * [ add a description ]
	 *
	 * @url POST /profile/personal
     * @deprecated 3.0.0.34
	 */
	public function profilePersonalSaveRequest()
	{
		$this->redirect($this->getSystemUrl("home"), $this->translate->translate("The profile change is disabled on demo enviroment!"), "warning");
		exit;
		/*
		["name"]=> string(8) "VINICIOS"
		["surname"]=> string(13) "CUTRIM MENDES"
		["email"]=> string(28) "vinicioscmendes@yahoo.com.br"
		["language"]=> string(10) "portuguese"
		["timezone"]=> string(20) "America/Buenos_Aires"
		["short_description"]=> string(31) "dfdfdfsdfsdfsdfsdfdfsdfsdfsdfdf"
		// EXTENDED USER PROFILE
		["data_nascimento"]=> string(10) "07/19/1984"
		*/
		$values = $_POST;
		// PUT ALL THIS DATA UNDER A MODEL, PLEASE!!!!!!
		// VALIDATE VALUES

		$currentUser    = $this->getCurrentUser(true);

		$currentUser->user['name'] = $values['name'];
		$currentUser->user['surname'] = $values['surname'];
		$currentUser->user['email'] = $values['email'];
		$currentUser->user['languages_NAME'] = $values['languages_NAME'];
		$currentUser->user['timezone'] = $values['timezone'];
		$currentUser->user['short_description'] = $values['short_description'];

		$status = $currentUser->persist();

		$extProperties = array();

		$date_fmt = $this->module("settings")->get("php_date_fmt");
		$data_nascimento = date_create_from_format($date_fmt, $values['data_nascimento']);
		if ($data_nascimento != false) {
			$extProperties['data_nascimento'] = $data_nascimento->format("Y-m-d");
			$this->_updateTableData("module_xuser", $extProperties, "id = " . $currentUser->user['id']);
		}

		$this->redirect(
			$this->getBasePath() . "profile",
			$this->translate->translate("Your personal info has been saved successfully!"),
			"success"
		);
		exit;
	}
	/**
	 * [ add a description ]
	 *
	 * @url POST /profile/password
     * @deprecated 3.0.0.34
	 */
	public function profilePasswordSaveRequest()
	{
		//$this->redirect($this->getSystemUrl("home"), $this->translate->translate("The profile change is disabled on demo enviroment!"), "warning");
		//exit;

		$currentUser = $this->getCurrentUser(true);
		$values = $_POST;

		$password = MagesterUser::createPassword($values['password']);
		if ($password != $currentUser->user['password']) {
			$this->redirect(
				$this->getBasePath() . "profile",
				$this->translate->translate("Your old password is incorrect!"),
				"danger"
			);
			exit;
		}
		if ($values['new-password'] !== $values['new-password-confirm']) {
			$this->redirect(
				$this->getBasePath() . "profile",
				$this->translate->translate("Your new password doesn't match!"),
				"warning"
			);
			exit;
		}
		$currentUser->user['password'] = MagesterUser::createPassword($values['new-password']);
		$currentUser->persist();

		$this->redirect(
			$this->getBasePath() . "profile",
			$this->translate->translate("Your password has been changed successfully!"),
			"success"
		);
		exit;

	}

    /**
     * Entry point for 'select2' request data
     *
     * @url GET /combo/items
     * @url GET /combo/items/:type
     * @deprecated 3.0.0.0
     */
    public function comboItensRequest($type) {
        $q = $_GET['q'];

        switch ($type) {
            case 'user_types':
            default : {
                $roles = MagesterUser::GetRoles(true);
                $result = array();
                foreach($roles as $key => $value) {
                    $result[] = array(
                        'id'    => $key,
                        'name'  => $value
                    );
                }
                if (!empty($q)) {
                    $result = sC_filterData($result, $q);
                }
                return array_values($result);
            }
        }
    }

    /*
    public function getLinks() {
        //if ($this->getCurrentUser(true)->getType() == 'administrator') {
            return array(
                'users' => array(
                    array(
                        //'count' => count($data),
                        'text'  => $this->translate->translate('My Profile'),
                        'link'  => $this->getBasePath() . 'profile'
                    ),
                    array(
                        //'count' => count($data),
                        'text'  => $this->translate->translate('Users'),
                        'link'  => $this->getBasePath() . 'view'
                    ),
                    array(
                        //'count' => count($data),
                        'text'  => $this->translate->translate('Users Types'),
                        'link'  => $this->getBasePath() . 'view/types'
                    ),
                    array(
                        //'count' => count($data),
                        'text'  => $this->translate->translate('Users Types'),
                        'link'  => $this->getBasePath() . 'view/groups'
                    )
                )
            );
        //}
    }
    */
   

  
    const PERMISSION_IN_LESSON      = "PERMISSION_IN_LESSON";
    const PERMISSION_IN_COURSE      = "PERMISSION_IN_COURSE";
    const PERMISSION_SPECIFIC_TYPE  = "PERMISSION_SPECIFIC_TYPE";

    public static $permissions = null;

    /* IPermissionChecker */
    public function getName() {
        return $this->translate->translate("Users");
    }
    public function getPermissions($index = null) {
        if (is_null(self::$permissions)) {
            self::$permissions = array(
                self::PERMISSION_IN_LESSON => array(
                    'name'  => "All students enrolled in a lesson",
                    'token' => "All students enrolled in the lesson '%s'"
                ),
                self::PERMISSION_IN_COURSE => array(
                    'name'  => "All students enrolled in a course",
                    'token' => "All students enrolled in the course '%s'"
                ),
                self::PERMISSION_SPECIFIC_TYPE => array(
                    'name'  => "All users from specific type",
                    'token' => "All users of type '%s'"
                )
            );
        }
        if (!array_key_exists($index, self::$permissions)) {
            return self::$permissions;
        } else {
            return self::$permissions[$index];
        }
    }

    /**
     * [getConditionText description]
     * @param  [type] $condition_id [description]
     * @param  [type] $data         [description]
     * @return [type]               [description]
     */
    public function getConditionText($condition_id, $data) {

        $condition = $this->getPermissions($condition_id);

        switch($condition_id) {
            case self::PERMISSION_IN_LESSON : {
                $lessonObject = $this->model("course/lessons")->getItem($data);
                return $this->translate->translate($condition['token'], $lessonObject->lesson['name']);
            }
            case self::PERMISSION_IN_COURSE : {
                $course = $this->model("course/item")->getItem($data);
                return $this->translate->translate($condition['token'], $course['name']);
            }
            case self::PERMISSION_SPECIFIC_TYPE : {
                $roles = MagesterUser::GetRoles(true);
                return $this->translate->translate($condition['token'], $roles[$data]);
            }
            default : {
                return "Permission Unknown";
            }
        }
    }

    /**
     * [checkCondition description]
     * @param  [type] $condition_id [description]
     * @param  [type] $data         [description]
     * @return [type]               [description]
     */
    public function checkCondition($condition_id, $data) {
        $entity = $this->getCurrentUser();
        return $this->checkConditionByEntityId($entity['id'], $condition_id, $data);
    }

    /**
     * [checkConditionByEntityId description]
     * @param  [type] $entity_id    [description]
     * @param  [type] $condition_id [description]
     * @param  [type] $data         [description]
     * @return [type]               [description]
     */
    public function checkConditionByEntityId($entity_id, $condition_id, $data) {
        $userModel = $this->model("users");
        $userObject = $userModel->getItem($entity_id);

        switch($condition_id) {
            case self::PERMISSION_IN_LESSON : {
                // CHECK IF
                $checkIDs = explode(";", $data);
                if ($userObject->getType() == 'administrator') {
                    return true;
                }
                $userLessons = $userObject->getUserLessons(array("return_objects" => false));
                $userLessonsId = array_keys($userLessons);

                foreach($checkIDs as $lesson_id) {
                    if (!in_array($lesson_id, $userLessonsId)) {
                        return false;
                    }
                }
                return true;
            }
            case self::PERMISSION_IN_COURSE : {
                // CHECK IF
                $checkIDs = explode(";", $data);
                if ($userObject->getType() == 'administrator') {
                    return true;
                }
                $userCourses = $userObject->getUserCourses(array("return_objects" => false));
                $userCoursesId = array_keys($userCourses);

                foreach($checkIDs as $course_id) {
                    if (!in_array($course_id, $userCoursesId)) {
                        return false;
                    }
                }
                return true;
            }
            case self::PERMISSION_SPECIFIC_TYPE : {
                // CHECK IF
                $userType = $userObject->user['user_types_ID'] != 0 ? $userObject->user['user_types_ID'] : $userObject->getType();

                if ($userType == $data) {
                    return true;
                }
                return false;
            }

            default : {
                return true;
            }
        }
    }

    /**
     * [getPermissionForm description]
     * @param  [type] $condition_id [description]
     * @param  array  $data         [description]
     * @return [type]               [description]
     */
    public function getPermissionForm($condition_id, $data = array()) {
        if (array_key_exists($condition_id, $this->getPermissions())) {
            $this->putItem("data", $data);
            return $this->fetch("permission/" . $condition_id . ".tpl");
        } else {
            return false;
        }
    }

    /**
     * [parseFormData description]
     * @param  [type] $condition_id [description]
     * @param  [type] $data         [description]
     * @return [type]               [description]
     */
    public function parseFormData($condition_id, $data) {
        switch($condition_id) {
            case self::PERMISSION_IN_LESSON : {
                // CHECK IF
                $lesson_ids = explode(";", $data['lesson_id']);
                return implode(";", $lesson_ids);
            }
            case self::PERMISSION_IN_COURSE : {
                // CHECK IF
                $course_ids = explode(";", $data['course_id']);
                return implode(";", $course_ids);
            }
            case self::PERMISSION_SPECIFIC_TYPE : {
                // CHECK IF
                $user_type = explode(";", $data['user_type']);
                return implode(";", $user_type);
            }
            default : {
                return json_encode($data);
            }
        }
    }


}
