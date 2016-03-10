<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class CourseProgress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses_progress");
    }

    public function beforeSave() {
    	if (floatval($this->factor) >= 1) {
    		$this->completed = 1;
    	}
    }

    public function complete() {
    	$this->completed = 1;
    	return $this->save();
    }
}
