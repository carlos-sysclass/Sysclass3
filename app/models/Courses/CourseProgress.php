<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class CourseProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses_progress");
    }

    public function beforeSave() {
    	if (floatval($this->factor) >= 1) {
    		$this->completed = 1;
    	}
    }

    public function updateProgress($return_messages = true) {
        // GET RELATED UNIT, AND CALL AN UPDATE
        // CALCULATE BASED ON CONTENTS
        $manager = $this->getDI()->get("modelsManager");
        /*
        $phql = "SELECT AVG(factor) as factor
            FROM Sysclass\\Models\\Courses\\ClasseProgress
            WHERE class_id IN (
                SELECT CourseClasses.class_id FROM Sysclass\Models\Courses\CourseClasses as CourseClasses WHERE course_id = ?0
            )
        ";
        */

        $phql = "SELECT AVG(IFNULL(factor, 0)) as factor
            FROM Sysclass\\Models\\Courses\\Classe as c
            LEFT OUTER JOIN Sysclass\\Models\\Courses\\ClasseProgress as cp
                ON (c.id = cp.class_id AND (cp.user_id = ?1 OR cp.user_id IS NULL))
            WHERE class_id IN (
                SELECT CourseClasses.class_id FROM Sysclass\Models\Courses\CourseClasses as CourseClasses WHERE CourseClasses.course_id = ?0
            )
        ";


        $log = array();

        $data = $manager->executeQuery($phql, array($this->course_id, $this->user_id));

        $this->factor = $data[0]->factor;

        if ($this->factor == 1) {
            $this->completed = 1;
        }
        
        if ($this->save()) {
            $log[] = array(
                'type' => 'success',
                'message' => sprintf('Progress for Program #%s for user #%s updated.', $this->course_id, $this->user_id),
                'status' => true,
                'entity' => 'program',
                'data' => $this->toArray()
            );


        } else {
            $log[] = array(
                'type' => 'error',
                'message' => sprintf('Error when trying to update progress for Program #%s for user #%s updated.', $this->course_id, $this->user_id),
                'status' => false
            );

        }

        return $log;
    }

    public function complete() {
    	$this->completed = 1;
    	return $this->save();
    }
}
