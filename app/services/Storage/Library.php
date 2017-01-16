<?php
namespace Sysclass\Services\Storage;

use Phalcon\Mvc\User\Component,
    Sysclass\Services\Storage\Interfaces\IStorage,
    Sysclass\Services\Storage\Exception as StorageException,
    Sysclass\Models\Dropbox\File,
    Sysclass\Services\Storage\Adapter as StorageAdapter;

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
class Library extends Component /* implements IStorage */
{
    /*
    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
    */
    public function initialize() {
        /*
        $remote_storage = $this->environment->remote_storage;
        var_dump($remote_storage);
        exit;

        $this->setBackend($backend_class);

        $this->backend->initialize();
        */
    }

    public static function find($params) {
        $args = $params['args'];

        //$remote_storage = \Phalcon\DI::getDefault()->get('remote_storage');
        $remote_storage = StorageAdapter::getInstance('remote_storage');

        //$args['path'] = "video-queue";
        $status = $remote_storage->getFilesInFolder($args['path']);

        return $status;
    }

    public static function findSourcesById($id) {
        $file = File::findFirstById($id);

        if ($file) {
            if ($file->storage)

                $storage = StorageAdapter::getInstance($file->storage);

                $storage->getFullFileUrl($file);

                var_dump($file->toArray());
                exit;
        }
        return false;
    }

    /*

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


    public function fileExists(File $struct) {
        return $this->backend->fileExists($struct);
    }
    */
    /* PROXY/ADAPTER PATTERN */
    /*
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
    */

}
