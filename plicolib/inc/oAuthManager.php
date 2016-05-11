<?php 
/**
 * @package PlicoLib\Managers
 */
class oAuthManager
{
  // Path to tmhOAuth libraries
  private $lib = null;
  protected $host         = null;
  protected $key          = null;
  protected $secret       = null;
  protected $token        = null;
  protected $token_secret = null;


  protected $source_hosts = array(
    'bitbucket' => "bitbucket.org"
  );



  public function __construct($host, $endpoints) {
    $plicolib = PlicoLib::instance();
    $this->lib = $plicolib->get('path/base') . 'lib/oauth/';
    require_once $this->lib.'tmhOAuth.php';
    require_once $this->lib.'tmhUtilities.php';
    
    $this->host     = $host;
		$this->endpoint = $endpoints;
	}

  public function setConsumerKey($key, $secret) {
    $this->key    = $key;
    $this->secret = $secret;

    return $this;
  }
  public function setTokenKey($key, $secret) {
    $this->token        = $key;
    $this->token_secret = $secret;
    
    return $this;
  }

	private function getSignatureMethod() {
		return $this->signature_method;
	}

	public function requestToken($callback) {
    $plicolib = PlicoLib::instance();

    $url = $this->endpoint['request'];

    $tmhOAuth = new tmhOAuth(array(
      'host'                  => $this->host,
      "url"                   => $url,
      'consumer_key'          => $this->key,
      'consumer_secret'       => $this->secret,
      'curl_ssl_verifypeer'   => false
    ));
    $params = array(
      'oauth_callback'  => "http://" . $plicolib->get('http_host') . $callback
    );

    $tmhOAuth->request('GET', $tmhOAuth->url($url, ""), $params);
    parse_str($tmhOAuth->response['response'], $response); 
    $this->token        = $response['oauth_token'];
    $this->token_secret = $response['oauth_token_secret'];
    return $response;
	}

  public function accessToken($oauth_verifier) {
    $url = $this->endpoint['access'];

    $tmhOAuth = new tmhOAuth(array(
      'host'                  => $this->host,
      "url"                   => $url,
      'consumer_key'          => $this->key,
      'consumer_secret'       => $this->secret,
      'user_token'            => $this->token,
      'user_secret'           => $this->token_secret,
      'curl_ssl_verifypeer'   => false
    ));

    $tmhOAuth->request($method, $tmhOAuth->url($url, ""), $params);

    parse_str($tmhOAuth->response['response'], $response); 
    return $response;
  }

  public function doRequest($endpoint, $method = "GET", $params = array(), $endpoint_p = array()) {
    $url = $this->endpoint[$endpoint];
    if (count($endpoint_p) > 0) {
      $search = array_keys($endpoint_p);
      $replace = array_values($endpoint_p);
      $url = str_replace($search, $replace, $url);
    }

    $tmhOAuth = new tmhOAuth(array(
      'host'                  => $this->host,
      "url"                   => $url,
      'consumer_key'          => $this->key,
      'consumer_secret'       => $this->secret,
      'user_token'            => $this->token,
      'user_secret'           => $this->token_secret,
      'curl_ssl_verifypeer'   => false
    ));

    $tmhOAuth->request($method, $tmhOAuth->url($url, ""), $params);
    if ($tmhOAuth->response['code'] == 200) {
      $response = json_decode($tmhOAuth->response['response'], true);   
    } else {
      $response = false;
    }
    
    return $response;
  }
	
}