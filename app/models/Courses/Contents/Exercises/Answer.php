<?php
namespace Sysclass\Models\Courses\Contents\Exercises;

use Phalcon\Mvc\Model;

class Answer extends Model
{
    public function initialize()
    {
        $this->setSource("mod_units_content_questions_answers");
    }
}

