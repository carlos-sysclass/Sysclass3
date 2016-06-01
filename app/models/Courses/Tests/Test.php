<?php
namespace Sysclass\Models\Courses\Tests;

use Plico\Mvc\Model;

class Test extends Model
{
    public function initialize()
    {
        $this->setSource("mod_tests");

		$this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Lesson",
            "id",
            array('alias' => 'Lesson')
        );
    }

}
