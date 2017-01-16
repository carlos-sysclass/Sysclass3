<?php
use 
	Phalcon\DI,
    Phalcon\Loader,
    Phalcon\DI\FactoryDefault as WebDI,
    Phalcon\DI\FactoryDefault\CLI as CliDI,
    Phalcon\Logger,
    Phalcon\Logger\Adapter\File as FileLogger;


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
        PLICOLIB_PATH . "", // CAN BE REMOVED AFTER ALL UPDATE
        __DIR__ . '/../../vendor/sabre/uri/lib/'
    )
);

$loader->registerNamespaces(array(
    "Sysclass\Controllers" => __DIR__ . "/../../controller/", 
    "Sysclass\Modules" => __DIR__ . "/../../modules/", 
    "Sysclass\Forms" => __DIR__ . "/../forms/",
    "Sysclass\Models" => __DIR__ . "/../models/",
    "Sysclass\Collections" => __DIR__ . "/../collections/",
    "Sysclass\Services" => __DIR__ . "/../services/",
    "Sysclass\Tasks" => __DIR__ . "/../tasks/",
    "Sysclass\Sockets" => __DIR__ . "/../sockets/",
    "Plico" => __DIR__ . "/../plico/", // TODO: Move code to plicolib itself
    "Sysclass" => __DIR__ . "/../sysclass/",
    "Phalcon" => __DIR__ . '/../../vendor/phalcon/incubator/Library/Phalcon/',
    'Phalcon\Script' => __DIR__ . '/../../vendor/phalcon/devtools/scripts/Phalcon/Script',
    "Dompdf" => __DIR__ . '/../../vendor/dompdf/dompdf/src/',
    "FontLib" => __DIR__ . '/../../vendor/phenx/php-font-lib/src/FontLib/',
    //'Sabre\DAV' => __DIR__ . '/../../vendor/sabre/dav/lib/DAV/',
    //'Sabre\HTTP' => __DIR__ . '/../../vendor/sabre/http/lib/',
    //'Sabre\Event' => __DIR__ . '/../../vendor/sabre/event/lib/',
    //'Sabre\Xml' => __DIR__ . '/../../vendor/sabre/xml/lib/',
    
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
    'Cpdf'    => __DIR__ . "/../../vendor/dompdf/dompdf/lib/Cpdf.php",
    'Kint'    => __DIR__ . "/../../vendor/raveren/kint/Kint.class.php",
    'PiwikTracker' => __DIR__ . "/../../vendor/piwik/piwik-php-tracker/PiwikTracker.php",
    "mPdf" => __DIR__ . '/../../vendor/mpdf/mpdf/mpdf.php',
    "HTML5_Tokenizer" => __DIR__ . '/../../vendor/dompdf/dompdf/lib/html5lib/Tokenizer.php',
    "HTML5_InputStream" => __DIR__ . '/../../vendor/dompdf/dompdf/lib/html5lib/InputStream.php',
    "HTML5_TreeBuilder" => __DIR__ . '/../../vendor/dompdf/dompdf/lib/html5lib/TreeBuilder.php',
    "HTML5_Data" => __DIR__ . '/../../vendor/dompdf/dompdf/lib/html5lib/Data.php'
));



//$loader->setEventsManager($eventsManager);

// Register autoloader
$loader->register();

// LOAD THE COMPOSER AUTOLOADER
include __DIR__ . '/../../vendor/autoload.php';


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

$di->set("eventsManager", $eventsManager);
DI::setDefault($di);

/*
//$eventsManager->attach(null, new Sysclass\Services\Events\Listener());
$logger = new FileLogger(REAL_PATH . "/logs/events.log",
    array(
        'mode' => 'w'
    )
);

$listener = function($event, $source) use ($logger) {
    $logger->log(get_class($source) . "::" . $event->getType(), Logger::INFO);
};

$eventsManager->attach("dispatch", $listener);
$eventsManager->attach("loader", $listener);
$eventsManager->attach("acl", $listener);
$eventsManager->attach("console", $listener);
$eventsManager->attach("cli", $listener);
$eventsManager->attach("db", $listener);
$eventsManager->attach("application", $listener);
$eventsManager->attach("collection", $listener);
$eventsManager->attach("micro", $listener);
$eventsManager->attach("model", $listener);
$eventsManager->attach("view", $listener);
$eventsManager->attach("collectionManager", $listener);
$eventsManager->attach("modelsManager", $listener);
$eventsManager->attach("volt", $listener);
*/