<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("users");
    }


    public function getType() {
        return $this->user_type;
    }
}
