<?php


/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */
class UsersModule extends SysclassModule implements ILinkable, IBlockProvider, IBreadcrumbable, IActionable, IPermissionChecker, IWidgetContainer
{

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $items = $this->model("users/collection")->addFilter(array(
                'active'    => true
            ))->getItems();

            $groupItems = $this->model("users/groups/collection")->addFilter(array(
                'active'    => true
            ))->getItems();
            // $items = $this->module("permission")->checkRules($itemsData, "course", 'permission_access_mode');

            return array(
                'users' => array(
                    array(
                        'count' => count($items),
                        'text'  => self::$t->translate('Users'),
                        'icon'  => 'icon-user',
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

            }
        );
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Users")
                );
                //$breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Users")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("New User"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Users")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit User"));
                break;
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
                    'text'      => self::$t->translate('New User'),
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

	const PERMISSION_IN_LESSON 		= "PERMISSION_IN_LESSON";
	const PERMISSION_IN_COURSE 		= "PERMISSION_IN_COURSE";
	const PERMISSION_SPECIFIC_TYPE 	= "PERMISSION_SPECIFIC_TYPE";

	public static $permissions = null;

	/* IPermissionChecker */
	public function getName() {
		return self::$t->translate("Users");
	}
	public function getPermissions($index = null) {
		if (is_null(self::$permissions)) {
			self::$permissions = array(
				self::PERMISSION_IN_LESSON => array(
					'name'	=> "All students enrolled in a lesson",
					'token'	=> "All students enrolled in the lesson '%s'"
				),
				self::PERMISSION_IN_COURSE => array(
					'name'	=> "All students enrolled in a course",
					'token'	=> "All students enrolled in the course '%s'"
				),
				self::PERMISSION_SPECIFIC_TYPE => array(
					'name'	=> "All users from specific type",
					'token'	=> "All users of type '%s'"
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
				return self::$t->translate($condition['token'], $lessonObject->lesson['name']);
			}
			case self::PERMISSION_IN_COURSE : {
				$course = $this->model("course/item")->getItem($data);
				return self::$t->translate($condition['token'], $course['name']);
			}
			case self::PERMISSION_SPECIFIC_TYPE : {
				$roles = MagesterUser::GetRoles(true);
				return self::$t->translate($condition['token'], $roles[$data]);
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

			$userDetails = MagesterUserDetails::getUserDetails($currentUser->user['login']);
			$userDetails = array_merge($currentUser->user, $userDetails);

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
					'template'	=> $this->template("overview.widget"),
					'panel'		=> true,
					'data'      => $data
					//'box'       => 'blue'
				)
			);
		}
	}
	/*
	public function getLinks() {
        //if ($this->getCurrentUser(true)->getType() == 'administrator') {
            return array(
                'users' => array(
                    array(
                        //'count' => count($data),
                        'text'  => self::$t->translate('My Profile'),
                        'link'  => $this->getBasePath() . 'profile'
                    ),
                    array(
                        //'count' => count($data),
                        'text'  => self::$t->translate('Users'),
                        'link'  => $this->getBasePath() . 'view'
                    ),
					array(
                        //'count' => count($data),
                        'text'  => self::$t->translate('Users Types'),
                        'link'  => $this->getBasePath() . 'view/types'
                    ),
                    array(
                        //'count' => count($data),
                        'text'  => self::$t->translate('Users Types'),
                        'link'  => $this->getBasePath() . 'view/groups'
                    )
                )
            );
        //}
    }
	*/
    /**
     * Entry point for 'select2' request data
     *
     * @url GET /combo/items
     * @url GET /combo/items/:type
     * @deprecated 3.0.0.0
     */
    public function comboItensAction($type) {
        $q = $_GET['q'];

        switch ($type) {
            case 'user_types':
            default : {
                $roles = MagesterUser::GetRoles(true);
                $result = array();
                foreach($roles as $key => $value) {
                	$result[] = array(
                		'id'	=> $key,
                		'name'	=> $value
                	);
                }
                if (!empty($q)) {
                    $result = sC_filterData($result, $q);
                }
                return array_values($result);
            }
        }
    }



    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
    */
    public function getItemAction($id) {
        $request = $this->getMatchedUrl();

        if (strpos($request, "groups/") === 0) {
            $editItem = $this->model("users/groups/collection")->getItem($id);
        } else {
            $editItem = $this->model("users/collection")->getItem($id);
            // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        }
        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        $request = $this->getMatchedUrl();

        if (strpos($request, "groups/") === 0) {
            $itemModel = $this->model("user/groups/item");
        } else {
            $itemModel = $this->model("user/item");
            // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        }

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->debug()->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("User created with success"),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
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
        $request = $this->getMatchedUrl();

        $itemModel = $this->model("user/item");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("User updated with success"), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url DELETE /item/me/:id
     */
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("user/item");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("User removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * [ add a description ]
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    public function getItemsAction($type)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $request = $this->getMatchedUrl();

        $modelRoute = "users/collection" ;
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();


 		// $items = $this->module("permission")->checkRules($itemsData, "users", 'permission_access_mode');
        $items = $itemsData;

        if ($type === 'combo') {
        	/*
            $q = $_GET['q'];

            $items = $itemsCollection->filterCollection($items, $q);

            foreach($items as $course) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($course['id']),
                    'name'  => $course['name']
                );
            }
            return $result;
            */
        } elseif ($type === 'datatable') {

            $items = array_values($items);
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
                        'block'  => array(
                            'icon'  => 'icon-lock',
                            'link'  => $baseLink . "block/" . $item['id'],
                            'class' => 'btn-sm btn-info'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                }
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }

        return array_values($items);
    }


    // CRUD FUNCIONS
    /**
     * [ add a description ]
     *
     * @url GET /view
     */
    public function viewPage()
    {
        $this->putBlock("group.add");

        parent::viewPage();

    }



	/**
	 * [ add a description ]
	 *
	 * @url GET /profile
	 */
	public function profilePage()
	{
		//$this->redirect($this->getSystemUrl("home"), self::$t->translate("The profile change is disabled on demo enviroment!"), "warning");
		//exit;
		$currentUser    = $this->getCurrentUser(true);
		// PUT HERE CHAT MODULE (CURRENTLY TUTORIA)
		$this->putComponent("select2");
		$this->putComponent("datepicker");
		$this->putComponent("pwstrength");

		// GET SUMMARY
		$summaryModules = $this->getModules("ISummarizable");
		$summary = array();
		foreach($summaryModules as $key => $mod) {
			$summary[$key] = $mod->getSummary();
		}
		$summary = $this->module("layout")->sortModules("users.overview.notification.order", $summary);

		$languages = MagesterSystem :: getLanguages(true, true);

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

		$this->putCss("css/pages/profile");
		$this->display("profile.tpl");
	}

	/**
	 * [ add a description ]
	 *
	 * @url POST /profile/personal
	 */
	public function profilePersonalSaveAction()
	{
		$this->redirect($this->getSystemUrl("home"), self::$t->translate("The profile change is disabled on demo enviroment!"), "warning");
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
			self::$t->translate("Your personal info has been saved successfully!"),
			"success"
		);
		exit;
	}
	/**
	 * [ add a description ]
	 *
	 * @url POST /profile/password
	 */
	public function profilePasswordSaveAction()
	{
		$this->redirect($this->getSystemUrl("home"), self::$t->translate("The profile change is disabled on demo enviroment!"), "warning");
		exit;

		$currentUser = $this->getCurrentUser(true);
		$values = $_POST;

		$password = MagesterUser::createPassword($values['password']);
		if ($password != $currentUser->user['password']) {
			$this->redirect(
				$this->getBasePath() . "profile",
				self::$t->translate("Your old password is incorrect!"),
				"danger"
			);
			exit;
		}
		if ($values['new-password'] !== $values['new-password-confirm']) {
			$this->redirect(
				$this->getBasePath() . "profile",
				self::$t->translate("Your new password doesn't match!"),
				"warning"
			);
			exit;
		}
		$currentUser->user['password'] = MagesterUser::createPassword($values['new-password']);
		$currentUser->persist();

		$this->redirect(
			$this->getBasePath() . "profile",
			self::$t->translate("Your password has been changed successfully!"),
			"success"
		);
		exit;

	}


}
