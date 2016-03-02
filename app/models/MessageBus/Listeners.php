<?php
namespace Sysclass\Models\MessageBus;

use Phalcon\Mvc\Model;

class Listeners extends Model
{
    public function initialize()
    {
        $this->setSource("mod_listeners");
    }
}
