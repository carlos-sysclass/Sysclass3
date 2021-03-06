<?php
namespace Sysclass\Controllers;

use Phalcon\DI,
	Sysclass\Models\Users\User,
	Sysclass\Models\I18n\Language,
	Sysclass\Models\Users\UserPasswordRequest,
	Sysclass\Models\Enrollments\Enroll,
	Sysclass\Services\Authentication\Exception as AuthenticationException;

class LoginController extends \AbstractSysclassController
{
	public function authorize()
	{
		// Always authorize login page
		return TRUE;
	}

    /**
     * * Create login and reset password forms
     * @Get("/")
     * @Get("/login")
     * @Get("/login/{reset}")
     * 
     */
	public function loginPage($reset)
	{
		$di = DI::getDefault();

		try {
			// CHECK IF THE USER IS ALREADY LOGGED IN AND REDIRECT IF SO
			$user = $di->get("authentication")->checkAccess();
			$this->redirect("/dashboard");
		} catch (AuthenticationException $e) {
		}

		//$session = $di->get("session");
		//$request_uri = $session->get("requested_uri");

		//if ($request_uri) {
		//	$session->set("requested_uri", $request_uri);
		//}

		// CLEAR SESSION, IF THE USER IS OPENING THE LOGIN PAGE, AFTER CHECKING THE "REMEMBER" COOKIE
		isset($_COOKIE[session_name()]) ? setcookie(session_name(), '', time()-42000, '/') : null;

		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->putCss("css/pages/login");
		$this->putCss("css/bigvideo/bigvideo");

		$this->putCss("plugins/uniform/css/uniform.default");
		$this->putScript("plugins/uniform/jquery.uniform.min");

		$this->putScript("plugins/modernizr/modernizr");
		//$this->putScript("plugins/imagesloaded/imagesloaded");

		$this->putComponent("bigvideo");

		//$this->putScript("plugins/videoBG/jquery.videoBG");
		$this->putScript("scripts/pages/login");

		if ($reset == "reset") {
			$this->putItem("open_login_section", "reset");
		} else {
			$this->putItem("open_login_section", "login");
		}

		if ($this->dispatcher->wasForwarded()) {
			$this->putItem("requested_uri", $this->request-> getURI());
		}

		$enroll = Enroll::findFirst();

		$this->putItem("default_enroll_link", $enroll->identifier);

		parent::display('pages/auth/login.tpl');
	}
	/**
	 * [Add a description]
	 *
	 * @Get("/signup")
	 */
	public function signupPage()
	{
		$di = DI::getDefault();

		try {
			// CHECK IF THE USER IS ALREADY LOGGED IN AND REDIRECT IF SO
			$user = $di->get("authentication")->checkAccess();
			$this->redirect("/dashboard");
		} catch (AuthenticationException $e) {
			/*
			*/
		}


		$session = $di->get("session");
		$request_uri = $session->get("requested_uri");

		if ($request_uri) {
			$session->set("requested_uri", $request_uri);
		}

		/*
		if (isset($_COOKIE['cookie_login']) && isset($_COOKIE['cookie_password'])) {
		    try {
		        $user = MagesterUserFactory :: factory($_COOKIE['cookie_login']);
		        $user->login($_COOKIE['cookie_password'], true);
		        if ($GLOBALS['configuration']['show_license_note'] && $user->user['viewed_license'] == 0) {
		            //sC_redirect("index.php?ctg=agreement");
		            $this->redirect("agreement");
		        } else {
		            // Check if the mobile version of SysClass is required - if so set a session variable accordingly
		            //sC_setMobile();
		            MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_VISITED, "users_LOGIN" => $user->user['login'], "users_name" => $user->user['name'], "users_surname" => $user->user['surname']));
		            //LoginRedirect($user->user['user_type']);
		            $this->redirect("/dashboard/" . $user->user['user_types_ID']);
		        }
		        exit;
		    } catch (MagesterUserException $e) {}
		}
		*/
		// CLEAR SESSION, IF THE USER IS OPENING THE LOGIN PAGE, AFTER CHECKING THE "REMEMBER" COOKIE
		isset($_COOKIE[session_name()]) ? setcookie(session_name(), '', time()-42000, '/') : null;

		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->putCss("css/pages/login");
		$this->putCss("css/bigvideo/bigvideo");

		$this->putCss("plugins/uniform/css/uniform.default");
		$this->putScript("plugins/uniform/jquery.uniform.min");

		$this->putScript("plugins/modernizr/modernizr");
		$this->putScript("plugins/imagesloaded/imagesloaded");
		$this->putScript("plugins/bigvideo/bigvideo");

		//$this->putScript("plugins/videoBG/jquery.videoBG");
		$this->putScript("scripts/pages/signup");

		$this->putItem("requested_uri", $request_uri);


		$this->putComponent("select2");
		//$this->putCss("plugins/select2/select2_metro");
		//$this->putScript("plugins/select2/select2");

        $languages = Language::find("active = 1");
        $this->putitem("languages", $languages->toArray());

		parent::display('pages/auth/signup.tpl');



	    /*
	    } elseif (isset($_GET['id']) && isset($_GET['login'])) { //Second stage, user received the email and clicked on the link
	        $login = $_GET['login'];
	        if (!sC_checkParameter($login, 'login')) { //Possible hacking attempt: malformed user
	            $message = _INVALIDUSER;
	            $message_type = 'failure';
	        } else {
	            $user = sC_getTableData("users", "email, name", "login='".$login."'");
	            if (strcmp($_GET['id'], MagesterUser::createPassword($login)) == 0 && sizeof($user) > 0) {
	                $password = mb_substr(md5($login.time()), 0, 8);
	                $password_encrypted = MagesterUser::createPassword($password);
	                sC_updateTableData("users", array('password' => $password_encrypted), "login='$login'");
	                MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_NEW_PASSWORD_REQUEST, "users_LOGIN" => $login, "entity_name" => $password));
	                $message = _EMAILWITHPASSWORDSENT;
	                sC_redirect(''.basename($_SERVER['PHP_SELF']).'?message='.urlencode($message).'&message_type=success');
	            } else {
	                $message = _INVALIDUSER;
	                $message_type = 'failure';
	            }
	        }
	    }
		}
		*/

	}
	/**
	 * [Add a description]
	 *
	 * @Post("/signup")
	 */
	public function signupRequest()
	{
		$di = DI::getDefault();

		try {
			// CHECK IF THE USER IS ALREADY LOGGED IN AND REDIRECT IF SO
			$user = $di->get("authentication")->checkAccess();
			$this->redirect("/dashboard");
		} catch (AuthenticationException $e) {
			/*
			switch($e->getCode()) {
				case AuthenticationException :: NO_USER_LOGGED_IN : {
					// IN THIS CONTEXT, IT'S SEEN TO BE THE EXPECT BEHAVIOR
		            //$message = $this->translate->translate("Your session has expired. Please, re-enter your username and password.");
		            //$message_type = 'info';
					break;
				}
			}
			*/
		}


        //$itemModel = $this->model("user/item");
        // TODO CHECK IF CURRENT USER CAN DO THAT
        $data = $this->getHttpData(func_get_args());

        if ($user = $di->get("authentication")->signup($data)) {
        	// SHOW SUCCESS MESSAGE
        	if ($di->get("configuration")->get("signup_must_approve")) {
        		$this->redirect(
        			"/login", 
        			$this->translate->translate("You'll receive an email when your registration is processed."),
        			"info"
        		);
        	} else {
        		$this->redirect(
        			"/login", 
        			$this->translate->translate("Check your email with the instructions on how to proceed with your registration."),
        			"success"
        		);
        	}
        } else {
        	// SHOW ERROR MESSAGE
    		$this->redirect(
    			"/signup", 
    			$this->translate->translate("A problem ocurred when attempting to complete your registration. Please, try again. If the problem persists, contact the system administrator."),
    			"danger"
    		);
        }
	}


