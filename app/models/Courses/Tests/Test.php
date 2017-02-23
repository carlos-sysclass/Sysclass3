<?php
/**
 * @deprecated 3.4 Use \Sysclass\Models\Content\UnitTest instead.
 */

namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model;

class Test extends Model
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
            "unit_id", "question_id",
            "Sysclass\\Models\\Courses\\Questions\Question",
            "id",
            array('alias' => 'Questions')
        );
    }

}
