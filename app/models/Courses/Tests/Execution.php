<?php
namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User;

class Execution extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests_execution");

		$this->belongsTo(
            "test_id",
            "Sysclass\\Models\\Content\\UnitTest",
            "id",
            array('alias' => 'Test')
        );

        $this->belongsTo(
            "user_id",
            "Sysclass\\Models\\Users\\User",
            "id",
            array('alias' => 'User')
        );

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\ExecutionQuestions",
            "lesson_id", 
            array('alias' => 'ExecutionQuestions')
        );
    }

    public function canExecuteAgain(User $user) {

        $test = $this->getTest();
        $test_repetition = @isset($test->test_repetition) ? $test->test_repetition : false;

        if (is_numeric($test_repetition)) {
            $test_repetition = intval($test_repetition);

            $total_executions = self::count([
                'conditions' => "test_id = ?0 AND user_id = ?1",
                'bind' => [$this->test_id, $user->id]
            ]);

            if ($test_repetition <= 0 || $test_repetition > $total_executions) {
                return true;
            } else {
                return false;
            }

        }
        // ASSUMES NO LIMIT
        return true;
    }
}
