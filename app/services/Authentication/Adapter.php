<?php
namespace Sysclass\Services\Authentication;

use Phalcon\Mvc\User\Component,
    Phalcon\Events\EventsAwareInterface,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Services\Authentication\Exception as AuthenticationException,
    Sysclass\Models\Users,
    Sysclass\Models\UserTimes;

class Adapter extends Component implements IAuthentication, EventsAwareInterface
{

    public function getBackend($info) {
        $user = Users::findFirstByLogin($info['login']);

        if ($user) {
            $class = "Sysclass\\Services\\Authentication\\Backend\\" . ucfirst(strtolower($user->backend));

            if (class_exists($class)) {
                return new $class();
            }
        }
        return false;
    }

    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }

    /* PROXY/ADAPTER PATTERN */
    public function login($info, $options = null)
    {
        $this->_eventsManager->fire("authentication:beforeLogin", $this);

        // LOGIN PROCESS
        // 1.1 Check for username/pass sent
        // 1.2 Check for maintenance mode
        // 1.3 Check for system lock
        // 1.4 Check for account lock and unlock if necessary
        // 1.5 Check for account restrictions (like IP access, 2-way authetication, multiple-login, etc...)
        // 1.6 Create session to handle user login status

        $backend = $this->getBackend($info);

        if ($backend) {
            // 1.1 Check for username/pass sent
            if (($user = $backend->login($info, $options)) === FALSE) {
                throw new AuthenticationException("error", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
            }

            $this->checkForMaintenance($user);


            // 1.4 Check for account lock and unlock if necessary
            if ($user->locked != 0) {
                $user->locked = 0;
            }

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

    protected function checkForMaintenance(Users $user) {
        // 1.2 Check for maintenance mode
        if ($this->configuration->get("maintenance_mode") == TRUE
            && !in_array($user->user_type, array("system_administrator"))
        ) {
            throw new AuthenticationException("error", AuthenticationException::MAINTENANCE_MODE);
        }
        // 1.3 Check for system lock
        if (
            $this->configuration->get("locked_down") &&
            !in_array($user->user_type, array("administrator", "system_administrator"))
        ) {
            throw new AuthenticationException("error", AuthenticationException::LOCKED_DOWN);
        }

        return true;
    }

    public function checkAccess($info = null)
    {
        $this->_eventsManager->fire("authentication:beforeCheckAccess", $this, $user);

        $userTimes = UserTimes::findFirst(array(
            'session_id' => $this->session->getId(),
            'id' => $this->session->get('session_index')
        ));

        if ($userTimes) {
            $user = $userTimes->getUser();

            if ($user) {

                $this->checkForMaintenance($user);
                $userTimes->ping = time();
                $userTimes->save();
                return $user;
            } else {
                $userTimes->expired = 1;
                $userTimes->save();
            }
        }

        $this->_eventsManager->fire("authentication:afterCheckAccess", $this, $user);
        return false;
    }

    protected function registerSession(Users $user) {
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



}
