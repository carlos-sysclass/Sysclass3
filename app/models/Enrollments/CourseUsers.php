<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Message as Message;

class CourseUsers extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_course_to_users");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
        $this->belongsTo("course_id", "Sysclass\\Models\\Courses\\Course", "id",  array('alias' => 'Course', 'reusable' => false));

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

    public function afterCreate() {
        // CREATE THE TIMELINE RECORDS
        
        
        // CREATE THE PAYMENT RECORDS
    }    

    public function getProgress() {


        $start = new \DateTime($this->created);

        $end = $this->getCourse()->calculateDuration($start);

        $total_days = $start->diff($end)->days;
        $current_days = $start->diff(new \DateTime())->days;

        /**
         * @todo GET THE USER CALCULATE PROGRESS
         */

        return array(
            'start' => $start,
            'end' => $end,
            'total_days' => $total_days,
            'current_days' => $current_days,
            'expected' => $current_days / $total_days,
            'current' => $this->getCourseProgress()->factor
        );
    }

}
