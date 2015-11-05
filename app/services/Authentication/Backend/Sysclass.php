<?php
namespace Sysclass\Services\Authentication\Backend;

use Phalcon\Mvc\User\Component,
    Phalcon\Mvc\Model\Resultset,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Models\Users\User,
    Sysclass\Models\Users\UserApiTokens;

class Sysclass extends Component implements IAuthentication
{
    public function login($info, $options = null)
    {
        if ($info instanceof User) {
            $user = $info;
            $password = @isset($options['password']) ? $options['password'] : null;
            $secret_key = @isset($options['secret_key']) ? $options['secret_key'] : null;
        } else {
            $user = User::findFirstByLogin($info['login']);
            $password = $info['password'];
            $secret_key = $info['secret_key'];
        }
        
        if (array_key_exists('useSecretKey', $options) && $options['useSecretKey'] == TRUE && $this->checkSecretKey($secret_key, $user)) {
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

    public function signup($info, $options = null)
    {
        if ($info instanceof User) {
            $user = $info;
        } else {
            $user = new User();
            $user->assign($info);
        }

        if (empty($user->login)) {
            $user->login = $user->createNewLogin();
        }

        if (empty($user->passwd)) {
            $password = $user->createRandomPass();
            // ENCRYPT PASS
            $user->password = $this->hashPassword($password, $user);
        }

        $user->viewed_license = 0;

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
