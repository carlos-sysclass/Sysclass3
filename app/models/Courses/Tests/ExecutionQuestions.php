<?php
namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model;

class ExecutionQuestions extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests_execution_questions");

        $this->belongsTo("unit_id", "Sysclass\\Models\\Courses\\Tests\\Unit", "id",  array('alias' => 'Unit'));
        $this->belongsTo("unit_id", "Sysclass\\Models\\Courses\\Tests\\Test", "id",  array('alias' => 'Test'));
        $this->belongsTo("question_id", "Sysclass\\Models\\Courses\\Questions\\Question", "id",  array('alias' => 'Question'));
    }
}
