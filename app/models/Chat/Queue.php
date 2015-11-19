<?php
namespace Sysclass\Models\Chat;

use Phalcon\Mvc\Model;

class Queue extends Model
{
    public function initialize()
    {
        $this->setSource("mod_chat_queue");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => false));
    }

}
