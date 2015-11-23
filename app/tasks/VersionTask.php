<?php
namespace Sysclass\Tasks;

use Kint;

class VersionTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
    	//$this->debug->dump($this->sysconfig->deploy->toArray());
    	Kint::dump($this->sysconfig->deploy->toArray());

    }
}