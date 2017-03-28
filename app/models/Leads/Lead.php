<?php
namespace Sysclass\Models\Leads;

use Plico\Mvc\Model;

class Lead extends Model
{
    public function initialize()
    {
        $this->setSource("mod_leads");
    }

    /*
    public function beforeValidationOnCreate() {
        $this->renewAccess();
        $this->addToDefaultGroup();
    }
    */
}
