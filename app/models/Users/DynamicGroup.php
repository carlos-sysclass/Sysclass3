<?php
namespace Sysclass\Models\Users;

use Sysclass\Models\Users\Group,
    Phalcon\Mvc\Model\Relation;

class DynamicGroup extends Group
{
    protected static $_translateFields = array(
        'name'
    );
    
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeValidation() {
        $this->dynamic = 1;
    }
}
