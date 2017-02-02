<?php
namespace Sysclass\Models\Reports;

use Plico\Mvc\Model;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("mod_report_users");
    }

}
