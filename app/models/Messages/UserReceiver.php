<?php
namespace Sysclass\Models\Messages;

use Phalcon\Mvc\Model;

class UserReceiver extends Model
{
    public function initialize()
    {
        $this->setSource("mod_messages_to_users");

		$this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'Group', 'reusable' => true));

		$this->belongsTo("message_id", "Sysclass\\Models\\Messages\\Message", "id",  array('alias' => 'Message', 'reusable' => true));
		
    }

}

