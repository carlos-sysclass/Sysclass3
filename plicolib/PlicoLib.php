<?php
final class PlicoLib
{

	private static $instance = NULL;
	private $plicoLibDir;

	private $config;
	private $plicoConfig;
	private $appConfig;

	private $server = NULL;


	public static function instance($appRootDir=NULL)
	{
		if (is_null(self::$instance)) {
			if (is_null($appRootDir)) {
				throw new Exception('Application Directory is necessary to instantiante the library', 100);
				exit(100);
			}

			self::$instance = new PlicoLib($appRootDir);
			// LOAD APPLICATION CONFIG, IF EXISTS.
			if (file_exists($appRootDir . "/app.config.php") === TRUE) {
				include_once $appRootDir . "/app.config.php";
			}
		}

		return self::$instance;

	}

	public static function handler() {
		return RestServer::getCurrentController();
	}

	public function server() {
		return $this->server;
	}

	private function __construct($appRootDir)
	{
		$this->plicoLibDir = __DIR__ . "/";

		$appRootDir = realpath($appRootDir) . "/";

		$this->config = array(
			'default/theme'			=> 'default',
			'default/resource'		=> '/res/%s/',
			'theme' 				=> 'default',
			'client_name'			=> 'Default Client',
			'app_name'				=> 'Default App',
	        'app_version'			=> '0.1',
	        'app_description'		=> 'Default App Description',
	        'app_email'				=> 'maintainer@localhost',
	        'app_license'			=> 'Comercial',
	        'app_license_url'		=> '',
			'http/secure'			=> isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on',
			'http_host'				=> $_SERVER['HTTP_HOST'],
			'db_dsn'				=> NULL,
			'path/base'				=> $this->plicoLibDir,
			'path/lib'				=> $this->plicoLibDir . 'lib/',
			'path/themes'			=> $this->plicoLibDir . 'themes/',
			'path/core-modules'		=> $this->plicoLibDir . 'core-modules/',
			'path/modules'			=> $this->plicoLibDir . 'core-modules/',
			'path/template'			=> '%s/templates/',
			'path/plugins'			=> '%s/plugins/',
			'path/cache'			=> $this->plicoLibDir . 'cache/',
			'path/app'				=> realpath($appRootDir) . "/",
			'path/app/www'			=> $appRootDir . 'www',
			'path/files'			=> $appRootDir . 'files',
			'path/files/public'		=> $appRootDir . 'files',
			'path/files/private'	=> $appRootDir . 'files-private',

			'module/base_path'		=> 'module',
			"timestamp/format"		=> "d/m/Y \à\s H:i",
			'controller'			=> array(),
			'mail/send/isSMTP'		=> FALSE,
			// Enables SMTP debug information (for testing).
			'mail/send/debug'  		=> 0,
			// Enable SMTP authentication.
			'mail/send/do_auth' 	=> FALSE,
			// Sets the SMTP server.
			'mail/send/host'    	=> "mail.yourhost.com",
			// Set the SMTP port for the GMAIL server.
			'mail/send/port'		=> 25,
			// SMTP account username.
			'mail/send/user'   		=> "user@mail.yourhost.com",
			// SMTP account password.
			'mail/send/pass'   		=> "********",
			'mail/send/from/email'	=> 'user@mail.yourhost.com',
			'mail/send/from/name'	=> 'First Last',
			'resources/css'			=> array(),
			'resources/js'			=> array(),
			'resources/components'	=> array(),
			'default/homepage'		=> 'dashboard',
			'urls'					=> array()
		);

		//$this->config['http/secure'] = $this->config['https'] == 'https';
		$this->config['http/host'] = $this->config['http_host'];
		$this->config['http/fqdn'] = ($this->config['http/secure'] ? "https://" : "http://") . $this->config['http_host'];

	}

	public function start()
	{
		$appControllers = $this->getArray('controller');
		// Values: 'debug' or 'production'.
		$mode 	= 'debug';
		$this->server	= new RestServer($mode);
		// Uncomment momentarily to clear the cache if classes change in production mode.
		$this->server->refreshCache();

		foreach ($appControllers as $classe) {
			if (is_array($classe)) {
				$this->server->addClass($classe[0], $classe[1]);
			} else {
				$this->server->addClass($classe);
			}
		}
		$this->server->addErrorClass("ErrorController");

		//WRAP MODULE PATH TO CATCH SYSTEM MODULES
		$this->importModules($this->get('path/core-modules'));
		$this->importModules($this->get('path/modules'));

		$this->server->handle();
		exit;
	}

	private function checkCronIntervalItem($interval, $date_part) {
		if (strpos($interval, ",") !== FALSE) {
			$intervals = explode(",", $interval);
		} else {
			$intervals = array($interval);
		}

		$continue = false;
		foreach ($intervals as $interval) {
			if ($interval == "*" || $interval == $date_part) {
				$continue = true;
				break;
			}
			if (strpos($interval, "/") !== FALSE) {
				$math = str_replace("*", $date_part, $interval);
				$fatores = explode("/", $math);
				if ($fatores[0] % $fatores[1] == 0) {
					$continue = true;
					break;
				}
			}
		}
		return $continue;
	}

