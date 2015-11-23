<?php
namespace Plico\Mvc\Model\Behavior;

trait Timestampable
{
    public function beforeCreate()
    {
        $this->timestamp = date('U');
    }

    public function beforeUpdate()
    {
    	$this->timestamp = date('U');
    }
}
