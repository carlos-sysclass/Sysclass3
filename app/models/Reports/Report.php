<?php
namespace Sysclass\Models\Reports;

use Phalcon\Mvc\Model;

class Report extends Model
{
    public function initialize()
    {
        $this->setSource("mod_reports");
    }

}
