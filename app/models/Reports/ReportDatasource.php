<?php
namespace Sysclass\Models\Reports;

use Phalcon\Mvc\Model;

class ReportDatasource extends Model
{
    public function initialize()
    {
        $this->setSource("mod_reports_datasources");
    }
}
