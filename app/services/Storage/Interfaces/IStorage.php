<?php
namespace Sysclass\Services\Storage\Interfaces;

use Sysclass\Models\Users\User,
	Sysclass\Models\Dropbox\File;

interface IStorage {
    public function getFilestream(File $struct);
    public function getFullFilePath(File $struct);
    public function getFullFileUrl(File $struct);
}
