<?php
namespace Sysclass\Models\System;

use Phalcon\Mvc\Model;

class Colors extends Model
{
    public function initialize() {
        $this->setSource("mod_colors");
    }
}
