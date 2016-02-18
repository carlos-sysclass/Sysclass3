<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\DI,
    Sysclass\Models\Users\User,
    Sysclass\Models\Courses\Course,
    Sysclass\Models\Enrollments\CourseUsers,
    Sysclass\Models\Enrollments\Fields as EnrollFields,
    Sysclass\Models\Forms\Fields as FormFields,
    Phalcon\Mvc\Model\Message as Message;

class Enroll extends Model
{
    const FAIL_EXPIRED_PACKAGE = "FAIL_EXPIRED_PACKAGE";

    public function initialize()
    {
        $this->setSource("mod_enroll");

        $this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\Courses",
            "enroll_id",
            array('alias' => 'Courses')
        );


        $this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\CourseUsers",
            "enroll_id",
            array('alias' => 'CourseUsers')
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
    /**
     * Check if is allowed to enroll a new user!
     * @return boolean|object returns false if not possible, true if possible wihtout group, or a object containing the group for the user be allocated
     */
    public function isAllowed() {
        // CHECK IF THE ENROLL HAS BEEN EXPIRED 
        if ($this->end_date != 0) {
            $endDate = \DateTime::createFromFormat("U", $this->end_date);
            $today = new \DateTime("now");
            if ($endDate < $today) {
                return array(
                    'error' => true,
                    'reason' => self::FAIL_EXPIRED_PACKAGE
                );
            }
        }

        
        if ($this->admittance_type == "individual") {
            return array(
                'error' => false
            );
        } else {
            /**
             * @todo  CHECK FOR DEFINED GROUP AND LIMITS
             */
        }
        return true;
    }

    public function enrollUser(User $user, Course $course) {

        $enrollment = new CourseUsers();
        $enrollment->assign(array(
            'enroll_id' => $this->id,
            'user_id' => $user->id,
            'course_id' => $course->id
        ));

        $enrollment->save();
        return $enrollment->getMessages();
    }

}
