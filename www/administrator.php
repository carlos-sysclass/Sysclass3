<?php
/**
 * Administrator main page
 * This page performs all administrative functions.
 * @package SysClass
 * @version 3.6.0
 */
session_cache_limiter('none'); //Initialize session
session_start();
$path = "../libraries/"; //Define default path
//$_GET['theme'] = 'SysClass';

/** The configuration file. */
require_once $path . "configuration.php";
$benchmark = new MagesterBenchmark($debug_TimeStart);
$benchmark->set('init');
//Set headers in order to eliminate browser cache (especially IE's)
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//pr($_SESSION);
//If the page is shown as a popup, make sure it remains in such mode
if (!isset($_GET['reset_popup']) && (isset($_GET['popup']) || isset($_POST['popup']) || (isset($_SERVER['HTTP_REFERER']) && strpos(strtolower($_SERVER['HTTP_REFERER']), 'popup') !== false && strpos(strtolower($_SERVER['HTTP_REFERER']), 'reset_popup') === false && !strpos(strtolower($_SERVER['HTTP_REFERER']), 'evaluation')))) {
	output_add_rewrite_var('popup', 1);
	$smarty->assign("T_POPUP_MODE", true);
	$popup = 1;
}
$message = $message_type = $search_message = ''; //Initialize messages, because if register_globals is turned on, some messages will be displayed twice
$load_editor = false;
$loadScripts = array();
$loadStylesheets = array();
try {
	$currentUser = MagesterUser :: checkUserAccess('administrator');
	$smarty->assign("T_CURRENT_USER", $currentUser);
} catch (Exception $e) {
	if ($e->getCode() == MagesterUserException :: USER_NOT_LOGGED_IN) {
		setcookie('c_request', http_build_query($_GET), time() + 300);
	}
	eF_redirect("index.php?message=" . urlencode($message = $e->getMessage() . ' (' . $e->getCode() . ')') . "&message_type=failure", true);
	exit;
}

if (!isset($_GET['ajax']) && !isset($_GET['postAjaxRequest']) && !isset($popup) && !isset($_GET['tabberajax'])) {
	$_SESSION['previousMainUrl'] = $_SERVER['REQUEST_URI'];
}

if (isset($_COOKIE['c_request']) && $_COOKIE['c_request']) {
	setcookie('c_request', '', time() - 86400);
	if (mb_strpos($_COOKIE['c_request'], '.php') !== false) {
		eF_redirect("" . $_COOKIE['c_request']);
	} else {
		eF_redirect("" . $_SESSION['s_type'] . '.php?' . $_COOKIE['c_request']);
	}
}

$loadedModules = $currentUser->getModules();
$module_css_array = array();
$module_js_array = array();
$module_append_templates = array();

// Include module languages
foreach ($loadedModules as $module) {
	// The $setLanguage variable is defined in globals.php
	$mod_lang_file = $module->getLanguageFile($setLanguage);
	if (is_file($mod_lang_file)) {
		require_once $mod_lang_file;
	}

	// Get module css
	if ($mod_css_file = $module->getModuleCSS()) {
		if (is_file($mod_css_file)) {

			// Get the relative path
			if ($position = strpos($mod_css_file, "modules")) {
				$mod_css_file = substr($mod_css_file, $position);
			}
			$module_css_array[] = $mod_css_file;
		}
	}

	// Get module js
	if ($mod_js_file = $module->getModuleJS()) {
		if (is_file($mod_js_file)) {
			// Get the relative path
			if ($position = strpos($mod_js_file, "modules")) {
				$mod_js_file = substr($mod_js_file, $position);
			}

			$module_js_array[] = $mod_js_file;
		}
	}

	// Run onNewPageLoad code of the module (if such is defined)
	$module->onNewPageLoad();
}


/* Added Session variable for search results */
$_SESSION['referer'] = $_SERVER['REQUEST_URI'];

