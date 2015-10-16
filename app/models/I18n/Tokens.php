<?php
namespace Sysclass\Models\I18n;

use Phalcon\Mvc\Model;

class Tokens extends Model
{
    public function initialize()
    {
        $this->setSource("mod_translate_tokens");

    }
}
