<?php
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class Lesson extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons");

		$this->belongsTo(
			"class_id", 
			"Sysclass\\Models\\Courses\\Classe", 
			"id",
			array('alias' => 'Classe')
		);

		$this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\LessonProgress",
            "lesson_id",
            array('alias' => 'Progress')
        );

    }

}
