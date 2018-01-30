<?php
namespace Sysclass\Collections\Requests;

use Plico\Mvc\Collection;

class Entry extends Collection {
	public function getSource() {
		return "http_requests";
	}

	public function beforeCreate() {
		$this->processed = false;
		$this->timestamp = time();
		//$this->_environment = $this->getDi()->get('environment')->name;
	}

	public function assign($data) {
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}
}