<?php
namespace Sysclass\Models\Acl;

use Phalcon\Mvc\Model;

class Resource extends Model
{
    public function initialize()
    {
         $this->setSource("acl_resources");
    }
}
