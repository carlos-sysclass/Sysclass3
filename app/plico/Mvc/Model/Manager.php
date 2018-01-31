<?php
namespace Plico\Mvc\Model;

use Phalcon\DI;
use Phalcon\Mvc\Model\Manager as ModelManager;

class Manager extends ModelManager {
	public function initialize(\Phalcon\Mvc\ModelInterface $model) {
		$di = DI::getDefault();
		$this->_cache = $di->get("cacheReusable");

		return parent::initialize($model);
	}
	/**
	 * Returns a reusable object from the cache
	 *
	 * @param string $modelName
	 * @param string $key
	 * @return object
	 */
	public function getReusableRecords($modelName, $key) {
		if ($this->_cache->exists($key)) {
			return $this->_cache->get($key);
		}

		// For the rest, use the memory cache
		return parent::getReusableRecords($modelName, $key);
	}

	/**
	 * Stores a reusable record in the cache
	 *
	 * @param string $modelName
	 * @param string $key
	 * @param mixed $records
	 */
	public function setReusableRecords($modelName, $key, $records) {
		$this->_cache->save($key, $records);
		//return;

		// For the rest, use the memory cache
		parent::setReusableRecords($modelName, $key, $records);
	}
}
