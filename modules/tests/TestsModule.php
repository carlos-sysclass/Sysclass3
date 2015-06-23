<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class TestsModule extends SysclassModule implements ISummarizable, ILinkable, IBreadcrumbable, IActionable
{
    private static $suitable_translate_contents = array("subtitle");

    /* ISummarizable */
    public function getSummary() {
        $data = array(1);

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => self::$t->translate('Scheduled Tests'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model("tests")->addFilter(array(
                'active'    => true
            ))->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "test", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => count($items),
                        'text'  => self::$t->translate('Tests'),
                        'icon'  => 'fa fa-list-ol ',
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
                'icon'  => 'icon-bookmark',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Tests")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New Test"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Test"));
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
                    'text'      => self::$t->translate('New Test'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus'
                )/*,
                array(
                    'separator' => true,
                ),
                array(
                    'text'      => 'Add New 2',
                    'link'      => $this->getBasePath() . "add",
                    //'class'       => "btn-primary",
                    //'icon'      => 'icon-plus'
                )*/
            )
        );

        return $actions[$request];
    }
    /*
    public function registerBlocks()
    {
        return array(
            'tests.questions.edit' => function ($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("jquery-file-upload-image");
                $self->putComponent("jquery-file-upload-video");
                $self->putComponent("jquery-file-upload-audio");
                $self->putComponent("bootstrap-confirmation");

                $self->putModuleScript("translate", "models.translate");

                $self->putModuleScript("blocks.lessons.content");



                $languages = self::$t->getItems();

                $userLanguageCode =  self::$t->getUserLanguageCode();



                foreach ($languages as &$value) {
                    if ($value['code'] == $userLanguageCode) {
                        $value['selected'] = true;
                        break;
                    }
                }

                //$block_context = $self->getConfig("blocks\\roadmap.courses.edit\context");
                $self->putItem("languages", $languages);

                $self->putSectionTemplate("lessons_content", "blocks/lessons.content");
                //$self->putSectionTemplate("foot", "dialogs/season.add");
                //$self->putSectionTemplate("foot", "dialogs/class.add");

                return true;
            }
        );
    }
    */

    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    public function addPage()
    {
        $items = $this->model("classes")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);


        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:identifier
     */
    public function editPage($identifier)
    {
        $items = $this->model("classes")->addFilter(array(
            'active' => true
        ))->getItems();

        $this->putItem("classes", $items);

        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);




        parent::editPage($identifier);
    }

    /**
     * [ add a description ]
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
        if ($model == "me") {
            $modelRoute = "tests";
            $optionsRoute = "edit";

            $itemsCollection = $this->model($modelRoute);
            if (!empty($filter)) {
                $filter = json_decode($filter, true);
                if (is_array($filter)) {
                    // SANITIZE ARRAY
                    $itemsCollection->addFilter($filter);
                }
            }
            //var_dump($filter);
            //exit;
            $itemsData = $itemsCollection->getItems();
            //$itemsData = $this->module("permission")->checkRules($itemsData, "lesson", 'permission_access_mode');
        } elseif ($model == "question") {
            $modelRoute = "tests/question";
            $optionsRoute = "edit";

            $itemsCollection = $this->model($modelRoute);

            if (!empty($filter)) {
                $filter = json_decode($filter, true);
                if (is_array($filter)) {
                    // SANITIZE ARRAY
                    $itemsCollection->addFilter($filter);
                }
            }

            $itemsData = $itemsCollection->getItems();
        } else {
            return $this->invalidRequestError();
        }

        if ($type === 'combo') {
            $query = $_GET['q'];
            $itemsData = $itemsCollection->filterCollection($itemsData, $query);

            $result = array();

            foreach ($itemsData as $item) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($item['id']),
                    'name'  => $item['name']
                );
            }
            return $result;
        } elseif ($type === 'datatable') {
            $itemsData = array_values($itemsData);
            foreach ($itemsData as $key => $item) {
                $itemsData[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $this->getBasePath() . $optionsRoute . "/" . $item['id'],
                        'class' => 'btn-sm btn-primary'
                    ),
                    'remove'    => array(
                        'icon'  => 'icon-remove',
                        'class' => 'btn-sm btn-danger'
                    )
                );
            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($itemsData),
                'iTotalDisplayRecords'  => count($itemsData),
                'aaData'                => array_values($itemsData)
            );
        }

        return array_values($itemsData);
    }

    /**
     * [ add a description ]
     *
     * @url PUT /items/:model/set-order/:lesson_id
     */
    public function setOrderAction($model, $lesson_id)
    {
        if ($model == "me") {
            return $this->invalidRequestError();
        } elseif ($model == "question") {
            $modelRoute = "tests/question";
            $optionsRoute = "edit";
        } else {
            return $this->invalidRequestError();
        }


        $itemsCollection = $this->model($modelRoute);
        // APPLY FILTER
        if (is_null($lesson_id) || !is_numeric($lesson_id)) {
            return $this->invalidRequestError();
        }

        $messages = array(
            'success' => "Question order updated with success",
            'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
        );

        $data = $this->getHttpData(func_get_args());

        if ($itemsCollection->setOrder($lesson_id, $data['position'])) {
            return $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
        } else {
            return $this->invalidRequestError(self::$t->translate($messages['success']), "success");
        }
    }


    /**
     * [ add a description ]
     *
     * @url GET /item/:model/:identifier
     */
    public function getItemAction($model = "me", $identifier = null)
    {
        if ($model == "me") {
            $itemModel = $this->model("tests");
        } elseif ($model == "question") {
            $itemModel = $this->model("tests/question");
        }

        $editItem = $itemModel->getItem($identifier);

        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/:model
     */
    public function addItemAction($model, $type)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($model == "me") {
                $itemModel = $this->model("tests");
                $messages = array(
                    'success' => "Lesson created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question created with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );

                $data['language_code'] = self::$t->getUserLanguageCode();

                $_GET['redirect'] = "0";
            } else {
                return $this->invalidRequestError();
            }



            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== false) {
                if ($_GET['redirect'] === "0") {
                    $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                    return array_merge($response, $data);
                } else {
                    return $this->createRedirectResponse(
                        $this->getBasePath() . "edit/" . $data['id'],
                        self::$t->translate($messages['success']),
                        "success"
                    );
                }
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($messages['error'], "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url PUT /item/:model/:identifier
     */
    public function setItemAction($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($model == "me") {
                $itemModel = $this->model("tests");
                $messages = array(
                    'success' => "Lesson updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question updated with success",
                    'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            if ($itemModel->setItem($data, $identifier) !== false) {
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                $data = $itemModel->getItem($identifier);
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @url DELETE /item/:model/:identifier
     */
    public function deleteItemAction($model, $identifier)
    {
        if ($userData = $this->getCurrentUser()) {
            if ($model == "me") {
                $itemModel = $this->model("tests");
                $messages = array(
                    'success' => "Lesson removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } elseif ($model == "question") {
                $itemModel = $this->model("tests/question");
                $messages = array(
                    'success' => "Question removed with success",
                    'error' => "There's ocurred a problem when the system tried to remove your data. Please check your data and try again"
                );
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            if ($itemModel->deleteItem($identifier) !== false) {
                $response = $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError(self::$t->translate($messages['error']), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

}
