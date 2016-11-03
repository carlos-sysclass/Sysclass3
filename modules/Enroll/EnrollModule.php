<?php
namespace Sysclass\Modules\Enroll;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Courses\Course as Course,
    Sysclass\Models\Enrollments\CourseUsers as Enrollment,
    Sysclass\Models\Forms\Fields;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/enroll")
 */
class EnrollModule extends \SysclassModule implements \IBlockProvider, \ILinkable, \IBreadcrumbable, \IActionable
{

    /* IBlockProvider */
    public function registerBlocks() {
        return array(
            /*
            'enroll.user.block' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $self->putComponent("select2");
                $self->putComponent("data-tables", "select2");
                $self->putScript("scripts/utils.datatables");

                $self->putModuleScript("user.block");

                $block_context = $self->getConfig("blocks\\enroll.user.block\context");
                $self->putItem("enroll_user_block_context", $block_context);

                $self->putSectionTemplate("enroll", "blocks/enroll.user");

                return true;
            },
            */
            'fixed_grouping.dialog' => function($data, $self) {
                $self->putComponent("bootstrap-confirmation", "bootstrap-editable");
                $self->putModuleScript("dialogs.fixed_grouping.form");

                $self->putSectionTemplate("dialogs", "dialogs/fixed_grouping.form");
                return true;
            },
            'enroll.fields.dialog' => function($data, $self) {
                $self->putComponent("bootstrap-confirmation", "bootstrap-editable");
                $self->putModuleScript("dialogs.fields.form");

                $self->putSectionTemplate("dialogs", "dialogs/fields.form");
                return true;
            },
            'enroll.fields' => function($data, $self) {
                // GET ALL THIS DATA FROM config.yml
                //$self->putBlock('enroll.fields.dialog');
                $self->putModuleScript("fields.form");

                $self->putComponent("data-tables");
                $self->putComponent("select2");
                $self->putScript("scripts/utils.datatables");

                $fields = Fields::find();
                $self->putItem("form_fields", $fields->toArray());

                //$self->putComponent("bootstrap-switch");

                $block_context = $self->getConfig("blocks\\enroll.fields\\context");
                $self->putItem("enroll_fields_context", $block_context);

                $self->putSectionTemplate("enroll.fields", "blocks/fields");

                return true;
            },
            'enroll.courses' => function($data, $self) {
                // GET ALL THIS DATA FROM config.yml
                //$self->putBlock('enroll.fields.dialog');
                $self->putBlock("enroll.users.dialog");
                $self->putBlock("enroll.settings.dialog");
                $self->putModuleScript("blocks.enroll.courses");
                

                $self->putComponent("data-tables");
                $self->putComponent("select2");
                $self->putScript("scripts/utils.datatables");

                //$fields = Fields::find();
                //$self->putItem("form_fields", $fields->toArray());

                //$self->putComponent("bootstrap-switch");

                $block_context = $self->getConfig("blocks\\enroll.courses\\context");
                $self->putItem("enroll_courses_context", $block_context);

                $self->putSectionTemplate("enroll.courses", "blocks/courses");

                return true;
            },
            'enroll.users.dialog' => function($data, $self) {
                // GET ALL THIS DATA FROM config.yml
                $self->putComponent("data-tables");
                $self->putComponent("select2");
                $self->putScript("scripts/utils.datatables");
                //$self->putComponent("bootstrap-switch");

                $block_context = $self->getConfig("blocks\\enroll.users.dialog\\context");
                $self->putItem("enroll_users_dialog_context", $block_context);

                $self->putModuleScript("dialogs.enroll.users");

                $self->putSectionTemplate("dialogs", "dialogs/users");

                return true;
            },
            'enroll.settings.dialog' => function($data, $self) {
                // GET ALL THIS DATA FROM config.yml
                $self->putComponent("data-tables");
                //$self->putComponent("select2");
                $self->putScript("scripts/utils.datatables");
                //$self->putComponent("bootstrap-switch");

                //$block_context = $self->getConfig("blocks\\enroll.settings.dialog\\context");
                //$self->putItem("enroll_users_dialog_context", $block_context);

                $self->putModuleScript("dialogs.enroll.settings");

                $self->putSectionTemplate("dialogs", "dialogs/settings");

                return true;
            }
        );
    }
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, "Enroll", "View")) {

            return array(
                'administration' => array(
                    array(
                        'count' => count($items),
                        'text'  => $this->translate->translate('Enrollment'),
                        'icon'  => 'fa fa-cogs',
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
                    'icon'  => 'fa fa-list',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Enrollment")
                );
                return $breadcrumbs;
                break;
            }
            case "add" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-list',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Enrollment")
                );
                $breadcrumbs[] = array(
                    'text'   => $this->translate->translate("New Enrollment Guideline"),
                    'icon'  => 'fa fa-add-circle',
                    );
                return $breadcrumbs;
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array(
                    'icon'  => 'fa fa-list',
                    'link'  => $this->getBasePath() . "view",
                    'text'  => $this->translate->translate("Enrollment")
                );
                $breadcrumbs[] = array(
                    'text'   => $this->translate->translate("Edit Enrollment Guideline"),
                    'icon' => 'fa fa-pencil'
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
                    'text'      => $this->translate->translate('New Enrollment Guideline'),
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

    public function getDatatableItemOptions() {
        if ($this->_args['model'] == 'courses') {
            return array(
                'enroll' => array(
                    'icon'  => 'fa fa-users',
                    'link'  => 'javascript:void(0);',
                    'class' => 'btn-sm btn-primary datatable-actionable',
                ),
                'settings' => array(
                    'icon'  => 'fa fa-cogs',
                    'link'  => 'javascript:void(0);',
                    'class' => 'btn-sm btn-warning datatable-actionable',
                ),
                'remove'  => array(
                    'icon'  => 'fa fa-remove',
                    'class' => 'btn-sm btn-danger'
                )
            );
        }
        return parent::getDatatableItemOptions();
    }

    /**
     * [ add a description ]
     *
     * @Get("/datasource/users")
     * @Get("/datasource/users/{type}/{filter}")
     */
    public function getEnrolledUsersItemsRequest($type, $filter)
    {
        $filter = json_decode($filter, true);

        if (is_array($filter)) {
            if ($filter['exclude'] == TRUE) {
                $usersRS = Enrollment::getUsersNotEnrolled($filter, $_GET['q']);
                //$groupsRS = RolesGroups::getGroupsWithoutARole($filter['role_id'], $_GET['q']);
            } else {
                $usersRS = Enrollment::getUsersEnrolled($filter, null);
                //$groupsRS = RolesGroups::getGroupsWithARole($filter['role_id']);
            }
        } else {
            $usersRS = Enrollment::getUsersNotEnrolled(null, $_GET['q']);
            //$groupsRS = RolesGroups::getGroupsWithoutARole(null, $_GET['q']);
        }

        // FILTER BY Query
        $items = array();

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
            /*
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
            */
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
            /*
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
            */
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
        return array_values($usersRS->toArray() /* + $groupsRS->toArray()*/);
    }
    /**
     * [ add a description ]
     *
     * @Put("/items/{model}/set-order/{enroll_id}")
     */
    public function setOrderRequest($model, $enroll_id)
    {
        if (array_key_exists($model, $this->model_info)) {
            $model_info = $this->model_info[$model];

            $class = $model_info['class'];

            $position = $this->request->getPut('position');

            $result = $class::setOrder($enroll_id, $position);

            $response = $this->createAdviseResponse($this->translate->translate("Collection sorted successfully"), "success");
            return $response;
        } else {
            return $this->invalidRequestError();
        }
    }
}
