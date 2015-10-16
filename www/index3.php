<?php
//@session_start();

// SYSCLASS NEW BOOTSTRAP FILE... POP-OUT PLICOLIB, AND TRYUING TO USE JUST STANDALONE COMPONENTS
//
// SMARTY WILL MAINTAINED FOR NOW*
// 

error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
ini_set("display_errors", "1");


use Phalcon\Loader,
    Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\Application;
    //Phalcon\Mvc\Micro as Application;


define("REAL_PATH", realpath(__DIR__ . "/../"));

if ($_SERVER['HTTP_HOST'] == 'local.beta.sysclass.com') {
    define("PLICOLIB_PATH", "/projects/repo/plico/plicolib.local/");
} else {
    define("PLICOLIB_PATH", __DIR__ . "/../../../../plicolib/current/");

}


try {

    // Creates the autoloader
    $loader = new Loader();

    // Register some namespaces
    $loader->registerDirs(
        array(
            __DIR__ . "/../controller/",
            PLICOLIB_PATH . "/controller/",
            PLICOLIB_PATH . "/inc/",
            PLICOLIB_PATH . "/"
        )
    );

    $loader->registerNamespaces(
        array(
           "Sysclass\Controllers" => __DIR__ . "/../controller/", 
           "Sysclass\Modules" => __DIR__ . "/../modules/", 
           "Sysclass\Models" => __DIR__ . "/../app/models/",
           "Sysclass\Services" => __DIR__ . "/../app/services/",
           "Plico" => __DIR__ . "/../app/plico/", // TODO: Move code to plicolib itself
           "Sysclass" => __DIR__ . "/../app/sysclass/"
        )
    );
    // Register autoloader
    $loader->register();

    $plico = PlicoLib::instance(__DIR__ . "/../");

    $di = new FactoryDefault();
    $eventsManager = new Phalcon\Events\Manager();
    $di->set("eventManager", $eventsManager);


    require_once("../app/bootstrap/services.php");


    // GENERATE ROUTES BASED ON MODELS

    //require_once("../app/routes.php");

    // Handle the request
    $application = new Application($di);

    echo $application->handle();

} catch (\Exception $e) {
    var_dump($e);
}
