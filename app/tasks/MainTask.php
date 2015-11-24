<?php
namespace Sysclass\Tasks;

use Sysclass\Services\Queue\Message;

class MainTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
    	// SHOW HELP
    }

    public function sendAction() {
    	$this->queue->send(new Message("events", array("event" => "teste")));
    }
}