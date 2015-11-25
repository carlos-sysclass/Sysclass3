<?php
namespace Sysclass\Tasks;

use Sysclass\Services\Queue\AsyncCall;

class MainTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
    	// SHOW HELP
    }

    public function callAction($params, $method) {
        $service = array_shift($params);
        $method = array_shift($params);

        echo sprintf('Calling %s:%s(%s)', $service, $method, implode(",", $params));
        
        $task = new AsyncCall($service, $method, $params);
        $this->queue->send($task);
    }
}