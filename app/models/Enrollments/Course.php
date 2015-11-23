<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Message as Message;

class Course extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_course_to_users");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
        $this->belongsTo("course_id", "Sysclass\\Models\\Courses\\Course", "id",  array('alias' => 'Course', 'reusable' => true));

		$this->hasOne(
            array("course_id", "user_id"),
            "Sysclass\Models\Courses\CourseProgress",
            array("course_id", "user_id"),
            array('alias' => 'CourseProgress')
        );

    }

    public function beforeValidationOnCreate() {
        if (is_null($this->token)) {
            $random = new \Phalcon\Security\Random();
            $this->token = $random->uuid();
        }
        
        $count = self::count(array(
            'conditions' => "user_id = ?0 AND course_id = ?1",
            'bind' => array($this->user_id, $this->course_id)
        ));
        if ($count > 0) {
            $message = new Message(
                "It's already a enrollment registered. Please try again.",
                null,
                "warning"
            );
            $this->appendMessage($message);
        }
        return $count == 0;

    }

    public function enroll() {

    }


}
