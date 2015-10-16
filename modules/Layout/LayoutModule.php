<?php
namespace Sysclass\Modules\Layout;
/**
 * Module Class File
 * @filesource
 */
/**
 * This module manage the pages layouts, create and save layout paterns, and manage layout sections
 * @package Sysclass\Modules
 * @todo think about move this module to PlicoLib
 */

class LayoutModule extends \SysclassModule
{
	protected $layoutSpec = null;
	protected $blankWidget = null;
	protected $widgets = null;

    public function init($url = null, $method = null, $format = null, $root=NULL, $basePath="")
    {
    	parent::init($url, $method, $format, $root, $basePath);
/*
		// DEFAULT LAYOUT SPEC, MUST BE OVERRRIDEN
		$modules = $this->getModules();

		$this->defaultLayout = array(
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
*/
/*
		$dashboardAdministrator = array(
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
				'layout.linkable.order'			=> array("institution", "courses", "news", "users"),
				"layout.linkable.groups.order" 	=> array("administration", "content", "users", "communication", "not_classified")
			)
		);
*/
/*
		$this->createLayout('default', $this->defaultLayout);
		$this->createLayout('dashboard.student', $this->defaultLayout);
//		$this->createLayout('dashboard.administrator', $dashboardAdministrator);
		$this->layoutSpec = $this->defaultLayout;
*/
    }
/*
	public function createLayout($id, $spec) {
		$this->layouts[$id] = array_merge($this->defaultLayout, $spec);
		$this->layouts[$id]['resources'] = array_merge($this->defaultLayout['resources'], $this->layouts[$id]['resources']);
	}
*/
	protected function getResource($resourceID) {
		if (array_key_exists($resourceID, $this->layoutSpec['resources'])) {
			return $this->layoutSpec['resources'][$resourceID];
		} else {
			return $this->layoutSpec['resources']['default'];
		}
	}
/*
	public function layoutExists($layout_id = 'default') {
		return array_key_exists($layout_id, $this->layouts);
	}
*/

}
