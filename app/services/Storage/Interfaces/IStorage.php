<?php
namespace Sysclass\Services\Storage\Interfaces;

use Sysclass\Models\Users\User,
	Sysclass\Models\Dropbox\File;

interface IStorage {
    public function getfilestream(File $struct);
}