/* Horizontal menus */
if ($GLOBALS['currentTheme']->options['sidebar_interface'] == 1 || $GLOBALS['currentTheme']->options['sidebar_interface'] == 2) {
	// Used inside new_sidebar_frame to opt out code
	$horizontal_inframe_version = true;
	include "new_sidebar.php";
}

//include "new_sidebar.php";
//var_dump($GLOBALS['currentTheme'] -> options['sidebar_interface']); 
!isset($_GET['ctg']) ? $ctg = "control_panel" : $ctg = $_GET['ctg'];

$smarty->assign("T_CTG", $ctg); //As soon as we derive the current ctg, assign it to smarty.
$smarty->assign("T_OP", isset($_GET['op']) ? $_GET['op'] : false);

//Create shorthands for user type, to avoid long variable names
$_student_ = $_professor_ = $_admin_ = 0;
if ((isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] == 'student') || (!isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_type'] == 'student')) {
	$_student_ = 1;
} else if ((isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] == 'professor') || (!isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_type'] == 'professor')) {
	$_professor_ = 1;
} else {
	$_admin_ = 1;
}
$smarty->assign("_student_", $_student_);
$smarty->assign("_professor_", $_professor_);
$smarty->assign("_admin_", $_admin_);

try {
	if ($ctg == 'control_panel') {
		/*		 * */
		require_once ("control_panel.php");
	} elseif ($ctg == 'landing_page') {
		/*		 * */
		require_once ("landing_page.php");
	} elseif ($ctg == 'social') {
		/*		 * */
		require_once ("social.php");
	} elseif ($ctg == 'languages') {
		/*		 * */
		require_once ("languages.php");
	} elseif ($ctg == 'forum') {
		/*		 * */
		require_once("includes/forum.php");
	} elseif ($ctg == 'messages') {
		/*		 * */
		require_once("includes/messages.php");
	} elseif ($ctg == 'chat') {
		/*		 * */
		require_once("includes/chat.php");
	} elseif ($ctg == 'backup') {
		/*		 * */
		require_once ("backup.php");
	} elseif ($ctg == 'news') {
		/*		 * */
		require_once ("news.php");
	} elseif ($ctg == 'user_profile') {
		/*		 * */
		require_once ("user_profile.php");
	} elseif ($ctg == 'import_export') {
		/*		 * */
		require_once ("import_export.php");
	} elseif ($ctg == 'system_config') {
		/*		 * */
		require_once ("includes/system_config.php");
	} elseif ($ctg == 'personal') {
		/*		 * This part is used to display the user's personal information */
		include "includes/personal.php";
	} elseif ($ctg == 'maintenance') {
		/*		 * */
		require_once ("maintenance.php");
	} elseif ($ctg == 'versionkey') {
		/*		 * */
		require_once ("versionkey.php");
	} elseif ($ctg == 'curriculums') {
		/*		 * */
		require_once ("curriculums.php");
	} elseif ($ctg == 'payments') {
		/*		 * */
		require_once ("payments.php");
	} elseif ($ctg == 'modules') {
		/*		 * */
		require_once ("includes/modules.php");
	} elseif ($ctg == 'users') {
		/*		 * */
		require_once ("users.php");
	} elseif ($ctg == 'lessons') {
		/*		 * */
		require_once ("lessons.php");
	} elseif ($ctg == 'directions') {
		/*		 * */
		require_once "categories.php";
	} elseif ($ctg == 'archive') {
		/*		 * */
		require_once "archive.php";
	} elseif ($ctg == 'courses') {
		/*		 * */
		require_once "courses.php";
	} elseif ($ctg == "file_manager") {
		//This page has a file manager, so bring it on with the correct options
		$basedir = $currentUser->getDirectory();
		//Default options for the file manager
		$options = array('share' => false,
			'lessons_ID' => false,
			'metadata' => 0);
		//Default url for the file manager
		$url = basename($_SERVER['PHP_SELF']) . '?ctg=file_manager';
		$extraFileTools = array(array('image' => 'images/16x16/arrow_right.png', 'title' => _INSERTEDITOR, 'action' => 'insert_editor'));

		include "file_manager.php";
	} elseif ($ctg == 'logout_user') {

		// Done here to include administrator.php access control
		if ($_GET['ajax'] == "ajax" && eF_checkParameter($_GET['user'], "login")) {
			$user = MagesterUserFactory :: factory($_GET['user']);
			$user->logout();
			exit;
		}

		/*		 * */
		require_once 'logout_user.php';
	} elseif ($ctg == 'user_types') {
		/*		 * */
		require_once 'user_types.php';
	} elseif ($ctg == 'user_groups') {
		/*		 * */
		require_once 'includes/groups.php';
	} elseif ($ctg == 'calendar') {
		if ($currentUser->coreAccess['calendar'] != 'hidden' && $GLOBALS['configuration']['disable_calendar'] != 1) {
			/*			 * */
			require_once "calendar.php";
		} else {
			eF_redirect("" . basename($_SERVER['PHP_SELF']) . "?ctg=control_panel&message=" . urlencode(_UNAUTHORIZEDACCESS) . "&message_type=failure");
		}
	} elseif ($ctg == 'search_courses') {
		/*		 * Search courses is used to find the course users that fulfill an arbitrary number of criteria */
		require_once "search_courses.php";
	} elseif ($ctg == 'search_users') {
		
	} elseif ($ctg == 'digests') {
		/** Email digests feature */
		require_once "digests.php";
	} elseif ($ctg == 'statistics') {
		if (isset($currentUser->coreAccess['statistics']) && $currentUser->coreAccess['statistics'] == 'hidden') {
			eF_redirect("" . basename($_SERVER['PHP_SELF']) . "?ctg=control_panel&message=" . urlencode(_UNAUTHORIZEDACCESS) . "&message_type=failure");
		}
		/** Statistics is the page that calculates and displays the system statistics. */
		require_once "statistics.php";
	} elseif ($ctg == 'module_hcd') {
		/*		 * */
		require_once "module_hcd.php";
	} elseif ($ctg == 'themes') {
		/*		 * */
		require_once "themes.php";
	} else if ($ctg == 'tests') {
		/*		 * */
		require_once "module_tests.php";
		/*		 * Ranking tests */
		require_once "tests.php";
	} else if ($ctg == 'facebook') {
		/*		 * */
		require_once "module_facebook.php";
	} elseif ($ctg == 'module') {
		/*		 * */
		require_once "module.php";
	}

	/*

	  $fields_log = array ('users_LOGIN' => $_SESSION['s_login'],                                 //This is the log entry array

	  'timestamp'   => time(),

	  'action'      => 'lastmove',

	  'comments'    => 0,

	  'session_ip'  => eF_encodeIP($_SERVER['REMOTE_ADDR']));

	  eF_deleteTableData("logs", "users_LOGIN='".$_SESSION['s_login']."' AND action='lastmove'"); //Only one lastmove action interests us, so delete any other

	  eF_insertTableData("logs", $fields_log);

	 */
} catch (Exception $e) {
	$smarty->assign("T_EXCEPTION_TRACE", $e->getTraceAsString());
	$message = $e->getMessage() . ' (' . $e->getCode() . ') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(\'' . _ERRORDETAILS . '\', 2, \'error_details\')">' . _MOREINFO . '</a>';
	$message_type = 'failure';
}
$smarty->assign("T_HEADER_EDITOR", $load_editor); //Specify whether we need to load the editor
if (isset($_GET['refresh']) || isset($_GET['refresh_side'])) {
	$smarty->assign("T_REFRESH_SIDE", "true");
}
/*

 * Check if you should input the JS code to

 * trigger sending the next notificatoin emails

 * Since 3.6.0

 */
