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

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));
    }

    public function beforeSave() {
    	if ($this->edited == 1) {
    		$this->user_id = $this->getDI()->get("user")->id;
    	}
    	$this->timestamp = time();
    }
}
