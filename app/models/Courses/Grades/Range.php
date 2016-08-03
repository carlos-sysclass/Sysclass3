<?php
namespace Sysclass\Models\Courses\Grades;

use Phalcon\Mvc\Model;

class Range extends Model
{
    public function initialize()
    {
        $this->setSource("mod_grades_ranges");
        
        $this->belongsTo("grade_id", "Sysclass\\Models\\Courses\\Grades\\Grade", "id",  array('alias' => 'Grade'));
    }
}

