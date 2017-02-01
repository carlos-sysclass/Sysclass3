<?php
namespace Sysclass\Models\Reports;

use Sysclass\Models\Users\User as BaseUser;

class User extends BaseUser
{
    public function initialize()
    {
        parent::initialize();
        $this->setSource("users");
    }

}
