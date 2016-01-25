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
    }
    
}
