<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model,
	Sysclass\Models\Courses\CourseProgress as ProgramProgress;

class ClasseProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes_progress");

        $this->belongsTo("class_id", "Sysclass\\Models\\Courses\\Classe", "id",  array('alias' => 'Classe')); // USING THE NEW GLOSSARY NAME Programs, Courses and Units
    }

    public function updateProgress() {
        // GET RELATED UNIT, AND CALL AN UPDATE
        // CALCULATE BASED ON CONTENTS
        $manager = $this->getDI()->get("modelsManager");
        $phql = "SELECT AVG(IFNULL(factor, 0)) as factor
            FROM Sysclass\\Models\\Courses\\Lesson as l
        	LEFT OUTER JOIN Sysclass\\Models\\Courses\\LessonProgress as lp
                ON (l.id = lp.lesson_id AND (lp.user_id = ?1 OR lp.user_id IS NULL))
            WHERE l.class_id = ?0
        ";

        $log = array();

        $data = $manager->executeQuery($phql, array($this->class_id, $this->user_id));

        $this->factor = $data[0]->factor;

        $evManager = \Phalcon\DI::getDefault()->get("eventsManager");

        $evData = array(
            'entity_id' => $this->class_id,
            'user_id' => $this->user_id,
            'factor' => $this->factor,
            'trigger' => 'course'
        );
        $evManager->fire("course:progress", $this, $evData);
        
        if ($this->save()) {
	        $log[] = array(
	        	'type' => 'success',
	        	'message' => sprintf('Progress for Course #%s for user #%s updated.', $this->class_id, $this->user_id),
	        	'status' => true,
                'entity' => 'course',
                'data' => $this->toArray()
	        );

            if (floatval($this->factor) == 1) {
                $evManager->fire("course:completed", $this, $evData);
            }
        } else {
	        $log[] = array(
	        	'type' => 'error',
	        	'message' => sprintf('Error when trying to update progress for Course #%s for user #%s updated.', $this->class_id, $this->user_id),
	        	'status' => false
	        );
        }
        
    	// CALL UPDATE ON CLASS
        $classe = $this->getClasse();

        // GET ALL COURSES 
        $programs = $classe->getCourses();
        $course_log = array();

        foreach($programs as $program) {
	        $courseProgress = ProgramProgress::findFirst(array(
	            'conditions' => 'user_id = ?0 AND course_id = ?1',
	            'bind' => array($this->user_id, $program->id)
	        ));

            if (!$courseProgress) {
                $courseProgress = new ProgramProgress();
                $courseProgress->user_id = $this->user_id;
                $courseProgress->course_id = $program->id;
                $courseProgress->save();
            }
	        $course_log = array_merge($course_log, $courseProgress->updateProgress());
        }

        return array_merge($course_log, $log);
    }
}
