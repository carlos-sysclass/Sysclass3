<?php
namespace Sysclass\Models\Acl;

use Phalcon\Mvc\Model,
    Sysclass\Models\Acl\RolesGroups,
    Sysclass\Models\Acl\RolesUsers;

class Role extends Model
{
    public function initialize()
    {
         $this->setSource("acl_roles");

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesResources",
            "role_id", "resource_id",
            "Sysclass\Models\Acl\Resource",
            "id",
            array('alias' => 'Resources', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesUsers",
            "role_id", "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'Users', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesGroups",
            "role_id", "group_id",
            "Sysclass\Models\Users\Group",
            "id",
            array('alias' => 'Groups', 'reusable' => true)
        );
    }

    public function getAllUsers() {
        $users = $this->getUsers()->toArray();

        $groups = $this->getGroups($this->id);

        foreach($groups as $group) {
            $users = array_merge($users, $group->getUsers()->toArray());
        }

        $di = \Phalcon\DI::getDefault();

        $users = $di->get('arrayHelper')->multiUnique($users, 'id');

        return $users;
    }
}
