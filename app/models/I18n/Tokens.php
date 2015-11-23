<?php
namespace Sysclass\Models\I18n;

use Phalcon\Mvc\Model,
	Plico\Mvc\Model\Behavior\Timestampable;

class Tokens extends Model
{
	use Timestampable;

    public function initialize()
    {
        $this->setSource("mod_translate_tokens");

    }
}
