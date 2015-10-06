<?php
class SetConfigTask extends \Phalcon\CLI\Task
{
    public function versionAction()
    {
    	var_dump($this->configuration->get("default_auth_backend"));

        //echo "\nThis is the default task and the default action \n";
    }
}
