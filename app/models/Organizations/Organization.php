<?php
namespace Sysclass\Models\Organizations;

use Plico\Mvc\Model;

class Organization extends Model
{
    public function initialize()
    {
        $this->setSource("mod_institution");
    }

}
