<?php
namespace Sysclass\Services\Storage;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Storage\Interfaces\IStorage,
    Sysclass\Services\Storage\Exception as StorageException,
    Sysclass\Models\Dropbox\File;

/*
    Phalcon\Events\EventsAwareInterface,
    Phalcon\Events\Event,
    Phalcon\Mvc\Dispatcher,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Services\Authentication\Exception as AuthenticationException,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UsersGroups,
    Sysclass\Models\Users\UserApiTokens,
    Sysclass\Models\Users\UserTimes;
*/
class Adapter extends Component /* implements IStorage */
{
    /*
    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
    */
    protected $backend_class = null;
    protected $backend = null;

    private static $instances = [];

    public static function getInstance($storage) {
        if (array_key_exists($storage, self::$instances)) {
            return $instances[$storage];
        }
        $instances[$storage] = new self();
        $instances[$storage]->initialize($storage);

        return $instances[$storage]->backend;
    }

    public function initialize($storage) {
        $this->setBackend($storage);
    }
    public function getBackend() {
        return $this->backend;
    }
    public function setBackend($storage) {
        $this->backend_class = $this->environment->$storage->backend;

        if (class_exists($this->backend_class)) {
            $this->backend = new $this->backend_class();

            $this->backend->initialize($this->environment->$storage);
        } else {
            throw new StorageException("NO_BACKEND_DISPONIBLE", StorageException::NO_BACKEND_DISPONIBLE);
        }
        return true;
    }



/*
    public function getDefaultBackend() {
        // TODO: GET FROM CONFIGURATION
        $default_backend = ucfirst(strtolower($this->configuration->get("default_auth_backend")));

        $class = "Sysclass\\Services\\Authentication\\Backend\\" . $default_backend;
        if (class_exists($class)) {
            return new $class();
        }

        return false;
    }

    public function getBackend($info) {
        if ($info instanceof User) {
            $user = $info;
        } else {
            $user = User::findFirstByLogin($info['login']);
        }

        if ($user) {
            $class = "Sysclass\\Services\\Authentication\\Backend\\" . ucfirst(strtolower($user->backend));

            if (class_exists($class)) {
                return new $class();
            } else {
                // TRY DEFAULT BACKEND
                return $this->getDefaultBackend();
            }
        }
        return false;
    }
*/

    public function beforeFileCreate(File $struct) {
        return $this->backend->beforeFileCreate($struct);
    }


    public function fileExists(File $struct) {
        return $this->backend->fileExists($struct);
    }

    /* PROXY/ADAPTER PATTERN */
    public function getFilesInFolder($folder) {
        return $this->backend->getFilesInFolder($folder);
    }
    public function getFullFilePath(File $struct) {
        return $this->backend->getFullFilePath($struct);
    }

    public function getFullFileUrl(File $struct) {
        return $this->backend->getFullFileUrl($struct);
    }
    public function getFilestream(File $struct) {
        return $this->backend->getfilestream($struct);
    }
    public function putFilestream(File $struct, $fileStream = null) {
        return $this->backend->putFilestream($struct, $fileStream);
    }

    public function getImageFileInfo(File $struct) {
        return $this->backend->getImageFileInfo($struct);
    }

}
