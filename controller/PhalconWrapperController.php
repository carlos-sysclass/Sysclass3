<?php
/*
use Monolog\Logger;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\WildfireFormatter;
*/
use Phalcon\DI,
	Sysclass\Models\System\Settings,
	Sysclass\Services\Authentication\Exception as AuthenticationException,
	Phalcon\Mvc\Controller;



/**
 * Use this class to move code from plico lib controllers, after all it is resposible to manage the migration between frameworks
 */
abstract class PhalconWrapperController extends Controller
{
	public static $t = null;
	protected static $db;

	protected $disabledSections = array();

    public function initialize()
    {
		//if (is_null(self::$t)) {
		//	self::$t = $this->translate;
		//}

		$styleSheets = $this->environment["resources/css"];

		foreach($styleSheets as $css) {
			$this->putCss($css);
		}

		$scripts = $this->environment["resources/js"];
		//var_dump(get_class($this));
		foreach($scripts as $script) {
			$this->putScript($script);
		}

    }

	public function beforeExecuteRoute() {

	}


	protected function resolvePaths(array $files) {

		$depInject = DI::getDefault();
		$url = $depInject->get("url");

		$result = array();
		foreach($files as $file) {
			$file = $this->stringsHelper->vksprintf($file, ['locale' => $this->translate->getJsSource()]);

			if (strpos($file, "/") === 0) {
				$result[] = $file;
			} else {

				$templatedPath = $this->environment['default/resource'] . $file;

				$themedPath = sprintf($templatedPath, $this->environment->view->theme);

				if (file_exists($this->environment['path/app/www'] . $themedPath)) {
					$item = $themedPath;
				} else {
					$item = sprintf($templatedPath, 'default');
				}

				$result[] = substr($item, 1);

			}
		}
		return $result;
	}
	// LoaderManager

	protected static $resourceCache = array(
		'model' => array(),
		'module' => array(),
		'helper' => array(),
		'controler' => array()
	);

	public function module($module, $noCached=FALSE)
	{
		return $this->loader->module($module);
		$module = strtolower($module);

		//var_dump($module, self::$resourceCache["module"]);
		if (!$noCached && array_key_exists($module, self::$resourceCache["module"])) {
			return self::$resourceCache["module"][$module];
		}
		
		$class_name = sprintf(
			'Sysclass\\Modules\\%1$s\\%1$sModule',
			ucfirst($module)
		);

		if (class_exists($class_name)) {
			//debug_print_backtrace();
			$class = new $class_name();

			self::$resourceCache["module"][$module] = $class;

			if (method_exists($class_name, "init")) {
				self::$resourceCache["module"][$module]->init();
			}
			return self::$resourceCache["module"][$module];
		} else {
			return false;
		}
	}

	protected function getResourceClassName($type, $resource)
	{
		if (!in_array($type, array_keys(self::$resourceCache))) {
			throw new Exception("Please provide a valid resource type.");
		}
		$resource = str_replace("_", "/", $resource);
		$resource_parts = explode("/", $resource);
		if (count($resource_parts) == 1) {
			//array_push($resource_parts, reset($resource_parts));
		}
		array_push($resource_parts, $type);

		array_walk($resource_parts, function(&$n) {
  			$n = ucfirst($n);
		});

		return implode("", $resource_parts);

	}

	protected function resourceExists($type, $resource, $noCached=FALSE)
	{
		if (!in_array($type, array_keys(self::$resourceCache))) {
			throw new Exception("Please provide a valid resource type.");
		}
		if (!$noCached && array_key_exists($resource, self::$resourceCache[$type])) {
			return true;
		}

		$class_name = $this->getResourceClassName($type, $resource);

		return class_exists($class_name);
	}

	public function model($model, $noCached=FALSE)
	{
		if (!$noCached && array_key_exists($model, self::$resourceCache["model"])) {
			return self::$resourceCache["model"][$model];
		}

		if ($this->resourceExists("model", $model)) {
			$class_name = $this->getResourceClassName("model", $model);
			//debug_print_backtrace();
			$class = new $class_name();

			self::$resourceCache["model"][$model] = $class;

			if (method_exists($class_name, "init")) {
				self::$resourceCache["model"][$model]->init();
			}
			return self::$resourceCache["model"][$model];
		}
		// MAYBE RETURN HERE A SIMPLE MODEL, CREATING A METHOD TO PARAMETRIZING THE METHOD CREATION
		return false;
	}

