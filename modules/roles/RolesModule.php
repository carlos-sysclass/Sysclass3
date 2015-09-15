<?php
use Phalcon\DI,
    Sysclass\Models\Users\Role,
    Sysclass\Services\L10n\Timezones,
    Sysclass\Services\Authentication\Exception as AuthenticationException;
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */
class RolesModule extends SysclassModule implements ILinkable, IBreadcrumbable, IActionable, IBlockProvider
{

    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsAction();
        if ($this->getCurrentUser(true)->getType() == 'administrator') {

            $count = Role::count("active = 1");

            // $items = $this->module("permission")->checkRules($itemsData, "course", 'permission_access_mode');

            return array(
                'users' => array(
                    array(
                        'count' => $count,
                        'text'  => self::$t->translate('Users Roles'),
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
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Roles")
                );
                return $breadcrumbs;
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Roles")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("New Role"));
                return $breadcrumbs;
                break;
            }
            case "edit/:id" : {
                $breadcrumbs[] = array(
                    'icon'  => 'icon-user',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => self::$t->translate("Roles")
                );
                $breadcrumbs[] = array('text'   => self::$t->translate("Edit Role"));
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
                    'text'      => self::$t->translate('New Role'),
                    'link'      => "javascript:void(0)",
                    'class'     => "btn-primary dialog-create-role-open-action",
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
            }
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
        $languages = self::$t->getItems();
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
        $languages = self::$t->getItems();
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
     * @url GET /item/me/:id
    */
    public function getItemAction($id) {
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

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        $request = $this->getMatchedUrl();

        if ($userData = $this->getCurrentUser()) {
            //$itemModel = $this->model("user/item");
            // TODO CHECK IF CURRENT USER CAN DO THAT
            $data = $this->getHttpData(func_get_args());
            $userModel = new Role();
            $userModel->assign($data);

            if ($userModel->save()) {
                return $this->createAdviseResponse(
                    self::$t->translate("Role created with success"),
                    "success"
                );
            } else {
                $response = $this->createAdviseResponse(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                return array_merge($response, $userModel->toFullArray());
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
        $request = $this->getMatchedUrl();

        //$itemModel = $this->model("user/item");

        if ($userModel = $this->getCurrentUser(true)) {
            $data = $this->getHttpData(func_get_args());

            $itemModel = Role::findFirstById($id);
            $itemModel->assign($data);

            if ($itemModel->save()) {
                $response = $this->createAdviseResponse(self::$t->translate("Role updated with success"), "success");

                return array_merge($response, $itemModel->toArray());
            } else {
                $response = $this->createAdviseResponse(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "error");
                return array_merge($response, $itemModel->toArray());
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

            $itemModel = Role::findFirstById($id);

            if ($itemModel->delete() !== FALSE) {
                $response = $this->createAdviseResponse(self::$t->translate("Role removed with success"), "success");
                return $response;
            } else {
                // MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
                return $this->invalidRequestError("A problem ocurred when tried to save you data. Please try again.", "error");
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

        $currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRS = Role::find();
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

}
