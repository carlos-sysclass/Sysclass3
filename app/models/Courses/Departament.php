<?php
/**
 * @deprecated 3.3.0 Use the Sysclass\Models\Content\Departament
 */
namespace Sysclass\Models\Courses;

use Plico\Mvc\Model;

class Departament extends Model
{
    public function initialize()
    {
        $this->setSource("mod_areas");

		$this->hasMany(
            "id",
            "Sysclass\Models\Courses\Course",
            "area_id",
            array('alias' => 'Courses')
        );

        $this->belongsTo(
            "coordinator_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'Coordinator')
        );

        
    }

    
}
