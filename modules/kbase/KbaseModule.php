<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class KbaseModule extends SysclassModule implements ISummarizable, IWidgetContainer, ILinkable, IBreadcrumbable, IActionable
{
    protected $_modelRoute = "kbase";
    /* ISummarizable */
    public function getSummary() {
        $data = array(1);

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

    /* IWidgetContainer */
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('kbase.widget', $widgetsIndexes)) {
            $this->putScript("plugins/jquery.pulsate.min");

            $this->putModuleScript("widget.kbase");
            //$tutorias = $this->dataAction();
            //$this->putItem("tutoria", $tutorias);

        	return array(
        		'kbase.widget' => array(
                    'type'      => 'kbase', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                    'id'        => 'kbase-widget',
       				'title' 	=> self::$t->translate('Questions & Answers'),
       				'template'	=> $this->template("widgets/overview"),
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
    public function getLinks() {

        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model($this->_modelRoute)->getItems();
            //$items = $this->module("permission")->checkRules($itemsData, "test", 'permission_access_mode');

            return array(
                'communication' => array(
                    array(
                        'count' => count($itemsData),
                        'text'  => self::$t->translate('Knowledge Base'),
                        'icon'  => 'fa fa-book ',
                        'link'  => $this->getBasePath() . 'view'
                    )
                )
            );
        }
    }

    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-book',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Knowledge Base")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New KB Item"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit KB Item"));
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
                    'text'      => self::$t->translate('New KB Item'),
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
                        self::$t->translate("Your question has been successfully registered!"),
                        "success"
                    );
                } else {
                    $modelData = $itemModel->getItem($data['id']);
                    $data = array_merge($data, $modelData);

                    $response = $this->createAdviseResponse(self::$t->translate("Your question has been successfully registered!"), "success");
                    return array_merge($response, $data);

                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        self::$t->translate("Your question has been successfully registered!"),
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

                $response = $this->createAdviseResponse(self::$t->translate("Item updated with success"), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
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
                $response = $this->createAdviseResponse(self::$t->translate("Item removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
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


        // $items = $this->module("permission")->checkRules($itemsData, "users", 'permission_access_mode');
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
