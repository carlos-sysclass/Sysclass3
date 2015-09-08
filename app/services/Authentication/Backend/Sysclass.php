<?php
namespace Sysclass\Services\Authentication\Backend;

use Phalcon\Mvc\User\Component,
    Phalcon\Mvc\Model\Resultset,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Models\Users\User;

class Sysclass extends Component implements IAuthentication
{
    public function login($info, $options = null)
    {
        if ($info instanceof User) {
            $user = $info;
            $password = @isset($options['password']) ? $options['password'] : null;
        } else {
            $user = User::findFirstByLogin($info['login']);
            $password = $info['password'];
        }

        if ($this->checkPassword($password, $user)) {
            return $user;
        }
        return false;
    }

    public function checkPassword($password, $user) {
        return (!is_null($password) && $this->security->checkHash($password, $user->password));
    }
    public function hashPassword($password) {
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
