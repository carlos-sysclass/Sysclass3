<?php
use 
	Phalcon\DI,
    Phalcon\Loader,
    Phalcon\DI\FactoryDefault;

// Creates the autoloader
$loader = new Loader();

// Register some namespaces
$loader->registerDirs(
    array(
        __DIR__ . "/../../controller/",
        PLICOLIB_PATH . "/controller/",
        PLICOLIB_PATH . "/inc/",
        PLICOLIB_PATH . "/"
    )
);

$loader->registerNamespaces(array(
    "Sysclass\Controllers" => __DIR__ . "/../../controller/", 
    "Sysclass\Modules" => __DIR__ . "/../../modules/", 
    "Sysclass\Models" => __DIR__ . "/../models/",
    "Sysclass\Services" => __DIR__ . "/../services/",
    "Plico" => __DIR__ . "/../plico/", // TODO: Move code to plicolib itself
    "Sysclass" => __DIR__ . "/../sysclass/",
    "Phalcon" => __DIR__ . '/../../vendor/phalcon/incubator/Library/Phalcon/'
));

$loader-> registerClasses(array(
    'Smarty'    => __DIR__ . "/../../vendor/smarty/smarty/libs/Smarty.class.php",
));

// Register autoloader
$loader->register();


$plico = PlicoLib::instance(__DIR__ . "/../");

$di = new FactoryDefault();
$eventsManager = new Phalcon\Events\Manager();
$di->set("eventManager", $eventsManager);

DI::setDefault($di);