	private function isCronIntervalMatched($interval, $date) {
		list($i_minute, $i_hour, $i_day, $i_month, $i_weekday) = explode(" ", $interval);
		$continue = true;
		$continue = $continue && $this->checkCronIntervalItem($i_weekday, 	date("w"));
		$continue = $continue && $this->checkCronIntervalItem($i_month, 	date("m"));
		$continue = $continue && $this->checkCronIntervalItem($i_day, 		date("d"));
		$continue = $continue && $this->checkCronIntervalItem($i_hour, 		date("G"));
		$continue = $continue && $this->checkCronIntervalItem($i_minute, 	intval(date("i")));

		return $continue;
	}

	public function doCron($params = null)
	{
		$appControllers = $this->getArray('controller');
		// Values: 'debug' or 'production'.
		$mode 	= 'debug';

		$requestedController = null;

		if (is_array($params)) {
			$requestedController = $params[0];
		}

		$cronControllers = array();

		foreach ($appControllers as $classe) {
			if (is_array($classe)) {
				$controler = $classe[0];
			} else {
				$controler = $classe;
			}

			if (in_array("ICronable", class_implements($controler))) {
				$cronControllers[] = $controler;
			}
		}
		$date = time();

		foreach($cronControllers as $controller) {
			$control = new $controller();
			$control->init("cron", "cron", "json");

			$interval = $control->getInterval();
			if ($controller == $requestedController) {
				array_shift($params);
				$control->cronExecute($params);
			} elseif ($this->isCronIntervalMatched($interval, $date)) {
				$control->cronExecute();
			}
		}
		/*
		$this->server	= new RestServer($mode);
		// Uncomment momentarily to clear the cache if classes change in production mode.
		$this->server->refreshCache();

		foreach ($appControllers as $classe) {
			if (is_array($classe)) {
				$this->server->addClass($classe[0], $classe[1]);
			} else {
				$this->server->addClass($classe);
			}
		}
		$this->server->addErrorClass("ErrorController");

		//WRAP MODULE PATH TO CATCH SYSTEM MODULES
		$this->importModules($this->get('path/core-modules'));
		$this->importModules($this->get('path/modules'));

		$this->server->handle();
		*/
		exit;
	}

	protected function importModules($path) {
		if (is_dir($path)) {
			$paths = scandir($path);
			if (is_array($paths)) {
				foreach($paths as $module) {
					if ($module == '.' || $module == '..') {
						continue;
					}

					$class_name = $this->camelCasefying($module . "_module", "_", false);

					if (file_exists($path . $module . "/" . $class_name . ".php")) {
						$this->server->addClass($class_name, $this->get('module/base_path') . "/" . $module);
					}

				}
			}
		}
	}

	/**
	 * @deprecated 3.0.1.1 Use \Plico\Php\Helpers\Strings instead
	 */
    public function camelCasefying($string, $sep = "_", $firstLower = true)
    {
        $strings = explode($sep, $string);

        foreach ($strings as &$data) {
            $data = ucfirst($data);
        }
        if (count($strings) > 0 && $firstLower) {
            $strings[0] = strtolower($strings[0]);
        }

        return implode('', $strings);
    }

	/**
	 * @deprecated 3.0.1.1 Use \Plico\Php\Helpers\Strings instead
	 */
    public function camelDiscasefying($string, $sep = "_")
    {
    	$matches = array();
    	preg_match_all('/[A-Z]/', $string, $matches, PREG_OFFSET_CAPTURE);

    	$matches = array_reverse($matches[0]);

    	foreach($matches as $match) {
			$string = substr_replace(
				$string,
				($match[1] == 0) ? strtolower($match[0]) : ($sep . strtolower($match[0])),
				$match[1],
				1
			);
    	}
    	return $string;
    }


	public function get($index=NULL)
	{
		if (is_null($index)) {
			return $this->config;
		} else {
			if (is_array($this->config[$index])) {
				// Use object->getArray() to all values.
				return end($this->config[$index]);
			} else {
				return $this->config[$index];
			}
		}

	}

	public function getArray($index=NULL)
	{
		if (is_null($index)) {
			throw new Exception("Index $index does not exists", 101);
			exit(101);
		}

		if (array_key_exists($index, $this->config)) {
			if (!is_array($this->config[$index])) {
				return array($this->config[$index]);
			}
			return $this->config[$index];
		}

		return array();

	}

	public function set($index, $value)
	{
		$this->config[$index] = $value;
		return $this;

	}

	public function add($index, $value)
	{
		if (!array_key_exists($index, $this->config)) {
			$this->set($index, array($value));
			return $this;
		}
		if (array_key_exists($index, $this->config) && !is_array($this->config[$index])) {
			$this->config[$index] = array(
				$this->config[$index]
			);
		}

		$this->config[$index][] = $value;
		return $this;

	}

	public function concat($index, array $value)
	{
		if (!array_key_exists($index, $this->config)) {
			$this->set($index, $value);
			return $this;
		}
		if (array_key_exists($index, $this->config) && !is_array($this->config[$index])) {
			$this->config[$index] = array(
				$this->config[$index]
			);
		}

		$this->config[$index] = array_values(array_merge_recursive($this->config[$index], $value));
		return $this;

	}
}
