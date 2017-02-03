<?php
namespace Sysclass\Models\Reports;

use Plico\Mvc\Model;

class Report extends Model
{
    // MUST DECLARE "ARRAY" FIELDS
    public $report_fields;
    public $filters;
    public $options;

    public function initialize()
    {
        $this->setSource("mod_reports");

        $this->belongsTo("datasource_id", "Sysclass\\Models\\Reports\\ReportDatasource", "name",  array('alias' => 'Datasource'));
    }
    /*
    public function mergeOptions($baseOptions) {
        $objectOptions = json_decode($this->options, true);
        if (!is_array($objectOptions)) {
            return $baseOptions;
        }

        $fullOptions = array();

        $fullOptions['model'] = array_key_exists('model', $objectOptions) ? $objectOptions['model'] : $baseOptions['model'];


        // FIELDS
        if (array_key_exists('fields', $baseOptions) && array_key_exists('fields', $objectOptions)) {
            $fullOptions['fields'] = array_intersect($objectOptions['fields'], $baseOptions['fields']);

        } else {
            $fullOptions['fields'] = array_key_exists('fields', $objectOptions) ? $objectOptions['fields'] : (array_key_exists('fields', $baseOptions) ? $baseOptions['fields'] : null);
        }
        
        $fullOptions['datafields'] = array_key_exists('datafields', $objectOptions) ? $objectOptions['datafields'] : $baseOptions['datafields'];

       return $fullOptions;

    }
    */

    public function afterFetch() {
        $this->report_fields = json_decode($this->report_fields, true);
        $this->filters = json_decode($this->filters, true);
    }

    public function afterSave() {
        $this->report_fields = json_decode($this->report_fields, true);
        $this->filters = json_decode($this->filters, true);
    }


    public function beforeSave() {
        if (!is_null($this->report_fields)) {
            $this->report_fields = json_encode($this->report_fields);
        }
        if (!is_null($this->filters)) {
            $this->filters = json_encode($this->filters);
        }
    }
}
