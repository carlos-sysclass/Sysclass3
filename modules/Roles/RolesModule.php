<?php
namespace Sysclass\Modules\Roles;

use Phalcon\DI,
    Sysclass\Models\Acl\Role as AclRole,
    Sysclass\Models\Acl\Resource as AclResource,
    Sysclass\Models\Acl\RolesUsers,
    Sysclass\Models\Acl\RolesGroups,
    Sysclass\Models\Acl\RolesResources,
    Sysclass\Services\L10n\Timezones,
    Sysclass\Services\Authentication\Exception as AuthenticationException;
/**
 * Module Class File
 * @filesource
 */
/**
 * @RoutePrefix("/module/roles")
 */
class RolesModule extends \SysclassModule implements \IBlockProvider, \ILinkable, \IBreadcrumbable, \IActionable
{

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        if ($this->acl->isUserAllowed(null, "Roles", "View")) {
            $count = AclRole::count("active = 1");

            return array(
                'users' => array(
                    array(
                        'count' => $count,
                        'text'  => $this->translate->translate('Permissions'),
                        'icon'  => 'fa fa-shield',
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
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-shield',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Permissions")
                );
                return $breadcrumbs;
                break;
            }
            case "set-resources/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-shield',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Permissions")
                );
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-lock',
                    'text'   => $this->translate->translate("View Resources")
                );
                return $breadcrumbs;
                break;
            }
        }
    }

    /* IActionable */
    public function getActions() {
        $request = $this->getMatchedUrl();

        $actions = array(
            'view'  => array(
                array(
                    'text'      => $this->translate->translate('New permission'),
                    'link'      => "javascript:void(0)",
                    'class'     => "btn-primary dialog-create-role-open-action",
                    'icon'      => 'fa fa-plus'
                )/*,
                array(
                    'separator' => true,
                ),
                array(
                    'text'      => 'Add New 2',
                    'link'      => $this->getBasePath() . "add",
                    //'class'       => "btn-primary",
                    //'icon'      => 'fa fa-plus-square'
                )*/
            )
        );

        return $actions[$request];
    }

    public function registerBlocks() {
        return array(
            'roles.create.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");
                //$self->putComponent("select2");
                $self->putComponent("bootstrap-switch");

                //$block_context = $self->getConfig("blocks\\questions.select.dialog\\context");
                //$self->putItem("questions_select_block_context", $block_context);

                $self->putModuleScript("dialogs.roles.create");
                //$self->setCache("dialogs.questions.select", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/create");

                return true;
            },
            'roles.resources.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                // 
                // 
                // 
                // 
                // MUST SHOW ALL AVALIABLE CALENDARS TYPES
                //$this->createClientContext("set-resources");
                //if (!$this->createClientContext("set-resources", array('entity_id' => $id))) {
                  //  $this->entryPointNotFoundError($this->getSystemUrl('home'));
                //}
                

                //$roleModel = AclRole::findFirstById($id);

                //$this->putItem("role", $roleMOdel->toArray());

                $resources = AclResource::find()->toArray();
                $self->putItem("acl_resources", $resources);

                // GET ALL THIS DATA FROM config.yml
                $self->putComponent("data-tables");
                $self->putComponent("bootstrap-switch");
                $self->putComponent("select2");
                $self->putScript("scripts/utils.datatables");
                //$self->putComponent("bootstrap-switch");

                $block_context = $self->getConfig("blocks\\roles.resources.dialog\\context");
                $self->putItem("roles_resources_dialog_context", $block_context);

                $self->putModuleScript("dialogs.roles.resources");

                $self->putSectionTemplate("dialogs", "dialogs/resources");

                return true;
            },
            'roles.users.dialog' => function($data, $self) {
                // GET ALL THIS DATA FROM config.yml
                $self->putComponent("data-tables");
                $self->putComponent("select2");
                $self->putScript("scripts/utils.datatables");
                //$self->putComponent("bootstrap-switch");

                $block_context = $self->getConfig("blocks\\roles.users.dialog\\context");
                $self->putItem("roles_users_dialog_context", $block_context);

                $self->putModuleScript("dialogs.roles.users");

                $self->putSectionTemplate("dialogs", "dialogs/users");

                return true;
            },
        );
    }


    /**
     * [ add a description ]
     *
     * @url GET /add
     */
    /*
    public function addPage($id)
    {
        $languages = $this->translate->getItems();
        $this->putitem("languages", $languages);


        parent::addPage();
    }
    */

    /**
     * [ add a description ]
     *
     * @url GET /edit/:id
     */
    /*
    public function editPage($id)
    {
        $languages = $this->translate->getItems();
        $this->putitem("languages", $languages);

        $groups =  $this->model("users/groups/collection")->addFilter(array(
            'active' => true
        ))->getItems();
        $this->putItem("groups", $groups);

        parent::editPage($id);
    }
    */

    /**
     * [ add a description ]
     *
     * @url GET /set-resources/:id
    */
    /*
    public function setResourcesPage($id) {
        // MUST SHOW ALL AVALIABLE CALENDARS TYPES
        //$this->createClientContext("set-resources");
        if (!$this->createClientContext("set-resources", array('entity_id' => $id))) {
            $this->entryPointNotFoundError($this->getSystemUrl('home'));
        }

        $roleMOdel = AclRole::findFirstById($id);

        $this->putItem("role", $roleMOdel->toArray());

        $resources = AclResource::find()->toArray();
        $this->putItem("acl_resources", $resources);

        $this->display($this->template);
    }
    */

    /**
     * [ add a description ]
     *
     * @url GET /item/me/:id
    */
    /*
    public function getItemRequest($id) {
        $request = $this->getMatchedUrl();

        // TODO: CHECK PERMISSIONS

        if (strpos($request, "groups/") === 0) {
            $editItem = $this->model("users/groups/collection")->getItem($id);
        } else {
            $editItem = \Sysclass\Models\Users\User::findFirstById($id);

            return $editItem->toFullArray(array('Avatars'));
        }
        return $editItem;
    }
    */
    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    /*
    public function addItemRequest($id)
    {
        $request = $this->getMatchedUrl();

        if ($userData = $this->getCurrentUser()) {
            //$itemModel = $this->model("user/item");
            // TODO CHECK IF CURRENT USER CAN DO THAT
            $data = $this->getHttpData(func_get_args());
            $userModel = new AclRole();
            $userModel->assign($data);

            if ($userModel->save()) {
                return $this->createAdviseResponse(
                    $this->translate->translate("Role created."),
                    "success"
                );
            } else {
                $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please, try again."), "warning");
                return array_merge($response, $userModel->toFullArray());
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
    public function setItemRequest($id)
    {
        $request = $this->getMatchedUrl();

        //$itemModel = $this->model("user/item");

        if ($userModel = $this->getCurrentUser(true)) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = AclRole::findFirstById($id);
            $itemModel->assign($data);

            if ($itemModel->save()) {
                $response = $this->createAdviseResponse($this->translate->translate("Role updated."), "success");

                return array_merge($response, $itemModel->toArray());
            } else {
                $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please, try again."), "error");
                return array_merge($response, $itemModel->toArray());
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
    public function deleteItemRequest($id)
    {
        if ($userData = $this->getCurrentUser()) {

            $itemModel = AclRole::findFirstById($id);

            if ($itemModel->delete() !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Role removed."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please, try again.", "error");
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
    public function getItemsRequest($type)
    {

        $currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRS = AclRole::find();
        $items = array();
        foreach($modelRS as $key => $item) {
            $items[$key] = $item->toArray();
            //$news[$key]['user'] = $item->getUser()->toArray();;
        }

        if ($type === 'datatable') {
            $items = array_values($items);
            $baseLink = $this->getBasePath();

            foreach($items as $key => $item) {
                // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                if (array_key_exists('block', $_GET)) {
                    $items[$key]['options'] = array(
                        'check'  => array(
                            'icon'  => 'icon-check',
                            'link'  => $baseLink . "block/" . $item['id'],
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                } else {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'fa fa-edit',
                            //'link'  => $baseLink . "edit/" . $item['id'],
                            'class' => 'btn-sm btn-primary datatable-actionable'
                        ),
                        'users'  => array(
                            'icon'  => 'fa fa-user',
                            //'link'  => $baseLink . "set-resources/" . $item['id'],
                            'class' => 'btn-sm btn-info datatable-actionable'
                        ),
                        'permission'  => array(
                            'icon'  => 'fa fa-lock',
                            //'link'  => $baseLink . "set-resources/" . $item['id'],
                            'class' => 'btn-sm btn-warning datatable-actionable'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
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
    */
   
    protected function getDatatableItemOptions($model = "me") {

        $model_info = $this->model_info['me'];

        $options = array(
            'edit'  => array(
                'icon'  => 'fa fa-edit',
                'link'  => 'javascript:void(0);',
                'class' => 'btn-sm btn-primary datatable-actionable tooltips',
                'attrs' => [
                    'data-original-title' => $this->translate->translate("Edit")
                ]
            ),
            'users'  => array(
                'icon'  => 'fa fa-user',
                'link'  => 'javascript:void(0);',
                'class' => 'btn-sm btn-info datatable-actionable tooltips',
                'attrs' => [
                    'data-original-title' => $this->translate->translate("Users")
                ]
            ),
            'permission'  => array(
                'icon'  => 'fa fa-cog',
                'link'  => 'javascript:void(0);',
                'class' => 'btn-sm btn-warning datatable-actionable tooltips',
                'attrs' => [
                    'data-original-title' => $this->translate->translate("Manage")
                ]
            )
        );

        
        if ($this->isResourceAllowed("delete", $model_info)) {
            $options['remove'] = array(
                'icon'  => 'fa fa-remove',
                'link'  => 'javascript:void(0);',
                'class' => 'btn-sm btn-danger tooltips',
                'attrs' => [
                    'data-original-title' => $this->translate->translate("Remove")
                ]
            );
        }

        return $options;
    }
    /**
     * [ add a description ]
     *
     * @Post("/datasource/resources/toggle")
     */
    public function toggleRoleInResourceRequest() {
        $data = $this->getHttpData(func_get_args());

        $index = 0;
        $modelFilters = array();
        $filterData = array();
        foreach($data as $key => $item) {
            $modelFilters[$index] = "{$key} = ?{$index}";
            $filterData[$index] = $item;
            $index++;
        }

        $roleResources = RolesResources::find(array(
            'conditions'    => implode(" AND ", $modelFilters),
            'bind' => $filterData
        ));
        if ($roleResources->count() == 0) {
            $RolesResourcesModel = new RolesResources();
            $RolesResourcesModel->assign($data);
            $RolesResourcesModel->save();

            // USER ADICIONANDO AO GRUPO
            $info = array('insert' => true, "removed" => false);
            $response = $this->createAdviseResponse($this->translate->translate("Permission set."), "success");

        } else {
            $roleResources->getFirst()->delete();

            $info = array('insert' => false, "removed" => true);
            $response = $this->createAdviseResponse($this->translate->translate("Permission removed."), "error");
        }
        return array_merge($response, $info);
    }

    /**
     * [ add a description ]
     *
     * @Put("/users/{role_id}/{user_id}")
     */
    public function createUserRoleItemRequest($role_id, $user_id) {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = new RolesUsers();
            $itemModel->assign(array(
                'role_id' => $role_id,
                'user_id' => $user_id
            ));

            if ($itemModel->save() !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("User Included."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please, try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @Delete("/users/{role_id}/{user_id}")
     */
    public function deleteUserRoleItemRequest($role_id, $user_id)
    {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = RolesUsers::findFirst(array(
                'conditions' => 'role_id = ?0 AND user_id = ?1',
                'bind' => array($role_id, $user_id)
            ));

            if ($itemModel->delete() !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("User removed."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please, try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [ add a description ]
     *
     * @Put("/groups/{role_id}/{user_id}")
     */
    public function createGroupRoleItemRequest($role_id, $user_id)
    {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = new RolesGroups();
            $itemModel->assign(array(
                'role_id' => $role_id,
                'group_id' => $user_id
            ));

            if ($itemModel->save() !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Group Included."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please, try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    /**
     * [ add a description ]
     *
     * @Delete("/groups/{role_id}/{user_id}")
     */
    public function deleteGroupRoleItemRequest($role_id, $user_id)
    {
        if ($userData = $this->getCurrentUser()) {
            $itemModel = RolesGroups::findFirst(array(
                'conditions' => 'role_id = ?0 AND group_id = ?1',
                'bind' => array($role_id, $user_id)
            ));

            if ($itemModel->delete() !== FALSE) {
                $response = $this->createAdviseResponse($this->translate->translate("Group removed."), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please, try again.", "error");
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }


    /**
     * [ add a description ]
     *
     * @Get "/items/resources"
     * @Get "/items/resources/{type}/{filter}"
     */
    /*
    public function getResourcesItemsRequest($type, $filter)
    {
        var_dump(1);
        exit;
        $filter = json_decode($filter, true);
        if (is_array($filter)) {
            $index = 0;
            foreach($filter as $key => $item) {
                $modelFilters[] = "{$key} = ?{$index}";
                $filterData[$index] = $item;
                $index++;
            }

            $modelRS = RolesResources::find(array(
                'conditions'    => implode(" AND ", $modelFilters),
                'bind' => $filterData
            ));
        } else {
            $modelRS = RolesResources::find();    
        }
            
        $items = array();
        foreach($modelRS as $key => $item) {
            $items[$key] = $item->toArray();
            $items[$key]['resource'] = $item->getAclResource()->toArray();
            //$news[$key]['user'] = $item->getUser()->toArray();;
        }

        if ($type === 'datatable') {
            $items = array_values($items);
            $baseLink = $this->getBasePath();

            foreach($items as $key => $item) {
                // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                if (array_key_exists('block', $_GET)) {
                    $items[$key]['options'] = array(
                        'check'  => array(
                            'icon'  => 'icon-check',
                            'link'  => $baseLink . "block/" . $item['id'],
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                } else {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'fa fa-edit',
                            //'link'  => $baseLink . "edit/" . $item['id'],
                            'class' => 'btn-sm btn-primary datatable-actionable'
                        ),

                        'permission'  => array(
                            'icon'  => 'fa fa-lock',
                            'link'  => $baseLink . "set-resources/" . $item['id'],
                            'class' => 'btn-sm btn-warning'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
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
    */
    /**
     * [ add a description ]
     *
     * @Get("/datasource/users")
     * @Get("/datasource/users/{type}/{filter}")
     */
    public function getUsersItemsRequest($type, $filter)
    {
        $filter = json_decode($filter, true);
        if (is_array($filter)) {
            /*
            $index = 0;
            foreach($filter as $key => $item) {
                $modelFilters[] = "{$key} <> ?{$index}";
                $filterData[$index] = $item;
                $index++;
            }
            */
            if ($filter['exclude'] == TRUE) {
                $usersRS = RolesUsers::getUsersWithoutARole($filter['role_id'], $_GET['q']);
                $groupsRS = RolesGroups::getGroupsWithoutARole($filter['role_id'], $_GET['q']);
            } else {
                $usersRS = RolesUsers::getUsersWithARole($filter['role_id']);
                $groupsRS = RolesGroups::getGroupsWithARole($filter['role_id']);
            }

        } else {
            $usersRS = RolesUsers::getUsersWithoutARole(null, $_GET['q']);
            $groupsRS = RolesGroups::getGroupsWithoutARole(null, $_GET['q']);
        }

        // FILTER BY Query

        $items = array();
        /*
        foreach($modelRS as $key => $item) {
            $items[$key] = $item->toArray();
            $items[$key]['resource'] = $item->getAclResource()->toArray();
            //$news[$key]['user'] = $item->getUser()->toArray();;
        }
        */
        if ($type === 'combo') {
            $results = array();
            
            if ($usersRS->count()) {
                $usersItems = array();
                foreach($usersRS as $key => $item) {
                    $usersItems[$key] = array(
                        'id' => 'user:' . $item->id,
                        'name' => $item->name . " " . $item->surname,
                    );
                }
                $results[] = array(
                    'text'  => $this->translate->translate("Users"),
                    'children'  => $usersItems
                );
            }
            if ($groupsRS->count()) {
                $groupItems = array();
                foreach($groupsRS as $key => $item) {
                    $groupItems[$key] = array(
                        'id' => 'group:' . $item->id,
                        'name' => $item->name,
                    );
                }
                $results[] = array(
                    'text'  => $this->translate->translate("Groups"),
                    'children'  => $groupItems
                );
            }

            return $results;

        } elseif ($type === 'datatable') {

            $items = array();
            if ($usersRS->count() > 0) {
                foreach($usersRS as $item) {
                    $items[] = array_merge(
                        $item->toArray(),
                        array(
                            'fullname' => $item->name . " " . $item->surname,
                            'type'  => 'user',
                            'icon'  => 'fa fa-user'
                        )
                    );
                }
            }
            if ($groupsRS->count()) {
                foreach($groupsRS as $item) {
                    $items[] = array_merge(
                        $item->toArray(),
                        array(
                            'fullname' => $item->name,
                            'type'  => 'group',
                            'icon'  => 'fa fa-group'
                        )
                    );
                }
            }

            $baseLink = $this->getBasePath();

            foreach($items as $key => $item) {
                // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                if (array_key_exists('block', $_GET)) {
                    $items[$key]['options'] = array(
                        'remove'  => array(
                            'icon'  => 'fa fa-close',
                            //'link'  => $baseLink . "block/" . $item['id'],
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                } else {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'fa fa-edit',
                            //'link'  => $baseLink . "edit/" . $item['id'],
                            'class' => 'btn-sm btn-primary datatable-actionable'
                        ),

                        'permission'  => array(
                            'icon'  => 'fa fa-lock',
                            'link'  => $baseLink . "set-resources/" . $item['id'],
                            'class' => 'btn-sm btn-warning'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
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
        return array_values($usersRS->toArray() + $groupsRS->toArray());
    }
}