	public function helper($helper, $noCached=FALSE)
	{
		if (!$noCached && array_key_exists($helper, self::$resourceCache["helper"])) {
			return self::$resourceCache["helper"][$helper];
		}

		if ($this->resourceExists("helper", $helper)) {
			$class_name = $this->getResourceClassName("helper", $helper);
			//debug_print_backtrace();
			$class = new $class_name();

			self::$resourceCache["helper"][$helper] = $class;

			if (method_exists($class_name, "init")) {
				self::$resourceCache["helper"][$helper]->init();
			}
			return self::$resourceCache["helper"][$helper];
		}
		// MAYBE RETURN HERE A SIMPLE MODEL, CREATING A METHOD TO PARAMETRIZING THE METHOD CREATION
		return false;
	}

	public function getModules($interface = null) {
		//$plico = PLicoLib::instance();
		$moduledir = $this->environment["path/modules"];

		$modulesList = scandir($moduledir);

		$modules = array();

		foreach($modulesList as $mod) {

			if ($mod == '.' || $mod == '..') {
				continue;
			}
			$module = $this->module($mod);
			if ($module) {
				$modules[$mod] = $module;
			}
		}

		if (!is_null($interface)) {
			foreach($modules as $key => $module) {
				if (!($module instanceof $interface)) {
					unset($modules[$key]);
				}
			}
		}

		return $modules;
	}
	// CacheManager
	protected function getSessionToken()
	{
		@session_start();
		if (array_key_exists('token', $_SESSION)) {
			return $_SESSION['token'];
		}
		return null;
	}

	protected function hasCache($name)
	{
		$token = $this->getSessionToken();
		if (array_key_exists($token, $_SESSION) && is_array($_SESSION[$token]) && array_key_exists($name, $_SESSION[$token])) {
			return true;
		}
		return false;

	}

	protected function getCache($name)
	{
		if ($this->hasCache($name)) {
			$token = $this->getSessionToken();
			return $_SESSION[$token][$name];
		}
		return NULL;

	}

	public function setCache($name, $value)
	{
		// TODO MANAGE CACHE EXPIRATION
		$token = $this->getSessionToken();
		if (!array_key_exists($token, $_SESSION) || !is_array($_SESSION[$token])) {
			$_SESSION[$token] = array();
		}
		$_SESSION[$token][$name] = $value;

		return $value;

	}

	// DatabaseManager
	public static function db() {

		if (is_null(self::$db)) {
			
			$plico = PLicoLib::instance();
			$DB_DSN = $plico->get('db_dsn');
			$charset = $plico->get("db/charset");

			$GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
			self::$db = &ADONewConnection($DB_DSN);
			if (!is_null($charset)) {
				switch(self::$db->databaseType) {
					case "mysql" :{
						mysql_set_charset($charset, self::$db->_connectionID);
						break;
					}
					case "postgres7" :{
						pg_set_client_encoding(self::$db->_connectionID, $charset);
					}
				}
			}
		}
		return self::$db;

	}

	// RequestManager
	protected function createResponse($code, $message, $type, $intent, $callback = null)
	{
		http_response_code($code);
		$error = array(
			"_response_" => array(
				"code" 		=> $code,
				"message"	=> $message,
				"type"		=> $type,
				"intent"	=> $intent,
				"data"		=> $callback
			)
		);
		return $error;
	}

	protected function createReloadResponse($message = null, $type = null, $code = 200) {
		if (!is_null($message)) {
			$this->putMessage($message, $type);
		}
		return $this->createResponse($code, $message, $type, "redirect");
	}


	protected function createRedirectResponse($location, $message = null, $type = null, $code = 200) {
		if (!is_null($message)) {
			$this->putMessage($message, $type);
		}
		return $this->createResponse($code, $message, $type, "redirect", $location);
	}

	protected function notAuthenticatedError()
	{
		debug_print_backtrace();
		return $this->createResponse(403, "You don't have access to this resource", "error", "advise");
	}


