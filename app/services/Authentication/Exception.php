<?php
namespace Sysclass\Services\Authentication;

class Exception extends \Phalcon\Exception
{
    const NO_ERROR = 0;
    const NO_BACKEND_DISPONIBLE = 900;
    const MAINTENANCE_MODE = 901;
    const INVALID_USERNAME_OR_PASSWORD = 902;
    const LOCKED_DOWN = 903;
    const NO_USER_LOGGED_IN = 905;
    const CANT_LOGOUT_RIGHT_NOW = 906;

    /*
    const INVALID_LOGIN = 401;
    const USER_NOT_EXISTS = 402;
    const INVALID_PARAMETER = 403;
    const USER_EXISTS = 404;
    const DATABASE_ERROR = 405;
    const USER_FILESYSTEM_ERROR = 406;
    const INVALID_TYPE = 407;
    const ALREADY_IN = 408;
    const INVALID_PASSWORD = 409;
    const USER_NOT_HAVE_LESSON = 410;
    const WRONG_INPUT_TYPE = 411;
    const USER_PENDING = 412;
    const TYPE_NOT_EXISTS = 414;
    const MAXIMUM_REACHED = 415;
    const RESTRICTED_USER_TYPE = 416;
    const USER_INACTIVE = 417;
    const USER_NOT_LOGGED_IN = 418;
    const GENERAL_ERROR = 499;
    */
}
