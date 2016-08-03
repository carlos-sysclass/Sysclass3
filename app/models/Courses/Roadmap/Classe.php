<?php
namespace Sysclass\Models\Courses\Roadmap;

use Plico\Mvc\Model;

class Classe extends Model
{
    public function initialize()
    {
        $this->setSource("v_roadmap_classes");
    }

    public function beforeSave() {
    	var_dump(1);
    	exit;
    }
}
