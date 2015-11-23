<?php
namespace Plico\Assets;

use Phalcon\DI,
    Phalcon\Assets\Manager as AssetsManager;

class Manager extends AssetsManager
{
    public function output($collection, $callback, $type=null){
        return parent::output($collection, $callback, $type);
    }

}
