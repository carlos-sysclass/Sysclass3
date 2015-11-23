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
    /*
    public static function findFirst($parameters=null)
    {
    	if (is_null($parameters)) {
    		$parameters['conditions'] = "type"
    	}
        return parent::findFirst($parameters);
    }

    public static function find($parameters=null)
    {
        // ...
        var_dump($parameters);
        exit;
        return parent::find($parameters);
    }
    */
}
