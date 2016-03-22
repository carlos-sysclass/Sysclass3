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
        $phql = "SELECT AVG(factor) as factor
            FROM Sysclass\\Models\\Courses\\ClasseProgress
            WHERE class_id IN (
                SELECT CourseClasses.class_id FROM Sysclass\Models\Courses\CourseClasses as CourseClasses WHERE course_id = ?0
            )
        ";

        $log = array();

        $data = $manager->executeQuery($phql, array($this->course_id));
        $this->factor = $data[0]->factor;
        
        if ($this->save()) {
            $log[] = array(
                'type' => 'success',
                'message' => sprintf('Progress for Course #%s for user #%s updated.', $this->course_id, $this->user_id),
                'status' => true
            );
        } else {
            $log[] = array(
                'type' => 'error',
                'message' => sprintf('Error when trying to update progress for Course #%s for user #%s updated.', $this->course_id, $this->user_id),
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
