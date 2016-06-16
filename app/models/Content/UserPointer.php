<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class UserPointer extends Model
{
    public function initialize()
    {
        $this->setSource("mod_content_pointer");
               
        $this->belongsTo(
            "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array(
                'alias' => 'Coordinator',
            )
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
