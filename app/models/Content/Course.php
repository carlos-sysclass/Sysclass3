<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User;

class Course extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes");

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Content\\Unit",
            "class_id",
            array(
                'alias' => 'Units',
                'params' => array(
                    'order' => '[Sysclass\Models\Content\Unit].position ASC, [Sysclass\Models\Content\Unit].id ASC'
                )
            )
        );

        $this->hasMany(
        	"id",
        	"Sysclass\\Models\\Content\\Unit",
        	"class_id",
        	array(
                'alias' => 'Units',
                'params' => array(
                    'conditions' => "type = 'unit'",
                    'order' => '[Sysclass\Models\Content\Unit].position ASC, [Sysclass\Models\Content\Unit].id ASC'
                )
                
            )
        );
        $this->hasMany(
            "id",
            "Sysclass\\Models\\Courses\\Tests\\Unit",
            "class_id",
            array(
                'alias' => 'Tests',
                'params' => array(
                    'conditions' => "type = 'test'",
                    'order' => '[Sysclass\Models\Courses\Tests\Unit].position ASC, [Sysclass\Models\Courses\Tests\Unit].id ASC'
                )
            )
        );


		$this->hasOne(
            "id",
            "Sysclass\\Models\\Content\\Progress\\Course",
            "class_id",
            array('alias' => 'Progress')
        );

        $this->belongsTo(
            "professor_id",
            "Sysclass\\Models\\Users\\User",
            "id",
            array('alias' => 'Professor')
        );

        $this->belongsTo(
            "course_id",
            "Sysclass\Models\Content\Program",
            "id",
            array(
                'alias' => 'Program'
            )
        );

        $this->belongsTo(
            "period_id",
            "Sysclass\\Models\\Content\\CoursePeriods",
            "id",
            array('alias' => 'Period')
        );
    }

    protected function beforeValidation() {
        if (is_null($this->active) || $this->active) {
            $this->active = 1;
        } else {
            $this->active = 0;
        }
    }

    protected function resetOrder($class_id) {
		$manager = \Phalcon\DI::GetDefault()->get("modelsManager");

		$phql = "UPDATE Sysclass\\Models\\Content\\Unit 
			SET position = -1 WHERE class_id = :class_id:";

		return $manager->executeQuery(
		    $phql,
			array(
		        'class_id' => $this->id
		    )
		);
    }

    public function setUnitOrder(array $order_ids) {
        $status = self::resetOrder();
        $manager = \Phalcon\DI::GetDefault()->get("modelsManager");

        foreach($order_ids as $index => $unit_id) {
			$phql = "UPDATE Sysclass\\Models\\Content\\Unit
				SET position = :position: 
				WHERE id = :id: AND class_id = :class_id:";

			$status->success() && $status = $manager->executeQuery(
			    $phql,
				array(
					'position' => $index + 1,
					'id' => $unit_id,
			        'class_id' => $this->id
			    )
			);

        }

        return $status->success();
    }

    public function getFullTree(User $user = null, $only_active = false) {
        if (is_null($user)) {
            $user = \Phalcon\DI::getDefault()->get('user');
        }

        $result = $this->toArray();
        if ($professor =  $this->getProfessor()) {
            $result['professor'] = $professor->toArray();
        } else {
            $result['professor'] = array();
        }
        $result['units'] = array();

        if ($only_active) {
            $units = $this->getUnits([
                'conditions' => "active = 1"
            ]);
        } else {
            $units = $this->getUnits();
        }

        foreach($units as $unit) {
            $result['units'][] = $unit->getFullTree($user, $only_active);
        }

        $user_id = $user->id;

        $progress = $this->getProgress(array(
            'conditions' => "user_id = ?0",
            'bind' => array($user_id)
        ));

        if ($progress) {
            $result['progress'] = $progress->toArray();   
            $result['progress']['factor'] = floatval($result['progress']['factor']);
        } else {
            $result['progress'] = array(
                'factor' => 0
            );
        }


        return $result;
    }

}
