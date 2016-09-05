<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class Program extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses");
        
		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Content\ProgramCourses",
            "course_id", "class_id",
            "Sysclass\Models\Content\Course",
            "id",
            array(
                'alias' => 'Courses',
                'params' => array(
                    'order' => '[Sysclass\Models\Content\ProgramCourses].position'
                )
            )
        );
        
        $this->hasMany(
            "id",
            "Sysclass\Models\Content\ProgramCourses",
            "course_id",
            array(
                'alias' => 'ProgramsCourses',
                'params' => array(
                    'order' => '[Sysclass\Models\Content\ProgramCourses].position'
                )
            )
        );

        $this->belongsTo(
            "area_id",
            "Sysclass\Models\Content\Departament",
            "id",
            array(
                'alias' => 'Departament',
            )
        );

        $this->belongsTo(
            "coordinator_id",
            "Sysclass\Models\Users\User",
            "id",
            array(
                'alias' => 'Coordinator',
            )
        );

        $this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\CourseProgress",
            "course_id",
            array('alias' => 'Progress')
        );
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
            default : {
                $interval = false;
            }
        }

        $end = clone $start;
        if ($interval) {
            return $end->add(new \DateInterval($interval));
        }
        return $end;
    }

    public static function getUserContentTree(User $user = null) {
        if (is_null($user)) {
            $user = \Phalcon\DI::getDefault()->get('user');
        }
        $programs = $user->getPrograms();

        $tree = array();
        foreach($programs as $program) {
            $tree[] = $program->getFullTree();
        }

        return $tree;

    }

    public function getFullTree() {
        $result = $this->toArray();
        if ($coordinator =  $this->getCoordinator()) {
            $result['coordinator'] = $coordinator->toArray();
        } else {
            $result['coordinator'] = array();
        }
        if ($departament =  $this->getDepartament()) {
            $result['departament'] = $departament->toArray();
        } else {
            $result['departament'] = array();
        }

        $result['courses'] = array();
        $courses = $this->getCourses();
        foreach($courses as $course) {
            $result['courses'][] = $course->getFullTree();
        }

        return $result;
    }
}
