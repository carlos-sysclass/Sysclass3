<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class LessonProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_progress");
    }
}
