<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\Institution;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
use Sysclass\Models\Organizations\Organization,
    Sysclass\Services\I18n\Timezones,
    Sysclass\Models\I18n\Language;
/**
 * @RoutePrefix("/module/institution")
 */
class InstitutionModule extends \SysclassModule implements \IWidgetContainer, \ILinkable, \IBreadcrumbable, \IBlockProvider
{
    // IBlockProvider
    public function registerBlocks() {
        return array(
            'institution.social-gadgets' => function($data, $self) {
                //$this->putComponent("modal");
                //$this->putModuleScript("dialog.permission");
                //$this->putSectionTemplate(null, "blocks/permission");
                $self->putSectionTemplate("bottom", "blocks/social-gadgets");

                return true;

            },
            'organization.social.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                $languages = Language::find("active = 1");
                $this->putitem("languages", $languages->toArray());

                $self->putComponent("data-tables");
                $self->putComponent("select2");
                //$self->putComponent("bootstrap-editable");

                $block_context = $self->getConfig("blocks\\organization.social.dialog\\context");
                $self->putItem("organization_social_dialog_context", $block_context);

                $self->putModuleScript("dialogs.institution.social");
                $self->setCache("organization.social.dialog", $block_context);

                $self->putSectionTemplate("social", "blocks/social.list");

                return true;
            },
            'organization.social.list' => function($data, $self) {
                $this->putBlock("organization.social.dialog");


                $self->putComponent("data-tables");
                $self->putComponent("select2");
                //$self->putComponent("bootstrap-editable");

                $block_context = $self->getConfig("blocks\\organization.social.list\\context");
                $self->putItem("organization_social_list_context", $block_context);

                $self->putModuleScript("dialogs.institution.social");
                $self->setCache("organization.social.dialog", $block_context);

                $self->putSectionTemplate("dialogs", "dialogs/social");

                return true;
            }
        );
    }

    public function getWidgets($widgetsIndexes = array(), $caller = null)
    {


        $organization = Organization::findFirstById(1);

        if (in_array('institution.overview', $widgetsIndexes)) {
            //$this->putModuleScript("widget.institution");

            $data = $organization->toFullArray();

            $time_at = Timezones::getTimeAt($organization->timezone);

            if ($time_at) {
                $data['time_at'] = $time_at->format('H:i');
                $data['details']['time_at'] = $data['time_at'];
            }

        	return array(
        		'institution.overview' => array(
       				//'title' 	=> 'User Overview',
                    'id'        => 'institution-widget',
       				'template'	=> $this->template("widgets/overview"),
                    'panel'     => true,
                    'body'      => "no-padding",
                    'data'      => $data
        		)
        	);
        }
    }
    /* ILinkable */
    public function getLinks() {
        //$data = $this->getItemsRequest();
        
        if ($this->acl->isUserAllowed(null, "Institution", "View")) {
            $organization = Organization::findById(1);

            return array(
                'administration' => array(
                    array(
                        'count' => $organization->count(),
                        'text'  => $this->translate->translate('Organization'),
                        'icon'  => 'fa fa-university',
                        'link'  => $this->getBasePath() . 'edit/1'
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
            ),
            array(
                'icon'  => 'fa fa-university',
                'link'  => $this->getBasePath() . "edit/1",
                'text'  => $this->translate->translate("Organizations")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "view" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("View"));
                break;
            }
            case "add" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("New Organization"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array('text'   => $this->translate->translate("Edit Organization"));
                break;
            }
        }
        return $breadcrumbs;
    }

    /**
     * [ add a description ]
     *
     * @Get("/edit/{id}")
     * @allow(resource=users, action=edit)
     */
    public function editPage($id)
    {
        $timezones = Timezones::findAll();
        $this->putitem("timezones", $timezones);

        parent::editPage($id);
    }





}
