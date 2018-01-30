<?php
use Phalcon\CLI\Console as ConsoleApp;

//@session_start();

// SYSCLASS NEW BOOTSTRAP FILE... POP-OUT PLICOLIB, AND TRYUING TO USE JUST STANDALONE COMPONENTS
//
// SMARTY WILL MAINTAINED FOR NOW*
//

ini_set("display_errors", "1");
//error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);

//Phalcon\Mvc\Micro as Application;

define("REAL_PATH", realpath(__DIR__ . "/"));

define("PLICOLIB_PATH", __DIR__ . "/plicolib/");

/*
if ($_SERVER['HTTP_HOST'] == 'local.beta.sysclass.com') {
define("PLICOLIB_PATH", "/projects/repo/plico/plicolib.local/");
} else {
define("PLICOLIB_PATH", __DIR__ . "/../../../plicolib/current/");
}
 */
define("APP_TYPE", "CONSOLE");

try {

	require_once "app/bootstrap/bootstrap.php";

	$console = new ConsoleApp();
	$console->setDI($di);

	/**
	 * Process the console arguments
	 */
	$arguments = array();
	foreach ($argv as $k => $arg) {
		if ($k == 1) {
			$arguments['task'] = $arg;
		} elseif ($k == 2) {
			$arguments['action'] = $arg;
		} elseif ($k >= 3) {
			$arguments['params'][] = $arg;
		}
	}

	// Define global constants for the current task and action
	define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
	define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

	try {
		$console->handle($arguments);
	} catch (\Phalcon\Exception $e) {
		echo $e->getMessage();
		exit(255);
	}

} catch (\Exception $e) {
	var_dump($e);
	exit;
}

exit;