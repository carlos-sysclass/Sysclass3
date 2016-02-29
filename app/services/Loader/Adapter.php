<?php 
namespace Sysclass\Services\Loader;

use Phalcon\Mvc\User\Component;

class Adapter extends Component
{
    public function module($module) {
		$module = strtolower($module);
        $class = sprintf('\Sysclass\Modules\%1$s\%1$sModule', ucfirst($module));

        $object = new $class();
        $object->setDI($this->getDI());

        $object->init();
        
        return $object;
    }
}