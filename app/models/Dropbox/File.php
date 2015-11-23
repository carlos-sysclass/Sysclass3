<?php
namespace Sysclass\Models\Dropbox;

use Phalcon\Mvc\Model,
    Sysclass\Models\Users\User;

class File extends Model
{
    public function initialize()
    {
        $this->setSource("mod_dropbox");
    }

    public function getFileStream() {
    	// GET THE FILE FROM BACKEND
    	
    }
}
