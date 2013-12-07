<?php 
class UsersModule extends SysclassModule implements ISectionMenu, IWidgetContainer
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
        $modules = $this->getModules("ISummarizable");

        $data = array();
        $data['notification'] = array();

        foreach($modules as $key => $mod) {
            $data['notification'][$key] = $mod->getSummary();
        }
        
        $data['notification'] = $this->module("layout")
            ->sortModules("users.overview.notification.order", $data['notification']);

        $this->putModuleScript("users");

    	return array(
    		'users.overview' => array(
                'id'        => 'users-panel',
                'type'      => 'users',
   				//'title' 	=> 'User Overview',
   				'template'	=> $this->template("overview.widget"),
   				'panel'		=> true,
                'data'      => $data
                //'box'       => 'blue'
    		)
    	);
    }
    /**
     * Module Entry Point
     *
     * @url GET /profile
     */
    public function profilePage()
    {
        $currentUser    = $this->getCurrentUser(true);
        // PUT HERE CHAT MODULE (CURRENTLY TUTORIA)
        $this->putCss("css/pages/profile");
        $this->display("profile.tpl");
    }

}
