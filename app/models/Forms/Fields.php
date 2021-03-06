<?php
namespace Sysclass\Models\Forms;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Message as Message;

class Fields extends Model
{
    public function initialize()
    {
        $this->setSource("mod_fields");
		$this->belongsTo("type_id", "Sysclass\\Models\\Forms\\FieldTypes", "id",  array('alias' => 'Type', 'reusable' => false));


    }

    public function toArray() {
        //var_dump($this->getDI());
        $translate = $this->getDI()->get("translate");

        $result = parent::toArray();

        $type = $this->getType([
            'conditions' => "language_code = ?0",
            'bind' => [$translate->getSource()]
        ]);

        if (!$type) {
            $type = $this->getType([
                'conditions' => "language_code = 'en'"
            ]);
        }
        $result['type'] = $type->toArray();

        return $result;
    }
    /*
    public function translate() {
    	var_dump(static::$_translateFields);
    	var_dump(1);

    	parent::translate();

        exit;

        return $this;
    }
    */

}
