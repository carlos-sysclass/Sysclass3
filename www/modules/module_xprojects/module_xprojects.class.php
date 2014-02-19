<?php

class module_xprojects extends MagesterExtendedModule
{
    // CORE MODULE FUNCTIONS
    public function getName()
    {
        return "XPROJECTS";
    }
    public function getPermittedRoles()
    {
        return array("professor", "student");
    }
    public function isLessonModule()
    {
        return true;
    }

    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
   /*
    public function getCourseDashboardLinkInfo()
    {
		return array(
			'title' 		=> __XPROJECTS_NAME,
        	'image'			=> "images/others/transparent.gif",
			'image_class'	=> "sprite32 sprite32-projects",
            'link'  		=> $this -> moduleBaseUrl
		);
    }
    */

    public function getLessonCenterLinkInfo()
    {
		$currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student" || $currentUser -> getRole($this -> getCurrentLesson()) == "professor") {

        	$count = sC_countTableData(
        		"module_xprojects_groups_to_users",
        		"user_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if ($count[0]['count'] > 0) {

	        	$result = sC_getTableData(
	        		"module_xprojects_groups_to_users grp2user, module_xprojects_topics top",
	        		"*",
	        		sprintf("grp2user.topic_id = top.id AND grp2user.user_id = %d", $currentUser->user['id'])
	        	);

				if (count($result) == 1 && $result[0]['page_id'] > 0) {
					$this->loadModule("xcms");

					$centerLinkInfoUrl = $this->modules['xcms']->createBaseUrlByPageId($result[0]['page_id']);
				} else {
					$centerLinkInfoUrl = $this -> moduleBaseUrl . "&action=choose_group";
				}

				if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {
		            return array(
		            	'title' 		=> 'Meus Projetos',
						'image'			=> $this->moduleBaseLink . 'images/32/projects.png',
						'link'  		=> $centerLinkInfoUrl
					);
				} else {
		            return array(
		            	'title' 		=> 'Projetos dos Alunos',
						'image'			=> $this->moduleBaseLink . 'images/32/projects.png',
						'link'  		=> $centerLinkInfoUrl
					);
				}

        	}
        }
    }

    /* BLOCK FUNCTIONS */
    /*
	public function loadCourseProjectsBlock($blockIndex = null)
	{
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

    	// GET INTER-LESSON PROJECTS

    	$this->getCourseProjects();
//		if (!isset($currentUser -> coreAccess['calendar']) || $currentUser -> coreAccess['calendar'] != 'hidden') {

    	// CHECK IF USER IS A COURSE (INTER-LESSON) GROUP

    	//
	    	$this->getParent()->appendTemplate(array(
		   		'title'			=> __XPROJECTS_COURSE_PROJECTS,
		   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xprojects.projects.tpl'
	    	), $blockIndex);
//		} else {
//			return false;
//		}
    	return true;

    }
    public function loadCourseGroupsBlock($blockIndex = null)
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

//		if (!isset($currentUser -> coreAccess['calendar']) || $currentUser -> coreAccess['calendar'] != 'hidden') {

    	// CHECK IF USER IS A COURSE (INTER-LESSON) GROUP

    	//
	    	$this->getParent()->appendTemplate(array(
		   		'title'			=> __XPROJECTS_COURSE_GROUPS,
		   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xprojects.groups.tpl'
	    	), $blockIndex);
//		} else {
//			return false;
//		}
    	return true;

    }
    */
    public function loadGroupsLandingBlock($blockIndex = null)
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {

        	$count = sC_countTableData(
        		"module_xprojects_groups_to_users",
        		"user_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if ($count[0]['count'] > 0) {
        		$topicData = sC_getTableData(
        			"module_xprojects_groups_to_users grp_usr,
        			module_xprojects_groups grp,
        			module_xprojects_topics top,
        			module_xprojects prj,
        			users u",
        			"
        			top.id,
        			top.title,
        			top.project_id,
        			prj.title as project_name,
        			grp_usr.user_id,
        			u.name, u.surname, u.login,
        			grp.tag
        			",
        			sprintf("
        				grp_usr.topic_id = top.id
        				AND grp_usr.topic_id = grp.id
        				AND grp_usr.user_id = u.id
        				AND top.project_id = prj.id
        				AND user_id = %d", $currentUser->user['id']
        			)
        		);

        		$topicData[0]['tag'] = json_decode($topicData[0]['tag'], true);

        		$smarty -> assign("T_XPROJECTS_TOPIC", $topicData[0]);

		    	$this->getParent()->appendTemplate(array(
			   		'title'			=> $topicData[0]['project_name'],
			   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xprojects.groups.landing.tpl',
		    		'contentclass'	=> 'blockContents'
		    	), $blockIndex);
	      	} else {
	      		 sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        		exit;
	      	}
        } elseif ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
        	$count = sC_countTableData(
        		"module_xprojects_groups_to_users",
        		"user_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	var_dump($count);
        } else {
        	sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        	exit;
        }

        return false;
    }
    public function loadGroupsMembersBlock($blockIndex = null)
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {

        	$topicUser = sC_getTableData(
        		"module_xprojects_groups_to_users",
        		"user_id, topic_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if (is_numeric($topicUser[0]['topic_id'])) {
        		// GET
        		$topic_id = $topicUser[0]['topic_id'];

        		$membersData = sC_getTableData(
        			"module_xprojects_groups_to_users grp_usr,
        			users u",
        			"
        			grp_usr.user_id,
        			u.name, u.surname, u.login, u.user_type
        			",
        			sprintf("
        				grp_usr.user_id = u.id
        				AND grp_usr.topic_id = %d
        				AND grp_usr.user_id <> %d",
        				$topic_id, $currentUser->user['id']
        			)
        		);

        		foreach ($membersData as &$member) {
        			$member['username'] = formatLogin(null, $member);
        		}
        		$smarty -> assign("T_XPROJECTS_MEMBERS", $membersData);

		    	$this->getParent()->appendTemplate(array(
			   		'title'			=> __XPROJECTS_PROJECT_MEMBERS,
			   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xprojects.groups.members.tpl',
		    		'contentclass'	=> 'blockContents'
		    	), $blockIndex);
	      	} else {
	      		 sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        		exit;
	      	}
        } elseif ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
        } else {
        	sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        	exit;
        }

        return false;
    }
    public function loadGroupsForumBlock($blockIndex = null)
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {
        	$topicUser = sC_getTableData(
        		"module_xprojects_groups_to_users",
        		"user_id, topic_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if (is_numeric($topicUser[0]['topic_id'])) {
        		// GET
        		$topic_id = $topicUser[0]['topic_id'];

        		// GET FORUM BY TOPIC ID

   	            //New forum messages block
	            if ((!isset($currentUser -> coreAccess['forum']) || $currentUser -> coreAccess['forum'] != 'hidden') && $GLOBALS['configuration']['disable_forum'] != 1) {
	                //changed  l.name as show_lessons_name to l.name as lessons_name
	    			$forum_messages =
	    				sC_getTableData("f_messages fm JOIN f_topics ft JOIN f_forums ff LEFT OUTER JOIN lessons l ON ff.lessons_ID = l.id", "fm.title, fm.id, ft.id as topic_id, fm.users_LOGIN, fm.timestamp, l.name as lessons_name, lessons_id as show_lessons_id", "ft.f_forums_ID=ff.id AND fm.f_topics_ID=ft.id AND ff.group_topic_id = '".$topic_id."'", "fm.timestamp desc");

	                $forum_topic_ID = sC_getTableData("f_forums", "id", "group_topic_id=".$topic_id);
	                $smarty -> assign("T_FORUM_MESSAGES", $forum_messages);

	                $smarty -> assign("T_FORUM_LESSONS_ID", $forum_topic_ID[0]['id']);

	                $forumOptions = array();
	                if ($forum_lessons_ID[0]['id']) {
	                    if (!isset($currentUser -> coreAccess['forum']) || $currentUser -> coreAccess['forum'] == 'change') {
	                        $forumOptions[] = array('text' => _SENDMESSAGEATFORUM, 'image' => "16x16/add.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=forum&add=1&type=topic&forum_id=".$forum_topic_ID[0]['id']."&popup=1", 'onclick' => "sC_js_showDivPopup('"._NEWMESSAGE."', 2)", 'target' => 'POPUP_FRAME');
	                    }
	                }
	                $forumOptions[] = (array('text' => _GOTOFORUM, 'image' => "16x16/go_into.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=forum"));

	                //$smarty -> assign("T_FORUM_OPTIONS", $forumOptions);
	                $forum_link = basename($_SERVER['PHP_SELF'])."?ctg=forum&forum=".$forum_topic_ID[0]['id'];
	                //$smarty -> assign("T_FORUM_LINK", $forum_link);

					$this->getParent()->appendTemplate(array(
			   			'title'			=> _RECENTMESSAGESATFORUM,
			   			'template'		=> $this->moduleBaseDir . 'templates/blocks/xprojects.groups.forum.tpl',
		    			'contentclass'	=> 'blockContents',
						'image'			=> '32x32/forum.png',
						'options'		=> $forumOptions,
						'link'			=> $forum_link

		    		), $blockIndex);

		    		return true;
	            }
	      	} else {
	      		 sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        		exit;
	      	}
        } elseif ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
        } else {
        	sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        	exit;
        }

        return false;
    }
    public function loadGroupsFileListBlock($blockIndex = null)
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {

        	$count = sC_countTableData(
        		"module_xprojects_groups_to_users",
        		"user_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if ($count[0]['count'] > 0) {
        		/*
        		$topicData = sC_getTableData(
        			"module_xprojects_groups_to_users grp_usr,
        			module_xprojects_groups grp,
        			module_xprojects_topics top,
        			module_xprojects prj,
        			users u",
        			"
        			top.id,
        			top.title,
        			top.project_id,
        			prj.title as project_name,
        			grp_usr.user_id,
        			u.name, u.surname, u.login,
        			grp.tag
        			",
        			sprintf("
        				grp_usr.topic_id = top.id
        				AND grp_usr.topic_id = grp.id
        				AND grp_usr.user_id = u.id
        				AND top.project_id = prj.id
        				AND user_id = %d", $currentUser->user['id']
        			)
        		);

        		$topicData[0]['tag'] = json_decode($topicData[0]['tag'], true);

        		var_dump($resource = ftp_connect("grupo1.grupos.magester.net"));
        		var_dump(ftp_login($resource, "grupo1@magester.net", "61xeuy7epn"));
        		*/

        		/*
        		$smarty -> assign("T_XPROJECTS_TOPIC", $topicData[0]);

        		*/
        		$this->assignSmartyModuleVariables();
        		$smarty -> assign("T_XPROJECTS_MOD_DATA", $this->getModuleData());

        		$this->injectJS("jquery/jquery.filetree");
        		$this->injectCSS("jquery/jquery.filetree");

		    	$this->getParent()->appendTemplate(array(
			   		'title'			=> __XPROJECT_FILE_LIST,
			   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xprojects.groups.file_list.tpl',
		    		'contentclass'	=> 'blockContents'
		    	), $blockIndex);
	      	} else {
				sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        		exit;
	      	}
        } elseif ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
        } else {
        	sC_redirect($_SESSION['s_type'] . '.php?ctg=control_panel');
        	exit;
        }

        return false;
    }
    /* ACTIONS FUNCTIONS */
	public function chooseGroupAction()
	{
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

		$currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student" || $currentUser -> getRole($this -> getCurrentLesson()) == "professor") {

        	$count = sC_countTableData(
        		"module_xprojects_groups_to_users",
        		"user_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if ($count[0]['count'] > 0) {

	        	$user_groups = sC_getTableData(
	        		"module_xprojects_groups_to_users grp2user, module_xprojects_topics top, module_xprojects_groups grp",
	        		"*",
	        		sprintf("grp.topic_id = top.id AND grp2user.topic_id = top.id AND grp2user.user_id = %d", $currentUser->user['id'])
	        	);
/*
				$this->appendTemplate(array(
			   			'title'			=> _RECENTMESSAGESATFORUM,
			   			'template'		=> $this->moduleBaseDir . 'templates/actions/choose_group.tpl',
		    			'contentclass'	=> 'blockContents',
	    		));
 * */

//				$centerLinkInfoUrl = $this->modules['xcms']->createBaseUrlByPageId($result[0]['page_id']);
        	}
        }
	}
    public function loadGroupFileListAction()
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {

        	$count = sC_countTableData(
        		"module_xprojects_groups_to_users",
        		"user_id",
        		sprintf("user_id = %d", $currentUser->user['id'])
        	);

        	if ($count[0]['count'] > 0) {
        		$topicData = sC_getTableData(
        			"module_xprojects_groups_to_users grp_usr,
        			module_xprojects_groups grp,
        			module_xprojects_topics top,
        			module_xprojects prj,
        			users u",
        			"
        			top.id,
        			top.title,
        			top.project_id,
        			prj.title as project_name,
        			grp_usr.user_id,
        			u.name, u.surname, u.login,
        			grp.tag
        			",
        			sprintf("
        				grp_usr.topic_id = top.id
        				AND grp_usr.topic_id = grp.id
        				AND grp_usr.user_id = u.id
        				AND top.project_id = prj.id
        				AND user_id = %d", $currentUser->user['id']
        			)
        		);

        		$topicData[0]['tag'] = json_decode($topicData[0]['tag'], true);

        		$host = $topicData[0]['tag'][0]['value'];
        		$user = $topicData[0]['tag'][1]['value'];
        		$pass = $topicData[0]['tag'][2]['value'];

        		// 1. CONNECT
        		$ftp = ftp_connect($host);
        		// 2. LOGIN
        		ftp_login($ftp, $user, $pass);

        		/*
        		ftp_pasv($ftp, true);

        		*/

        		$current_dir = $_POST['dir'];

        		/*
        		$ok = @ftp_chdir($ftp, $directory);
			    if (!$ok) {
			        return false;
			    }
			    */
        		// 3. PASSIVE MODE
			    $ret = ftp_raw($ftp, 'PASV');

			    if (preg_match(
			        '/^227.*\(([0-9]+,[0-9]+,[0-9]+,[0-9]+),([0-9]+),([0-9]+)\)$/',
			        $ret[0], $matches)) {
					// 4. LIST FILES
			        ftp_chdir($ftp, $current_dir);
			        $controlIP = str_replace(',', '.', $matches[1]);
			        $controlPort = intval($matches[2])*256+intval($matches[3]);
			        $socket = fsockopen($controlIP, $controlPort);
			        ftp_raw($ftp, 'MLSD');
			        $s = '';
			        while (!feof($socket)) {
			            $s .= fread($socket, 4096);
			        }
			        fclose($socket);
			        $files = array();
			        foreach (explode("\n", $s) as $line) {
			            if (!$line) {
			                continue;
			            }
			            $file = array();
			            foreach (explode(';', $line) as $property) {
			                list($key, $value) = explode('=', $property);
			                if ($value) {
			                    $file[$key] = $value;
			                } else {
			                    $filename = trim($key);
			                }
			            }
			            $files[$filename] = $file;
			        }

			        $result = '<ul class="jqueryFileTree" style="display: none;">';
			        if (count($files) < 2) {
			        } else {
						foreach ($files as $filename => $filedata) {
							if (in_array($filedata['type'], array('dir'))) {
								$result .= sprintf('<li class="directory collapsed"><a href="#" rel="%s">%s</a></li>',
									$current_dir . $filename . '/',
									$filename
								);
							}
						}

						foreach ($files as $filename => $filedata) {
							if (in_array($filedata['type'], array('file'))) {

								$pos = strrpos($filename, '.');
							    if ($pos === false) { // dot is not found in the filename
							    	$extension = '';
							    } elseif ($pos === 0) { // dot is the first file, no show
							    	continue;
							    } else {
							        $extension = substr($filename, $pos+1);
							    }

								$result .= sprintf('<li class="file ext_%s"><a href="#" rel="%s">%s</a></li>',
									$extension,
									$current_dir . $filename,
									$filename
								);
							}
						}
			        }

					$result .= '</ul>';

					echo $result;
			    }
        		exit;
        		return array();
	      	} else {
	      	}
        } elseif ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
        } else {
        }
        exit;

        return array();

    }

    /* HOOK ACTIONS FUNCTIONS */
    /* DATA MODEL FUNCTIONS /*/
    public function getProjectById($project_id)
    {
    }
    public function getCourseProjects($course_id = null)
    {
    	if (sC_checkParameter($course_id, 'id')) {
    		$editcourse = $this->getEditedCourse(null, $course_id);
    	} else {
    		$editcourse = $this->getEditedCourse();
    	}
    	if ($editedcourse) {
	    	$editcourse->course['id'];

	    	//var_dump($editcourse->getProjects());
    	}
    	return false;

    }

}
