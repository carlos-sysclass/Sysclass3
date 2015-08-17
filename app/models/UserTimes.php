<?php
namespace Sysclass\Models;

use Phalcon\Mvc\Model,
    Sysclass\Models\Users;

class UserTimes extends Model
{
    public function initialize()
    {
        $this->belongsTo("user_id", "Sysclass\\Models\\Users", "id",  array('alias' => 'User'));
    }
}
