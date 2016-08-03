<?php
namespace Sysclass\Models\Reports;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User as BaseUser,
    Sysclass\Models\Acl\Resource,
    Sysclass\Models\Acl\RolesUsers;

class User extends BaseUser
{
    public function initialize()
    {
        parent::initialize();
        $this->setSource("users");
    }

}
