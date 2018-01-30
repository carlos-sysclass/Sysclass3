<?php
namespace Sysclass\Tasks;

use Sysclass\Collections\Requests\Entry;
use Sysclass\Models\Content\Program as Course;
use Sysclass\Models\Enrollments\Enroll;
use Sysclass\Models\Users\UserAttrs;
use Sysclass\Services\Authentication\Exception as AuthenticationException;

class ApiTask extends \Phalcon\CLI\Task {
	public function mainAction() {
		// SHOW HELP
	}

	public function reprocessAction() {
		$conditions = ["post_result" => null];

		$not_processed = Entry::find([$conditions]);

		foreach ($not_processed as $entry) {
			$entry->post_result = $this->createPostponeUser((array) $entry->postdata);

			$entry->save();
		}
	}

	protected function createPostponeUser($postdata) {
		//$postdata = $this->request->getJsonRawBody(true);
		/*
		$request = new RequestEntry();
		$request->postdata = $postdata;
		$request->save();
        */
		$error = false;

		$messages = $data = array();

		try {
			if (is_null($postdata)) {
				$messages[] = $this->invalidRequestError(self::INVALID_DATA, "warning");
			} else {
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
								} elseif (is_object($value)) {
									$userAttrs->field_value = json_encode((array) $value);
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
					$messages[] = $this->createResponse($e->getCode(), "USER_ALREADY_EXISTS", "error");
					break;
				}
			case AuthenticationException::USER_DATA_IS_INVALID_OR_INCOMPLETE:{
					$messages[] = $this->invalidRequestError("USER_DATA_IS_INVALID_OR_INCOMPLETE", "warning");
					break;
				}
			default:{
					$messages[] = $this->invalidRequestError($e->getMessage(), "warning");
					break;
				}
			}
		}

		return array(
			'messages' => $messages,
			'error' => $error,
			'data' => $data,
		);
	}

	/**
	 * @todo  Move this method to a parent "cli controller"
	 */
	protected function createResponse($code, $message, $type, $intent = null, $callback = null) {
		//http_response_code($code);
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
		return $this->createResponse(400, $message, $type, "advise");
	}
}