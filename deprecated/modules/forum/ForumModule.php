<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class ForumModule extends SysclassModule implements /* ISectionMenu, */ IWidgetContainer
{

    /* ISectionMenu */
    /*
    public function getSectionMenu($section_id) {
    	if ($section_id == "topbar") {
    		$menuItem = array(
    			'icon' 		=> 'comments',
    			'notif' 	=> 20,
    			'text'		=> $this->translate->translate('You have %d new forum posts', 12),
    			'external'	=> array(
    				'link'	=> $this->getBasePath() . "/timeline",
    				'text'	=> $this->translate->translate('See all forums')
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
    	return false;
    }
    */
    public function getWidgets($widgetsIndexes = array(), $caller = null) {
        if (in_array('forum', $widgetsIndexes)) {
        	return array(
        		'forum' => array(
       				'title' 	=> 'Forums',
       				'template'	=> $this->template("lastest.posts"),
                    'icon'      => 'comments',
                    'box'       => 'grey',
                    'tools'     => array(
                        'reload'    => $this->getBasePath() . "/widget/refresh",
                        'fullscreen'    => true
                    )
        		)
        	);
        }
    }

    /**
     * [ add a description ]
     *
     * @url GET /data
     * @deprecated Use /items/me entry point
     */
    public function dataAction()
    {
        $page   = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 10;

        $currentUser    = self::$current_user;

        //$xuserModule = $this->loadModule("xuser");
        $userUnits = $currentUser->getUnits();
        $unitsIds = array_keys($userUnits);

        // GET LAST MESSAGES FROM USER LESSONS
        $forum_messages = $this->_getTableData("f_messages fm
            JOIN f_topics ft
            JOIN f_forums ff
            LEFT OUTER JOIN units l ON ff.units_ID = l.id",
            "ft.title, ft.id as topic_id, ft.users_LOGIN, MAX(fm.timestamp) as `timestamp`, COUNT(fm.id) as total_reply, l.name as units_name, ff.units_id",
            sprintf("ft.f_forums_ID=ff.id AND fm.f_topics_ID=ft.id AND ff.units_ID IN (%s) ",
            implode(",", $unitsIds)),
            "MAX(fm.timestamp) DESC",
            "ft.title, ft.id, ft.users_LOGIN, l.name, units_id",
            sprintf("%d, %d", ($page - 1) * $per_page, $per_page)
        );
        return $forum_messages;
    }
    /**
     * [ add a description ]
     *
     * @url GET /data/:topic
     */
    public function dataTopicAction($topic)
    {
        if ($topic == 0) {
            return array();
        }

        $currentUser    = self::$current_user;

        //$xuserModule = $this->loadModule("xuser");
        $userUnits = $currentUser->getUnits();
        $unitsIds = array_keys($userUnits);

        // GET LAST MESSAGES FROM USER LESSONS
        $forum_messages = $this->_getTableData("f_messages fm
            JOIN f_topics ft
            JOIN f_forums ff
            LEFT OUTER JOIN units l ON ff.units_ID = l.id",
            "ft.title, ft.id as topic_id, ft.users_LOGIN, fm.timestamp, l.name as units_name, ff.units_id",
            sprintf("ft.f_forums_ID=ff.id AND fm.f_topics_ID=ft.id AND ff.units_ID IN (%s) AND fm.f_topics_ID = %d",
            implode(",", $unitsIds), $topic),
            "fm.timestamp ASC"
        );
        return $forum_messages;
    }

}
