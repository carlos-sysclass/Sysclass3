<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

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
    public function isCompleted(){
        return true;
    }
}
