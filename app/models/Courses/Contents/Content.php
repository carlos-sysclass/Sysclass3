<?php
namespace Sysclass\Models\Courses\Contents;

use Phalcon\Mvc\Model;

class Content extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content");

        /*
		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Courses\CourseClasses",
            "course_id", "class_id",
            "Sysclass\Models\Courses\Classe",
            "id",
            array('alias' => 'CourseClasses')
        );
        */
    }
}

