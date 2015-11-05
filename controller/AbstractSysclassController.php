<?php
/*
use Monolog\Logger;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\WildfireFormatter;
*/
use Phalcon\DI,
	Sysclass\Models\System\Settings,
	Sysclass\Services\Authentication\Exception as AuthenticationException;

abstract class AbstractSysclassController extends AbstractDatabaseController
{
	protected static $logger = null;
	protected static $current_user = null;
	protected static $logged_user = null;

	//public static $t = null;
	public static $cfg = null;
	public static $syscfg = null;
	public function init($url, $method, $format, $root=NULL, $basePath="", $urlMatch = null)
	{
		//parent::init($url, $method, $format, $root, $basePath, $urlMatch);

		// LOAD TRANSLATE MODEL
		// 
		/*
		if (is_null(self::$t)) {
			self::$t = $this->translator;
		}
		*/
		if (is_null(self::$cfg)) {
			$di = DI::getDefault();
			self::$cfg = $di->get("configuration")->asArray();
			self::$syscfg = $di->get("sysconfig");
		}
	}

	public function authorize()
	{
		var_dump(1);
		exit;
		// INJECT HERE SESSION AUTHORIZATION CODE
		$di = DI::getDefault();
		try {
			// OLD CHECK STYLE
			$user = $di->get("authentication")->checkAccess();

			self::$current_user = $user;
			self::$logged_user = $user->toArray();

		    $this->putItem("CURRENT_USER", self::$current_user);
		    $this->putItem("LOGGED_USER", self::$logged_user);
		    $GLOBALS["currentUser"] = self::$current_user;


		    // CODE TO CHECK FOR AUTHORIZATION
		    //echo "see {ROOT}controller/" . __CLASS__ . ".php:  line 121";
		    //var_dump($di->get("acl")->isUserAllowed($user, "Courses", "Enroll"));
		    //exit;

		} catch (AuthenticationException $e) {
			$url = "/login";

			$session = $di->get("session");
			$session->set("requested_uri", $_SERVER['REQUEST_URI']);
			//$session->set("a", "aaaa");
			//exit;
			switch($e->getCode()) {
				case AuthenticationException :: MAINTENANCE_MODE : {
		            $message = $this->translate->translate("System is under maintenance mode. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = $this->translate->translate("The system was locked down by a administrator. Please came back in a while.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: NO_USER_LOGGED_IN : {
		            $message = $this->translate->translate("Your session appers to be expired. Please provide your credentials.");
		            $message_type = 'info';
					break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
					$url = "/lock";
		            $message = $this->translate->translate("Your account is locked. Please provide your password to unlock.");
		            $message_type = 'info';
		            break;
				}
				default : {
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}
			// TODO:  CHECK IF THE REQUEST WASN'T A JSON REQUEST
			$this->redirect($url, $message, $message_type);
		}
		return TRUE;
	}

	protected function beforeDisplay() {
		//$smarty = $this->getSmarty();
		// GET USER TOP BAR ICONS
		$this->putItem("configuration", self::$cfg);
		$this->putItem("sysconfig", $this->sysconfig);

		if ($user = $this->getCurrentUser(true)) {

			$userSettings = $user->getSettings()->toArray();

			$this->putItem("SETTINGS_", $userSettings);

			// GET TOPBAR
			$layoutManager = $this->module("layout");

			$currentUser = $this->getLoggedUser(true);
			$dashboard_id = $currentUser['dashboard_id'] == "default" ? $currentUser['user_type'] : $currentUser['dashboard_id'];
			$layoutManager->loadLayout($dashboard_id);

			$topbarMenu = $layoutManager->getMenuBySection("topbar");

        	$this->putItem("topbar_menu", $topbarMenu);


        	//print_r($topbarMenu);

        	

        	/*
			if (unserialize(self::$current_user -> user['additional_accounts'])) {
				$accounts = unserialize(self::$current_user -> user['additional_accounts']);
				$queryString = "'".implode("','", array_values($accounts))."'";
				$bar_additional_accounts = sC_getTableData("users", "login, user_type", "login in (".$queryString.")");
				$this -> putItem("additional_accounts", $bar_additional_accounts);
			}
			*/
			$this -> putItem("user_types_icons", array(
				'administrator'	=> array(
					"icon"	=> "rocket",
					"color"	=> "danger"
				),
				'professor' => array(
					"icon"	=> "plane",
					"color"	=> "info"
				),
				'student' => array(
					"icon"	=> "road",
					"color"	=> "success"
				)
			));

			// CREATE USER TOP-BAR AVATAR
			//
			$userData = $this->getCurrentUser(true)->toFullArray(array('Avatars'));

			$this->putItem("current_user", $userData);
			/*
			$small_user_avatar = $big_user_avatar = array();
			try {
			    $file = new MagesterFile(self::$current_user->user['avatar']);
			    list($small_user_avatar['width'], $small_user_avatar['height']) = sC_getNormalizedDims($file['path'], 29, 29);
			    $small_user_avatar['avatar'] = self::$current_user->user['avatar'];

			    list($big_user_avatar['width'], $big_user_avatar['height']) = sC_getNormalizedDims($file['path'], 200, 200);
				$big_user_avatar['avatar'] = self::$current_user->user['avatar'];
			} catch (MagesterFileException $e) {
			    $small_user_avatar = array(
			        'avatar' => "img/avatar_small.jpg",
			        'width'  => 29,
			        'height' => 29
			    );
			    $big_user_avatar = array(
			        'avatar' => "img/avatar_big.jpg",
			        'width'  => 200,
			        'height' => 200
			    );
			}
			$this->putItem("small_user_avatar", $small_user_avatar);
			$this->putItem("big_user_avatar", $big_user_avatar);
			*/
			parent::beforeDisplay();
		} else {
			parent::beforeDisplay();
		}

	}



	/**
	 * @deprecated
	*/
	public function getCurrentUser($object = false) {
		if (is_null(self::$current_user)) {
			$this->authorize();
		}
		if (self::$current_user) {
			if ($object) {
				return self::$current_user;
			} else {
				return self::$current_user->toArray();
			}
		}
		return false;
	}

	public function getLoggedUser($object = false) {
		if (is_null(self::$logged_user)) {
			$this->authorize();
		}
		return self::$logged_user;
	}

	public function getSystemUrl($who = null) {
		if (is_null($who)) {
			$who = 'default';
		}
		
		$urls = $this->environment['urls']->toArray();
		if (array_key_exists($who, $urls)) {
			return $urls[$who];
		}
		return false;
	}
	/* FRAMEWORK CANDIDATE FUNCTIONS - !MOVE TO plicolib if apply! */
	protected function log() {
		if (is_null(self::$logger)) {
	        // create a log channel

	        // TODO LOAD LOG HANDLERS FROM CONFIGURATION
	        $streamHandler = new FirePHPHandler();
	        $streamHandler->setFormatter(new WildfireFormatter());

	        self::$logger = new Logger('sysclass');
	        self::$logger->pushHandler($streamHandler);
		}
		return self::$logger;
    }
}
