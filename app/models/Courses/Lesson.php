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

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Contents\\Content",
            "lesson_id",
            array('alias' => 'Contents')
        );

    }

    public function toFullLessonArray() {
        $result = $this->toArray();

        $contents = $this->getContents();
        $result['contents'] = array();

        foreach($contents as $content) {
            $item = $content->toFullContentArray();
            
            $result['contents'][] = $item;
        }
        
        $progress = $this->getProgress();
        $result['progress'] = $progress->toArray();

        return $result;
    }

}
