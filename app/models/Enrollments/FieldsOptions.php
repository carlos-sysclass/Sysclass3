<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Message as Message;

class FieldsOptions extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_fields_options");
        /*
        $this->belongsTo("enroll_field_id", "Sysclass\\Models\\Enrollments\\Fields", "id",  array('alias' => 'Field'));
        */

    }
    /*
    public function toArray() {
    	return $this->toFullArray(array('Field'), parent::toArray());
    }
    */

}
