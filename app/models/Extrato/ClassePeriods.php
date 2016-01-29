<?php
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class ClassePeriods extends Model
{
    public function initialize()
    {
        $this->setSource("mod_roadmap_classes_to_periods");
    }
}
