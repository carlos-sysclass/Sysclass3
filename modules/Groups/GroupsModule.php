<?php
namespace Sysclass\Modules\Groups;
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */
/**
 * @RoutePrefix("/module/groups")
 */
class GroupsModule extends \SysclassModule implements \ILinkable, \IBreadcrumbable, \IActionable, \IBlockProvider
{

    /* ILinkable */
    public function getLinks() {

        if ($this->acl->isUserAllowed(null, "Groups", "View")) {
            $groupItems = $this->model("users/groups/collection")->addFilter(array(
                'active'    => true
            ))->getItems();

            return array(
                'users' => array(
                    array(
                        'count' => count($groupItems),
                        'text'  => $this->translate->translate('Groups'),
                        'icon'  => 'fa fa-users',
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
                'text'  => $this->translate->translate("Home")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users Groups")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users Groups")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Group"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users Groups")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Group"));
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
                    'text'      => $this->translate->translate('New Group'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'icon-plus'
                )
            )
        );

        return $actions[$request];
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'group.definition' => function($data, $self) {
                $self->putComponent("data-tables");
                $self->putComponent("jquery-builder");

                // CREATE BLOCK CONTEXT
                $block_context = $self->getConfig("blocks\\group.definition\\context");

                //var_dump($block_context);
                //exit;
                $self->putItem("group_definition_context", $block_context);

                return true;
            }
        );
    }



    /**
     * [ add a description ]
     *
     * @Get("/add-dynamic")
     */
    public function addDynamicPage()
    {
        $model_info = $this->model_info['me'];

        if ($this->isResourceAllowed("create", $model_info)) {
            if (!$this->createClientContext("add-dynamic")) {
                $this->entryPointNotFoundError($this->getSystemUrl('home'));
            }
            $this->display($this->template);

        } else {
            $this->redirect($this->getSystemUrl('home'), "", 401);
        }
    }


    /**
     * [ add a description ]
     *
     * @Get("/item/users/{group_id}")
    */
    public function getUsersInGroup($group_id) {
        $data = $this->getHttpData(func_get_args());

        $userGroupModel = $this->model("user/groups/item");

        $users = $userGroupModel->getUsersInGroup($group_id);

        return $users;
    }

    /**
     * [ add a description ]
     *
     * @Post("/item/users/switch")
    */
    public function switchUserInGroup() {
        $data = $this->getHttpData(func_get_args());

        $userGroupModel = $this->model("user/groups/item");

        $status = $userGroupModel->switchUserInGroup(
            $data['group_id'],
            $data['user_id']
        );

        if ($status == 1) {
            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse($this->translate->translate("User added to group."), "success");
        } elseif ($status == -1) {
            // USER EXCLUÍDO AO GRUPO
            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse($this->translate->translate("User removed from group."), "error");
        }
        return array_merge($response, $info);
    }

    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
    */
    /*
    public function getItemAction($id) {
        $editItem = $this->model("users/groups/collection")->getItem($id);
        return $editItem;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    /*
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
                    $this->translate->translate("Group created."),
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
    */
    /**
     * [ add a description ]
     *
     * @url PUT /item/me/:id
     */
    /*
    public function setItemAction($id)
    {
        $itemModel = $this->model("user/groups/item");

        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            if ($itemModel->setItem($data, $id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Group updated."), "success");
                return array_merge($response, $data);
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError($this->translate->translate("There's ocurred a problen when the system tried to save your data. Please check your data and try again"), "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }  
    */

    /**
     * [ add a description ]
     *
     * @url DELETE /item/me/:id
     */
    /*
    public function deleteItemAction($id)
    {
        if ($userData = $this->getCurrentUser()) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = $this->model("user/groups/item");
            if ($itemModel->deleteItem($id) !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Group removed."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    /**
     * [ add a description ]
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    /*
    public function getItemsAction($type)
    {
        $currentUser    = $this->getCurrentUser(true);
        $dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRoute = "users/groups/collection";
        $baseLink = $this->getBasePath();

        $itemsCollection = $this->model($modelRoute);
        $itemsData = $itemsCollection->getItems();


        $items = $itemsData;

        if ($type === 'combo') {
 
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
    */
}
