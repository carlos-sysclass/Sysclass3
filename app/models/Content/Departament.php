<?php
namespace Sysclass\Models\Content;

use Plico\Mvc\Model;

class Departament extends Model
{
    public function initialize()
    {
        $this->setSource("mod_areas");

		$this->hasMany(
            "id",
            "Sysclass\Models\Content\Program",
            "area_id",
            array('alias' => 'Programs')
        );

        $this->belongsTo(
            "coordinator_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'Coordinator')
        );

        
    }

    
}
