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

    }
}
