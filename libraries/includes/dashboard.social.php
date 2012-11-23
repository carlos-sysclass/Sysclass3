<?php
/**

* SysClass social

*

* This page is used for the functionalities of the SysClass social infrastructure

* @package SysClass

* @version 3.6.0

*/
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}
 $loadScripts[] = 'includes/social';
 /********************* DASHBOARD PAGE ******************/
 if ($_GET['op'] == "dashboard") {
  //Calculate element positions, so they can be rearreanged accordingly to the user selection
  //$elementPositions = eF_getTableData("users_to_lessons", "positions", "lessons_ID=".$currentLesson -> lesson['id']." AND users_LOGIN='".$currentUser -> user['login']."'");
  $elementPositions = $currentUser -> user['dashboard_positions'];
  if (sizeof($elementPositions) > 0) {
  	/*
   $elementPositions = unserialize($elementPositions); //Get the inner tables positions, stored by the user.
   !is_array($elementPositions['first']) ? $elementPositions['first'] = array() : null;
   !is_array($elementPositions['second']) ? $elementPositions['second'] = array() : null;
   $smarty -> assign("T_POSITIONS_FIRST", $elementPositions['first']); //Assign element positions to smarty
   $smarty -> assign("T_POSITIONS_SECOND", $elementPositions['second']);
   $smarty -> assign("T_POSITIONS_VISIBILITY", $elementPositions['visibility']);
   $smarty -> assign("T_POSITIONS", array_merge($elementPositions['first'], $elementPositions['second']));
   */
  } else {
   $smarty -> assign("T_POSITIONS", array());
  }
  // Get *eligible* lessons of interest to this user if he is not administrator
  if ($currentUser -> getType() != "administrator" ) {
   $eligibleLessons = $currentUser -> getEligibleLessons();
   $lessons_list = array_keys($eligibleLessons);
  }
  /*Projects list - Users get only projects for their lessons while administrators none*/
  /*Forum messages list*/
  // Users see forum messages from the system forum and their own lessons while administrators for all

  /*Lesson announcements list*/
  if (!isset($currentUser -> coreAccess['news']) || $currentUser -> coreAccess['news'] != 'hidden') {
   if (!empty($lessons_list)) {
    if ($currentUser -> getType() == "student") {
     //See only non-expired news
     $news = news :: getNews(0, true) + news :: getNews($lessons_list, true);
    } else {
    }
   } else {
    //Administrator news, he doesn't have to see lesson news (since he can't actually access them)
    $news = news :: getNews(0, true);
    //$announcements		 = eF_getTableData("users u, news n LEFT OUTER JOIN lessons l ON n.lessons_ID = l.id", "n.*, l.name as show_lessons_name, l.id as show_lessons_id", "n.users_LOGIN = u.login", "n.timestamp desc, n.id desc LIMIT 5");
   }

   $announcements_options = array(array('text' => _ANNOUNCEMENTGO, 'image' => "16x16/go_into.png", 'href' => basename($_SERVER['PHP_SELF'])."?ctg=news&lessons_ID=all"));

   $smarty -> assign("T_NEWS", $news); //Assign announcements to smarty
   $smarty -> assign("T_NEWS_OPTIONS",$announcements_options);
   $smarty -> assign("T_NEWS_LINK", "student.php?ctg=news");
  }

  /* Calendar */
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
  }

  //-----------------------------------------

  $innertable_modules = array();
  //var_dump(array_keys($loadedModules));

  foreach ($loadedModules as $name => $module) {
   unset($InnertableHTML);
     //$centerLinkInfo = $module -> getCenterLinkInfo();
    $InnertableHTML = $module -> getDashboardModule();
    $InnertableHTML ? $module_smarty_file = $module -> getDashboardSmartyTpl() : $module_smarty_file = false;

   // If the module has a lesson innertable
   if ($InnertableHTML) {
    // Get module html - two ways: pure HTML or PHP+smarty
    // If no smarty file is defined then false will be returned

    if ($module_smarty_file) {
     // Execute the php code -> The code has already been executed by above (**HERE**)
     // Let smarty know to include the module smarty file
     $innertable_modules[$module->className] = array('smarty_file' => $module_smarty_file);
    } else {

     // Present the pure HTML cod
     $innertable_modules[$module->className] = array('html_code' => $InnertableHTML);
     //var_dump($innertable_modules[$module->className]);
    }
   }
  }
  //pr($innertable_modules);
  if (!empty($innertable_modules)) {
   $smarty -> assign("T_INNERTABLE_MODULES", $innertable_modules);
  }
 }
