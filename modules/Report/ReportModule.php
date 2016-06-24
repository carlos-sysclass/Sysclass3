<?php
namespace Sysclass\Modules\Report;
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
 * @RoutePrefix("/module/report")
 */
class ReportModule extends \SysclassModule implements \IBlockProvider, \ILinkable, \IBreadcrumbable, \IActionable
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
            }
        );
    }
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, "Report", "View")) {

            return array(
                'administration' => array(
                    array(
                        'count' => count($items),
                        'text'  => $this->translate->translate('Reports'),
                        'icon'  => 'fa fa-cogs',
                        'link'  => $this->getBasePath() . 'manage'
                    )
                )
            );
        }
    }


    /**
     * [ add a description ]
     *
     * @Get("/manage")
     * @allow(resource=report, action=edit)
     */

    public function managePage($route)
    {
        // MUST SHOW ALL AVALIABLE CALENDARS TYPES
        //$this->createClientContext("manage");
        if (!$this->createClientContext("manage")) {
            $this->entryPointNotFoundError($this->getSystemUrl('home'));
        }

        $this->display($this->template);
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
                'remove'  => array(
                    'icon'  => 'fa fa-remove',
                    'class' => 'btn-sm btn-danger'
                )
            );
        }
        return parent::getDatatableItemOptions();
    }

}
