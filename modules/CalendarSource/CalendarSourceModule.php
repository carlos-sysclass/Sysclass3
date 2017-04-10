<?php
/**
 * Module Class File
 * @filesource
 */
namespace Sysclass\Modules\CalendarSource;

use Sysclass\Models\System\Colors;

use Sysclass\Models\Calendar\Sources as CalendarSource,
    Sysclass\Models\Calendar\Event;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/calendarsource")
 */
class CalendarSourceModule extends \SysclassModule implements \IBreadcrumbable, \IActionable
{
    /* IBreadcrumbable */
    public function getBreadcrumb() {
        $breadcrumbs = array(
            array(
                'icon'  => 'fa fa-home',
                'link'  => $this->getSystemUrl('home'),
                'text'  => $this->translate->translate("Home")
            ),
            array(
                'icon'  => 'fa fa-calendar',
                'link'  => $this->getBasePath() . "view",
                'text'  => $this->translate->translate("Calendar tags")
            )
        );

        $request = $this->getMatchedUrl();
        switch($request) {
            case "add" : {
                $breadcrumbs[] = array('icon' => 'fa fa-plus-square', 'text'   => $this->translate->translate("New calendar tag"));
                break;
            }
            case "edit/{id}" : {
                $breadcrumbs[] = array('icon' => 'fa fa-pencil', 'text'   => $this->translate->translate("Edit calendar tag"));
                break;
            }
        }
        return $breadcrumbs;
    }

    /* IActionable */
    public function getActions()
    {
        $request = $this->getMatchedUrl();

        $actions = array
        (
            'view'  => array
            (
                array(
                    'text'      => $this->translate->translate('New calendar tag'),
                    'link'      => $this->getBasePath() . "add",
                    'icon'      => 'fa fa-plus-square'
                ),
                array(
                    'separator' => true
                ),
                array(
                    'text'      => $this->translate->translate('View calendar'),
                    'link'      => $this->loader->module('Calendar')->getBasePath() . "manage",
                    'icon'      => 'fa fa-list'
                )
            ),
            'edit/{id}' => array
            (
                array(
                    'text'      => $this->translate->translate('View calendar'),
                    'link'      => $this->loader->module('Calendar')->getBasePath() . "manage",
                    'class'     => "btn-primary",
                    'icon'      => 'fa fa-list'
                )
            ),
        );

        return $actions[$request];
    }

    public function beforeModelCreate($evt, $model, $data) {
    }

    /**
     * Get the event according to the id
     *
     * @Get("/add")
    */
    public function addPage() {
        $colors = Colors::find();
        $this->putItem("colors", $colors->toArray());
        return parent::addPage();
    }

    /**
     * Get the event according to the id
     *
     * @Get("/edit/{id}")
    */
    public function editPage($id) {
        $colors = Colors::find();
        $this->putItem("colors", $colors->toArray());
        return parent::editPage($id);
    }
}
