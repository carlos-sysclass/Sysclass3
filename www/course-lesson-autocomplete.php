<?php
/**
 * Platform boleto page
 *
 * Esta página permite a visualização de um boleto através de um hash
 *
 * @package SysClass
 * @version 3.0.0
 */
session_cache_limiter('nocache');
//This causes the double-login problem, where the user needs to login twice when already logged in with the same browser
session_start();

$path = "../libraries/";
//Automatically redirect to installation page if configuration file is missing
/** Configuration file */
require_once $path."configuration.php";

try {
	$currentUser = MagesterUser :: checkUserAccess();

	if ($currentUser->getType() == 'student') {
		$sheetUser = $this->getCurrentUser();
	} else {
		$login = "aluno";
		$sheetUser = MagesterUserFactory::factory($login);
	}

	$searchCondition = sprintf("l.name LIKE '%%%s%%'", $_GET['term']);

	$userCourses = $sheetUser->getUserCourses(array('return_objects' => true));
	$userLessons = $sheetUser->getUserLessons(array('return_objects' => false));
	$userLessonsIndexes = array_keys($userLessons);

	foreach ($userCourses as $course) {

		$courseLessons = $course->getCourseLessons(array('return_objects' => false, 'condition' => $searchCondition));

		foreach ($courseLessons as $courseLesson) {
			if (in_array($courseLesson['id'], $userLessonsIndexes)) {
				$coursesData[] = array(
					'course_id'		=> $course->course['id'],
					'course_name'	=> $course->course['name'],
					'lesson_id'		=> $courseLesson['id'],
					'lesson_name'	=> $courseLesson['name'],
					'id'			=> $course->course['id'] . "_" . $courseLesson['id'],
					'value'			=> $courseLesson['name'],
					'label'			=> $courseLesson['name']
				);
			}
		}
	}
	echo json_encode($coursesData);
} catch (Exception $e) {
	var_dump($e);
}
