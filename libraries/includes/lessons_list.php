<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}
$loadScripts[] = 'includes/lessons_list';
$loadScripts[] = 'includes/catalog';
try {
 if (isset($_GET['op']) && $_GET['op'] == 'tests') {
  require_once("tests/show_skill_gap_tests.php");

 } elseif (isset($_GET['export']) && $_GET['export'] == 'rtf') {
  require_once("rtf_export.php");

 } elseif (isset($_GET['export']) && $_GET['export'] == 'xml') {
  require_once("xml_export.php");

 } elseif (isset($_GET['course'])) {
  $currentCourse = new MagesterCourse($_GET['course']);
  $result = eF_getTableData("users_to_courses", "user_type", "users_LOGIN='".$currentUser -> user['login']."' and courses_ID=".$currentCourse -> course['id']);
  if (empty($result) || $roles[$result[0]['user_type']] != 'professor') {
   throw new Exception(_UNAUTHORIZEDACCESS);
  }

  $baseUrl = 'ctg=lessons&course='.$currentCourse -> course['id'];
  $smarty -> assign("T_BASE_URL", $baseUrl);
  $smarty -> assign("T_CURRENT_COURSE", $currentCourse);

  require_once 'course_settings.php';
 } elseif (isset($_GET['op']) && $_GET['op'] == 'search') {
  require_once "module_search.php";

 } elseif (isset($_GET['catalog'])) {
  require_once "catalog_page.php";

 } else {

  $directionsTree = new MagesterDirectionsTree();

  $options = array('noprojects' => 1, 'notests' => 1);
  $userLessons = $currentUser -> getUserStatusInLessons(false, true);
  foreach ($userLessons as $key => $lesson) {
   if (!$lesson -> lesson['active']) {
    unset($userLessons[$key]);
   }
  }

  /*
		 $userLessonProgress = MagesterStats :: getUsersLessonStatus($userLessons, $currentUser -> user['login'], $options);
		 $userLessons        = array_intersect_key($userLessons, $userLessonProgress); //Needed because MagesterStats :: getUsersLessonStatus might remove automatically lessons, based on time constraints
		 */
  $constraints = array('archive' => false, 'active' => true, 'sort' => 'name');
  $userCourses = $currentUser -> getUserCourses($constraints);
  
  foreach ($userCourses as $key => $course) {
   //this must be here (before $userCourses assignment) in order to revoke a certificate if it is expired and/or re-assign a course to a student if needed
   if ($course -> course['start_date'] && $course -> course['start_date'] > time()) {
    $value['remaining'] = null;
   } elseif ($course -> course['end_date'] && $course -> course['end_date'] < time()) {
    $value['remaining'] = 0;
   } else if ($course -> options['duration'] && $course -> course['active_in_course']) {
    if ($course -> course['active_in_course'] < $course -> course['start_date']) {
     $course -> course['active_in_course'] = $course -> course['start_date'];
    }
    $course -> course['remaining'] = $course -> course['active_in_course'] + $course -> options['duration']*3600*24 - time();
    if ($course -> course['end_date'] && $course -> course['end_date'] < $course -> course['active_in_course'] + $course -> options['duration']*3600*24) {
     $course -> course['remaining'] = $course -> course['end_date'] - time();
    }
   } else {
    $course -> course['remaining'] = null;
   }
   //Check whether the course registration is expired. If so, set $value['active_in_course'] to false, so that the effect is to appear disabled
   if ($course -> course['duration'] && $course -> course['active_in_course'] && $course -> course['duration'] * 3600 * 24 + $course -> course['active_in_course'] < time()) {
    $course -> archiveCourseUsers($course -> course['users_LOGIN']);
   }
   if ($course -> course['user_type'] != $currentUser -> user['user_type']) {
    $course -> course['different_role'] = 1;
   }
   $userCourses[$key] = $course;
  }
  
  //$userCourses        = $currentUser -> getCourses(true, false, $options);
  //$userCourseProgress = MagesterStats :: getUsersCourseStatus($userCourses, $currentUser -> user['login'], $options);
  //$userCourses        = array_intersect_key($userCourses, $userCourseProgress); //Needed because MagesterStats :: getUsersCourseStatus might remove automatically courses, based on time constraints
  //debug(false);exit;
  /*
		 $temp = array();
		 foreach ($userLessonProgress as $lessonId => $user) {
		 $temp[$lessonId] = $user[$currentUser -> user['login']];
		 }
		 $userProgress['lessons'] = $temp;

		 $temp = array();
		 foreach ($userCourseProgress as $courseId => $user) {
		 $temp[$courseId] = $user[$currentUser -> user['login']];
		 }
		 $userProgress['courses'] = $temp;
		 */
  $options = array('lessons_link' => '#user_type#.php?lessons_ID=',
                              'courses_link' => false,
                  'catalog' => false,
         'only_progress_link' => true);
  if (sizeof ($userLessons) > 0 || sizeof($userCourses) > 0) {
   $smarty -> assign("T_DIRECTIONS_TREE", $directionsTree -> toHTML(false, $userLessons, $userCourses, $userProgress, $options));
  }
  $innertable_modules = array();
  foreach ($loadedModules as $module) {
   unset($InnertableHTML);
     $centerLinkInfo = $module -> getCenterLinkInfo();
    $InnertableHTML = $module -> getCatalogModule();
    $InnertableHTML ? $module_smarty_file = $module -> getCatalogSmartyTpl() : $module_smarty_file = false;
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
    }
   }
  }
  //pr($innertable_modules);
  if (!empty($innertable_modules)) {
   $smarty -> assign("T_INNERTABLE_MODULES", $innertable_modules);
  }
 }
} catch (Exception $e) {
 handleNormalFlowExceptions($e);
}
