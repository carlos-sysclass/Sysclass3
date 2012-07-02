<?php
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}
if ($currentUser -> coreAccess['dashboard'] == 'hidden') {
 eF_redirect($_SESSION['s_type'].".php");
}
!isset($currentUser -> coreAccess['users']) || $currentUser -> coreAccess['users'] == 'change' ? $_change_ = 1 : $_change_ = 0;
$smarty -> assign("_change_", $_change_);
//error_reporting(E_ALL);
//print_r($_POST);print_r($_GET);
//print_r($_FILES);
$loadScripts[] = 'jquery/jquery.1.5.2.min';
$loadScripts[] = 'includes/personal';
// Set facebook template variables
/***************************************************************/
/*** Check the user type and define the currentUser instance ***/
/***************************************************************/
if (isset($currentUser -> login) && $_SESSION['s_password']) {
 try {
  // The factory takes care for the definition of the HCD user type in $currentUser -> aspects['hcd']
  if (!($currentUser instanceOf MagesterUser)) {
   $currentUser = MagesterUserFactory :: factory($currentUser -> login);
  }
  $currentEmployee = $currentUser -> aspects['hcd'];
 } catch (MagesterException $e) {
  $message = $e -> getMessage().' ('.$e -> getCode().')';
  eF_redirect("index.php?message=".urlencode($message)."&message_type=failure");
  exit;
 }
} else {
	echo 2;
	echo("index.php?message=".urlencode(_YOUCANNOTACCESSTHISPAGE)."&message_type=failure");
 //eF_redirect("index.php?message=".urlencode(_YOUCANNOTACCESSTHISPAGE)."&message_type=failure");
 exit;
}

if (isset($_GET['add_evaluation']) || isset($_GET['edit_evaluation'])) {
} else {
  $_GET['edit_user'] = $currentUser -> login;
  $editedUser = $currentUser;
  $editedEmployee = $currentUser -> aspects['hcd'];

 $smarty -> assign("T_LOGIN", $_GET['edit_user']);
 $smarty -> assign("T_EDITEDUSER", $editedUser);
 //Set the avatar
 try {
  $avatarsFileSystemTree = new FileSystemTree(G_SYSTEMAVATARSPATH);
  foreach (new MagesterFileTypeFilterIterator(new MagesterFileOnlyFilterIterator(new MagesterNodeFilterIterator(new RecursiveIteratorIterator($avatarsFileSystemTree -> tree, RecursiveIteratorIterator :: SELF_FIRST))), array('png')) as $key => $value) {
   $systemAvatars[basename($key)] = basename($key);
  }
  $smarty -> assign("T_SYSTEM_AVATARS", $systemAvatars);
 } catch (Exception $e) {
  $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
  $message = $e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(\''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
 }
 /**

	 * The avatar form has changed since 3.6.0.

	 * In the personal mode it is a part of the user profile tab and contains other information as well

	 * which are submitted through it.

	 */

 
  $baseUrl = "ctg=personal";
  $smarty -> assign("T_PERSONAL_CTG", 1);


  if (!isset($_GET['op'])) {
   $_GET['op'] = 'dashboard';
  }
  
  $options = array(array('image' => '16x16/generic.png', 'title' => _EDITUSER, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=account', 'selected' => isset($_GET['op']) && $_GET['op'] == 'account' ? true : false),
  array('image' => '16x16/user_timeline.png', 'title' => _LEARNINGSTATUS, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=status' , 'selected' => isset($_GET['op']) && $_GET['op'] == 'status' ? true : false));
  $titles = array ( "account" => array("edituser" => _EDITUSER,
            "profile" => _USERPROFILE,
            "mapped" => _ADDITIONALACCOUNTS,
  			"details"	=> _PERSONALDETAILS,
            "placements" => _PLACEMENTS,
            "history" => _HISTORY,
            "files" => _FILERECORD,
            "payments" => _PAYMENTS),
        "status" => array("lessons" => _LESSONS,
           "courses" => _COURSES,
             "groups" => _GROUPS,
  			"courseclasses" => _COURSESCLASSES,
           "certifications"=> _CERTIFICATIONS));
 
 $smarty -> assign("T_OP",$_GET['op']);
 $smarty -> assign("T_TABLE_OPTIONS", $options);
 $smarty -> assign("T_TITLES", $titles);
 // If in personal mode then include the user profile fields
 if ($personal_profile_form) {
  if (!($GLOBALS['configuration']['social_modules_activated'] & SOCIAL_FUNC_USERSTATUS)) {
   if ($currentUser -> coreAccess['dashboard'] == 'hidden') {
    $smarty -> assign("T_HIDE_USER_STATUS", 1);
   }
  }
 }
 //Get the dashboard innertables
 if (!isset($_GET['add_user']) && ($editedUser -> login == $currentUser -> login)) {
  $loadScripts[] = 'scriptaculous/dragdrop';
  require_once 'dashboard.social.php';
 }
 /** Get the skill list by ajax **/
 $edit_user= $_GET['edit_user'];
 $courseUser = $editedUser;
 
}