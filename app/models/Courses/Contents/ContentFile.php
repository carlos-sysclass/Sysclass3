<?php
namespace Sysclass\Models\Courses\Contents;

use Plico\Mvc\Model;

class ContentFile extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content_files");

        
    }
}

