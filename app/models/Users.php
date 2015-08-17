<?php
namespace Sysclass\Models;

use Phalcon\Mvc\Model;

class Users extends Model
{
    public function getType() {
        return $this->user_type;
    }
}
