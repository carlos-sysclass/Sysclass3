<?php
namespace Sysclass\Models\Calendar;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class Event extends Model
{
    public function initialize()
    {
        $this->setSource("mod_event");

        $this->belongsTo("type_id", "Sysclass\\Models\\Calendar\\EventTypes", "id",  array('alias' => 'Type', 'reusable' => true));

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));

    }

}
