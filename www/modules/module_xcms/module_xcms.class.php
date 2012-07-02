<?php
class module_xcms extends MagesterExtendedModule {
	const PGTYPE_USER_TYPE	= 1;
	const PGTYPE_COURSE		= 2;
	//const PGTYPE_LESSON		= 4;

	protected $page_types = array(
		'user_type'	=> self::PGTYPE_USER_TYPE,
		'course'	=> self::PGTYPE_COURSE/*,
	'lesson'	=> self::PGTYPE_LESSON*/
	);

	protected $page_callbacks = array(
		'user_type'	=> null,
		'course'	=> null
	);

	public function __construct($defined_moduleBaseUrl, $defined_moduleFolder) {
		parent::__construct($defined_moduleBaseUrl, $defined_moduleFolder);

		$this->page_callbacks = array(
			'user_type'	=> 
		create_function('$context', '
					$object = $context->getCurrentUser();
					return isset($object->user["user_type"]) ? $object->user["user_type"] : false;
				'),
			'course'	=> 
		create_function('$context', '
					$object = $context->getCurrentCourse();
					return isset($object->course["id"]) ? $object->course["id"] : false;
				')/*,
		'lesson'	=> self::PGTYPE_LESSON
		*/
		);


		$this->preActions[] = 'checkUserPermissionAction';
		//		$this->preActions[] = 'makeEnrollmentOptions';

		//		$this->postActions[] = 'checkUserPermission';
		$this->postActions[] = 'makeXenrollmentOptionsAction';
	}

	// CORE MODULE FUNCTIONS
	public function getName() {
		return "XCMS";
	}
	public function getPermittedRoles() {
		return array("student", "professor");
	}
	public function isLessonModule() {
		return false;
	}

	public function getNavigationLinks() {
		$this->showModuleBreadcrumbs = false;
		return parent::getNavigationLinks();
	}

	/* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
	/*
	 public function addStylesheets() {
		return array("960gs/fluid/24columns");
		}
		*/
	
	public function loadNews( $ajax=false ) {
		$currentUser	= $this->getCurrentUser();

		# Carrega noticias da ultima licao selecionada
		$news = news :: getNews(0, true) + news :: getNews($_SESSION['s_lessons_ID'], false);

		# Filtra comunicado pela classe do aluno
		$userClasses = ef_getTableDataFlat(
			"users_to_courses",
			"classe_id",
			sprintf("users_LOGIN = '%s'", $currentUser->user['login'])
		);
		foreach( $news as $key => $noticia) {
			if ( !in_array( $noticia['classe_id'], $userClasses['classe_id'] ) && $noticia['classe_id']!=0 ) {
				unset($news[$key]);
			} elseif ( $ajax && $noticia['classe_id']==0 ) { 
				unset($news[$key]);
			}
		}

		return $news;
	}

	/* BLOCK FUNCTIONS */
	public function loadNewsBlock($blockIndex = null) {
		$smarty = $this->getSmartyVar();

		# carrega comunicados do aluno
		$news =  $this->loadNews();
		$announcements_options = 	array( 
										array(
											'text' 	=> _ANNOUNCEMENTGO, 
											'image' => "16x16/go_into.png", 
											'href' 	=> basename($_SERVER['PHP_SELF'])."?ctg=news&lessons_ID=all"
										)
									);

		# passa comunicados do aluno para template
		if ( count($news) > 0 ) {
			$smarty -> assign("T_NEWS", $news);
			$smarty -> assign("T_NEWS_OPTIONS",$announcements_options);
			$smarty -> assign("T_NEWS_LINK", "student.php?ctg=news");
			$this->getParent()->appendTemplate	(	array (
												   		'title'			=> _ANNOUNCEMENTS,
												   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xcms.news.tpl',
												   		'contentclass'	=> 'blockContents'
			   										),
			   										$blockIndex
		   										);
		   	return true;
		}
		return false;
	}

	public function loadCalendarBlock($blockIndex = null) {
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		 
		if (!isset($currentUser -> coreAccess['calendar']) || $currentUser -> coreAccess['calendar'] != 'hidden') {
			$today = getdate(time()); //Get current time in an array
			$today = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']); //Create a timestamp that is today, 00:00. this will be used in calendar for displaying today
			isset($_GET['view_calendar']) && eF_checkParameter($_GET['view_calendar'], 'timestamp') ? $view_calendar = $_GET['view_calendar'] : $view_calendar = $today; //If a specific calendar date is not defined in the GET, set as the current day to be today
				
			$calendarOptions = array();
			if (!isset($currentUser -> coreAccess['calendar']) || $currentUser -> coreAccess['calendar'] == 'change') {
				$calendarOptions[] = array('text' => _ADDCALENDAR, 'image' => "16x16/add.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=calendar&add=1&view_calendar=".$view_calendar."&popup=1", "onClick" => "eF_js_showDivPopup('"._ADDCALENDAR."', 2)", "target" => "POPUP_FRAME");
			}
			$calendarOptions[] = array('text' => _GOTOCALENDAR, 'image' => "16x16/go_into.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=calendar");
				
			$smarty -> assign("T_CALENDAR_OPTIONS", $calendarOptions);
			$smarty -> assign("T_CALENDAR_LINK", basename($_SERVER['PHP_SELF'])."?ctg=calendar");
			isset($_GET['add_another']) ? $smarty -> assign('T_ADD_ANOTHER', "1") : null;
				
			$events = calendar :: getCalendarEventsForUser($currentUser);
			$events = calendar :: sortCalendarEventsByTimestamp($events);
				
			$smarty -> assign("T_CALENDAR_EVENTS", $events); //Assign events and specific day timestamp to smarty, to be used from calendar
			$smarty -> assign("T_VIEW_CALENDAR", $view_calendar);

			$this->getParent()->appendTemplate(array(
		   		'title'			=> _CALENDAR,
		   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xcms.calendar.tpl',
		   		'contentclass'	=> 'blockContents blockCalendar'
		   		), $blockIndex);
		} else {
			return false;
		}
		/*
		 T_CALENDAR_EVENTS
		 T_VIEW_CALENDAR
		 T_CALENDAR_OPTIONS
		 T_CALENDAR_LINK
		 */
		return true;
	}
	public function loadNewsletterBlock($blockIndex = null) {
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();

		if (!isset($currentUser -> coreAccess['calendar']) || $currentUser -> coreAccess['calendar'] != 'hidden') {
			$today = getdate(time()); //Get current time in an array
			$today = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']); //Create a timestamp that is today, 00:00. this will be used in calendar for displaying today
			isset($_GET['view_calendar']) && eF_checkParameter($_GET['view_calendar'], 'timestamp') ? $view_calendar = $_GET['view_calendar'] : $view_calendar = $today; //If a specific calendar date is not defined in the GET, set as the current day to be today
				
			$calendarOptions = array();
			if (!isset($currentUser -> coreAccess['calendar']) || $currentUser -> coreAccess['calendar'] == 'change') {
				$calendarOptions[] = array('text' => _ADDCALENDAR, 'image' => "16x16/add.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=calendar&add=1&view_calendar=".$view_calendar."&popup=1", "onClick" => "eF_js_showDivPopup('"._ADDCALENDAR."', 2)", "target" => "POPUP_FRAME");
			}
			$calendarOptions[] = array('text' => _GOTOCALENDAR, 'image' => "16x16/go_into.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=calendar");
				
			$smarty -> assign("T_CALENDAR_OPTIONS", $calendarOptions);
			$smarty -> assign("T_CALENDAR_LINK", basename($_SERVER['PHP_SELF'])."?ctg=calendar");
			isset($_GET['add_another']) ? $smarty -> assign('T_ADD_ANOTHER', "1") : null;
				
			$events = calendar :: getCalendarEventsForUser($currentUser);
			$events = calendar :: sortCalendarEventsByTimestamp($events);
				
			$smarty -> assign("T_CALENDAR_EVENTS", $events); //Assign events and specific day timestamp to smarty, to be used from calendar
			$smarty -> assign("T_VIEW_CALENDAR", $view_calendar);

			$this->getParent()->appendTemplate(array(
		   		'title'			=> "Acompanhe mensalmente as notícias da ULT.",
		   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xcms.newsletter.tpl',
		   		'contentclass'	=> 'blockContents'
		   		), $blockIndex);
		} else {
			return false;
		}
		/*
		 T_CALENDAR_EVENTS
		 T_VIEW_CALENDAR
		 T_CALENDAR_OPTIONS
		 T_CALENDAR_LINK
		 */
		return true;
	}

	public function loadAdsBlock($blockIndex = null) {
		$this->getParent()->appendTemplate(array(
	   		'title'			=> " ",
	   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xcms.ads.tpl',
	   		'contentclass'	=> 'blockContents'
	   		), $blockIndex);
	   		 
	   		$this->injectJS("jquery/jquery.cycle.all");
	   		 
	   		return true;
	}

	/* ACTIONS FUNCTIONS */
	public function loadNewsAction(){
		$smarty = $this->getSmartyVar();

		# carrega comunicados do aluno
		$news =  $this->loadNews(true);
		$announcements_options = 	array( 
										array(
											'text' 	=> _ANNOUNCEMENTGO, 
											'image' => "16x16/go_into.png", 
											'href' 	=> basename($_SERVER['PHP_SELF'])."?ctg=news&lessons_ID=all"
										)
									);

		# passa comunicados do aluno para template
		if ( count($news) > 0 ) {
			$smarty -> assign("T_NEWS", $news);
			$smarty -> assign("T_NEWS_OPTIONS",$announcements_options);
			$smarty -> assign("T_NEWS_LINK", "student.php?ctg=news");
			echo $smarty -> fetch($this->moduleBaseDir . 'templates/blocks/xcms.news.tpl');
		}
	}
	
	public function loadXpageAction() {
		$smarty = $this->getSmartyVar();
		if ( !($editedPage = $this->getEditedPage()) ) {
			$this->setMessageVar(__XCMS_NOPAGE_FOUND, "failure");
			/*
			 echo '<pre>';
			 $format = '(%1$2d = %1$04b) = (%2$2d = %2$04b)'
			 . ' %3$s (%4$2d = %4$04b)' . "\n";

			 echo <<<EOH
			 ---------     ---------  -- ---------
			 result        value      op test
			 ---------     ---------  -- ---------
			 EOH;
			 $values = array(0, 1, 2, 4, 8);
			 $test = 1 + 4;
			 echo "\n Bitwise AND \n";
			 foreach ($values as $value) {
			 $result = $value & $test;
			 printf($format, $result, $value, '&', $test);
			 }
			 echo "\n Bitwise Inclusive OR \n";
			 foreach ($values as $value) {
			 $result = $value | $test;
			 printf($format, $result, $value, '|', $test);
			 }

			 echo "\n Bitwise Exclusive OR (XOR) \n";
			 foreach ($values as $value) {
			 $result = $value ^ $test;
			 printf($format, $result, $value, '^', $test);
			 }
			 echo '</pre>';
			 exit;
			 */
			// TRY TO GET PAGE BY PAGE TYPE
			if ($_GET['xpage_type']) {
				$editedPage = $this->getPageByPageTypeId($_GET['xpage_type']);
			}
			if (!$editedPage) {
				return true;
			}
		}
		// ALL PERMISSIONS
		$editedPage['rules'] = array(
    		'allow'	=> array(
    			'user_type'	=> 'student',
    			'course'	=> 'all'
    			),
    		'deny'	=> 'all',
    		'order'	=> 'allow, deny'    	
    		);
    		 

    		// DEFINE LAYOUT BLOCK SIZES
    		$sections = array();
    		switch($editedPage['layout']) {
    			case 'twocolumn-50-50' : {
    				$sections[] = array(
					'class'	=> "grid_12"
					);
					$sections[] = array(
					'class'	=> "grid_12"
					);
					break;
    			}
    			case 'twocolumn-75-25' : {
    				$sections[] = array(
					'class'	=> "grid_18"
					);
					$sections[] = array(
					'class'	=> "grid_6"
					);
					break;
    			}
    			case 'twocolumn-25-75' : {
    				$sections[] = array(
					'class'	=> "grid_6"
					);
					$sections[] = array(
					'class'	=> "grid_18"
					);
					break;
    			}
    			case 'twocolumn-66-33' : {
    				$sections[] = array(
					'class'	=> "grid_16"
					);
					$sections[] = array(
					'class'	=> "grid_8"
					);
					break;
    			}
    			case 'twocolumn-33-66' : {
    				$sections[] = array(
					'class'	=> "grid_8"
					);
					$sections[] = array(
					'class'	=> "grid_16"
					);
					break;
    			}
    			case 'twocolumn-70-30' : {
    				$sections[] = array(
					'class'	=> "grid_17"
					);
					$sections[] = array(
					'class'	=> "grid_7"
					);
					break;
    			}
    			case 'threecolumn-33-34-33' : {
    				$sections[] = array(
					'class'	=> "grid_8"
					);
					$sections[] = array(
					'class'	=> "grid_8"
					);
					$sections[] = array(
					'class'	=> "grid_8"
					);
					break;
    			}
    			case 'onecolumn' :
    			default : {
    				$sections[] = array(
					'class'	=> "grid_24"
					);
					break;
    			}
    		}

    		end($sections);
    		$lastSectionKey = key($sections);

    		if (!is_null($editedPage['positions']) && is_array($editedPage['positions'])) {
    			// APLLY BLOCK POSITIONS TO SECTIONS
    			$positions = array_values($editedPage['positions']);
    			foreach($positions as $key => $blocksNames) {
    				if (array_key_exists($key, $sections)) {
    					$sections[$key]['blocks'] = $blocksNames;
    				} else {
    					$sections[$lastSectionKey]['blocks'] = $blocksNames;
    				}
    			}
    		}

    		$pageBlocks = $this->loadPageBlocks($editedPage['id']);
    		reset($sections);

    		foreach($pageBlocks as $block) {
    			if ($blockModule = $this->loadModule($block['module'])) {
    				 
    				$actionResult = $blockModule->setParent($this)->callBlock($block['action'], $block['name'], null, $block['tag']);
    				 
    				if (!$actionResult) {
    					//	var_dump($block['module'] . '::' . $block['action']);
    					//	var_dump($actionResult);
    				}

    				// CHECK FOR SECTION POSITION
    				if (is_null($editedPage['positions'])) {
    					// NO POSITION, THEN PUT ONE  DEFAULT LIST
    					if (($currentSection = each($sections)) === FALSE) {
    						reset($sections);
    						$currentSection = each($sections);
    					}
    					$sections[$currentSection['key']]['blocks'][] = $block['name'];
    				} else {
    					// CHECK FOR BLOCK NAME IN COLUMN INDEX
    					$found = false;
    					foreach($sections as $key => $column) {
    						if (in_array($block['name'], $column['blocks'])) {
    							$found = true;
    							break;
    						}
    					}
    					if (!$found) {
    						if (($currentSection = each($sections)) === FALSE) {
    							reset($sections);
    							$currentSection = each($sections);
    						}
    						$sections[$currentSection['key']]['blocks'][] = $block['name'];
    					}
    			}
    		}

    		if ($editedPage['id'] == 1) {
    			$editedPage['positions'] = array(
    			array(
	    			'magesterCourseUserActivity',
	    			'magesterUserCourseLessonContent',
	    			'magesterNews',
	    			'magesterAcademicCalendar',
		    		'magesterMainBillboard',
	    			'magesterRssFeedsList'
	    			),
	    			array(
	    			'sysclassAds',
	    			'magesterPaymentDueInvoicesBlock',
	    			'magesterCalendar',
	    			'magesterContentScheduleList',
	    			'magesterQuickContactList',
	    			'magesterNewsletter',
	    			'magesterQuickFeedbackList',
	    		    'magesterDataPolos'
	    		    )
	    		    );
    		} else {
    			// UPDATE POSITIONS
    			$editedPage['positions'] = array();
    			foreach($sections as $key => $value) {
    				$editedPage['positions'][] = array_values($value['blocks']);
    			}
    		}
    		/*
    		 echo '<pre>';
    		 var_dump($sections);
    		 var_dump($editedPage);
    		 echo '</pre>';
    		 */
    		$this->persistPage($editedPage);

    		$smarty -> assign("T_XCMS_SECTIONS", $sections);
    		$smarty -> assign("T_XCMS_EDITED_PAGE", $editedPage);

	}}
	/* HOOK ACTIONS FUNCTIONS */
	/* DATA MODEL FUNCTIONS /*/
	protected function loadPageBlocks($page_id = null) {
		if (is_null($page_id)) {
			$editedpage = $this->getEditedPage();
			$page_id = $editedpage['id'];
		}
		$pageBlocks = eF_getTableData(
    		"module_xcms_pages_to_blocks pg2block 
    		LEFT JOIN module_xcms_blocks block ON (pg2block.block_id = block.id)",
    		"pg2block.page_id, block.name, block.module, block.action, block.tag as block_tag, pg2block.tag as custom_tag",
    		"pg2block.page_id = " . $page_id
		);
		 
		foreach($pageBlocks as &$block) {
			$block['block_tag'] = json_decode($block['block_tag'], true);
			$block['custom_tag'] = json_decode($block['custom_tag'], true);
			$block['tag'] = array_merge (
			is_array($block['block_tag']) ? $block['block_tag'] : array(),
			is_array($block['custom_tag']) ? $block['custom_tag'] : array()
			);
		}
		 
		return $pageBlocks;
		 
	}
	protected function preFilterPageFields($editedpage) {
		$editedpage['positions'] = json_encode($editedpage['positions']);
		$editedpage['rules'] = json_encode($editedpage['rules']);
		 
		return $editedpage;
	}
	public function persistPage($editedpage = null, $page_id = null) {
		if (is_null($editedpage)) {
			$editedpage = $this->getEditedPage();
		}
		if (is_null($page_id)) {
			$page_id = $editedpage['id'];
		}
		$pageFields = $this->preFilterPageFields($editedpage);
		eF_updateTableData("module_xcms_pages", $pageFields, "id = " . $page_id);
	}
	public function getPageByPageTypeId($page_bit) {
		$pageContraints = array();
		foreach($this->page_types as $key => $type) {
			$pageContraints[$key] = (bool)($_GET['xpage_type'] & $type);
		}
		/*
		 $pageContraints
		 foreach($pageContraints as $key
		 $this->page_callbacks
		 */
		//var_dump($pageContraints);
		//exit;
	}
	public function createBaseUrlByPageId($page_id, $action = 'load_xpage') {
		return sprintf($this->moduleBaseUrl . "&action=%s&xpage_id=%s", $action, $page_id);
	}
	/*
	 public function updatePageFieldById($page_id, $fields) {
	 eF_updateTableData("module_xcms_pages", $fields, "id = " . $page_id);
	 }
	 */
}
?>