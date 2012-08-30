<?php
/**
* Direct Link Service Wrapper 
*
* Service call wrapper. Will be deprecated once the new model will be implemented. 
* @package SysClass
* @version 0.1
*/
//This is needed in order to make cron jobs able to run the file
session_cache_limiter('none'); //Initialize session
session_start();
$path = "../../../libraries/"; //Define default path
/** The configuration file.*/
require_once $path."configuration.php";

//Set headers in order to eliminate browser cache (especially IE's)
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// CHECK FOR HASH
$hashToCheck = filter_var($_GET['_chk'], FILTER_SANITIZE_MAGIC_QUOTES);
unset($_GET['_chk']);

$hashDB = eF_getTableData(
	"service_direct_link_hash", 
	"id, user_login, user_type, query, expires",
	sprintf("hash = '%s' AND expires > NOW()", $hashToCheck)
);

$getValues = array();
foreach($_GET as $index => $value) {
	$getValues[] = sprintf("%s=%s", $index, $value); 
}
$requestedQuery = implode("&", $getValues);

$found = false;
foreach($hashDB as $hashItem) {
	if ($hashItem['query'] == $requestedQuery) {
		$found = true;
		$redirectItem = $hashItem;
	}
}

if (!$found) {
	header("Location: " . G_SERVERNAME . "?message=Link inválido&message_type=failure");
} elseif (isset($redirectItem)) {
	// AUTOMATIC LOGIN AS  
	$user = MagesterUserFactory :: factory($redirectItem['user_login']);
	$password = $user -> user['password'];
	$ok = $user -> login($password, true);
	
	$redirectURL = G_SERVERNAME . $redirectItem['user_type'] . ".php?" . $redirectItem['query'];
	header("Location: " . $redirectURL);
}
exit;
?>