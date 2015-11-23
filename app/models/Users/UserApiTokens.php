<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model;

class UserApiTokens extends Model
{
    public function initialize()
    {
        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => false));
    }
}
