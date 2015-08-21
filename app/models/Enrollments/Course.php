<?php
namespace Sysclass\Models\Enrollments;

use Phalcon\Mvc\Model;

class Course extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_course_to_users");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
        $this->belongsTo("course_id", "Sysclass\\Models\\Courses\\Course", "id",  array('alias' => 'Course', 'reusable' => true));

    }
}