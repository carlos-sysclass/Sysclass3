<?php
namespace Sysclass\Models\Courses\Questions;

use Phalcon\Mvc\Model;

class Difficulty extends Model
{
    public function initialize()
    {
        $this->setSource("mod_questions_difficulties");

    }
}
