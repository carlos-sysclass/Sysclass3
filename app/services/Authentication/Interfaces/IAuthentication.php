<?php
namespace Sysclass\Services\Authentication\Interfaces;

use Sysclass\Models\Users\User;

interface IAuthentication {
    /**
     * Try to execute the user login,
     * @param  array $info    Array containg signin information (backend dependent)
     * @param  array $options Array containg additional info (backend dependent)
     * @return boolean|object   FALSE ON FAILURE, OR THE USER OBJCT ON SUCESS
     */
    public function login($info, $options = null);

    /**
     * Check if user is already logged in
     * @param  array $info  Array containg signin / session information (backend dependent)
     * @return boolean      True on session ok, false when the session expires or can not be continued.
     */
    public function checkAccess($info = null);

    /**
     * [checkPassword description]
     * @param  [type]    $password [description]
     * @param  User|null $user     [description]
     * @return [type]              [description]
     */
    public function checkPassword($password, User $user = null);

    /**
     * [hashPassword description]
     * @param  [type]    $password [description]
     * @param  User|null $user     [description]
     * @return [type]              [description]
     */
    public function hashPassword($password, User $user = null);
}
