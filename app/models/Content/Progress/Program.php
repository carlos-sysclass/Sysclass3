<?php
namespace Sysclass\Models\Content\Progress;

use Phalcon\Mvc\Model;

class Program extends Model
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

        $phql = "SELECT AVG(IFNULL(factor, 0)) as factor
            FROM Sysclass\\Models\\Content\\Course as c
            LEFT OUTER JOIN Sysclass\\Models\\Content\\Progress\\Course as cp
                ON (c.id = cp.class_id AND (cp.user_id = ?1 OR cp.user_id IS NULL))
            WHERE course_id =?0
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
