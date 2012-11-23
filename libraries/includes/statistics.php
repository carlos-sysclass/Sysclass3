<?php
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

$loadScripts[] = 'scriptaculous/excanvas';
$loadScripts[] = 'scriptaculous/flotr';
$loadScripts[] = 'scriptaculous/controls';
$loadScripts[] = 'includes/graphs';
$loadScripts[] = 'includes/statistics';
$loadScripts[] = 'scriptaculous/canvastext';

$smarty -> assign("T_CATEGORY", 'statistics');
//$smarty -> assign("T_BASIC_TYPE", $currentUser -> user['user_type']);
$smarty -> assign("T_BASIC_TYPE", $_SESSION['s_lesson_user_type'] !="" ? $_SESSION['s_lesson_user_type'] : $currentUser -> user['user_type']);
$isProfessor = 0;
$isStudent = 0;

//check to see if the user has any lessons as a student and any lessons as professor
$lessonRoles = MagesterLessonUser::getLessonsRoles();
if ($currentUser -> user['user_type'] != 'administrator') {
    $lessons = $currentUser -> getLessons(false);
    foreach ($lessons as $key => $type) {
        if ($lessonRoles[$type] == 'professor') {
            $isProfessor = 1;
            $professorLessons[] = $key;
        } elseif ($type == 'student') {
            $isStudent = 1;
            $studentLessons[] = $key;
        }
    }
}
$smarty -> assign("T_ISPROFESSOR", $isProfessor);
$smarty -> assign("T_ISSTUDENT", $isStudent);
// Only administrators and supervisors are allowed to see user reports
if ($currentUser -> user['user_type'] != 'administrator' && !$isSupervisor) {
    if ($isProfessor) {
        if (isset($currentLesson) && !in_array($currentLesson -> lesson['id'], $professorLessons)) {
            $_GET['option'] = 'user';
        } elseif (!isset($currentLesson) && $currentUser -> user['user_type'] != 'professor') {
            $_GET['option'] = 'user';
        }
    } else {
        $_GET['option'] = 'user';
        if (!$_SESSION['s_lessons_ID']) {
            $_GET['sel_user'] = $_SESSION['s_login'];
        }
    }
}
$smarty -> assign("T_OPTION", $_GET['option']);
try {
    /*no option is set, so just show the available options*/
	if (!isset($_GET['option'])) {
		$reportGroups = array(0 => 0);
		$smarty -> assign("T_REPORTS_GROUPS", $reportGroups);
		if ($currentUser -> user['user_type'] == 'administrator') {
			$options[] = array('text' => _USERSTATISTICS, 'image' => "32x32/relatorios-user.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=user");
			$options[] = array('text' => _LESSONSTATISTICS, 'image' => "32x32/relatorios-lessons.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=lesson");
			$options[] = array('text' => _COURSESTATISTICS, 'image' => "32x32/relatorios-courses.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=course");
			$options[] = array('text' => _SYSTEMSTATISTICS, 'image' => "32x32/relatorios-reports.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=system");
			$smarty -> assign("T_STATISTICS_OPTIONS", $options);
		} elseif ($isProfessor) {
			$options[] = array('text' => _USERSTATISTICS, 'image' => "32x32/relatorios-user.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=user");
			$options[] = array('text' => _LESSONSTATISTICS, 'image' => "32x32/relatorios-lessons.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=lesson");
			$options[] = array('text' => _COURSESTATISTICS, 'image' => "32x32/relatorios-courses.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=course");
			if ($isSupervisor) {
			$options[] = array('group' => 1, 'text' => _ADVANCEDUSERREPORTS, 'image' => "32x32/users.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=advanced_user_reports");
			$options[] = array('text' => _BRANCHSTATISTICS, 'image' => "32x32/branch.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=branches");
			}
			$smarty -> assign("T_STATISTICS_OPTIONS", $options);
		} elseif ($isSupervisor) {
			$options[] = array('text' => _USERSTATISTICS, 'image' => "32x32/relatorios-user.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=user");
			$options[] = array('text' => _COURSESTATISTICS, 'image' => "32x32/relatorios-courses.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=course");
			$options[] = array('text' => _BRANCHSTATISTICS, 'image' => "32x32/relatorios-branch.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=branches");
			$options[] = array('group' => 1, 'text' => _ADVANCEDUSERREPORTS, 'image' => "32x32/users.png", 'href' => $_SERVER['PHP_SELF']."?ctg=statistics&option=advanced_user_reports");
			$smarty -> assign("T_STATISTICS_OPTIONS", $options);
		}
	} elseif ($_GET['option'] == 'user') {
		require_once 'statistics/users_stats.php';
	} elseif ($_GET['option'] == 'lesson') {
		require_once 'statistics/lessons_stats.php';
	} elseif ($_GET['option'] == 'course') {
		require_once 'statistics/courses_stats.php';
	} elseif ($_GET['option'] == 'test') {
		require_once 'statistics/tests_stats.php';
	} elseif ($_GET['option'] == 'feedback') {
		require_once 'statistics/feedback_stats.php';
	} elseif ($_GET['option'] == 'system') {
		require_once 'statistics/system_stats.php';
	} elseif ($_GET['option'] == 'custom') {
		require_once 'statistics/custom_stats.php';
	} elseif ($_GET['option'] == 'certificate') {
		require_once 'statistics/certificates_stats.php';
	} elseif ($_GET['option'] == 'events') {
		require_once 'statistics/events_stats.php';
	} elseif ($_GET['option'] == "groups") {
		require_once 'statistics/groups_stats.php';
	} elseif ($_GET['option'] == "branches") {
		require_once 'statistics/branches_stats.php';
	} elseif ($_GET['option'] == "participation") {
		require_once 'statistics/participation_stats.php';
	} elseif ($_GET['option'] == "advanced_user_reports") {
		require_once 'statistics/advanced_user_reports.php';
	}
} catch (Exception $e) {
handleNormalFlowExceptions($e);
}
