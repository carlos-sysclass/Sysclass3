<?php
namespace Sysclass\Services\Authentication;

use Phalcon\Mvc\User\Component,
    Phalcon\Events\EventsAwareInterface,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Services\Authentication\Exception as AuthenticationException,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UserTimes;

class Adapter extends Component implements IAuthentication, EventsAwareInterface
{
    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }

    public function getDefaultBackend() {
        // TODO: GET FROM CONFIGURATION
        return $this->configuration->get("default_auth_backend");
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
                $class = "Sysclass\\Services\\Authentication\\Backend\\" . ucfirst(strtolower($this->getDefaultBackend()));
                if (class_exists($class)) {
                    return new $class();
                }
            }
        }
        return false;
    }

    /* PROXY/ADAPTER PATTERN */
    public function checkPassword($password, User $user = null) {
        if (is_null($user)) {
            $user = $this->checkAccess();
        }
        $backend = $this->getBackend($user);

        return $backend->checkPassword($password, $user);
    }

    public function hashPassword($password, User $user = null) {
        if (is_null($user)) {
            $user = $this->checkAccess();
        }
        $backend = $this->getBackend($user);

        return $backend->hashPassword($password);
    }

    public function login($info, $options = null)
    {
        $this->_eventsManager->fire("authentication:beforeLogin", $this);

        // LOGIN PROCESS
        // 1.1 Check for username/pass sent
        // 1.2 Check for maintenance mode
        // 1.3 Check for system lock
        // 1.4 Check for account lock and unlock if necessary
        // 1.5 Check for account restrictions (like IP access, 2-way authentication, multiple-login, etc...)
        // 1.6 Create session to handle user login status

        $backend = $this->getBackend($info);

        if ($backend) {
            // 1.1 Check for username/pass sent

            if (!array_key_exists('disableBackends', $options) || $options['disableBackends'] == FALSE) {
                if (($user = $backend->login($info, $options)) === FALSE) {
                    throw new AuthenticationException("error", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
                }
            }
            if (!$user) {
                if ($info instanceof User) {
                    $user = $info;
                } else {
                    throw new AuthenticationException("error", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
                }
            }
            try {
                $this->checkForMaintenance($user);
            } catch (AuthenticationException $e) {
                switch($e->getCode()) {
                    case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
                        $user->locked = 0;
                        break;
                    }
                    default : {
                        throw new AuthenticationException($e->getMessage(), $e->getCode());
                    }
                }
            }

            // 1.4 Check for account lock and unlock if necessary
            //if ($user->locked != 0) {
            //}

            // 1.5 Check for account restrictions (like IP access, 2-way authetication)
            // TODO
            //
            $this->registerSession($user);

            //$this->_eventsManager->collectResponses(true);
            $this->_eventsManager->fire("authentication:afterLogin", $this, $user);

            $user->save();

            return $user;

        }
        throw new AuthenticationException("error", AuthenticationException::NO_BACKEND_DISPONIBLE);
        return false;
    }

    public function logout(User $user = null) {
        $this->_eventsManager->fire("authentication:beforeLogout", $this);

        if (is_null($user)) {
            $user = $this->checkAccess();
        }

        // LOGOUT PROCESS

        $backend = $this->getBackend($user);

        if ($backend) {
            if (!$backend->logout($user)) {
                // TODO: stop the logout process
                //throw new AuthenticationException("error", AuthenticationException::CANT_LOGOUT_RIGHT_NOW);
            }

            // REGISTER USER LOGOUT EVENT
            $this->unregisterSession($user);

            //var_dump($this->modelsCache-> exists("UserTimes"));
            //
            $this->modelsCache->delete("User");
            $this->modelsCache->delete("UserTimes");
            //var_dump($this->modelsCache->get("UserTimes"));
            //$this->modelsCache->flush();
            //exit;

            //$this->_eventsManager->collectResponses(true);
            $this->_eventsManager->fire("authentication:afterLogout", $this, $user);

           // $user->save();

            return $user;

        }
        throw new AuthenticationException("error", AuthenticationException::NO_BACKEND_DISPONIBLE);
        return false;

        $this->_eventsManager->fire("authentication:afterLogout", $this, $user);
    }

    public function lock(User $user = null) {
        $this->_eventsManager->fire("authentication:beforeLock", $this);

        if (is_null($user)) {
            $user = $this->checkAccess();
        }

        $user->locked = 1;
        $user->save();

        $this->_eventsManager->fire("authentication:afterLock", $this, $user);

        return $user;
    }


    protected function checkForMaintenance(User $user) {
        // 1.4 Check for account explicit lock
        if ($user->locked == 1) {
            throw new AuthenticationException("USER_ACCOUNT_IS_LOCKED", AuthenticationException::USER_ACCOUNT_IS_LOCKED);
        }

        // 1.2 Check for maintenance mode
        if ($this->configuration->get("maintenance_mode") == TRUE
            && !in_array($user->user_type, array("system_administrator"))
        ) {
            throw new AuthenticationException("MAINTENANCE_MODE", AuthenticationException::MAINTENANCE_MODE);
        }
        // 1.3 Check for system lock
        if (
            $this->configuration->get("locked_down") &&
            !in_array($user->user_type, array("administrator", "system_administrator"))
        ) {
            throw new AuthenticationException("LOCKED_DOWN", AuthenticationException::LOCKED_DOWN);
        }

        return true;
    }

    public function checkAccess($info = null)
    {
        $this->_eventsManager->fire("authentication:beforeCheckAccess", $this, $user);

        if ($this->session->has('session_index')) {

            $userTimes = $this->getSession();

            if ($userTimes) {

                $user = $userTimes->getUser();

                if ($user) {

                    $this->checkForMaintenance($user);
                    if (time() - $userTimes->ping > 90) {
                        $this->modelsCache->delete("UserTimes");

                        $userTimes->ping = time();
                        $userTimes->save();
                    }

                    return $user;
                } else {
                    $userTimes->expired = 1;
                    $userTimes->save();
                }
            } else {
                throw new AuthenticationException("NO_USER_LOGGED_IN", AuthenticationException::NO_USER_LOGGED_IN);
            }

            $this->_eventsManager->fire("authentication:afterCheckAccess", $this, $user);
        } else {
            throw new AuthenticationException("NO_USER_LOGGED_IN", AuthenticationException::NO_USER_LOGGED_IN);
        }

        return false;
    }

    protected function getSession() {
        $userTimes = UserTimes::findFirst(array(
            "session_id = '{$this->session->getId()}'",
            "id = {$this->session->get('session_index')}",
            'expired = 0'
        ));

        if ($userTimes) {
            return $userTimes;
        }
        return false;

    }

    public function getSessionUser() {
        $userTimes = $this->getSession();

        if ($userTimes) {
            return $user = $userTimes->getUser();
        }
        return false;

    }

    protected function registerSession(User $user) {
        //$this->session()
        $token = $this->crypt->encrypt($user->password, '%31.1e#a&!$i86e$f!8jz');

        $this->session->set('token', $token);

        $userTimes = UserTimes::findFirstBySessionId($this->session->getId());
        if ($userTimes) {
            $userTimes->delete();
        }

        $userTimes = new UserTimes();
        $userTimes->session_id = $this->session->getId();
        $userTimes->user_id = $user->id;
        $userTimes->started = time();
        $userTimes->ping = time();

        $userTimes->save();

        $this->session->set('session_index', $userTimes->id);

        return true;
    }

    protected function unregisterSession(User $user) {
        if ($this->session->has('session_index')) {

            // EXPIRES ALL USERS PENDING SESSION

            $userTimes = UserTimes::find(array(
                "user_id = {$user->id}",
                'expired = 0'
            ));
            if ($userTimes) {
                foreach($userTimes as $userTime) {
                    $userTime->expired = 1;
                    $userTime->save();
                }
            }
        }
        $this->session->destroy();

        return true;
    }





}
