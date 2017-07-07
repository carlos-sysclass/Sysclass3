<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Sysclass\Models\Users\User;

class UserAttrs extends Model
{
    public function initialize()
    {
        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => false));
    }
}
