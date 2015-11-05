<?php
namespace Sysclass\Controllers;

use Phalcon\DI,
	Phalcon\Mvc\Dispatcher,
	Sysclass\Models\Users\User,
	Sysclass\Models\I18n\Language,
	Sysclass\Services\Authentication\Exception as AuthenticationException;

/**
 * @RoutePrefix("/api")
 */
class ApiController extends \AbstractSysclassController
{
    /**
     * Generates a new Token for API Access
     * @Get("/")
     * @Get("/token")
     * 
     */
	public function tokenRequest($reset)
	{
		//$userHash = "44adcd9fcb0b3f7c74fdd6bc860f0f7c5803be49c7bfb3e695ba519e5ca66c37";

		$this->response->setContentType('application/json', 'UTF-8');

		try {
			$user = $this->request->getServer('PHP_AUTH_USER');
			$secret_key = $this->request->getServer('PHP_AUTH_PW');

			if (empty($user) || empty($secret_key)) {
				throw new \Exception("NO_DATA_SENT", 9000);
			}

			$user = $this->authentication->login(
				array(
					'login' => $user,
					'secret_key' => $secret_key
				), array(
					'useSecretKey' => true
				)
			);

			$this->response->setJsonContent(array(
				'error' 		=> false,
				'message' 		=> "Access Granted.",
				'token'			=> $user->token,
			));

			return true;
		} catch (AuthenticationException $e) {
			$url = null;
			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
		            $message = $this->translate->translate("The system can't authenticate you using the current methods. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}

				case AuthenticationException :: MAINTENANCE_MODE : {

		            $message = $this->translate->translate("System is under maintenance mode. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
		            $message = $this->translate->translate("Username and password are incorrect. Please make sure you typed correctly.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = $this->translate->translate("The system was locked down by a administrator. Please came back in a while.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
					$url = "/lock";
		            $message = $this->translate->translate("Your account is locked. Please provide your password to unlock.");
		            $message_type = 'info';
		            break;
				}
				default : {
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}

			//RETURN THE CORRECT JSON MESSAGE
			//
		} catch (\Exception $e) {
            $message = "Welcome to Sysclass API. Please provide your access details to continue.";
            $message_type = 'info';
		}

		$this->response->setJsonContent(array(
			'error' 		=> true,
			'message' 		=> $message,
			'message_type' 	=> $message_type,
		));

		return false;

	}

	public function beforeExecuteRoute(Dispatcher $dispatcher) {
		$this->response->setContentType('application/json', 'UTF-8');

		if ($dispatcher->getActionName() == "tokenrequest") {
			return true;
		}
		//$userHash = "44adcd9fcb0b3f7c74fdd6bc860f0f7c5803be49c7bfb3e695ba519e5ca66c37";
		

		try {
			$user = $this->request->getServer('PHP_AUTH_USER');
			$secret_key = $this->request->getServer('PHP_AUTH_PW');
			$token = $this->request->getHeader('X-SC-HEADER');

			//$user = $this->user;
			$user = $this->authentication->checkAccess();

			return true;
		} catch (AuthenticationException $e) {
			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
		            $message = "The system can't authenticate you using the current methods. Please came back in a while.";
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: MAINTENANCE_MODE : {

		            $message = "System is under maintenance mode. Please came back in a while.";
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
		            $message = "Username and password are incorrect. Please make sure you typed correctly.";
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = "The system was locked down by a administrator. Please came back in a while.";
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
		            $message = "Your account is locked. Please provide your password to unlock.";
		            $message_type = 'info';
		            break;
				}
                case AuthenticationException :: API_TOKEN_TIMEOUT : {
                    $message = "Your token has expired. Please generate a new one";
                    $message_type = 'info';
                    break;
                }
                case AuthenticationException :: API_TOKEN_NOT_FOUND : {
                    $message = "This token is invalid. Please generate a new one";
                    $message_type = 'info';
                    break;
                }
				default : {
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}

			//RETURN THE CORRECT JSON MESSAGE
			//
			$this->response->setJsonContent(array(
				'error' 		=> true,
				'message' 		=> $message,
				'message_type' 	=> $message_type,
			));

			return false;
		}
	}

    /**
     * Just Ping!! (Authentication Test)
     * @Get("/ping")
     * 
     */
	public function pingRequest()
	{
		$token = $this->authentication->getUserToken();
		//var_dump($token);
		$this->response->setJsonContent(array(
			'error' 		=> false,
			'message' 		=> "Your token is valid.",
			'token'			=> $token->token,
			'now'			=> date('c', time()),
			'started'		=> date('c', $token->started),
			'valid_until'	=> date('c', $token->expires)
		));

	}

	// ENTRY POINT FOR ENROLLMENT
    /**
     * Just Ping!! (Authentication Test)
     * @Post("/enroll")
     * 
     */
	public function enrollRequest() {
		$this->response->setJsonContent($this->request->getPost());
		var_dump($this->user->toArray());
		//exit;
	}

}
