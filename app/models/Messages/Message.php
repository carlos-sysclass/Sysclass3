<?php
namespace Sysclass\Models\Messages;

use Plico\Mvc\Model;

class Message extends Model
{
    public function initialize()
    {
        $this->setSource("mod_messages");


        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Messages\GroupReceiver",
            "message_id", "group_id",
            "Sysclass\Models\Users\Group",
            "id",
            array('alias' => 'Groups')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Messages\UserReceiver",
            "message_id", "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'Users')
        );

    }

    public function beforeValidationOnCreate() {
        $this->timestamp = time();

        if (is_null($this->user_id)) {
            $depinj = \Phalcon\DI::getDefault();
            $user = $depinj->get("user");
            $this->user_id = $user->id;
        }
    }
}
