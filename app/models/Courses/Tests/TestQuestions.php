<?php
namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model;

class TestQuestions extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests_to_questions");

        $this->belongsTo("lesson_id", "Sysclass\\Models\\Courses\\Tests\\Lesson", "id",  array('alias' => 'Lesson'));

        $this->belongsTo("lesson_id", "Sysclass\\Models\\Courses\\Tests\\Test", "id",  array('alias' => 'Test'));
        $this->belongsTo("question_id", "Sysclass\\Models\\Courses\\Questions\\Question", "id",  array('alias' => 'Question'));
        
    }

    public function beforeValidationOnCreate() {
    	if (is_null($this->points)) {
    		$this->points = 10;
    	}
    	if (is_null($this->weight)) {
    		$this->weight = 1;
    	}

    }

}
