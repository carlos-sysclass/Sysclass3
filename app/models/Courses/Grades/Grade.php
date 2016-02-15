<?php
namespace Sysclass\Models\Courses\Grades;

use Plico\Mvc\Model;

class Grade extends Model
{
    public function initialize()
    {
        $this->setSource("mod_grades");

        $this->hasMany("id", "Sysclass\\Models\\Courses\\Grades\\Range", "grade_id",  array('alias' => 'Ranges'));
    }

    public function beforeSave() {
    	//var_dump($this->grades);
    	//$this->getRanges()->delete();


    	/*
    	foreach($this->getRanges() as $range) {
    		$range->delete();
    	}
    	*/
    	/*
    	if () {

    	}
    	var_dump("dd", $this->toArray());
    	exit;
    	*/
    }
}

