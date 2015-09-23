<?php
namespace Sysclass\Models\Acl;

use Phalcon\Mvc\Model;

class RolesResources extends Model
{
    public function initialize()
    {
        $this->setSource("acl_roles_to_resources");

		$this->belongsTo("role_id", "Sysclass\\Models\\Acl\\Role", "id",  array('alias' => 'AclRole', 'reusable' => true));
		$this->belongsTo("resource_id", "Sysclass\\Models\\Acl\\Resource", "id",  array('alias' => 'AclResource', 'reusable' => true));

    }
}


