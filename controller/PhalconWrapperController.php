<?php
/*
use Monolog\Logger;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\WildfireFormatter;
*/
use Phalcon\DI,
	Sysclass\Models\Settings,
	Sysclass\Services\Authentication\Exception as AuthenticationException,
	Phalcon\Mvc\Controller;



/**
 * Use this class to move code from plico lib controllers, after all it is resposible to manage the migration between frameworks
 */
abstract class PhalconWrapperController extends Controller
{
	public static $t = null;

    public function initialize()
    {
		if (is_null(self::$t)) {
			self::$t = $this->translate;
		}

    }

	public function beforeExecuteRoute() {
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


	protected function resolvePaths(array $files) {

		$depInject = DI::getDefault();
		$url = $depInject->get("url");

		$result = array();
		foreach($files as $file) {
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
		}
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
	// DatabaseManager
	// RequestManager
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

	public function getHttpData($args)
	{
		return $this->request->get();

	}
	// SessionManager
	public function getBasePath() {
		return $this->getContext('basePath');
	}
	public function getContext($key) {
		return $this->context[$key];
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
		if ($this->beforeDisplay() === FALSE) {
			$template = $this->template;
		}

		//$smarty = $this->getSmarty();
		$params = array();

		foreach (self::$tmplData as $key => $item) {
			//$params["T_" . strtoupper($key)] = $item;
			//$smarty->assign("T_" . strtoupper($key), $item);
			$this->view->setVar("T_" . strtoupper($key), $item);
		}


		echo $this->view->render($template);
		exit;

		if (is_null($template)) {
			var_dump($this->view->render($this->template, $params, true));

			//$this->getSmarty()->display($this->template);
		} else {
			var_dump($this->view->render($template, $params, true));
			//$this->getSmarty()->display($template);
		}

        var_dump($template);
		var_dump(1);
		exit;
		var_dump($a);

		$this->afterDisplay();

		return TRUE;

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


	protected function beforeDisplay()
	{
		$depInject = DI::getDefault();
		$assets = $depInject->get("assets");

		$plico = PlicoLib::instance();

        $layoutManager = $this->module("layout");

        //$leftbarMenu = $layoutManager->getMenuBySection("leftbar");
        //$this->putItem("leftbar_menu", $leftbarMenu);

		$this->onThemeRequest();

		if ($this->supress_scripts) {
			$this->clearScripts();
		} else {
			$styleSheets = $plico->getArray("resources/" . $this->theme . "/css");
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

		$cssHeaderAssets
			->join(true)
		    // The name of the final output
		    ->setTargetPath("resources/" . $filename)
		    // The script tag is generated with this URI
		    ->setTargetUri("resources/" . $filename)
		    ->addFilter(new Phalcon\Assets\Filters\Cssmin());

		foreach($css as $file) {
			$cssHeaderAssets->addCss($file, true);
		}

		$scripts = $this->resolvePaths(self::$_scripts);

		$jsFooterAssets = $assets->collection("jsFooter");

		// UNCOMMENT TO PROVIDE CSS MINIFICATION (MUST ADJUST @import inside)
		$filename = md5(implode(":", $scripts)) . ".js";
		
		$jsFooterAssets
			->join(true)
		    // The name of the final output
		    ->setTargetPath("resources/" . $filename)
		    // The script tag is generated with this URI
		    ->setTargetUri("resources/" . $filename)
		    ->addFilter(new Phalcon\Assets\Filters\Jsmin());
		
		foreach($scripts as $file) {
			$jsFooterAssets->addJs($file, true);
		}

		$assets->outputCss("cssHeader");

		$this->putData(array(
			//'scripts'			=> self::$_scripts,
			//'stylesheets'		=> $assets->outputCss("cssHeader"),
			'scripts'			=> $assets->outputJs("jsFooter"),
			'module_scripts'	=> self::$_moduleScripts,
			//'stylesheets'		=> self::$_css,
			'links'				=> $this->_links,
			'section_tpl'		=> self::$_sections_tpl
		));

		$this->putItem("stylesheet_target", $cssHeaderAssets->getTargetUri());
		$this->putItem("script_target", $jsFooterAssets->getTargetUri());

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

		// PROFILE EDIT
		/*
		$this->putData(array(
			'profile_url'	=> $this->getBasePath() . "profile/edit",
			'logout_url'	=> $this->getBasePath() . "logout.html"
		));
		*/
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
		return PlicoLib::instance()->get('theme');

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



}
