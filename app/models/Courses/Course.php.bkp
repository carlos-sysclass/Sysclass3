<?php
/**
 * @deprecated 3.3.0 Use the Sysclass\Models\Content\Program
 */
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class Course extends Model
{
    public function initialize()
    {
        $this->setSource("mod_courses");
        
		$this->hasManyToMany(
            "id",
            "Sysclass\Models\Courses\CourseClasses",
            "course_id", "class_id",
            "Sysclass\Models\Courses\Classe",
            "id",
            array(
                'alias' => 'Classes',
                'params' => array(
                    'order' => '[Sysclass\Models\Courses\CourseClasses].position'
                )
            )
        );
        
        $this->hasMany(
            "id",
            "Sysclass\Models\Courses\CourseClasses",
            "course_id",
            array(
                'alias' => 'CourseClasses',
                'params' => array(
                    'order' => '[Sysclass\Models\Courses\CourseClasses].position'
                )
            )
        );

        $this->belongsTo(
            "coordinator_id",
            "Sysclass\Models\Users\User",
            "id",
            array(
                'alias' => 'Coordinator',
            )
        );

        $this->belongsTo(
            "language_id",
            "Sysclass\Models\I18n\Language",
            "id",
            array(
                'alias' => 'language',
            )
        );

        $this->belongsTo(
            "image_id",
            "Sysclass\Models\Dropbox\File",
            "id",
            array(
                'alias' => 'Image',
            )
        );
    }
/*
    public function toFullArray($manyAliases = null, $itemData = null, $extended = false) {
        $item = parent::toFullArray($manyAliases, $itemData, $extended);

        var_dump($item);
        return $item;
        exit;
    }*/

    public function calculateDuration(\DateTime $start) {
        switch($this->duration_type) {
            case "week" : {
                $duration = $this->duration_units * 7;
                $interval = "P{$duration}D";
                break;
            }
            case "year" : {
                $interval = "P{$this->duration_units}Y";
                break;
            }
            case "month" : {
                $interval = "P{$this->duration_units}M";
                break;
            }
            default : {
                $interval = false;
            }
        }

        $end = clone $start;
        if ($interval) {
            return $end->add(new \DateInterval($interval));
        }
        return $end;
    }
}
