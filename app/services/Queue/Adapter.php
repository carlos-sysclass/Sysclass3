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

	    $endpoint = $environment->queue->endpoint;

	    $this->socket->connect($endpoint);
	}
	public function send(Message $message) {
    	$this->socket->send($message, \ZMQ::MODE_NOBLOCK);
	}
}