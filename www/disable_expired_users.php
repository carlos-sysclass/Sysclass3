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
	"users_LOGIN, courses_ID, end_timestamp",
	"end_timestamp < UNIX_TIMESTAMP() AND user_type = 'student' AND archive = 0",
	'courses_ID ASC',
	'',
	'2'
);
$row = array();
foreach($result as $item) {
	$course = new MagesterCourse($item['courses_ID']);
	$user = MagesterUserFactory :: factory($item['users_LOGIN']);
	$course -> archiveCourseUsers($user);

	$row[] = sprintf(
		"%s\t%s %s(%s)\t%s", 
		$course->course['name'], $user->user['name'], $user->user['surname'], $user->user['login'], date("d/m/Y", $item['end_timestamp'])
	);
}

$message = "Lista de usuários desativados por data de expiração\n\n";
$message .= implode("\n", $row);


$smtp = Mail::factory('mail');

$header = array (
	'From' => $GLOBALS['configuration']['system_email'],
	'To' => 'andre@kucaniz.com',
	'Subject' => 'Usuários Desativados',
	'Content-Transfer-Encoding' => '7bit',
	'Content-type' => 'text/plain;charset="UTF-8"'
);

$smtp->send('andre@kucaniz.com', $header, $message);

$header = array (
	'From' => $GLOBALS['configuration']['system_email'],
	'To' => 'adriane@ult.com.br',
	'Subject' => 'Usuários Desativados',
	'Content-Transfer-Encoding' => '7bit',
	'Content-type' => 'text/plain;charset="UTF-8"'
);

$smtp->send('adriane@ult.com.br', $header, $message);