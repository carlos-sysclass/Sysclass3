<?php
namespace Sysclass\Models\Courses\Questions;

use Phalcon\Mvc\Model;

class Type extends Model
{
    public function initialize()
    {
        $this->setSource("mod_questions_types");

        //$this->belongsTo("area_id", "Sysclass\\Models\\Content\\Department", "id",  array('alias' => 'Department'));

    }
}
