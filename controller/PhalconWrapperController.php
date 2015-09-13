<?php
/*
use Monolog\Logger;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\WildfireFormatter;
*/
use Phalcon\DI,
	Sysclass\Models\Settings,
	Sysclass\Services\Authentication\Exception as AuthenticationException;



/**
 * Use this class to move code from plico lib controllers, after all it is resposible to manage the migration between frameworks
 */
abstract class PhalconWrapperController extends PageController
{
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

		//var_dump($jsFooterAssets->getResources());

		$this->putData(array(
			//'scripts'			=> self::$_scripts,
			'scripts'			=> $assets->outputJs("jsFooter"),
			'module_scripts'	=> self::$_moduleScripts,
			//'stylesheets'		=> self::$_css,
			'stylesheets'		=> $assets->outputCss("cssHeader"),
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

	protected function resolvePaths(array $files) {

		$depInject = DI::getDefault();
		$url = $depInject->get("url");

		$result = array();
		foreach($files as $file) {
			if (strpos($file, "/") === 0) {
				$result[] = $file;
			} else {
				$plico = PlicoLib::instance();
				$templatedPath = $plico->get('default/resource') . $file;

				$themedPath = sprintf($templatedPath, $plico->get('theme'));

				//var_dump($plico->get('path/app/www') . $themedPath);
				if (file_exists($plico->get('path/app/www') . $themedPath)) {
					$item = $themedPath;
				} else {
					$item = sprintf($templatedPath, $plico->get('default/theme'));
				}

				$result[] = substr($item, 1);

			}
		}
		return $result;
	}
}
