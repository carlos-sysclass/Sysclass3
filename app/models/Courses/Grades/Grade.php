<?php
namespace Sysclass\Models\Courses\Grades;

use Phalcon\Mvc\Model;

class Grade extends Model
{
    public function initialize()
    {
        $this->setSource("mod_grades");
        /*
        $this->belongsTo("area_id", "Sysclass\\Models\\Courses\\Departament", "id",  array('alias' => 'Departament'));

        $this->belongsTo("type_id", "Sysclass\\Models\\Courses\\Questions\\Type", "id",  array('alias' => 'Type'));
        $this->belongsTo("difficulty_id", "Sysclass\\Models\\Courses\\Questions\\Difficulty", "id",  array('alias' => 'Difficulty'));
        */
    }
}

