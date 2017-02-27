<?php
/**
 * @package PlicoLib\Managers
 */
abstract class LoaderManager {
	protected static $load;

	protected static $resourceCache = array(
		'model' => array(),
		'module' => array(),
		'helper' => array(),
		'controler' => array()
	);

	protected function getResourceClassName($type, $resource)
	{
		if (!in_array($type, array_keys(self::$resourceCache))) {
			throw new Exception("Please, provide a valid resource type.");
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
			throw new Exception("Please, provide a valid resource type.");
		}
		if (!$noCached && array_key_exists($resource, self::$resourceCache[$type])) {
			return true;
		}

		$class_name = $this->getResourceClassName($type, $resource);

		return class_exists($class_name);
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

	public function module($module, $noCached=FALSE)
	{
		if (!$noCached && array_key_exists($module, self::$resourceCache["module"])) {
			return self::$resourceCache["module"][$module];
		}

		if ($this->resourceExists("module", $module)) {
			$class_name = $this->getResourceClassName("module", $module);
			//debug_print_backtrace();
			$class = new $class_name();

			self::$resourceCache["module"][$module] = $class;

			if (method_exists($class_name, "init")) {
				self::$resourceCache["module"][$module]->init();
			}
			return self::$resourceCache["module"][$module];
		}

		//$module_parts = explode("/", $module);
		//array_push($module_parts, "module");

		//array_walk($module_parts, function(&$n) {
  		//	$n = ucfirst($n);
		//});

		//$class_name = implode("", $module_parts);
		/*
		$plicolib = PlicoLib::instance();
		$class_name = $plicolib->camelCasefying($module, "_", false) . "Module";

		if (class_exists($class_name)) {
			self::$moduleCache[$module] = new $class_name;

			if (method_exists($class_name, "init")) {
				self::$moduleCache[$module]->init();
			}
			return self::$moduleCache[$module];
		} else {
			return false;
		}
		*/
	}
	public function getControllers($interface = null, $asObject = true) {
		$plico = PLicoLib::instance();

		$controllerObjects = array();

		$controllers = $plico->getArray("controller");

		foreach($controllers as $key => $controlDef) {
			if (is_string($controlDef)) {
				$control = $controlDef;
			} else {
				$control = $controlDef[0];
			}
			if (class_exists($control)) {
				$controllerObjects[$key] = new $control;

				if (!is_null($interface) && !($controllerObjects[$key] instanceof $interface)) {
					unset($controllers[$key]);
					unset($controllerObjects[$key]);
				}
			}
		}

		if ($asObject) {
			return $controllerObjects;
		} else {
			return $controllers;
		}

		$moduledir = $plico->getArray("controller");
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

	public function getModules($interface = null) {
		$plico = PLicoLib::instance();
		$moduledir = $plico->get("path/modules");
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

}
