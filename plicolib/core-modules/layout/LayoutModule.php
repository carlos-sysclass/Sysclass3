<?php 
/**
  * This module manage the pages layouts, create and save layout paterns, and manage layout sections
 */
class LayoutModule extends AbstractModule
{
	protected $layoutSpec = null;
	protected $blankWidget = null;
	protected $widgets = null;

    public function init() {
        // DEFAULT LAYOUT SPEC, MUST BE OVERRRIDEN
        $modules = $this->getModules();

        $this->defaultLayout = array(
            "rows" => array(
                array(
                    1   => array("weight" => "12")
                )
            ),
            'widgets' => array(
                1 => array(
                    "hospitais.widget",
                    "leitos.widget"
                )
            ),
            'sortable'  => false,
            'resources' => array(
                "default"   => array_keys($modules),
                "users.overview.notification.order" => array("messages", "news"),
                "layout.sections.topbar-left"   => array("internacao", "prescricao", "scores", "enfermagem", "stats", "cepetipordia")
            )
        );

        $this->layouts = array(
            'default' => $this->defaultLayout
        );
        $this->layoutSpec = $this->defaultLayout;
    }

    public function createLayout($id, $spec) {
        $this->layouts[$id] = array_merge($this->defaultLayout, $spec);
    }

    protected function getResource($resourceID) {
        if (array_key_exists($resourceID, $this->layoutSpec['resources'])) {
            return $this->layoutSpec['resources'][$resourceID];
        } else {
            return $this->layoutSpec['resources']['default'];
        }
    }
    public function sortModules($sortId, $data) {
        $resource = $this->getResource($sortId);
        $dataArray = array();
        if ($resource) {
            foreach($resource as $sortIndex) {
                if (array_key_exists($sortIndex, $data)) {
                    $dataArray[$sortIndex] = $data[$sortIndex];
                //} else {
                    //$dataArray[$sortIndex] = false;    
                }
            }
        }
        return $dataArray;
    }
    // CREATE FUNCTION HERE
    protected function clearWidgets() {
    	$this->widgets = array();
    	$this->widgets['blank'] = array(
    		'title' => 'Blank Porlet'
    	);
    }
    public function getLayout($layout_id = 'default') {
        $modules = $this->getModules();
        if (!array_key_exists($layout_id, $this->layouts)) {
            $layout_id = 'default';
        }
        $this->layoutSpec = $this->layouts[$layout_id];
        /*
        $this->layoutSpec = array(

            "rows" => array(
                array(
                    1   => array("weight" => "12")
                )
            ),
            'widgets' => array(
            	1 => array(
                    "leitos.widget"
            	)
            ),
            'sortable'  => false,
            'resources' => array(
                "default"   => array_keys($modules),
                "users.overview.notification.order" => array("messages", "news")
            )
        );
        */
        $modules_size = count($modules);
        $defaultArray = $this->layoutSpec['resources']['default'];

        foreach($this->layoutSpec['resources'] as $key => $resource) {
            if ($key == "default") {
                continue;
            }
            $sorterArray = array_unique(array_merge($resource, $defaultArray));
            $this->layoutSpec['resources'][$key] = $sorterArray;
        }

        return $this->layoutSpec;
    }
    public function getMenuBySection($section) {
        $controllers = array();
        $controllers = $this->getControllers("ISectionMenu");
        foreach($controllers as $index => $module) {

            $menu_item = $module->getSectionMenu($section);
            if ($menu_item) {
                $menu_items[$index] = $menu_item;
            }
        }

    	// LOAD MENUS
		$modules = array();
    	// GET ALL MODULES, CHECK FOR IMenu Interface, CHECK FOR SECTION
    	$modules = $this->getModules("ISectionMenu");

		//$menu_items = array();
    	foreach($modules as $index => $module) {

			$menu_item = $module->getSectionMenu($section);
   			if ($menu_item) {
   				$menu_items[$index] = $menu_item;
   			}
    	}
        //$menu_items = $this->sortModules("layout.sections." . $section, $menu_items);
    	return $menu_items;
    }
    protected function getMenuOrderBySection($section, $size) {
    	$sorterArray = array_fill(0, $size, "");
    	if ($section == "topbar") {
    		$sorterArray[0] = "messages";
    		$sorterArray[1] = "forum";
    	}
    	return array_slice($sorterArray, 0, $size);
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
}
