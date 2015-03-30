<?php

if ($_SERVER['HTTP_HOST'] == 'local.beta.sysclass.com') {
    define("PLICOLIB_PATH", "/projects/repo/plico/plicolib.local/");
} else {
    define("PLICOLIB_PATH", __DIR__ . "/../../../plicolib/current/");

}


//define("PLICOLIB_PATH", "/var/www/tests/codelockv2_7/encrypted/");

require_once PLICOLIB_PATH . "startup.php";

$plicoLib = PlicoLib::instance(__DIR__ . "/../");


//define("DEBUG", 1);

//if (defined("DEBUG") && DEBUG == 1) {
    error_reporting(E_ALL);
	ini_set("display_errors", "1");
//} else {
//	ini_set("display_errors", "0");
//}

session_cache_limiter('nocache');
$sid = session_id();
if (empty($sid)) session_start(); //This causes the double-login problem, where the user needs to login twice when already logged in with the same browser

$path = "../libraries/";
//Automatically redirect to installation page if configuration file is missing
if (!is_file($path."configuration.php")) { //If the configuration file does not exist, this is a fresh installation, so redirect to installation page
    is_file("install/index.php") ? header("location:install/index.php") : print('Failed locating configuration file <br/> Failed locating installation directory <br/> Please execute installation script manually <br/>');
    exit;
} else {
    /** Configuration file */
    require_once $path."configuration.php";
}
/*
if ($GLOBALS['configuration']['webserver_auth']) {
    $usernameVar = $GLOBALS['configuration']['username_variable'];
    $currentUser = MagesterUser :: checkWebserverAuthentication();
    $currentUser->login($currentUser->user['password'], true);
}

//@todo:temporary here, should leave
$cacheId = null;

$message = $message_type = '';

$benchmark = new MagesterBenchmark($debug_TimeStart);
$benchmark->set('init');

//Set headers in order to eliminate browser cache (especially IE's)
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//Delete installation directory after install/upgrade
if (is_dir("install") && isset($_GET['delete_install'])) {
    try {
        $dir = new MagesterDirectory('install');
        $dir->delete();
    } catch (Exception $e) {
        echo "The installation directory could not be deleted. Please delete it manually or your system security is at risk.";
    }
}
*/
$plicoLib->start();

echo 'done.';
exit;
