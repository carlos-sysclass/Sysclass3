<?php

class module_lessonsidebar extends MagesterModule
{
    public function getName()
    {
        return _LESSON_SIDEBAR;
    }
    public function getPermittedRoles()
    {
     return array("student", "professor");
    }

    public function onInstall()
    {
        return true;
    }

    public function onUninstall()
    {
        return true;
    }

    public function getSidebarLinkInfo()
    {
    	/*
        $currentUser = $this -> getCurrentUser();

        $userLessons        = $currentUser -> getLessons(true);
        $userLessonProgress = MagesterStats :: getUsersLessonStatus($userLessons, $currentUser -> user['login']);

        $userCourses        = $currentUser -> getUserCourses();
        $userCourseProgress = MagesterStats :: getUsersCourseStatus($userCourses, $currentUser -> user['login']);

        // Assign progress in a per-lesson fashion
        $temp = array();
        foreach ($userLessonProgress as $lessonId => $user) {
        	$temp[$lessonId] = $user[$currentUser -> user['login']];
        }
		!is_null($benchmark) ? $benchmark->set("sidebar-3") : null;
        $userInfo['lessons'] = $temp;

        // Assign progress in a per-course fashion
        $temp = array();
        foreach ($userCourseProgress as $courseId => $user) {
        	$temp[$courseId] = $user[$currentUser -> user['login']];
        }
		$userInfo['courses'] = $temp;

        $roles     = MagesterLessonUser :: getLessonsRoles();
        $roleNames = MagesterLessonUser :: getLessonsRoles(true);
		!is_null($benchmark) ? $benchmark->set("sidebar-4") : null;
        foreach ($userCourses as $course) {
        	$roleBasicType = $roles[$userInfo['courses'][$course -> course['id']]['user_type']];        //The basic type of the user's role in the course

            if ($roleBasicType == 'student') {
	            $eligible = $course -> checkRules($userInfo['courses'][$course -> course['id']]['login']);
	        } else {
	        	$courseLessons = $course -> getCourseLessons();
	            $eligible = array_combine(array_keys($courseLessons), array_fill(0, sizeof($courseLessons), 1));    //All lessons set to true
	        }

	        foreach ($eligible as $lessonId => $value) {
	        	$userLessons[$lessonId] -> lesson['eligible'] = $value;
	        	$userLessons[$lessonId] -> lesson['courseId'] = $course -> course['id'];
	        }
        }

        $mylessons = array();
        foreach ($userLessons as $lesson) {
            if ($lesson -> lesson['id'] != "" && (!isset($lesson -> lesson['eligible']) || (isset($lesson -> lesson['eligible']) && $lesson -> lesson['eligible']))) {
            	if ($lesson -> lesson['courseId']) {
            		$lessonCourseId = "&course=".$lesson -> lesson['courseId'];
            	} else {
            		$lessonCourseId = "";
            	}
            	$mylessons[] = array('id' => 'lessons_' . $lesson -> lesson['id'],
                   'title' => $lesson -> lesson['name'],
                   'image' => $this -> moduleBaseDir . 'images/books',
                   '_magesterExtensions' => '1',
                   'link'  =>  $roles[$lesson -> userStatus['user_type']]. ".php?lessons_ID=". $lesson -> lesson['id'] .$lessonCourseId);
            }
        }

        $currentLesson = $this -> getCurrentLesson();

        return array (  "other"  => array('menuTitle' => _MYLESSONSMENU, 'links' => $mylessons));
		*/

		return false;

    }

    public function getNavigationLinks()
    {
        return array ();
    }

    public function getLinkToHighlight()
    {
        return '';
    }

    public function getSmartyTpl()
    {
        return false;
    }
}
