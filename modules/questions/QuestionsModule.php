<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class QuestionsModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable , IBlockProvider
{
    protected $_modelRoute = "questions";
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $itemsData = $this->model($this->_modelRoute)->addFilter(array(
                'active'    => true
            ))->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "classe", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => count($items),
                        'text'  => self::$t->translate('Questions'),
                        'icon'  => 'fa fa-question',
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
                'icon'  => 'fa fa-question',
                'link'  => $this->getBasePath() . "view",
                'text'  => self::$t->translate("Questions")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New Question"));
                break;
            }
            case "edit/:identifier" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Question"));
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
                    'text'      => self::$t->translate('New Question'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus'
                )
            )
        );

        return $actions[$request];
    }

    public function registerBlocks() {
        return array(
            'questions.list' => function($data, $self) {
                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");
                //$self->putComponent("select2");
                //$self->putComponent("bootstrap-editable");

                //$block_context = $self->getConfig("blocks\\blocks.questions.list\\context");
                //$self->putItem("questions_list_block_context", $block_context);

                $self->putModuleScript("blocks.questions.list");
                //$self->setCache("blocks.questions.list", $block_context);

                $self->putSectionTemplate("questions-list", "blocks/questions.list");

                $self->putBlock('questions.select.dialog');

                return true;
            },
            'questions.select.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("data-tables");
                $self->putComponent("select2");
                //$self->putComponent("bootstrap-editable");

                $block_context = $self->getConfig("blocks\\questions.select.dialog\\context");
                $self->putItem("questions_select_block_context", $block_context);

                $self->putModuleScript("dialogs.questions.select");
                $self->setCache("dialogs.questions.select", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/questions.select");

                return true;
            }
        );
    }


    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    public function addPage()
    {
        $items = $this->model("courses/areas/collection")->addFilter(array(
            'active' => 1
        ))->getItems();

        $this->putitem("knowledge_areas", $items);

        $items = $this->model("questions/types")->getItems();
        $this->putItem("questions_types", $items);

        $items =  $this->model("questions/difficulties")->getItems();
        $this->putItem("questions_difficulties", $items);

        parent::addPage($id);

    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:identifier
     */
    public function editPage($identifier)
    {
        $items = $this->model("courses/areas/collection")->addFilter(array(
            'active' => 1
        ))->getItems();

        $this->putitem("knowledge_areas", $items);

        $items = $this->model("questions/types")->getItems();
        $this->putItem("questions_types", $items);

        $items =  $this->model("questions/difficulties")->getItems();
        $this->putItem("questions_difficulties", $items);


        parent::editPage($identifier);
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
            } else {
                return $this->invalidRequestError();
            }

            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model($modelRoute);
            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("Question created with success"),
                    "success"
                );
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

                $response = $this->createAdviseResponse(self::$t->translate("Question updated with success"), "success");
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
                $response = $this->createAdviseResponse(self::$t->translate("Question removed with success"), "success");
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
        } elseif ($model == "lesson-content") {
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



}
