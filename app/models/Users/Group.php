<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class Group extends Model
{
    public function initialize()
    {
        $this->setSource("groups");
    }

}
