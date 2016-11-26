<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class Program extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses");
        
		$this->hasMany(
            "id",
            "Sysclass\Models\Content\Course",
            "course_id",
            array(
                'alias' => 'Courses',
                'params' => array(
                    'order' => '[Sysclass\Models\Content\Course].position'
                )
            )
        );
        
        $this->hasMany(
            "id",
            "Sysclass\Models\Content\Course",
            "course_id",
            array(
                'alias' => 'ProgramsCourses',
                'params' => array(
                    'order' => '[Sysclass\Models\Content\Course].position'
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
            "language_id",
            "Sysclass\Models\I18n\Language",
            "id",
            array(
                'alias' => 'Language',
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

        $this->belongsTo(
            "image_id",
            "Sysclass\Models\Dropbox\File",
            "id",
            array(
                'alias' => 'Image',
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

    protected function resetOrder($course_id, $period_id = null) {
        $manager = \Phalcon\DI::GetDefault()->get("modelsManager");

        if (is_null($period_id)) {
            $phql = "UPDATE Sysclass\\Models\\Content\\Course
                SET position = -1 WHERE course_id = :course_id: AND period_id IS NULL";
            $bind = ['course_id' => $this->id];
        } else {
            $phql = "UPDATE Sysclass\\Models\\Content\\Course
                SET position = -1 WHERE course_id = :course_id: AND period_id = :period_id:";
            $bind = ['course_id' => $this->id, 'period_id' => $this->period_id];
            
        }

        return $manager->executeQuery(
            $phql,
            $bind
        );
    }

    public function setCourseOrder(array $order_ids, $period_id = null) {
        $status = self::resetOrder();
        $manager = \Phalcon\DI::GetDefault()->get("modelsManager");

        foreach($order_ids as $index => $course_id) {
            $bind = [
                'position' => $index + 1,
                'id' => $course_id,
                'course_id' => $this->id // program_id
            ];

            if (empty($period_id)) {
                $phql = "UPDATE Sysclass\\Models\\Content\\Course 
                    SET position = :position: 
                    WHERE id = :id: AND course_id = :course_id: AND period_id IS NULL";
            } else {
                $phql = "UPDATE Sysclass\\Models\\Content\\Course
                    SET position = :position:  WHERE id = :id: AND course_id = :course_id: AND period_id = :period_id:";
                $bind['period_id'] = $period_id;
            }

            $status->success() && $status = $manager->executeQuery(
                $phql,
                $bind
            );

        }

        return $status->success();
    }

    public static function getUserProgressTree(User $user = null) {    
        if (is_null($user)) {
            $user = \Phalcon\DI::getDefault()->get('user');
        }
        $programs = $user->getPrograms();

        $result = array();
        $result['programs'] = array();
        $result['courses'] = array();
        $result['units'] = array();
        $result['contents'] = array();


        foreach($programs as $program) {
            if ($progress = $program->getProgress(array(
                'conditions' => 'user_id = ?0',
                'bind' => array($user->id)
            ))) {
                $result['programs'][] = $progress->toArray();
            } else {
                $result['programs'][] = array(
                    'user_id' => $user->id,
                    'course_id' => $program->id,
                    'program_id' => $program->id,
                    'factor' => 0,
                    'completed' => 0
                );
            }


            foreach($program->getCourses() as $course) { 

                if ($progress = $course->getProgress(array(
                    'conditions' => 'user_id = ?0',
                    'bind' => array($user->id)
                ))) {
                    $result['courses'][] = $progress->toArray();
                } else {
                    $result['courses'][] = array(
                        'user_id' => $user->id,
                        'class_id' => $course->id,
                        'factor' => 0
                    );
                }

                foreach($course->getUnits() as $unit) {
                    if ($progress = $unit->getProgress(array(
                        'conditions' => 'user_id = ?0',
                        'bind' => array($user->id)
                    ))) {
                        $result['units'][] = $progress->toArray();
                    } else {
                        $result['units'][] = array(
                            'user_id' => $user->id,
                            'lesson_id' => $unit->id,
                            'factor' => 0
                        );
                    }
                    
                    foreach($unit->getContents() as $content) {
                        if ($progress = $content->getProgress(array(
                            'conditions' => 'user_id = ?0',
                            'bind' => array($user->id)
                        ))) {
                            $result['contents'][] = $progress->toArray();
                        } else {
                            $result['contents'][] = array(
                                'user_id' => $user->id,
                                'content_id' => $content->id,
                                'factor' => 0
                            );
                        }
                    }
                }
            }




            
        }


            




        return $result;
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
