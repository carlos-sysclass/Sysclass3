<?php
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class Course extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses");

		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Courses\CourseClasses",
            "course_id", "class_id",
            "Sysclass\Models\Courses\Classe",
            "id",
            array('alias' => 'Classes')
        );
    }
}
