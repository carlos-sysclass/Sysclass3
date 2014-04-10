<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class TutoriaModule extends SysclassModule implements ISummarizable, IWidgetContainer
{
    public function getSummary() {
        $data = array(1); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => self::$t->translate('Questions Answered'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }
    
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('tutoria.widget', $widgetsIndexes)) {
            $this->putScript("plugins/jquery.pulsate.min");
            $this->putModuleScript("tutoria");
            //$tutorias = $this->dataAction();
            //$this->putItem("tutoria", $tutorias);

        	return array(
        		'tutoria.widget' => array(
                    'type'      => 'tutoria', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                    'id'        => 'tutoria-widget',
       				'title' 	=> self::$t->translate('Questions & Answers'),
       				'template'	=> $this->template("tutoria.widget"),
                    'icon'      => 'book',
                    'box'       => 'dark-blue',
                    'tools'     => array(
                        'search'        => true,
                        //'collapse'      => true,
                        //'reload'        => true,
                        'fullscreen'    => true
                    )
        		)
        	);
        }
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

        $currentUser    = $this->getCurrentUser(true);
        
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
    /**
     * Module Entry Point
     *
     * @url GET /data/:topic
     */
    /*
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
    */
    /**
     * Module Entry Point
     *
     * @url POST /insert
     */   
    public function insertTutoriaAction()
    {
        if ($currentUser    = $this->getCurrentUser()) {
            $defaults = array(
                'question_timestamp'    => time(),
                'lessons_ID'            => 0,
                'unit_ID'               => 0,
                'title'                 => '',
                'question_user_id'      => $currentUser['id'],
                'question'              => ''
            );
            $values = array();

            $values['title'] = $_POST['title'];
            if (!array_key_exists('question', $_POST)) {
                $values['question'] = $values['title'];
            } else {
                $values['question'] = $_POST['question'];
            }
            if (array_key_exists('lessons_ID', $_POST) && is_numeric($_POST['lessons_ID'])) {
                $values['lessons_ID'] = $_POST['lessons_ID'];
            }
            if (array_key_exists('unit_ID', $_POST) && is_numeric($_POST['unit_ID'])) {
                $values['unit_ID'] = $_POST['unit_ID'];
            }

            $values = array_merge($defaults, $values);
            $status = $this->_insertTableData("mod_tutoria", $values);
            if ($status) {
                return $this->createResponse(200, self::$t->translate("Your question has been successfully registered!"), "success", "advise");
            } else {
                return $this->createResponse(200, self::$t->translate("An error ocurred when trying to register your question!"), "danger", "advise");
            }
        }
        /*
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
            tt.answer",
            sprintf("tt.lessons_ID IN (%s) ", implode(",", $lessonsIds)),
            "tt.question_timestamp DESC",
            "",
            sprintf("%d, %d", ($page - 1) * $per_page, $per_page)
        );
        
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
        */
    }

    /**
     * Module Entry Point
     *
     * @url GET /chat/pool/:chat_index
     */
    public function chatMessagesAction($chat_index)
    {
        $timestamp = $_GET['_'];
        // CHECK LAST TIMESTAMP AND, IF HAS MESSAGES FROM THERE TO NOW, SEND THEN.

        return array(
        );
    }
        
}
