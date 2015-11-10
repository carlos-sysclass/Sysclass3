<?php
namespace Sysclass\Services\Authentication;

use Phalcon\Mvc\User\Component,
    Phalcon\Events\EventsAwareInterface,
    Phalcon\Events\Event,
    Phalcon\Mvc\Dispatcher,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Services\Authentication\Exception as AuthenticationException,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UsersGroups,
    Sysclass\Models\Users\UserApiTokens,
    Sysclass\Models\Users\UserTimes;

class Adapter extends Component implements IAuthentication /* , EventsAwareInterface */
{
    /*
    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }
    */
    protected $userTime = null;
    protected $userToken = null;


    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
        // ALWAYS AUTHORIZE LOGIN CONTROLLER
        if (in_array($this->dispatcher->getControllerName(), array("login_controller", "api_controller"))) {
            return true;
        }

        // INJECT HERE SESSION AUTHORIZATION CODE
        try {
            // OLD CHECK STYLE
            $user = $this->authentication->checkAccess();


        } catch (AuthenticationException $e) {

            switch($e->getCode()) {
                case AuthenticationException :: MAINTENANCE_MODE : {
                    $message = $this->translate->translate("System is under maintenance mode. Please came back in a while.");
                    $message_type = 'warning';
                    break;
                }
                case AuthenticationException :: LOCKED_DOWN : {
                    $message = $this->translate->translate("The system was locked down by a administrator. Please came back in a while.");
                    $message_type = 'warning';
                    break;
                }
                case AuthenticationException :: NO_USER_LOGGED_IN : {
                    $message = $this->translate->translate("Your session appers to be expired. Please provide your credentials.");
                    $message_type = 'warning';
                    break;
                }
                case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
                    $action = "lockpage";
                    $message = $this->translate->translate("Your account is locked. Please provide your password to unlock.");
                    $message_type = 'info';
                    break;
                }
                case AuthenticationException :: API_TOKEN_TIMEOUT : {
                    $message = $this->translate->translate("Your token has expired. Please generate a new one");
                    $message_type = 'info';
                    $this->response->setJsonContent(array(
                        'error'         => true,
                        'message'       => $message,
                        'message_type'  => $message_type,
                    ));
                    return false;
                    break;
                }
                case AuthenticationException :: API_TOKEN_NOT_FOUND : {
                    $message = $this->translate->translate("Token invalid. Please generate a new one");
                    $message_type = 'danger';
                    $this->response->setJsonContent(array(
                        'error'         => true,
                        'message'       => $message,
                        'message_type'  => $message_type,
                    ));
                    return false;
                    break;
                }
                default : {
                    $message = $this->translate->translate($e->getMessage());
                    $message_type = 'danger';
                    break;
                }
            }

            $this->flashSession->message($message_type, $message);
            // TODO:  CHECK IF THE REQUEST WASN'T A JSON REQUEST
            //$this->redirect($url, $message, $message_type);
            //

