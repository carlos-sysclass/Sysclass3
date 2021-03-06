<?php
namespace Sysclass\Models\Messages;

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class Group extends Model
{
    public function initialize()
    {
        $this->setSource("mod_messages_groups");

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
