<?php 
/**
 * @package PlicoLib\Controllers
 */
abstract class oAuthClientController extends SessionManager
{

	protected $host = "";

	protected $oAuthHelper = null;

	protected $endpoints = array();

	protected $key = "VGgu9aN4M5Hu8xUBec";
	protected $secret = "hnLF7PsEaKhwLjfuDQt4LgrC25PTkuWH";
	
	protected $token = "7QaLLxt36XLDyPwSRK";
	protected $token_secret = "NSy6uRuXdnpV2NuEuxeCchjtyHaEURgn";

	protected function checkAuthorization() {
		//check if $token and $token_secret exists
		return true;
	}

	protected function doAuthorization() {
		@session_start();
		$plicolib = PlicoLib::instance();

    	$oAuth = new oAuthManager($this->host, $this->endpoints);
    	$oAuth->setConsumerKey($this->key, $this->secret);

		if (!isset($_GET['oauth_verifier'])) {
	    	$response = $oAuth
	    		->requestToken("http://" . $plicolib->get('http_host') . "/" . $this->context['url'], $this->key, $this->secret);

    		$_SESSION['oauth_token']		= $response['oauth_token'];
    		$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];

	    	header("Location: https://bitbucket.org/!api/1.0/oauth/authenticate?oauth_token=" . $response['oauth_token']);
		} else {
	    	$this->token 		= $_SESSION['oauth_token'];
	    	$this->token_secret = $_SESSION['oauth_token_secret'];

	    	$oauth_verifier = $_GET['oauth_verifier'];

			$response = $oAuth
				->setTokenKey($this->token, $this->token_secret)
    			->accessToken($oauth_verifier);

	    	$this->token 		= $response['oauth_token'];
	    	$this->token_secret = $response['oauth_token_secret'];

			return $this;
		}
	}

	protected function getOAuthHandler() {
		$this->oAuthHelper = new oAuthManager($this->host, $this->endpoints);
		return $this->oAuthHelper
			->setConsumerKey($this->key, $this->secret)
			->setTokenKey($this->token, $this->token_secret);
	}
}

