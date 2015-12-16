<?php 
namespace Sysclass\Services\Queue;

class AsyncCall extends Message 
{
	public function __construct($task, $method, $args) {

		$data = array(
			'service' => $task,
			'method' => $method,
			'args' => $args
		);

		parent::__construct("task", $data);
	}
	/*
    public function __toString() {
        return json_encode($this->data);
    }
    */
}