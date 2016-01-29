<?php
namespace Sysclass\Models\Courses\Contents;

use Phalcon\Mvc\Model;

class Progress extends Model
{
    public function initialize()
    {
        $this->setSource("mod_lessons_content_progress");


    }
    /*
    public function metaData()
    {
        print_r($this->getModelsMetaData());
        exit;
    }
    */
}

