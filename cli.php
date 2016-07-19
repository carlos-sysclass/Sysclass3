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


use Phalcon\Mvc\Application;
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

    require_once("app/bootstrap/bootstrap.php");

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
	define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
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

use 
	Phalcon\DI,
	Phalcon\DI\FactoryDefault,
	//Phalcon\Mvc\Model\Manager as ModelsManager,
	//Plico\Mvc\Model\Manager as ModelsManager,
	Phalcon\Mvc\Model\Metadata\Memory as MetaData,
	Phalcon\Mvc\Model\MetaData\Apc as ApcMetaData,
	Phalcon\Session\Adapter\Files as Session,
	Phalcon\Cache\Backend\Apc as BackendCache,
	Phalcon\Logger,
	Phalcon\Logger\Adapter\File as FileLogger,
	Phalcon\Crypt,
	Phalcon\Acl\Adapter\Memory as AclList;

// Using the CLI factory default services container
/*
$di = new CliDI();
$eventsManager = new Phalcon\Events\Manager();
$di->set("eventManager", $eventsManager);
*/

// Load the configuration file (if any)
/*
use 
	Phalcon\DI,
	Phalcon\DI\FactoryDefault,
	//Phalcon\Mvc\Model\Manager as ModelsManager,
	//Plico\Mvc\Model\Manager as ModelsManager,
	Phalcon\Mvc\Model\Metadata\Memory as MetaData,
	Phalcon\Mvc\Model\MetaData\Apc as ApcMetaData,
	Phalcon\Session\Adapter\Files as Session,
	Phalcon\Cache\Backend\Apc as BackendCache,
	Phalcon\Logger,
	Phalcon\Logger\Adapter\File as FileLogger,
	Phalcon\Crypt,
	Phalcon\Acl\Adapter\Memory as AclList;

// Creates the autoloader
$loader = new Loader();

// Register some namespaces
$loader->registerNamespaces(
    array(
       "Sysclass\Models" => "../app/models/",
       "Sysclass\Services" => "../app/services/",
       "Plico" => "../app/plico/", // TODO: Move code to plicolib itself
       "Sysclass" => "../app/sysclass/"
    )
);
// Register autoloader
$loader->register();

$di = new FactoryDefault();
$eventsManager = new Phalcon\Events\Manager();
$di->set("eventManager", $eventsManager);





DI::setDefault($di);
*/