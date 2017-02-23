<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class TutoriaModule extends SysclassModule implements IWidgetContainer, IBreadcrumbable, IActionable
{
    protected $_modelRoute = "tutoria";
    /* ISummarizable */
    /*
    public function getSummary() {
        $data = array(1);

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => $this->translate->translate('Questions Answered'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }
    */
    /* IWidgetContainer */
    public function getWidgets($widgetsIndexes = array(), $caller = null) {
        if (in_array('tutoria.widget', $widgetsIndexes)) {
            $this->putScript("plugins/jquery.pulsate.min");

            $this->putModuleScript("widget.tutoria");
            //$tutorias = $this->dataAction();
            //$this->putItem("tutoria", $tutorias);

        	return array(
        		'tutoria.widget' => array(
                    'type'      => 'tutoria', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                    'id'        => 'tutoria-widget',
       				'title' 	=> $this->translate->translate('Questions & Answers'),
       				'template'	=> $this->template("tutoria.widget"),
                    'icon'      => 'book',
                    'box'       => 'dark-blue tabbable',
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

    /* ILinkable */
    /*
    public function getLinks() {
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model("tutoria")->getItems();

            return array(
                'communication' => array(
                    array(
                        'count' => count($itemsData),
                        'text'  => $this->translate->translate('Tutoria'),
                        'icon'  => 'fa fa-book ',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }
    */
    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-book',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Tutoria")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Tutoria"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Tutoria"));
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
                    'text'      => $this->translate->translate('New Tutoria'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus'
                )
            )
        );

        return $actions[$request];
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/:model/:id
     */
    public function getItemAction($model, $id) {
        if ($model == "me") {
            $modelRoute = $this->_modelRoute;
        } else {
            return $this->invalidRequestError();
        }
        $editItem = $this->model($modelRoute)->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS

        return $editItem;
    }


    /**
     * [ add a description ]
     *
     * @url POST /item/:model
     */
    public function addItemAction($model)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $modelRoute = $this->_modelRoute;
                $redirect = true;
            } elseif ($model == "question") {
                $modelRoute = $this->_modelRoute;
                $redirect = false;
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($modelRoute);
            //$data['login'] = $userData['login'];
            // DEFAULTS
            $data['question_user_id'] = $userData['id'];
            $data['question_timestamp'] = time();

            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                if ($redirect) {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        $this->translate->translate("Your question has been registered!"),
                        "success"
                    );
                } else {
                    $modelData = $itemModel->getItem($data['id']);
                    $data = array_merge($data, $modelData);

                    $response = $this->createAdviseResponse($this->translate->translate("Your question has been registered!"), "success");
                    return array_merge($response, $data);

                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        $this->translate->translate("Your question has been registered!"),
                        "success"
                    );

                }
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("There's ocurred a problen when the system tried to save your data. Please check your data and try again", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url PUT /item/:model/:id
     */
    public function setItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $modelRoute = $this->_modelRoute;
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($modelRoute);

            if ($itemModel->setItem($data, $id) !== FALSE) {

                $modelData = $this->model($modelRoute)->getItem($id);
                $data = array_merge($data, $modelData);

                $response = $this->createAdviseResponse($this->translate->translate("Item updated."), "success");
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
     * @url DELETE /item/:model/:id
     */
    public function deleteItemAction($model, $id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($this->_modelRoute);
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Item removed."), "success");
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
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:data
     */
    public function getItemsAction($model, $type)
    {
        if ($model == "me") {
            $modelRoute = $this->_modelRoute;
            $optionsRoute = "edit";
        } elseif ($model == "question") {
            $modelRoute = $this->_modelRoute;

        } else {
            return $this->invalidRequestError();
        }


        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        //$modelRoute = "users/groups/collection";
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();


        $items = $itemsData;

        if ($type === 'combo') {
            /*
            $q = $_GET['q'];

            $items = $itemsCollection->filterCollection($items, $q);

            foreach($items as $course) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($course['id']),
                    'name'  => $course['name']
                );
            }
            return $result;
            */
        } elseif ($type === 'datatable') {

            $items = array_values($items);
            foreach($items as $key => $item) {
                if ($model == "me") {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'icon-edit',
                            'link'  => $baseLink . $optionsRoute . "/" . $item['id'],
                            'class' => 'btn-sm btn-primary'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                } elseif ($model == "lesson-content") {
                    $items[$key]['options'] = array(
                        'select'  => array(
                            'icon'  => 'icon-check',
                            'class' => 'btn-sm btn-primary'
                        )
                    );
                }
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }

        return array_values($items);
    }


    /**
     * [ add a description ]
     *
     * @url GET /data
     * @deprecated 3.0.0.19
     */
    public function dataAction()
    {
        $page   = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 10;

        $currentUser    = $this->getCurrentUser(true);

        //$xuserModule = $this->loadModule("xuser");
<<<<<<< HEAD
        $userUnits = $currentUser->getUnits();
        $unitsIds = array_keys($userUnits);
=======
        $userLessons = $currentUser->getLessons();
        $lessonsIds = array_keys($userLessons);
>>>>>>> parent of 7cdd908... lesson complete

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
     * [ add a description ]
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
<<<<<<< HEAD
        $userUnits = $currentUser->getUnits();
        $unitsIds = array_keys($userUnits);
=======
        $userLessons = $currentUser->getLessons();
        $lessonsIds = array_keys($userLessons);
>>>>>>> parent of 7cdd908... lesson complete

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
     * [ add a description ]
     *
     * @url POST /insert
     * @deprecated 3.0.0.19
     */
    /*
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
                return $this->createResponse(200, '', "success", "advise");
            } else {
                return $this->createResponse(200, $this->translate->translate("An error ocurred when trying to register your question!"), "danger", "advise");
            }
        }
    }
    */
    /**
     * [ add a description ]
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
