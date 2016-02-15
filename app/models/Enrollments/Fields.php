<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Message as Message;

class Fields extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_fields");

        $this->belongsTo("enroll_id", "Sysclass\\Models\\Enrollments\\Enroll", "id",  array('alias' => 'Enroll'));

        $this->belongsTo("field_id", "Sysclass\\Models\\Forms\\Fields", "id",  array('alias' => 'Field'));

        $this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\FieldsOptions",
            "enroll_field_id",
            array('alias' => 'Options')
        );
    }

    public function toArray() {
    	return $this->toFullArray(array('Field', 'Options'), parent::toArray());
    }

}
