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
use Sysclass\Models\Organizations\Organization;
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

            }
        );
    }

    public function getWidgets($widgetsIndexes = array(), $caller = null)
    {


        $organization = Organization::findFirstById(1);

        if (in_array('institution.overview', $widgetsIndexes)) {
            //$this->putModuleScript("widget.institution");

        	return array(
        		'institution.overview' => array(
       				//'title' 	=> 'User Overview',
                    'id'        => 'institution-widget',
       				'template'	=> $this->template("widgets/overview"),
                    'panel'     => true,
                    'body'      => "no-padding",
                    'data' => $organization->toFullArray()
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

}