if (class_exists("MagesterNotification") && MagesterNotification::shouldSendNextNotifications()) {
	$smarty->assign("T_TRIGGER_NEXT_NOTIFICATIONS_SEND", 1);
	$_SESSION['send_next_notifications_now'] = 0; // the msg that triggered the immediate send should be sent now
}
///MODULES5
$smarty->assign("T_MODULE_CSS", $module_css_array);
$smarty->assign("T_MODULE_CSS_STRING", implode(",",$module_css_array));
$smarty->assign("T_MODULE_JS", $module_js_array);
$smarty->assign("T_MODULE_JS_STRING", implode(",",$module_js_array));
foreach ($loadedModules as $module) {
	$loadScripts = array_merge($loadScripts, $module->addScripts());
	$loadStylesheets = array_merge($loadStylesheets, $module->addStylesheets());
}
//Main scripts, such as prototype
$mainScripts = getMainScripts();
$smarty->assign("T_HEADER_MAIN_SCRIPTS", implode(",", $mainScripts));
//Operation/file specific scripts
$loadScripts = array_diff($loadScripts, $mainScripts); //Clear out duplicates
$smarty->assign("T_HEADER_LOAD_SCRIPTS", implode(",", array_unique($loadScripts))); //array_unique, so it doesn't send duplicate entries

