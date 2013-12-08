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
     * @url GET /content/:course/:lesson/:set_as_current
     */
    public function getContentByCourseAndLessonAction($course, $lesson, $set_as_current = false)
    {
        /**
          * @todo Check for user access, if he is enrolled on the course, and check content rules
         */
        /**
          * @todo Migrar este código para um Model, dentro do módulo
         */
        // SET CURRENT COURSE AND LESSON IF $set_as_current !== FALSE
        if ($set_as_current !== FALSE) { 

        }
        $currentUser    = $this->getCurrentUser(true);

        $currentLesson = new MagesterLesson($lesson);
        $currentContent = new MagesterContentTree($currentLesson);
        $currentContent -> markSeenNodes($currentUser);

        //Legal values are the array of entities that the current user may actually edit or change.
        $classeData = sC_getTableData("users_to_courses", "classe_id", sprintf("users_LOGIN = '%s'", $currentUser -> user['login']));

        // GET USER CLASS
        $courseClass = $classeData[0]['classe_id'];

        $filterIterator = new MagesterVisitableFilterIterator(
            new MagesterContentCourseClassFilterIterator(
                new MagesterNodeFilterIterator(
                    new RecursiveIteratorIterator(
                        new RecursiveArrayIterator($currentContent -> tree)
                        , RecursiveIteratorIterator :: SELF_FIRST
                    )
                ), 
                $courseClass
            )
        );

        $unseenContent = array();
        $contentID = false;
        foreach (new MagesterUnseenFilterIterator($filterIterator) as $key => $value) {
            $contentID = $key;
            break;
        }
        if ($contentID == FALSE) {
            foreach (new MagesterSeenFilterIterator($filterIterator) as $key => $value) {
                $contentID = $key;
                break;
            }
        }
        //var_dump($currentContent->getPreviousNodes($contentID));
        //var_dump($currentContent->getNextNodes($contentID));

        /**
          * @todo ESPELHAR NESTE MÉTODO PARA GERAR A ÁRVORE DE CONTEÙDO, PARA VISUALIZAÇÃO 
          * var_dump($currentContent->toPathStrings($filterIterator));
         */

        $currentUnit = new MagesterUnit($contentID);
        $unitArray = $currentUnit->getArrayCopy();

        if (unserialize($unitArray['metadata'])) {
            $unitArray['metadata'] = unserialize($unitArray['metadata']);
        }
        return $unitArray;

    }

}
