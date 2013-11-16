<?php 
class MessagesModule extends SysclassModule implements ISectionMenu, IWidgetContainer
{

    // CREATE FUNCTION HERE
    public function getSectionMenu($section_id) {
    	if ($section_id == "topbar") {
    		$menuItem = array(
    			'icon' 		=> 'envelope',
    			'notif' 	=> 5,
    			'text'		=> self::$t->translate('You have %s new messages', 12),
    			'external'	=> array(
    				'link'	=> $this->getBasePath() . "/inbox",
    				'text'	=> self::$t->translate('See all messages')
    			),
    			'type'		=> 'inbox',
    			'items'		=> array(
    				array(
    					'link' 		=> $this->getBasePath() . "/inbox/1",
    					
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
    	return false;
    }

    public function getWidgets() {
        return array(
            'messages.contactus' => array(
                'title'     => self::$t->translate('Contact Us'),
                'template'  => $this->template("contactus"),
                'icon'      => 'envelope',
                //'box'       => 'blue',
                'tools'     => array(
                    'reload'    => $this->getBasePath() . "/widget/refresh",
                )
            ),
            'messages.help' => array(
                'title'     => self::$t->translate('We are here to help'),
                'template'  => $this->template("contactus"),
                'icon'      => 'help',
                //'box'       => 'blue',
                'tools'     => array(
                    'reload'    => $this->getBasePath() . "/widget/refresh",
                )
            ),
            'messages.improvements' => array(
                'title'     => self::$t->translate('System Improvements'),
                'template'  => $this->template("contactus"),
                'icon'      => 'envelope',
                //'box'       => 'blue',
                'tools'     => array(
                    'reload'    => $this->getBasePath() . "/widget/refresh",
                )
            )
        );
    }
}
