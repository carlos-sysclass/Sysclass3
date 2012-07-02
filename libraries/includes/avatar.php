<?php
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}


$loadScripts[] = 'js/jquery';
//$loadScripts[] = 'includes/personal';


 /****************************************************************************************************************************************************/
 /************************************************* ADD USER OR EDIT USER ****************************************************************************/
 /****************************************************************************************************************************************************/
 /************************************************* Create $editedUser, [HCD] $editedEmployee in case of submit *************************************************/
 //If the user is not specified through the get parameter, it means that a user with no priviledges is changing his own personal settings.
 if (!isset($_GET['edit_user'])) {
  $_GET['edit_user'] = $currentUser -> login;
  $editedUser = $currentUser;
 } 
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

  if ($editedUser -> user['login'] == $currentUser -> user['login']) { //The user is editing himself
  if ($currentUser -> getType() == "administrator") {
   $form = new HTML_QuickForm("set_avatar_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=users&edit_user=".$currentUser -> user['login']."&tab=my_profile&op=account", "", null, true);
   $baseUrl = "ctg=users&edit_user=".$currentUser -> user['login'];
  } else {
   $form = new HTML_QuickForm("set_avatar_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=personal&tab=my_profile&op=account", "", null, true);
   $baseUrl = "ctg=personal";
  }
  $smarty -> assign("T_PERSONAL_CTG", 1);
  if ($GLOBALS['configuration']['social_modules_activated'] > 0) {
   $personal_profile_form = 1;
   $systemAvatars = array_merge(array("" => ""), $systemAvatars);
  }
 } else { 
 	
 	
 	
 	
 	//The user is being edited by the admin
  $form = new HTML_QuickForm("set_avatar_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=users&edit_user=".$editedUser -> user['login'], "", null, true);
  if ($GLOBALS['configuration']['social_modules_activated'] > 0) {
   $personal_profile_form = 1;
  // $smarty -> assign("T_SOCIAL_INTERFACE", 1);
   $systemAvatars = array_merge(array("" => ""), $systemAvatars);
  }
  $baseUrl = "ctg=users&edit_user=".$editedUser -> user['login'];
 }
 $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter'); //Register this rule for checking user input with our function, eF_checkParameter
 $form -> addElement('file', 'file_upload', _IMAGEFILE, 'class = "inputText"');
 $form -> addElement('advcheckbox', 'delete_avatar', _DELETECURRENTAVATAR, null, 'class = "inputCheckbox"', array(0, 1));

 $form -> addElement('select', 'system_avatar' , _ORSELECTONEFROMLIST, $systemAvatars, "id = 'select_avatar'");
 
 $form -> setMaxFileSize(FileSystemTree :: getUploadMaxSize() * 1024); //getUploadMaxSize returns size in KB
 // Distinguishing between personal and other user administrator

 if ($ctg == "personal") {
  if (!isset($_GET['op'])) {
   $_GET['op'] = 'account';
  }
  $options = array( 
//  	array('image' => '16x16/home.png', 'title' => _DASHBOARD, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=dashboard', 'selected' => isset($_GET['op']) && $_GET['op'] == 'dashboard' ? true : false),
  	array('image' => '16x16/generic.png', 'title' => _MYACCOUNT, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=account', 'selected' => isset($_GET['op']) && $_GET['op'] == 'account' ? true : false)
);
  if ($currentUser -> getType() != "administrator") {
   $options[] = array('image' => '16x16/user_timeline.png', 'title' => _MYSTATUS, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=status' , 'selected' => isset($_GET['op']) && $_GET['op'] == 'status' ? true : false);
  }
  $titles = array ( "account" => array("edituser" => _MYSETTINGS,
            "profile" => _MYPROFILE,
            "mapped" => _MAPPEDACCOUNTS,
            "placements" => _MYPLACEMENTS,
            "history" => _MYHISTORY,
            "files" => _MYFILES,
            "payments" => _PAYPALMYTRANSACTIONS),
        "status" => array("lessons" => _MYLESSONS,
           "courses" => _MYCOURSES,
             "groups" => _MYGROUPS,
           "certifications"=> _MYCERTIFICATIONS));
 } else {
  if (!isset($_GET['op'])) {
   $_GET['op'] = 'account';
  }
  $options = array(
  	array('image' => '16x16/generic.png', 'title' => _EDITUSER, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=account', 'selected' => isset($_GET['op']) && $_GET['op'] == 'account' ? true : false),
  	array('image' => '16x16/user_timeline.png', 'title' => _LEARNINGSTATUS, 'link' => basename($_SERVER['PHP_SELF']).'?'.$baseUrl.'&op=status' , 'selected' => isset($_GET['op']) && $_GET['op'] == 'status' ? true : false)
  );
  $titles = array ( "account" => array("edituser" => _EDITUSER,
            "profile" => _USERPROFILE,
            "mapped" => _ADDITIONALACCOUNTS,
  			//"details"	=> _PERSONALDETAILS,
            "placements" => _PLACEMENTS,
            "history" => _HISTORY,
            "files" => _FILERECORD,
            "payments" => _PAYMENTS),
        "status" => array("lessons" => _LESSONS,
           "courses" => _COURSES,
             "groups" => _GROUPS,
  			"courseclasses" => _COURSESCLASSES,
           "certifications"=> _CERTIFICATIONS));
 }
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
  $form -> addElement('textarea', 'short_description', _SHORTDESCRIPTIONCV, 'class = "inputContentTextarea simpleEditor" style = "width:100%;height:14em;"'); //The unit content itself
  if ($_GET['op'] == 'account') { //normally editor is not needed with op='dashboard' makriria 7/6/2010
   $load_editor = true;
  }
  $form -> setDefaults(array( 'short_description' => $editedUser -> user['short_description']));
 }
 //Get the dashboard innertables

 if ((isset($currentUser -> coreAccess['users']) && $currentUser -> coreAccess['users'] != 'change') || (isset($currentUser -> coreAccess['dashboard']) && $currentUser -> coreAccess['dashboard'] != 'change')) {
  $form -> freeze();
 } else {
  if ($personal_profile_form) {
   $form -> addElement('submit', 'submit_upload_file', _APPLYPROFILECHANGES, 'class = "flatButton"');
  } else {
   $form -> addElement('submit', 'submit_upload_file', _APPLYAVATARCHANGES, 'class = "flatButton"');
  }
  if ($form -> isSubmitted() && $form -> validate()) {
   $avatarDirectory = G_UPLOADPATH.$editedUser -> login.'/avatars';
   if (!is_dir($avatarDirectory)) {
    mkdir($avatarDirectory);
   }
   try {
    if ($_FILES['file_upload']['size'] > 0) {
     $filesystem = new FileSystemTree($avatarDirectory);
     $uploadedFile = $filesystem -> uploadFile('file_upload', $avatarDirectory);
     // Normalize avatar picture to 150xDimY or DimX x 100
     eF_normalizeImage($avatarDirectory . "/" . $uploadedFile['name'], $uploadedFile['extension'], 150, 100);
     $editedUser -> user['avatar'] = $uploadedFile['id'];
     MagesterEvent::triggerEvent(array("type" => MagesterEvent::AVATAR_CHANGE, "users_LOGIN" => $editedUser -> user['login'], "users_name" => $editedUser->user['name'], "users_surname" => $editedUser->user['surname'], "lessons_ID" => 0, "lessons_name" => "", "entity_ID" => $editedUser -> user['avatar']));
     if ($personal_profile_form) {
      $editedUser -> user['short_description'] = $form ->exportValue('short_description');
      MagesterEvent::triggerEvent(array("type" => MagesterEvent::PROFILE_CHANGE, "users_LOGIN" => $editedUser -> user['login'], "users_name" => $editedUser->user['name'], "users_surname" => $editedUser->user['surname'], "lessons_ID" => 0, "lessons_name" => ""));
      $message = _SUCCESFULLYUPDATEDPROFILE;
     } else {
      $message = _SUCCESFULLYSETAVATAR;
     }
     $message_type = 'success';
     $editedUser -> persist();
    } else {
     if ($form -> exportValue('delete_avatar')) {
      $selectedAvatar = 'unknown_small.png';
     } else {
      if (!$personal_profile_form || $form -> exportValue('system_avatar') != "") {
       $selectedAvatar = $form -> exportValue('system_avatar');
      }
     }
     if (isset($selectedAvatar)) {
      $selectedAvatar = $avatarsFileSystemTree -> seekNode(G_SYSTEMAVATARSPATH.$selectedAvatar);
      $newList = FileSystemTree :: importFiles($selectedAvatar['path']); //Import the file to the database, so we can access it with view_file
      $editedUser -> user['avatar'] = key($newList);
      MagesterEvent::triggerEvent(array("type" => MagesterEvent::AVATAR_CHANGE, "users_LOGIN" => $editedUser -> user['login'], "users_name" => $editedUser->user['name'], "users_surname" => $editedUser->user['surname'], "lessons_ID" => 0, "lessons_name" => "", "entity_ID" => $editedUser -> user['avatar']));
      $needed_reload = 1;
      $message = _SUCCESFULLYSETAVATAR; // in case we have simultaneous changes in profile and avatar this value will be overwritten
     }
     
     $editedUser -> persist();
     $message_type = 'success';
    }
    if ($editedUser -> login == $currentUser -> login && !$no_reload_needed) {
     $smarty -> assign("T_REFRESH_SIDE", 1);
     $smarty -> assign("T_PERSONAL_CTG", 1);
    }
   } catch (Exception $e) {
    $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
    $message = $e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(\''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
   }
  }
  
 }
 
 $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
 $form -> accept($renderer);
 $smarty -> assign('T_AVATAR_FORM', $renderer -> toArray());
 //End of set the avatar

