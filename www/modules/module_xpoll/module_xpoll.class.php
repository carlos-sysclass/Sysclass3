<?php
class module_xpoll extends MagesterExtendedModule {
    // CORE MODULE FUNCTIONS
    public function getName() {
        return "XPOLL";
    }
    public function getPermittedRoles() {
        return array("student");
    }
    public function isLessonModule() {
        return true;
    }
	
    /* CURRENT-LESSON ATTACHED MODULE PAGES */
    public function getLessonModule() {
    	$result = $this->getActivePollsBlock("polls-active-list");
		// CHECK FOR TEMPLATING
		$smarty = $this -> getSmartyVar();
		
		if (count($this->templates) > 0) {
			$smarty -> assign ("T_" . $this->getName() . "_TEMPLATES", $this->templates);
		}
		$smarty -> assign ("T_" . $this->getName() . "_MOD_DATA", $this->getModuleData());
		
		$this->assignSmartyModuleVariables();
		
    	return $result;
	}
	public function getLessonSmartyTpl() {
		return $this->getSmartyTpl();
	}
    
    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    /* BLOCK FUNCTIONS */
    public function getActivePollsBlock($blockIndex = null) {
		// GET ALL ACTIVE POLL IDs
		$currentUser	= $this->getCurrentUser();
		$smarty			= $this->getSmartyVar();
	
		if (!is_null($this->getParent())) {
			$context = $this->getParent();
		} else {
			$context = $this;
		}
			
		
		$lessonsIDs 	= array();
		
		if (!$this->getCurrentLesson()) {
			// GET LESSON IDS FROM USER
		} else {
			$lessonsIDs = array($this->getCurrentLesson()->lesson['id']);
		}
		
		if (count($lessonsIDs) > 0) {
			foreach($lessonsIDs as $lesson_id) {
//				echo prepareGetTableData("f_users_to_polls", "*", "users_LOGIN='".$currentUser->user['login']."' AND f_poll_ID IN (SELECT f_poll_ID FROM f_poll WHERE f_forums_ID IN (SELECT id FROM f_forums WHERE lessons_ID =".$lesson_id . "))");
				$result = eF_getTableData("f_users_to_polls", "*", "users_LOGIN='".$currentUser->user['login']."' AND f_poll_ID IN (SELECT f_poll_ID FROM f_poll WHERE f_forums_ID IN (SELECT id FROM f_forums WHERE lessons_ID =".$lesson_id . "))");

				if (count($result) == 0) {
					///continue;
				}
				

				if (sizeof($result) > 0 || (isset($_GET['action']) && $_GET['action'] == 'view') || ($currentUser -> coreAccess['forum'] && $currentUser -> coreAccess['forum'] != 'change')) {
					$smarty -> assign("T_ACTION", "view");
				}
				
				$poll_data = eF_getTableData("f_poll", "*", "f_forums_ID IN (SELECT id FROM f_forums WHERE lessons_ID =".$lesson_id . ")");
				
				foreach($poll_data as $poll_item) {
					//$poll_item['timestamp_end'] > time() ? $poll_item['isopen'] = true : $poll_item['isopen'] = false;
					if ($poll_item['timestamp_end'] > time()) {									
						$poll_item['isopen'] = true;
						
						$currentPoll = $poll_item;
					} else {
						$poll_item['isopen'] = false;
					}
				}
				
				//var_dump($currentPoll);
				
				if (!is_null($currentPoll)) {
								
					$parent_forum = $currentPoll['f_forums_ID'];
	
					$currentPoll['options'] = array_values(unserialize($currentPoll['options'])); //Array values are put here to reindex array, if the keys are not in order
					$poll_votes = eF_getTableData("f_users_to_polls", "*", "f_poll_ID=".$currentPoll['id']);
					
					$votes_distrib = array();
					for ($i = 0; $i < sizeof($poll_data[0]['options']); $i++){
						$votes_distrib[$i]['vote'] = 0;
					}
					
					for ($i = 0; $i < sizeof($poll_votes); $i++){
						$votes_distrib[$poll_votes[$i]['vote']]['vote']++;
					}
					
					for ($i = 0; $i < sizeof($votes_distrib); $i++){
						$votes_distrib[$i]['perc'] = round($votes_distrib[$i]['vote'] / sizeof($poll_votes), 2);
						$votes_distrib[$i]['text'] = $currentPoll['options'][$i];
						$votes_distrib[$i]['width'] = $votes_distrib[$i]['perc'] * 200;
					}
	
					$smarty -> assign("T_POLL_VOTES", $votes_distrib);
					$smarty -> assign("T_POLL_TOTALVOTES", sizeof($poll_votes));
					
					
					$form = new HTML_QuickForm("poll_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=forum&poll=".$currentPoll['id'], "", null, true); //Build the form
					
					$form -> addElement("hidden", "poll_id", $currentPoll['id']);
					
					foreach ($currentPoll['options'] as $key => $option) {
						$group[] = HTML_Quickform :: createElement('radio', 'vote', null, $option, $key);
					}
					$form -> addGroup($group, 'options', '', '<br/>');
					$form -> addRule('options', _PLEASEPICKANOPTION, 'required', null, 'client');
					$form -> addElement('submit', 'submit_poll', _VOTE, 'class = "flatButton"');
					
					if ($form -> isSubmitted() && $form -> validate()) {
						/*
						$values = $form -> exportValues();
						//pr($values);
						//debug();
						$res = eF_getTableData("f_users_to_polls", "*", "f_poll_ID=".$values['options']['vote']." and users_LOGIN='".$currentUser -> user['login']."'");
						//debug(false);
						if (sizeof($res) > 0){
							$message = _YOUHAVEALREADYVOTED;
							$message_type = 'failure';
						} else {
							$fields = array(
								'f_poll_ID' => $_GET['poll'],
								'users_LOGIN' => $_SESSION['s_login'],
								'vote' => $values['options']['vote'],
								'timestamp' => time()
							);
							if (eF_insertTableData("f_users_to_polls", $fields)){
								$message = _SUCCESFULLYVOTED;
								$message_type = 'success';
								eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=forum&poll=".$_GET['poll']);
							} else {
								$message = _SOMEPROBLEMEMERGED;
								$message_type = 'failure';
							}
						}
						*/
					}
					$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty); //Create a smarty renderer
					$form -> accept($renderer); //Assign this form to the renderer, so that corresponding template code is created
					$smarty -> assign('T_POLL_FORM', $renderer -> toArray()); //Assign the form to the template
					$smarty -> assign("T_POLL", $currentPoll);
					
					$context->appendTemplate(array(
				   		'title'			=> __XPOLL_ACTIVE_LIST,
				   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xpoll.active_polls.tpl',
				   		'contentclass'	=> 'blockContents',
			    		'options'		=> array(
			    			array(
			    				'text'	=> 'Ver Resultados',
			    				'image'	=> '16x16/go_into.png',
			    				'href'	=> $_SERVER['PHP_SELF'] . "?ctg=forum&poll=" . $currentPoll['id'] . "&action=view"
			    			)
			    		)
			    	), $blockIndex);
			    	return true;
					break;
		    	} else {
			    	return false;
					break;
		    	}
			}
		}
    }
    /* ACTION FUNCTIONS */
    /* HOOK ACTION FUNCTIONS */  
    /* DATA MODEL FUNCTIONS /*/
}
