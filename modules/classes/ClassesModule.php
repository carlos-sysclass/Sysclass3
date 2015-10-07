<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class ClassesModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable, IBlockProvider
{
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
//
        $depinject = Phalcon\DI::getDefault();
        if ($depinject->get("acl")->isUserAllowed(null, "Classes", "View")) {
            $itemsData = $this->model("classes")->addFilter(array(
                'active'    => true
            ))->getItems();
            $items = $this->module("permission")->checkRules($itemsData, "classe", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => count($items),
                        'text'  => self::$t->translate('Classes'),
                        'icon'  => 'fa fa-folder',
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
                'text'  => self::$t->translate("Classes")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("New Class"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Class"));
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
                    'text'      => self::$t->translate('New Class'),
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

    public function registerBlocks() {
        return array(
            'classes.lessons.edit' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("bootstrap-confirmation");
                $self->putComponent("bootstrap-editable");

                $self->putModuleScript("blocks.classes.lessons.edit");

                $self->putSectionTemplate("lessons", "blocks/lessons.edit");

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
        /*
        $items = $this->model("courses/collection")->addFilter(array(
            'active' => true
        ))->getItems();
        $this->putItem("courses", $items);
        */
        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);

        parent::addPage($id);

    }

    /**
     * [ add a description ]
     *
     * @url GET /edit/:id
     */
    public function editPage($id)
    {
        /*
        $items = $this->model("courses/collection")->addFilter(array(
            'active' => true
        ))->getItems();
        $this->putItem("courses", $items);
        */
        $items =  $this->model("users/collection")->addFilter(array(
            'can_be_instructor' => true
        ))->getItems();
        $this->putItem("instructors", $items);

        parent::editPage($id);
    }




    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
     */
    public function getItemAction($id) {

        $editItem = $this->model("classes")->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
        /*
        $editItem['lessons'] = $this->model("classes/lessons/collection")->addFilter(array(
            'class_id' => $id
        ))->getItems($id);
        */
        return $editItem;
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("classes");
            $data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("Class created with success"),
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
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("classes");
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Class updated with success"), "success");
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
     * @url DELETE /item/me/:id
     */
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("courses/classes/collection");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Class removed with success"), "success");
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
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    public function getItemsAction($type)
    {
        $modelRoute = "roadmap/classes";
        $optionsRoute = "edit";

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
                $items[$key]['options'] = array(
                    'edit'  => array(
                        'icon'  => 'icon-edit',
                        'link'  => $baseLink . $optionsRoute . "/" . $item['class_id'],
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
     * @url PUT /items/lessons/set-order/:class_id
     */
    public function setLessonOrderAction($class_id)
    {
        $modelRoute = "base/lessons";

        $itemsCollection = $this->model($modelRoute);
        // APPLY FILTER
        if (is_null($class_id) || !is_numeric($class_id)) {
            return $this->invalidRequestError();
        }

        $messages = array(
            'success' => "Lesson order updated with success",
            'error' => "There's ocurred a problem when the system tried to save your data. Please check your data and try again"
        );

        $data = $this->getHttpData(func_get_args());

        if ($itemsCollection->setOrder($class_id, $data['position'])) {
            return $this->createAdviseResponse(self::$t->translate($messages['success']), "success");
        } else {
            return $this->invalidRequestError(self::$t->translate($messages['success']), "success");
        }
    }

}
