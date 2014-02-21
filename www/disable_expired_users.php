<?php

/**
 * Cron job script
 *
 * This script is used by a cron manager to periodically send the top X unsent email messages
 * from the notifications table.
 * @package SysClass
 * @version 3.6.0
 */

//This is needed in order to make cron jobs able to run the file
$dir = getcwd();
chdir(dirname(__FILE__));

//Debugging timer - initialization
$debug_TimeStart = microtime(true);

//Initialize session
session_cache_limiter('none');
@session_start();

//Define default path
$path = "../libraries/";

// The configuration file.
require_once $path."configuration.php";

// Debugging timer - time spent on file inclusion
$debug_InitTime = microtime(true) - $debug_TimeStart;

//Set headers in order to eliminate browser cache (especially IE's)
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//debug();
$lowest_possible_time = time() - 21600; // last acceptable time - pending 6 hours in the queue to be sent
//sC_deleteTableData("notifications", "timestamp != 0 AND timestamp <" . $lowest_possible_time);

// CHECK EXPIRED USERS

$result = sC_getTableData(
	"users_to_courses",
	"users_LOGIN, courses_ID",
	"end_timestamp < UNIX_TIMESTAMP() AND user_type = 'student'"
);

foreach($result as $item) {
	$course = new MagesterCourse($item['courses_ID']);
	$user = MagesterUserFactory :: factory($item['users_LOGIN']);
	$course -> archiveCourseUsers($user);
}

var_dump($result);
