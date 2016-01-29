<?php
namespace Sysclass\Models\Extrato;

use Phalcon\Mvc\Model;

class Extrato extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses");

		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Extrato\CourseClasses",
            "course_id", "class_id",
            "Sysclass\Models\Extrato\Classe",
            "id",
            array('alias' => 'Extrato')
        );
    }
}
