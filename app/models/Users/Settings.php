<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class Settings extends Model
{
    public function initialize()
    {
        $this->setSource("user_settings");
    }

}
