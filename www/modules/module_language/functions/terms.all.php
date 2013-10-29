<?php
//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
//header("Content-type: text/javascript");
ob_start();

//header("Content-Type: application/javascript");

// REQUIRE ALL LANGUAGE CONSTANTS
$path = dirname(__FILE__) . "/../../../../libraries/";
//Automatically redirect to installation page if configuration file is missing
if (!is_file($path."configuration.php")) { //If the configuration file does not exist, this is a fresh installation, so redirect to installation page
 is_file("install/index.php") ? header("location:install/index.php") : print('Failed locating configuration file <br/> Failed locating installation directory <br/> Please execute installation script manually <br/>');
 exit;
} else {
	$GLOBALS['loadLanguage'] = FALSE;
	
	/** Configuration file */
	require_once $path."configuration.php";
}

$modulesDB = sC_getTableData("modules","*","className = 'module_language' AND active=1");
foreach ($modulesDB as $module) {
	$folder = $module['position'];
	$className = $module['className'];

	require_once G_MODULESPATH.$folder."/".$className.".class.php";
	if (class_exists($className)) {
		$modulesLanguage = new $className("", $folder);
	} else {
		$modulesLanguage = false;
	}
	break;
}
if (!$modulesLanguage) {
	exit;
}
//$languages = $modulesLanguage->getDisponibleLanguages();

//if (isset($_GET['language']) && in_array($_GET['language'], $languages)) {
	$modulesLanguage->getLanguageFile($_GET['language'], true);
//} else {
//	echo json_encode(array());
//	exit;
//}
//$modulesLanguage->getLanguageFile($language);

$allConstants = get_defined_constants(true);

$userConstants = $allConstants['user'];

foreach ($userConstants as $key => $value) {
	if (strpos($key, "_") === 0) {
		$langConstants[trim($key)] = $value;
	}
}
if (count($langConstants) == 0) {
	exit;
}

echo json_encode(array($_GET['index'] => $langConstants));
//ob_clean();
//header("Content-Type: application/javascript", true);

//echo 'var $languageJS = ' . json_encode($langConstants);
exit;