            $dispatcher->forward(
                array(
                    'namespace'     => 'Sysclass\Controllers',
                    'controller'    => 'login_controller',
                    'action'        => !empty($action) ? $action : 'loginpage'
                )
            );
            return false;
            //
        }
        return TRUE;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
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
            if (array_key_exists('useSecretKey', $options) && $options['useSecretKey'] == TRUE) {
                $user->token = $this->registerToken($user);
            } else {
                $this->registerSession($user);
            }

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

    public function signup($info, $options = null)
    {
        $this->_eventsManager->fire("authentication:beforeSignup", $this);

        // LOGIN PROCESS
        // 1.1 Create the new login and a temporary pass
        // 1.2 Save the user
        // 1.3 Put him in default groups

        $backend = $this->getDefaultBackend();

        if ($this->configuration->get("signup_enable") != "1") {
            throw new AuthenticationException("USER_PUBLIC_SIGNUP_IS_FORBIDEN", AuthenticationException::USER_PUBLIC_SIGNUP_IS_FORBIDEN);
        }

        if ($backend) {
            try {
                // 1.1 Create the new login and a temporary pass
                if (!array_key_exists('disableBackends', $options) || $options['disableBackends'] == FALSE) {
                    if (($user = $backend->signup($info, $options)) === FALSE) {
                    }
                }
            } catch (AuthenticationException $e) {
                // JUST BY-PASS THE EXCEPTION
                throw new AuthenticationException($e->getMessage(), $e->getCode());
            }

            // CHECK FOR DEFAULT GROUP FOR USERS
            $default_group_id = $this->configuration->get("signup_group_default");

            //var_dump($default_group_id);
            //exit;

            if (is_numeric($default_group_id) && $default_group_id > 0) {
                $userGroup = new UsersGroups();
                $userGroup->user_id = $user->id;
                $userGroup->group_id = $default_group_id;
                $userGroup->save();
            }

            
            //$this->_eventsManager->collectResponses(true);
            $this->_eventsManager->fire("authentication:afterSignup", $this, $user);

            return $user;

        }
        throw new AuthenticationException("error", AuthenticationException::NO_BACKEND_DISPONIBLE);
        return false;
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
        $this->_eventsManager->fire("authentication:beforeCheckAccess", $this, $info);

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
            // TRY WITHOUT SESSION, JUST TOKEN
                        
                        //exit;

            if (is_null($info)) {
                $info = array(
                    'login' => $this->request->getServer('PHP_AUTH_USER'),
                    'secret_key' => $this->request->getServer('PHP_AUTH_PW'),
                    'token' => $this->request->getHeader('X-SC-TOKEN')
                );
            }

            if (
                array_key_exists('token', $info) && 
                !empty($info['login']) &&
                !empty($info['secret_key'])
            ) {
                $userToken = UserApiTokens::findFirst(array(
                    'conditions' => 'token = ?0 AND expired = 0',
                    'bind' => array($info['token'])
                ));

                if ($userToken) {
                    if (
                        ((time() - $userToken->started) > $this->environment->api->token_timeout) ||
                        ((time() - $userToken->ping) > $this->environment->api->ping_timeout)
                    ) {
                        $userToken->expired = 1;
                        $userToken->save();

                        throw new AuthenticationException("API_TOKEN_TIMEOUT", AuthenticationException::API_TOKEN_TIMEOUT);
                    }

                    $user = User::findFirst(array(
                        'conditions' => 'id = ?0 AND login = ?1',
                        'bind' => array($userToken->user_id, $info['login'])
                    ));

                    if (!$user) {
                        throw new AuthenticationException("API_TOKEN_INVALID", AuthenticationException::API_TOKEN_INVALID);
                    }

                    $backend = $this->getBackend($user);

                    if ($backend && $backend->checkSecretKey($info['secret_key'], $user)) {
                        $userToken->ping = time();
                        
                        $userToken->expires = min(
                            $userToken->started + $this->environment->api->token_timeout, 
                            $userToken->ping + $this->environment->api->ping_timeout
                        );

                        $userToken->save();

                        $this->userToken = $userToken;
                        return $user;
                    }
                } else {
                    throw new AuthenticationException("API_TOKEN_NOT_FOUND", AuthenticationException::API_TOKEN_NOT_FOUND);
                }
            }
            throw new AuthenticationException("NO_USER_LOGGED_IN", AuthenticationException::NO_USER_LOGGED_IN);
        }

        return false;
    }

    public function getUserToken() {
        return $this->userToken;
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

    protected function registerToken(User $user) {
        //$this->session()
        $token = $this->security->computeHmac(
            $user->login . "-" . microtime(true),
            $user->api_secret_key . $this->environment->api->hash,
            'sha256'
        );

        $userTimes = UserApiTokens::findFirstByToken($token);
        if ($userTimes) {
            $userTimes->delete();
        }

        $userTimes = new UserApiTokens();
        $userTimes->token = $token;
        $userTimes->user_id = $user->id;
        $userTimes->started = time();
        $userTimes->ping = time();
        $userTimes->save();

        //$this->session->set('session_index', $userTimes->id);

        return $token;
    }

    protected function registerSession(User $user) {
        //$this->session()
        $token = $this->crypt->encrypt($user->password, $this->environment->run->session_hash);

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
