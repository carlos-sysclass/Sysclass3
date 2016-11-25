<?php
namespace Sysclass\Modules\Users;

use Phalcon\DI,
    Sysclass\Forms\BaseForm,
    Phalcon\Mvc\Model\Message,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UserCurriculum,
    Sysclass\Models\Users\Group,
    Sysclass\Models\Users\UsersGroups,
    Sysclass\Models\I18n\Language,
    Sysclass\Models\Content\Unit,
    Sysclass\Services\I18n\Timezones,
    Sysclass\Services\Authentication\Exception as AuthenticationException,
    Sysclass\Models\Users\UserPasswordRequest,
    Sysclass\Services\MessageBus\INotifyable,
    Sysclass\Collections\MessageBus\Event;

/**
 * @RoutePrefix("/module/users")
 */
class UsersModule extends \SysclassModule implements \ILinkable, \IBlockProvider, \IBreadcrumbable, \IActionable, \IWidgetContainer, /* \ISectionMenu, */ INotifyable
{
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
            },
            'users.details' => function($data, $self) {
                //$country_codes = Country::findAll();
                //$country_codes = $self->model("i18n/country")->getItems();
                //$self->putItem("country_codes", $country_codes);

                $userFields = array(
                    'how_did_you_know',
                    'supplier_name',
                    'cnpj',
                    'is_supplier'
                );

                $enrollments = $self->user->getEnrollments();

                $info = array(
                    'fields' => array()
                );

                foreach($enrollments as $enroll) {
                    $fields = $enroll->getEnrollFields();

                    foreach($fields as $enrollfield) {
                        if (in_array($enrollfield->field->name, $userFields)) {
                            $enrollfield->translate();

                            $enrollfield->weight = 12;

                            $info['fields'][] = $enrollfield->toAdditionalArray(["field", "options"]);
                        }
                    }
                }

                $form = new BaseForm(null, $info);

                $info = [];

                foreach ($form as $key => $value) {
                    $value->weight = 12;
                    $info['fields'][] = $value->toArray();

                }

                $self->putItem("user_details_info", $info);

                $self->putSectionTemplate("users.details", "blocks/details");

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
            'view'  => array(
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
     */
	public function getWidgets($widgetsIndexes = array(), $caller = null) {
    	if (in_array('users.overview', $widgetsIndexes)) {
			$currentUser    = $this->user;

            $this->putComponent("easy-pie-chart");

			$modules = $this->getModules("ISummarizable");

            $userDetails = $currentUser->toFullArray(array('Avatars', 'Courses'));

			$data = array();
			$data['user_details'] = $userDetails;

			$data['notification'] = array();

			foreach($modules as $key => $mod) {
				$notif = $mod->getSummary();
				if (is_array($notif)) {
					$data['notification'][$key] = $mod->getSummary();
				}
			}

			$data['notification'] = $caller->sortModules("users.overview.notification.order", $data['notification']);

			$this->putModuleScript("users");

            $userPointers = $userPointers = Unit::getContentPointers();

            $data['pointer'] = array(
                'program_id'    => $userPointers['program']->id,
                'course_id'     => $userPointers['course']->id,
                'unit_id'       => $userPointers['unit']->id,
                'content_id'    => $userPointers['content']->id
            );

			return array(
				'users.overview' => array(
					'id'        => 'users-panel',
					'type'      => 'users',
					//'title' 	=> 'User Overview',
					'template'	=> $this->template("widgets/overview"),
					'panel'		=> true,
                    'body'      => 'no-padding',
					'data'      => $data
					//'box'       => 'blue'
				)
			);
		}
	}

    /* ISectionMenu */
    /*
    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {

            $this->putScript("scripts/ui.menu.users");

            $courses = $this->user->getCourses();

            $items = array();
            foreach($courses as $course) {
                $items[] = array(
                    'link' => "javascript:void(0);",
                    'text' => sprintf("#%s %s", $course->id, $course->name),
                    'attrs' => array(
                        'data-entity-id' => $course->id
                    )
                );
            }

            var_dump($this->translate->translate('My Programs'));
            exit;

            if (count($courses) > 0) {
                $menuItem = array(
                    'id'        => "users-topbar-menu",
                    'icon'      => ' fa fa-graduation-cap',
                    'text'      => $this->translate->translate('My Programs'),
                    'type'      => '',
                    'items'     => $items,
                    'extended'  => false,
                );

                return $menuItem;
            }
        }
        return false;
    }
    */

    /* INotifyable */
    public function getAllActions() {

    }

    public function processNotification($action, Event $event) {
        switch($action) {
            case "start-password-request" : {
                // SEND EMAIL PASSWORD RESET 
                $data = $event->data;

                $user = User::findFirstById($data['id']);

                if ($user) {
                    // REMOVE PREVIOUS REQUESTS
                    $requests = $user->getPasswordRequests();
                    foreach ($requests as $request) {
                        $request->active = 0;
                        $request->save();
                    }

                    $passwordRequest = new UserPasswordRequest();
                    $passwordRequest->user_id = $user->id;
                    $passwordRequest->reset_hash = $user->createRandomPass(16);

                    $date = \DateTime::createFromFormat('U', $event->timestamp);
                    $date->add(new \DateInterval('PT6H'));
                    $passwordRequest->valid_until = $date->format('Y-m-d H:i:s');

                    if ($passwordRequest->save()) {
                        $status = $this->mail->send(
                            $user->email, 
                            "Solicitação de troca de senha. Email automático, não é necessário responder.",
                            "email/" . $this->sysconfig->deploy->environment . "/password-reset.email",
                            true,
                            array(
                                'user' => $user->toArray(),
                                'reset_link' => "http://" . $this->sysconfig->deploy->environment . ".sysclass.com/password-reset/" . $passwordRequest->reset_hash
                            )
                        );


                        $this->notification->createForUser(
                            $user,
                            sprintf(
                                'You request a password reset.', 
                                $course->name
                            ),
                            'activity',
                            array(
                                'text' => "View",
                                'link' => $this->getBasePath() . "view/" . $course->id
                            )
                        );

                        return array(
                            'status' => true
                        );
                    }
                }
                return array(
                    'status' => false,
                    'unqueue' => true
                );
            }
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    public function addPage()
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

        if (array_key_exists('curriculum', $data) && is_array($data['curriculum']) ) {
            $curriculum = new UserCurriculum();
            $data['curriculum']['id'] = $model->id;
            $curriculum->assign($data['curriculum']);
            $curriculum->save();
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
                if ($this->isUserAllowed("change-password")) {
                    // DEFINE AUTHENTICATION BACKEND
                    if (
                        array_key_exists('old-password', $data) &&
                        !empty($data['old-password']) &&
                        $this->authentication->checkPassword($data['old-password'], $model)
                    ) {
                        $model->password = $this->authentication->hashPassword($data['new-password'], $model);
                    } elseif ($this->isUserAllowed("edit")) {
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
            UsersGroups::find("user_id = {$model->id}")->delete();
            
            foreach($data['usergroups'] as $group) {
                $userGroup = new UsersGroups();
                $userGroup->user_id = $model->id;
                $userGroup->group_id = $group['id'];
                $userGroup->save();
            }
        }

        if (array_key_exists('curriculum', $data) && is_array($data['curriculum']) ) {
            $curriculum = new UserCurriculum();
            $data['curriculum']['id'] = $model->id;
            $curriculum->assign($data['curriculum']);
            $curriculum->save();
        }
    }

    protected function getDatatableItemOptions() {
        if ($this->request->hasQuery('block')) {
            return array();
            /*
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
            */
        } else {
            return parent::getDatatableItemOptions();
        }
    }

    protected function getDatatableSingleItemOptions($item) {
        if (!$this->request->hasQuery('block') && $item->pending == 1) {
            return array(
                'aprove' => array(
                    'icon'  => 'fa fa-lock',
                    //'link'  => $this->getBasePath() . $baseLink . "block/" . $item->id,
                    'class' => 'btn-sm btn-info tooltips',
                    'attrs' => array(
                        'data-datatable-action' => "aprove",
                        'data-original-title' => 'Aprove User'
                    )
                )
            );
        }
        return false;
    }

    protected function isUserAllowed($action, $module_id = null) {
        $allowed = parent::isUserAllowed($action);
        if (!$allowed) {
            switch($action) {
                case "edit" : {
                    // ALLOW IF THE USER IS UPDATING HIMSELF
                    return $this->_args['id'] == $this->user->id;
                }
            }
        }
        return $allowed;
    }

    /**
     * [ add a description ]
     *
     * @Put("/datasource/agreement/{id}")
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
                    $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                    return array_merge($response, $userModel->toFullArray());
                }
            } else {
                var_dump("Sem permissão");
                exit;
                return $this->invalidRequestError($this->translate->translate("You don't have the permission to update these info."), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    
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

        $userWidgets = $this->getWidgets(array('users.overview'), $this);

        foreach($userWidgets as $key => $widget) {
            $this->addWidget($key, $widget);
        }

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
}
