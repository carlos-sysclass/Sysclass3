<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model;

class UserPasswordRequest extends Model
{
    public function initialize()
    {
        $this->setSource("users_password_request");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => false));
    }
}
