<?php 
/**
  * This module manage the pages layouts, create and save layout paterns, and manage layout sections
*/
class LayoutModule extends SysclassModule
{
	protected $layoutSpec = null;
	protected $blankWidget = null;
	protected $widgets = null;

    public function init() {
        // DEFAULT LAYOUT SPEC, MUST BE OVERRRIDEN
        $modules = $this->getModules();
        
        $this->layoutSpec = array(
            /**
              * @todo THIS DATA MUST BE PERSITED ON DB, OR OTHER MEANS
             */
            "rows" => array(
                array(
                    1   => array("weight" => "8"),
                    2   => array("weight" => "4")
                ),
                array(
                    3   => array("weight" => "12")
                ),
            ),
            'widgets' => array(
                1 => array(
                    "users.overview",
                    "news.latest",
                    "courses.overview",
                    "tutoria.widget"
                ),
                2 => array(
                    "institution.overview",
                    "advertising",
                    "messages.contactus",
                    "messages.help",
                    "messages.improvements"
                ),
                3 => array(
                    "calendar"
                )
            ),
            'sortable'  => false,
            'resources' => array(
                "default"   => array_keys($modules),
                "layout.sections.topbar"    => array("messages", "forum"),
                "users.overview.notification.order" => array("messages", "news")
            )
        );
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
        foreach($resource as $sortIndex) {
            if (array_key_exists($sortIndex, $data)) {
                $dataArray[$sortIndex] = $data[$sortIndex];
            //} else {
                //$dataArray[$sortIndex] = false;    
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
    public function getLayout() {
        $modules = $this->getModules();

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
    	// LOAD MENUS
		$modules = array();
    	// GET ALL MODULES, CHECK FOR IMenu Interface, CHECK FOR SECTION
    	$modules = $this->getModules("ISectionMenu");

		$menu_items = array();
    	foreach($modules as $index => $module) {
			$menu_item = $module->getSectionMenu($section);
   			if ($menu_item) {
   				$menu_items[$index] = $menu_item;
   			}
    	}
        $menu_items = $this->sortModules("layout.sections." . $section, $menu_items);

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

    	// GET WIDGET BY COLUMN
    	$this->clearWidgets();
    	foreach($modules as $index => $module) {
			$mod_widgets = $module->getWidgets();
   			if ($mod_widgets) {
   				$this->widgets = array_merge($this->widgets, $mod_widgets);
   			}
    	}

    	$widgetStruct = array();
    	foreach($this->layoutSpec['widgets'] as $column_id => $columnWidgets) {
    		foreach($columnWidgets as $widgetID) {
	    		$widgetStruct[] = array(
	    			$widgetID,
	    			$this->widgets[$widgetID],
	    			$column_id
	    		);
	    	}
    	}

    	return $widgetStruct;
    }
}
