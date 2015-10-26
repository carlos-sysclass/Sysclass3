<?php
namespace Sysclass\Models\Courses\Contents;

use 
	Sysclass\Models\Courses\Contents\Content,
	Sysclass\Models\Courses\Contents\Progress,
	Sysclass\Models\Courses\Contents\Exercises\Answer;

class Exercise extends Content
{

    public function initialize()
    {
        parent::initialize();

        $this->hasMany(
        	"id",
        	"Sysclass\Models\Courses\Contents\Exercises\Question",
        	"content_id",
        	array('alias' => "questions")
        );

        // ADD A FIXED FILTER, ONLY FOR EXERCISES
    }

    public function setAnswers($answers, $user_id) {

    	$answer_count = 0;

    	foreach($answers as $question_id => $answer) {
    		//
    		$answerModel = new Answer();
    		$answerModel->content_id = $this->id;
    		$answerModel->question_id = $question_id;
    		if (!is_null($answer)) {
		   		$answerModel->answer = json_encode($answer);
		   		$answer_count++;
		   	} else {
		   		$answerModel->answer = null;
		   	}
    		$answerModel->user_id = $user_id;

    		$answerModel->save();
    	}

    	$total_questions = $this->getQuestions()->count();

    	$progressModel = Progress::findFirst(array(
    		"conditions" => "content_id = ?0 AND user_id = ?1",
    		"bind" => array($this->id, $user_id)
    	));

    	if (!$progressModel) {
    		$progressModel = new Progress();
	    	$progressModel->content_id = $this->id;
	    	$progressModel->user_id = $user_id;
    	}

    	$progressModel->factor = $answer_count / $total_questions;
    	$progressModel->save();

		return true;
    }
}



