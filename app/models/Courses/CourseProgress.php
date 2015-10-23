<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class CourseProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses_progress");
    }
}
