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
                    'text'  => $this->translate->translate("Users groups")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users groups")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("New group"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-group',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Users groups")
                );
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit group"));
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
                    'text'      => $this->translate->translate('New group'),
                    'link'      => $this->getBasePath() . "add",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-plus-square'
                )
            )
        );

        return $actions[$request];
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'group.definition' => function($data, $self) {
                $self->putComponent("sprintf");
                $self->putComponent("select2");
                $self->putComponent("data-tables");
                $self->putComponent("jquery-builder");


                // CREATE BLOCK CONTEXT
                $block_context = $self->getConfig("blocks\\group.definition\\context");

                $self->putItem("group_definition_static_context", $block_context['static']);
                $self->putItem("group_definition_dynamic_context", $block_context['dynamic']);

                return true;
            }
        );
    }





    /**
     * [ add a description ]
     *
     * @Get("/item/users/{group_id}")
     * @deprecated 3.4.1
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
     * @deprecated 3.4.1
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

    protected function getDatatableItemOptions($model = "me") {
        if ($model == "users") {
            // RETURN THE OPTION TO EXCLUDE THE 
            $model_info = $this->model_info[$model];
            $editAllowed = $this->isResourceAllowed("edit", $model_info);

            if ($editAllowed) {
                $options['remove']  = array(
                    'icon'  => 'fa fa-remove',
                    'class' => 'btn-sm btn-danger tooltips',
                    'attrs' => array(
                        'data-original-title' => 'Remove'
                    )
                );
            }
            return $options;
        } else {
            return parent::getDatatableItemOptions($model);
        }
    }



}
