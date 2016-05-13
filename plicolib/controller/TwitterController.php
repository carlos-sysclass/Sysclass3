<?php 
/**
 * @package PlicoLib\Controllers\Utilities
 */
class TwitterController extends PageController
{
	public function authorize()
	{
		return true;

	}

	/*************************************** config ***************************************/
	private $tweet_users = array();
   // Your Twitter App Consumer Key
	private $consumer_key = '0bc4WjIgAgRsvwC0oUxkVQ';

	// Your Twitter App Consumer Secret
	private $consumer_secret = 'nfVJ9cdFX4YYmMcRrrXU0jXQlN3f5KnQOXKxHmAGzo';

	// Your Twitter App Access Token
	private $user_token = '1546064610-JyxzrJPuYKQ9RuiybwLiRrW9nQ5rC8qBSSdgwC3';

	// Your Twitter App Access Token Secret
	private $user_secret = '9Ey5VPxRME33Qyw6QqD2vPGz6VUkpPCA52WeqpEDs';

	// Path to tmhOAuth libraries
	private $lib = '/../lib/oauth/';

	// Enable caching
	private $cache_enabled = true;

	// Cache interval (minutes)
	private $cache_interval = 15;

	// Path to writable cache directory
	private $cache_dir = '/../cache/twitter/';

	// Enable debugging
	private $debug = true;

	/**************************************************************************************/

	public function init($url, $method, $format, $root=NULL, $basePath="")
	{
		// Initialize paths and etc.
		$this->cache_dir = __DIR__ . $this->cache_dir;
		$this->lib = __DIR__ . $this->lib;

		$this->pathify($this->cache_dir);
		$this->pathify($this->lib);
		$this->message = '';

		$this->tweet_users[] = "reformeagora";
		// Set server-side debug params
		/*
		if($this->debug === true) {
			error_reporting(-1);
		} else {
			error_reporting(0);
		}
		*/
		parent::init($url, $method, $format, $root, $basePath);
	}

	private function getJSON() {
		if($this->cache_enabled === true) {
			$CFID = $this->generateCFID();
			$cache_file = $this->cache_dir.$CFID;

			if(file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * intval($this->cache_interval)))) {
				return file_get_contents($cache_file, FILE_USE_INCLUDE_PATH);
			} else {

				$JSONraw = $this->getTwitterJSON();
				$JSON = $JSONraw['response'];

				// Don't write a bad cache file if there was a CURL error
				if($JSONraw['errno'] != 0) {
					$this->consoleDebug($JSONraw['error']);
					return $JSON;
				}

				if($this->debug === true) {
					// Check for twitter-side errors
					$pj = json_decode($JSON, true);
					if(isset($pj['errors'])) {
						foreach($pj['errors'] as $error) {
							$message = 'Twitter Error: "'.$error['message'].'", Error Code #'.$error['code'];
							$this->consoleDebug($message);
						}
						return false;
					}
				}

				if(is_writable($this->cache_dir) && $JSONraw) {
					if(file_put_contents($cache_file, $JSON, LOCK_EX) === false) {
						$this->consoleDebug("Error writing cache file");
					}
				} else {
					$this->consoleDebug("Cache directory is not writable");
				}
				return $JSON;
			}
		} else {
			$JSONraw = $this->getTwitterJSON();

			if($this->debug === true) {
				// Check for CURL errors
				if($JSONraw['errno'] != 0) {
					$this->consoleDebug($JSONraw['error']);
				}

				// Check for twitter-side errors
				$pj = json_decode($JSONraw['response'], true);
				if(isset($pj['errors'])) {
					foreach($pj['errors'] as $error) {
						$message = 'Twitter Error: "'.$error['message'].'", Error Code #'.$error['code'];
						$this->consoleDebug($message);
					}
					return false;
				}
			}
			return $JSONraw['response'];
		}
	}

	private function getTwitterJSON() {
		require $this->lib.'tmhOAuth.php';
		require $this->lib.'tmhUtilities.php';

		$tmhOAuth = new tmhOAuth(array(
			'host'                  => $_POST['request']['host'],
			'consumer_key'          => $this->consumer_key,
			'consumer_secret'       => $this->consumer_secret,
			'user_token'            => $this->user_token,
			'user_secret'           => $this->user_secret,
			'curl_ssl_verifypeer'   => false
		));

		$url = $_POST['request']['url'];
		$params = $_POST['request']['parameters'];

		$tmhOAuth->request('GET', $tmhOAuth->url($url), $params);
		return $tmhOAuth->response;
	}

	private function generateCFID() {
		// The unique cached filename ID
		return md5(serialize($_POST)).'.json';
	}

	private function pathify(&$path) {
		// Ensures our user-specified paths are up to snuff
		$path = realpath($path).'/';
	}

	private function consoleDebug($message) {
		if($this->debug === true) {
			$this->message .= 'tweet.js: '.$message."\n";
		}
	}


	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url GET /twitter/timeline/:who
	 * @url GET /twitter/timeline/:who/:count
	 */
	public function index($who, $count)
	{
		if (!in_array($who, $this->tweet_users)) {
			return array("message" => "usuário não permitido");
		}
		if (!is_numeric($count)) {
			$count = 5;
		}

		$_POST['request'] = array(
			'host'			=> "api.twitter.com",
			'url' 			=> "/1.1/statuses/user_timeline.json",
			"parameters" 	=> array(
				'include_entities'	=> 1,
				'screen_name'	 	=> array($who),
				'page'				=> 1,
				'count'				=> 1,
				'count'				=> $count,
				'include_rts'		=> 1
			)
		);

		return array(
			'response' => json_decode($this->getJSON(), true),
			'message' => ($this->debug) ? $this->message : false
		);
	}
}
