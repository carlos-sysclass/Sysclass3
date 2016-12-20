<?php
namespace Sysclass\Modules\Courses;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Content\Program as Course,
    Sysclass\Models\Enrollments\CourseUsers,
    Sysclass\Models\Acl\Role,
    Sysclass\Models\I18n\Language;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/courses")
 */
class CoursesModule extends \SysclassModule implements /* \ISummarizable, */\ILinkable, \IBreadcrumbable, \IActionable, \IBlockProvider, \IWidgetContainer
{
    /* ISummarizable */
    public function getSummary() {
        $progress = CourseUsers::getUserProgress(true);

        foreach($progress as $progCourse) {
            $completed += $progCourse['lessons']['completed'];
            $expected += $progCourse['lessons']['expected'];
            $total += $progCourse['lessons']['total'];
        }

        if ($completed > $expected) {
            $type = 'success';
            $count = '<i class="icon-arrow-up"></i>';
        } elseif ($completed < $expected) {
            $type = 'danger';
            $count = '<i class="icon-arrow-down"></i>';
        } else {
            $type = 'info';
            $count = '<i class="icon-arrow-right"></i>';
        }

        return array(
            'type'  => $type,
            'count' => $count,
            'text'  => $this->translate->translate('Progress'),
            'link'  => array(
                'text'  => $completed . "/" . $total
            )
        );
    }

