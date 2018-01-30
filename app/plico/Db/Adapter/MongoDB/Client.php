<?php

namespace Plico\Db\Adapter\MongoDB;

class Client extends \Phalcon\Db\Adapter\MongoDB\Client {
	public function selectDb($databaseName, array $options = []) {
		return $this->selectDatabase($databaseName, $options);
	}
}
