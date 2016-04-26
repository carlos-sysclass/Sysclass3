<?php
namespace Sysclass\Modules\Dashboard;

/**
 * Module Class File
 * @filesource
 */
/**
 * This module manage the pages layouts, create and save layout paterns, and manage layout sections
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */

class DashboardModule extends \SysclassModule implements \ISectionMenu, \IWidgetContainer
{
    protected $layout_id;
    public $config;

    /* ISectionMenu */
    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {
            $currentUser = $this->getCurrentUser(true);

            $modules = $this->getModules("ILinkable");
            $modulesKeys = array_combine(array_keys($modules), array_keys($modules));

            $modulesOrder = $this->getResource('dashboard.linkable.order');
            /**
             * @todo Get the data fomr system settings (NOT FROM dashboard setting)
             */
            $modulesOrder = 
                array("Settings", "Institution", "Translate", "Areas", "Courses", "Classes", "Lessons", "Tests", "Questions", "Grades", "Users", "Groups", "Roles", "Advertising", "Calendar");

            $modulesOrder = array_unique(array_merge($modulesOrder, $modulesKeys));


            $links = array();

            foreach($modulesOrder as $module_id) {
                $module_id = ucfirst($module_id);
                if (array_key_exists($module_id, $modules)) {
                    $mod_links = $modules[$module_id]->getLinks();
                    if (is_array($mod_links)) {
                        $links = array_merge_recursive($links, $mod_links);
                    }
                }
                unset($modulesKeys[$module_id]);
            }
            
            //["administration", "content", "users", "communication", "not_classified"]
            //var_dump($links);
            //exit;
            //$links = $this->sortModules("dashboard.linkable.groups.order", $links, "not_classified");

            $groupLabels = array(
                "content"           => $this->translate->translate('Content'),
                "administration"    => $this->translate->translate('Administation'),
                "communication"     => $this->translate->translate('Communication'),
                "users"             => $this->translate->translate('Users'),
                "not_classified"    => $this->translate->translate('Not Classified')
            );

            foreach($links as $groupKey => $item) {
                if (array_key_exists($groupKey, $groupLabels)) {
                    $links[$groupLabels[$groupKey]] = $item;
                    unset($links[$groupKey]);
                }
            }

            // ADD ENVIROMENT SELECTION
            $dashboards = $currentUser->getDashboards();

            if (count($dashboards) > 1) {
                $items = array();

                foreach($dashboards as $dashboard) {
                    if ($this->layoutExists($dashboard)) {

                        $items[] = array(
                            'link'  => "/dashboard/" . $dashboard,
                            'text'  => $this->translate->translate(ucfirst($dashboard))
                        );
                    }
                }

                $links[$this->translate->translate('Environment')] = $items;
            }

            $menuItem = array(
                'icon'      => 'fa fa-bars',
                //'notif'     => count($items),
                'text'      => $this->translate->translate('Menu'),
                'type'      => 'mega',
                'items'     => $links,
                'extended'  => false
                //'template'  => "translate-menu"
            );

            //var_dump($menuItem);

            return $menuItem;
        }
        return false;
    }

    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('dashboard.linkable.view', $widgetsIndexes)) {
            $modules = $this->getModules("ILinkable");
            $modulesKeys = array_combine(array_keys($modules), array_keys($modules));

            $groupLabels = array(
                "content"           => $this->translate->translate('Content'),
                "administration"    => $this->translate->translate('Administation'),
                "communication"     => $this->translate->translate('Communication'),
                "users"             => $this->translate->translate('Users'),
                "not_classified"    => $this->translate->translate('Not Classified')
            );

            $modulesOrder = 
                array("Settings", "Institution", "Translate", "Areas", "Courses", "Classes", "Lessons", "Tests", "Questions", "Grades", "Users", "Groups", "Roles", "Advertising", "Calendar");

            $modulesOrder = array_unique(array_merge($modulesOrder, $modulesKeys));

            $links = array();
            foreach($modulesOrder as $module_id) {
                $module_id = ucfirst($module_id);
                if (array_key_exists($module_id, $modules)) {
                    $mod_links = $modules[$module_id]->getLinks();
                    if (is_array($mod_links)) {
                        $links = array_merge_recursive($links, $mod_links);
                    }
                }
                unset($modulesKeys[$module_id]);
            }

            //$links = $this->sortModules("dashboard.linkable.groups.order", $links, "not_classified");

            foreach($links as $group_id => $group) {
                if (array_key_exists($group_id, $groupLabels)) {
                    $groups[$group_id] = $groupLabels[$group_id];
                } else {
                    $groups[$group_id] = $this->translate->translate($group_id);
                }
            }

            $this->putCss("css/components");

            $groupColors = array(
                "administration" => "yellow-gold",
                "content" => "red-thunderbird",
                "users" => "blue-steel",
                "communication" => "green-seagreen"
            );

            return array(
                'dashboard.linkable.view' => array(
                    'type'      => 'control-panel', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                    'id'        => 'dashboard-control-panel',
                    //'title'     => true,
                    //'icon'        => 'th-large',
                    'template'  => $this->template("dashboard.linkable.view.widget"),
                    'panel'     => true,
                    //'box'         => 'light-grey',
                    'tools'     => array(
                        'search'        => true,
                        'reload'        => 'javascript:void(0);',
                        'collapse'      => true,
                        'fullscreen'    => true
                    ),
                    'data'      => array(
                        'groups'    => $groups,
                        'colors'    => $groupColors,
                        'links'     => $links
                    )
                )
            );
        }
    }

    // CREATE FUNCTION HERE
    protected function clearWidgets() {
        $this->widgets = array();
        $this->widgets['blank'] = array(
            'title' => 'Blank Porlet'
        );
    }

    public function getPageWidgets() {
        // LOAD MENUS
        $modules = array();
        // GET ALL MODULES, CHECK FOR IMenu Interface, CHECK FOR SECTION
        $modules = $this->getModules("\IWidgetContainer");

        $widgetsIndexes = array();
        foreach($this->layoutSpec['widgets'] as $column_id => $columnWidgets) {
            $widgetsIndexes = array_merge($widgetsIndexes, $columnWidgets);
        }

        $widgetsIndexes = array_unique($widgetsIndexes);

        // GET WIDGET BY COLUMN
        $this->clearWidgets();
        foreach($modules as $index => $module) {
            //var_dump($index);
            $mod_widgets = $module->getWidgets($widgetsIndexes);
            //var_dump($mod_widgets);
            if ($mod_widgets) {
                $this->widgets = array_merge($this->widgets, $mod_widgets);
            }
        }

        $widgetKeys = array_map(
            function($item) { return reset(explode("/", $item)); },
            array_keys($this->widgets)
        );

        $widgetKeys = array_combine(array_keys($this->widgets), $widgetKeys);

        $widgetStruct = array();
        foreach($this->layoutSpec['widgets'] as $column_id => $columnWidgets) {
            foreach($columnWidgets as $widgetID) {
                if (array_key_exists($widgetID, $widgetKeys)) {
                    $widgetRealID = $widgetID;
                    unset($widgetKeys[$widgetRealID]);
                    $widgetStruct[] = array(
                        $widgetRealID,
                        $this->widgets[$widgetRealID],
                        $column_id
                    );
                } else {
                    while($widgetRealID = array_search ( $widgetID , $widgetKeys ))  {
                        unset($widgetKeys[$widgetRealID]);
                        $widgetStruct[] = array(
                            $widgetRealID,
                            $this->widgets[$widgetRealID],
                            $column_id
                        );
                    }
                }
            }
        }



        return $widgetStruct;
    }

    protected function getResource($resourceID) {
        return $this->config['dashboard']['resources'][$resourceID];
    }
}
