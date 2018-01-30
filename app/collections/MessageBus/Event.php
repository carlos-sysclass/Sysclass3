<?php
namespace Sysclass\Collections\MessageBus;

use Plico\Mvc\Collection;

class Event extends Collection {
	public $type;
	public $name;
	public $data;
	public $processed;
	public $priority;
	public $timestamp;
	/*
		public $surname;
		public $email;
		public $cnpj;
		public $courses;
	*/
	public function getSource() {
		return "events";
	}

	public function beforeCreate() {
		$this->processed = false;
		$this->timestamp = time();
		//$this->_environment = $this->getDi()->get('environment')->name;
	}

}