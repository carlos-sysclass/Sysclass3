<?php
namespace Sysclass\Models\Notifications;

use Phalcon\Mvc\Model;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("mod_notification_to_users");
    }

    public function beforeCreate() {
    	$this->timestamp = time();
    }
}
