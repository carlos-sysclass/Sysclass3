<?php 
class TutoriaModule extends SysclassModule implements IWidgetContainer
{
    public function getWidgets() {
        $tutorias = $this->dataAction();
        $this->putItem("tutoria", $tutorias);

    	return array(
    		'tutoria.widget' => array(
                'id'        => 'tutoria-widget',
   				'title' 	=> 'Questions & Awnsers',
   				'template'	=> $this->template("tutoria.widget"),
                'icon'      => 'book',
                'box'       => 'dark-blue',
                'tools'     => array(
                    'search'        => true,
                    'collapse'      => true,
                    'reload'        => true,
                    'fullscreen'    => true
                ),
                'data'  => $tutorias
    		)
    	);
    }

    

    /**
     * Module Entry Point
     *
     * @url GET /data
     */
    public function dataAction()
    {
        $page   = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 10;

        $currentUser    = self::$current_user;
        
        //$xuserModule = $this->loadModule("xuser");
        $userLessons = $currentUser->getLessons();
        $lessonsIds = array_keys($userLessons);

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
            u2.avatar as answer_avatar_id,
            tt.answer",
            sprintf("tt.lessons_ID IN (%s) ", implode(",", $lessonsIds)),
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
    /**
     * Module Entry Point
     *
     * @url GET /data/:topic
     */
    public function dataTopicAction($topic)
    {
        if ($topic == 0) {
            return array();
        }

        $currentUser    = self::$current_user;
        
        //$xuserModule = $this->loadModule("xuser");
        $userLessons = $currentUser->getLessons();
        $lessonsIds = array_keys($userLessons);

        // GET LAST MESSAGES FROM USER LESSONS
        $forum_messages = $this->_getTableData("f_messages fm
            JOIN f_topics ft
            JOIN f_forums ff
            LEFT OUTER JOIN lessons l ON ff.lessons_ID = l.id",
            "ft.title, ft.id as topic_id, ft.users_LOGIN, fm.timestamp, l.name as lessons_name, ff.lessons_id",
            sprintf("ft.f_forums_ID=ff.id AND fm.f_topics_ID=ft.id AND ff.lessons_ID IN (%s) AND fm.f_topics_ID = %d",
            implode(",", $lessonsIds), $topic),
            "fm.timestamp ASC"
        );
        return $forum_messages;
    }
        
}
