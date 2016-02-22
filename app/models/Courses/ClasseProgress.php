<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class ClasseProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes_progress");
    }
}
