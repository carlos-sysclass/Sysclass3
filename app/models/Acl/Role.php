<?php
namespace Sysclass\Models\Acl;

use Phalcon\Mvc\Model;

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
}
