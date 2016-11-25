<?php
namespace Sysclass\Models\Messages;

use Phalcon\Mvc\Model;

class GroupReceiver extends Model
{
    public function initialize()
    {
        $this->setSource("mod_messages_to_groups");

		$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'Group', 'reusable' => true));

		$this->belongsTo("message_id", "Sysclass\\Models\\Messages\\Message", "id",  array('alias' => 'Message', 'reusable' => true));
		
    }

}

