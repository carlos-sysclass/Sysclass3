<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\RolesUsers;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("users");

        $this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'group'));

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserAvatar", "user_id",  array('alias' => 'avatar'));

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Users\\UserAvatar",
            "user_id", "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'Avatars', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesUsers",
            "user_id", "role_id",
            "Sysclass\Models\Acl\Role",
            "id",
            array('alias' => 'UserRoles', 'reusable' => true)
        );

    }

    public function getType() {
        return $this->user_type;
    }
}
