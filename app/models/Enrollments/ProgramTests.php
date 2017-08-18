<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model;

class ProgramTests extends Model {
	public function initialize() {
		$this->setSource("mod_enroll_courses_tests");

		$this->belongsTo("program_id", "Sysclass\\Models\\Content\\Program", "id", array('alias' => 'Program'));
		$this->belongsTo("test_id", "Sysclass\\Models\\Courses\\Tests\Lesson", "id", array('alias' => 'Test'));
	}
}
