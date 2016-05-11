<?php
/**
 * @package PlicoLib\Controllers
 */
abstract class PageController extends ViewableContentManager
{
	protected $menu = array();
	protected $breadcrumbs = array();
	protected $actions = array();

	private static $blockClosures = NULL;
	protected static $blockResults = array();
	/**
	 * [$widgets description]
	 * @var array
	 * @deprecated Use Block sctruct instead
	 */
	protected $widgets = array();
	/**
	 * [$treeLevel description]
	 * @var array
	 * @deprecated Use IBreadcrumbable interface methods instea[d
	*/
	protected $treeLevel = array();


	protected static $_scripts = array();
	private $supress_scripts = false;
	protected static $_moduleScripts = array();
	protected static $_css = array();
	protected static $_sections_tpl = array();
	protected $_links = array();

	public static function getMenuOption()
	{
		return FALSE;

	}

	protected function onThemeRequest()
	{
		return PlicoLib::instance()->get('theme');

	}

	protected function setConfig()
	{
		// MOVE THIS TO ADMIN CONFIGURATION!
		$config = array(
			'config'	=> array(
				'dev'				=> FALSE,
				'show_locales'		=> FALSE,
				'show_themer'		=> FALSE,
				'skin'				=> "ssra",
				'gen_time'			=> time(0),
				'container_class'	=> ''
			)
		);
		$this->putData($config);

	}

	public function onUnauthorized()
	{
		$this->putMessage("Você não está autorizado a acessar este recurso", "error");
		$this->redirect("/login");
	}

	public function init($url, $method, $format, $root=NULL, $basePath="", $urlMatch = null)
	{
		parent::init($url, $method, $format, $root, $basePath, $urlMatch);

		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN)!
		$plico = PlicoLib::instance();

		$this->setTheme($plico->get('theme'));

		$this->putData(array('client_name'	=> $plico->get('client_name')));
		$this->putData(array('app_name'		=> $plico->get('app_name')));

		$styleSheets = $plico->getArray("resources/css");

		foreach($styleSheets as $css) {
			$this->putCss($css);
		}

		$scripts = $plico->getArray("resources/js");
		//var_dump(get_class($this));
		foreach($scripts as $script) {
			$this->putScript($script);
		}

		$this->onThemeRequest();

		$styleSheets = $plico->getArray("resources/" . $this->theme . "/css");
		foreach($styleSheets as $css) {
			$this->putCss($css);
		}

		$scripts = $plico->getArray("resources/" . $this->theme . "/js");
		foreach($scripts as $script) {
			$this->putScript($script);
		}
	}

	protected function clearScripts() {
		self::$_scripts = array();
		$this->supress_scripts = true;
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

	protected function putCss($script)
	{
		self::$_css[$script] = $script . ".css";
		return $this;

	}

	protected function putLink($link) {
		$this->_links[] = $link;
		return $this;
	}

	public function putSectionTemplate($key, $tpl) {
		if (!array_key_exists($key, self::$_sections_tpl)) {
			self::$_sections_tpl[$key] = array();
		}
		self::$_sections_tpl[$key][$tpl] = $tpl . ".tpl";
		return $this;
	}


	public function putComponent() {
		//if (func_num_args() > 1) {
		$names = func_get_args();
		//} else {
		//	$names = array($name);
		//}

		$plico = PlicoLib::instance();
		$components = $plico->getArray("resources/components");

		foreach ($names as $name) {
			$inject = false;
			foreach($components as $component) {
				if ($component['name'] == $name) {
					$inject = $component;
					break;
				}
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

	/**
	 * Add a widget to the page
	 * @param [type]  $type   [description]
	 * @param [type]  $data   [description]
	 * @param integer $weight [description]
	 * @deprecated Use PutBlock instead
	 */
	public function addWidget($type, $data, $weight=12) {
		$this->widgets[] = array(
			'type'		=> $type,
			'data'		=> $data,
			'weight'	=> $weight
		);
		$this->loadDependencies($type);

	}
	/**
	 * Add a widget to the page
	 * @param [type]  $type     [description]
	 * @param [type]  $template [description]
	 * @param array   $data     [description]
	 * @param integer $weight   [description]
	 * @param array   $dep      [description]
	 * @deprecated Use PutBlock instead
	 */
	public function addBlock($type, $template, $data=array(), $weight=12, $dep=array()) {
		$this->widgets[] = array(
			'type'			=> $type,
			'template'		=> "block/" . $template,
			'data'			=> $data,
			'weight'		=> is_null($weight) ? 12 : $weight,
			'dependencies'	=> $dep
		);

		foreach($dep as $type) {
			$this->loadDependencies($type);
		}

	}
	/**
	 * [treeLevel description]
	 * @param  [type] $treePath [description]
	 * @return [type]           [description]
	 * @deprecated Use IBreadcrumbable interface methods instead
	 */
	public function treeLevel($treePath) {
		$this->treeLevel = explode("/", $treePath);
	}


	protected function beforeDisplay()
	{
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

		$user = $this->getCurrentUser();

		if ($user) {
			$this->putData(array('user' => $this->model("profile")->getItemById($user['id'])));
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

			if (class_exists($theClass) && is_subclass_of($theClass, 'PageController')) {
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
		/*
		$url_parts = array_merge($this->treeLevel, explode("/", $this->getRequestedUrl()));

		$lastMenu = array();

		foreach($this->menu as $menu) {
			foreach($url_parts as $item) {
				if ($item == $menu['url']) {
					$this->breadcrumbs[] = array(
						'text'	=> $menu['text'],
						'icon'	=> isset($menu['icon']) ? $menu['icon'] : false,
						'href'	=> $this->getBasePath() . $menu['url']
					);

					$lastMenu[] = $menu['url'];
					continue;
				}
				if (isset($menu['sub'])) {
					foreach($menu['sub'] as $submenu) {
						if (in_array($menu['url'], $lastMenu) && $item == $submenu['url']) {
							$this->breadcrumbs[] = array(
								'text'	=> $submenu['text'],
								'icon'	=> isset($submenu['icon']) ? $submenu['icon'] : false,
								'href'	=> $this->getBasePath() . $menu['url'] . "/" . $submenu['url']
							);
						}
					}
				}
			}
		}

		$this->putData(array('breadcrumbs'	=> $this->breadcrumbs));
		*/

		$this->putData(array(
			'scripts'			=> self::$_scripts,
			'module_scripts'	=> self::$_moduleScripts,
			'stylesheets'		=> self::$_css,
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

		// PROFILE EDIT
		$this->putData(array(
			'profile_url'	=> $this->getBasePath() . "profile/edit",
			'logout_url'	=> $this->getBasePath() . "logout.html"
		));
	}

	protected function afterDisplay()
	{
		$this->putData(array('menu'	=> $this->menu));

	}

	protected function beforeFetch()
	{
		return TRUE;

	}

	protected function afterFetch()
	{
		return TRUE;

	}
	/**
	  * @todo Incluir um método de carregamento de dependencias mais limpo
	  * @deprecated Use the block/component/script/css structure
	 */
	protected function loadDependencies($type)
	{
		switch($type) {
			case "wysiwyg": {
				$this->putScript("js/wysihtml5-0.3.0_rc2");
				$this->putScript("js/wysihtml5-0.3.0_rc2.bootstrap");
				break;
			}
			case "validate": {
				$this->putScript("js/jquery.validate");
				$this->putScript("js/jquery.validate.methods");
				break;
			}
			case "mask": {
				$this->putScript("js/jquery.mask");
				break;
			}
		}

	}

}
