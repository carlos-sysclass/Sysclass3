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

    public function calculateDuration(\DateTime $start) {
        switch($this->duration_type) {
            case "week" : {
                $duration = $this->duration_units * 7;
                $interval = "P{$duration}D";
                break;
            }
            case "year" : {
                $interval = "P{$this->duration_units}Y";
                break;
            }
            case "month" : {
                $interval = "P{$this->duration_units}M";
                break;
            }
        }

        $end = clone $start;

        return $end->add(new \DateInterval($interval));
    }
}
