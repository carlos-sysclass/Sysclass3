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
        $phql = "SELECT AVG(factor) as factor
        	FROM Sysclass\\Models\\Courses\\LessonProgress
        	WHERE lesson_id IN (
        		SELECT Lesson.id FROM Sysclass\\Models\\Courses\\Lesson as Lesson WHERE class_id = ?0
        	)
        ";

        $log = array();

        $data = $manager->executeQuery($phql, array($this->class_id));
        $this->factor = $data[0]->factor;
        
        if ($this->save()) {
	        $log[] = array(
	        	'type' => 'success',
	        	'message' => sprintf('Progress for Class #%s for user #%s updated.', $this->class_id, $this->user_id),
	        	'status' => true
	        );
        } else {
	        $log[] = array(
	        	'type' => 'error',
	        	'message' => sprintf('Error when trying to update progress for Class #%s for user #%s updated.', $this->class_id, $this->user_id),
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

	        $course_log = array_merge($course_log, $courseProgress->updateProgress());
        }

        return array_merge($course_log, $log);
    }
}