    /* ILinkable */
    public function getLinks() {

        if ($this->acl->isUserAllowed(null, "Courses", "View")) {
            /*
            $itemsData = $this->model("courses")->addFilter(array(
                'active'    => true
            ))->getItems();
            */
            $count = Course::count("active = 1");

            return array(
                'content' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Programs'),
                        'icon'  => 'fa fa-graduation-cap',
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
                'icon'  => 'fa fa-cube',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Programs")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Program"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Program"));
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
                    'text'      => $this->translate->translate('New Program'),
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
    /* IBlockProvider */
    public function registerBlocks() {
        return array(
            'programs.moreinfo' => function($data, $self) {
                $self->putSectionTemplate("moreinfo", "blocks/moreinfo");
            },
            'courses.list.table' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("data-tables");
                $self->putScript("scripts/utils.datatables");

                $block_context = $self->getConfig("blocks\\courses.list.table\context");
                $self->putItem("courses_block_context", $block_context);

                $self->putSectionTemplate("courses", "blocks/table");

                return true;

            }
        );
    }

    /* IWidgetContainer */
	public function getWidgets($widgetsIndexes = array(), $caller = null) {
        
		if (in_array('courses.overview', $widgetsIndexes) && $currentUser = $this->getCurrentUser(true)) {

			// TODO MOVE TO YOUR OWN COMPONENT
			//$this->putScript("plugins/jquery-easy-pie-chart/jquery.easy-pie-chart");
			//$this->putComponent("fuelux-tree");
			//$this->putComponent("jquery-nestable");
            $this->putComponent("bootstrap-switch");
            $this->putComponent("icheck");
            $this->putComponent("easy-pie-chart");

			$this->putScript("plugins/holder");
            
			//$this->putScript("plugins/videojs/vjs.youtube");

			//$this->putModuleScript("models.courses");
			$this->putModuleScript("widget.courses");

            $this->putBlock("tests.info.dialog");

            $this->putBlock("lessons.dialogs.exercises");


            $settings = $this->module("settings")->getSettings(true);
            
            if (!@isset($settings['course_id']) || !is_numeric($settings['course_id'])) {
                // GET FIRST COURSE FORM USER LIST
                $enrollment = CourseUsers::findFirst(array(
                    'conditions'    => 'user_id = ?0 AND status_id = 1',
                    'bind' => array($currentUser->id)
                    //'bind' => '123456'
                ));

                if (!is_object($enrollment)) {
                    // CHECK FOR CLASS-ONLY ENROLLMENTS
                } else {
                    $this->db->Execute(sprintf(
                        "DELETE FROM user_settings WHERE user_id = %d AND item = '%s'",
                        $currentUser->id,
                        'course_id'
                    ));
                    $this->db->Execute(sprintf(
                        "INSERT INTO user_settings (user_id, item, value) VALUES (%d, '%s', '%s')",
                        $currentUser->id,
                        'course_id',
                        $enrollment->course_id
                    ));
                    if (@isset($settings['class_id'])) {
                        // GET THE FIRST CLASS FROM COURSE

                    }

                }

            }
            
			return array(
				'courses.overview' => array(
					'type'      => 'courses', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
					'id'        => 'courses-widget',
					'template'	=> $this->template("widgets/courses.overview"),
					'box'       => 'dark-blue tabbable tabbable-left',
					'tools'     => array(
						'search'        => true,
						'fullscreen'    => true
					)
				)
			);

		}

		return false;
	}

    /**
     * [ add a description ]
     *
     * @Get("/add")
     */
    public function addPage()
    {
        $knowledgeAreas = $this->model("courses/areas/collection")->addFilter(array(
            'active' => 1
        ))->getItems();

        $this->putitem("knowledge_areas", $knowledgeAreas);

        $teacherRole = Role::findFirstByName('Teacher');
        $users = $teacherRole->getAllUsers();

        $this->putItem("instructors", $users);

        $languageRS = Language::find();
        $this->putitem("languages", $languageRS->toArray());

        parent::addPage();
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     */
    public function editPage($id)
    {        
        $knowledgeAreas = $this->model("courses/areas/collection")->addFilter(array(
            'active' => 1
        ))->getItems();

        $this->putitem("knowledge_areas", $knowledgeAreas);

        $teacherRole = Role::findFirstByName('Teacher');
        $users = $teacherRole->getAllUsers();

        $this->putItem("instructors", $users);

        $languageRS = Language::find();
        $this->putitem("languages", $languageRS->toArray());


        parent::editPage($id);
    }

    /**
     * [ add a description ]
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    /*
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        if ($currentUser = $this->getCurrentUser(true)) {

            if ($model ==  "users") {
                $filter = filter_var($filter, FILTER_DEFAULT);

                if (!is_array($filter)) {
                    $filter = json_decode($filter, true);
                }

                $index = 0;
                foreach($filter as $key => $item) {
                    $modelFilters[] = "{$key} = ?{$index}";
                    $filterData[$index] = $item;
                    $index++;
                }

                $modelRS = CourseUsers::find(array(
                    'conditions'    => implode(" AND ", $modelFilters),
                    'bind' => $filterData
                ));

                $itemsData = $modelRS->toArray();
            } elseif ($model ==  "me") {
                $modelRoute = "courses";
                $optionsRoute = "edit";

                $itemsCollection = $this->model($modelRoute);
                $itemsData = $itemsCollection->getItems();
            } else {
                return $this->invalidRequestError();
            }

            //$currentUser    = $this->getCurrentUser(true);
            //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

            if ($type === 'combo') {
                $q = $_GET['q'];
                $itemsData = $itemsCollection->filterCollection($itemsData, $q);

                $result = array();

                foreach($itemsData as $item) {
                    // @todo Group by course
                    $result[] = array(
                        'id'    => intval($item['id']),
                        'name'  => ($model ==  "instructor") ? $item['name'] . ' ' . $item['surname'] : $item['name']
                    );
                }
                return $result;
            } elseif ($type === 'datatable') {

                $itemsData = array_values($itemsData);
                foreach($itemsData as $key => $item) {
                    $itemsData[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'icon-edit',
                            'link'  => $this->getBasePath() . $optionsRoute . "/" . $item['id'],
                            'class' => 'btn-sm btn-primary'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
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
    */
    /**
     * [ add a description ]
     *
     * @url GET /item/:model/:identifier
     */
    public function getItemAction($model, $identifier)
    {
        $itemModel = $this->model("courses");
        if ($model == "me") {
            $editItem = $itemModel->getItem($identifier);
        } elseif ($model == "users") {

        //} elseif ($model == "full") {
        //    $editItem = $itemModel->getFullItem($identifier);
        } else {
            return $this->invalidRequestError();
        }
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction()
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("courses");
            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    $this->translate->translate("Course created."),
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
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("courses");
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Course updated."), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
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

            $itemModel = $this->model("courses");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Course removed."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @Get("/stats/me/{identifier}")
     */
    public function getCourseStatsRequest($identifier)
    {
        //$user = $this->getCurrentUser(true);
        $enrollmentCourse = CourseUsers::findFirst(array(
            'conditions'    => "user_id = ?0 AND course_id = ?1",
            'bind' => array($this->user->id, $identifier)
        ));

        if (count($enrollmentCourse) > 0) {
            // CALCULATE COURSE PROGRESS
            $progress = $enrollmentCourse->getProgress(true);

            $this->response->setJsonContent($progress);
            return true;
        } else {
            // USER NOT ENROLLED IN REQUESTED COURSE
            $this->response->setJsonContent($this->invalidRequestError());
        }
    }
    /**
     * @todo Move all /items routes to above
     */
    /**
     * Get all classes from selected(s) course(s)
     *
     * @url GET /items/classes/:courses
     * @url GET /items/classes/:courses/:datatable
     * @deprecated
     */
    public function getClassesItemsAction($courses, $datatable = null)
    {
        $currentUser    = $this->getCurrentUser(true);

        $courses = filter_var($courses, FILTER_DEFAULT);

        if (!is_array($courses)) {
			$courses = json_decode($courses, true);
		}
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $itemsData = $this->model("course/classes")->addFilter(array(
            'active' 	=> 1,
            'course_id'	=> $courses
        ), array("operator" => "="))->getItems();

        $items = $itemsData;
        /*
        if ($datatable === 'datatable') {
            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . "edit/" . $item['id'],
                        'class' => 'btn-sm btn-primary'
                    ),
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    )
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }
        */
        return array_values($items);
    }

	/**
	 * [ add a description ]
	 *
	 * @url GET /combo/items
	 * @url GET /combo/items/:type
     * @deprecated
	 */
	public function comboItensAction($type) {
		$q = $_GET['q'];

		switch ($type) {
            /*
			case 'courses': {
				$courses = MagesterCourse::getCourses();
				if (!empty($q)) {
					$courses = sC_filterData($courses, $q);
				}
				$result = array();
				foreach($courses as $course_id => $course) {
					// @todo Group by course
					$result[] = array(
						'id'    => $course_id,
						'name'  => $course['name']
					);
				}
				return $result;
			}
            */
			case 'lessons':
			default : {
				$lessons = MagesterLesson::getLessons();
				if (!empty($q)) {
					$lessons = sC_filterData($lessons, $q);
				}
				$result = array();
				foreach($lessons as $lesson_id => $lesson) {
					// @todo Group by course
					$result[] = array(
						'id'    => $lesson_id,
						'name'  => $lesson['name']
					);
				}
				return $result;
			}

				# code...
				break;
		}
		return $results;
	}

    /**
     * [ add a description ]
     *
     * @url GET /item/users/:course_id
     * @deprecated 3.0.0.18
    */
    public function getUsersInCourse($course_id) {
        $data = $this->getHttpData(func_get_args());

        $userCourseModel = $this->model("user/courses/item");

        $users = $userCourseModel->getUsersInCourse($course_id);

        return $users;
    }

    /**
     * [ add a description ]
     *
     * @Post("/item/users/toggle")
     */
    public function switchUserInGroup() {
        $data = $this->getHttpData(func_get_args());

        $enrollCourseModel = $this->model("enrollment/course");

        $status = $enrollCourseModel->switchUser(
            $data['course_id'],
            $data['user_id']
        );

        if ($status == 1) {
            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse($this->translate->translate("User added to course."), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse($this->translate->translate("User removed from course."), "error");
        }
        return array_merge($response, $info);
    }

	/**
	 * [ add a description ]
	 *
	 * @url GET /item/courses
	 * @url GET /item/courses/:id
     * @deprecated 3.0.0.19
	 */
	public function getCourseAction($id) {
		// TODO USE New Model classes to get this info
		$currentUser    = $this->getCurrentUser(true);
		$result = array();

		if (is_null($id)) {
			$id = $this->module("settings")->get("course_id");
		}
		$constraints    =  array(
			'archive'   => false,
			'active'    => true,
			'condition' => "(uc.user_type = 'student' OR uc.user_type IN (SELECT id FROM user_types WHERE basic_user_type = 'student'))",
			'sort'      => 'name'
		);

		$login = $currentUser->user['login'];
		$userEntities    =   $currentUser -> getUserCourses($constraints);
		// TODO CHECK PERMISSION RULES HERE

		$userEntityIDs = array_keys($userEntities);
		if (!in_array($id, $userEntityIDs)) {
			if (count($userEntityIDs) == 0) {
				return $this->invalidRequestError("You aren't enrolled in any course at the moment.");
			}
			$id = reset($userEntityIDs);
		}
		$result['id']   = $id;
		$result['data'] = $userEntities[$id]->course;
		$courseIndex = array_search($id, $userEntityIDs);
		// GETTING THE PREVIOUS COURSE
		if ($courseIndex == 0) {
			$result['prev'] = null;
		} else {
			$result['prev'] = $userEntityIDs[$courseIndex-1];
		}
		// GETTING THE NEXT COURSE
		if ($courseIndex == (count($userEntityIDs) - 1)) {
			$result['next'] = null;
		} else {
			$result['next'] = $userEntityIDs[$courseIndex+1];
		}

		return $result;
	}

	/**
	 * Get all classes from selected course
	 *
	 * @url GET /item/classes
	 * @url GET /item/classes/:course_id
	 * @url GET /item/classes/:course_id/:id
     * @deprecated 3.0.0.19
	 */
	public function getClassAction($course_id, $id) {
		// TODO USE New Model classes to get this info
		$currentUser    = $this->getCurrentUser(true);
		$result = array();

		if (is_null($course_id)) {
			$course_id = $this->module("settings")->get("course_id");
		}
		// TODO GET USERS COURSE_IDS AND CHECK IF THIS ID MATCH
		$userCourses    =   $currentUser -> getUserCourses($constraints);
		$userCoursesID	= array_keys($userCourses);
		if (!in_array($course_id, $userCoursesID)) {
			$course_id = reset($userCoursesID);
		}

		$course = new MagesterCourse($course_id);

		if (is_null($id)) {
			$id = $this->module("settings")->get("class_id");
		}

		$login = $currentUser->user['login'];
		$userLessons    =   $currentUser -> getUserLessons($constraints);
		$userLessonsIDs = array_keys($userLessons);

		$constraints    =  array(
			'archive'   => false,
			'active'    => true,
			'condition' => sprintf("(l.id IN (%s))", implode(",", $userLessonsIDs))
		);

		$userEntities = $course->getCourseLessons($constraints);
		$userEntityIDs = array_keys($userEntities);
		// TODO CHECK PERMISSION RULES HERE

		if (!in_array($id, $userEntityIDs)) {
			if (count($userEntityIDs) == 0) {
				return $this->invalidRequestError("You aren't enrolled in any class at the moment.");
			}
			$id = reset($userEntityIDs);
		}

		$result['id']   = $id;
		$result['course_id']   = $course_id;
		$result['data'] = $userEntities[$id]->lesson;
		$entityIndex = array_search($id, $userEntityIDs);
		// GETTING THE PREVIOUS COURSE
		if ($entityIndex == 0) {
			$result['prev'] = null;
		} else {
			$result['prev'] = $userEntityIDs[$entityIndex-1];
		}
		// GETTING THE NEXT COURSE
		if ($entityIndex == (count($userEntityIDs) - 1)) {
			$result['next'] = null;
		} else {
			$result['next'] = $userEntityIDs[$entityIndex+1];
		}

		return $result;
	}
	/**
	 * Get all classes from selected course
	 *
	 * @url GET /item/lessons
	 * @url GET /item/lessons/:course_id
	 * @url GET /item/lessons/:course_id/:class_id
	 * @url GET /item/lessons/:course_id/:class_id/:id
     * @deprecated 3.0.0.19
	 */
	public function getLessonAction($course_id, $class_id, $id) {
		// TODO USE New Model classes to get this info
		$currentUser    = $this->getCurrentUser(true);
		$result = array();

		if (is_null($course_id)) {
			$course_id = $this->module("settings")->get("course_id");
		}
		// TODO GET USERS COURSE_IDS AND CHECK IF THIS ID MATCH
		$userCourses    =   $currentUser -> getUserCourses($constraints);
		$userCoursesID	= array_keys($userCourses);
		if (!in_array($course_id, $userCoursesID)) {
			$course_id = reset($userCoursesID);
		}
		$course = new MagesterCourse($course_id);


		if (is_null($class_id)) {
			$class_id = $this->module("settings")->get("class_id");
		}
		// TODO GET USERS CLASS IDS AND CHECK IF THIS ID MATCH
		$userClasses    =   $currentUser -> getUserLessons($constraints);
		$userClassesIDs = array_keys($userClasses);
		$constraints    =  array(
			'archive'   => false,
			'active'    => true,
			'condition' => sprintf("(l.id IN (%s))", implode(",", $userClassesIDs))
		);
		$userClasses = $course->getCourseLessons($constraints);
		$userClassesIDs = array_keys($userClasses);


		if (!in_array($class_id, $userClassesIDs)) {
			$class_id = reset($userClassesIDs);
		}
		$currentClass = new MagesterLesson($class_id);

		if (is_null($id)) {
			$id = $this->module("settings")->get("lesson_id");
		}

		$currentContent = new MagesterContentTree($currentClass);
		$currentContent -> markSeenNodes($currentUser);


		$filterIterator = new MagesterNodeFilterIterator(
			new RecursiveIteratorIterator(
				new RecursiveArrayIterator($currentContent -> tree)
				, RecursiveIteratorIterator :: SELF_FIRST
			)
		);
		if (!is_null($id)) {
			$found = false;
			foreach ($filterIterator as $key => $value) {
				if ($id == $key) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$id = null;
			}
		}


		if (is_null($id)) {
			$unseenContent = array();
			$id = false;
			foreach (new MagesterVisitableFilterIterator(new MagesterUnseenFilterIterator($filterIterator)) as $key => $value) {
				$id = $key;
				break;

			}
			if ($id == FALSE) {
				foreach (new MagesterVisitableFilterIterator(new MagesterSeenFilterIterator($filterIterator)) as $key => $value) {
					$id = $key;
					break;
				}
			}
		}

		$currentUnit = new MagesterUnit($id);
		$unitArray = $currentUnit->getArrayCopy();

		$result['id'] = $id;
		$result['course_id'] = $course_id;
		$result['class_id'] = $class_id;

		$prevNodes = $currentContent->getPreviousNodes($id);
		if (is_null($prevNodes) || count($prevNodes) == 0) {
			$result['prev'] = null;
		} else {
			$prevNode = end($prevNodes);
			$result['prev'] = $prevNode['id'];
		}

		$nextNodes = $currentContent->getNextNodes($id);
		if (is_null($nextNodes) || count($nextNodes) == 0) {
			$result['next'] = null;
		} else {
			$nextNode = reset($nextNodes);
			$result['next'] = $nextNode['id'];
		}

		$unitArray['sources'] = array(
			'materials' => $this->getMaterialsSource($course_id, $class_id, $id),
		);

		if (unserialize($unitArray['metadata'])) {
			$unitArray['metadata'] = unserialize($unitArray['metadata']);
		}
		if ($unitArray['ctg_type'] == "video") {
			$unitArray['data'] = json_decode(utf8_encode($unitArray['data']), true);
			if (!is_array($unitArray['data'])) {
				$unitArray['data'] = $this->getVideoDefaults();
			} else {
				$unitArray['data'] = array_merge($this->getVideoDefaults(), $unitArray['data']);
			}

			$unitArray['data']['video'] = $this->getVideoSource($class_id, $id);
		} else if ($unitArray['ctg_type'] == "tests") {

			$currentTest = new MagesterTest($unitArray['id'], true);

			$testStatus = $currentTest->getStatus($currentUser->user['login']);
			//var_dump($currentUser->user['login']);
			//$doneTests = MagesterStats::getDoneTestsPerTest(array($currentUser->user['login']), $currentTest->test['id']);
			// CHECK FOR CONDITIONS HERE, IF THE USER CAN MAKE THE TEST, OR IF THE SYSTEM WILL JUST SHOW THE TESTS RESULTS
			if ($testStatus['status'] == '') { // CAN BE 'completed', 'incomplete', 'passed', 'failed'
				$unitArray['data'] = $currentTest->test['description'];
			} else {
				//$doneTests = MagesterStats::getDoneTestsPerTest(array($currentUser->user['login']), $currentTest->test['id']);
				$unitArray['data'] = "<h4>TEST DONE!</h4>";
				//$unitArray['data'] .= "<br />Score: " . $doneTests['score'];
				$unitArray['data'] .= "<br />Status: " . $testStatus['status'];
				//echo "<pre>";

				//$doneTests[$currentTest->test['id']][$currentUser->user['login']]
			}
		}
		$result['data'] = $unitArray;
		return $result;
	}

	/**
	 * [ add a description ]
	 *
	 * @url GET /list
     * @deprecated
	 */
	public function listCourseAction()
	{
		$page   = isset($_GET['page']) ? $_GET['page'] : 1;
		$per_page = 10;

		$currentUser    = $this->getCurrentUser(true);

		$constraints    =  array(
			'archive'   => false,
			'active'    => true,
			'condition' => "(uc.user_type = 'student' OR uc.user_type IN (SELECT id FROM user_types WHERE basic_user_type = 'student'))",
			'sort'      => 'name'
		);

		$login = $currentUser->user['login'];
		$userCourses    =   $currentUser -> getUserCourses($constraints);
		//var_dump($currentUser->user['login']);
		$courseStats = MagesterStats::getUsersCourseStatus($userCourses, $login);

		$userLessons = $currentUser->getLessons();
		$lessonsIds = array_keys($userLessons);

		foreach($userCourses as $course) {
			$course->course['lessons'] = array();
			$lessons = $course->getCourseLessons(array('return_objects' => false));

			foreach($lessons as $lesson) {
				if (in_array($lesson['id'], $lessonsIds)) {
					$lesson['stats'] = $courseStats[$course->course['id']][$login]['lesson_status'][$lesson['id']];
					$course->course['lessons'][] = $lesson;
				}
			}
			unset($courseStats[$course->course['id']][$login]['lesson_status']);
			$course->course['stats'] = $courseStats[$course->course['id']][$login];
			$courses[] = $course->course;
		}
		return $courses;
	}

	/**
	 * [ add a description ]
	 *
	 * @url GET /content
     * @deprecated
	 */
	public function getContentBySettingsAction()
	{
		// RETURN JUST THE content ID
		// SAVE COURSE AND LESSON, ON USERS SETTINGS
		$settings = $this->module("settings")->getSettings(true);
		//var_dump($settings);
		return $this->getContent($settings['course_id'], $settings['lesson_id'], $settings['content_id']);
	}

	/**
	 * [ add a description ]
	 *
	 * @url GET /content/:course/:lesson
     * @deprecated
	 */
	public function getContentByCourseAndLessonAction($course, $lesson)
	{
		// RETURN JUST THE content ID
		// SAVE COURSE AND LESSON, ON USERS SETTINGS
		$this->module("settings")->put("course_id", $course);
		$this->module("settings")->put("lesson_id", $lesson);
		return $this->getContent($course, $lesson, null);
	}

	/**
	 * [ add a description ]
	 *
	 * @url GET /content/:course/:lesson/:content
     * @deprecated
	 */
	public function getContentAction($course, $lesson, $content)
	{
		$this->module("settings")->put("course_id", $course);
		$this->module("settings")->put("lesson_id", $lesson);
		$this->module("settings")->put("content_id", $content);

		return $this->getContent($course, $lesson, $content);
	}

    /**
     * [ add a description ]
     *
     * @deprecated
     */
	protected function getContent($course = null, $lesson = null, $content = null) {
		$currentUser    = $this->getCurrentUser(true);
		if (empty($lesson)) {
			// GET LESSON ID FROM COURSE
			if (empty($course)) {
				// GET FIRST COURSE FROM USER
				$userCourses = $currentUser->getUserCourses(array('return_objects' => true));
				if (count($userCourses) == 0) {
					return false;
				}
				$firstCourse = reset($userCourses);
				$course = $firstCourse->course['id'];
			} else {
				$firstCourse = new MagesterCourse($course);
			}
			$userLessons = $firstCourse->getCourseLessons(array('return_objects' => false));
			reset($userLessons);
			$lesson = key($userLessons);

			$this->module("settings")->put("course_id", $course);
			$this->module("settings")->put("lesson_id", $lesson);

		}

		$currentLesson = new MagesterLesson($lesson);

		$currentContent = new MagesterContentTree($currentLesson);
		$currentContent -> markSeenNodes($currentUser);

		//Legal values are the array of entities that the current user may actually edit or change.
		$classeData = sC_getTableData("users_to_courses", "classe_id", sprintf("users_LOGIN = '%s'", $currentUser -> user['login']));

		// GET USER CLASS
		$courseClass = $classeData[0]['classe_id'];

		$filterIterator = //new MagesterVisitableFilterIterator(
			new MagesterContentCourseClassFilterIterator(
				new MagesterNodeFilterIterator(
					new RecursiveIteratorIterator(
						new RecursiveArrayIterator($currentContent -> tree)
						, RecursiveIteratorIterator :: SELF_FIRST
					)
				),
				$courseClass
			);
		//);

		if (!is_null($content)) {
			$found = false;
			foreach ($filterIterator as $key => $value) {
				if ($content == $key) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$content = null;
			}
		}

		if (is_null($content)) {
			$unseenContent = array();
			$content = false;
			foreach (new MagesterVisitableFilterIterator(new MagesterUnseenFilterIterator($filterIterator)) as $key => $value) {
				$content = $key;
				break;

			}
			if ($content == FALSE) {
				foreach (new MagesterVisitableFilterIterator(new MagesterSeenFilterIterator($filterIterator)) as $key => $value) {
					$content = $key;
					break;
				}
			}
		}

		$prevNodes = $currentContent->getPreviousNodes($content);
		//var_dump($prevNodes);
		$prevNode = end($prevNodes);
		$nextNodes = $currentContent->getNextNodes($content);
		$nextNode = reset($nextNodes);

		$currentUnit = new MagesterUnit($content);

		$unitArray = $currentUnit->getArrayCopy();

		$unitArray['prev'] = $prevNode;
		$unitArray['next'] = $nextNode;

		$unitArray['course_id'] = $course;
		$unitArray['lesson_id'] = $lesson;

		$unitArray['sources'] = array(
			'materials' => $this->getMaterialsSource($course, $lesson, $content),
		);


		if (unserialize($unitArray['metadata'])) {
			$unitArray['metadata'] = unserialize($unitArray['metadata']);
		}
		if ($unitArray['ctg_type'] == "video") {
			$unitArray['data'] = json_decode(utf8_encode($unitArray['data']), true);
			if (!is_array($unitArray['data'])) {
				$unitArray['data'] = $this->getVideoDefaults();
			} else {
				$unitArray['data'] = array_merge($this->getVideoDefaults(), $unitArray['data']);
			}

			$unitArray['data']['video'] = $this->getVideoSource($lesson, $content);
		} else if ($unitArray['ctg_type'] == "tests") {

			$currentTest = new MagesterTest($unitArray['id'], true);

			$testStatus = $currentTest->getStatus($currentUser->user['login']);
			//var_dump($currentUser->user['login']);
			//$doneTests = MagesterStats::getDoneTestsPerTest(array($currentUser->user['login']), $currentTest->test['id']);
			// CHECK FOR CONDITIONS HERE, IF THE USER CAN MAKE THE TEST, OR IF THE SYSTEM WILL JUST SHOW THE TESTS RESULTS
			if ($testStatus['status'] == '') { // CAN BE 'completed', 'incomplete', 'passed', 'failed'
				$unitArray['data'] = $currentTest->test['description'];
			} else {
				//$doneTests = MagesterStats::getDoneTestsPerTest(array($currentUser->user['login']), $currentTest->test['id']);
				$unitArray['data'] = "<h4>TEST DONE!</h4>";
				//$unitArray['data'] .= "<br />Score: " . $doneTests['score'];
				$unitArray['data'] .= "<br />Status: " . $testStatus['status'];
				//echo "<pre>";

				//$doneTests[$currentTest->test['id']][$currentUser->user['login']]
			}
		}
		return $unitArray;
	}

	/**
	 * [ add a description ]
	 *
	 * @url GET /materials/list/:course/:lesson/:content
     * @deprecated
	 */
	public function getMaterialsAction($course, $lesson, $content)
	{
		$plico = PlicoLib::instance();
		$basepath = realpath($plico->get("path/app") . "files");
		$basedirpath = $basepath . sprintf("/%s/%s/materials", $lesson, $content);
		//$basedirpath = realpath($dirpath);
		$folder = "";
		if (array_key_exists('type', $_GET) && $_GET['type'] == 'folder' && array_key_exists('filename', $_GET)) {
			$folder = $_GET['filename'] . "/";
			$dirpath = $basedirpath . "/" . $folder;
			$dirpath = realpath($dirpath);
		} else {
			$dirpath = realpath($basedirpath);
		}

		$finfo = new finfo(FILEINFO_MIME_TYPE);

		$mimeTypesIcons = array(
			"default"                       => "icon-file-alt",
			"application/vnd.ms-powerpoint" => "icon-ms-ppt",
			"application/pdf"               => "icon-adobe-pdf"
		);

		if (strpos($dirpath, $basepath) == 0) {
			//if (strpos($dirpath, $base_path))
			$dirs = $files = array();
			$directoryArray = scandir($dirpath);
			foreach($directoryArray as $file) {
				if (strpos($file, '.') === 0) {
					continue;
				}
				if (is_dir($dirpath . "/" . $file)) {
					$dirs[] = array(
						'name'      => $file,
						'filename'  => $folder . $file,
						'type'      => "folder"
					);
				} else {
					$mime_type = $finfo->file($dirpath . "/" . $file);
					if (array_key_exists($mime_type, $mimeTypesIcons)) {
						$icon = $mimeTypesIcons[$mime_type];
					} else {
						$icon = $mimeTypesIcons["default"];
					}

					$files[] = array(
						'name'      => sprintf('<i class="%s"></i> %s', $icon, $file),
						'filename'  => $folder . $file,
						'type'      => "item"
					);
				}
			}
			return array_merge($dirs, $files);
		} else {
			return $this->invalidRequestError();
		}
		exit;
		//return $this->getContent($course, $lesson, $content);
	}

    /**
     * [ add a description ]
     *
     * @deprecated
     */
	public function getVideoSource($lesson, $content)
	{
		$urlRoot = sprintf("http://aulas.sysclass.com/layout/%s/%s/", $lesson, $content);
		$urlRoot = sprintf("/files/%s/%s/video/", $lesson, $content);
		// TODO CREATE A WAY TO QUERY FROM THE SELECTED BACKEND (file, dropdbox, etc.)
		$plico = PlicoLib::instance();

		$sources = array();
		$sources_types = array(
			"video/flv" => "flv",
			"video/mp4" => "mp4",
			"video/webm" => "webm"
		);

		foreach($sources_types as $type => $ext) {
			if (file_exists(realpath($plico->get('path/app') . $urlRoot . "video." . $ext))) {
				$sources[$type] = $urlRoot . "video." . $ext;
			}
		}
		//$sources['video/youtube'] = 'https://www.youtube.com/watch?v=tFxZuewrRE8';


		return array(
			// @todo GET FORMATS QUERYING SERVER
			"sources" => $sources,
			"tracks"    => array(
			/*
				"captions" => array(
					"src"       => $urlRoot . "captions.en.vtt",
					"label"     => "English",
					"srclang"   => "en"
				),
			*/
				"subtitles" => array(
					"src"       => $urlRoot . "captions.en.vtt",
					"label"     => "English",
					"srclang"   => "en"
				)
			)
		);
	}

    /**
     * [ add a description ]
     *
     * @deprecated
     */
	public function getMaterialsSource($course, $lesson, $content)
	{
		//$urlRoot = sprintf("http://aulas.sysclass.com/layout/%s/%s/", $lesson, $content);
		$urlRoot = sprintf("/module/courses/materials/list/%s/%s/%s", $course, $lesson, $content);
		return $urlRoot;
	}

    /**
     * [ add a description ]
     *
     * @deprecated
     */
	protected function getVideoDefaults() {
		return array(
			//'poster'    =>  "http://aulas.sysclass.com/upload/ult.jpg",
			'poster'    	=>  "/assets/sysclass.default/img/video-poster-indiegogo.jpg",
			'techOrder' 	=> array(
				/* 'youtube' , */'html5', 'flash'
			),
			'ytcontrols'	=> true,
			'width'     	=> 'auto',
			'height'   	 	=> 'auto',
			'controls'  	=> true,
			'preload'   	=> 'metadata',
			'autoplay'  	=> false
		);
	}

    /**
     * [ add a description ]
     *
     * @url GET /items/seasons/:courses
     * @url GET /items/seasons/:courses/:datatable
     * @deprecated
     */
    public function getSeasonsItemsAction($courses, $datatable = null)
    {
        $currentUser    = $this->getCurrentUser(true);

        $courses = filter_var($courses, FILTER_DEFAULT);

        if (!is_array($courses)) {
            $courses = json_decode($courses, true);
        }
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $itemsData = $this->model("course/seasons")->addFilter(array(
            'active'    => 1,
            'course_id' => $courses
        ), array("operator" => "="))->getItems();

        $items = $itemsData;
        /*
        if ($datatable === 'datatable') {
            $items = array_values($items);
            foreach($items as $key => $item) {
                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . "edit/" . $item['id'],
                        'class' => 'btn-sm btn-primary'
                    ),
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    )
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }
        */
        return array_values($items);
    }

}
