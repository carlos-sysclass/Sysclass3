<?php

namespace Plico\Cache\Backend;

class Mongo extends \Phalcon\Cache\Backend\Mongo {
	public function delete($keyName) {
		$this->_getCollection()->deleteOne(["key" => $this->_prefix . $keyName]);

		if (((int) rand()) % 100 == 0) {
			$this->gc();
		}

		return true;
	}

	public function gc() {
		return $this->_getCollection()->deleteMany(["time" => ["$lt" => time()]]);
	}
}
