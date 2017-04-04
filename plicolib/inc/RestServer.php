<?php
/**
 * Constants used in RestServer Class.
 */
class RestFormat
{

	const PLAIN = 'text/plain';
	const HTML = 'text/html';
	const AMF = 'applicaton/x-amf';
	const JSON = 'application/json';
	const JAVASCRIPT = 'application/javascript';
	const XML = 'text/xml';
	static public $formats = array(
		'plain' => self::PLAIN,
		'txt' => self::PLAIN,
		'txt' => self::PLAIN,
		'html' => self::HTML,
		'amf' => self::AMF,
		'json' => self::JSON,
		'js' => self::JAVASCRIPT,
		'xml' => self::XML
	);
}

/**
 * Description of RestServer
 *
 * @author jacob
 */
class RestServer
{
	public $url;
	public $method;
	public $params;
	public $format;
	public $cacheDir = '.';
	public $realm;
	public $mode;
	public $root;

	protected $map = array();
	protected $descriptors = array(
		'summary'	=> array()
	);
	protected $errorClasses = array();
	protected $cached;

	protected static $handledController;

	/**
	 * The constructor.
	 *
	 * @param string $mode The mode, either debug or production
	 */
	public function getCurrentController() {
		return self::$handledController;
	}
	public function  __construct($mode='debug', $realm='Rest Server')
	{
		$this->mode = $mode;
		$this->realm = $realm;
		$dir = dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));

		if (strpos($dir, "/") == 0 && strlen($dir) > 1) {
			$dir = substr($dir, 1);
		}

		if ($dir == '.' || $dir == '/') {
			$this->root = '';
		} else {
			$this->root = $dir . '/';
		}

	}

	public function  __destruct()
	{
		if ($this->mode == 'production' && !$this->cached) {
			if (function_exists('apc_store')) {
				apc_store('urlMap', $this->map);
			} else {
				file_put_contents($this->cacheDir . '/urlMap.cache', serialize($this->map));
			}
		}

	}

	public function refreshCache()
	{
		$this->map = array();
		$this->cached = FALSE;

	}

	public function unauthorized($ask=FALSE)
	{
		if ($ask) {
			header("WWW-Authenticate: Basic realm=\"$this->realm\"");
		}

		throw new RestException(401, "You are not authorized to access this resource.");

	}

	public function handle()
	{

		$this->url = $this->getPath();
		$this->method = $this->getMethod();
		$this->format = $this->getFormat();

		if ($this->method == 'PUT' || $this->method == 'POST') {
			$this->data = $this->getData();
		}

		list($obj, $method, $params, $this->params, $noAuth, $basePath, $urlMatch) = $this->findUrl();

		if ($this->method == 'PUT' || $this->method == 'POST') {
			array_push($params, $this->data);
		}

		if ($obj) {
			if (is_string($obj)) {
				if (class_exists($obj)) {
					$obj = new $obj();
				} else {
					throw new Exception("Class $obj does not exist");
				}
			}
			self::$handledController = $obj;

			$obj->server = $this;

			try {
				if (method_exists($obj, 'init')) {
					$obj->init($this->url, $this->method, $this->format, $this->root, $basePath, $urlMatch);
				}

				if (!$noAuth && method_exists($obj, 'authorize')) {
					if (!$obj->authorize()) {
						$this->sendData($this->unauthorized(FALSE));
						exit;
					}
				}

				/// DO PHLACON REFLECTION AND CHECK FOR PERMISSION
				if (extension_loaded("phalcon")) {
					$continue = true;
					$has_annotation = false;
					$depinject = Phalcon\DI::getDefault();

					if ($depinject && $depinject->has("acl")) {

						$reader = new Phalcon\Annotations\Adapter\Memory();
						// Reflect the annotations in the class Example
						$reflector = $reader->get(get_class($obj));
						// Read the annotations in the class' docblock
						$annotations = $reflector->getMethodsAnnotations();
						//$annotation = $annotations->get($method);
						// Traverse the annotations
						foreach ($annotations[$method] as $annotation) {
							if ($annotation->getName() != "deny") {
								continue;
							}

							$has_annotation = true;

							$args = $annotation->getArguments();

							$allowed = $depinject->get("acl")->isUserAllowed(null, $args['resource'], $args['action']);
							// IF AT LEAST ONE deny HAS BEEN MATCHED, BLOCKED ACCESS
							if ($allowed) {
								$continue = false;
								break;
							}
						}
						if ($continue) {
							foreach ($annotations[$method] as $annotation) {
								if ($annotation->getName() != "allow") {
									continue;
								}
								$has_annotation = true;
								$args = $annotation->getArguments();
								$allowed = $depinject->get("acl")->isUserAllowed(null, $args['resource'], $args['action']);
								// IF AT LEAST ONE allow HAS BEEN MATCHED, OPEN ACCESS
								if ($annotation->getName() == "allow" && $allowed) {
									$continue = true;
									break;
								}
							}
						}
					}
				} else {
					$continue = true;
				}

				if (!$has_annotation || $continue) {
					$result = call_user_func_array(array($obj, $method), $params);
				} else {
					$this->sendData($this->unauthorized(FALSE));
					exit;
				}
			} catch (RestException $e) {
				$this->handleError($e->getCode(), $e->getMessage());
			}

			if ($result !== NULL) {
				$this->sendData($result);
			}
		} else {
			$this->handleError(404);
		}

	}

	public function addClass($class, $basePath='')
	{
		$this->loadCache();

		if (!$this->cached) {
			if (is_string($class) && !class_exists($class, TRUE)) {
				throw new Exception('Invalid method or class');
			} else if (!is_string($class) && !is_object($class)) {
				throw new Exception('Invalid method or class; must be a classname or object');
			}

			if (substr($basePath, 0, 1) == '/') {
				$basePath = substr($basePath, 1);
			}

			if ($basePath && substr($basePath, -1) != '/') {
				$basePath .= '/';
			}

			$this->generateMap($class, $basePath);
		}

	}

	public function addErrorClass($class)
	{
		$this->errorClasses[] = $class;

	}

	public function handleError($statusCode, $errorMessage=NULL)
	{
		$method = "handle$statusCode";
		foreach ($this->errorClasses as $class) {
			if (is_object($class)) {
				$reflection = new ReflectionObject($class);
			} else if (class_exists($class)) {
				$reflection = new ReflectionClass($class);
			}

			if ($reflection->hasMethod($method)) {
				if ((is_string($class))) {
					$obj = new $class();
				} else {
					$obj = $class;
				}
				self::$handledController = $obj;

				$obj->$method();
				return;
			}
		}

		$message = $this->codes[$statusCode];

		$message .= (($errorMessage && $this->mode == 'debug') ? ': ' . $errorMessage : '');

		$this->setStatus($statusCode);
		$this->sendData(array('error' => array('code' => $statusCode, 'message' => $message)));

	}

	protected function loadCache()
	{
		if ($this->cached !== NULL) {
			return;
		}

		$this->cached = FALSE;

		if ($this->mode == 'production') {
			if (function_exists('apc_fetch')) {
				$map = apc_fetch('urlMap');
			} else if (file_exists($this->cacheDir . '/urlMap.cache')) {
				$map = unserialize(file_get_contents($this->cacheDir . '/urlMap.cache'));
			}

			if ($map && is_array($map)) {
				$this->map = $map;
				$this->cached = TRUE;
			}
		} else {
			if (function_exists('apc_delete')) {
				apc_delete('urlMap');
			} else {
				@unlink($this->cacheDir . '/urlMap.cache');
			}
		}

	}

	protected function findUrl()
	{
		$urls = $this->map[$this->method];

		if (!$urls) {
			return NULL;
		}

		foreach ($urls as $url => $call) {
			$args = $call[2];

			if (!strpos($url, ':')) {
				if ($url == $this->url) {
					if (isset($args['data'])) {
						$params = array_fill(0, ($args['data'] + 1), NULL);
						$params[$args['data']] = $this->data;
						$call[2] = $params;
					}
					$call[6] = $url;
					return $call;
				}
			} else {
				$regex = preg_replace('/\\\\\:([\w\d]+)\.\.\./', '(?P<$1>.+)', str_replace('\.\.\.', '...', preg_quote($url)));
				$regex = preg_replace('/\\\\\:([\w\d]+)/', '(?P<$1>[^\/]+)', $regex);
				if (preg_match(":^$regex$:", urldecode($this->url), $matches)) {
					$params = array();
					$paramMap = array();
					if (isset($args['data'])) {
						$params[$args['data']] = $this->data;
					}

					foreach ($matches as $arg => $match) {
						if (is_numeric($arg)) {
							continue;
						}

						$paramMap[$arg] = $match;

						if (isset($args[$arg])) {
							$params[$args[$arg]] = $match;
						}
					}

					ksort($params);
					// Make sure we have all the params we need!
					end($params);
					$max = key($params);
					for ($i = 0; $i < $max; $i++) {
						if (!key_exists($i, $params)) {
							$params[$i] = NULL;
						}
					}

					ksort($params);
					$call[2] = $params;
					$call[3] = $paramMap;
					$call[6] = $url;

					return $call;
				}
			}
		}

	}

	protected function generateMap($class, $basePath)
	{
		if (is_object($class)) {
			$reflection = new ReflectionObject($class);
		} else if (class_exists($class)) {
			$reflection = new ReflectionClass($class);
		}

		$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

		$hasApiMethods = false;

		foreach ($methods as $method) {
			$doc = $method->getDocComment();

			$noAuth = strpos($doc, '@noAuth') !== FALSE;
			$publicApi = strpos($doc, '@api') !== FALSE;

			if ($publicApi) {
				$hasApiMethods = true;
			}
			if (preg_match_all('/@url[ \t]+(GET|POST|PUT|DELETE|HEAD|OPTIONS)[ \t]+\/?(\S*)/s', $doc, $matches, PREG_SET_ORDER)) {
				$params = $method->getParameters();

				foreach ($matches as $match) {
					$httpMethod = $match[1];
					$url = $basePath . $match[2];
					if ($url && $url[(strlen($url) - 1)] == '/') {
						$url = substr($url, 0, -1);
					}

					$call = array($class, $method->getName());
					$args = array();
					foreach ($params as $param) {
						$args[$param->getName()] = $param->getPosition();
					}

					$call[] = $args;
					$call[] = NULL;
					$call[] = $noAuth;
					$call[] = $basePath;

					if ($publicApi) {
						$path = strtolower(str_replace(
							array('Controller', 'Module'),
							array('', ''),
							$class
						));
						$swaggerPath = $path;
						if (!array_key_exists($swaggerPath, $this->descriptors)) {
							$this->descriptors[$swaggerPath] = array();
						}
						$swaggerUrl = $this->translateSwaggerUrl($url, $args);
						if (!array_key_exists($swaggerUrl, $this->descriptors[$swaggerPath])) {

							$this->descriptors[$swaggerPath][$swaggerUrl] = array(
								'path'			=> "/" . $this->translateSwaggerUrl($swaggerUrl, $args)
							);
						}
						if (!array_key_exists('operations', $this->descriptors[$swaggerPath][$swaggerUrl])) {
							$this->descriptors[$swaggerPath][$swaggerUrl]['operations'] = array();
						}
						//var_dump($args);

						$swaggerArgs = $this->translateSwaggerParams($args);

						// @todo Inject here code to grab another methods parameters
						if (preg_match_all('/@param[ \t]+?(\S*) (\S*) (\S*) ?([ \S]*)/s', $doc, $matches, PREG_SET_ORDER)) {
							foreach($matches as $match) {
								$swaggerArgs[] = array(
									'name'			=> $match[1],
									'description'	=> $match[4],
									'required'		=> false,
									'type'			=> $match[2],
									'paramType'		=> $match[3],
									'allowMultiple'	=> false
								);
							}
						}
						$this->descriptors[$swaggerPath][$swaggerUrl]['operations'][] = array(
							'method'			=> $httpMethod,
							'summary'			=> '',
							'notes'				=> '',
							'type'				=> '',
							'nickname'			=> $method->getname(),
							'authorizations'	=> '',
							'parameters'		=> $swaggerArgs
						);

					}

					$this->map[$httpMethod][$url] = $call;
				}
			}
		}
		if ($hasApiMethods) {
			// REDUCE CLASS NAME
			$path = str_replace(
				array('Controller', 'Module'),
				array('', ''),
				$class
			);

			$class_doc = $reflection->getDocComment();
			preg_match_all('/@description[ \t]+?([ \S]*)/s', $class_doc, $matches, PREG_SET_ORDER);

			$description = @$matches[0][1];

			$this->descriptors['summary'][] = array(
				'path'			=> "/" . strtolower($path),
				'description'	=> empty($description) ? "" : $description
			);
		}
		//var_dump($this->apiMap);
		//exit;
	}

	protected function translateSwaggerUrl($url, $args) {
		if (count($args) == 0) {
			return $url;
		}
		foreach($args as $key => $pos) {
			$url = str_replace(":" . $key, "{" . $key . "}", $url);
		}
		return $url;
	}
	protected function translateSwaggerParams($args) {
		$swaggerArgs = array();

		foreach($args as $key => $pos) {
			$swaggerArgs[] = array(
				'name'			=> $key,
				'description'	=> 'No description available',
				'required'		=> false,
				'type'			=> 'string',
				'paramType'		=> 'path',
				'allowMultiple'	=> false
			);
		}
		return $swaggerArgs;
	}

	public function getDescriptors($class = 'summary') {
		//var_dump($this->descriptors);
		return array_values($this->descriptors[$class]);
	}

	public function getPath()
	{
		$path = substr(preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']), 1);

		if ($path[(strlen($path) - 1)] == '/') {
			$path = substr($path, 0, -1);
		}

		// Remove root from path.
		if ($this->root) {
			$path = str_replace($this->root, '', $path);
		}

		// Remove trailing format definition, like /controller/action.json -> /controller/action!
		$path = preg_replace('/\.(\w+)$/i', '', $path);
		return $path;

	}

	public function getMethod()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$override
			= isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] : (isset($_GET['method']) ? $_GET['method'] : '');
		if ($method == 'POST' && strtoupper($override) == 'PUT') {
			$method = 'PUT';
		} else if ($method == 'POST' && strtoupper($override) == 'DELETE') {
			$method = 'DELETE';
		}

		return $method;

	}

	public function getFormat()
	{
		$format = RestFormat::PLAIN;
		// Ensures that exploding the HTTP_ACCEPT string does not get confused by whitespaces.
		$accept_mod = preg_replace('/\s+/i', '', $_SERVER['HTTP_ACCEPT']);
		$accept = explode(',', $accept_mod);

		$override = "json";

		if (isset($_REQUEST['format']) || isset($_SERVER['HTTP_FORMAT'])) {
			// Give GET/POST precedence over HTTP request headers!
			$override = isset($_SERVER['HTTP_FORMAT']) ? $_SERVER['HTTP_FORMAT'] : '';
			$override = isset($_REQUEST['format']) ? $_REQUEST['format'] : $override;
			$override = trim($override);
		}

		// Check for trailing dot-format syntax like /controller/action.format -> action.json!
		$matches = array();
		if (preg_match('/\.(\w+)$/i', $_SERVER['REQUEST_URI'], $matches)) {
			$override = $matches[1];
		}

		// Give GET parameters precedence before all other options to alter the format!
		$override = isset($_GET['format']) ? $_GET['format'] : $override;
		if (isset(RestFormat::$formats[$override])) {
			$format = RestFormat::$formats[$override];
		} else if (in_array(RestFormat::AMF, $accept)) {
			$format = RestFormat::AMF;
		} else if (in_array(RestFormat::JSON, $accept)) {
			$format = RestFormat::JSON;
		}

		return $format;

	}

	public function getData()
	{
		$data = file_get_contents('php://input');

		if ($this->format == RestFormat::AMF) {
			include_once 'Zend/Amf/Parse/InputStream.php';
			include_once 'Zend/Amf/Parse/Amf3/Deserializer.php';
			$stream = new Zend_Amf_Parse_InputStream($data);
			$deserializer = new Zend_Amf_Parse_Amf3_Deserializer($stream);
			$data = $deserializer->readTypeMarker();
		} else {
			$json = json_decode($data, TRUE);
			if (is_null($json)) {
				// TRY TO DECODE FROM QUERY STRING.
				parse_str(urldecode($data), $data);
			} else {
				$data = $json;
			}
		}

		return $data;

	}

	public function sendData($data)
	{
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: 0");
		header('Content-Type: ' . $this->format);

		if ($this->format == RestFormat::AMF) {
			include_once 'Zend/Amf/Parse/OutputStream.php';
			include_once 'Zend/Amf/Parse/Amf3/Serializer.php';
			$stream = new Zend_Amf_Parse_OutputStream();
			$serializer = new Zend_Amf_Parse_Amf3_Serializer($stream);
			$serializer->writeTypeMarker($data);
			$data = $stream->getStream();
		} else {
			if (is_object($data) && method_exists($data, '__keepOut')) {
				$data = clone $data;
				foreach ($data->__keepOut() as $prop) {
					unset($data->$prop);
				}
			}
			if ($this->format == RestFormat::XML) {
				$data = $this->xmlEncode($data);
			} else {
				$data = json_encode($data);
				if ($data && $this->mode == 'debug') {
					$data = $this->json_format($data);
				}
			}


		}

		echo $data;

	}

	public function setStatus($code)
	{
		$code .= ' ' . $this->codes[strval($code)];
		header("{$_SERVER['SERVER_PROTOCOL']} $code");

	}

	protected function xmlEncode($data, $topElement = "response") {
		$xml = $this->xmlEncodeElement($data, $topElement);

		$doc = simplexml_load_string($xml);
		//$doc->formatOutput = true;
		return $doc->asXML();
	}

	protected function xmlEncodeElement($element, $key) {
		if (is_array($element)) {
			foreach($element as $subkey => $subelement) {
				$result[] = $this->xmlEncodeElement($subelement, $subkey);
			}
			$value = implode(" ", $result);
		} else {
			$value = $element;
		}
		return sprintf('<%1$s>%2$s</%1$s>', $key, $value);
	}

	private function xml_format($xml) {

	}

	// Pretty print some JSON!
	private function json_format($json)
	{
		$tab = "  ";
		$new_json = "";
		$indent_level = 0;
		$in_string = FALSE;

		$len = strlen($json);

		for ($c = 0; $c < $len; $c++) {
			$char = $json[$c];
			switch($char) {
				case '{':
				case '[':
					if (!$in_string) {
						$new_json .= $char . "\n" . str_repeat($tab, ($indent_level + 1));
						$indent_level++;
					} else {
						$new_json .= $char;
					}
					break;
				case '}':
				case ']':
					if (!$in_string) {
						$indent_level--;
						$new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
					} else {
						$new_json .= $char;
					}
					break;
				case ',':
					if (!$in_string) {
						$new_json .= ",\n" . str_repeat($tab, $indent_level);
					} else {
						$new_json .= $char;
					}
					break;
				case ':':
					if (!$in_string) {
						$new_json .= ": ";
					} else {
						$new_json .= $char;
					}
					break;
				case '"':
					if ($c > 0 && $json[($c - 1)] != '\\') {
						$in_string = !$in_string;
					}

				default:
					$new_json .= $char;
					break;
			}
		}

		return $new_json;

	}


	private $codes = array(
		'100' => 'Continue',
		'200' => 'OK',
		'201' => 'Created',
		'202' => 'Accepted',
		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
		'205' => 'Reset Content',
		'206' => 'Partial Content',
		'300' => 'Multiple Choices',
		'301' => 'Moved Permanently',
		'302' => 'Found',
		'303' => 'See Other',
		'304' => 'Not Modified',
		'305' => 'Use Proxy',
		'307' => 'Temporary Redirect',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'409' => 'Conflict',
		'410' => 'Gone',
		'411' => 'Length Required',
		'412' => 'Precondition Failed',
		'413' => 'Request Entity Too Large',
		'414' => 'Request-URI Too Long',
		'415' => 'Unsupported Media Type',
		'416' => 'Requested Range Not Satisfiable',
		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'503' => 'Service Unavailable'
	);
}

class RestException extends Exception
{
	public function __construct($code, $message=NULL)
	{
		parent::__construct($message, $code);

	}

}
