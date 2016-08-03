<?php
namespace Sysclass\Models\Forms;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Message as Message;

class FieldTypes extends Model
{
    public function initialize()
    {
        $this->setSource("mod_fields_types");

        $this->hasMany("id", "Sysclass\\Models\\Forms\\Fields", "type_id",  array('alias' => 'Fields'));
    }

}
