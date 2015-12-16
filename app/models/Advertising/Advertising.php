<?php
namespace Sysclass\Models\Advertising;

use Phalcon\Mvc\Model;

class Advertising extends Model
{
    public function initialize()
    {
        $this->setSource("mod_advertising");

    }

}

