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
class Adapter extends Component implements IStorage
{
    /*
    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
    */
    protected $backend_class = null;
    protected $backend = null;

    public function initialize() {
        $backend_class = $this->environment->storage->backend;

        $this->setBackend($backend_class);

        $this->backend->initialize();
    }

    public function setBackend($class) {
        if (class_exists($class)) {
            $this->backend = new $class();
        } else {
            throw new StorageException("NO_BACKEND_DISPONIBLE", StorageException::NO_BACKEND_DISPONIBLE);
        }
        return true;

    }

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




    /* PROXY/ADAPTER PATTERN */
    public function getFullFilePath(File $struct) {
        return $this->backend->getFullFilePath($struct);
    }

    public function getFullFileUrl(File $struct) {
        return $this->backend->getFullFileUrl($struct);
    }
    public function getFilestream(File $struct) {
        return $this->backend->getfilestream($struct);
    }
   

}
