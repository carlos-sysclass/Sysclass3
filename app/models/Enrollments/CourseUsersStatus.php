<?php
namespace Sysclass\Models\Enrollments;

use Plico\Mvc\Model;

class CourseUsersStatus extends Model
{
    public function initialize()
    {
        $this->setSource("mod_enroll_course_to_users_status");
        
		$this->hasMany(
            "id",
            "Sysclass\Models\Enrollments\CourseUsers",
            "status_id",
            array('alias' => 'CourseProgress')
        );
        
    }
}
