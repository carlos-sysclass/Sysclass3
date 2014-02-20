<?php
$path = "../libraries/";
//require_once $path."configuration.php";

require_once $path."external/oauth-php/OAuthStore.php";
require_once $path."external/oauth-php/OAuthRequester.php";

define("JIRA_BASE_URL", "https://jira.wiseflex.com");

$key = 'n4xhSLpZXOB3mOr35ljZd2oHZU7YIW5AuxqXaWsLMyU='; // this is your consumer key
$secret = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDHxdka8T2uAEZL0ikTeOO6YHbb
oTqbwUXpQAy0qJa9cOs3tjBOU5kvRlE6IUnClclNhYn7Nl74FqD0s7IZWn5lyqan
m59MJomXEzeyAw/ytTRaZM1koTNkNAENuemjzMzdKtQ6lcYMxizzfoU2mbSgkUVF
JY8Z15ajsTmqZJtpoQIDAQAB'; // this is your secret key

$token = OAuthRequester::requestRequestToken($key, 1);

$options = array( 'consumer_key' => $key, 'consumer_secret' => $secret );
OAuthStore::instance("2Leg", $options );

// this is the URL of the request
$url = JIRA_BASE_URL . "/plugins/servlet/oauth/request-token";
// you can also use POST instead
$method = "GET";
$params = null;

try {
	// Obtain a request object for the request we want to make
	$request = new OAuthRequester($url, $method, $params);

	// Sign the request, perform a curl request and return the results,
	// throws OAuthException2 exception on an error
	// $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
	$result = $request->doRequest(
		0,
		array(
			CURLOPT_SSL_VERIFYPEER	=> true,
			CURLOPT_SSL_VERIFYHOST	=> 2,
			CURLOPT_CAINFO			=> dirname(__FILE__) . "/../ssl/jira.wiseflex.com.pem"
		)
	);

	$response = $result['body'];
	var_dump($response);
} catch (OAuthException2 $e) {
	var_dump($e);
}
