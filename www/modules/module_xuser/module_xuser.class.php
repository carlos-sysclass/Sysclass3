<?php
class module_xuser extends MagesterExtendedModule {
	
	const GET_XUSERS				= 'get_xusers';
	const GET_XUSERS_SOURCE			= 'get_xusers_source';
	const ADD_XUSER					= 'add_xuser';
	const EDIT_XUSER				= 'edit_xuser';
	const DELETE_XUSER				= 'delete_xuser';
	const UPDATE_XUSER				= 'update_xuser';
	const SHOW_CONTROL_PANEL		= 'show_control_panel';

	protected static $roles = null;

    // Mandatory functions required for module function
    public function getName() {
        return "XUSER";
    }

    public function getPermittedRoles() {
        return array("administrator" /*,"professor" *//*,"student"*/);
    }

    public function isLessonModule() {
        return false;
    }
    
    public function getUrl($action) {
    	switch($action) {
    		case self::EDIT_XUSER : {
    			return $this -> moduleBaseUrl . 
    				"&action=" . self::EDIT_XUSER . 
    				"&xuser_id=" . $this->getEditedUser()->user['id'] /*. 
    				"&xuser_login=". $this->getEditedUser()->user['login']*/
    			; 
    		}
    		default : {
    			return parent::getUrl($action);
    		}
    	}
    }

    public function getTitle($action) {
    	switch($action) {
    		case self::ADD_XUSER : {
    			return __XUSER_ADDUSER;
    		}
    		case self::EDIT_XUSER : {
    			$userName = '<span class="username">' . $this->getEditedUser()->user['login'] . '</span>';
    			
    			return sprintf(__XUSERS_EDITINGXUSER_, $userName);
    		}
   			case $this->getDefaultAction() : {
   				return __XUSER_MANAGEMENT;
    		}
    		default : {
    			return parent::getTitle($action);
    		}
    	}
    }
    /*
    public function getCenterLinkInfo() {
        $currentUser = $this -> getCurrentUser();
        
        if (
        	$this->getExtendedTypeID($currentUser) == "administrator"
        ) {
        	
            return array(
				'title' 	=> $this->getTitle(self::SHOW_CONTROL_PANEL),
                'image' 	=> 'images/others/transparent.gif',
                'link'  	=> $this -> moduleBaseUrl,
            	'image_class'	=> 'sprite32 sprite32-user'
            );
        }
    }
    */
	/*    	
    public function getNavigationLinks() {
			
    	$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;

        $basicNavArray = array (
			array ('title' => _HOME, 'link' => "administrator.php?ctg=control_panel"),
    		array ('title' => _MODULE_XUSERS_MANAGEMENT, 'link'  => $this -> moduleBaseUrl)
		);
        
		if ($selectedAction == self::EDIT_XUSER) {
			
			$userName = '<span class="username">' . $this->getEditedUser()->user['login'] . '</span>';
			
            $basicNavArray[] = array ('title' => sprintf(__XUSERS_EDITINGXUSER_, $userName), 'link'  => $this -> moduleBaseUrl . "&action=" . self::EDIT_XUSER . "&xuser_id=". $_GET['xuser_id'] . "&xuser_login=". $_GET['xuser_login']);
		} else if ($selectedAction == self::ADD_XUSER) {
            $basicNavArray[] = array ('title' => _MODULE_XUSERS_ADDXUSER, 'link'  => $this -> moduleBaseUrl . "&action=" . self::ADD_XUSER);
		}
        return $basicNavArray;
        
    }
    */

    public function getSidebarLinkInfo() {
/*
        $link_of_menu_clesson = array (array ('id' => 'xusers_link_id1',
                                              'title' => _MODULE_XUSER,
                                              'image' => $this -> moduleBaseDir . 'images/xusers16.png',
                                              '_magesterExtensions' => '1',
                                              'link'  => $this -> moduleBaseUrl));

        return array ( "user" => $link_of_menu_clesson);
*/
    }

    public function getLinkToHighlight() {
        return 'xusers_link_id1';
    }
    
	public function getDefaultAction() {
		return self::GET_XUSERS;
	}
    /* ACTION HANDLERS */
    
