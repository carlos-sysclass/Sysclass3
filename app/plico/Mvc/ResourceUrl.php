<?php
namespace Plico\Mvc;

use Phalcon\Mvc\Url;

class ResourceUrl extends Url
{
	protected $_themes = array();
	protected $_check_existence = true;
	public function __construct(array $themes = null, $check_existence = true) {
		$this->_themes = $themes;

		$this->_check_existence = $check_existence;
	}
	public function get ($uri = NULL, $args = NULL, $local = NULL) {
		$func_args = func_get_args();
		if (count($func_args) >= 4) {
			$baseUri = $func_args[3];
		} else {
			$baseUri = $this->getBaseUri();
		}

		if (!$this->_check_existence) {
			$baseUri .= "/" . reset($this->_themes);
			return parent::get($uri, $args, $local, $baseUri);
		} else {

			foreach($this->_themes as $theme) {
				$file = $this->path("/" . $theme .  $uri);
				if (realpath($file)) {
					$baseUri .= "/" . $theme;
					return parent::get($uri, $args, $local, $baseUri);
				}
			}
			$baseUri .= "/" . end($this->_themes);
			return parent::get($uri, $args, $local, $baseUri);

		}
		// INJECT THEME
		
		var_dump($baseUri);

		
	}

	public function getStatic ($uri) {
		$route = parent::getStatic($uri);
		return $route;
	}
	public function path ($path = NULL) {
		$route = parent::path($path);
		return $route;
	}

}
