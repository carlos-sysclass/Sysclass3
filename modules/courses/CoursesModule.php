<?php 
class CoursesModule extends SysclassModule implements ISectionMenu, IWidgetContainer
{

    // CREATE FUNCTION HERE
    public function getSectionMenu($section_id) {
    	// PROVIDE ADDITIONAL ACCOUNTS MENU
    	/*
    	if ($section_id == "topbar") {
    		$menuItem = array(
    			'icon' 		=> 'comments',
    			'notif' 	=> 20,
    			'text'		=> self::$t->translate('You have %d new forum posts', 12),
    			'external'	=> array(
    				'link'	=> $this->getBasePath() . "/timeline",
    				'text'	=> self::$t->translate('See all forums')
    			),
    			'type'		=> 'inbox',
    			'items'		=> array(
    				array(
    					'link' 		=> $this->getBasePath() . "/timeline/1",
    					
    					'values' => array(
	    					'photo'		=> 'img/avatar2.jpg',
    						'from'		=> 'Lisa Wong',
    						'time'		=> 'Just Now',
	    					'message' 	=> 'Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh...'
    					)
    				)
    			)
    		);

    		return $menuItem;
    	}
    	*/
    	return false;
    }

    public function getWidgets() {
        $this->putScript("plugins/jquery-easy-pie-chart/jquery.easy-pie-chart");

        $this->putModuleScript("courses");

    	return array(
    		'courses.overview' => array(
                'type'      => 'courses', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                'id'        => 'courses-widget',
   				'title' 	=> '<span id="courses-title">Course 1</span> <i class="icon-arrow-right" style="float: none"></i> <span id="lessons-title">Lesson 1</span>',
   				'template'	=> $this->template("overview.widget"),
                'icon'      => 'bolt',
                'box'       => 'dark-blue',
                'tools'     => array(
                    'search'        => true,
                    'reload'        => true,
                    'fullscreen'    => true,
                    'filter'        => true
                )/*,
                'actions'   => array(
                    $this->template("overview.widget.actions"),
                )*/
    		)
    	);
    }
}
