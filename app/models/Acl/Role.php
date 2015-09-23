<?php
namespace Sysclass\Models\Acl;

use Phalcon\Mvc\Model;

class Role extends Model
{
    public function initialize()
    {
         $this->setSource("acl_roles");
    }
}
