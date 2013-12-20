<?php 
class InstitutionModule extends SysclassModule implements ISectionMenu, IWidgetContainer
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
        $this->putModuleScript("institution");
        $data = array();
        // GET USER POLO
        $currentUser = $this->getCurrentUser(true);

        $data['polo'] = $currentUser->getUserPolo();
        //var_dump($data['polo']);

    	return array(
    		'institution.overview' => array(
   				//'title' 	=> 'User Overview',
   				'template'	=> $this->template("overview.widget"),
                'panel'     => true,
                //'box'       => 'blue'
                'data'      => $data
    		)
    	);
    }
}
