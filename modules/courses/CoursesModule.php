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
   				'title' 	=> '<a id="courses-title" href="javascript: void(0);" class="filter">Course 1</a>',
   				'template'	=> $this->template("overview.widget"),
                'icon'      => 'bolt',
                'box'       => 'dark-blue',
                'tools'     => array(
                    'search'        => true,
                    'reload'        => true,
                    'fullscreen'    => true,
                    'filter'        => true
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
}
