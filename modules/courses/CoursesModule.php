<?php 
class CoursesModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets() {
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
     * @url GET /content/:course/:lesson
     */
    public function getContentByCourseAndLessonAction($course, $lesson)
    {
        // RETURN JUST THE content ID
        return $this->getContent($course, $lesson, null);
    }

    /**
     * Module Entry Point
     *
     * @url GET /content/:course/:lesson/:content
     */
    public function getContentAction($course, $lesson, $content)
    {
        return $this->getContent($course, $lesson, $content);
    }

    protected function getContent($course, $lesson, $content = null) {
        /**
          * @todo Check for user access, if he is enrolled on the course, and check content rules
         */
        /**
          * @todo Migrar este código para um Model, dentro do módulo
         */
        $currentUser    = $this->getCurrentUser(true);

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
        } else {
            $found = false;
            foreach ($filterIterator as $key => $value) {
                if ($content == $key) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return $this->invalidRequestError();
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

        if (unserialize($unitArray['metadata'])) {
            $unitArray['metadata'] = unserialize($unitArray['metadata']);
        }
        return $unitArray;
    }

}
