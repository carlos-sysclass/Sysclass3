<?php
namespace Sysclass\Models\Calendar;

use Phalcon\Mvc\Model;

class EventTypes extends Model
{
    public function initialize()
    {
        $this->setSource("mod_event_types");

    }

}
