<?php

class module_onsync extends MagesterExtendedModule
{
	private $accounts = array(
		0 => array(
			'name'			=> 'carlos_americas (Conta Teste)',
			'username'		=> 'carlos_americas',
			'password'		=> 'americas',
			'API_baseurl'	=> 'http://onsync.digitalsamba.com/api/1/',
			'link_baseurl'	=> 'http://onsync.digitalsamba.com/go/carlos_americas/'
		),
		1 => array(
			'name'			=> 'lab1@americas.com.br',
			'username'		=> 'lab1',
			'password'		=> 'cmnsl',
			'API_baseurl'	=> 'http://onsync.digitalsamba.com/api/1/',
			'link_baseurl'	=> 'http://onsync.digitalsamba.com/go/lab1/'
		),
		2 => array(
			'name'			=> 'lab2@americas.com.br',
			'username'		=> 'lab2',
			'password'		=> 'cmnsl',
			'API_baseurl'	=> 'http://onsync.digitalsamba.com/api/1/',
			'link_baseurl'	=> 'http://onsync.digitalsamba.com/go/lab2/'
		),
		3 => array(
			'name'			=> 'lab3@americas.com.br',
			'username'		=> 'lab3',
			'password'		=> 'cmnsl',
			'API_baseurl'	=> 'http://onsync.digitalsamba.com/api/1/',
			'link_baseurl'	=> 'http://onsync.digitalsamba.com/go/lab3/'
		)
	);
    // Mandatory functions required for module function
    public function getName()
    {
        return "ONSYNC";
    }

    public function getPermittedRoles()
    {
        return array("administrator","professor","student");
    }

    public function isLessonModule()
    {
        return true;
    }

    // Optional functions
    // What should happen on installing the module
    public function onInstall()
    {
        eF_executeNew("drop table if exists module_onsync");
        $a = eF_executeNew("CREATE TABLE module_onsync (
        					internal_ID int(11) NOT NULL auto_increment,
							account_ID int(11) NOT NULL,
							onsync_ID int(11),
							topic varchar(255) NOT NULL,
							duration int(11) NOT NULL,
							start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
							timezone DEFAULT 'UM3'
							friendly_url DEFAULT NULL,
							password DEFAULT NULL,
                          	PRIMARY KEY  (internal_ID)
		) DEFAULT CHARSET=utf8;");
        eF_executeNew("drop table if exists module_onsync_users_to_meeting ");
        $b = eF_executeNew("CREATE TABLE module_onsync_users_to_meeting (
                        users_LOGIN varchar(255) NOT NULL,
                        meeting_ID int(11) NOT NULL,
                        KEY (users_LOGIN,meeting_ID)
                       ) DEFAULT CHARSET=utf8;");

        if (!($c = eF_executeNew("INSERT INTO configuration VALUES ('module_onsync_server','http://');"))) {
            $c = eF_executeNew("UPDATE configuration SET value = 'http://' WHERE name = 'module_onsync_server';");
        }

        return $a && $b && $c;
    }

    // And on deleting the module
    public function onUninstall()
    {
        $a = eF_executeNew("DROP TABLE module_onsync;");
        $b = eF_executeNew("DROP TABLE module_onsync_users_to_meeting;");
        $c = eF_executeNew("DELETE FROM configuration WHERE name='module_onsync_server';");

        return $a && $b && $c;
    }

    // On exporting a lesson
    public function onDeleteLesson($lessonId)
    {
        $meetings_to_del = eF_getTableDataFlat("module_onsync", "*","lessons_ID='".$lessonId."'");

        $this->deleteOnSyncConference($meetings_to_del['onsync_ID'], $meetings_to_del);

        eF_deleteTableData("module_onsync", "lessons_ID='".$lessonId."'");
        $delmeet = implode($meetings_to_del['internal_ID'],"','");
        eF_deleteTableData("module_onsync_users_to_meeting", "meeting_ID IN ('".$delmeet ."')");

        return true;
    }
/*
    // On exporting a lesson
    public function onExportLesson($lessonId)
    {
        $data = array();
        $data['meetings'] = eF_getTableData("module_onsync", "*","lessons_ID=".$lessonId);
        $data['users_to_meetings'] = eF_getTableData("module_onsync_users_to_meeting JOIN module_onsync ON module_onsync.internal_ID = module_onsync_users_to_meeting.meeting_ID", "module_onsync_users_to_meeting.*","lessons_ID=".$lessonId);

        return $data;
    }

    // On importing a lesson
    public function onImportLesson($lessonId, $data)
    {
        $changed_ids = array();

        foreach ($data['meetings'] as $meeting_record) {

            // Keep the old id
            $old_meeting_id = $meeting_record['id'];
            unset($meeting_record['id']);
            $meeting_record['lessons_ID'] = $lessonId;
            $new_meeting_id = eF_insertTableData("module_onsync",$meeting_record);

            if ($new_meeting_id != $old_meeting_id) {
                $changed_ids[$old_meeting_id] = $new_meeting_id;
            }
        }

        foreach ($data['users_to_meetings'] as $users_to_meetings_record) {

            if (isset($changed_ids[$users_to_meetings_record['meeting_ID']])) {
                $users_to_meetings_record['meeting_ID'] = $changed_ids[$users_to_meetings_record['meeting_ID']];
            }
            eF_insertTableData("module_onsync_users_to_meeting",$users_to_meetings_record);
        }

        return true;
    }
*/
    public function getCourseDashboardLinkInfo()
    {
		return array(
			'title' => _ONSYNC,
        	'image' => $this -> moduleBaseLink . 'images/onsync32.png',
            'link'  => $this -> moduleBaseUrl
		);
    }
	public function getLessonCenterLinkInfo()
	{
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
            return array('title' => _ONSYNC,
                         'image' => $this -> moduleBaseDir . 'images/onsync32.png',
                         'link'  => $this -> moduleBaseUrl);
        }
    }
    public function getCenterLinkInfo()
    {
        $currentUser = $this -> getCurrentUser();

        $xuserModule = $this->loadModule("xuser");
		if (
        	$xuserModule->getExtendedTypeID($currentUser) == "administrator"
        ) {
            return array('title' => _ONSYNC,
                         'image' => $this -> moduleBaseDir . 'images/onsync32.png',
                         'link'  => $this -> moduleBaseUrl);
        } else {
        	return array('title' => _ONSYNC,
                         'image' => $this -> moduleBaseDir . 'images/onsync32.png',
                         'link'  => $this -> moduleBaseUrl);
        }
    }

    public function getNavigationLinks()
    {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole() == "administrator") {
            $basicNavArray = array (array ('title' => _HOME, 'link' => "administrator.php?ctg=control_panel"),
                                    array ('title' => _ONSYNC, 'link'  => $this -> moduleBaseUrl));

        } else {
			$currentLesson = $this -> getCurrentLesson();
            $basicNavArray = array (
                                    array ('title' => _MYLESSONS, 'onclick'  => "location='".$currentUser -> getRole($this -> getCurrentLesson()).".php?ctg=lessons';top.sideframe.hideAllLessonSpecific();"),
                                    array ('title' => $currentLesson -> lesson['name'], 'link' => $currentUser -> getRole($this -> getCurrentLesson()) . ".php?ctg=control_panel"),
                                    array ('title' => _ONSYNC, 'link'  => $this -> moduleBaseUrl));
	        if (isset($_GET['edit_onsync'])) {
	            $basicNavArray[] = array ('title' => _ONSYNC_MANAGEMENT, 'link'  => $this -> moduleBaseUrl . "&edit_onsync=". $_GET['edit_onsync']);
	        } elseif (isset($_GET['add_onsync'])) {
	            $basicNavArray[] = array ('title' => _ONSYNC_MANAGEMENT, 'link'  => $this -> moduleBaseUrl . "&add_onsync=1");
	        }
        }

        return $basicNavArray;

    }