    /**
     * Authenticate the user
     * @Post("/")
     * @Post("/login")
     * 
     */
	public function login()
	{
		$data = $this->request->getPost();

		$di = DI::getDefault();

		// DEFINE AUTHENTICATION BACKEND

		//$authBackend = $di->get("authentication")->getBackend($data['login']);
		


		try {
			$is_email = filter_var($data['login'], FILTER_VALIDATE_EMAIL);

			$user = $di->get("authentication")->login(
				array(
					'login' => $data['login'],
					'password' => $data['password']
				),
				array(
					'isEmail' => (bool)$is_email
				)
			);

			// IF THE USER IS AUTHENTICATED, GO TO AUTHORIZATION
		} catch (AuthenticationException $e) {

			$url = null;
			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
		            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
		            $message_type = 'warning';
		            break;
				}

				case AuthenticationException :: MAINTENANCE_MODE : {

		            $message = $this->translate->translate("System under maintenance.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
		            $message = $this->translate->translate("Username and/or password are incorrect. Please, make sure you typed correctly.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
					$url = "/lock";
		            $message = $this->translate->translate("Please, check if the login info is correct, and re-enter the username and password.");
		            $message_type = 'info';
		            break;
				}
				default : {
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}

			//if (!empty($data["requested_uri"])) {
			//	$di->get("session")->set("requested_uri", $data["requested_uri"]);
			//}

			$this->redirect($url, $message, $message_type);
		}
		// USER IS LOGGED IN, SO...
		// 1.6 Check for license agreement
		if ($user->viewed_license == 0) {
			$this->redirect("agreement");
		}

		// 1.7 Check if the user needs to realize a payment to continue (like after a enroll process)
		/**
		 * @todo Create a way check if the user came from a external service, and if this service needs a pament function.
		 */
		
		if (!empty($data["requested_uri"])) {
			$this->redirect($data["requested_uri"]);
		} else {
			$this->redirect("/dashboard/");
		}
	}

	/**
	 * [Add a description]
	 *
	 * @Get("/logout")
	 * @Post("/logout")
	 */
	public function logoutRequest()
	{
		$di = DI::getDefault();

		try {
			// CHECK IF THE USER IS ALREADY LOGGED IN AND REDIRECT IF SO
			//$user = $di->get("authentication")->checkAccess();

			$di->get("authentication")->logout($user);

		    $message = $this->translate->translate("You have been logout sucessfully. Thanks for using SysClass.");
		    $message_type = 'warning';

			$this->redirect("/login", $message, $message_type);
		} catch (AuthenticationException $e) {
			//AuthenticationException::NO_USER_LOGGED_IN;
			$this->redirect("/login");
		}
	}
	/**
	 * Create login and reset password forms
	 *
	 * @Get("/autologin/{hash}")
	 */
	public function autologinPage($hash)
	{
		$url = "/login";
		if (isset($hash) && ($hash == 'demo-user' || $hash == 'admin-user' || $this->_checkParameter($hash, 'hex'))) {

			$di = DI::getDefault();
			try {
				// CHECK IF THE USER IS ALREADY LOGGED IN AND LOGOUT HIM...
				try {
					$user = $di->get("authentication")->checkAccess();
					if ($user) {
						$di->get("authentication")->logout($user);


						if (!$this->session->isStarted()) {
							$this->session->start();
						}
					}
				} catch (AuthenticationException $e) {
					
				}


	            $user = User::findFirst(array(
	                "autologin = '{$hash}'",
	                'active = 1'
	            ));

	            if ($user) {
            		$di->get("authentication")->login($user, array('disableBackends' => true));
					$this->redirect("/dashboard");
	            } else {
	            	throw new AuthenticationException("Error Processing Request", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
	            }
			} catch (AuthenticationException $e) {
				switch($e->getCode()) {
					case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
			            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: MAINTENANCE_MODE : {

			            $message = $this->translate->translate("System under maintenance.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
			            $message = $this->translate->translate("The system can't locate this account. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					case AuthenticationException :: LOCKED_DOWN : {
			            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					default : {
			            $message = $this->translate->translate($e->getMessage());
			            $message_type = 'danger';
			            break;
					}
				}
			}
		}
		$this->redirect($url, $message, $message_type);
	}

	/**
	 * Create login and reset password forms
	 *
	 * @Get("/confirm/{hash}")
	 */
	public function confirmPage($hash)
	{
		$url = "/login";
		//if (isset($hash) && ($hash == 'demo-user' || $hash == 'admin-user' || $this->_checkParameter($hash, 'hex'))) {

			$di = DI::getDefault();
			try {
				// CHECK IF THE USER IS ALREADY LOGGED IN AND LOGOUT HIM...
				try {
					
					$user = $di->get("authentication")->checkAccess();
					
					if ($user) {
						$di->get("authentication")->logout($user);

						$this->redirect(null);
						exit;
					}
					
				} catch (AuthenticationException $e) {
					//AuthenticationException::NO_USER_LOGGED_IN
				}

	            $user = User::findFirst(array(
	            	'conditions' => "reset_hash = ?0 AND active = 1",
	                'bind' => array($hash)
	            ));

	            //$di->get("authentication")->logout($user);

	            if ($user) {
        			$di->get("authentication")->login($user, array('disableBackends' => true));
        		
		       		$this->putCss("css/pages/login");
					$this->putCss("css/bigvideo/bigvideo");

					$this->putScript("plugins/bigvideo/bigvideo");
					$this->putScript("scripts/pages/reset");

					$this->putItem('is_confirmation', true);

					$this->putItem('form_action', "/confirm/{$hash}");
					$this->putItem('user', $user->toArray());

		            return parent::display('pages/auth/reset.tpl');
	            } else {
	            	throw new AuthenticationException("Error Processing Request", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
	            }
			} catch (AuthenticationException $e) {
				switch($e->getCode()) {
					case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
			            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: MAINTENANCE_MODE : {

			            $message = $this->translate->translate("System under maintenance.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
			            $message = $this->translate->translate("The system can't locate this account. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					case AuthenticationException :: LOCKED_DOWN : {
			            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					default : {
			            $message = $this->translate->translate($e->getMessage());
			            $message_type = 'danger';
			            break;
					}
				}
			}
		
		$this->redirect($url, $message, $message_type);
	}

	/**
	 * Create login and reset password forms
	 *
	 * @Post("/confirm/{hash}")
	 */
	public function confirmRequest($hash)
	{
		$url = "/confirm/{$hash}";
		//if (isset($hash) && ($hash == 'demo-user' || $hash == 'admin-user' || $this->_checkParameter($hash, 'hex'))) {

			$di = DI::getDefault();
			// CHECK IF THE USER IS ALREADY LOGGED IN AND LOGOUT HIM...
			try {
				$current_user = $di->get("authentication")->checkAccess();

	            $user = User::findFirst(array(
	            	'conditions' => "reset_hash = ?0 AND active = 1",
	                'bind' => array($hash)
	            ));

	           	$postData = $this->request->getPost();

				if ($user == $current_user) {
					/*
					if ($postData['login'] != $user->login) {
						// CHECK IF EXISTS
			            $exists = User::count(array(
			            	'conditions' => "login = ?0 AND id <> ?1",
			                'bind' => array($postData['login'], $user->id)
			            ));
			            if ($exists > 0) {
							throw new AuthenticationException("This username is not available. Please, select another one.");
			            }
			            $user->login = $postData['login'];
					}
					*/
					if ($postData['password'] === $postData['password-confirm']) {
						$user->password = $this->authentication->hashPassword($postData['password'], $user);

						//$user->pending = 0;
						$user->reset_hash = null;

						if ($user->save()) {
							$message = $this->translate->translate("Password updated.");
			            	$message_type = 'success';

							// USER IS LOGGED IN, SO...
							// 1.6 Check for license agreement
							//$di->get("authentication")->logout($current_user);


							if ($user->viewed_license == 0) {
								$this->redirect("/agreement", $message, $message_type);
							} else {
								$this->redirect("/dashboard", $message, $message_type);
							}
						}
					}
				} else {
					$di->get("authentication")->logout($user);
					throw new AuthenticationException("Error Processing Request", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
				}
			} catch (AuthenticationException $e) {
				switch($e->getCode()) {
					case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
			            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: MAINTENANCE_MODE : {

			            $message = $this->translate->translate("System under maintenance.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
			            $message = $this->translate->translate("The system can't locate this account. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					case AuthenticationException :: LOCKED_DOWN : {
			            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					default : {
			            $message = $this->translate->translate($e->getMessage());
			            $message_type = 'warning';
			            break;
					}
				}
			}
		$this->redirect($url, $message, $message_type);
	}

	/**
	 * Create login and reset password forms
	 *
	 * @Get("/password-reset/{hash}")
	 */
	public function passwordResetPage($hash)
	{
		$url = "/login";
		//if (isset($hash) && ($hash == 'demo-user' || $hash == 'admin-user' || $this->_checkParameter($hash, 'hex'))) {

			$di = DI::getDefault();
			try {
				// CHECK IF THE USER IS ALREADY LOGGED IN AND LOGOUT HIM...
				try {
					$user = $di->get("authentication")->checkAccess();
					if ($user) {
						$di->get("authentication")->logout($user);
					}
				} catch (AuthenticationException $e) {
					//AuthenticationException::NO_USER_LOGGED_IN
				}

				$passwordRequest = UserPasswordRequest::findFirst(array(
					'conditions' => 'reset_hash = ?0 AND active = 1',
					'bind' => array($hash)
				));

	            if ($passwordRequest) {
	            	$user = $passwordRequest->getUser();

	            	$this->putItem("user", $user->toArray());
	            	//Date
	            	//
	            	$valid_until = new \DateTime($passwordRequest->valid_until);

	            	if ($valid_until < new \DateTime('today')) {
	            		$url = "/login/reset";
	            		throw new AuthenticationException("There is a problem with your request. Please, enter a new request.", AuthenticationException::RESET_HASH_ISNT_VALID_ANYMORE);
	            	}

            		//$di->get("authentication")->login($user, array('disableBackends' => true));
					
		       		$this->putCss("css/pages/login");
					$this->putCss("css/bigvideo/bigvideo");

					$this->putScript("plugins/bigvideo/bigvideo");
					$this->putScript("scripts/pages/reset");

					$this->putItem('form_action', "/password-reset/{$hash}");
					$this->putItem('user', $user->toArray());
					$this->putItem("disable_login", true);
					

		            return parent::display('pages/auth/reset.tpl');
	            } else {
	            	throw new AuthenticationException("There is a problem with your request. Please, enter a new request.", AuthenticationException::RESET_HASH_ISNT_VALID_ANYMORE);
	            }
			} catch (AuthenticationException $e) {
				switch($e->getCode()) {
					case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
			            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: MAINTENANCE_MODE : {

			            $message = $this->translate->translate("System under maintenance.");
			            $message_type = 'warning';
			            break;
					}
					case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
			            $message = $this->translate->translate("The system can't locate this account. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					case AuthenticationException :: LOCKED_DOWN : {
			            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
			            $message_type = 'warning';
						break;
					}
					default : {
			            $message = $this->translate->translate($e->getMessage());
			            $message_type = 'danger';
			            break;
					}
				}
			}
		
		$this->redirect($url, $message, $message_type);
	}

	/**
	 * Create login and reset password forms
	 *
	 * @Post("/password-reset/{hash}")
	 */
	public function passwordResetUpdateRequest($hash)
	{
		$url = "/password-reset/{$hash}";

		$di = DI::getDefault();
		try {
			// CHECK IF THE USER IS ALREADY LOGGED IN AND LOGOUT HIM...
			try {
				$user = $di->get("authentication")->checkAccess();
				if ($user) {
					$di->get("authentication")->logout($user);
				}
			} catch (AuthenticationException $e) {
				//AuthenticationException::NO_USER_LOGGED_IN
			}

			$passwordRequest = UserPasswordRequest::findFirst(array(
				'conditions' => 'reset_hash = ?0 AND active = 1',
				'bind' => array($hash)
			));

			$user = $passwordRequest->getUser();

			$postData = $this->request->getPost();

			if ($postData['password'] === $postData['password-confirm']) {
				$user->password = $this->authentication->hashPassword($postData['password'], $user);

				$user->reset_hash = null;

				$passwordRequest->active = 0;

				if ($user->save() && $passwordRequest->save()) {
					$message = $this->translate->translate("Password updated. Please, enter you login details below.");
	            	$message_type = 'success';

	            	$this->redirect("/login", $message, $message_type);


					// USER IS LOGGED IN, SO...
					/*
					$di->get("authentication")->login($user, array('disableBackends' => true));
					// 1.6 Check for license agreement
					if ($user->viewed_license == 0) {
						$this->redirect("/agreement", $message, $message_type);
					} else {
						$this->redirect("/dashboard", $message, $message_type);
					}
					*/
				}
			} else {
				$di->get("authentication")->logout($user);
				throw new AuthenticationException("Error Processing Request", AuthenticationException::INVALID_USERNAME_OR_PASSWORD);
			}
		} catch (AuthenticationException $e) {

			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
		            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: MAINTENANCE_MODE : {

		            $message = $this->translate->translate("System under maintenance.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
		            $message = $this->translate->translate("The system can't locate this account. Please, contact the system administrator.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
		            $message_type = 'warning';
					break;
				}
				default : {
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'warning';
		            break;
				}
			}
		}
	
		$this->redirect($url, $message, $message_type);
	}
	/**
	 * Create login and reset password forms
	 *
	 * @Get("/lock")
	 */
	public function lockPage($reset)
	{
		$di = DI::getDefault();

		try {
			// CHECK IF THE USER IS ALREADY LOGGED IN AND REDIRECT IF SO
			$user = $di->get("authentication")->lock();
		    //$smarty->assign("T_LOGGED_USER", self::$logged_user);
		} catch (AuthenticationException $e) {
			$url = "/login";
			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
		            $message = $this->translate->translate("Incorrect username or password. If you need reset your login info, click on FORGOT YOUR PASSWORD.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: MAINTENANCE_MODE : {
		            $message = $this->translate->translate("System under maintenance.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = $this->translate->translate("Access not available. Please, contact the system administrator.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: NO_USER_LOGGED_IN : {
		            $message = $this->translate->translate("Your session has expired. Please, re-enter your username and password.");
		            $message_type = 'info';
		            break;
				}
				case AuthenticationException :: USER_ACCOUNT_IS_LOCKED : {
					$url = null;
					$message = null;
		            $message_type = null;
		            $user = $di->get("authentication")->getSessionUser();
				}
				default : {
		            $message = $this->translate->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}
		}

		if (!is_null($url)) {
			$this->redirect($url, $message, $message_type);
		}

		if ($user) {
			$this->putItem("LOGGED_USER", $user->toFullArray(array("Avatars")));

			$this->putCss("css/pages/lock"); 
			$this->putScript("scripts/lock");
			parent::display('pages/auth/lock.tpl');
		} else {
			$this->redirect("/login");
		}

	}

	/**
	 * [Add a description]
	 *
	 * @Get("/login/reset")
	 */
	/*
	public function loginResetRequest()
	{

		$message = $this->translate->translate("The system doesn't support this function.");
		$message_type = 'warning';
		$this->redirect("/login", $message, $message_type);
		
		if ($GLOBALS['configuration']['password_reminder'] && !$GLOBALS['configuration']['only_ldap']) { //The user asked to display the contact form
			$form = $this->createResetPasswordForm();

			if ($form->isSubmitted() && $form->validate()) {
		        $input = $form->exportValue("login_or_pwd");
		        try {
		            if ($this->_checkParameter($input, 'email')) { //The user entered an email address
		                $result = sC_getTableData("users", "login", "email='".$input."'"); //Get the user stored login
		                if (sizeof($result) > 1) {
		                    $message = $this->translate->translate("There is more than one user with the same data given. Please, try to use email or login.");
		                    $message_type = 'warning';
		                    //sC_redirect(''.basename($_SERVER['PHP_SELF']).'?ctg=reset_pwd&message='.urlencode($message).'&message_type='.$message_type);
							$this->redirect("login/reset", $message, $message_type);
		                    exit;
		                } else {
		                    $user = MagesterUserFactory :: factory($result[0]['login']);
		                }
		            } elseif ($this->_checkParameter($input, 'login')) { //The user entered his/her login name
		                $user = MagesterUserFactory :: factory($input);
		            }
		            if ($user->isLdapUser) {
		                sC_redirect(''.basename($_SERVER['PHP_SELF']).'?message='.urlencode(_LDAPUSERMUSTCONTACTADMIN.$GLOBALS['configuration']['system_email']).'&message_type=failure');
		            } else {
		                MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_FORGOTTEN_PASSWORD, "users_LOGIN" => $user->user['login'], "users_name" => $user->user['name'], "users_surname" => $user->user['surname']));
		                $message = $this->translate->translate("Within minutes, you will receive an email with instructions to set your new password.");
		                $message_type = 'success';
		                if ($_SESSION['login_mode'] != 1) {
		                    //sC_redirect(''.basename($_SERVER['PHP_SELF']).'?message='..'&message_type='.);
		                    $this->redirect("login", $message, $message_type);
		                }
		            }
		        } catch (Exception $e) {
		            $message = _NONEXISTINGMAIL;
		            $message_type = 'failure';
		            sC_redirect(''.basename($_SERVER['PHP_SELF']).'?ctg=reset_pwd&message='.urlencode($message).'&message_type='.$message_type);
		        }
			}
		} else {
			return false;
		}
	}
	*/

	/**
	 * [Add a description]
	 *
	 * @Post("/password-reset")
	 */
	public function passwordResetRequest() {
		$email = $this->request->getPost('email');

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->redirect(
				"login/reset",
				$this->translate->translate('Please, provide a valid email.'),
				'warning'
			);
		} else {
			// GET THE USER, FIRE THE EVENT, AND LET THE BACKEND SYSTEM DO THE REST! :)
			$user = User::findFirstByEmail($email);

			if ($user) {

				$this->eventsManager->fire("user:password-reset", $this, $user->toArray());

				$this->redirect(
					"login/reset",
					$this->translate->translate("Check your email with the instructions on how to reset your password."),
					'success'
				);
			} else {
				$this->redirect(
					"login/reset",
					$this->translate->translate('We can\'t locate the email provided in our records. Please, verity your email.'),
					'warning'
				);
			}
		}
	}
}
