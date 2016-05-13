<?php
/**
 * @package PlicoLib\Managers
 */
abstract class ViewableContentManager extends SessionManager {
	private static $smarty = NULL;
	protected $theme	= 'default';
	protected $template = 'common/pages/not-found.tpl';
	protected static $tmplData = array();

	abstract protected function beforeDisplay();
	abstract protected function afterDisplay();
	abstract protected function beforeFetch();
	abstract protected function afterFetch();
	abstract protected function onThemeRequest();
	abstract protected static function getMenuOption();

	protected function getTheme()
	{
		return $this->theme;

	}

	protected function setTheme($theme)
	{
		$this->theme = $theme;
		$plico = PlicoLib::instance();
		$plico->set("theme", $this->theme);

		$this->refreshSmartyThemes();
	}

	public function putItem($key, $value)
	{
		$this->putData(array($key => $value));

	}

	protected function putData(array $data)
	{
		self::$tmplData = array_merge(self::$tmplData, $data);

	}

	protected static function refreshSmartyThemes() {
		if (!is_null(self::$smarty)) {
			$plico = PlicoLib::instance();

			$themesPath = array_reverse($plico->getArray('path/themes'));

			$smartyTemplates = array();
			$smartyPlugins = array();

			$smartyPlugins[] = $plico->get('path/lib') . "smarty/plugins/";

			foreach ($themesPath as $theme) {
				$smartyTemplates[] 	= sprintf($theme . $plico->get('path/template'), $plico->get('theme'));
				$smartyTemplates[] 	= sprintf($theme . $plico->get('path/template'), $plico->get('default/theme'));
				$smartyPlugins[] 	= sprintf($theme . $plico->get('path/plugins'), $plico->get('theme'));
				$smartyPlugins[] 	= sprintf($theme . $plico->get('path/plugins'), $plico->get('default/theme'));
			}

			self::$smarty->setTemplateDir($smartyTemplates);
			self::$smarty->setPluginsDir($smartyPlugins);
		}
	}

	protected function getSmartyPaths() {

		return array($smartyTemplates, $smartyPlugins);
	}

	public static function getSmarty($force=FALSE, $BC=false)
	{
		$plico = PlicoLib::instance();

		if ($BC) {
			if (!class_exists('SmartyBC', FALSE)) {
				include_once $plico->get('path/lib') . 'smarty/SmartyBC.class.php';
				$smartyClass = "SmartyBC";
			}

		} else {
			if (!class_exists('Smarty', FALSE)) {
				include_once $plico->get('path/lib') . 'smarty/Smarty.class.php';
				$smartyClass = "Smarty";
			}
		}

		if ($force || is_null(self::$smarty)) {
			self::$smarty = new $smartyClass();
			self::$smarty->force_compile 		= TRUE;
			self::$smarty->debugging 			= FALSE;
			self::$smarty->caching 			= FALSE;
			self::$smarty->cache_lifetime 	= 120;

			self::refreshSmartyThemes();

			self::$smarty->setCompileDir($plico->get('path/cache') . 'smarty/compiled');
			self::$smarty->setCacheDir($plico->get('path/cache') . 'smarty/cache');

		}

		return self::$smarty;

	}

	protected function template_exists($template) {
		//$smarty = $this->getSmarty();
		return $this->getSmarty()->templateExists($template);
	}

	protected function display($template=NULL)
	{
		if ($this->beforeDisplay() === FALSE) {
			$template = $this->template;
		}


		$smarty = $this->getSmarty();

		foreach (self::$tmplData as $key => $item) {
			$smarty->assign("T_" . strtoupper($key), $item);
		}

		if (is_null($template)) {
			$this->getSmarty()->display($this->template);
		} else {
			$this->getSmarty()->display($template);
		}
		exit;

		$this->afterDisplay();

		return TRUE;

	}

	protected function fetch($template=NULL)
	{
		$smarty = $this->getSmarty();

		$this->beforeFetch();

		foreach (self::$tmplData as $key => $item) {
			$smarty->assign("T_" . strtoupper($key), $item);
		}

		if (is_null($template)) {
			$fetchedTpl = $this->getSmarty()->fetch($this->template);
		} else {
			$fetchedTpl = $this->getSmarty()->fetch($template);
		}

		$this->afterFetch();

		return $fetchedTpl;

	}

	protected function getResourcePath($file, $theme=NULL)
	{
		$plico = PlicoLib::instance();

		if (is_null($theme)) {
			$theme = $plico->get('theme');
		}

		$templatedPath = $plico->get('default/resource') . $file;

		$themedPath = sprintf($templatedPath, $theme);

		if (file_exists($plico->get('path/app/www') . $themedPath)) {
			return $themedPath;
		} else {
			return sprintf($templatedPath, $plico->get('default/theme'));
		}

	}

	protected function getFullResourcePath($file, $theme=NULL)
	{
		$plico = PlicoLib::instance();

		return $plico->get('path/app/www') . $this->getResourcePath($file, $theme);

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
        $templatedPath = $plico->get('default/resource') . $file;

        $themedPath = sprintf($templatedPath, $plico->get('theme'));

        if (file_exists($plico->get('path/app/www') . $themedPath)) {
            return $themedPath;
        } else {
            return sprintf($templatedPath, $plico->get('default/theme'));
        }
    }

}