    public function getXusersAction($sendData) {
    	$smarty = $this->getSmartyVar();
		$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());
		$smarty -> assign("T_XROLES", MagesterUser :: getRoles(true));
		return true;
    }
    
    public function addXuserAction($sendData) {
     	$this->makeEditUserOptions();
		if ( $this->makeBasicForm() ) {
           	$this->appendTemplate(
           		array(
	            	'title'			=> _MODULE_XUSERS_EDITBASICXUSER,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/xuser_basic_form.tpl",
	            	'contentclass'	=> ''
	            )
           	);
		}
    }
    public function editXuserAction($sendData) {
    	$smarty = $this->getSmartyVar();
    	
    	$smarty -> assign("T_EDITED_USER", $this->getEditedUser()->user);
    	
		$this->makeEditUserOptions();
       		
       	if ( $this->makeBasicForm() ) {
            $this->appendTemplate(
            	array(
            	'title'			=> _MODULE_XUSERS_EDITBASICXUSER,
            	'template'		=> $this->moduleBaseDir . "templates/includes/xuser_basic_form.tpl",
            	'contentclass'	=> ''
            	)
            );
		}
            
        if ( $this->makeResponsibleForm() ) {
	    	$this->appendTemplate(
	    		array(
		        	'title'			=> _MODULE_XUSERS_EDITRESPONSIBLEXUSER,
		            'template'		=> $this->moduleBaseDir . "templates/includes/xuser_responsible_form.tpl",
		            'contentclass'	=> ''
				)
			);
		}
/*		
        if (
            $this->getEditedUser()->getType() != "administrator" && 
            !($this->getEditedUser() instanceof MagesterAdministrator) &&
            in_array($this->getEditedUser()->getType(), array_keys($this->getEditedUser()->getLessonsRoles())) 
		) {
			
			
        }
*/
		
    }
    public function createResponsibleUserAction($token = null, $fields = null) {
        if (is_null($token)) {
        	//$xenrollmentModule = $this->loadModule("xenrollment");
			//$token = $xenrollmentModule->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}
		
		if ($fields['login']) {
			$this->getEditedUser(true, $fields['login']);
		}
		if (empty($fields['surname'])) {
			$names = explode(' ', $fields['name'], 2);
			$fields['name'] = $names[0];
			$fields['surname'] = $names[1];
		}
		unset($fields['login']);
		
		if (!isset($fields['type'])) {
			$fields = 'parents';
		}
		
        switch ($GLOBALS['configuration']['date_format']) {
			case "YYYY/MM/DD": {
				$date_format = 'Y/m/d'; break;
			}
			case "MM/DD/YYYY": {
				$date_format = 'm/d/Y'; break;
			}
			case "DD/MM/YYYY": 
			default: {
				$date_format = 'd/m/Y'; break;
			}
		}
		
		$tableFields = array(
			'id', 'type', 'name', 'surname', 'email', 'data_nascimento', 'rg', 'cpf', 'telefone', 'celular',
			'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'uf'
		);
		
		foreach($fields as $key => $value) {
			if (!in_array($key, $tableFields)) {
				unset($fields[$key]);
			}
		}
		
					
		$fields['data_nascimento'] = date_create_from_format($date_format, $fields['data_nascimento']);

		if ($fields['data_nascimento']) {
			$fields['data_nascimento']	= $fields['data_nascimento']->format('Y-m-d');
		}
			    
		$xuser_responsible_entry = eF_getTableData("module_xuser_responsible", "*", sprintf("id=%d AND type = '%s'", $this->getEditedUser() -> user['id'], $fields['type']));
		
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
		
		if (count($xuser_responsible_entry) == 0) {
			$fields['id']	= $this->getEditedUser() -> user['id'];
			$result = eF_insertTableData("module_xuser_responsible", $fields);
		} else {
			$result = eF_updateTableData("module_xuser_responsible", $fields, sprintf("id=%d AND type = '%s'", $this->getEditedUser() -> user['id'], $fields['type']));
		}
		
		if ($result) {

			return array(
				'status'		=> 'ok',
				'message'		=> $this->getCurrentUser() -> getType() == "administrator" ? $message = _PERSONALDATACHANGESUCCESSADMIN : $message = _PERSONALDATACHANGESUCCESS,
				'message_type'	=> 'success'	 
			);
		} else {
			return array(
				'status'		=> 'error',
				'message'		=> $this->getCurrentUser() -> getType() == "administrator" ? $message = _PERSONALDATACHANGEERRORADMIN : $message = _PERSONALDATACHANGEERROR,
				'message_type'	=> 'failure'	 
			);
		}
		
		/*
		array(16) {
		  ["name"]=>
		  string(13) "joao da silva"
		  ["surname"]=>
		  string(0) ""
		  ["email"]=>
		  string(16) "andre@ult.com.br"
		  ["data_nascimento"]=>
		  string(10) "19/07/1983"
		  ["rg"]=>
		  string(8) "84148911"
		  ["cpf"]=>
		  string(14) "047.436.969-25"

		  ["telefone"]=>
		  string(14) "(41) 3022-7414"
		  ["celular"]=>
		  string(14) "(41) 3022-7414"
		  ["login"]=>
		  string(15) "andre.kucaniz39"
		  
		  <!-- IGNORADO
		  ["cep"]=>
		  string(9) "80240-041"
		  ["endereco"]=>
		  string(36) "Avenida Presidente Getúlio Vargas, "
		  ["numero"]=>
		  string(4) "4547"
		  ["complemento"]=>
		  string(5) "AP 90"
		  ["bairro"]=>
		  string(11) "Água Verde"
		  ["cidade"]=>
		  string(8) "Curitiba"
		  ["estado"]=>
		  string(2) "PR"
		  -->
		}
		*/
		
		/*
		res_name	fdsfds
		res_surname	fsdfds
		res_email	andre@ult.com.br
		res_data_nascimento
		res_rg	
		res_cpf	
		res_telefone
		res_celular	
		xuser_ID	
		*/

    }

    
    public function searchUserAction($token = null, $contraints = array()) {
    	/*
    	 $lessons = MagesterLesson :: getLessons();
    	$lessons = eF_multiSort($lessons, 'id', 'desc');
    	*/
    	//    	error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
    	if ($contraints['id']) {
    		$where[] 	= sprintf("u.id = '%s'", $contraints['id']);
    	}
    	if ($contraints['login']) {
    		$where[] 	= sprintf("u.login = '%s'", $contraints['login']);
    	}
    	if ($contraints['autologin']) {
    		$where[] 	= sprintf("u.autologin = '%s'", $contraints['autologin']);
    	}
    	if ($contraints['active']) {
    		$where[]	= 'u.active = ' . $contraints['active'];
    	}
    	if (count($where))
    	$result = ef_getTableData('users u', "login", implode(' AND ', $where));
    	
    	if (count($result) > 0) {
    		$login = $result[0]['login'];
    		
    		$user =  MagesterUserFactory::factory($login);
    		
    		$details = MagesterUserDetails :: getUserDetails($login);
    		
    		
    		
    		$fullUser = array_merge($user->user, $details);
    		
    		var_dump($fullUser);
    		
    		// LOAD USER skills
    		$xSkillModule = $this->loadModule("xskill");
    		
    		$fullUser['skills'] = $xSkillModule->loadUserSkills($fullUser['id']);
    		
    		return $fullUser;
    	} else {
    		return array();
    	}
    }    
    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule() {
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		$migratedActions = array(
			self::ADD_XUSER,
			self::EDIT_XUSER,
			$this->getDefaultAction()
		);
		
		if (in_array($selectedAction, $migratedActions)) {
			return parent::getModule();
		}
		
		$smarty = $this -> getSmartyVar();
		
		$smarty -> assign("T_MODULE_XUSER_ACTION", $selectedAction);    	
    	
        // Get smarty global variable
        $smarty = $this -> getSmartyVar();

        if ($selectedAction == self::DELETE_XUSER && eF_checkParameter($_GET['xuser_id'], 'id')) {
        	// ENVIAR EVENTOS PARA TODO O SISTEMA, PARA DESMATRICULAR O USUÁRIO, NEGOCIAR DÉBITOS EM ABERTO, DESCONFIGURAR MÓDULOS, ETC..
            eF_deleteTableData("module_xuser", "id=".$_GET['xuser_id']);
            
            header("location:". $this -> moduleBaseUrl ."&message=".urlencode(_MODULE_XUSERS_SUCCESFULLYDELETEDXUSERENTRY)."&message_type=success");
            
        } elseif ($selectedAction == self::UPDATE_XUSER && eF_checkParameter($_GET['xuser_login'], 'login')) {
        	switch($_GET['field']) {
        		case "user_course.course_type" : {
        			
        			$user_login = $_GET['xuser_login'];
        			$course_id = $_POST['course_id'];
        			$course_type = $_POST['course_type'];
        			if (!in_array($course_type, array('Presencial', 'Via Web'))) {
        				$course_type = "";
        			}
        			
        			$result = eF_updateTableData(
        				"users_to_courses", 
        				array('course_type' => $course_type), 
        				sprintf("users_LOGIN = '%s' AND courses_ID = %d", $user_login, $course_id) 
        			);
        			
        			//echo sprintf("users_LOGIN = '%s' AND courses_ID = %d", $user_login, $course_id);
        			
        			if ($result) {
	        			echo json_encode(array(
	        				'status'	=> 'success',
	        				'message'	=> _XUSER_UPDATED_SUCCESS
	        			));
        			} else {
	        			echo json_encode(array(
	        				'status'	=> 'error',
	        				'message'	=> _XUSER_UPDATED_ERROR
	        			));
        			}
        			exit;

        			break;
        		}
        		default : {
        			
        			
        		}
        		
        	}
        	
            
        } else if (
        	$selectedAction == self::ADD_XUSER || 
        	($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_id'], 'id')) ||
        	($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_login'], 'login'))
        ) {
            //$result = $rendererBasic -> toArray();
            /*
            
       		if ( $this->makeEditUserOptions() ) {
       		}
       		
			$templates = array();
       		
           	if ( $this->makeBasicForm() ) {
	            $templates[] = array(
	            	'title'			=> _MODULE_XUSERS_EDITBASICXUSER,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/xuser_basic_form.tpl",
	            	'contentclass'	=> ''
	            );
            }
            
        	if ( $this->makeResponsibleForm() ) {
	            $templates[] = array(
	            	'title'			=> _MODULE_XUSERS_EDITRESPONSIBLEXUSER,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/xuser_responsible_form.tpl",
	            	'contentclass'	=> ''
	            );
            }
            
            if (
        		($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_id'], 'id')) ||
        		($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_login'], 'login'))
        	) {
	            if (
	            	$this->getEditedUser()->getType() != "administrator" && 
	            	in_array($this->getEditedUser()->getType(), array_keys($this->getEditedUser()->getLessonsRoles())) 
	            ) {
	            	$userCourses = $this->getEditedUser()->getUserCourses(array('return_objects' => false));
	            	
	            	$courses = array();
	            	
   					foreach($userCourses as $userCourse) {

   						if (MagesterUser :: isStudentRole($userCourse['user_type'])) {
   							$course = array(
   								'id'				=> $userCourse['id'],
   								'name'				=> $userCourse['name'],
   								'course_type'		=> $userCourse['course_type'],
   								'enable_presencial'	=> $userCourse['enable_presencial'],
   								'enable_web'		=> $userCourse['enable_web'],
   								'classes'			=> array()
   							);

	   						if ($course['course_type'] == 'Via Web') {
	   							$course['price'] = $userCourse['price_web'];
	   						} elseif ($course['course_type'] == 'Presencial') {
	   							$course['price'] = $userCourse['price_presencial'];
	   						} else {
	   							$course['price'] = $userCourse['price'];
	   							$course['course_type'] = _PAGAMENTO_COURSETYPENOSELECTED;
	   						}
	   						
   							$courseClass = MagesterCourseClass::getClassForUserCourse($this->getEditedUser()->user['id'], $userCourse['id'], array('return_objects' => false));
   							
   							if (count($courseClass) > 0) {
   								foreach($courseClass as $class) {
   									$course['classes'][] = array(
   										'id'			=> $class['id'],
   										'name'			=> $class['name'],
   										'start_date'	=> $class['start_date'],
   										'end_date'		=> $class['end_date'],
   										'schedules'		=> $class['schedules']
   									); 
   								}
   							}
	   						$courses[] = $course;
   						}
   					}
   					
   					$smarty -> assign("T_XUSER_COURSES_LIST", $courses);
   						
		            $templates[] = array(
		            	'title'			=> __XUSER_SHOWUSERCOURSES,
		            	'template'		=> $this->moduleBaseDir . "templates/includes/xuser.list.courses.tpl",
		            	'contentclass'	=> ''
		            );
	            }
        	}
            
            $modules = eF_loadAllModules(true);
            foreach ($modules as $module_name => $module) {
            	if (is_callable(array($module, "receiveEvent"))) {
            		$templates[] = $module->receiveEvent($this, $selectedAction, array('editedUser' => $this->getEditedUser()));
            	}
            }
            
			$smarty -> assign('T_MODULE_XUSER_FORM_TABS',
				$templates
			);
            */
        } elseif ($selectedAction == self::GET_XUSERS_SOURCE) {
        	$this->getDatatableSource();
		} else {
        	
        /*
			$users = eF_getTableData("users", "*", "archive = 0");
			$user_lessons = eF_getTableDataFlat("users_to_lessons as ul, lessons as l", "ul.users_LOGIN, count(ul.lessons_ID) as lessons_num", "ul.lessons_ID=l.id AND l.archive=0", "", "ul.users_LOGIN");
			$user_courses = eF_getTableDataFlat("users_to_courses as uc, courses as c", "uc.users_LOGIN, count(uc.courses_ID) as courses_num", "uc.courses_ID=c.id AND c.archive=0", "", "uc.users_LOGIN");
			$user_groups = eF_getTableDataFlat("users_to_groups", "users_LOGIN, count(groups_ID) as groups_num", "", "", "users_LOGIN");
			$user_lessons = array_combine($user_lessons['users_LOGIN'], $user_lessons['lessons_num']);
			$user_courses = array_combine($user_courses['users_LOGIN'], $user_courses['courses_num']);
			$user_groups = array_combine($user_groups['users_LOGIN'], $user_groups['groups_num']);
			array_walk($users, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["lessons_num"] = $s[$v["login"]] : $v["lessons_num"] = 0;'), $user_lessons); //Assign lessons number to users array (this way we eliminate the need for an expensive explicit loop)
			array_walk($users, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["courses_num"] = $s[$v["login"]] : $v["courses_num"] = 0;'), $user_courses);
			array_walk($users, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["groups_num"] = $s[$v["login"]] : $v["groups_num"] = 0;'), $user_groups);
			$result = eF_getTableDataFlat("logs", "users_LOGIN, timestamp", "action = 'login'", "timestamp");
			$lastLogins = array_combine($result['users_LOGIN'], $result['timestamp']);
		            
			foreach ($users as $key => $value) {
				$users[$key]['last_login'] = $lastLogins[$value['login']];
				if (isset($_COOKIE['toggle_active'])) {
					if (($_COOKIE['toggle_active'] == 1 && !$value['active']) || ($_COOKIE['toggle_active'] == -1 && $value['active'])) {
						unset($users[$key]);
					}
				}
			}
		*/	
//			$smarty -> assign("T_USERS_SIZE", sizeof($users));

//            $smarty -> assign("T_XUSERS", $users);

			$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());
            $smarty -> assign("T_XROLES", MagesterUser :: getRoles(true));
            
//            $smarty -> assign("T_XUSERS", $xusers);
        }
        return true;
    }

    public function getSmartyTpl() {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_MODULE_XUSER_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_MODULE_XUSER_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_MODULE_XUSER_BASELINK" , $this -> moduleBaseLink);
        
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
        
        //$smarty -> assign("T_MODULE_XUSER_ACTION", $selectedAction);
        
        return $this -> moduleBaseDir . "templates/default.tpl";
    }
    
    public function addScripts() {
    	return array('jquery/jquery.meio.mask'/*,  'includes/users' */);
    }
    /*
    public function getModuleCSS() {
		return $this -> moduleBaseDir . "css/xuser.css";
    }

    public function getModuleJS() {
		return $this -> moduleBaseDir . "js/xuser.js";
    }
    */

    
    private function makeEditUserOptions() {
        // CREATING RESPONSIBLE FORM
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		$smarty = $this -> getSmartyVar();
		
    	if (
        	($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_id'], 'id')) ||
        	($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_login'], 'login'))
        ) {		
			$options = array();
/*		
			$options[] = array(
				'text' 		=> __XUSER_UNREGISTER,
				'hint'		=> __XUSER_UNREGISTER_HINT, 
				'image' 	=> "/themes/sysclass/images/icons/small/grey/delete.png", 
				'href' 		=> $this->moduleBaseUrl . '&action=' . self::DELETE_XUSER . "&xuser_id=" . $_GET['xuser_id'] . "&xuser_login=" . $_GET['xuser_login']
			);
*/			
			$options[] = array(
				'text' 		=> __XENROLLMENT_UNREGISTER,
				'hint'		=> __XENROLLMENT_UNREGISTER_HINT, 
				'image' 	=> "/themes/sysclass/images/icons/small/grey/delete.png", 
				'href' 		=> "/" . $_SESSION['s_type'] . ".php?ctg=module&op=module_xenrollment" . "&action=unregister_xenrollment&xuser_id=" . $_GET['xuser_id'] . "&xuser_login=" . $_GET['xuser_login']
			);
			
/*					
			$options[] = array(
				'text' 		=> _MODULE_PAGAMENTO_UPDATE_INVOICES_STATUS,
				'hint'		=> __PAGAMENTO_UPDATE_INVOICES_HINT,
				'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png", 
				'href' 		=> $this->moduleBaseUrl . '&action=' . self::UPDATE_INVOICES_STATUS,
				'selected'	=> $selectedAction == self::UPDATE_INVOICES_STATUS
			);
		
			$options[] = array(
				'text' 		=> _MODULE_PAGAMENTO_PAYMENT_TYPES,
				'hint'		=> __PAGAMENTO_PAYMENT_TYPES_HINT, 
				'image' 	=> "/themes/sysclass/images/icons/small/grey/cog_2.png",
				'href' 		=> $this->moduleBaseUrl . '&action=' . self::GET_PAYMENT_TYPES,
				'selected'	=> $selectedAction == self::GET_PAYMENT_TYPES
			);
*/
			$smarty -> assign("T_XUSER_OPTIONS", $options);
			$smarty -> assign("T_XUSER_OPTIONS_SIZE", count($options));
			
			return true;
        }
        return false;

    }

    private function makeBasicForm() {
        // CREATING RESPONSIBLE FORM
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		
		$smarty = $this -> getSmartyVar();
		
    	if (
        	$selectedAction == self::ADD_XUSER || 
        	($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_id'], 'id')) ||
        	($selectedAction == self::EDIT_XUSER && eF_checkParameter($_GET['xuser_login'], 'login'))
        ) {		
			$currentUser = $this->getCurrentUser();
			
			
			if ($selectedAction == self::ADD_XUSER) {
				$form = new HTML_QuickForm("xuser_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, true);	
			} elseif ($selectedAction == self::EDIT_XUSER) {
				$form = new HTML_QuickForm("xuser_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, false);
			}
			
			//Register this rule for checking user input with our function, eF_checkParameter
            $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');			
			
			
			$form -> addElement('hidden', 'xuser_ID');
			
			
			$schools = eF_getTableDataFlat("module_ies", "id, nome", "active = 1" );
			   		
			if (count($schools) > 0) {
				$schools = array_merge(
	  				array(-1 => __SELECT_ONE_OPTION),
	  				array_combine($schools['id'], $schools['nome'])
	   			);
   			} else {
   				$schools = array(-1 => __NO_DISPONIBLE_OPTIONS, 0 => __IES_ALL_OPTIONS);
   			}
   			
			$polosData = eF_getTableData(
				"`module_polos` pol LEFT OUTER JOIN `module_ies_to_polos` ies2pol ON (pol.id = ies2pol.polo_id)", 
				"pol.id, pol.nome, ies2pol.ies_id", 
				"pol.active = 1"
			);
			
	   		if (count($polosData) > 0) {
	   			$polos = array(
	   				-1 => array(-1 => __SELECT_ONE_OPTION)
	   			);

	   			foreach($polosData as $poloItem) {
	   				$polos[$poloItem['ies_id']][$poloItem['id']] = $poloItem['nome'];
	   			}
   			} else {
   				$polos = array(
	   				-1 => array(-1 => __NO_DISPONIBLE_OPTIONS)
	   			);
   			}

  			
			$hierIesPolo = $form -> addElement('hierselect', 'ies_polo_id', array(__IES_FORM_NAME, __IES_POLO_FORM_NAME), 'class = "large"', '');
			$hierIesPolo->setOptions(array($schools, $polos));
			
			
		   	
		   	
        	if ($selectedAction == self::ADD_XUSER) {
        		$form -> addElement('text', 'new_login', __XUSER_NEW_LOGIN, 'readonly="readonly" autocomplete="off" class = "medium"');
        		$form -> addElement('text', 'password_', _PASSWORD, 'readonly="readonly" autocomplete="off" class = "medium"');
        		
				$form->setDefaults(array('password_' => MagesterUser::generateMD5Password()));
			} elseif ($selectedAction == self::EDIT_XUSER) {
				$form -> addElement('password', 'password_', _PASSWORDLEAVEBLANK, 'autocomplete="off" class = "medium"');
				$form -> addElement('password', 'passrepeat', _REPEATPASSWORD, 'class = "medium "');
				$form -> addRule(array('password_', 'passrepeat'), _PASSWORDSDONOTMATCH, 'compare', null, 'client');
			}
		   	
			
	           
	        $stateList = localization::getStateList();
	            
			$form -> addElement('text', 'cep', _MODULE_XUSERS_CEP, 'class = "medium" alt="cep"');
			$form -> addElement('text', 'endereco', _MODULE_XUSERS_ENDERECO, 'class = "large"');
			$form -> addElement('text', 'numero', _MODULE_XUSERS_NUMERO, 'class = "small"');
			$form -> addElement('text', 'complemento', _MODULE_XUSERS_COMPLEMENTO, 'class = "small"');
			$form -> addElement('text', 'bairro', _MODULE_XUSERS_BAIRRO, 'class = "medium"');
			$form -> addElement('text', 'cidade', _MODULE_XUSERS_CIDADE, 'class = "medium"');
			$form -> addElement('select', 'uf', _MODULE_XUSERS_UF, $stateList, 'class = "small"');
			$form -> addElement('text', 'telefone', _MODULE_XUSERS_TELEFONE, 'class = "medium" alt="phone"');
			$form -> addElement('text', 'celular', _MODULE_XUSERS_CELULAR, 'class = "medium" alt="phone"');
			$form -> addElement('jquerydate', 'data_nascimento', _USER_DATA_NASCIMENTO);
			$form -> addElement('advcheckbox', 'not_18', __XUSER_FORM_IS_MENOR, null, '', array(0, 1));
			$form -> addElement('text', 'rg', _USER_RG, 'class = "medium"');
			$form -> addElement('text', 'cpf', _USER_CPF, 'class = "medium" alt="cpf"');
			
			
            $form -> addElement('text', 'name', _NAME, 'class = "large"');
			$form -> addRule('name', _THEFIELD.' '._NAME.' '._ISMANDATORY, 'required', null, 'client');
			$form -> addElement('text', 'surname', _SURNAME, 'class = "large"');
 			$form -> addRule('surname', _THEFIELD.' '._SURNAME.' '._ISMANDATORY, 'required', null, 'client');
			$form -> addElement('text', 'email', _EMAILADDRESS, 'class = "large mask-email"');
			$form -> addRule('email', _THEFIELD.' '._EMAILADDRESS.' '._ISMANDATORY, 'required', null, 'client');
			$form -> addRule('email', _INVALIDFIELDDATA, 'checkParameter', 'email');
			
			
        	// Find all groups available to create the select-group drop down
			if (!isset($groups_table)) {
				$groups_table = eF_getTableData("groups", "id, name", "active=1");
			}
			if (!empty($groups_table)) {
				$groups = array ("" => "");
				foreach ($groups_table as $group) {
					$gID = $group['id'];
					$groups["$gID"] = $group['name'];
				}
				$form -> addElement('select', 'group' , _GROUP, $groups ,'class = "medium" id="group" name="group"');
			} else {
				$form -> addElement('select', 'group' , _GROUP, array ("" => _NOGROUPSDEFINED) ,'class = "medium" id="group" name="group" disabled="disabled"');
			}

			if ($selectedAction == self::EDIT_XUSER) {
				$this->getEditedUser() -> getGroups();
				$init_group = end($this->getEditedUser() -> groups);
				$form -> setDefaults(array('group' => $init_group['groups_ID']));

			}
			$resultRole = eF_getTableData("users", "user_types_ID", "login='".$currentUser -> login."'");
			$smarty -> assign("T_CURRENTUSERROLEID", $resultRole[0]['user_types_ID']);
			
			$timezones = eF_getTimezones();
			$form -> addElement("select", "timezone", _TIMEZONE, $timezones, 'class = "large" style="width:20em"');
			// Set default values for new users
			if (($selectedAction == self::ADD_XUSER) || ($selectedAction == self::EDIT_XUSER && $this->getEditedUser() -> user['timezone'] == "")) {
				$form -> setDefaults(array('timezone' => $GLOBALS['configuration']['time_zone']));
			}
			if ($this->getEditedUser()->user['login'] == $_SESSION['s_login']) { //prevent a logged admin to change its type
				$form -> freeze(array('user_type'));
			}
			
			
			if ($GLOBALS['configuration']['onelanguage']) {
				$form -> addElement('hidden', 'languages_NAME', $GLOBALS['configuration']['default_language']);
			} else {
				$form -> addElement('select', 'languages_NAME', _LANGUAGE, MagesterSystem :: getLanguages(true, true));
				// Set default values for new users
				if (isset($_GET['add_user'])) {
					$form -> setDefaults(array('languages_NAME' => $GLOBALS['configuration']['default_language']));
				}
			}
			
	         // In HCD mode supervisors - and not only administrators - may create employees
	 		if ($currentUser -> getType() == "administrator") {
	 			$rolesTypes = MagesterUser :: getRoles();
	  			if ($resultRole[0]['user_types_ID'] == 0 || $rolesTypes[$resultRole[0]['user_types_ID']] == "administrator") {
				   	$roles = eF_getTableDataFlat("user_types", "*");
				   	
				   	$roles_array['student'] = _STUDENT;
				   	$roles_array['professor'] = _PROFESSOR;
					$roles_array['administrator'] = _ADMINISTRATOR;
					if (sizeof($roles) > 0) {
						for ($k = 0; $k < sizeof($roles['id']); $k++) {
							//if ($roles['basic_user_type'][$k] != 'student') {
								if ($roles['active'][$k] == 1 || (/* isset($this->getEditedUser()) && */$this->getEditedUser() -> user['user_types_ID'] == $roles['id'][$k])) { //Make sure that the user's current role will be listed, even if it's deactivated
									$roles_array[$roles['id'][$k]] = $roles['name'][$k];
								}
							//}
						}
					}
					$form -> addElement('select', 'user_type', _USERTYPE, $roles_array);
				}
				$form -> addElement('advcheckbox', 'active', _ACTIVEUSER, null, 'class = "inputCheckbox" id="activeCheckbox" ', array(0, 1));
				// Set default values for new users
				if (isset($_GET['add_user'])) {
					$form -> setDefaults(array('active' => '1'));
				}
			}
			
           	$form -> addElement('submit', 'submit_xuser', _MODULE_XUSERS_SAVE, 'class = "button_colour round_all"');
           	
           	
           	 if ($selectedAction == self::EDIT_XUSER) {	
          		$smarty -> assign("T_USER_TYPE", $this->getEditedUser() -> user['user_type']);
  				$smarty -> assign("T_REGISTRATION_DATE", $this->getEditedUser() -> user['timestamp']);

	  			try {
	   				$avatar = new MagesterFile($this->getEditedUser() -> user['avatar']);
	   				$smarty -> assign ("T_AVATAR", urlencode($this->getEditedUser() -> user['avatar']));
				   list($width, $height) = getimagesize($avatar['path']);
	   				if ($width > 200 || $height > 100) {
					    // Get normalized dimensions
	    				list($newwidth, $newheight) = eF_getNormalizedDims($avatar['path'], 200, 100);
					    // The template will check if they are defined and normalize the picture only if needed
	    				$smarty -> assign("T_NEWWIDTH", $newwidth);
	    				$smarty -> assign("T_NEWHEIGHT", $newheight);
	   				}
	  			} catch (Exception $e) {
	   				$smarty -> assign ("T_AVATAR", urlencode(G_SYSTEMAVATARSPATH."unknown_small.png"));
	  			}
			}
			
       		if ($form -> isSubmitted() && $form -> validate()) {
            	$values = $form->exportValues();
            	
             	if ($selectedAction == self::EDIT_XUSER) {
             		
             		$rolesTypes = MagesterUser :: getRoles();
             		
					$users_content = array(
						'name' 				=> $values['name'],
                    	'surname' 			=> $values['surname'],
                        'email' 			=> $values['email'],
                        'languages_NAME'	=> $values['languages_NAME'],
                        'timezone' 			=> $values['timezone']
					);
					
    				if ($currentUser -> getType() == "administrator") {
    					$roles = MagesterUser::getRoles();
    					
     					$users_content['active'] = $values['active'];
				
     					$users_content['user_type'] = $roles[$values['user_type']];
     					$users_content['user_types_ID'] = $values['user_type'];
     					$users_content['pending'] = 0; //The user cannot be pending, since the admin sent this information
    				}
    				if (isset($values['password_']) && $values['password_']) {
     					$users_content['password'] = MagesterUser::createPassword($values['password_']);
    				}
				    // If name/surname changed then the sideframe must be reloaded
				    if (
				    	$this->getEditedUser() -> login == $currentUser -> login && (
				    		$this->getEditedUser() -> user['languages_NAME'] != $values['languages_NAME'] || 
				    		$this->getEditedUser() -> user['name'] != $values['name'] || 
				    		$this->getEditedUser() -> user['surname'] != $values['surname']
				    	)
				    ) {
					    $smarty -> assign("T_REFRESH_SIDE", 1);
     					$smarty -> assign("T_PERSONAL_CTG", 1);
     					if ($_SESSION['s_language'] != $values['languages_NAME']) {
      						$_SESSION['s_language'] = $values['languages_NAME'];
     					}
    				}
    				eF_updateTableData("users", $users_content, "login='".$this->getEditedUser() -> login."'");
    				
	             	switch ($GLOBALS['configuration']['date_format']) {
						case "YYYY/MM/DD": {
							$date_format = 'Y/m/d'; break;
						}
						case "MM/DD/YYYY": {
							$date_format = 'm/d/Y'; break;
						}
						case "DD/MM/YYYY": 
						default: {
							$date_format = 'd/m/Y'; break;
						}
					}
					
					$values['data_nascimento'] = date_create_from_format($date_format, $values['data_nascimento']);
					
     				$user_details = array(
     					'ies_id'			=> $values['ies_polo_id'][0],
     					'polo_id'			=> $values['ies_polo_id'][1],
						'rg'				=> $values['rg'],
						'cpf'				=> $values['cpf'],
						'cep'				=> $values['cep'],
						'endereco'			=> $values['endereco'],
						'numero'			=> $values['numero'],
						'complemento'		=> $values['complemento'],
						'bairro'			=> $values['bairro'],
						'cidade'			=> $values['cidade'],
						'uf'				=> $values['uf'],
						'telefone'			=> $values['telefone'],
						'celular'			=> $values['celular'],
     					'not_18'			=> $values['not_18']
					);
				    if ($values['data_nascimento']) {
				     	$user_details['data_nascimento']	= $values['data_nascimento']->format('Y-m-d');
				    }
     
     				MagesterUserDetails :: injectDetails($this->getEditedUser() -> login, $user_details);
    
				    // mpaltas temporary solution: manual OO to keep $this->getEditedUser() object cache consistent
					if ($this->getEditedUser() -> user['user_type'] != $values['user_type']) {
				     	// the new instance will be of the updated type
						$this->getEditedUser(true);
				    }
				    foreach ($users_content as $field => $content) {
						$this->getEditedUser() -> user[$field] = $content;
				    }
				    // end of mpaltas temp solution
					$currentUser -> getType() == "administrator" ? $message = _PERSONALDATACHANGESUCCESSADMIN : $message = _PERSONALDATACHANGESUCCESS;
				    $message_type = 'success';
        	
        	
				    
					if (isset($values['password_']) && $values['password_'] && $currentUser -> login == $_GET['edit_user']) { //In case the user changed his password, change it in the session as well
						$_SESSION['s_password'] = $users_content['password'];
				    }
				    // Assignment of user group
				    if ($values['group'] != $init_group['groups_ID']) {
						if ($init_group['groups_ID']) {
							$this->getEditedUser() -> removeGroups($init_group['groups_ID']);
						}
						if ($values['group']) {
							$this->getEditedUser() -> addGroups($values['group']);
						} else {
							$groups = eF_getTableDataFlat("groups","id","");
							$this->getEditedUser() -> removeGroups($groups['id']);
						}
				    }

				    $this->setMessageVar($message, $message_type);
				} else {
					/** @todo INSERT INTO DEFAULTS USERS TABLE, AND INJECT DETAILS AFTER. */
					$insertionTimestamp = time(); // needed for the rest of the code to now when the insertion took place
					
					// GENERATE LOGIN AND PASSWORD BASED ON NAME AND SURNAME 
					$values['new_login']	= MagesterUser::generateNewLogin($values['name'], $values['surname']);
					
					$roles = MagesterUser::getRoles();
					
					$users_content = array(
						'login' => $values['new_login'],
						'name' => $values['name'],
						'surname' => $values['surname'],
						'active' => $values['active'],
						'email' => $values['email'],
						'password' => $values['password_'],
						'user_type' => $values['user_type'],
						'languages_NAME' => $values['languages_NAME'],
						'timezone' => $values['timezone'],
						'timestamp' => $insertionTimestamp,
						'user_type' => $roles[$values['user_type']],
						'user_types_ID' => $values['user_type']
					);
					
					try {
     					MagesterUser :: createUser($users_content);
     					
     					$this->getEditedUser(true, $values['new_login']);
     					
						if ($values['group']) {
							$group = new MagesterGroup($values['group']);
							$group -> addUsers($values['new_login']);
						}
						
		             	switch ($GLOBALS['configuration']['date_format']) {
							case "YYYY/MM/DD": {
								$date_format = 'Y/m/d'; break;
							}
							case "MM/DD/YYYY": {
								$date_format = 'm/d/Y'; break;
							}
							case "DD/MM/YYYY": 
							default: {
								$date_format = 'd/m/Y'; break;
							}
						}
						
						$values['data_nascimento'] = date_create_from_format($date_format, $values['data_nascimento']);
						
		     			$user_details = array(
		     				'ies_id'			=> $values['ies_id'],
		     				'polo_id'			=> $values['polo_id'],
							'rg'				=> $values['rg'],
							'cpf'				=> $values['cpf'],
							'cep'				=> $values['cep'],
							'endereco'			=> $values['endereco'],
							'numero'			=> $values['numero'],
							'complemento'		=> $values['complemento'],
							'bairro'			=> $values['bairro'],
							'cidade'			=> $values['cidade'],
							'uf'				=> $values['uf'],
							'telefone'			=> $values['telefone'],
							'celular'			=> $values['celular'],
		     				'not_18'			=> $values['not_18']
						);
					    if ($values['data_nascimento']) {
					     	$user_details['data_nascimento']	= $values['data_nascimento']->format('Y-m-d');
					    }
		     
		     			MagesterUserDetails :: injectDetails($this->getEditedUser() -> login, $user_details);

		     			//$this->setMessageVar(_USERCREATED, 'success');
		     			
		     			eF_redirect($this->moduleBaseUrl . "&action=" . self::EDIT_XUSER . "&xuser_login=" . $values['new_login'] . "&message=".urlencode(_USERCREATED) . "&message_type=success");
						
					} catch (Exception $e) {
						$smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
						$message = $e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(\''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
						$message_type = 'failure';
						$this->setMessageVar($message, $message_type);
					}
					/*
					if ($result = eF_insertTableData("module_xuser", $fields)) {
						header("location:".$this -> moduleBaseUrl."&action=" . self::EDIT_XUSER ."&xuser_id=".$result."&message=".urlencode(_MODULE_XUSERS_SUCCESFULLYINSERTEDXUSERENTRY)."&message_type=success&tab=users");
					} else {
						header("location:".$this -> moduleBaseUrl."&action=" . self::ADD_XUSER . "&message=".urlencode(_MODULE_XUSERS_PROBLEMINSERTINGXUSERENTRY)."&message_type=failure");
					}
					*/
				}
            }
            
			if ($selectedAction == self::ADD_XUSER) {
	            $defaults = array(
					'xuser_ID'		=> -1,
	                'ies_id'		=> -1,
	                'polo_id'		=> -1
				);
	        } elseif ($selectedAction == self::EDIT_XUSER) {
	            
				$form -> setDefaults($this->getEditedUser() -> user);
				$form -> setDefaults(MagesterUserDetails::getUserDetails($this->getEditedUser() -> user['login']));
				
				//If the user's type is other than the basic types, set the corresponding select box to point to this one
				if ($this->getEditedUser() -> user['user_types_ID']) {
					$form -> setDefaults(array('user_type' => $this->getEditedUser() -> user['user_types_ID']));
				}
	            
				$xuser_entry = eF_getTableData("module_xuser", "*", "id=".$this->getEditedUser() -> user['id']);

				$defaults = array(
					'xuser_ID'			=> $xuser_entry[0]['id'],
					'ies_polo_id'		=> array($xuser_entry[0]['ies_id'], $xuser_entry[0]['polo_id']),
//					'polo_id'			=> ,
					'nome' 				=> $xuser_entry[0]['nome'],
					'cep'				=> $xuser_entry[0]['cep'],
					'endereco'			=> $xuser_entry[0]['endereco'],
					'numero'			=> $xuser_entry[0]['numero'],
					'complemento'		=> $xuser_entry[0]['complemento'],
					'bairro'			=> $xuser_entry[0]['bairro'],
					'cidade'			=> $xuser_entry[0]['cidade'],
					'uf'				=> $xuser_entry[0]['uf'],
					'telefone'			=> $xuser_entry[0]['telefone'],
					'celular'			=> $xuser_entry[0]['celular'],
					'data_nascimento'	=> $xuser_entry[0]['data_nascimento'],
					'not_18'			=> $xuser_entry[0]['not_18']
				);
			}
	        $form -> setDefaults( $defaults );
	            
	        $rendererBasic = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
	        $form -> accept($rendererBasic);
	        $smarty -> assign('T_MODULE_XUSER_BASIC_FORM', $rendererBasic -> toArray());
	        return true;
        }
        return false;
    }

    private function makeResponsibleForm() {
        // CREATING RESPONSIBLE FORM
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		
		$smarty = $this -> getSmartyVar();
		
		if (
			$selectedAction == self::EDIT_XUSER &&
			$this->getEditedUser()->getType() != "administrator" &&
			!($this->getEditedUser() instanceof MagesterAdministrator) &&
			in_array($this->getEditedUser()->getType(), array_keys($this->getEditedUser()->getStudentRoles()))
		) {
			$resTypes = array(
				'parents' 	=> "Mãe, Pai ou Maior Responsável",
				'financial'	=> "Responsável Financeiro"
			);
			
			$respForms  = array();
			
			foreach($resTypes as $respKey => $respType) {
				$responsibleForm = new HTML_QuickForm("xuser_responsible_entry_form_" . $respKey, "post", $_SERVER['REQUEST_URI'], "", null, true);
	            $responsibleForm -> addElement('hidden', 'xuser_ID');
	            
	  			$responsibleForm -> addElement('text', 'res_telefone', _MODULE_XUSERS_TELEFONE, 'class = "medium" alt="phone"');
	            $responsibleForm -> addElement('text', 'res_celular', _MODULE_XUSERS_CELULAR, 'class = "medium" alt="phone"');
	            $responsibleForm -> addElement('jquerydate', 'res_data_nascimento', _USER_DATA_NASCIMENTO);
	            $responsibleForm -> addElement('text', 'res_rg', _USER_RG, 'class = "medium"');
	            $responsibleForm -> addElement('text', 'res_cpf', _USER_CPF, 'class = "medium" alt="cpf"');
				
				//$responsibleForm -> addElement('select', 'res_type', __XUSER_RESPONSIBLE_TYPE, $resTypes, 'class = "large"');
				$responsibleForm -> addElement('hidden', 'res_type', $respKey);
	            
				$responsibleForm -> addElement('text', 'res_name', _NAME, 'class = "large"');
				$responsibleForm -> addRule('res_name', _THEFIELD.' '._NAME.' '._ISMANDATORY, 'required', null, 'client');
	 			$responsibleForm -> addElement('text', 'res_surname', _SURNAME, 'class = "large"');
	 			$responsibleForm -> addRule('res_surname', _THEFIELD.' '._SURNAME.' '._ISMANDATORY, 'required', null, 'client');
				$responsibleForm -> addElement('text', 'res_email', _EMAILADDRESS, 'class = "large mask-email"');
				$responsibleForm -> addRule('res_email', _THEFIELD.' '._EMAILADDRESS.' '._ISMANDATORY, 'required', null, 'client');
				$responsibleForm -> addRule('res_email', _INVALIDFIELDDATA, 'checkParameter', 'email');
				
				$responsibleForm -> addElement('text', 'res_cep', _MODULE_XUSERS_CEP, 'class = "medium" alt="cep"');
				$responsibleForm -> addElement('text', 'res_endereco', _MODULE_XUSERS_ENDERECO, 'class = "large"');
				$responsibleForm -> addElement('text', 'res_numero', _MODULE_XUSERS_NUMERO, 'class = "small"');
				$responsibleForm -> addElement('text', 'res_complemento', _MODULE_XUSERS_COMPLEMENTO, 'class = "small"');
				$responsibleForm -> addElement('text', 'res_bairro', _MODULE_XUSERS_BAIRRO, 'class = "medium"');
				$responsibleForm -> addElement('text', 'res_cidade', _MODULE_XUSERS_CIDADE, 'class = "medium"');
				
				$stateList = localization::getStateList();
				$responsibleForm -> addElement('select', 'res_uf', _MODULE_XUSERS_UF, $stateList, 'class = "small"');
				
	  			$responsibleForm -> addElement('submit', 'res_submit_xuser', _MODULE_XUSERS_SAVE, 'class = "button_colour round_all"');
				
				if ($responsibleForm -> isSubmitted() && $responsibleForm -> validate()) {
	            	$values = $responsibleForm->exportValues();
					

					
					$fields = array(
						'name' 				=> $values['res_name'],
						'type' 				=> $values['res_type'],
	                    'surname' 			=> $values['res_surname'],
	                    'email' 			=> $values['res_email'],
						'rg'				=> $values['res_rg'],
						'cpf'				=> $values['res_cpf'],
						'telefone'			=> $values['res_telefone'],
						'celular'			=> $values['res_celular'],
						'cep'				=> $values['res_cep'],
						'endereco'			=> $values['res_endereco'],
						'numero'			=> $values['res_numero'],
						'complemento'		=> $values['res_complemento'],
						'bairro'			=> $values['res_bairro'],
						'cidade'			=> $values['res_cidade'],
						'uf'				=> $values['res_uf']
					);
					if ($selectedAction == self::EDIT_XUSER) {
						$result = $this->createResponsibleUserAction(null, $fields);
	
					    $this->setMessageVar($result['message'], $result['message_type']);
					    
					} 
	            }


				$xuser_responsible_entry = eF_getTableData("module_xuser_responsible", "*", sprintf("id=%d AND type = '%s'", $this->getEditedUser() -> user['id'], $respKey));
				
				//var_dump($xuser_responsible_entry);
				
				$defaultsResponsible = array(
					'res_xuser_ID'			=> $this->getEditedUser() -> user['id'],
					'res_type' 				=> $respKey,
					'res_name' 				=> $xuser_responsible_entry[0]['name'],
					'res_surname' 			=> $xuser_responsible_entry[0]['surname'],
					'res_email' 			=> $xuser_responsible_entry[0]['email'],
					'res_data_nascimento'	=> $xuser_responsible_entry[0]['data_nascimento'],
					'res_rg'				=> $xuser_responsible_entry[0]['rg'],
					'res_cpf'				=> $xuser_responsible_entry[0]['cpf'],
					'res_telefone'			=> $xuser_responsible_entry[0]['telefone'],
					'res_celular'			=> $xuser_responsible_entry[0]['celular'],
					'res_cep'				=> $xuser_responsible_entry[0]['res_cep'],
					'res_endereco'			=> $xuser_responsible_entry[0]['res_endereco'],
					'res_numero'			=> $xuser_responsible_entry[0]['res_numero'],
					'res_complemento'		=> $xuser_responsible_entry[0]['res_complemento'],
					'res_bairro'			=> $xuser_responsible_entry[0]['res_bairro'],
					'res_cidade'			=> $xuser_responsible_entry[0]['res_cidade'],
					'res_uf'				=> $xuser_responsible_entry[0]['res_uf']
				);			
				
				$responsibleForm -> setDefaults( $defaultsResponsible );
				
	 			$rendererResponsible = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
	            $responsibleForm -> accept($rendererResponsible);
				
				$respForms[$respKey] = $rendererResponsible -> toArray();
			}		
			$smarty -> assign('T_RESPONSIBLE_TYPES', $resTypes);
			
			$smarty -> assign('T_MODULE_XUSER_BASIC_RESPONSIBLE_FORMS', $respForms);
			
            return true;
		}

		return false;
    }
    
    protected function getDatatableSource() {

		/*
		 * Script:    DataTables server-side script for PHP and MySQL
		 * Copyright: 2010 - Allan Jardine
		 * License:   GPL v2 or BSD (3-point)
		 */
		
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * Easy set variables
		 */
		
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$aColumns = array('login', 'user_type_name', 'courses_num', 'last_login', 'active' );

		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "usr.id";
		
		/* DB table to use */
//		$sTable = "ajax";
		
		/* Database connection information */
//		$gaSql['user']       = "";
//		$gaSql['password']   = "";
//		$gaSql['db']         = "";
//		$gaSql['server']     = "localhost";

		
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
		 * no need to edit below this line
		 */
		
		/* 
		 * MySQL connection
		 */
		/*
		$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
			die( 'Could not open connection to server' );
		
		mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
			die( 'Could not select database '. $gaSql['db'] );
		*/
	
		/*
		 * SQL queries
		 * Get data to display
		 */
			/*
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";
		$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		*/
		
		/* Data set length after filtering */
		/*
		$sQuery = "
			SELECT FOUND_ROWS()
		";
		$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];
		*/
		/* Total data set length */
		/*
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.")
			FROM   $sTable
		";
		$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultTotal = mysql_fetch_array($rResultTotal);
		$iTotal = $aResultTotal[0];
		*/
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		
		$sWhere = $sFixedWhere = "usr.archive = 0";
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere .= " AND (UPPER(login) LIKE UPPER('%".mysql_real_escape_string( $_GET['sSearch'] )."%') OR ";
			$sWhere .= "UPPER(name) LIKE UPPER('%".mysql_real_escape_string( $_GET['sSearch'] )."%') OR ";
			$sWhere .= "UPPER(surname) LIKE UPPER('%".mysql_real_escape_string( $_GET['sSearch'] )."%'))";
		}
		
		/* Individual column filtering */
		/*
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		*/
	   	/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			//$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
					 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			/*
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
			*/
		}
		
	    /* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".mysql_real_escape_string( $_GET['iDisplayLength'] );
		}
		/*
		prepareGetTableData(
			"users", 					// Table 
			"SQL_CALC_FOUND_ROWS *",	// Fields
			"archive = 0",				// Where
			$sOrder,					// Order
			"",							// Group
			$sLimit						// Limit
		);
		*/
		$entifySql = prepareGetTableData(
			"module_xusers_data as usr",	// Table 
			implode(',', $aColumns),		// Fields
			$sWhere,								// Where
			$sOrder,						// Order
			"",								// Group
			$sLimit							// Limit
		);
		
		$entifySource = eF_getTableData(
			"users as usr",	// Table 
			"
			usr.id, usr.login, usr.name, usr.surname, user_type, user_types_ID, user_type, 
			CASE 
    			WHEN usr.user_types_ID IS NULL OR usr.user_types_ID NOT IN (SELECT id FROM user_types) THEN usr.user_type
    			ELSE (SELECT name FROM user_types WHERE usr.user_types_ID = user_types.id)
			END as user_type_name,
			active, 
			(SELECT timestamp FROM logs WHERE usr.login = logs.users_LOGIN AND logs.action = 'login' LIMIT 1) as last_login,
			pending 
			",
			$sWhere,						// Where
			$sOrder,						// Order
			"",								// Group
			$sLimit							// Limit
		);
		
		$entifyDisplayedCount = eF_countTableData(
			"users as usr", 		// Table 
			$sIndexColumn,	// Fields
			$sWhere
		);
		
		$entifyCount = eF_countTableData(
			"users as usr", 		// Table 
			$sIndexColumn,	// Fields
			$sFixedWhere
		);
		
		
		
//		$user_lessons = eF_getTableDataFlat("users_to_lessons as ul, lessons as l", "ul.users_LOGIN, count(ul.lessons_ID) as lessons_num", "ul.lessons_ID=l.id AND l.archive=0", "", "ul.users_LOGIN");
		$user_courses = eF_getTableDataFlat("users_to_courses as uc, courses as c", "uc.users_LOGIN, count(uc.courses_ID) as courses_num", "uc.courses_ID=c.id AND c.archive=0", "", "uc.users_LOGIN");
//		$user_groups = eF_getTableDataFlat("users_to_groups", "users_LOGIN, count(groups_ID) as groups_num", "", "", "users_LOGIN");
//		$user_lessons = array_combine($user_lessons['users_LOGIN'], $user_lessons['lessons_num']);
		$user_courses = array_combine($user_courses['users_LOGIN'], $user_courses['courses_num']);
//		$user_groups = array_combine($user_groups['users_LOGIN'], $user_groups['groups_num']);
//		array_walk($users, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["lessons_num"] = $s[$v["login"]] : $v["lessons_num"] = 0;'), $user_lessons); //Assign lessons number to users array (this way we eliminate the need for an expensive explicit loop)
		array_walk($entifySource, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["courses_num"] = $s[$v["login"]] : $v["courses_num"] = 0;'), $user_courses);
//		array_walk($users, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["groups_num"] = $s[$v["login"]] : $v["groups_num"] = 0;'), $user_groups);
/*
		$result = eF_getTableDataFlat("logs", "users_LOGIN, timestamp", "action = 'login'", "timestamp");
		$last_logins = array_combine($result['users_LOGIN'], $result['timestamp']);
		array_walk($entifySource, create_function('&$v, $k, $s', '$s[$v["login"]] ? $v["last_login"] = $s[$v["login"]] : $v["last_login"] = null;'), $last_logins);
*/		
		// DO MULTI-SORT HERE, IN CASE OF CANT BE ON MYSQL		
		
/*			            
		foreach ($users as $key => $value) {
			$users[$key]['last_login'] = $lastLogins[$value['login']];
			if (isset($_COOKIE['toggle_active'])) {
				if (($_COOKIE['toggle_active'] == 1 && !$value['active']) || ($_COOKIE['toggle_active'] == -1 && $value['active'])) {
					unset($users[$key]);
				}
			}
		}
*/	
	//			$smarty -> assign("T_USERS_SIZE", sizeof($users));
	
		//$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());
		//$smarty -> assign("T_XUSERS", $users);
	    //$smarty -> assign("T_XROLES", MagesterUser :: getRoles(true));
	
		
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
		//	"sSql"	=> $entifySql,
			"iTotalRecords" => intval($entifyCount[0]['count']),
			"iTotalDisplayRecords" => intval($entifyDisplayedCount[0]['count']),
			"aaData" => array()
		);
		
		$magesterRoles = MagesterUser :: getRoles(true);
		
		//var_dump(G_CURRENTTHEMEURL, G_CURRENTTHEMEPATH);

		$activeString = '
			<button class="%2$s skin_colour round_all activateUserLink" title = "%1$s" %3$s>
			<img 
				class = "ajaxHandle" 
				src = "/' . G_CURRENTTHEMEURL . '/images/icons/small/white/alert_2.png"
				width="16" 
				height="16"
				alt = "%1$s" 
				>
			</button>';
		
		$canChange = false;
		if (
			!isset($this->getCurrentUser()->coreAccess['users']) || 
			$this->getCurrentUser()->coreAccess['users'] == 'change'
		) {
			$canChange = true;
		}
		
		foreach($entifySource as $entifyRow) {
			$row = array();
			$row["DT_RowId"] 	= "user_" . $entifyRow['id'];
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{	
				switch($aColumns[$i]) {
					case "login" : {
						$url = $this->moduleBaseUrl . "&action=edit_xuser&xuser_login=" . $entifyRow[ 'login' ];
						
						$row["login"] = sprintf(
							'<a href = "%s" class = "editLink" %s>%s</a>',
							$url,
							$entifyRow['active'] != 1 ? 'style="color:red;"' : '',
							formatLogin(null, $entifyRow)
						);
						break;
					}
					case "user_type_name" : {
						$row["user_type_name"] = is_null($entifyRow[ "user_types_ID" ]) || $entifyRow[ "user_types_ID" ] == 0 ?
							$magesterRoles[$entifyRow[ "user_type" ]] :
							$magesterRoles[$entifyRow[ "user_types_ID" ]];

						break;
					}
					case "last_login" : {
						$row["last_login"] = is_null($entifyRow[ $aColumns[$i] ]) ? _NEVER : date('d/m/Y \à\s H:i:s', $entifyRow[ $aColumns[$i] ]);
						break;
					}
					case "active" : {
						//$row[] = $entifyRow[ $aColumns[$i] ];
						if ($entifyRow[ $aColumns[$i] ] == 1) { // ACTIVE
							$row["active"] = sprintf(
								$activeString, 
								_DEACTIVATE, 
								"green",
								($canChange && $this->getCurrentUser()->user['login'] != $entifyRow['login']) ? 
									'onclick = "xuser_activateUser(this, \'' . $entifyRow['login'] . '\'); "' : 
									"" 
							);
						} else {
							$row["active"] = sprintf(
								$activeString, 
								_ACTIVATE, 
								"red",
								($canChange && $this->getCurrentUser()->user['login'] != $entifyRow['login']) ? 
									'onclick = "xuser_activateUser(this, \'' . $entifyRow['login'] . '\'); "' : 
									"" 
							);
						}
						break;
					}
					default : {
						$row[$aColumns[$i]] = $entifyRow[ $aColumns[$i] ];
						break;
					}
				}
			}
//			$row["DT_RowClass"] = "TESTE";
			$output['aaData'][] = $row;
		}
		header("Content-Type: application/javascript");

		//usort($output['aaData'], create_function('$first, $last', 'return $first["user_type_name"] < $last["user_type_name"] ? -1 : 1;'));

		echo json_encode( $output );
		exit;
    }

    /* Data Model functions */ 
	public function getUserById($userID) {
		$userData = eF_getTableData("users", "login", "id = " . $userID);
		
		if ($userData) {
			return MagesterUserFactory::factory($userData[0]['login']);
		} else {
			return false;
		}
	}
	public function getExtendedTypeID($userObject) {
		if (is_null(self::$roles)) {
			$roles = eF_getTableDataFlat("user_types", "*", "active=1"); //Get available roles
			self::$roles = array_combine($roles['id'], $roles['extended_user_type']);
		}
		
		return $userObject->user['user_types_ID'] != 0 ? self::$roles[$userObject->user['user_types_ID']] : $userObject->user['user_type'];
	}
	
	public function getExtendedTypeIDInCourse($userObject, $courseObject) {
		if (is_null(self::$roles)) {
			$roles = eF_getTableDataFlat("user_types", "*", "active=1"); //Get available roles
			self::$roles = array_combine($roles['id'], $roles['extended_user_type']);
		}
		$userType = $userObject->getUserTypeInCourse($courseObject);
	
		return $userType != 0 ? self::$roles[$userType] : $userType;
	}
	
	public function getUserClasses($userID = null) {
		if (is_null($userID)) {
			$user = $this->getCurrentUser();
		} else {
			$user = $this->getUserById($userID);
		}
		$userClasses = $user->getUserCoursesClasses();
		
		return $userClasses;
	}
	
	public function getUserTags($user) {
		if (is_string($user)) {
			$user = MagesterUserFactory::factory($user);
		}
		if ($user instanceof MagesterUser) {
			$userID = $user->user['id'];
		}
		if (is_array($user)) {
			$userID = $user['id'];
		}
		if (is_numeric($user)) {
			$userID = $user;
		}
    	if (is_null($userID)) {
    		return array();
    	}
    	
    	$userLogin = eF_getTableData("users", "login", "id = $userID");
    	
    	$login = $userLogin[0]['login'];
    	
    	$user = MagesterUserFactory::factory($login);
    	
    	$scopes = $this->loadModule("xentify")->getScopesForUser($user);
    	$tags = $this->loadModule("xentify")->getTagsForScopes($scopes);
    	
    	if (count($tags) == 0) {
    		// RETURN DEFAULT TAGS
    		$tags = array("is_user_default", "is_not_custom");
    	}
    	
    	return $tags;
	}

	public function getUserDetails($userID, $user_details_type = 'self') {
		if ($user_details_type == 'parent') {
			$user_details_type = 'parents';
		}
		if ($user_details_type == "self" || $user_details_type == "student") {
			$respData = eF_getTableData(
				"module_xuser det JOIN users u ON (det.id = u.id)",
				"u.name, u.surname, det.*",
				sprintf("u.id = %d", $userID)
			);
		} else {
			$respData = eF_getTableData(
				"module_xuser_responsible det JOIN users u ON (det.id = u.id)", 
				"u.name, u.surname, det.*",
				sprintf("u.id = %d AND det.type = '%s'", $userID, $user_details_type)
			);
		}
		if (count($respData) > 0) {
			return reset($respData);
		}
		return false;
	}
	public function getUserIes() {
		
	}
}
?>