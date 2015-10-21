<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class CourseClasses extends Model
{
    public function initialize()
    {
        $this->setSource("mod_roadmap_courses_to_classes");
        
        $this->belongsTo("course_id", "Sysclass\\Models\\Courses\\Course", "id",  array('alias' => 'Course'));
        $this->belongsTo("class_id", "Sysclass\\Models\\Courses\\Class", "id",  array('alias' => 'Class'));

    }
}
