<?php
namespace Sysclass\Models\Dropbox;

use Plico\Mvc\Model,
    Sysclass\Models\Users\User,
    Sysclass\Services\Storage\Adapter as StorageAdapter;

class File extends Model
{
    public function initialize()
    {
        $this->setSource("mod_dropbox");

        $this->belongsTo(
            "language_code",
            "Sysclass\Models\I18n\Language",
            "code",
            array("alias" => 'Language')
        );
    }

    public function getFileStream() {
    	// GET THE FILE FROM BACKEND
    }

    public function addOrUpdate() {
    	if (!empty($this->id)) { 
			return call_user_func_array([$this, "update"], func_get_args());
    	}
    	if (!empty($this->etag)) {
    		$exists = self::findFirstByEtag($this->etag);
    		if ($exists) {
    			$this->id = $exists->id; 

    			return call_user_func_array([$this, "update"], func_get_args());
    		}
    	}
    	return call_user_func_array([$this, "create"], func_get_args());
    }

    public function beforeValidation() {
    	if (is_null($this->owner_id)) {
    		$user = $this->getDI()->get('user');
    		if ($user) {
    			$this->owner_id = $user->id;
    		}
    	}

    	if (empty($this->active)) {
    		$this->active = 1;
    	}

        $storage = StorageAdapter::getInstance($this->storage);
        $this->url = $storage->getFullFileUrl($this);

        if (is_null($this->language_code)) {
            $translator = $this->getDI()->get('translate');

            $this->language_code = $translator->getSource();
        }
    }

    public function beforeSave() {
        /*
    	if (!empty($this->storage)) {
    		$status = $this->getDI()->get($this->storage)->beforeFileCreate($this);
    	}

    	return $status;
        */
    }

    public function afterSave() {
        /*
        $storage = StorageAdapter::getInstance($this->storage);
        $storage->getFullFileUrl($this);

        return true;
        */
    }
}
