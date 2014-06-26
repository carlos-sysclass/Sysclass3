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

class LayoutModule extends SysclassModule implements IWidgetContainer
{
	protected $layoutSpec = null;
	protected $blankWidget = null;
	protected $widgets = null;

    public function getWidgets($widgetsIndexes = array()) {
    	if (in_array('layout.linkable.view', $widgetsIndexes)) {
	        $modules = $this->getModules("ILinkable");
	        $modulesKeys = array_combine(array_keys($modules), array_keys($modules));

	        $groupLabels = array(
	        	"general" 		=> self::$t->translate('General'),
	        	"communication" => self::$t->translate('Communication'),
	        	"users" 		=> self::$t->translate('Users')
	        );
	        $modulesOrder = $this->getResource('layout.linkable.order');

			$links = array();
			foreach($modulesOrder as $module_id) {
				if (array_key_exists($module_id, $modules)) {
					$links = array_merge_recursive($links, $modules[$module_id]->getLinks());
				}
				unset($modulesKeys[$module_id]);
			}

	        $links = $this->sortModules("layout.linkable.groups.order", $links);

	        foreach($links as $group_id => $group) {
	        	if (array_key_exists($group_id, $groupLabels)) {
	        		$groups[$group_id] = $groupLabels[$group_id];
	        	} else {
	        		$groups[$group_id] = self::$t->translate($group_id);
	        	}
	        }

	        return array(
	            'layout.linkable.view' => array(
	                'type'      => 'control-panel', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
	                'id'        => 'layout-control-panel',
	                //'title'     => true,
	                //'icon'		=> 'th-large',
	                'template'  => $this->template("layout.linkable.view.widget"),
	                'panel'     => true,
	                //'box'     	=> 'light-grey',
	                'tools'		=> array(
	                    'search'        => true,
	                	'reload'	    => 'javascript:void(0);',
	                    'collapse'      => true,
	                    'fullscreen'    => true
	                ),
	                'data'		=> array(
	                	'groups' 	=> $groups,
	                	'links'		=> $links
	                )
	            )
	        );
		}
    }

    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="")
    {
    	parent::init($url, $method, $format, $root, $basePath);

		// DEFAULT LAYOUT SPEC, MUST BE OVERRRIDEN
		$modules = $this->getModules();

		$this->defaultLayout = array(
			/**
			  * @todo THIS DATA MUST BE PERSITED ON DB, OR OTHER MEANS
			 */
			"rows" => array(
				array(
					1   => array("weight" => array(
						'lg' => "8",
						'md' => "8",
						'sm' => "12",
						'xs' => "12"
					)),
					2   => array("weight" => array(
						'lg' => "4",
						'md' => "4",
						'sm' => "12",
						'xs' => "12"
					))
				),
				array(
					3   => array("weight" => array(
						'lg' => "12",
						'md' => "12",
						'sm' => "12",
						'xs' => "12"
					))
				),
			),
			'widgets' => array(
				1 => array(
					"users.overview",
					"courses.overview",
					"news.latest",
					"tutoria.widget"
				),
				2 => array(
					"institution.overview",
					//"proctoring.overview",
					"advisor.overview",
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
				"users.overview.notification.order" => array("courses", "tutoria", "calendar", "advisor", "tests")
			)
		);

		$dashboardAdministrator = array(
			/*
			"rows" => array(
				array(
					1   => array("weight" => "8")
				),
				array(
					3   => array("weight" => "12")
				),
			),
			*/
			"rows" => array(
				array(
					1   => array("weight" => array(
						'lg' => "8",
						'md' => "8",
						'sm' => "12",
						'xs' => "12"
					))
				),
				array(
					3   => array("weight" => array(
						'lg' => "12",
						'md' => "12",
						'sm' => "12",
						'xs' => "12"
					))
				),
			),

			'widgets' => array(
				1 => array(
					'users.overview'
				),
				3 => array(
					"layout.linkable.view"
				)
			),
			'sortable'  => false,
			'resources' => array(
			//	"default"   => array_keys($modules),
			//	"layout.sections.topbar"    => array("messages", "forum"),
			//	"users.overview.notification.order" => array("messages", "news")
				'layout.linkable.order'			=> array("news", "users"),
				"layout.linkable.groups.order" 	=> array("general", "users", "communication")
			)
		);


		$this->createLayout('default', $this->defaultLayout);
		$this->createLayout('dashboard.student', $this->defaultLayout);
		$this->createLayout('dashboard.administrator', $dashboardAdministrator);
		$this->layoutSpec = $this->defaultLayout;
    }

	public function createLayout($id, $spec) {
		$this->layouts[$id] = array_merge($this->defaultLayout, $spec);
		$this->layouts[$id]['resources'] = array_merge($this->defaultLayout['resources'], $this->layouts[$id]['resources']);
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
	public function layoutExists($layout_id = 'default') {
		return array_key_exists($layout_id, $this->layouts);
	}
	public function getLayout($layout_id = 'default') {
		// CHECK AND MERGE LAYOUT 
		$layoutPaths = explode(".", $layout_id);
		for($i = count($layoutPaths); $i > 0; $i--) {
			$path = array_slice ( $layoutPaths, 0, $i);
			if ($this->layoutExists(implode(".", $path))) {
				$layout_id = implode(".", $path);
				break;
			}
		}
		if (!$this->layoutExists($layout_id)) {
			$layout_id = 'default';
		}
		$this->layoutSpec = $this->layouts[$layout_id];	
		
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
