<?php
namespace Sysclass\Models\Calendar;

use Plico\Mvc\Model;

class Event extends Model
{
    public function initialize()
    {
        $this->setSource("mod_calendar_events");

        $this->belongsTo("source_id", "Sysclass\\Models\\Calendar\\Sources", "id",  array('alias' => 'CalendarSource', 'reusable' => true));

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
    }

    public function beforeValidation() {
    	if (is_null($this->user_id)) {
    		$user = $this->getDI()->get('user');
    		if ($user) {
    			$this->user_id = $user->id;
    		}
    	}
    }

}
