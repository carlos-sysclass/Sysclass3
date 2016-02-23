<?php
namespace Sysclass\Models\Courses;

use Phalcon\Mvc\Model;

class Classe extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes");

        $this->hasMany(
        	"id",
        	"Sysclass\\Models\\Courses\\Lesson",
        	"class_id",
        	array('alias' => 'Lessons')
        );

		$this->hasOne(
            "id",
            "Sysclass\\Models\\Courses\\ClasseProgress",
            "class_id",
            array('alias' => 'Progress')
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

}
