<?php
namespace Sysclass\Models\Calendar;

use Phalcon\Mvc\Model;

class Sources extends Model
{
    public function initialize()
    {
        $this->setSource("mod_calendar_sources");

    }

}
