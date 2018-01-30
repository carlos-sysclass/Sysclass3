<?php
namespace Plico\Mvc;

class Collection extends \Phalcon\Mvc\MongoCollection {
	public function assign($data) {
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	public function toArray() {
		$array = parent::toArray();
		unset($array['_id']);
		if (is_object($this->_id)) {
			//$array['_id'] = $this->_id->oid;
			$array['id'] = (string) $this->_id;
		}

		return $array;
	}
}