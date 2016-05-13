<?php
/**
 * @package PlicoLib\Controllers
 */
class OAuthController extends SessionManager
{
	protected $key = "VGgu9aN4M5Hu8xUBec";
	protected $secret = "hnLF7PsEaKhwLjfuDQt4LgrC25PTkuWH";
	/**
	 *
	 * @url GET /oauth
	 * @url GET /oauth/request
	 */
	public function doOAuthRequest()
	{
    	$oAuth = new oAuthManager();
    	//$consumer = $oAuth->getConsumer();
		//var_dump($consumer);
    	$response = $oAuth
    		->setConsumerKey($this->key, $this->secret)
    		->requestToken($this->getBasePath() . "oauth/access", $this->key, $this->secret);

    	@session_start();
    	$_SESSION['oauth_token']		= $response['oauth_token'];
    	$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];

		header("Location: https://bitbucket.org/api/1.0/oauth/authenticate?oauth_token=" . $response['oauth_token']);
		exit;
	}
	/**
	 *
	 * @url GET /oauth/access
	 */
	public function doOAuthAccess()
	{
    	@session_start();
    	$this->token		= $_SESSION['oauth_token'];
    	$this->token_secret	= $_SESSION['oauth_token_secret'];
    	$oauth_verifier		= $_GET['oauth_verifier'];

    	$oAuth = new oAuthManager();
    	//$consumer = $oAuth->getConsumer();
		//var_dump($consumer);
    	$response = $oAuth
			->setConsumerKey($this->key, $this->secret)
			->setTokenKey($this->token, $this->token_secret)
    		->accessToken($oauth_verifier);

    	var_dump($response);
		exit;


	}
	/**
	 *
	 * @url GET /oauth/:type/:endpoint
	 */
	public function doGetUserProfile($type, $endpoint)
	{
		var_dump($type);
		var_dump($endpoint);
		//$endpoint =

		/*
		//array(2) { ["oauth_token_secret"]=> string(32) "NSy6uRuXdnpV2NuEuxeCchjtyHaEURgn" ["oauth_token"]=> string(18) "7QaLLxt36XLDyPwSRK" }

		$oAuth = new oAuthManager();
    	//$consumer = $oAuth->getConsumer();
		//var_dump($consumer);
    	$response = $oAuth
    		->setConsumerKey($this->key, $this->secret)
    		->requestToken($this->getBasePath() . "oauth/access", $this->key, $this->secret);

    	@session_start();
    	$_SESSION['oauth_token']		= $response['oauth_token'];
    	$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];

		header("Location: https://bitbucket.org/api/1.0/oauth/authenticate?oauth_token=" . $response['oauth_token']);
		exit;
		var_dump(1);
		exit;
		*/
	}
}

