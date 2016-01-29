<?php
namespace Sysclass\Models\Courses\Questions;

use Plico\Mvc\Model;

class Question extends Model
{
    public function initialize()
    {
        $this->setSource("mod_questions");

        $this->belongsTo("area_id", "Sysclass\\Models\\Courses\\Departament", "id",  array('alias' => 'Departament'));

        $this->belongsTo("type_id", "Sysclass\\Models\\Courses\\Questions\\Type", "id",  array('alias' => 'Type'));
        $this->belongsTo("difficulty_id", "Sysclass\\Models\\Courses\\Questions\\Difficulty", "id",  array('alias' => 'Difficulty'));

    }
}
