<?php
namespace Sysclass\Models\Chat;

use Plico\Mvc\Model;

class Chat extends Model
{
    public function initialize()
    {
        $this->setSource("mod_chat");

        $this->belongsTo("requester_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'Requester', 'reusable' => true));
    }

}
