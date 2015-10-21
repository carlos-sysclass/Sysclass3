<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class Lesson extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons");

		$this->belongsTo("class_id", "Sysclass\\Models\\Courses\\Classe", "id",  array('alias' => 'Classe'));
    }
}
