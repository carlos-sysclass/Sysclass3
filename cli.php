<?php
use Phalcon\Loader,
	Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\CLI\Console as ConsoleApp;

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

define('VERSION', '1.0.0');

$configurationDefaults = array(
	'_default'			=> array(
		'server'	=> $protocol.'://'.$HTTP_HOST.'/',
		'dbtype'	=> 'mysql',
		'dbhost'	=> 'localhost',
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass_demo',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'none',
		'theme'		=> (@isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass.default')
	),
	/*
	'local.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_demo',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass.default')
	),
	'local.beta.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_demo',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass.default')
	),
	*/
	'develop.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_develop',
		'theme'		=> 'sysclass.default',
		'https'		=> 'required',
	),
	'enterprise.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_enterprise',
		'theme'		=> 'sysclass.default',
		'https'		=> 'required',
	),
	'www.enterprise.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_enterprise',
		'theme'		=> 'sysclass.default',
		'https'		=> 'required',
	),
	'fornecedores.itaipu.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_itaipu',
		'theme'		=> 'sysclass.itaipu',
		'https'		=> 'required',
	),
	'itaipu.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_itaipu',
		'theme'		=> 'sysclass.itaipu',
		'https'		=> 'required'
	)
);





// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__)));

/**
 * Register the autoloader and tell it to register the tasks directory
 */
// Creates the autoloader
$loader = new Loader();

echo APPLICATION_PATH . '/app/tasks';

$loader->registerDirs(
    array(
        APPLICATION_PATH . '/app/tasks'
    )
);

// Register some namespaces
$loader->registerNamespaces(
    array(
       "Sysclass\Models" => "app/models/",
       "Sysclass\Services" => APPLICATION_PATH . '/app/services',
       "Plico" => "app/plico/", // TODO: Move code to plicolib itself
       "Sysclass" => "app/sysclass/"
    )
);

// Register autoloader
$loader->register();

// Using the CLI factory default services container
$di = new CliDI();
$eventsManager = new Phalcon\Events\Manager();
$di->set("eventManager", $eventsManager);


// Load the configuration file (if any)
/*
if (is_readable(APPLICATION_PATH . '/config/config.php')) {
    $config = include APPLICATION_PATH . '/config/config.php';
    $di->set('config', $config);
}
*/
$di->set('db', function () use ($configuration, $eventsManager) {
	$class = "Phalcon\\Db\\Adapter\\Pdo\\" . ucfirst($configuration['dbtype']);
	if (class_exists($class)) {
	    $database = new $class(array(
	        "host"     => $configuration['dbhost'],
	        "username" => $configuration['dbuser'],
	        "password" => $configuration['dbpass'],
	        "dbname"   => $configuration['dbname'],
	        "charset"  => 'utf8'
	    ));

	    $database->setEventsManager($eventsManager);

	    return $database;
	} else {
		throw new Exception("Error estabilishing a database connection");
		exit;
	}
});



// Set a models manager
$di->set('modelsManager', function ()  use ($eventsManager) {
    $ModelsManager = new Plico\Mvc\Model\Manager();

	return $ModelsManager;
});

$di->set('modelsCache', function () {

	//Cache data for 1 hour
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
	    'lifetime' => 3600
	));

	$cache = new BackendCache($frontCache, array(
    	'prefix' => 'SYSCLASS-MODELS'
  	));

    return $cache;
});

$di->set('cache', function() {

	//Cache data for 1 hour
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
	    'lifetime' => 3600
	));

	$cache = new BackendCache($frontCache, array(
    	'prefix' => 'SYSCLASS'
  	));

	return $cache;
});



$di->set("configuration", function() {
	return new Sysclass\Services\Configuration();
});


// Create a console application
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
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}



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

$di->set('db', function () use ($configuration, $eventsManager) {
	$class = "Phalcon\\Db\\Adapter\\Pdo\\" . ucfirst($configuration['dbtype']);
	if (class_exists($class)) {
	    $database = new $class(array(
	        "host"     => $configuration['dbhost'],
	        "username" => $configuration['dbuser'],
	        "password" => $configuration['dbpass'],
	        "dbname"   => $configuration['dbname'],
	        "charset"  => 'utf8'
	    ));

	    $database->setEventsManager($eventsManager);

	    return $database;
	} else {
		throw new Exception("Error estabilishing a database connection");
		exit;
	}
});

$logger = new FileLogger(__DIR__ . "/logs/database.log");
// Listen all the database events
$eventsManager->attach('db', function ($event, $connection) use ($logger) {
    if ($event->getType() == 'beforeQuery') {
        $logger->log($connection->getSQLStatement(), Logger::INFO);
    }
});

// Set a models manager
$di->set('modelsManager', function ()  use ($eventsManager) {
    $ModelsManager = new Plico\Mvc\Model\Manager();

	return $ModelsManager;
});

$di->set('modelsCache', function () {

	//Cache data for 1 hour
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
	    'lifetime' => 3600
	));

	$cache = new BackendCache($frontCache, array(
    	'prefix' => 'SYSCLASS-MODELS'
  	));

    return $cache;
});

// Use the memory meta-data adapter or other
//$di->set('modelsMetadata', new MetaData());

$di->set('modelsMetadata', new \Phalcon\Mvc\Model\Metadata\Files(array(
    'metaDataDir' => __DIR__ . '/cache/metadata/'
)));
$di->set('cache', function() {

	//Cache data for 1 hour
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
	    'lifetime' => 3600
	));

	$cache = new BackendCache($frontCache, array(
    	'prefix' => 'SYSCLASS'
  	));

	return $cache;
});


$di->setShared("acl", function() use ($di, $eventsManager) {
	// GET CURRENT USER
	$user = $di->get("authentication")->checkAccess();

	// CREATE THE ACL
	$acl = Sysclass\Acl\Adapter::getDefault($user);
	// Bind the eventsManager to the ACL component
	$acl->setEventsManager($eventsManager);

	return $acl;

});


$di->setShared("url", function() use ($di) {
	$url = new Phalcon\Mvc\Url();
	$url->setDI($di);
	$url->setBasePath("/var/www/sysclass/current/www");

	return $url;
});

$di->setShared("escaper", function() {
    $escaper = new \Phalcon\Escaper();
    return $escaper;
});

$di->setShared("assets", function() use ($di) {
	$assets = new Plico\Assets\Manager(array(
		"sourceBasePath" => __DIR__ . "/www/",
		"targetBasePath" => __DIR__ . "/www/"
	));
	//$assets->setDI($di);
	//$url->setBasePath("/var/www/local.sysclass.com/current/www");
	return $assets;
});


$session = new Session(array('uniqueId' => 'SYSCLASS'));
if (!$session->isStarted()) {
	$session->start();
}
$di->set('session', $session);



// TODO: Load Autentication Backends, based on configuration
$di->set("authentication", function() use ($eventsManager) {
	$authentication = new Sysclass\Services\Authentication\Adapter();
	$authentication->setEventsManager($eventsManager);
	return $authentication;
});

$di->set('crypt', function () {
    $crypt = new Crypt();
    // Set a global encryption key
    //$crypt->setKey();
    return $crypt;
}, true);


$di->set('stringsHelper', function () {
    $strings = new \Plico\Php\Helpers\Strings();
    // Set a global encryption key
    //$crypt->setKey();
    return $strings;
});

DI::setDefault($di);
*/