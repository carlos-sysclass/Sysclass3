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

    public static function getUserProgress($full = false) {
        $user = \Phalcon\DI::getDefault()->get("user");

        $enrolledCourses = self::find(array(
            'conditions' => 'user_id = ?0',
            'bind' => array($user->id)
        ));
        $progress = array();
        foreach($enrolledCourses as $course) {
            $progress[$course->id] = $course->getProgress($full);
        }
        return $progress;
    }

    public function getProgress($full = false) {
        $start = new \DateTime($this->created);

        $course = $this->getCourse();

        $end = $course->calculateDuration($start);

        $total_days = $start->diff($end)->days;
        $current_days = $start->diff(new \DateTime())->days;

        /**
         * @todo GET THE USER CALCULATE PROGRESS
         */
        $info = array(
            "id" => $course->id,
            "name" => $course->name,
            'start' => $start,
            'end' => $end,
            'total_days' => $total_days,
            'current_days' => $current_days,
            'expected' => $current_days / $total_days,
            'current' => $this->getCourseProgress()->factor
        );
        if ($full) {
            $info['classes'] = array(
                'total' => 0,
                'completed' => 0,
                'started' => 0,
                'expected' => 0 // TODO: CALCULATE THIS VALUE
            );
            $info['lessons'] = array(
                'total' => 0,
                'completed' => 0,
                'started' => 0,
                'expected' => 0 // TODO: CALCULATE THIS VALUE
            );

            $classes = $course->getClasses();
            $info['classes']['total'] = $classes->count();

            $class_expected_days = $total_days / $info['classes']['total'];
            $info['classes']['expected'] = floor($current_days / $class_expected_days);

            $class_start_interval = 0;

            $info['lessons']['total'] = 0;
            foreach($classes as $classe) {
                
                $progress = $classe->getProgress(array(
                    'conditions' => 'user_id = ?0',
                    'bind' => array($this->user_id)
                ));

                if ($progress) {
                    $info['classes']['completed'] += (floatval($progress->factor) == 1) ? 1 : 0;
                    $info['classes']['started'] += (floatval($progress->factor) > 0) ? 1 : 0;
                }

                $lessons = $classe->getLessons();
                $info['lessons']['total'] += $lessons->count();

                $startOffset = $current_days - $class_start_interval;

                if ($class_start_interval > $current_days) {
                    $info['lessons']['expected'] += 0;
                } elseif ($startOffset > 0 && $startOffset < $class_expected_days) {
                    $lesson_expected_days = $class_expected_days / $lessons->count();

                    $info['lessons']['expected'] += floor(($current_days - $class_start_interval) / $lesson_expected_days);
                } else {
                    $info['lessons']['expected'] += $lessons->count();
                }
                $class_start_interval +=  $class_expected_days;

                foreach($lessons as $lesson) {
                    $progress = $lesson->getProgress(array(
                        'conditions' => 'user_id = ?0',
                        'bind' => array($this->user_id)
                    ));

                    if ($progress) {
                        $info['lessons']['completed'] += (floatval($progress->factor) == 1) ? 1 : 0;
                        $info['lessons']['started'] += (floatval($progress->factor) > 0) ? 1 : 0;
                    }
                }
            }
        }

        return $info;
    }

    public function isCompleted(){
        return true;
    }

    public function complete() {
        // TODO: CHECK IF THE COURSE CAN BE COMPLETED AND MAKE THE COMPLETION LOGIC
        $evManager = $this->getDI()->get("eventsManager");
        $evManager = $this->getDI()->get("eventsManager");

        $evManager->fire("course:user-completed", $this, $this->toArray());
        //var_dump(get_class($evManager));

        //$this->eventsManager;
    }

}
