<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Sysclass\Models\Users\User;

class Role extends Model
{
    public function initialize()
    {
         $this->setSource("roles");
    }
}
