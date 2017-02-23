<?php
/**
  * @deprecated 3.4.0 Use Sysclass\Models\Content\ContentFile instead
 */ 

namespace Sysclass\Models\Courses\Contents;

use Plico\Mvc\Model;

class ContentFile extends Model
{
    public function initialize()
    {
        $this->setSource("mod_units_content_files");

        
    }
}

