<?php 
namespace Sysclass\Services\Queue;

use Phalcon\Mvc\User\Component,
    Phalcon\Events\Event,
    Phalcon\Mvc\Dispatcher;

class Adapter extends Component
{
	public function __construct() {
	    $context = new \ZMQContext();
	    $this->socket = $context->getSocket(\ZMQ::SOCKET_PUSH);
	    $this->socket->connect("tcp://localhost:5555");
	}
	public function send(Message $message) {
    	$this->socket->send($message, \ZMQ::MODE_NOBLOCK);
	}
	/*
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
		//var_dump($event);
		$eventData = array(
			'event'  => $event->getType(),
			'source' => $event->getSource(),
			'class' => get_class($event->getSource()),
			'data' 	 => $event->getData()
		);
		$message = new Message("event", $eventData);
		$this->socket->send($message, \ZMQ::MODE_NOBLOCK);
	}
	*/

    
}