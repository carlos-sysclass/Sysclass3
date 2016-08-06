<?php
namespace Sysclass\Models\Acl;

use Plico\Mvc\Model;

class Resource extends Model
{
	public function initialize()
    {
         $this->setSource("acl_resources");

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesResources",
            "resource_id", "role_id",
            "Sysclass\Models\Acl\Role",
            "id",
            array('alias' => 'Roles', 'reusable' => true)
        );
    }

}
