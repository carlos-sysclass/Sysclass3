<?php
//@session_start();

// SYSCLASS NEW BOOTSTRAP FILE... POP-OUT PLICOLIB, AND TRYUING TO USE JUST STANDALONE COMPONENTS
//
// SMARTY WILL MAINTAINED FOR NOW*
// 


ini_set("display_errors", "1");
error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);


use Phalcon\Mvc\Application;
    //Phalcon\Mvc\Micro as Application;


define("REAL_PATH", realpath(__DIR__ . "/../"));

if ($_SERVER['HTTP_HOST'] == 'local.beta.sysclass.com') {
    define("PLICOLIB_PATH", "/projects/repo/plico/plicolib.local/");
} else {
    define("PLICOLIB_PATH", __DIR__ . "/../../../../plicolib/current/");

}

define("APP_TYPE", "WEB");

try {
    require_once("../app/bootstrap/bootstrap.php");

    // Handle the request
    $application = new Application($di);

    $application->useImplicitView(false);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    var_dump($e);
    exit;
}
