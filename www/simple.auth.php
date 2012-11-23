<?php
$path = "../libraries/";


$user		= 'akucaniz';
$pass		= 'z7d#lqed$';

//require_once $path."configuration.php";
define("JIRA_BASE_URL", "https://jira.wiseflex.com");
define("JIRA_BASE_API", "/rest/api/latest/");

$url = sprintf(JIRA_BASE_URL . JIRA_BASE_API . "issue/%s", "SYSCLASS-159");

$ch 		= curl_init();

$userPassEncoded = base64_encode($user.":".$pass);

$headers = array(
	"Authorization: Basic $userPassEncoded",
	"Content-Type: application/json"		
);

curl_setopt_array($ch, array(
	CURLOPT_SSL_VERIFYPEER	=> true,
	CURLOPT_SSL_VERIFYHOST	=> 2,
	CURLOPT_CAINFO			=> dirname(__FILE__) . "/../ssl/jira.wiseflex.com.pem",
	CURLOPT_URL				=> $url,
	CURLOPT_RETURNTRANSFER	=> true,
	CURLOPT_HEADER			=> false,
	CURLOPT_TIMEOUT			=> 30,
	CURLOPT_HTTPHEADER		=> $headers
));

$json_encoded = curl_exec($ch);
curl_close($ch);

$resultData = json_decode($json_encoded, true);

echo "<pre>";
var_dump($resultData);
echo "</pre>";
exit;
