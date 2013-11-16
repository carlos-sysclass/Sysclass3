<?php 
/**
  * This module manage the pages layouts, create and save layout paterns, and manage layout sections
*/
class LayoutModule extends SysclassModule
{
	protected $layoutSpec = null;
	protected $blankWidget = null;
	protected $widgets = null;
    // CREATE FUNCTION HERE
    protected function clearWidgets() {
    	$this->widgets = array();
    	$this->widgets['blank'] = array(
    		'title' => 'Blank Porlet'
    	);
    }
    public function getLayout() {

    	return $this->layoutSpec = array(
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
            		"blank",
            		"news.latest",
            		"blank",
            		"forum"
            	),
            	2 => array(
            		"blank",
            		"blank",
            		"messages.contactus",
            		"messages.help",
            		"messages.improvements"
            	),
            	3 => array(
            		"blank"
            	)
            ),
            'sortable'  => false
        );

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

    	$sorterArray = $this->getMenuOrderBySection($section, count($menu_items));
    	array_multisort($sorterArray,$menu_items);
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
			$mod_widgets = $module->getWidgets($section);
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