/*
    public function getSidebarLinkInfo()
    {
		$currentUser = $this -> getCurrentUser();

        $xuserModule = $this->loadModule("xuser");
		if (
        	$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
        	$xuserModule->getExtendedTypeID($currentUser) == "professor" ||
        	$xuserModule->getExtendedTypeID($currentUser) == "student"
        ) {
	        $link_of_menu_clesson = array (array ('id' => 'onsync_link_id1',
	                                              'title' => _ONSYNC,
	                                              'image' => $this -> moduleBaseDir . 'images/onsync16.png',
	                                              '_magesterExtensions' => '1',
	                                              'link'  => $this -> moduleBaseUrl));

	        return array ( "current_lesson" => $link_of_menu_clesson, "communication" => $link_of_menu_clesson);
        }

    }
*/
    public function getLinkToHighlight()
    {
        return 'onsync_link_id1';
    }

    private $onsync_server_host = false;

    private function getOnsyncServer()
    {
        if (!$this -> onsync_server_host) {
            $onsync_server = eF_getTableData("configuration", "value", "name = 'module_onsync_server'");
            $this -> onsync_server_host = $onsync_server[0]['value'];
        }

        return $this -> onsync_server_host;
    }

    /*
     * Function used to create the OnSync module URL
     * Parses the options stored for the meeting in the DB and retuns the correct
     * URL according to role of the user, whether the meeting has started or
     * wheter
     */
    /*
    private function createOnsyncUrl($currentUser, $meeting_info, $always_joining = false)
    {
        $onsync_server = $this -> getOnsyncServer();

        $server_host = $onsync_server;

    	//$onsyncUrl .= $server_host; // just the room name :)
    	$room_name= str_replace(" ","",$meeting_info['name']);
    	//$onsyncUrl .= $server_host .$room_name; // room name + lessonname

    	if (strpos($meeting_info['name'], "{%room_name}") == FALSE) {
    		$onsyncUrl = $server_host . '/' . $meeting_info['name'];
    	} else {
    	   	$onsyncUrl = str_replace("{%room_name}", $meeting_info['name'], $server_host);
    	}

        return $onsyncUrl;
    }
    */

    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule()
    {
        $currentUser = $this -> getCurrentUser();
        // Get smarty global variable
        $smarty = $this -> getSmartyVar();

        $userRole = $currentUser -> getRole($this -> getCurrentLesson());

        if ($currentUser -> getType() == "administrator") {

            $form = new HTML_QuickForm("onsync_server_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
            $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');                   //Register this rule for checking user input with our function, eF_checkParameter
            $form -> addElement('text', 'server', null, 'class = "inputText" id="server_input"');
            $form -> addRule('server', _ONSYNCTHEFIELDNAMEISMANDATORY, 'required', null, 'client');
            $form -> addElement('submit', 'submit_onsync_server', _SUBMIT, 'class = "flatButton"');

            if ($form -> isSubmitted() && $form -> validate()) {
                $server_name = $form -> exportValue('server');
                if ($server_name[strlen($server_name)-1] == "/") {
                    $server_name = substr($server_name, 0, strlen($server_name)-1);
                }
                eF_updateTableData("configuration", array("value" => $server_name), "name = 'module_onsync_server'");
                $this -> setMessageVar(_ONSYNC_SUCCESFULLYCHANGEDSERVER, "success");
            }

            $form -> setDefaults(array('server'       => $this -> getOnsyncServer()));

            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $form -> accept($renderer);

            $smarty -> assign('T_ONSYNC_FORM', $renderer -> toArray());
        }

        /*** Ajax Methods - Add/remove skills/jobs***/
        if (isset($_GET['postAjaxRequest'])) {
            /** Post skill - Ajax skill **/
            if ($_GET['insert'] == "true") {
                eF_insertTableData("module_onsync_users_to_meeting", array('users_LOGIN' => $_GET['user'], 'meeting_ID' => $_GET['edit_onsync']));
            } elseif ($_GET['insert'] == "false") {
                eF_deleteTableData("module_onsync_users_to_meeting", "users_LOGIN = '". $_GET['user'] . "' AND meeting_ID = '".$_GET['edit_onsync']."'");
            } elseif (isset($_GET['addAll'])) {

/*
                $users = eF_getTableData(
                	"users JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN LEFT OUTER JOIN module_onsync_users_to_meeting ON users.login = module_onsync_users_to_meeting.users_LOGIN",
                	"users.login, users.name, users.surname, meeting_ID",
                	"users_to_lessons.archive=0 and users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID']."' AND (meeting_ID <> '".$_GET['edit_onsync']."' OR meeting_ID IS NULL)"
                );
*/
/*
                $users_attending = eF_getTableDataFlat(
                	"users JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN LEFT OUTER JOIN module_onsync_users_to_meeting ON users.login = module_onsync_users_to_meeting.users_LOGIN",
                	"users.login",
                	"users_to_lessons.archive=0 and users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID']."' AND meeting_ID = '".$_GET['edit_onsync']."'");
*/
				$users = eF_getTableData(
					"users
						JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN
                		JOIN module_onsync ON module_onsync.lessons_ID = users_to_lessons.lessons_ID
						LEFT OUTER JOIN module_onsync_users_to_meeting ON module_onsync.internal_ID = module_onsync_users_to_meeting.meeting_ID AND users.login = module_onsync_users_to_meeting.users_LOGIN",

					"users.login, users.name, users.surname, meeting_ID",

					" users_to_lessons.archive = 0 " .
					" AND users.active = 1 " .
					" AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID'] . "'" .
					" AND users.login <> '".$currentUser -> user['login'] . "'" .
					" AND (module_onsync.classes_ID = -1 OR users.login IN (SELECT users_LOGIN FROM users_to_courses WHERE classes_id = module_onsync.classes_ID)) " .
					" AND module_onsync.internal_ID = '".$_GET['edit_onsync']."'"
				);

				$users_attending = eF_getTableDataFlat(
					"users
						JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN
                		JOIN module_onsync ON module_onsync.lessons_ID = users_to_lessons.lessons_ID
						LEFT OUTER JOIN module_onsync_users_to_meeting ON module_onsync.internal_ID = module_onsync_users_to_meeting.meeting_ID AND users.login = module_onsync_users_to_meeting.users_LOGIN",

					"DISTINCT users.login",

					" users_to_lessons.archive = 0 " .
					" AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID'] . "'" .
					" AND users.login <> '".$currentUser -> user['login'] . "'" .
					" AND (module_onsync.classes_ID = -1 OR users.login IN (SELECT users_LOGIN FROM users_to_courses WHERE classes_id = module_onsync.classes_ID)) " .
					" AND module_onsync_users_to_meeting.meeting_ID = '".$_GET['edit_onsync']."'"
				);

                isset($_GET['filter']) ? $users = eF_filterData($users, $_GET['filter']) : null;
                //$users_attending = $users_attending['login'];

                foreach ($users as $user) {
                    if (!in_array($user['login'], $users_attending)) {
                        eF_insertTableData("module_onsync_users_to_meeting", array('users_LOGIN' => $user['login'], 'meeting_ID' => $_GET['edit_onsync']));
                        $users_attending[] = $user['login'];
                    }
                }
                // MAKE JSON MESSAGE

                exit;
            } elseif (isset($_GET['removeAll'])) {
            	/*
                $users_attending = eF_getTableData(
                	"users JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN LEFT OUTER JOIN module_onsync_users_to_meeting ON users.login = module_onsync_users_to_meeting.users_LOGIN",
                	"users.login",
                	"users_to_lessons.archive=0
                	AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID']."'
                	AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID']."'
                	AND meeting_ID = '".$_GET['edit_onsync']."'"
                );
                */

				$users_attending = eF_getTableData(
					"users
						JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN
                		JOIN module_onsync ON module_onsync.lessons_ID = users_to_lessons.lessons_ID
						LEFT OUTER JOIN module_onsync_users_to_meeting ON module_onsync.internal_ID = module_onsync_users_to_meeting.meeting_ID AND users.login = module_onsync_users_to_meeting.users_LOGIN",

					"users.login",

					" users_to_lessons.archive = 0 " .
					" AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID'] . "'" .
					" AND users.login <> '".$currentUser -> user['login'] . "'" .
					" AND (module_onsync.classes_ID = -1 OR users.id IN (SELECT users_LOGIN FROM users_to_courses WHERE classe_id = module_onsync.classes_ID)) " .
					" AND module_onsync.internal_ID = '".$_GET['edit_onsync']."'"
				);

                //$users_attending = $users_attending['login'];
                isset($_GET['filter']) ? $users_attending = eF_filterData($users_attending, $_GET['filter']) : null;

                $users_to_delete = array();
                foreach ($users_attending as $user) {
                    $users_to_delete[] = $user['login'];
                }
                $users_to_delete = array_unique($users_to_delete);
                eF_deleteTableData("module_onsync_users_to_meeting", "meeting_ID = '".$_GET['edit_onsync']."' AND users_LOGIN IN ('".implode("','", $users_to_delete)."')");

                // MAKE JSON MESSAGE

                exit;
            } elseif (isset($_GET['mail_users']) && $_GET['mail_users'] == 1) {
                $currentLesson = $this ->getCurrentLesson();
                $meeting_users = eF_getTableData("module_onsync_users_to_meeting JOIN users ON module_onsync_users_to_meeting.users_LOGIN = users.login", "users.login, users.name, users.surname, users.email", "meeting_ID = ".$_GET['edit_onsync'] . " AND users.active = 1 AND users.login <> '". $currentUser -> user['login'] ."'");

                isset($_GET['filter']) ? $meeting_users  = eF_filterData($meeting_users , $_GET['filter']) : null;

                $meeting_info = eF_getTableData("module_onsync", "*", "internal_ID = ".$_GET['edit_onsync']);

                $meeting_info[0]['timestamp']	= strtotime($meeting_info[0]['start_time']);

                $subject = _ONSYNC_MEETING;
                $count = 0;
                foreach ($meeting_users as $user) {

                    //$body = _ONSYNC_DEAR . " " . $user['name']. ",\n\n" ._ONSYNC_YOUHAVEBEENINVITEDBYPROFESSOR . " " . $currentUser -> user['name']. " " . $currentUser -> user['surname'] . " " . _ONSYNC_TOATTENDACONFERENCE . " \"". $meeting_info[0]['name'] . "\" " . _ONSYNC_FORLESSON. " \""  . $currentLesson -> lesson['name'] . "\" " . _ONSYNC_SCHEDULEDFOR . "\n\n". date("D d.m.y, g:i a", $meeting_info[0]['timestamp']). "\n\n" ._ONSYNCYOUCANJOINTHEMEETINGDIRECTLYBYCLICKINGTHEFOLLOWINGLINKAFTERITSTARTS . ":\n\n";

					$userObject = MagesterUserFactory::factory($user['login']);

                    $body = "Olá " . $user['name']. ",\n\n";
                    $body .= sprintf("Você foi convidado para participar da transmissão da Aula de %s agendada para o dia de %s.\n\n", $currentLesson -> lesson['name'], date("d/m/Y \à\s H:i", $meeting_info[0]['timestamp']));
                    $body .= "Após clicar sobre o link abaixo, é necessário selecionar a opção GUEST, e digitar seu nome.\n\n";
//                    $body .= $this -> createOnsyncUrl($userObject, $meeting_info[0], true) . "\n";
//			var_dump($meeting_info);
					$onsyncUrl = $this->accounts[$meeting_info[0]['account_ID']]['link_baseurl'] . $meeting_info[0]['friendly_url'];
					$body .= $onsyncUrl . "\n\n";

                    $body .= "Obrigado,\n";
					$body .= "Suporte ULT";

                    //$body .= $this -> createOnsyncUrl($userObject, $meeting_info[0], true);
                    //$body .= "\n\n" ._ONSYNC_SINCERELY . ",\n" . $currentUser -> user['surname']." ".$currentUser -> user['name'];

                    $my_email = $currentUser -> user['email'];
                    $user_mail = $user['email'];
                    $header = array ('From'                      => $GLOBALS['configuration']['system_email'],
                                     'To'                        => $user_mail,
                                     'Subject'                   => $subject,
                                     'Content-type'              => 'text/plain;charset="UTF-8"',                       // if content-type is text/html, the message cannot be received by mail clients for Registration content
                                     'Content-Transfer-Encoding' => '7bit');
                    $smtp = Mail::factory('smtp', array('auth'      => $GLOBALS['configuration']['smtp_auth'] ? true : false,
                                                         'host'      => $GLOBALS['configuration']['smtp_host'],
                                                         'password'  => $GLOBALS['configuration']['smtp_pass'],
                                                         'port'      => $GLOBALS['configuration']['smtp_port'],
                                                         'username'  => $GLOBALS['configuration']['smtp_user'],
                                                         'timeout'   => $GLOBALS['configuration']['smtp_timeout']));

                    if ($smtp -> send($user_mail, $header, $body)) {
                        $count++;
                    }

                }
                echo $count;
                exit;
            }
        }
		/*
        if (isset($_GET['start_meeting']) && eF_checkParameter($_GET['start_meeting'], 'id')) {

			$onsync_server = $this -> getOnsyncServer();
            if ($onsync_server != "") {

                $onsync = eF_getTableData("module_onsync", "*", "internal_ID=".$_GET['start_meeting']);
				$onsync[0]['timestamp'] = strtotime($onsync[0]['start_time']);
                if ($onsync[0]['timestamp'] >= time()) {

                    if ($currentUser -> getRole($this -> getCurrentLesson()) == "professor" && $meeting_info['status'] == 0) {
                        //eF_updateTableData("module_onsync", array('status' => '1'), "internal_ID=".$_GET['start_meeting']);
                    }
                    $onsyncUrl = $this->accounts[$meeting_info['account_ID']]['link_baseurl'] . $meeting_info['friendly_url'];

                    header("location:".$onsyncUrl);
                } else {
                    $this -> setMessageVar(_ONSYNCMEETINGHASFINISHED, "failure");
                }
            } else {
                $this -> setMessageVar(_ONSYNC_NOONSYNCSERVERDEFINED, "failure");
            }
        }
		*/
		/*
        if (isset($_GET['finished_meeting']) && eF_checkParameter($_GET['finished_meeting'], 'id')) {
            if ($userRole == "professor") {
                eF_updateTableData("module_onsync", array('status' => '2'), "internal_ID=".$_GET['finished_meeting']);
            }

            $currentLesson = $this -> getCurrentLesson();
            $_SESSION['previousSideUrl'] = G_SERVERNAME ."new_sidebar.php?new_lesson_id=" . $currentLesson -> lesson['id'] ;
            $_SESSION['previousMainUrl'] = G_SERVERNAME . $currentUser -> getType() . ".php?ctg=control_panel";
            header("location:". $currentUser -> getType() . "page.php");
        }
		*/
        if (isset($_GET['delete_onsync']) && eF_checkParameter($_GET['delete_onsync'], 'id') && $userRole == "professor") {
			$onsync_entry = eF_getTableData("module_onsync", "*", "internal_ID=".$_GET['delete_onsync']);

            eF_deleteTableData("module_onsync", "internal_ID=".$_GET['delete_onsync']);
            eF_deleteTableData("module_onsync_users_to_meeting", "meeting_ID=".$_GET['delete_onsync']);

            $this->deleteOnSyncConference($onsync_entry[0]['onsync_ID'], $onsync_entry[0]);

            header("location:". $this -> moduleBaseUrl ."&message=".urlencode(_ONSYNC_SUCCESFULLYDELETEDONSYNCENTRY)."&message_type=success");
        } elseif ($userRole == "professor" && (isset($_GET['add_onsync']) || (isset($_GET['edit_onsync']) && eF_checkParameter($_GET['edit_onsync'], 'id')))) {

            // Create ajax enabled table for meeting attendants
            if (isset($_GET['edit_onsync'])) {
                if (isset($_GET['ajax']) && $_GET['ajax'] == 'onsyncUsersTable') {
                    isset($_GET['limit']) && eF_checkParameter($_GET['limit'], 'uint') ? $limit = $_GET['limit'] : $limit = G_DEFAULT_TABLE_SIZE;

                    if (isset($_GET['sort']) && eF_checkParameter($_GET['sort'], 'text')) {
                        $sort = $_GET['sort'];
                        isset($_GET['order']) && $_GET['order'] == 'desc' ? $order = 'desc' : $order = 'asc';
                    } else {
                        $sort = 'login';
                    }

                    $users = eF_getTableData("users JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN
                                                    JOIN module_onsync ON module_onsync.lessons_ID = users_to_lessons.lessons_ID
                                                    LEFT OUTER JOIN module_onsync_users_to_meeting ON module_onsync.internal_ID = module_onsync_users_to_meeting.meeting_ID AND users.login = module_onsync_users_to_meeting.users_LOGIN",

                                                    "users.login, users.name, users.surname, users.email, meeting_ID",

                                                    " users_to_lessons.archive = 0 " .
                                                    " AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID'] . "'" .
                                                    " AND users.login <> '".$currentUser -> user['login'] . "'" .
                    								" AND (module_onsync.classes_ID = -1 OR users.login IN (SELECT users_LOGIN FROM users_to_courses WHERE classe_id = module_onsync.classes_ID)) " .
                                                    " AND module_onsync.internal_ID = '".$_GET['edit_onsync']."'"
					);

                    $users = eF_multiSort($users, $_GET['sort'], $order);
                    if (isset($_GET['filter'])) {
                        $users = eF_filterData($users , $_GET['filter']);
                    }

                    $smarty -> assign("T_USERS_SIZE", sizeof($users));

                    if (isset($_GET['limit']) && eF_checkParameter($_GET['limit'], 'int')) {
                        isset($_GET['offset']) && eF_checkParameter($_GET['offset'], 'int') ? $offset = $_GET['offset'] : $offset = 0;
                        $users = array_slice($users, $offset, $limit);
                    }

                    $smarty -> assign("T_USERS", $users);
                    $smarty -> display($this -> getSmartyTpl());
                    exit;

                } else {
                    $users = eF_getTableData("users JOIN users_to_lessons ON users.login = users_to_lessons.users_LOGIN
                                                    JOIN module_onsync ON module_onsync.lessons_ID = users_to_lessons.lessons_ID
                                                    LEFT OUTER JOIN module_onsync_users_to_meeting ON module_onsync.internal_ID = module_onsync_users_to_meeting.meeting_ID AND users.login = module_onsync_users_to_meeting.users_LOGIN",
                                                    "users.login, users.name, users.surname, meeting_ID",
                                                    " users_to_lessons.archive = 0 " .
                                                    " AND users_to_lessons.lessons_ID = '".$_SESSION['s_lessons_ID'] . "'" .
                                                    " AND users.login <> '".$currentUser -> user['login'] . "'" .
                    								" AND (module_onsync.classes_ID = -1 OR users.login IN (SELECT users_LOGIN FROM users_to_courses WHERE classe_id = module_onsync.classes_ID)) " .
                                                    " AND module_onsync.internal_ID = '".$_GET['edit_onsync']."'"
					);

                    $smarty -> assign("T_USERS", $users);
                }
            }

            $form = new HTML_QuickForm("onsync_entry_form", "post", $_SERVER['REQUEST_URI']. "&tab=users", "", null, true);
			$form -> addElement('hidden', 'onsync_ID');

            $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');                   //Register this rule for checking user input with our function, eF_checkParameter

            $accounts = array();

            foreach ($this->accounts as $key => $item) {
            	$accounts[$key]	= $item['name'];
            }

            $form -> addElement('select', 'account_ID', _ONSYNC_ACCOUNT, $accounts, 'class = "inputText"');

			$currentLesson = $this -> getCurrentLesson();
			$courses = $currentLesson->getCourses(true);

			$appendCourseName = count($courses) > 1;

			$classesItens = array(
				'-1'	=> _ONSYNC_ALL_CLASSES
			);

			foreach ($courses as $index => $courseObject) {
				$courseClasses = $courseObject->getCourseClasses(array('return_objects' => false));

				foreach ($courseClasses as $key => $item) {
            		$classesItens[$item['id']]	= ($appendCourseName ? $courseObject->course['name'] : '' ) . $item['name'];
            	}
			}

            $form -> addElement('select', 'classes_ID', _CLASSE, $classesItens, 'class = "inputText"');

            $form -> addElement('text', 'topic', _ONSYNC_TOPIC, 'class = "inputText"');
            $form -> addRule('topic', _ONSYNCTHEFIELDNAMEISMANDATORY, 'required', null, 'client');

            $durations = array(
	            15	=> "15 min",
	            30	=> "30 min",
	            45	=> "45 min",
	            60	=> "60 min",
	            75	=> "75 min",
	            90	=> "90 min",
	            105	=> "105 min",
	            120	=> "120 min",
	            135	=> "135 min",
	            150	=> "150 min",
	            165	=> "165 min",
	            180	=> "180 min",
	            195	=> "195 min",
	            210	=> "210 min",
	            225	=> "225 min",
	            240	=> "240 min"
			);
			$form -> addElement('select', 'duration', _ONSYNC_DURATION, $durations, 'class = "inputText"');

            // Dates
            $days = array();
            for ($i = 1; $i < 32; $i++) {
                $days[$i] = $i;
            }

            $months = array();
            for ($i = 1; $i <= 12; $i++) {
                $months[$i] = $i;
            }

            $years = array();
            for ($i = 2008; $i < 2015; $i++) {
                $years[$i] = $i;
            }

            $hours = array();
            for ($i = 0; $i <= 9; $i++) {
                $hours[$i] = "0".$i;
            }
            for ($i = 10; $i <= 23; $i++) {
                $hours[$i] = $i;
            }

            $minutes = array();
            $minutes[0] = "00";
            for ($i = 15; $i < 60; $i+=15) {
                $minutes[$i] = $i;
            }

            $duration_hours = array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5);

            $form -> addElement('select', 'day' , null, $days ,'id="day"');
            $form -> addElement('select', 'month' , null, $months,'id="month"');
            $form -> addElement('select', 'year' , null, $years,'id="year"');

            $form -> addElement('select', 'hour' , null, $hours,'id="hour"');
            $form -> addElement('select', 'minute' , null, $minutes,'id="minute"');

            $timezones = array(
				"UM12"		=> "(UTC -12:00)",
				"UM11"		=> "(UTC -11:00)",
				"UM10"		=> "(UTC -10:00)",
				"UM95"		=> "(UTC -9:30)",
				"UM9"		=> "(UTC -9:00)",
				"UM8"		=> "(UTC -8:00)",
				"UM7"		=> "(UTC -7:00)",
				"UM6"		=> "(UTC -6:00)",
				"UM5"		=> "(UTC -5:00)",
				"UM45"		=> "(UTC -4:30)",
				"UM4"		=> "(UTC -4:00)",
				"UM35"		=> "(UTC -3:30)",
				"UM3"		=> "(UTC -3:00)",
				"UM2"		=> "(UTC -2:00)",
				"UM1"		=> "(UTC -1:00)",
				"UTC"		=> "(UTC)",
				"UP1"		=> "(UTC +1:00)",
				"UP2"		=> "(UTC +2:00)",
				"UP3"		=> "(UTC +3:00)",
				"UP35"		=> "(UTC +3:30)",
				"UP4"		=> "(UTC +4:00)",
				"UP45"		=> "(UTC +4:30)",
				"UP5"		=> "(UTC +5:00)",
				"UP55"		=> "(UTC +5:30)",
				"UP575"		=> "(UTC +5:45)",
				"UP6"		=> "(UTC +6:00)",
				"UP65"		=> "(UTC +6:30)",
				"UP7"		=> "(UTC +7:00)",
				"UP8"		=> "(UTC +8:00)",
				"UP875"		=> "(UTC +8:45)",
				"UP9"		=> "(UTC +9:00)",
				"UP95"		=> "(UTC +9:30)",
				"UP10"		=> "(UTC +10:00)",
				"UP105"		=> "(UTC +10:30)",
				"UP11"		=> "(UTC +11:00)",
				"UP115"		=> "(UTC +11:30)",
				"UP12"		=> "(UTC +12:00)",
				"UP1275"	=> "(UTC +12:45)",
				"UP13"		=> "(UTC +13:00)",
				"UP14"		=> "(UTC +14:00)"
            );

            $form -> addElement('select', 'timezone' , _ONSYNC_TIMEZONE, $timezones ,'class = "inputText" id="timezone"');
            $form -> addElement('text', 'friendly_url', _ONSYNC_FRIEND_URL, 'class = "inputText"');
            $form -> addElement('password', 'password', _ONSYNC_PASSWORD, 'class = "inputText"');

            $currentLesson = $this -> getCurrentLesson();
            $students = eF_getTableData("users_to_lessons", "count(users_LOGIN) as total_students", "archive=0 and lessons_ID = '".$currentLesson -> lesson['id']."'");

            $form -> addElement('submit', 'submit_onsync', _SUBMIT, 'class = "flatButton"');

            if (isset($_GET['edit_onsync'])) {
            	$form->getElement('account_ID')->freeze();
            	$form->getElement('classes_ID')->freeze();

                $onsync_entry = eF_getTableData("module_onsync", "*", "internal_ID=".$_GET['edit_onsync']);
                $timestamp_info = getdate(strtotime($onsync_entry[0]['start_time']));

				$defaults = array(
	            	'onsync_ID'		=> $onsync_entry[0]['onsync_ID'],
	                'account_ID'	=> $onsync_entry[0]['account_ID'],
	                'lessons_ID'    => $onsync_entry[0]['lessons_ID'],
					'classes_ID'    => $onsync_entry[0]['classes_ID'],
	                'topic'         => $onsync_entry[0]['topic'],
	                'duration'		=> $onsync_entry[0]['duration'],
					'day'       	=> $timestamp_info['mday'],
					'month'    	 	=> $timestamp_info['mon'],
					'year'      	=> $timestamp_info['year'],
					'hour'      	=> $timestamp_info['hours'],
					'minute'    	=> $timestamp_info['minutes'],
					'timezone'		=> $onsync_entry[0]['timezone'],
					'friendly_url'	=> $onsync_entry[0]['friendly_url'],
					'password'		=> $onsync_entry[0]['password']
				);
            } else {
                $timestamp_info = getdate(time());
                $timestamp_info['minutes'] = $timestamp_info['minutes'] - ($timestamp_info['minutes'] % 15);

                $defaults = array(
	            	'onsync_ID'		=> null,
	                'account_ID'	=> 0,
	                'lessons_ID'    => $currentLesson -> lesson['id'],
                	'classes_ID'	=> -1,
	                'topic'         => '',
	                'duration'		=> 60,
					'day'       	=> $timestamp_info['mday'],
					'month'     	=> $timestamp_info['mon'],
					'year'      	=> $timestamp_info['year'],
					'hour'      	=> $timestamp_info['hours'],
					'minute'    	=> $timestamp_info['minutes'],
					'timezone'		=> 'UM3',
					'friendly_url'	=> '',
					'password'		=> ''
				);
            }
            $form -> setDefaults( $defaults );

            if ($form -> isSubmitted() && $form -> validate()) {

               // if (eF_checkParameter($form -> exportValue('name'), 'text')) {
                    $smarty = $this -> getSmartyVar();
                    $currentLesson = $this -> getCurrentLesson();
/*
	array(12) {
		["account_ID"]=> string(1) "0"
		["topic"]=> string(30) "Segurança da Informadsaldjkas"
		["duration"]=> string(2) "15"
		["day"]=> string(2) "15"
		["month"]=> string(1) "3"
		["year"]=> string(4) "2011"
		["hour"]=> string(2) "23"
		["minute"]=> string(2) "45"
		["timezone"]=> string(4) "UM12"
		["friend_url"]=> string(10) "lab1/teste"
		["password"]=> string(0) ""
		["submit_onsync"]=> string(6) "Salvar"
	}
*/

                    $fields = array(
                    	'onsync_ID'		=> $form -> exportValue('onsync_ID'),
                    	'account_ID'	=> $form -> exportValue('account_ID'),
                        'lessons_ID'    => $currentLesson -> lesson['id'],
                    	'classes_ID'	=> $form -> exportValue('classes_ID'),
                    	'topic'         => $form -> exportValue('topic'),
                    	'duration'		=> $form -> exportValue('duration'),
                    	'start_time'	=>
                    		sprintf('%04d', $form -> exportValue('year')) . '-' .
                    		sprintf('%02d', $form -> exportValue('month')) . '-' .
                    		sprintf('%02d', $form -> exportValue('day')) . ' ' .
                    		sprintf('%02d', $form -> exportValue('hour')) . ':' .
                    		sprintf('%02d', $form -> exportValue('minute')) . ':' .
                    		'00',
                        'timezone'		=> $form -> exportValue('timezone'),
				        'friendly_url'	=> $form -> exportValue('friendly_url'),
				        'password'		=> $form -> exportValue('password'),
					);

                    if (isset($_GET['edit_onsync'])) {
                    	// UPDATE ONSYNC CONFERENCE
                        if ($this->updateOnSyncConference($fields['onsync_ID'], $fields) && eF_updateTableData("module_onsync", $fields, "internal_ID=".$_GET['edit_onsync'])) {
                            header("location:".$this -> moduleBaseUrl."&message=".urlencode(_ONSYNC_SUCCESFULLYUPDATEDONSYNCENTRY)."&message_type=success");
                        } else {
                            header("location:".$this -> moduleBaseUrl."&message=".urlencode(_ONSYNC_PROBLEMUPDATINGONSYNCENTRY)."&message_type=failure");
                        }
                    } else {
                        // The key will be the current time when the event was set concatenated with the initial timestamp for the meeting
                        // If the latter changes after an event editing the key will not be changed
                        // INSERT ONSYNC CONFERENCE
                        $fields['onsync_ID']	= $this->insertOnSyncConference($fields);

                        if ($fields['onsync_ID'] !== FALSE && $result = eF_insertTableData("module_onsync", $fields)) {
                            header("location:".$this -> moduleBaseUrl."&edit_onsync=".$result."&message=".urlencode(_ONSYNC_SUCCESFULLYINSERTEDONSYNCENTRY)."&message_type=success&tab=users");
                        } else {
                            header("location:".$this -> moduleBaseUrl."&message=".urlencode(_ONSYNC_PROBLEMINSERTINGONSYNCENTRY)."&message_type=failure");
                        }
                    }
               // } else {
              //      header("location:".$this -> moduleBaseUrl."&message=".urlencode(_ONSYNC_PROBLEMINSERTINGONSYNCENTRY)."&message_type=failure");
              //  }
            }

            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $form -> accept($renderer);

            $smarty -> assign('T_ONSYNC_FORM', $renderer -> toArray());
        } else {
            $currentUser = $this -> getCurrentUser();
            $currentLesson = $this -> getEditedLesson();

            if ($currentUser -> getRole($this -> getCurrentLesson()) == "professor") {
                $onsync = eF_getTableData("module_onsync", "*", "lessons_ID = '".$currentLesson -> lesson['id']."'");
                $smarty -> assign("T_ONSYNC_CURRENTLESSONTYPE", "professor");
            } else {
                $onsync = eF_getTableData("module_onsync_users_to_meeting JOIN module_onsync ON internal_ID = meeting_ID", "*", "lessons_ID = '".$currentLesson -> lesson['id']."' AND users_LOGIN='".$currentUser -> user['login']."'");
                $smarty -> assign("T_ONSYNC_CURRENTLESSONTYPE", "student");
            }

            $now = time();
            foreach ($onsync as $key => $meeting) {
            	$onsync[$key]['timestamp'] = strtotime($meeting['start_time']);
            	$onsync[$key]['joining_url'] =
            		$this->accounts[$meeting['account_ID']]['link_baseurl'] . $meeting['friendly_url'];

                if ($onsync[$key]['timestamp'] < $now) {
                    $onsync[$key]['mayStart'] = 1;
                } else {
                    $onsync[$key]['mayStart'] = 0;
                }
            }
            $smarty -> assign("T_ONSYNC", $onsync);
            $smarty -> assign("T_USERINFO",$currentUser -> user);

			if ($_GET['output'] == 'innerhtml') {
				$result = $smarty -> fetch($this -> moduleBaseDir . "module.tpl");
				echo $result;
				exit;
        	}
        }

        return true;
    }

    public function addScripts()
    {
        //if (isset($_GET['edit_onsync'])) {
//            return array("scriptaculous/prototype", "scriptaculous/effects");
        //} else {
            return array();
        //}
    }

    public function getSmartyTpl()
    {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_ONSYNC_MODULE_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_ONSYNC_MODULE_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_ONSYNC_MODULE_BASELINK" , $this -> moduleBaseLink);

        return $this -> moduleBaseDir . "module.tpl";
    }

    /* CURRENT-LESSON ATTACHED MODULE PAGES */
    public function getLessonModule()
    {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) != "administrator") {
            // Get smarty variable
            $smarty = $this -> getSmartyVar();
            $currentLesson = $this -> getCurrentLesson();
            if ($currentUser -> getRole($this -> getCurrentLesson()) == "student") {
                $onsync = eF_getTableData("module_onsync_users_to_meeting JOIN module_onsync ON internal_ID = meeting_ID", "*", "lessons_ID = '".$currentLesson -> lesson['id']."' AND users_LOGIN='".$currentUser -> user['login']."'", "start_time DESC");
                $smarty -> assign("T_ONSYNC_CURRENTLESSONTYPE", "student");
                $now = time();

                $onsync_server = eF_getTableData("configuration", "value", "name = 'module_onsync_server'");
                foreach ($onsync as $key => $meeting) {
                	$onsync[$key]['timestamp'] = strtotime($meeting['start_time']);
            		$onsync[$key]['joining_url'] = $this->accounts[$meeting['account_ID']]['link_baseurl'] . $meeting['friendly_url'];
                    $onsync[$key]['time_remaining'] = eF_convertIntervalToTime(time() - $onsync[$key]['timestamp'], true). ' '._AGO;
                }
            } else {
                $onsync = eF_getTableData("module_onsync", "*", "lessons_ID = '".$currentLesson -> lesson['id']."'", "start_time DESC");
                $smarty -> assign("T_ONSYNC_CURRENTLESSONTYPE", "professor");
                $now = time();
                foreach ($onsync as $key => $meeting) {
                	$onsync[$key]['timestamp'] = strtotime($meeting['start_time']);
                	$onsync[$key]['joining_url'] = $this->accounts[$meeting['account_ID']]['link_baseurl'] . $meeting['friendly_url'];

                    if ($onsync[$key]['timestamp'] < $now) {
                        $onsync[$key]['mayStart'] = 1;
                        // always start_meeting = 1 url so that only one professor might start the meeting
                    } else {
                        $onsync[$key]['mayStart'] = 0;
                    }

                    $onsync[$key]['time_remaining'] = eF_convertIntervalToTime(time() - $onsync[$key]['timestamp'], true). ' '._AGO;
                }
            }

            $smarty -> assign("T_MODULE_ONSYNC_INNERTABLE_OPTIONS", array(array('text' => _ONSYNC_ONSYNCLIST,   'image' => $this -> moduleBaseLink."images/go_into.png", 'href' => $this -> moduleBaseUrl)));
            $smarty -> assign("T_ONSYNC_INNERTABLE", $onsync);

            return true;
        } else {
            return false;
        }
    }

    public function getLessonSmartyTpl()
    {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getRole($this -> getCurrentLesson()) != "administrator") {
            $smarty = $this -> getSmartyVar();
            $smarty -> assign("T_ONSYNC_MODULE_BASEDIR" , $this -> moduleBaseDir);
            $smarty -> assign("T_ONSYNC_MODULE_BASEURL" , $this -> moduleBaseUrl);
            $smarty -> assign("T_ONSYNC_MODULE_BASELINK" , $this -> moduleBaseLink);

            return $this -> moduleBaseDir . "module_InnerTable.tpl";
        } else {
            return false;
        }
    }

    protected function doRequest($url, $parameters, $accountData, $method = 'GET')
    {
		// encode as JSON
		$username 	= $accountData['username'];
		$password 	= $accountData['password'];
		$baseurl 	= $accountData['API_baseurl'];

		$json = json_encode($parameters);

		$postArgs = 'input_type=json&rest_data=' . $json;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL,  $baseurl . $username . $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_USERPWD, $username.':'.$password);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
    }

    protected function insertOnSyncConference($fields)
    {
		// PUT session

    	$account = $this->accounts[$fields['account_ID']];

		$parameters = array(
		    'topic' 		=> $fields['topic'],
		    'duration' 		=> $fields['duration'],
		    'start_time'	=> $fields['start_time'],
		    'timezone' 		=> $fields['timezone'],
		    'friendly_url'  => $fields['friendly_url'],
		    'password'    	=> $fields['password'],
		    'invited_participants' => array(
		        array(
                   'email'=>'andre@ult.com.br',
                   'first_name'=>'Andre',
                   'last_name'=> 'Kucaniz',
                   'role'=> 3 // 3 - Observer
		        )
		    )
		);

		$result = $this->doRequest('/session/format/json', $parameters, $account, 'PUT');

		$data = json_decode($result);

		if ($data->message == 'Session added') {
			return $data->id;
		} else {
			return false;
		}
    }
    protected function updateOnSyncConference($onsync_ID, $fields)
    {
    	$account = $this->accounts[$fields['account_ID']];

    	echo $onsync_ID;
		$parameters = array(
			'id'			=> $onsync_ID,
		    'topic' 		=> $fields['topic'],
		    'duration' 		=> $fields['duration'],
		    'start_time'	=> $fields['start_time'],
		    'timezone' 		=> $fields['timezone'],
		    'friendly_url'  => $fields['friendly_url'],
		    'password'    	=> $fields['password'],
		    'invited_participants' => array(
		        array(
                   'email'=>'andre@ult.com.br',
                   'first_name'=>'Andre',
                   'last_name'=> 'Kucaniz',
                   'role'=> 3 // 3 - Observer
		        )
		    )
		);

    	$result = $this->doRequest('/session/format/json', $parameters, $account, 'POST');

    	$data = json_decode($result);

		if ($data->message == 'Session updated') {
			return true;
		} else {
			return false;
		}
    }
    protected function deleteOnSyncConference($onsync_ID, $fields)
    {
	    $account = $this->accounts[$fields['account_ID']];

		$parameters = array(
			'id'			=> $onsync_ID
		);

    	$result = $this->doRequest('/session/format/json', $parameters, $account, 'DELETE');

    	$data = json_decode($result);

		if ($data->message == 'Session was deleted') {
			return true;
		} else {
			return false;
		}

    }

}
