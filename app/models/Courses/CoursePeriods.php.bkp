<?php
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class CoursePeriods extends Model
{
    public function initialize()
    {
        $this->setSource("mod_roadmap_course_periods");

        $this->belongsTo("course_id", "Sysclass\\Models\\Courses\\Course", "id",  array('alias' => 'Course'));

    }
}
