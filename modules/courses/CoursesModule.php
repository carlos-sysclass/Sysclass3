<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class CoursesModule extends SysclassModule implements IWidgetContainer
{

	public function getWidgets($widgetsIndexes = array()) {
		// TODO MOVE TO YOUR OWN COMPONENT
		$this->putScript("plugins/jquery-easy-pie-chart/jquery.easy-pie-chart");
		$this->putComponent("fuelux-tree");
		$this->putComponent("jquery-nestable");
		
		$this->putModuleScript("models.courses");
		$this->putModuleScript("widget.courses");


		return array(
			'courses.overview' => array(
				'type'      => 'courses', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
				'id'        => 'courses-widget',
				'template'	=> $this->template("overview.widget"),
				'box'       => 'dark-blue tabbable tabbable-left',
				'tools'     => array(
					'search'        => true,
					'fullscreen'    => true
				)
			)
		);
	}


    /**
     * Get all classes from selected(s) course(s)
     *
     * @url GET /items/classes/:courses
     * @url GET /items/classes/:courses/:datatable
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

        $items = $this->module("permission")->checkRules($itemsData, "classes", 'permission_access_mode');
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
     * Get all seasons from selected(s) course(s)
     *
     * @url GET /items/seasons/:courses
     * @url GET /items/seasons/:courses/:datatable
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
            'active' 	=> 1,
            'course_id'	=> $courses
        ), array("operator" => "="))->getItems();

        $items = $this->module("permission")->checkRules($itemsData, "seasons", 'permission_access_mode');
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
	 * Module Entry Point
	 *
	 * @url GET /combo/items
	 * @url GET /combo/items/:type
	 */
	public function comboItensAction($type) {
		$q = $_GET['q'];

		switch ($type) {
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
	 * Module Entry Point
	 *
	 * @url GET /item/courses
	 * @url GET /item/courses/:id
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
	 * Module Entry Point
	 *
	 * @url GET /list
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
	 * Module Entry Point
	 *
	 * @url GET /content
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
	 * Module Entry Point
	 *
	 * @url GET /content/:course/:lesson
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
	 * Module Entry Point
	 *
	 * @url GET /content/:course/:lesson/:content
	 */
	public function getContentAction($course, $lesson, $content)
	{
		$this->module("settings")->put("course_id", $course);
		$this->module("settings")->put("lesson_id", $lesson);
		$this->module("settings")->put("content_id", $content);

		return $this->getContent($course, $lesson, $content);
	}

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
	 * Module Entry Point
	 *
	 * @url GET /materials/list/:course/:lesson/:content
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
				)/*,
				"chapters" => array(
					"src"       => $urlRoot . "chapters.en.vtt",
					"label"     => "English",
					"srclang"   => "en",
					"default"   => "default"
				)
				*/
			)
		);
	}

	public function getMaterialsSource($course, $lesson, $content)
	{
		//$urlRoot = sprintf("http://aulas.sysclass.com/layout/%s/%s/", $lesson, $content);
		$urlRoot = sprintf("/module/courses/materials/list/%s/%s/%s", $course, $lesson, $content);
		return $urlRoot;
	}

	protected function getVideoDefaults() {
		return array(
			//'poster'    =>  "http://aulas.sysclass.com/upload/ult.jpg",
			'poster'    =>  "/assets/sysclass.default/img/video-poster.jpg",
			'techOrder' => array(
				'html5', 'flash'
			),
			'width'     => 'auto',
			'height'    => 'auto',
			'controls'  => true,
			'preload'   => 'metadata',
			'autoplay'  => false
		);
	}
}