	public function redirect($location = null, $message="", $message_type="", $code = 301)
	{
		if (!empty($message)) {
			if (!in_array($message_type, array("info", "success", "warning", "error", ))) {
				$message_type = "info";
			}
			$this->putMessage($message, $message_type);
		}
		if (is_null($location)) {
			$location = $_SERVER['REQUEST_URI'];
		}
		if (strpos($location, "/") === 0 || strpos($location, "http://") == 0) {
			header("Location: " . $location);
		} else {
			header("Location: " . $this->getBasePath() . $location);
		}
		exit;
	}

	protected function createAdviseResponse($message, $type)
	{
		return $this->createResponse(200, $message, $type, "advise");
	}

	protected function createNonAdviseResponse($message, $type = "ACK")
	{
		return $this->createResponse(200, $message, $type, 'info');
	}

	protected function invalidRequestError($message = "", $type = "warning") {
		if (empty($message)) {
			$message = $this->translate->translate("There's a problem with your request. Please try again.");
		}
		return $this->createResponse(200, $message, $type, "advise");
	}

	protected function entryPointNotFoundError($redirect = null)
	{
		if (!is_null($redirect)) {
			return $this->redirect($redirect, "Rota não encontrada", "error", 404);
		}
		return $this->createResponse(404, "Não encontrado", "error", "advise");
	}



	public function getHttpData($args)
	{
		if ($this->request->isAjax()) {
			return $this->request->getJsonRawBody(true);
		} else {
			return $this->request->get();
		}

	}
	// SessionManager
	public function getBasePath() {
		return $this->getContext('basePath');
	}
	public function getContext($key) {
		return $this->context[$key];
	}
	public function getMatchedUrl() {
		return $this->getContext('urlMatch');
	}


	// ViewableContentManager
	protected static $tmplData = array();

	public function putItem($key, $value)
	{
		$this->putData(array($key => $value));
		//$this->view->setVar($key, $value);

	}

	protected function putData(array $data)
	{
		self::$tmplData = array_merge(self::$tmplData, $data);
		//$this->view->setVars($data);

	}

	protected function display($template=NULL)
	{
		if ($this->beforeDisplay() === FALSE && is_null($template)) {
			//var_dump($template);
			$template = $this->template;
		}
		
		//$smarty = $this->getSmarty();
		$params = array();

		foreach (self::$tmplData as $key => $item) {
			//$params["T_" . strtoupper($key)] = $item;
			//$smarty->assign("T_" . strtoupper($key), $item);
			$this->view->setVar("T_" . strtoupper($key), $item);
		}

		$this->view->setVar("T_TRACKING_TAG_SCRIPT", $this->tracking->generateTrackingTag());

 		if (is_null($template)) {
			$this->view->render($this->template);

			//$this->getSmarty()->display($this->template);
		} else {
			$this->view->render($template);
			//$this->getSmarty()->display($template);
		}

		$this->afterDisplay();

		$this->response->setContent($this->view->getContent());

		//$this->response->send();

		return TRUE;

	}



