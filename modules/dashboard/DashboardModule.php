<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * This module manage the pages layouts, create and save layout paterns, and manage layout sections
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */

class DashboardModule extends SysclassModule implements IWidgetContainer
{
    protected $layout_id;
    protected $config;

    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('dashboard.linkable.view', $widgetsIndexes)) {
            $modules = $this->getModules("ILinkable");
            $modulesKeys = array_combine(array_keys($modules), array_keys($modules));

            $groupLabels = array(
                "content"           => self::$t->translate('Content'),
                "administration"    => self::$t->translate('Administation'),
                "communication"     => self::$t->translate('Communication'),
                "users"             => self::$t->translate('Users'),
                "not_classified"    => self::$t->translate('Not Classified')
            );
            $modulesOrder = $this->getResource('dashboard.linkable.order');

            $links = array();
            foreach($modulesOrder as $module_id) {
                if (array_key_exists($module_id, $modules)) {
                    $links = array_merge_recursive($links, $modules[$module_id]->getLinks());
                }
                unset($modulesKeys[$module_id]);
            }

            $links = $this->sortModules("dashboard.linkable.groups.order", $links, "not_classified");



            foreach($links as $group_id => $group) {
                if (array_key_exists($group_id, $groupLabels)) {
                    $groups[$group_id] = $groupLabels[$group_id];
                } else {
                    $groups[$group_id] = self::$t->translate($group_id);
                }
            }

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
                        'links'     => $links
                    )
                )
            );
        }
    }

    protected function getResource($resourceID) {
        return $this->config['dashboard']['resources'][$resourceID];
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
        $modules = $this->getModules("IWidgetContainer");


        $widgetsIndexes = array();
        foreach($this->layoutSpec['widgets'] as $column_id => $columnWidgets) {
            $widgetsIndexes = array_merge($widgetsIndexes, $columnWidgets);
        }
        $widgetsIndexes = array_unique($widgetsIndexes);

        // GET WIDGET BY COLUMN
        $this->clearWidgets();
        foreach($modules as $index => $module) {
            $mod_widgets = $module->getWidgets($widgetsIndexes);
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

    // CREATE FUNCTION HERE
    public function loadLayout($layout_id = 'default', $use_cache = true) {
        $this->layout_id = $layout_id;

        $this->config = $this->loadConfig($use_cache);

        if (@$this->config['dashboard']['merge_resource_with_modules']) {
            $modules = $this->getModules();

            $modules_keys = array_keys($modules);
            foreach($this->config['dashboard']['resources'] as $index => $resource) {
                $this->config['dashboard']['resources'][$index] = array_unique(array_merge($resource, $modules_keys));
            }
        }

        return  $this->layoutSpec = $this->config['dashboard'];
    }

    protected function loadConfig($use_cache = true) {
        $cached = $this->getCache("dashboard/{$this->layout_id}");

        if (is_null($cached) || $use_cache == false) {

            $defaultconfig = yaml_parse_file(__DIR__ . "/config/default.yml");
            $config = yaml_parse_file(__DIR__ . "/config/" . $this->layout_id . ".yml");




            $this->config = array_replace_recursive($defaultconfig, $config);

            $this->setCache("dashboard/{$this->layout_id}", $this->config);
        } else {
            $this->config = $cached;
        }
        return $this->config;
    }
}
