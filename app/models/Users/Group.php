<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class Group extends Model
{
    public function initialize()
    {
        $this->setSource("groups");

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesGroups",
            "group_id", "role_id",
            "Sysclass\Models\Acl\Role",
            "id",
            array('alias' => 'Roles', 'reusable' => true)
        );

    }
}
