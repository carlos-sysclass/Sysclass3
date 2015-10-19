<?php
namespace Sysclass\Models\Organizations;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\RolesUsers;

class Organization extends Model
{
    public function initialize()
    {
        $this->setSource("mod_institution");
    }

}