$smarty->assign("T_HEADER_LOAD_STYLESHEETS", implode(",", array_unique($loadStylesheets))); //array_unique, so it doesn't send duplicate entries

$smarty->assign("T_CURRENT_CTG", $ctg);
$smarty->assign("T_MENUCTG", $ctg);
//$smarty -> assign("T_MENU", eF_getMenu());
//$smarty -> assign("T_QUERIES", $numberOfQueries);
$smarty->assign("T_MESSAGE", $message);
$smarty->assign("T_MESSAGE_TYPE", $message_type);
$smarty->assign("T_SEARCH_MESSAGE", $search_message);
$smarty->assign("T_TEST_MESSAGE", 'Test Message');

$module_append_templates = array();
// Include module languages
foreach ($loadedModules as $module) {
	// Get append finish loading blocks
	$result = $module->onPageFinishLoadingSmartyTpl();
	if ($result !== FALSE) {
		$module_append_templates[] = $result;
	}
}
$smarty->assign("T_TO_APPEND_TEMPLATES", $module_append_templates);

$userRoles = MagesterUser :: GetRoles(true);
$smarty->assign("T_ROLES_ARRAY", $userRoles);
$smarty->assign("T_CURRENT_USER_TYPE", $userRoles[$currentUser->getType()]);


// Verifica se modulo chat esta ativo
$modulesUserOn = $currentUser->getModules();
$listModules = array_keys($modulesUserOn);

if( in_array("module_xlivechat", $listModules)) {
 	
	$userOnline = MagesterUser::getUsersOnline();
	
	foreach ($userOnline as $_useron ) {
			if ($_useron['login'] == "suporteult"){
	 		$viewLink = 1;
	 	} else {
	 		$viewLink = null;
	 	}
	}
}

$smarty->assign("T_CHECK_VIEW_LINK_CHAT", $viewLink);


// ASSIGN USER AVATAR
$user_avatar = array();
try {
	$file = new MagesterFile($currentUser->user['avatar']);
	list($user_avatar['width'], $user_avatar['height']) = eF_getNormalizedDims($file['path'], 80, 50);
	$user_avatar['avatar'] = $currentUser->user['avatar'];
} catch (MagesterFileException $e) {
	$user_avatar = array(
		'avatar' => G_SYSTEMAVATARSPATH . "unknown_small.png",
		//'width'		=> 80,
		'height' => 50
	);
}
$smarty->assign("T_CURRENT_USER_AVATAR", $user_avatar);

$benchmark->set('script');
$smarty->display('administrator.tpl');
$benchmark->set('smarty');
$benchmark->stop();
$output = $benchmark->display();
if (G_DEBUG) {
	echo $output;
}
?>