	protected function beforeDisplay()
	{

		if ($this->translate->inTranslationMode()) {
			$this->putBlock("translate.page.editor");
		}

		// IF CHAT MODULE IS ENABLED
		$depInject = DI::getDefault();
		/*
		$acl = $depInject->get("acl");
		if ($acl->isUserAllowed(null, "Chat", "Support")) {
			$this->putBlock("chat.quick-sidebar");
		}
		*/

		$assets = $depInject->get("assets");

		$plico = PlicoLib::instance();

        $layoutManager = $this->module("layout");

        //$leftbarMenu = $layoutManager->getMenuBySection("leftbar");
        //$this->putItem("leftbar_menu", $leftbarMenu);
		$this->onThemeRequest();

		if ($this->supress_scripts) {
			$this->clearScripts();
		} else {

			//$styleSheets = $plico->getArray("resources/" . $this->theme . "/css");

			$styleSheets = $this->environment['resources/css'];

			//var_dump("resources/" . $this->theme . "/css", $styleSheets);
			//exit;
			foreach($styleSheets as $css) {
				$this->putCss($css);
			}

			$scripts = $plico->getArray("resources/" . $this->theme . "/js");
			foreach($scripts as $script) {
				$this->putScript($script);
			}
		}

		// INJECT BLOCK TEMPLATES
		$this->putItem("block_templates", self::$blockResults);

		$user = $this->getCurrentUser(true);

		if ($user) {
			//$this->putData(array('user' => $this->model("profile")->getItemById($user['id'])));
			$this->putItem("user", $user->toArray());
		}

		$this->putData(array('context'	=> $this->context));

		if ($this instanceof IBreadcrumbable) {
			$this->breadcrumbs = $this->getBreadcrumb();
			$this->putData(array('breadcrumbs'	=> $this->breadcrumbs));
		}

		if ($this instanceof IActionable) {
			$this->actions = $this->getActions();
			$this->putData(array('actions'	=> $this->actions));
		}



		$controllers = $plico->getArray('controller');

		foreach ($controllers as $control) {
			if (is_array($control)) {
				$theClass = $control[0];
			} else {
				$theClass = $control;
			}

			if (class_exists($theClass) && is_subclass_of($theClass, '\PageController')) {
				$menu_item = call_user_func(array($theClass, 'getMenuOption'));
				if ($menu_item) {
					if (!is_array($menu_item['themes']) || in_array($this->theme, $menu_item['themes'])) {
						// CHECK FOR SELECTED
						$menu_item['selected'] = $menu_item['selected'] || (strpos("/" . $this->getRequestedUrl(), $this->getBasePath() . $menu_item['url']) !== FALSE);

						$this->menu[] = $menu_item;
					}
				}
			}
		}
		$this->putData(array('menu'	=> $this->menu));

		$assets->useImplicitOutput(false);



		$css = $this->resolvePaths(self::$_css);


		$cssHeaderAssets = $assets->collection("cssHeader");

		// UNCOMMENT TO PROVIDE CSS MINIFICATION (MUST ADJUST @import inside)
		$filename = md5(implode(":", $css)) . ".css";

		
		if (!$this->environment->run->debug) {
		
			$cssHeaderAssets
				->join(true)
			    // The name of the final output
			    ->setTargetPath("resources/" . $filename)
			    // The script tag is generated with this URI
			    ->setTargetUri("resources/" . $filename)
			    ->addFilter(new Phalcon\Assets\Filters\Cssmin());
		}

		foreach($css as $file) {
			$cssHeaderAssets->addCss($file, true);
		}

		//var_dump($css);
		//exit;

		if ($this->environment->run->debug) {
			$this->putItem('allstylesheets', $assets->outputCss("cssHeader"));
		} else {
			$assets->outputCss("cssHeader");

			$this->putItem("stylesheet_target", $cssHeaderAssets->getTargetUri());
		}

		$scripts = $this->resolvePaths(self::$_scripts);

		$jsFooterAssets = $assets->collection("jsFooter");

		// UNCOMMENT TO PROVIDE CSS MINIFICATION (MUST ADJUST @import inside)
		$filename = md5(implode(":", $scripts)) . ".js";
		
		if (!$this->environment->run->debug) {
			$jsFooterAssets
				->join(true)
			    // The name of the final output
			    ->setTargetPath("resources/" . $filename)
			    // The script tag is generated with this URI
			    ->setTargetUri("resources/" . $filename)
			    ->addFilter(new Phalcon\Assets\Filters\Jsmin());
		}

		foreach($scripts as $file) {
			$jsFooterAssets->addJs($file, true);
		}

		if ($this->environment->run->debug) {
			$this->putItem('allscripts', $assets->outputJs("jsFooter"));
		} else {
			$assets->outputJs("jsFooter");

			$this->putItem("script_target", $jsFooterAssets->getTargetUri());
		}

		$this->putData(array(
			'module_scripts'	=> self::$_moduleScripts,
			'links'				=> $this->_links,
			'section_tpl'		=> self::$_sections_tpl
		));

		$message = $this->getMessage();

		if ($message) {
			$this->putData(array('message'	=> $message));
		}

		$this->putData(array('widgets'	=> $this->widgets));

		$templatedPath = $plico->get('default/resource');
		$resourcePath = sprintf($templatedPath, $plico->get('theme'));

		$this->putData(array('path' => array(
			'resource' => $resourcePath
		)));

		$this->putItem("configuration", $this->configuration->asArray());
		// PROFILE EDIT
		/*
		$this->putData(array(
			'profile_url'	=> $this->getBasePath() . "profile/edit",
			'logout_url'	=> $this->getBasePath() . "logout.html"
		));
		*/

		$this->putItem("disabled_sections", $this->disabledSections);
	
		return true;
	}

