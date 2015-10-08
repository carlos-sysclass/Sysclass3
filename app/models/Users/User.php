<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\RolesUsers;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("users");

        //$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'group'));
        //
        $this->belongsTo("language_id", "Sysclass\\Models\\I18n\\Language", "id",  array('alias' => 'language'));

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

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Users\UsersGroups",
            "user_id", "group_id",
            "Sysclass\Models\Users\Group",
            "id",
            array('alias' => 'UserGroups')
        );

    }

    public function getType() {
        return $this->user_type;
    }

    public function getRoles() {
        $userRoles = $this->getUserRoles();

        $roles = $userRoles->toArray();

        $groups = $this->getUserGroups();

        foreach($groups as $group) {
            $groupRoles = $group->getRoles();
            $roles = array_merge($roles, $groupRoles->toArray());
            $roles = array_map("unserialize", array_unique(array_map("serialize", $roles)));
        }
        return $roles;
    }

    public function getDashboards() {
        $roles = $this->getRoles();
        $dashboards = array_map("strtolower", array_column($roles, "name"));

        return $dashboards;
    }
}
