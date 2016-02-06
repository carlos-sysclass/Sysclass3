<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\DI,
    Sysclass\Models\Enrollments\Fields as EnrollFields,
    Sysclass\Models\Forms\Fields as FormFields,
    Phalcon\Mvc\Model\Message as Message;

class Enroll extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll");

        
        $this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\Courses",
            "course_id",
            array('alias' => 'Courses')
        );

        $this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\Fields",
            "enroll_id",
            array('alias' => 'EnrollFields')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Enrollments\\Fields",
            "enroll_id", "field_id",
            "Sysclass\\Models\\Forms\\Fields",
            "id",
            array('alias' => 'Fields')
        );
    }
    
    public function beforeValidationOnCreate() {
        if (is_null($this->identifier)) {
            $random = new \Phalcon\Security\Random();
            $this->identifier = $random->uuid();
        }
        
    }


    public function afterCreate() {
        // CREATE THE SET OF FIELDS
        $fields = FormFields::find("[name] = 'name' OR [name] = 'surname' OR [name] = 'email'");


        foreach($fields as $field) {
            $enrollField = new EnrollFields();
            $enrollField->assign(array(
                'enroll_id' => $this->id,
                'field_id' => $field->id,
                'label' => $field->name,
                'required' => 1
            ));
            $enrollField->save();
        }
    }


}
