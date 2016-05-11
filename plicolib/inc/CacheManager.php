<?php
/**
 * @package PlicoLib\Managers
 */
abstract class CacheManager extends LoaderManager {

	protected function getSessionToken()
	{
		@session_start();
		if (array_key_exists('token', $_SESSION)) {
			return $_SESSION['token'];
		}
		return null;
	}

	protected function setSessionToken($token)
	{
		@session_start();
		$_SESSION['token'] = $token;

	}

	protected function clearSessionToken() {
		@session_start();
		unset($_SESSION['token']);

	}

	protected function clearCache($name)
	{
		if ($this->hasCache($name)) {
			$token = $this->getSessionToken();
			$return = $_SESSION[$token][$name];
			unset($_SESSION[$token][$name]);
			return $return;
		}
		return null;

	}

	protected function hasCache($name)
	{
		$token = $this->getSessionToken();
		if (array_key_exists($token, $_SESSION) && is_array($_SESSION[$token]) && array_key_exists($name, $_SESSION[$token])) {
			return true;
		}
		return false;

	}

	protected function getCache($name)
	{
		if ($this->hasCache($name)) {
			$token = $this->getSessionToken();
			return $_SESSION[$token][$name];
		}
		return NULL;

	}

	public function setCache($name, $value)
	{
		// TODO MANAGE CACHE EXPIRATION
		$token = $this->getSessionToken();
		if (!array_key_exists($token, $_SESSION) || !is_array($_SESSION[$token])) {
			$_SESSION[$token] = array();
		}
		$_SESSION[$token][$name] = $value;

		return $value;

	}

	protected function createToken($random, $timestamp=NULL, $salt=NULL, $method='md5')
	{
		if (is_null($timestamp)) {
			$timestamp = time();
		}

		if (is_null($salt)) {
			$salt = mt_rand();
		}

		$seed = $random . $timestamp;

		switch($method) {
			case 'sha1':{
				$token = sha1(md5($seed) . $salt);
				break;

			}
			case 'md5':
			default:{
				$token = md5(sha1($seed) . $salt);
				break;

			}
		}

		return $token;

	}

}
