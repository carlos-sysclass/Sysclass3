<?php
namespace Sysclass\Tasks;

use Sysclass\Services\Queue\AsyncCall;

class MainTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
    	// SHOW HELP
    }

    public function sendAction() {
        $task = new AsyncCall("translate", "translateTokens", array(
            "en",
            "pt"
        ));
        $this->queue->send($task);
    }
}