	protected function afterDisplay()
	{
		if ($this->translate->inTranslationMode()) {
			// INJECT SCRIPT FOR 
			//var_dump($this->translate->getTranslatedTokens());
			//exit;
		}
	}

	protected function getMessage() {
		if (!isset($_SESSION)) {
			session_start();
		}
		if (array_key_exists('message', $_SESSION)) {
			$message = $_SESSION['message'];
			unset($_SESSION['message']);
			return $message;
		}
		return false;
	}

	protected function putMessage($message, $message_type = 'info') {
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION['message']		= array(
			'message'	=> $message,
			'type'		=> $message_type
		);

	}

    public function translateHttpResource($file) {
        $plico = PlicoLib::instance();
        $templatedPath = $this->environment['default/resource'] . $file;

        $themedPath = sprintf($templatedPath, $this->environment->view->theme);

        if (file_exists($this->environment['path/app/www'] . $themedPath)) {
            return $themedPath;
        } else {
            return sprintf($templatedPath, $this->environment['default/theme']);
        }
    }






	// PageController
	protected static $_css = array();
	protected static $_scripts = array();

	private static $blockClosures = NULL;
	protected static $blockResults = array();

	protected static $_moduleScripts = array();
	protected static $_sections_tpl = array();


	/**
	 * [$widgets description]
	 * @var array
	 * @deprecated Use Block sctruct instead
	 */
	protected $widgets = array();



	protected function onThemeRequest()
	{
		$this->theme = PlicoLib::instance()->get('theme');

		return $this->theme;

	}

	protected function putCss($script)
	{
		self::$_css[$script] = $script . ".css";
		return $this;

	}

	public function putScript($script, $suffix = "js")
	{
		self::$_scripts[$script] = $script . "." . $suffix;
		return $this;

	}

	public function putModuleScript($script)
	{
		self::$_moduleScripts[$script] = $script . ".js";
		return $this;

	}


	public function addWidget($type, $data, $weight=12) {
		$this->widgets[] = array(
			'type'		=> $type,
			'data'		=> $data,
			'weight'	=> $weight
		);
	}

	public function putBlock($name, $data = null) {
		if (is_null(self::$blockClosures)) {
			$blockableModules = $this->getModules("IBlockProvider");
			self::$blockClosures = array();
			foreach ($blockableModules as $key => $module) {
				$mod_blocks = $module->registerBlocks();
				$mod_closures = array();
				foreach($mod_blocks as $key => $closure) {
					$mod_closures[$key] = array(
						'closure'	=> $closure,
						'self'		=> $module,
					);
				}
				self::$blockClosures = array_merge(self::$blockClosures, $mod_closures);
			}
		}
		if (array_key_exists($name, self::$blockClosures)) {
			$block = self::$blockClosures[$name];

			$callback = $block['closure'];
			return self::$blockResults[$name] = $callback($data, $block['self']);

		}

		return false;
    }

	public function putComponent() {
		//if (func_num_args() > 1) {
		$names = func_get_args();
		//} else {
		//	$names = array($name);
		//}

		//$plico = PlicoLib::instance();
		$components = $this->environment['resources/components'];
		//$components = $plico->getArray("resources/components");

		foreach ($names as $name) {
			$inject = false;
			if (array_key_exists($name, $components)) {
				$inject = $components[$name];
			}

			if ($inject) {
				if (array_key_exists('css', $inject)) {
					foreach ($inject['css'] as $stylesheet) {
						$this->putCss($stylesheet);
					}
				}
				if (array_key_exists('js', $inject)) {
					foreach ($inject['js'] as $script) {
						$this->putScript($script);
					}
				}
			}
		}
	}

	public function putSectionTemplate($key, $tpl, $type = "smarty") {
		if (!array_key_exists($key, self::$_sections_tpl)) {
			self::$_sections_tpl[$key] = array();
		}
		if ($type == "volt") {
			$ext = ".volt";
		} else {
			$ext = ".tpl";
		}
		self::$_sections_tpl[$key][$tpl] = $tpl . $ext;
		return $this;
	}


	public function disableSection($section_id) {
		$this->disabledSections[$section_id] = true;
	}

}
