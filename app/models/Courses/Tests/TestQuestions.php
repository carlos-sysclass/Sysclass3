<?php
namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model;

class TestQuestions extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests_to_questions");

        $this->belongsTo("unit_id", "Sysclass\\Models\\Courses\\Tests\\Unit", "id",  array('alias' => 'Unit'));

        $this->belongsTo("unit_id", "Sysclass\\Models\\Courses\\Tests\\Test", "id",  array('alias' => 'Test'));
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

    public function correct($answer) {
        $question = $this->getQuestion();

        switch ($question->type_id) {
            case "simple_choice" : {
                return $this->correctSingleChoice($answer);
                break;
            }
            case "true_or_false" : {
                return $this->correctTrueOrFalse($answer);
                break;
            }
            case "multiple_choice" : {
                return $this->correctMultipleChoice($answer);
                break;
            }
        }
        return 0;

    }

    protected function correctSingleChoice($answer) {

        $question = $this->getQuestion()->toArray();
        $options = $question['options'];

        foreach($options as $opt) {
            if (is_numeric($answer)) {
                $answer = intval($answer);
            } else {
                return 0;
            }

            if ($opt['answer'] === TRUE && $answer === intval($opt['index'])) {
                return ($this->points * $this->weight);
            }
        }
        return 0;
    }

    protected function correctTrueOrFalse($answer) {

        $question = $this->getQuestion()->toArray();
        $options = $question['options'];


        if (!is_numeric($question['answer'])) {
            $question['answer'] = 1;
        }
        if (is_numeric($answer)) {
            $answer = intval($answer);
        } else {
            return 0;
        }

        if ($question['answer'] == $answer) {
            return ($this->points * $this->weight);
        } else {
            return 0;
        }
    }

    protected function correctMultipleChoice($answer) {


        $question = $this->getQuestion()->toArray();
        $options = $question['options'];


        $countCorrect = 0;
        $countAnswer = 0;
        $countError = 0;

//        $options = $questionData['question']['options'];
        foreach($options as $opt) {
            if ($opt['answer'] === TRUE) {
                $countCorrect++;
                if (in_array($opt['index'], $answer)) {
                    $countAnswer++;
                } else {
                    $countError++;
                    $has_errors = true;
                }
            }
        }

        if ($countError == 0) {
            $correctFactor = $countAnswer / $countCorrect;
        } else {
            $correctFactor = 0;
        }

        return ($this->points * $this->weight) * $correctFactor;
    }

}
