<?php
namespace Sysclass\Services\Storage\Backend;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Storage\Interfaces\IStorage,
    Sysclass\Models\Dropbox\File;

class LocalFilesystem extends Component implements IStorage {

    public function initialize() {
        $this	->base_path = 
        	$this->environment['path/app'] . 
        	$this->environment->storage_localfilesystem->base_path;
        $this->base_url = 
        	$this->environment['http/fqdn'] . "/" .
        	$this->environment->storage_localfilesystem->base_url;
    }

    public function getFullFilePath(File $struct) {
    	$file_path = $this->base_path . $struct->upload_type . "/" . $struct->filename;
    	if (file_exists($file_path)) {
    		return $file_path;
    	}
    	return false;
    }

    public function getFullFileUrl(File $struct) {
        return $file_path = $this->base_url . $struct->upload_type . "/" . $struct->filename;
    }
    

	public function getFilestream(File $struct) {
		if ($file_path = $this->getFullFilePath($struct)) {
			return file_get_contents($file_path);
		}
		return false;
	}

    public function putFilestream(File $struct, $fileStream = null) {
        $file_path = $this->getFullFilePath($struct);
        $bytes  = file_put_contents($file_path, $fileStream);
        
        return $bytes;
    }

    public function getImageFileInfo(File $struct) {
        if ($file_path = $this->getFullFilePath($struct)) {
            $info = getimagesize($file_path);
            return array(
                'width' => $info[0],
                'height' => $info[1],
                'type'  => $info[2],
                'mime' => $info['mime']
            );
        }
        return false;
    }

    public function fileExists(File $struct) {
        if ($file_path = $this->getFullFilePath($struct)) {
            return file_exists($file_path);
        }
        return false;
    }
}
