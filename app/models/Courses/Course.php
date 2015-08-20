<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class Course extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses");
    }
}
