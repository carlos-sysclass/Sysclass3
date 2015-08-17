<?php
namespace Sysclass\Services\Authentication\Interfaces;

interface IAuthentication {
    /**
     * Try to execute the user login,
     * @param  array $info    Array containg signin information (backend dependent)
     * @param  array $options Array containg additional info (backend dependent)
     * @return boolean|object   FALSE ON FAILURE, OR THE USER OBJCT ON SUCESS
     */
    public function login($info, $options = null);

    /**
     * [ping description]
     * @param  array $info  Array containg signin / session information (backend dependent)
     * @return boolean      True on session ok, false when the session expires or can not be continued.
     */
    public function checkAccess($info = null);

}
