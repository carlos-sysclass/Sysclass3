<?php
namespace Sysclass\Controllers;

use Phalcon\DI,
	Phalcon\Mvc\Dispatcher,
	Sysclass\Models\Users\User,
	Sysclass\Models\Courses\Course,
	Sysclass\Models\Enrollments\Course as Enrollment,
	Sysclass\Models\Enrollments\Enroll,
	Sysclass\Models\I18n\Language,
	Sysclass\Services\Authentication\Exception as AuthenticationException;

/**
 * @RoutePrefix("/api")
 */
class ApiController extends \AbstractSysclassController
{
	const INVALID_DATA = "Your data sent is invalid. Please try again.";
	const NO_DATA_FOUND = "Sorry, no data found!";
	const EXECUTION_OK = "Method execute successfully.";
	

	public function beforeExecuteRoute(Dispatcher $dispatcher) {
		$this->response->setContentType('application/json', 'UTF-8');

		if ($dispatcher->getActionName() == "tokenrequest") {
			return true;
		}
		//$userHash = "44adcd9fcb0b3f7c74fdd6bc860f0f7c5803be49c7bfb3e695ba519e5ca66c37";
		

		try {
			/*
			*/
			//$user = $this->user;
			$user = $this->authentication->checkAccess();

			return true;
		} catch (AuthenticationException $e) {
			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
					$code = 403;
		            $message = "The system can't authenticate you using the current methods. Please came back in a while.";
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: MAINTENANCE_MODE : {
					$code = 403;
		            $message = "System is under maintenance mode. Please came back in a while.";
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
					$code = 403;
		            $message = "Username and password are incorrect. Please make sure you typed correctly.";
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
					$code = 403;
		            $message = "The system was locked down by a administrator. Please came back in a while.";
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
					$code = 403;
		            $message = "Your account is locked. Please provide your password to unlock.";
		            $message_type = 'info';
		            break;
				}
                case AuthenticationException :: API_TOKEN_TIMEOUT : {
                	$code = 403;
                    $message = "Your token has expired. Please generate a new one";
                    $message_type = 'info';
                    break;
                }
                case AuthenticationException :: API_TOKEN_NOT_FOUND : {
                	$code = 403;
                    $message = "This token is invalid. Please generate a new one";
                    $message_type = 'info';
                    break;
                }
				default : {
					$code = 403;
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}

			//RETURN THE CORRECT JSON MESSAGE
			//
			$this->response->setJsonContent(
				$this->createResponse($code, $message, $message_type)
			);

			$this->response->setJsonContent(array(
				'error' 		=> true,
				'message' 		=> $message,
				'message_type' 	=> $message_type,
			));

			return false;
		}
	}
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
					$code = 403;
		            $message = $this->translate->translate("The system can't authenticate you using the current methods. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}

				case AuthenticationException :: MAINTENANCE_MODE : {
					$code = 403;
		            $message = $this->translate->translate("System is under maintenance mode. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
					$code = 403;
		            $message = $this->translate->translate("Username and password are incorrect. Please make sure you typed correctly.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
					$code = 403;
		            $message = $this->translate->translate("The system was locked down by a administrator. Please came back in a while.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
					$code = 403;
		            $message = $this->translate->translate("Your account is locked. Please provide your password to unlock.");
		            $message_type = 'info';
		            break;
				}
				default : {
					$code = 403;
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}

			//RETURN THE CORRECT JSON MESSAGE
			//
		} catch (\Exception $e) {
			$code = 200;
            $message = "Welcome to Sysclass API. Please provide your access details to continue.";
            $message_type = 'info';
		}

		$this->response->setJsonContent(
			$this->createResponse($code, $message, $message_type)
		);

		return false;

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
		$postdata = $this->request->getJsonRawBody(true);

		if (is_null($postdata)) {
			$this->response->setJsonContent($this->invalidRequestError(self::INVALID_DATA, "warning"));
		} else {
			/*
				CHANGE SysclassModule Default Add/Edit/Delete Methods, 
				to allow parameters to be passed by arguments (it's now getting from GET/POST/PUT data)
				Maybe it's better to create a method to receive, or move the validation to model (the correct way!!)
			 */
			if (array_key_exists("user", $postdata)) {
				// SIGNUP USER
				$user = $this->authentication->signup($postdata['user']);
				$user->refresh();
			} elseif (array_key_exists("user_id", $postdata)) {
				$user = User::findFirstById($postdata['user_id']);
			}

			if (!$user) {
				$this->response->setJsonContent($this->invalidRequestError(self::INVALID_DATA, "warning"));
			} else {
				// USER IS UP AND DEFINED
				if (
					!array_key_exists("course_id", $postdata) ||
					!($course = Course::findFirstById($postdata['course_id']))
				) {
					$this->response->setJsonContent($this->invalidRequestError(self::INVALID_DATA, "warning"));
				} else {
					$enrollment = new Enrollment();

					$enrollment->assign(array(
						'user_id' => $user->id,
						'course_id' => $course->id
					));

					if (!$enrollment->save()) {
						$message = reset($enrollment->getMessages());

						$this->response->setJsonContent(
							$this->createResponse(412, $message->getMessage(), $message->getType())
						);
					} else {
						$enrollment->refresh();

						$this->response->setJsonContent(
							$this->createResponse(200, "Used Enrolled successfully.", "success")
						);
					}
				}
			}
		}
	}

    /**
     * Api Method to get enrollment info
     * @Get("/enroll/info/{identifier}")
     * 
     */
	public function enrollInfoRequest($identifier) {
		//if (filter_var($identifier, FILTER_VALIDATE_)) {
			$enroll = Enroll::findFirstByIdentifier($identifier);

			if (!$enroll) {
				$this->response->setJsonContent($this->invalidRequestError(self::NO_DATA_FOUND, "warning"));
			} else {
				$data = $enroll->toExtendArray(array('fields' => 'EnrollFields'));

				$this->response->setJsonContent(array(
					'status' => $this->createResponse(200, self::EXECUTION_OK, "success"),
					'data' => $data
				));
			}
		//} else {
		//	$this->response->setJsonContent($this->invalidRequestError(self::INVALID_DATA, "warning"));
		//}

	}

	// RequestManager
	protected function createResponse($code, $message, $type, $intent = null, $callback = null)
	{
		http_response_code($code);
		$error = array(
			"code" 		=> $code,
			"message"	=> $message,
			"type"		=> $type
		);
		if (!is_null($callback)) {
			$error['data'] = $callback;
		}
		return $error;
	}

	protected function invalidRequestError($message = "", $type = "warning") {
		if (empty($message)) {
			$message = $this->translate->translate("There's a problem with your request. Please try again.");
		}
		return $this->createResponse(400, $message, $type, "advise");
	}

}
