<?php
namespace Sysclass\Controllers;

use Phalcon\DI,
	Phalcon\Mvc\Dispatcher,
	Sysclass\Models\Users\User,
	Sysclass\Models\Courses\Course,
	Sysclass\Models\Enrollments\CourseUsers as Enrollment,
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
	public function addEnrollRequest() {
		$postdata = $this->request->getJsonRawBody(true);

		$error = false;

		$messages = $data = array();

		try {
			if (is_null($postdata)) {
				$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
			} else {

				$enroll = Enroll::findFirstByIdentifier($postdata['_package_id']);
				
				if($enroll) {
	 				$check = $enroll->isAllowed();
	 				if (!$check['error']) {
	 					// CREATE TRANSACTION
	 					$this->db->begin();

						$user = $this->authentication->signup($postdata);

	 					if ($user) {
	 						$user->refresh();
	 						$messages[] = $this->createResponse(200, "User created.", "success");

	 						$data['user'] = array(
	 							'id' => $user->id,
	 							'name' => $user->name,
	 							'surname' => $user->surname,
	 							'email' => $user->email,
	 							'login' => $user->login
	 						);

	 						if (count($postdata['courses']) > 0) {
	 							$data['courses'] = array();
		 						foreach($postdata['courses'] as $course_id) {
									$course = Course::findFirstById($course_id);
									if ($course) {
										$result = $enroll->enrollUser($user, $course);

										if (count($result) == 0) {
											$messages[] = $this->createResponse(200, "User enrolled in Course #{$course->id} {$course->name}.", "success");

											$data['courses'][] = array(
												'id' => $course->id,
												'name' => $course->name
											);
										} else {

											$messages[] = $this->createResponse(400, "The system can't enroll in the course at the moment. PLease try again", "error");
											$error = true;
											break;
										}
									} else {
										$messages[] = $this->createResponse(400, "Course does not exists!", "error");
										$error = true;
									}
								}
							} else {
								$messages[] = $this->createResponse(400, "Please select at least one course to enroll.", "error");
								$error = true;
							}

	 					} else {
							$messages[] = $this->createResponse(400, $this->translate->translate("Your data sent appers to be imcomplete. Please check your info and try again!"), "error");
							$error = true;
	 					}
	 				} else {
	 					$messages[] = $this->invalidRequestError($check['reason'], "warning");
	 					$error = true;
	 				}
				} else {
					// ENROLL DOES NOT EXISTS
					$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
					$error = true;
				}

				if ($error) {
					// ROLLBACK TRANSACTION
					$this->db->rollback();
				} else {
					$this->db->commit();

					// PUBLISH SYSTEM EVENT FOR ENROLLMENT
					$this->eventsManager->fire("user:signup", $this, $user->toArray());
				}
			}

		} catch (AuthenticationException $e) {
			$error = true;
			switch($e->getCode()) {
				case AuthenticationException :: SIGNUP_EMAIL_ALREADY_EXISTS: {
					$messages[] = $this->createResponse($e->getCode(), $this->translate->translate("There is already a registration made with this email! Would you like to login?"), "error");
		            break;
				}
				case AuthenticationException :: USER_DATA_IS_INVALID_OR_INCOMPLETE : {
		            $messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
		            break;
				}
				default : {
					$messages[] = $this->invalidRequestError($this->translate->translate($e->getMessage()), "warning");
		            break;
				}
			}
		}

		$this->response->setJsonContent(array(
			'messages' => $messages,
			'error' => $error,
			'data' => $data
		));

		return true;

	}

    /**
     * Api Method to get enrollment info
     * @Get("/enroll")
     * 
     */
	public function getEnrollRequest($identifier) {
		$identifier = $this->request->getQuery("identifier");

		$locale = $this->request->getQuery("locale");

		$language = \Locale::getPrimaryLanguage($locale);

		// CHECK IF $locale EXISTS and translate accordinaly
		$this->translate->setSource($language);

		//$this->response->setJsonContent($language);

		//return true;

		//if (filter_var($identifier, FILTER_VALIDATE_)) {
			$enroll = Enroll::findFirstByIdentifier($identifier);

			if (!$enroll) {
				$this->response->setJsonContent(array(
					'status' => $this->invalidRequestError(self::NO_DATA_FOUND, "warning")
				));
			} else {
				$data = $enroll->toExtendArray(["courses"]);
				//$data = $enroll->toArray();

				//echo ($data);
				//exit;
				
				$courses = $enroll->getCourses([
					'conditions' => 'signup_active = 1 AND signup_enable_new_users = 1'
				]);

				$data['courses'] = array();
				foreach($courses as $course) {
					$data['courses'][] = $course->toExtendArray();
				}
				

				$fields = $enroll->getEnrollFields(array(
					'order' => 'position'
				));
				$data['fields'] = array();

				foreach($fields as $field) {
					//print_r($field->toFullArray());
					$field->translate();

					$data['fields'][] = $field->toFullArray();
				}

				$data['labels'] = [
					'enroll_action' => $this->translate->translate("Enroll Now"),
					'already_has_account' => $this->translate->translate("Already has a account? Click Here."),
					'choose_program' => $this->translate->translate("Choose your program."),
					'accept_the' => $this->translate->translate("Accept the"),
					'use_terms' => $this->translate->translate("Use Terms"),
					/**
					  * @todo Inject this info inside the enrollment page
					 */

					'form_title' => $enroll->name,
					'form_subtitle' => $enroll->subtitle,
					'confirmation_text' => $this->translate->translate("<p>Your registration has been received successfully. In a few minutes you will receive a confirmation email containing a link to continue your registration.</p><p>In case you haven't received the confirmation email, check your Junk folder. If you still do not receive your email, please return to this page, and ask to have them emailed.</p>", null, "pt")
				];

				//$data = $enroll->toExtendArray(array('fields' => 'EnrollFields'));
				
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
