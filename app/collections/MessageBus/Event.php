<?php
namespace Sysclass\Collections\MessageBus;

use Phalcon\Mvc\Collection;

class Event extends Collection
{
	public $type;
	public $name;
	public $data;
	public $processed;
	public $priority;
	/*
	public $surname;
	public $email;
	public $cnpj;
	public $courses;
	*/
    public function getSource()
    {
        return "events";
    }

	public function beforeCreate() {
		$this->processed = false;
		//$this->_environment = $this->getDi()->get('environment')->name;
	}

	public function assign($data) {
		foreach($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/*
    public function toArray() {
    	$array = parent::toArray();
    	if (is_object($this->_id)) {
    		$array['_id'] = $this->_id->{'$id'};
    	}

    	return $array;
    }
    */
}