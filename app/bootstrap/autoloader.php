<?php
use 
	Phalcon\DI,
    Phalcon\Loader,
    Phalcon\DI\FactoryDefault as WebDI,
    Phalcon\DI\FactoryDefault\CLI as CliDI;


$eventsManager = new Phalcon\Events\Manager();


// Creates the autoloader
$loader = new Loader();

// Register some namespaces
$loader->registerDirs(
    array(
        __DIR__ . "/../../controller/", // CAN BE REMOVED AFTER ALL UPDATE
        __DIR__ . "/../../model/", // CAN BE REMOVED AFTER ALL UPDATE
        __DIR__ . "/../../helper/", // CAN BE REMOVED AFTER ALL UPDATE
        PLICOLIB_PATH . "controller/", // CAN BE REMOVED AFTER ALL UPDATE
        PLICOLIB_PATH . "inc/", // CAN BE REMOVED AFTER ALL UPDATE
        PLICOLIB_PATH . "" // CAN BE REMOVED AFTER ALL UPDATE
    )
);

$loader->registerNamespaces(array(
    "Sysclass\Controllers" => __DIR__ . "/../../controller/", 
    "Sysclass\Modules" => __DIR__ . "/../../modules/", 
    "Sysclass\Models" => __DIR__ . "/../models/",
    "Sysclass\Services" => __DIR__ . "/../services/",
    "Sysclass\Tasks" => __DIR__ . "/../tasks/",
    "Sysclass\Websockets" => __DIR__ . "/../websockets/",
    "Plico" => __DIR__ . "/../plico/", // TODO: Move code to plicolib itself
    "Sysclass" => __DIR__ . "/../sysclass/",
    "Phalcon" => __DIR__ . '/../../vendor/phalcon/incubator/Library/Phalcon/'
    /*
    "Ratchet" => __DIR__ . '/../../vendor/cboden/ratchet/src/Ratchet/',
    "React\EventLoop" => __DIR__ . '/../../vendor/react/event-loop/',
    "React\Socket" => __DIR__ . '/../../vendor/react/socket/src/',
    "React\Stream" => __DIR__ . '/../../vendor/react/stream/src/',
    "Evenement" => __DIR__ . '/../../vendor/evenement/evenement/src/Evenement/',
    */
    
));

$loader-> registerClasses(array(
    'Smarty'    => __DIR__ . "/../../vendor/smarty/smarty/libs/Smarty.class.php",
    'Kint'    => __DIR__ . "/../../vendor/raveren/kint/Kint.class.php"
));



//$loader->setEventsManager($eventsManager);

// Register autoloader
$loader->register();


$plico = PlicoLib::instance(__DIR__ . "/../");

if (APP_TYPE === "CONSOLE") {
    $di = new CliDI();

    /*
    $loader = new Loader();

    echo REAL_PATH . '/app/tasks';

    $loader->registerDirs(
        array(
            REAL_PATH . '/app/tasks'
        )
    );
    */

} else {
    $di = new WebDI();
}

$di->set("eventManager", $eventsManager);
DI::setDefault($di);


