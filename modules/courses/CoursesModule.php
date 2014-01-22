<?php 
class CoursesModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array()) {
        $this->putScript("plugins/jquery-easy-pie-chart/jquery.easy-pie-chart");

        $this->putComponent("fuelux-tree");
        

        $this->putModuleScript("courses");

    	return array(
    		'courses.overview' => array(
                'type'      => 'courses', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'courses-widget',
   				'title' 	=> ' ',
   				'template'	=> $this->template("overview.widget"),
                'icon'      => 'book',
                'box'       => 'dark-blue tabbable tabbable-left',
                'tools'     => array(
                    'search'        => true,
                    //'reload'        => true,
                    'fullscreen'    => true/*,
                    'filter'        => true*/
                )/*,
                'actions'   => array(
                    $this->template("overview.widget.actions"),
                )*/
    		)
    	);
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


        /*
        $modules = $this->getModules("IPermissionChecker");
        $permissions = array();
        $results = array();
        foreach ($modules as $key => $module) {
            $permissions[$key] = $module->getPermissions();
            $groupItem = array(
                'text'  => $module->getName(),
                'children'  => array()
            );
            foreach($permissions[$key] as $perm_id => $perm_item) {
                $groupItem['children'][] = array(
                    'id'    => $key."::".$perm_id,
                    'name'  => $perm_item['name']
                );
            }
            $results[] = $groupItem;
        }
        */
        return $results;
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
        /**
          * @todo Check for user access, if he is enrolled on the course, and check content rules
         */
        /**
          * @todo Migrar este código para um Model, dentro do módulo
         */
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
        
        return array(
            // @todo GET FORMATS QUERYING SERVER
            "sources" => array(
                "video/flv" => $urlRoot . "video.flv"
                /*
                "video/mp4" => $urlRoot . "video.mp4",
                "video/webm" => $urlRoot . "video.webm",
                "video/ogg" => $urlRoot . "video.ogg"
                */
            ),
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
            'width'     => 640,
            'height'    => 360,
            'controls'  => true,
            'preload'   => 'metadata',
            'autoplay'  => false
        );
    }
}
