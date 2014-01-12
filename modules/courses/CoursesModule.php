<?php 
class CoursesModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array()) {
        $this->putScript("plugins/jquery-easy-pie-chart/jquery.easy-pie-chart");

        $this->putModuleScript("courses");

    	return array(
    		'courses.overview' => array(
                'type'      => 'courses', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'courses-widget',
   				'title' 	=> '<a id="courses-title" href="javascript: void(0);" class="filter">Course 1</a> <span class=""><i class="icon-sort-down"></i></span>',
   				'template'	=> $this->template("overview.widget"),
                'icon'      => 'book',
                'box'       => 'dark-blue',
                'tools'     => array(
                    'search'        => true,
                    'reload'        => true,
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

        

        if (unserialize($unitArray['metadata'])) {
            $unitArray['metadata'] = unserialize($unitArray['metadata']);
        }
        if ($unitArray['ctg_type'] == "video") {
            //,file: 'http://aulas.sysclass.com/extensao/mainframe/web/sql/mainfweb_sql_aula01.flv',techOrder:['html5','flash'],'width':'640','height':'360'}

            $unitArray['data'] = json_decode(utf8_encode($unitArray['data']), true);   
            if (!is_array($unitArray['data'])) {
                $unitArray['data'] = $this->getVideoDefaults();
            } else {
                $unitArray['data'] = array_merge($this->getVideoDefaults(), $unitArray['data']);
            }

            $unitArray['data']['video'] = $this->getVideoSource($course, $lesson, $content);
        }
        return $unitArray;
    }

    public function getVideoSource($course, $lesson, $content)
    {
        $urlRoot = sprintf("http://aulas.sysclass.com/layout/%s/%s/%s/", $course, $lesson, $content);
        $urlRoot = sprintf("/video/%s/%s/", $lesson, $content);
        
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
    protected function getVideoDefaults() {
        return array(
            //'poster'    =>  "http://aulas.sysclass.com/upload/ult.jpg",
            'poster'    =>  "/assets/sysclass.default/img/video-poster.png",
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
