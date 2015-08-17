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
        $user = Users::findFirstByLogin($info['login']);

        if ($this->security->checkHash($info['password'], $user->password)) {
            return $user;
        }
        return false;
    }

    public function checkAccess($info = null)
    {
        // CALLED ON USER REQUEST, TO CHECK SESSION "VALIDNESS"
        return true;
    }
}
