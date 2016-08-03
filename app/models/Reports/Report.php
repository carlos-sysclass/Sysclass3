<?php
namespace Sysclass\Models\Reports;

use Phalcon\Mvc\Model;

class Report extends Model
{
    public function initialize()
    {
        $this->setSource("mod_reports");
    }

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

}
