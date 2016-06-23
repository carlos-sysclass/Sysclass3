<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model,
    Sysclass\Models\Courses\ClasseProgress,
    Sysclass\Models\Courses\Contents\Progress as ContentProgress;

class LessonProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_progress");

        $this->belongsTo("lesson_id", "Sysclass\\Models\\Courses\\Lesson", "id",  array('alias' => 'Unit'));
    }

    public function updateProgress() {
        // GET RELATED UNIT, AND CALL AN UPDATE
        // CALCULATE BASED ON CONTENTS
        $manager = $this->getDI()->get("modelsManager");
        //$phql = "SELECT * /* AVG(factor) as factor*/
        $phql = "SELECT AVG(IFNULL(factor, 0)) as factor 
            FROM Sysclass\\Models\\Courses\\Contents\\Content as c
        	LEFT JOIN Sysclass\\Models\\Courses\\Contents\\Progress as cp
                ON (c.id = cp.content_id)
            WHERE c.lesson_id = ?0 AND (user_id = ?1 OR user_id IS NULL)
        ";
        $data = $manager->executeQuery($phql, array($this->lesson_id, $this->user_id));

        if ($data->count() > 0) {
            $this->factor = $data[0]->factor;
        } else {
            $this->factor = 1;
        }

        if ($this->save()) {
	        $log[] = array(
	        	'type' => 'success',
	        	'message' => sprintf('Progress for lesson #%s for user #%s updated.', $this->lesson_id, $this->user_id),
	        	'status' => true
	        );
        } else {
	        $log[] = array(
	        	'type' => 'error',
	        	'message' => sprintf('Error when trying to update progress for lesson #%sd for user #%s updated.', $this->lesson_id, $this->user_id),
	        	'status' => false
	        );

        }

    	// CALL UPDATE ON CLASS
        $unit = $this->getUnit();
        
        $classProgress = ClasseProgress::findFirst(array(
            'conditions' => 'user_id = ?0 AND class_id = ?1',
            'bind' => array($this->user_id, $unit->class_id)
        ));

        if (!$classProgress) {
            $classProgress = new ClasseProgress();
            $classProgress->user_id = $this->user_id;
            $classProgress->class_id = $unit->class_id;
            $classProgress->save();
        }

        return array_merge($classProgress->updateProgress(), $log);
    }
}
