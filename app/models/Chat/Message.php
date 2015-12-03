<?php
namespace Sysclass\Models\Chat;

use Phalcon\Mvc\Model;

class Message extends Model
{
    public function initialize()
    {
        $this->setSource("mod_chat_messages");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => false));

        $this->belongsTo("chat_id", "Sysclass\\Models\\Chat\\Chat", "id",  array('alias' => 'Chat', 'reusable' => false));
    }

}
