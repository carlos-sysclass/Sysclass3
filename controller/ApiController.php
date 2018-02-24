<?php
namespace Sysclass\Controllers;

use Phalcon\Mvc\Dispatcher;
use Sysclass\Collections\Requests\Entry as RequestEntry;
use Sysclass\Models\Content\Program as Course;
use Sysclass\Models\Enrollments\CourseUsers as Enrollment;
use Sysclass\Models\Enrollments\Enroll;
use Sysclass\Models\Leads\Lead;
use Sysclass\Models\Users\UserAttrs;
use Sysclass\Services\Authentication\Exception as AuthenticationException;

/**
 * @RoutePrefix("/api")
 */
class ApiController extends \AbstractSysclassController {
	const INVALID_DATA = "The data sent is invalid. Please, try again.";
	const NO_DATA_FOUND = "No data found.";
	const EXECUTION_OK = "Method executed.";
	const INVALID_AGE = "To enroll in any of our programs you must be 14 years old, or older.";
	const INVALID_ENROLL_MASTER = "To enroll in the Master program you must have completed High School or Secondary School and have complete College or a Higher Education program.";
	const INVALID_ENROLL_ASSOCIATE = "To enroll in the Associate program you must have completed High School or Secondary School.";
	

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
			switch ($e->getCode()) {
			case AuthenticationException::NO_BACKEND_DISPONIBLE:{
					$code = 403;
					$message = "Incorrect username or password. To reset your password click bellow on FORGOT YOUR PASSWORD.";
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::MAINTENANCE_MODE:{
					$code = 403;
					$message = "System under maintenance.";
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::INVALID_USERNAME_OR_PASSWORD:{
					$code = 403;
					$message = "Username and password are incorrect. Please, make sure you typed correctly.";
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::LOCKED_DOWN:{
					$code = 403;
					$message = "Access not available. Please, contact the system administrator.";
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::USER_ACCOUNT_IS_LOCKED:{
					$code = 403;
					$message = "Please, check if the info provided is correct, and re-enter the username and password.";
					$message_type = 'info';
					break;
				}
			case AuthenticationException::API_TOKEN_TIMEOUT:{
					$code = 403;
					$message = "Invalid token. Please, re-enter token.";
					$message_type = 'info';
					break;
				}
			case AuthenticationException::API_TOKEN_NOT_FOUND:{
					$code = 403;
					$message = "Token invalid. Please, enter a new token.";
					$message_type = 'info';
					break;
				}
			default:{
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
				'error' => true,
				'message' => $message,
				'message_type' => $message_type,
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
	public function tokenRequest($reset) {
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
					'secret_key' => $secret_key,
				), array(
					'useSecretKey' => true,
				)
			);

			$this->response->setJsonContent(array(
				'error' => false,
				'message' => "Access Granted.",
				'token' => $user->token,
			));

			return true;
		} catch (AuthenticationException $e) {
			$url = null;
			switch ($e->getCode()) {
			case AuthenticationException::NO_BACKEND_DISPONIBLE:{
					$code = 403;
					$message = $this->translate->translate("Incorrect username or password. To reset your password click bellow on FORGOT YOUR PASSWORD.");
					$message_type = 'warning';
					break;
				}

			case AuthenticationException::MAINTENANCE_MODE:{
					$code = 403;
					$message = $this->translate->translate("System under maintenance.");
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::INVALID_USERNAME_OR_PASSWORD:{
					$code = 403;
					$message = $this->translate->translate("Username and password are incorrect. Please, make sure you typed correctly.");
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::LOCKED_DOWN:{
					$code = 403;
					$message = $this->translate->translate("Access not available. Please, contact the system administrator.");
					$message_type = 'warning';
					break;
				}
			case AuthenticationException::USER_ACCOUNT_IS_LOCKED:{
					$code = 403;
					$message = $this->translate->translate("Please, check if the info provided is correct, and re-enter the username and password.");
					$message_type = 'info';
					break;
				}
			default:{
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
			$message = "Welcome to SysClass API. Please, provide your access info to continue.";
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
	public function pingRequest() {
		$token = $this->authentication->getUserToken();
		//var_dump($token);
		$this->response->setJsonContent(array(
			'error' => false,
			'message' => "Your token is valid.",
			'token' => $token->token,
			'now' => date('c', time()),
			'started' => date('c', $token->started),
			'valid_until' => date('c', $token->expires),
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

		$request = new RequestEntry();
		$request->postdata = $postdata;
		$request->save();

		$error = false;

		$messages = $data = array();

		try {
			
			//Se o aluno marcar o campo Communication in English como Native Speaker ou o campo I want to enroll in the program como Certificate, 
			//não será necessário fazer o TEC. O aluno deverá ser direcionado para a tela de pagamento.
			
			
			if (is_null($postdata)) {
				$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
				$error = true;
			}else if( !$this->validAge($postdata['birthday']) ){
				$messages[] = $this->invalidRequestError(self::INVALID_AGE, "warning");
				$error = true;
			}else if( $postdata['secondary_school'] != 'Completed' && $postdata['courses'] == 9 ){
				$messages[] = $this->invalidRequestError(self::INVALID_ENROLL_ASSOCIATE, "warning");
				$error = true;
			}else if( ($postdata['secondary_school'] != 'Completed' || $postdata['higher_school'] != 'Completed') && $postdata['courses'] == 10 ){
				$messages[] = $this->invalidRequestError(self::INVALID_ENROLL_MASTER, "warning");
				$error = true;
			}else {
				$this->db->begin();

				$enroll = Enroll::findFirstByIdentifier($postdata['_package_id']);

				if ($enroll) {
					$check = $enroll->isAllowed();
					if (!$check['error']) {
						// CREATE TRANSACTION

						$user = $this->authentication->signup($postdata);

						if ($user) {
							$user->refresh();

							// REMOVE ALL POST DATA ALREADY ON USER MODEL
							$attrs = [];
							foreach ($postdata as $key => $value) {
								if (!$user->hasAttribute($key)) {
									$attrs[$key] = $value;
								}
							}
							unset($attrs['_package_id']);
							foreach ($attrs as $key => $value) {
								$userAttrs = new UserAttrs();
								$userAttrs->user_id = $user->id;
								$userAttrs->field_name = $key;
								if (is_array($value)) {
									$userAttrs->field_value = json_encode($value);
								} else {
									$userAttrs->field_value = $value;
								}
								$userAttrs->save();
							}

							$messages[] = $this->createResponse(200, "User created.", "success");

							$data['user'] = array(
								'id' => $user->id,
								'name' => $user->name,
								'surname' => $user->surname,
								'email' => $user->email,
								'login' => $user->login,
							);

							if (!empty($postdata['courses']) && is_numeric($postdata['courses'])) {
								$postdata['courses'] = [$postdata['courses']];
							}

							if (count($postdata['courses']) > 0) {
								$data['courses'] = array();
								foreach ($postdata['courses'] as $course_id) {
									$course = Course::findFirstById($course_id);
									if ($course) {
										$result = $enroll->enrollUser($user, $course);

										if (count($result) == 0) {
											$messages[] = $this->createResponse(200, "User enrolled in Course #{$course->id} {$course->name}.", "success");

											$data['courses'][] = array(
												'id' => $course->id,
												'name' => $course->name,
											);
										} else {
											// REMOVE THE USER
											$messages[] = $this->createResponse(400, "The system can't enroll in the course at the moment. PLease try again", "error");
											$error = true;
											break;
										}
									} else {
										// REMOVE THE USER
										$messages[] = $this->createResponse(400, "Course does not exists!", "error");
										$error = true;
									}
								}
							} else {
								// CHECK IF THE CONFIGURATION ALLOWS THE USER TO ENTER THE SYSTEM WITHOUT A COURSE
								if ($this->configuration->get("signup_require_program")) {
									$messages[] = $this->createResponse(400, "Please, select at least one course to enroll.", "error");
									$error = true;
								} else {
									// USER CAN PROCEED WITHOUT A COURSE
								}
							}
						} else {
							$messages[] = $this->createResponse(400, $this->translate->translate("Your data sent appers to be imcomplete. Please, check your info and try again!"), "error");
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
			switch ($e->getCode()) {
			case AuthenticationException::SIGNUP_EMAIL_ALREADY_EXISTS:{
					$messages[] = $this->invalidRequestError("There is already a registration with this email. Would you like to login", "warning");
					break;
				}
			case AuthenticationException::USER_DATA_IS_INVALID_OR_INCOMPLETE:{
					$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
					break;
				}
			default:{
					$messages[] = $this->invalidRequestError($this->translate->translate($e->getMessage()), "warning");
					break;
				}
			}
		}

		$request->post_result = array(
			'messages' => $messages,
			'error' => $error,
			'data' => $data,
		);

		$request->save();

		$this->response->setJsonContent(array(
			'messages' => $messages,
			'error' => $error,
			'data' => $data,
		));

		return true;

	}

	// ENTRY POINT FOR ENROLLMENT
	/**
	 * Just Ping!! (Authentication Test)
	 * @Post("/lead/create")
	 *
	 */
	public function createLeadRequest() {
		$postdata = $this->request->getJsonRawBody(true);

		$error = false;

		$messages = $data = array();

		try {
			if (is_null($postdata)) {
				$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
			} else {

				// GET LEADS FIELDS
				$postdata['user_type'] = "lead";

				$lead = Lead::findFirst([
					'conditions' => 'email = ?0',
					'bind' => [$postdata['email']],
				]);

				//if ($lead && $lead->getType() != "lead") {

				//}

				if (!$lead) {
					$lead = new Lead();
					$lead->assign($postdata);
					$lead->save();
				}

				if ($lead) {

					$data = [
						'url' => "http://" . $this->sysconfig->deploy->environment . ".sysclass.com/autologin/" . 'demo-user',
					];

					$message = $this->createResponse(200, $this->translate->translate(" Thank you for trying SysClass. Would like to know more? Contact us."), "success");

					$this->response->setJsonContent(array(
						'message' => $message,
						'error' => false,
						'redirect' => $data['url'],
					));
					return true;
				} else {
					$messages[] = $this->createResponse(400, $this->translate->translate("The information sent appers to be incomplete. Please, check your info and try again!"), "error");
					$error = true;
				}

				/*
									// CREATE TRANSACTION
									$this->db->begin();

									$user = $this->authentication->signup($postdata);

									if ($user) {
										$user->refresh();
										$messages[] = $this->createResponse(200, "User created.", "success");

					 				} else {
										$messages[] = $this->createResponse(400, $this->translate->translate("Your data sent appers to be imcomplete. Please, check your info and try again!"), "error");
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
				*/
			}

		} catch (AuthenticationException $e) {
			$error = true;
			switch ($e->getCode()) {
			case AuthenticationException::SIGNUP_EMAIL_ALREADY_EXISTS:{
					$messages[] = $this->invalidRequestError("There is already a registration with this email. Would you like to login", "warning");
					break;
				}
			case AuthenticationException::USER_DATA_IS_INVALID_OR_INCOMPLETE:{
					$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
					break;
				}
			default:{
					$messages[] = $this->invalidRequestError($this->translate->translate($e->getMessage()), "warning");
					break;
				}
			}
		}

		$this->response->setJsonContent(array(
			'messages' => $messages,
			'error' => $error,
			'data' => $data,
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
				'status' => $this->invalidRequestError(self::NO_DATA_FOUND, "warning"),
			));
		} else {
			$data = $enroll->toExtendArray(["courses"]);
			//$data = $enroll->toArray();

			//echo ($data);
			//exit;

			$courses = $enroll->getCourses([
				'conditions' => 'signup_active = 1 AND signup_enable_new_users = 1',
			]);

			$data['courses'] = array();
			foreach ($courses as $course) {
				$data['courses'][] = $course->toExtendArray();
			}

			$fields = $enroll->getEnrollFields(array(
				'order' => 'position',
			));
			$data['fields'] = array();

			foreach ($fields as $field) {
				//print_r($field->toFullArray());
				$field->translate();

				$data['fields'][] = $field->toFullArray();
			}

			$data['labels'] = [
				'enroll_action' => $this->translate->translate("Enroll Now"),
				'already_has_account' => $this->translate->translate("Already has a account? Click Here."),
				'choose_program' => $this->translate->translate("Choose your program."),
				'accept_the' => $this->translate->translate("Accept the"),
				'use_terms' => $this->translate->translate("terms of usage"),
				/**
				 * @todo Inject this info inside the enrollment page
				 */

				'form_title' => $enroll->name,
				'form_subtitle' => $enroll->subtitle,
				'confirmation_title' => $this->translate->translate("Thank you"),
				'confirmation_text' => $this->translate->translate("<p>Your registration has been received. In a few minutes you will receive a confirmation email containing a link to conclude your registration.</p><p>In case you haven't received the confirmation email, check your Junk folder. If you can't find it, return to this page and try again.</p>"),
			];

			//$data = $enroll->toExtendArray(array('fields' => 'EnrollFields'));

			$this->response->setJsonContent(array(
				'status' => $this->createResponse(200, self::EXECUTION_OK, "success"),
				'data' => $data,
			));

		}
		//} else {
		//	$this->response->setJsonContent($this->invalidRequestError(self::INVALID_DATA, "warning"));
		//}

	}

	//valid age
	protected function validAge($birthday , $age=14){
	 	$ar_birthday = explode('/', $birthday);
        if( count($ar_birthday) == 3 ){
            $birthday = strtotime($ar_birthday[2].'-'.$ar_birthday[1].'-'.$ar_birthday[0]);
            if(time() - $birthday < $age * 31536000)  {
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return FALSE;
        }
	}
	
	// RequestManager
	protected function createResponse($code, $message, $type, $intent = null, $callback = null) {
		http_response_code($code);
		$error = array(
			"code" => $code,
			"message" => $message,
			"type" => $type,
		);
		if (!is_null($callback)) {
			$error['data'] = $callback;
		}
		return $error;
	}

	protected function invalidRequestError($message = "", $type = "warning") {
		if (empty($message)) {
			$message = $this->translate->translate("There's a problem with your request. Please, try again.");
		}
		return $this->createResponse(200, $message, $type, "advise");
	}

}
