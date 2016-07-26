<?php
namespace Sysclass\Services\Authentication\Backend;

use Phalcon\Mvc\User\Component,
    Phalcon\Mvc\Model\Resultset,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UserApiTokens,
    Sysclass\Services\Authentication\Exception as AuthenticationException;

class Sysclass extends Component implements IAuthentication
{
    public function login($info, $options = null)
    {
        $options = is_null($options) ? array() : $options;

        if ($info instanceof User) {
            $user = $info;
            $password = @isset($options['password']) ? $options['password'] : null;
            $secret_key = @isset($options['secret_key']) ? $options['secret_key'] : null;
        } else {
            if (array_key_exists('login', $info)) {
                if (array_key_exists('isEmail', $options) && $options['isEmail']) {
                    $user = User::findFirstByEmail($info['login']);
                } else {
                    $user = User::findFirstByLogin($info['login']);
                }
            } elseif (array_key_exists('id', $info)) {
                $user = User::findFirstById($info['id']);
            }
            $password = @isset($info['password']) ? $info['password'] : null;
            $secret_key = @isset($info['secret_key']) ? $info['secret_key'] : null;
            $websocket_key = @isset($info['websocket_key']) ? $info['websocket_key'] : null;
        }
        
        if (array_key_exists('useSecretKey', $options) && $options['useSecretKey'] == TRUE && $this->checkSecretKey($secret_key, $user)) {
            return $user;
        } elseif (array_key_exists('useWebsocketKey', $options) && $options['useWebsocketKey'] == TRUE && $this->checkWebsocketKey($websocket_key, $user)) {
            return $user;
        } elseif ($this->checkPassword($password, $user)) {
            return $user;
        }
        return false;
    }

    public function checkPassword($password, User $user = null)
    {
        return (!is_null($password) && $this->security->checkHash($password, $user->password));
    }

    public function checkSecretKey($secret_key, User $user = null)
    {
        return (!is_null($secret_key) && $this->security->checkHash($secret_key, $user->api_secret_key));
    }

    public function checkWebsocketKey($websocket_key, User $user = null)
    {
        return (!is_null($websocket_key) && $this->security->checkHash($websocket_key, $user->websocket_key));
    }
    

    public function signup($info, $options = null)
    {
        if ($info instanceof User) {
            $user = $info;
        } else {
            $user = new User();
            $user->assign($info);
        }

        // OPTIONS NOT PUT IN SYSTEM ALREADY
        //if ($this->configuration->get("block_multiple_signup_for_same_email")) {
            $exists = User::count(array(
                'conditions' => "email = ?0",
                'bind' => array($user->email)
            ));

            if ($exists > 0) {
                throw new AuthenticationException("SIGNUP_EMAIL_ALREADY_EXISTS", AuthenticationException::SIGNUP_EMAIL_ALREADY_EXISTS);
                return false;
            }
        //}

        /*
        if (empty($user->login)) {
            $user->login = $user->createNewLogin();
        }

        if (empty($user->passwd)) {
            $password = $user->createRandomPass();
            // ENCRYPT PASS
            $user->password = $this->hashPassword($password, $user);
        }
        */
        $user->language_id = $this->configuration->get("default_user_language");

        if ($this->configuration->get("signup_must_accept_license")) {
            $user->viewed_license = 0;
        } else {
            $user->viewed_license = 1;
        }
        if ($this->configuration->get("signup_must_approve")) {
            $user->pending = 1;
        } else {
            $user->pending = 0;
        }

        if (!$user->save()) {
            throw new AuthenticationException("USER_DATA_IS_INVALID_OR_INCOMPLETE", AuthenticationException::USER_DATA_IS_INVALID_OR_INCOMPLETE);
        }

        return $user;
    }


    public function hashPassword($password, User $user = null)
    {
        return $this->security->hash($password);
    }



    public function logout(User $user) {
        // CALLED ON USER LOGOUT EXPLICIT REQUEST
        return TRUE;
    }

    public function checkAccess($info = null)
    {
        // CALLED ON USER REQUEST, TO CHECK SESSION "VALIDNESS"
        return true;
    }
}
