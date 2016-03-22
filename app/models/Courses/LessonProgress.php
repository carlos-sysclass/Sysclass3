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
        $phql = "SELECT AVG(factor) as factor
        	FROM Sysclass\\Models\\Courses\\Contents\\Progress as ContentProgress
        	WHERE content_id IN (
        		SELECT id FROM Sysclass\\Models\\Courses\\Contents\\Content WHERE lesson_id = ?0
        	)
        ";
        $data = $manager->executeQuery($phql, array($this->lesson_id));

        $this->factor = $data[0]->factor;

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

        return array_merge($classProgress->updateProgress(), $log);
    }
}
