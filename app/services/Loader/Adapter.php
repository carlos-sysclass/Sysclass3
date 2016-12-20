<?php 
namespace Sysclass\Services\Loader;

use Phalcon\Mvc\User\Component,
	Phalcon\Mvc\Dispatcher as MvcDispatcher,
    Phalcon\Cli\Dispatcher as CliDispatcher;

class Adapter extends Component
{
    public function module($module) {
		//$module = strtolower($module);
        $class = sprintf('Sysclass\Modules\%1$s\%1$sModule', ucfirst($module));

        if ($class == $this->dispatcher->getHandlerClass()) {
			if (APP_TYPE === "WEB") {
				return $this->dispatcher-> getActiveController();	
			} else {
				return $this->dispatcher-> getActiveTask();	
			}
        }

        $object = new $class();
        $object->setDI($this->getDI());
        
        $object->init();
        
        return $object;
    }
}