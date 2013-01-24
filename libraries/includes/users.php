<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
	exit;
}

/*

   if (isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] == 'hidden' && $currentUser -> user['login'] != $_GET['edit_user']) {

   eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");

   }

   !isset($currentUser -> coreAccess['users']) || $currentUser -> coreAccess['users'] == 'change' ? $_change_ = 1 : $_change_ = 0;

   $smarty -> assign("_change_", $_change_);

 */
if (isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] == 'hidden') {
	eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
}
$loadScripts[] = 'includes/users';
if (isset($_GET['delete_user']) && eF_checkParameter($_GET['delete_user'], 'login')) { //The administrator asked to delete a user
	try {
		if (isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] != 'change') {
			throw new Exception(_UNAUTHORIZEDACCESS);
		}
		$user = MagesterUserFactory :: factory($_GET['delete_user']);
		if (G_VERSIONTYPE == 'enterprise') {
			$user -> aspects['hcd'] -> delete();
		}
		$user -> delete();
	} catch (Exception $e) {
		handleAjaxExceptions($e);
	}
	exit;
} elseif (isset($_GET['archive_user']) && eF_checkParameter($_GET['archive_user'], 'login')) { //The administrator asked to delete a user
	try {
		if (isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] != 'change') {
			throw new Exception(_UNAUTHORIZEDACCESS);
		}
		$user = MagesterUserFactory :: factory($_GET['archive_user']);
		if (G_VERSIONTYPE == 'enterprise') {
			//$user -> aspects['hcd'] -> delete();
		}
		$user -> archive();
	} catch (Exception $e) {
		handleAjaxExceptions($e);
	}
	exit;
} elseif (isset($_GET['deactivate_user']) && eF_checkParameter($_GET['deactivate_user'], 'login') && ($_GET['deactivate_user'] != $_SESSION['s_login'])) { //The administrator asked to deactivate a user
	if (isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] != 'change') {
		echo urlencode(_UNAUTHORIZEDACCESS);exit;
	}
	try {
		$user = MagesterUserFactory :: factory($_GET['deactivate_user']);
		$user -> deactivate();
		echo "0";
	} catch (Exception $e) {
		handleAjaxExceptions($e);
	}
	exit;
} elseif (isset($_GET['activate_user']) && eF_checkParameter($_GET['activate_user'], 'login')) { //The administrator asked to activate a user
	if (isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] != 'change') {
		echo urlencode(_UNAUTHORIZEDACCESS);exit;
	}
	try {
		$user = MagesterUserFactory :: factory($_GET['activate_user']);
		$user -> activate();
		echo "1";
	} catch (Exception $e) {
		handleAjaxExceptions($e);
	}
	exit;
} elseif (isset($_GET['add_user']) || (isset($_GET['edit_user']) && $login = eF_checkParameter($_GET['edit_user'], 'login'))) { //The administrator asked to add a new user or to edit a user
	$smarty -> assign("T_PERSONAL", true);
	/**Include the personal settings file*/
	include 'includes/personal.php'; //User addition and manipulation is done through personal.
} else { //The admin just asked to view the users

	if ($currentUser -> getType() == "administrator") {
		//eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=module&op=module_xuser");
		//exit;
	}

	if (isset($_GET['ajax'])) {
		$limit  = (isset($_GET['limit']) && eF_checkParameter($_GET['limit'], 'uint'))  ? $_GET['limit']  : G_DEFAULT_TABLE_SIZE;
		$sort   = (isset($_GET['sort']) && eF_checkParameter($_GET['sort'], 'text'))    ? $_GET['sort']   : 'login';     
		$order  = (isset($_GET['order']) && $_GET['order'] == 'desc')                   ? 'desc'          : 'asc';       
		$offset = (isset($_GET['offset']) && eF_checkParameter($_GET['offset'], 'int')) ? $_GET['offset'] : 0;           

		$languages = MagesterSystem::getLanguages(true);                                                                 
		$smarty->assign("T_LANGUAGES", $languages);                                                                      


		$where = array();                                                                                                
		$where[] = "u.archive = 0";                                                                                      
		if (isset($_GET['filter'])) {                                                                                    
			$searchFor = eF_addSlashes($_GET['filter']);                                                                 
			$searchFor = explode(" ", eF_addSlashes($_GET['filter']));                                                   
			foreach ($searchFor as $eachName) {                                                                          
				$where[] = "(u.login LIKE '%$eachName%' OR u.email LIKE '%$eachName%' OR                                    
					u.name LIKE '%$eachName%' OR u.surname LIKE '%$eachName%')";                                
			}                                                                                                            
		}                                                                                                                
		$where = implode(" AND ", $where);                                                                               

		$sql = "SELECT DISTINCT                                                                                          
			u.*,                                                                                                    
			(SELECT count(ul.lessons_ID) FROM users_to_lessons as ul, lessons as l WHERE                            
			 ul.lessons_ID=l.id AND ul.archive=0 AND l.archive=0 AND ul.users_LOGIN = u.login) as lessons_num,
			(SELECT count(uc.courses_ID) FROM users_to_courses as uc, courses as c WHERE                            
			 uc.courses_ID=c.id AND uc.archive=0 AND c.archive=0 AND uc.users_LOGIN = u.login) as courses_num,
			(SELECT count(ug.groups_ID) FROM users_to_groups as ug WHERE                                            
			 ug.users_LOGIN = u.login) as groups_num,                                                            
			(SELECT logs.timestamp FROM logs WHERE                                                                  
			 logs.action='login' and logs.users_LOGIN=u.login order by logs.timestamp desc limit 1) as last_login
				FROM                                                                                                        
				users u                                                                                                 
				WHERE                                                                                                       
				$where                                                                                                  
				ORDER BY                                                                                                    
				$sort                                                                                                   
				LIMIT                                                                                                       
				$offset, $limit";                                                                                       
				$users = $GLOBALS['db']->GetAll($sql);                                                                              

		$totalUsersSql = "SELECT count(*) as totalUsers FROM users u WHERE $where";                                      
		$totalUsers = $GLOBALS['db']->GetAll($totalUsersSql);                                                            

		/* Como Assim? */                                                                                                
		// foreach ($users as $key => $value) {                                                                          
		//     if (isset($_COOKIE['toggle_active'])) {                                                                   
		//         if (($_COOKIE['toggle_active'] == 1 && !$value['active']) || ($_COOKIE['toggle_active'] == -1 && $value['active'])) {
		//             unset($users[$key]);                                                                              
		//         }                                                                                                     
		//     }                                                                                                         
		// }                                                                                                             

		//$users = eF_multiSort($users, $sort, $order);                                                                  
		$smarty->assign("T_USERS_SIZE", $totalUsers[0]['totalUsers']);                                                   

		$smarty -> assign("T_USERS", $users);                                                                            
		$smarty -> assign("T_ROLES", MagesterUser::getRoles(true));
		$smarty -> display('administrator.tpl');                                                                         
		exit;    
	}

}
