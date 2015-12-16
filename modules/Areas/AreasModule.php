<?php
namespace Sysclass\Modules\Areas;
/**
 * Module Class File
 * @filesource
 */
use \Sysclass\Models\Users\User;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/areas")
 */
class AreasModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable
{
    /* ILinkable */
    public function getLinks() {
        //$depinject = Phalcon\DI::getDefault();
        if ($this->acl->isUserAllowed(null, "Areas", "View")) {
            $itemsData = $this->model("courses/areas/collection")->addFilter(array(
                'active'    => true
            ))->getItems();
            //$items = $this->module("permission")->checkRules($itemsData, "area", 'permission_access_mode');

            return array(
                'content' => array(
                    array(
                        'count' => count($items),
                        'text'  => $this->translate->translate('Departments'),
                        'icon'  => 'fa fa-cubes',
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
                'text'  => $this->translate->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-cubes',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Departments")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Department"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Department"));
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
                    'text'      => $this->translate->translate('New Department'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
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

    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    public function addPage()
    {
        $coordinators = User::find(
            "can_be_coordinator = 1"
        );
        $this->putItem("coordinators", $coordinators->toArray());

        // HANDLE PAGE
        parent::addPage($id);
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     */
    public function editPage($id)
    {
        $coordinators = User::find(
            "can_be_coordinator = 1"
        );
        $this->putItem("coordinators", $coordinators->toArray());


        parent::editPage($id);
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/users/:course_id
    */
    /*
    public function getUsersInCourse($course_id) {
        $data = $this->getHttpData(func_get_args());

        $userCourseModel = $this->model("user/courses/item");

        $users = $userCourseModel->getUsersInCourse($course_id);

        return $users;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/users/switch
    */
    /*
    public function switchUserInGroup() {
        $data = $this->getHttpData(func_get_args());

        $userCourseModel = $this->model("user/courses/item");

        $status = $userCourseModel->switchUserInCourse(
            $data['course_id'],
            $data['user_login']
        );

        if ($status == 1) {
            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse($this->translate->translate("User added to group with success"), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse($this->translate->translate("User removed from group with success"), "error");
        }
        return array_merge($response, $info);
    }
    */
    /**
     * [ add a description ]
     *
     * @url GET /items/:model
     * @url GET /items/:model/:type
     * @url GET /items/:model/:type/:filter
     */
    /*
    public function getItemsAction($model = "me", $type = "default", $filter = null)
    {
            $modelRoute = "courses/areas/collection";
            $optionsRoute = "edit";

            $itemsCollection = $this->model($modelRoute);
            $itemsData = $itemsCollection->getItems();
            $itemsData = $this->module("permission")->checkRules($itemsData, "course", 'permission_access_mode');
        if ($type === 'combo') {
            $q = $_GET['q'];
            $itemsData = $itemsCollection->filterCollection($itemsData, $q);

            $result = array();

            foreach($itemsData as $item) {
                // @todo Group by course
                $result[] = array(
                    'id'    => intval($item['id']),
                    'name'  => ($model ==  "instructor") ? $item['name'] . ' ' . $item['surname'] : $item['name']
                );
            }
            return $result;
        } elseif ($type === 'datatable') {

            $itemsData = array_values($itemsData);
            foreach($itemsData as $key => $item) {
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
    */
    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
     */
    public function getItemAction($id) {

        $editItem = $this->model("courses/areas/collection")->getItem($id);
        // TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
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

            $itemModel = $this->model("courses/areas/collection");
            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    $this->translate->translate("Department created with success"),
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

            $itemModel = $this->model("courses/areas/collection");
            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Department updated with success"), "success");
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
     * @url DELETE /item/me/:id
     */
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("courses/areas/collection");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Department removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problem when the system tried to remove your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

}
