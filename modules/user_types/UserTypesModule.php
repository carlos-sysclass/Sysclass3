<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */
error_reporting(E_ALL);

class UserTypesModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable
{


    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {
            $groupItems = $this->model("users/groups/collection")->addFilter(array(
                'active'    => true
            ))->getItems();
            // $items = $this->module("permission")->checkRules($itemsData, "course", 'permission_access_mode');

            return array(
                'users' => array(
                    array(
                        'count' => count($groupItems),
                        'text'  => self::$t->translate('Groups'),
                        'icon'  => 'icon-group',
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
                'icon'  => 'icon-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => self::$t->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Users Groups")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Users Groups")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("New Group"));
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Users Groups")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Group"));
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
                    'text'      => self::$t->translate('New Group'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )
        );

        return $actions[$request];
    }

    /**
     * Get the institution visible to the current user
     *
     * @url GET /item/me/:id
    */
    public function getItemAction($id) {
        $editItem = $this->model("users/groups/collection")->getItem($id);
        return $editItem;
    }

    /**
     * Insert a news model
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        $request = $this->getMatchedUrl();

        $itemModel = $this->model("user/groups/item");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            //$data['login'] = $userData['login'];
            if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
                return $this->createRedirectResponse(
                    $this->getBasePath() . "edit/" . $data['id'],
                    self::$t->translate("Group created with success"),
                    "success"
                );
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * Update a news model
     *
     * @url PUT /item/me/:id
     */
    public function setItemAction($id)
    {
        $itemModel = $this->model("user/groups/item");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Group updated with success"), "success");
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
     * DELETE a news model
     *
     * @url DELETE /item/me/:id
     */
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("user/groups/item");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Group removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * Get all users visible to the current user
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    public function getItemsAction($type)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRoute = "users/groups/collection";
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
                        'link'  => $baseLink . "edit/" . $item['id'],
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

}
