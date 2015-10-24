<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class Classe extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes");

        $this->hasMany("id", "Sysclass\\Models\\Courses\\Lesson", "class_id",  array('alias' => 'Lessons'));
    }

}
