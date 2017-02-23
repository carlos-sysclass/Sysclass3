<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class UnitTest extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests");

		$this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Unit",
            "id",
            array('alias' => 'Unit')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\TestQuestions",
            "lesson_id", "question_id",
            "Sysclass\\Models\\Courses\\Questions\Question",
            "id",
            array('alias' => 'Questions')
        );

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Execution",
            "test_id",
            array(
                'alias' => 'Executions',
                'params' => array(
                    'order' => '[Sysclass\Models\Courses\Tests\Execution].try_index ASC'
                )

            )
        );
    }

}
