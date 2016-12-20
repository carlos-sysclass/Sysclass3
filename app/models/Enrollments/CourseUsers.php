<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model,
    Phalcon\DI,
    Sysclass\Models\Enrollments\Courses as EnrollCourses,
    Phalcon\Mvc\Model\Message as Message,
    Phalcon\Mvc\Model\Query;

class CourseUsers extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_course_to_users");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
        $this->belongsTo("course_id", "Sysclass\\Models\\Content\\Program", "id",  array('alias' => 'Course', 'reusable' => false));
        /*
        $this->belongsTo("status_id", "Sysclass\\Models\\Enrollments\\CourseUsersStatus", "id",  array('alias' => 'Status', 'reusable' => false));
        */
		$this->hasOne(
            array("course_id", "user_id"),
            "Sysclass\Models\Content\Progress\Program",
            array("course_id", "user_id"),
            array('alias' => 'CourseProgress')
        );

    }

    public function beforeValidation() {
        $depinj = DI::getDefault();
        $translator = $depinj->get("translate");

        $user = $depinj->get("user");
        if (is_null($this->user_id)) {
            $this->user_id = $user->id;
        } else {
            // CHECK IF THE USER HAS PERMISSION TO INCLUDE ANOTHER USER
            if ($this->user_id != $user->id) {
                $acl = $depinj->get("acl");
                if (!$acl->isUserAllowed(null, "enroll", "users")) {
                    $message = new Message(
                        $translator->translate("You don't have access to this resource."
                        ),
                        null,
                        "danger"
                    );
                    $this->appendMessage($message);
                    return false;
                }
            }
        }
    }

    public function beforeValidationOnCreate() {
        $depinj = DI::getDefault();
        $translator = $depinj->get("translate");
        if (is_null($this->token)) {
            $random = new \Phalcon\Security\Random();
            $this->token = $random->uuid();
        }

        // CHECK FOR ENROLLMENT PARAMETERS
        $enrollment = EnrollCourses::findFirstById($this->enroll_id);

        if ($enrollment->signup_active) {
            // @todo CHECK FOR ENROLLMENT DATES
            if ($enrollment->signup_auto_approval) {
                $this->approved = 1;
            } else {
                $this->approved = 0;
            }
        } else {
            $message = new Message(
                $translator->translate("This enrollment options is not avaliable right now"
                ),
                null,
                "warning"
            );
            $this->appendMessage($message);
            return false;
        }

        $count = self::count(array(
            'conditions' => "user_id = ?0 AND course_id = ?1",
            'bind' => array($this->user_id, $this->course_id)
        ));
        if ($count > 0) {
            $message = new Message(
                $translator->translate("It's already a enrollment registered. Please try again."),
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

    public function isCompleted() {
        $progress = $this->getCourseProgress();

        return $progress->completed == 1;
    }

    public function complete($force = false) {
        // TODO: CHECK IF THE COURSE CAN BE COMPLETED AND MAKE THE COMPLETION LOGIC
        $evManager = $this->getDI()->get("eventsManager");

        $evManager->fire("course:user-completed", $this, $this->toArray());
        //var_dump(get_class($evManager));
        
        $progress = $this->getCourseProgress();

        $progress->complete();
        

        //$this->eventsManager;
    }

    public static function getUsersNotEnrolled($filter, $search = null) {

        $where = [];
        $subwhere = [];
        $params = [];

        if (!is_null($search)) {
            $where[] = "LOWER(CONCAT(u.name, ' ', u.surname)) LIKE LOWER(:query:)";
            $params['query'] = '%' . $search . '%';
        }

        $subsql = "SELECT user_id FROM Sysclass\\Models\\Enrollments\\CourseUsers";

        if (is_array($filter) && array_key_exists('course_id', $filter)) {
            $subwhere[] = "course_id = :course_id:";
            $params['course_id'] = $filter['course_id'];
        }

        if (is_array($filter) && array_key_exists('enroll_id', $filter)) {
            $subwhere[] = "enroll_id = :enroll_id:";
            $params['enroll_id'] = $filter['enroll_id'];
        }

        if (count($subwhere) > 0) {
            $subsql .= " WHERE " . implode(" AND ", $subwhere);
            $where[] = sprintf("u.id NOT IN (%s)", $subsql);
        }


        //if (is_null($search)) {
        $sql = "SELECT u.* 
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Enrollments\\CourseUsers cu ON (u.id = cu.user_id)";
        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        /*
        } else {
            $sql = "SELECT u.*
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Enrollments\\CourseUsers cu ON (u.id = cu.user_id)
            WHERE (cu.enroll_id <> :enroll_id: OR cu.enroll_id IS NULL)
            AND LOWER(CONCAT(u.name, ' ', u.surname)) LIKE LOWER(:query:)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("enroll_id" => $enroll_id, 'query' => '%' . $search . '%'));
        }
        */

        $query = new Query($sql, DI::getDefault());
        $users   = $query->execute($params);

        //var_dump($users->toArray());

        return $users;
    }

    public static function getUsersEnrolled($filter, $search = null) {

        $where = [];
        $params = [];

        if (!is_null($search)) {
            $where[] = "LOWER(CONCAT(u.name, ' ', u.surname)) LIKE LOWER(:query:)";
            $params['query'] = '%' . $search . '%';
        }

        if (is_array($filter) && array_key_exists('course_id', $filter)) {
            $where[] = "(cu.course_id = :course_id:)";
            $params['course_id'] = $filter['course_id'];
        }

        if (is_array($filter) && array_key_exists('enroll_id', $filter)) {
            $where[] = "(cu.enroll_id = :enroll_id:)";
            $params['enroll_id'] = $filter['enroll_id'];
        }


        //if (is_null($search)) {
        $sql = "SELECT cu.id as id, u.id as user_id, u.name, u.surname, cu.status_id as active,
                cu.approved
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Enrollments\\CourseUsers cu ON (u.id = cu.user_id)";
        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }


        /*
        if (is_null($search)) {
            $sql = "SELECT cu.id as id, u.id as user_id, u.name, u.surname, cu.status_id as active
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Enrollments\\CourseUsers cu ON (u.id = cu.user_id)
            WHERE (cu.enroll_id = :enroll_id:)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("enroll_id" => $enroll_id));
        } else {
            $sql = "SELECT cu.id as id, u.id as user_id, u.name, u.surname, cu.status_id as active
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Enrollments\\CourseUsers cu ON (u.id = cu.user_id)
            WHERE (cu.enroll_id = :enroll_id:)
            AND LOWER(CONCAT(u.name, ' ', u.surname)) LIKE LOWER(:query:)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("enroll_id" => $role_id, 'query' => '%' . $search . '%'));
        }
        */
        $query = new Query($sql, DI::getDefault());
        $users   = $query->execute($params);


        return $users;
    }

}
