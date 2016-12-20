<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class CoursePeriods extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses_periods");

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Content\\Course",
            "period_id",
            array(
                'alias' => 'Courses'
            )
        );
    }
}
