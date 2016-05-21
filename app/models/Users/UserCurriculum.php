<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Sysclass\Models\Users\User;

class UserCurriculum extends Model
{
    public function initialize()
    {
    	$this->setSource("user_curriculum");

        $this->belongsTo("id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
    }
}
