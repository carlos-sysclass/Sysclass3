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
   				'title' 	=> '<span id="courses-title">Course 1</span> <i class="icon-arrow-right" style="float: none"></i> <span id="lessons-title">Lesson 1</span>',
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
        $userCourses    =   $currentUser -> getUserCourses($constraints);

        $userLessons = $currentUser->getLessons();
        $lessonsIds = array_keys($userLessons);

        foreach($userCourses as $course) {
            $courses[] = array(
                'id'        => $course->course['id'],
                'name'      => $course->course['name'],
                'lessons'   => array()
            );

            $lessons = $course->getCourseLessons();

        }



        var_dump($courses);


        



        var_dump($userLessons);
        exit;

        // GET LAST MESSAGES FROM USER LESSONS
        $tutorias = $this->_getTableData("mod_tutoria tt
            LEFT OUTER JOIN lessons l ON (tt.lessons_ID = l.id)
            LEFT OUTER JOIN users u1 ON (tt.question_user_id = u1.id)
            LEFT OUTER JOIN users u2 ON (tt.answer_user_id = u2.id)",
            "tt.id, tt.lessons_ID, tt.unit_ID, tt.title, 
            tt.question_timestamp, 
            tt.question_user_id, 
            u1.name as question_user_name,
            u1.surname as question_user_surname,
            u1.avatar as question_avatar_id, 
            tt.question, 
            tt.answer_timestamp, 
            tt.answer_user_id, 
            u2.name as answer_user_name,
            u2.surname as answer_user_surname,
            u2.avatar as answer_avatar_id,
            tt.answer,
            tt.approved",
            sprintf("tt.lessons_ID IN (0, %s) AND (tt.approved = 1 OR tt.question_user_id = %d)", implode(",", $lessonsIds), $currentUser->user['id']),
            "tt.question_timestamp DESC",
            "",
            sprintf("%d, %d", ($page - 1) * $per_page, $per_page)
        );

        foreach($tutorias as $key => $tut) {
            /* @todo DOUBLE-CHECK AVATAR CODE */
            $small_user_avatar = $big_user_avatar = array();
            try {
                $file = new MagesterFile($tut['question_avatar_id']);
                list($question_avatar['width'], $question_avatar['height']) = sC_getNormalizedDims($file['path'], 45, 45);
                $tutorias[$key]['question_avatar'] = $file['path'];
            } catch (MagesterFileException $e) {
                $tutorias[$key]['question_avatar'] = array(
                    'avatar' => "img/avatar_small.png",
                    'width'  => 45,
                    'height' => 45
                );
            }

            try {
                $file = new MagesterFile($tut['answer_avatar_id']);
                list($question_avatar['width'], $question_avatar['height']) = sC_getNormalizedDims($file['path'], 45, 45);
                $tutorias[$key]['answer_avatar'] = $file['path'];
            } catch (MagesterFileException $e) {
                $tutorias[$key]['answer_avatar'] = array(
                    'avatar' => "img/avatar_small.png",
                    'width'  => 45,
                    'height' => 45
                );
            }
        }

        return $tutorias;
    }
}
