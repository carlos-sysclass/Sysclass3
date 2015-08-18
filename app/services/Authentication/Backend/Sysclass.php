<?php
namespace Sysclass\Services\Authentication\Backend;

use Phalcon\Mvc\User\Component,
    Phalcon\Mvc\Model\Resultset,
    Sysclass\Services\Authentication\Interfaces\IAuthentication,
    Sysclass\Models\Users;

class Sysclass extends Component implements IAuthentication
{
    public function login($info, $options = null)
    {
        if ($info instanceof Users) {
            $user = $info;
            $password = @isset($options['password']) ? $options['password'] : null;
        } else {
            $user = Users::findFirstByLogin($info['login']);
            $password = $info['password'];
        }

        if (!is_null($password) && $this->security->checkHash($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function logout(Users $user) {
        // CALLED ON USER LOGOUT EXPLICIT REQUEST
        return TRUE;
    }

    public function checkAccess($info = null)
    {
        // CALLED ON USER REQUEST, TO CHECK SESSION "VALIDNESS"
        return true;
    }
}
