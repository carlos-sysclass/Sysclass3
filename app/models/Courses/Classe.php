<?php
/**
 * @deprecated 3.3.0 Use the Sysclass\Models\Content\Course
 */
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class Classe extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes");

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Unit",
            "class_id",
            array(
                'alias' => 'Units'
            )
        );

        $this->hasMany(
        	"id",
        	"Sysclass\\Models\\Courses\\Lesson",
        	"class_id",
        	array(
                'alias' => 'Lessons',
                'conditions' => "type = 'lesson'"
            )
        );
        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Lesson",
            "class_id",
            array(
                'alias' => 'Tests',
                'conditions' => "type = 'test'"
            )
        );


		$this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\ClasseProgress",
            "class_id",
            array('alias' => 'Progress')
        );

        $this->belongsTo(
            "professor_id",
            "Sysclass\\Models\\Users\\User",
            "id",
            array('alias' => 'Professor')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Courses\CourseClasses",
            "class_id", "course_id", 
            "Sysclass\Models\Courses\Course",
            "id",
            array(
                'alias' => 'Courses',
                'params' => array(
                    'order' => '[Sysclass\Models\Courses\CourseClasses].position'
                )
            )
        );
    }

    protected function resetOrder($class_id) {
		$manager = \Phalcon\DI::GetDefault()->get("modelsManager");

		$phql = "UPDATE Sysclass\\Models\\Courses\\Lesson 
			SET position = -1 WHERE class_id = :class_id:";

		return $manager->executeQuery(
		    $phql,
			array(
		        'class_id' => $this->id
		    )
		);
    }

    public function setLessonOrder(array $order_ids) {
        $status = self::resetOrder();
        $manager = \Phalcon\DI::GetDefault()->get("modelsManager");

        foreach($order_ids as $index => $lesson_id) {
			$phql = "UPDATE Sysclass\\Models\\Courses\\Lesson 
				SET position = :position: 
				WHERE id = :id: AND class_id = :class_id:";

			$status->success() && $status = $manager->executeQuery(
			    $phql,
				array(
					'position' => $index + 1,
					'id' => $lesson_id,
			        'class_id' => $this->id
			    )
			);

        }

        return $status->success();
    }

    public function getFullTree() {
        $result = $this->toArray();
        if ($professor =  $this->getProfessor()) {
            $result['professor'] = $professor->toArray();
        } else {
            $result['professor'] = array();
        }
        $result['units'] = array();
        $units = $this->getUnits();
        foreach($units as $unit) {
            $result['units'][] = $unit->getFullTree();
        }

        var_dump($result);
        exit;
    }

}
