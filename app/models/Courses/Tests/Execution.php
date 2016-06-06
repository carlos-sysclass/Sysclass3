<?php
namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model;

class Execution extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests_execution");

		$this->belongsTo(
            "test_id",
            "Sysclass\\Models\\Courses\\Tests\\Test",
            "id",
            array('alias' => 'Test')
        );
    }
}
