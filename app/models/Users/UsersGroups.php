<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model;

class UsersGroups extends Model
{
    public function initialize()
    {
        $this->setSource("users_to_groups");

		$this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
		$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'Group', 'reusable' => true));
    }

}

