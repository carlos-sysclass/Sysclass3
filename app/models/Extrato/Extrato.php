<?php
namespace Sysclass\Models\Extrato;

use Phalcon\Mvc\Model;

class Extrato extends Model
{
    public function initialize()
    {
        $this->setSource("mod_classes");

        $this->hasMany("id", "Sysclass\\Models\\Extrato", "class_id",  array('alias' => 'Extrato'));
    }

}
