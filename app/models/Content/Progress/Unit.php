<?php
namespace Sysclass\Models\Content\Progress;

use Phalcon\Mvc\Model,
    Sysclass\Models\Content\Progress\Course as CourseProgress,
    Sysclass\Models\Content\Progress\Content as ContentProgress;

class Unit extends Model
{
    public function initialize()
    {
        $this->setSource("mod_units_progress");

        $this->belongsTo("unit_id", "Sysclass\\Models\\Content\\Unit", "id",  array('alias' => 'Unit'));
    }

    public function updateProgress() {
        // GET RELATED UNIT, AND CALL AN UPDATE
        // CALCULATE BASED ON CONTENTS
        $manager = $this->getDI()->get("modelsManager");
        //$phql = "SELECT * /* AVG(factor) as factor*/

        $phql = "SELECT AVG(IFNULL(factor, 0)) as factor 
            FROM Sysclass\\Models\\Courses\\Contents\\Content as c
        	LEFT JOIN Sysclass\\Models\\Content\\Progress\\Content as cp
                ON (c.id = cp.content_id AND (user_id = ?1 OR user_id IS NULL))
            WHERE c.unit_id = ?0 
                AND c.content_type NOT IN ('subtitle', 'poster', 'subtitle-translation')
        ";

        $data = $manager->executeQuery($phql, array($this->unit_id, $this->user_id));

        if ($data->count() > 0) {
            $this->factor = $data[0]->factor;
        } else {
            $this->factor = 1;
        }

        if ($this->save()) {
	        $log[] = array(
	        	'type' => 'success',
	        	'message' => sprintf('Progress for unit #%s for user #%s updated.', $this->unit_id, $this->user_id),
	        	'status' => true,
                'entity' => 'unit',
                'data' => $this->toArray()
	        );
        } else {
	        $log[] = array(
	        	'type' => 'error',
	        	'message' => sprintf('Error when trying to update progress for unit #%sd for user #%s updated.', $this->unit_id, $this->user_id),
	        	'status' => false
	        );

        }

    	// CALL UPDATE ON CLASS
        $unit = $this->getUnit();

        $classProgress = CourseProgress::findFirst(array(
            'conditions' => 'user_id = ?0 AND class_id = ?1',
            'bind' => array($this->user_id, $unit->class_id)
        ));

        if (!$classProgress) {
            $classProgress = new CourseProgress();
            $classProgress->user_id = $this->user_id;
            $classProgress->class_id = $unit->class_id;
            $classProgress->save();
        }

        return array_merge($classProgress->updateProgress(), $log);
    }
}
