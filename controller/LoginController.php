<?php
use Phalcon\DI,
	Sysclass\Models\Users as usersModel,
	Sysclass\Services\Authentication\Exception as AuthenticationException;

class LoginController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!

	public function authorize()
	{
		// Always authorize login page
		return TRUE;

	}
	/*
	protected function onThemeRequest()
	{
		if ($this->getRequestedUrl() == "") {
			$this->setTheme('sysclass.frontend');
		}
	}
	*/
	protected function createLoginForm() {
		//$postTarget = "/login?debug=10";
		//		isset($_GET['ctg']) && $_GET['ctg'] == 'login' ? $postTarget = basename($_SERVER['PHP_SELF'])."?ctg=login" : $postTarget = basename($_SERVER['PHP_SELF'])."?index_page";

		$form = new HTML_QuickForm("login_form", "post", $_SERVER['REQUEST_URI'], "", "class = 'login-form'", true);
		$form->removeAttribute('name');
		//$form->registerRule('checkParameter', 'callback', 'sC_checkParameter'); //Register this rule for checking user input with our function, sC_checkParameter
		$form->addElement('text', 'login', self::$t->translate("Login"), 'class = "form-control placeholder-no-fix" ');
		//$form->addRule('login', _THEFIELD.' "'._LOGIN.'" '._ISMANDATORY, 'required', null, 'client');
		//$form->addRule('login', _INVALIDLOGIN, 'checkParameter', 'login');
		$form->addElement('password', 'password', self::$t->translate("Password"), 'class = "form-control placeholder-no-fix" tabindex = "0"');
		//$form->addRule('password', _THEFIELD.' "'._PASSWORD.'" '._ISMANDATORY, 'required', null, 'client');
		$form->addElement('checkbox', 'remember', self::$t->translate("Remember me"), 1, 'class = "inputCheckbox"');
		$form->addElement('submit', 'submit_login', self::$t->translate("Click to access"));

		return $form;
	}

	protected function createResetPasswordForm() {
		$postTarget = "/login/reset";
		$form = new HTML_QuickForm("reset_password_form", "post", $postTarget, "", "class = 'forget-form'", true);
		$form->removeAttribute('name');
		//$form->registerRule('checkParameter', 'callback', 'sC_checkParameter'); //Register this rule for checking user input with our function, sC_checkParameter
		$form->addElement('text', 'login_or_pwd', self::$t->translate("_LOGINOREMAIL"), 'class = "form-control placeholder-no-fix"');
		//$form->addRule('login_or_pwd', _THEFIELD.' '._ISMANDATORY, 'required', null, 'client');
		//$form->addRule('login_or_pwd', _INVALIDFIELDDATA, 'checkParameter', 'text');
		$form->addElement('submit', 'submit_reset_password', self::$t->translate("Send"), 'class="flatButton"');

		return $form;
	}
	/**
	 * Create login and reset password forms
	 *
	 * @url GET /
	 * @url GET /login
	 * @url GET /login/:reset
	 */
	public function loginPage($reset)
	{
		$di = DI::getDefault();

		$session = $di->get("session");
		$request_uri = $session->get("requested_uri");

		if ($request_uri) {
			$session->set("requested_uri", $request_uri);
		}

		var_dump($request_uri);

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
		$this->putScript("plugins/modernizr/modernizr");
		$this->putScript("plugins/imagesloaded/imagesloaded");
		$this->putScript("plugins/bigvideo/bigvideo");
		//$this->putScript("plugins/videoBG/jquery.videoBG");
		$this->putScript("scripts/pages/login");


		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		//$this->putScript("scripts/login-soft");

		//$smarty = $this->getSmarty();
		//$loginForm = $this->createLoginForm();

		/*
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$loginForm->setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);
		$loginForm->setRequiredNote(_REQUIREDNOTE);
		$loginForm->accept($renderer);
		$smarty->assign('T_LOGIN_FORM', $renderer->toArray());

		var_dump($renderer->toArray());
		exit;
		*/

		/*
		if ($GLOBALS['configuration']['password_reminder'] && !$GLOBALS['configuration']['only_ldap']) { //The user asked to display the contact form
			$resetForm = $this->createResetPasswordForm();
		    $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		    $renderer->setRequiredTemplate(
		        '{$html}{if $required}
		        &nbsp;<span class = "formRequired">*</span>
		            {/if}');
		    $resetForm->setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);
		    $resetForm->setRequiredNote(_REQUIREDNOTE);
		    $resetForm->accept($renderer);
		    $smarty->assign('T_RESET_PASSWORD_FORM', $renderer->toArray());
		}
		*/
		if ($reset) {
			$this->putItem("open_login_section", "reset");
		}
		$this->putItem("requested_uri", $request_uri);
		parent::display('pages/auth/login.tpl');
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

	protected function beforeDisplay() {
		parent::beforeDisplay();
		if ($this->getTheme() == "sysclass.frontend") {
			$this->template = 'login.tpl';
			// RETURN FALSE TO OVERRIDE TEMPLATE
			return false;
		}
	}

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /lock
	 */
	public function lockPage($reset)
	{
		parent::authorize();
		$_SESSION['user_locked'] = true;
		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->putCss("css/pages/lock");
		$this->putScript("scripts/lock");

		$smarty = $this->getSmarty();
		//$loginForm = $this->createLoginForm();


		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$loginForm->setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);
		$loginForm->setRequiredNote(_REQUIREDNOTE);
		$loginForm->accept($renderer);
		$smarty->assign('T_LOGIN_FORM', $renderer->toArray());

		parent::display('pages/auth/lock.tpl');



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
	 * @url POST /
	 * @url POST /login
	 * @url POST /lock
	 */
	public function loginAction()
	{
		$data = $this->getHttpData(func_get_args());

		$di = DI::getDefault();

		// DEFINE AUTHENTICATION BACKEND

		//$authBackend = $di->get("authentication")->getBackend($data['login']);
		//
		try {
			$user = $di->get("authentication")->login(
				array(
					'login' => $data['login'],
					'password' => $data['password']
				)
			);

			// IF THE USER IS AUTHENTICATED, GO TO AUTHORIZATION
		} catch (AuthenticationException $e) {
			$url = null;
			switch($e->getCode()) {
				case AuthenticationException :: NO_BACKEND_DISPONIBLE: {
		            $message = self::$t->translate("The system can't authenticate you using the current methods. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}

				case AuthenticationException :: MAINTENANCE_MODE : {

		            $message = self::$t->translate("System is under maintenance mode. Please came back in a while.");
		            $message_type = 'warning';
		            break;
				}
				case AuthenticationException :: INVALID_USERNAME_OR_PASSWORD : {
		            $message = self::$t->translate("Username and password are incorrect. Please make sure you typed correctly.");
		            $message_type = 'warning';
					break;
				}
				case AuthenticationException :: LOCKED_DOWN : {
		            $message = self::$t->translate("The system was locked down by a administrator. Please came back in a while.");
		            $message_type = 'warning';
					break;
				}
				default : {
		            $message = self::$t->translate($e->getMessage());
		            $message_type = 'danger';
		            break;
				}
			}

			if (!empty($data["requested_uri"])) {
				$di->get("session")->set("requested_uri", $data["requested_uri"]);
			}

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
			$di->get("session")->remove("requested_uri");
			$this->redirect($data["requested_uri"]);
		} else {
			$this->redirect("/dashboard/");
		}
	}

	/**
	 * [Add a description]
	 *
	 * @url POST /login/reset
	 */
	public function loginResetAction()
	{
		if ($GLOBALS['configuration']['password_reminder'] && !$GLOBALS['configuration']['only_ldap']) { //The user asked to display the contact form
			$form = $this->createResetPasswordForm();

			if ($form->isSubmitted() && $form->validate()) {
		        $input = $form->exportValue("login_or_pwd");
		        try {
		            if ($this->_checkParameter($input, 'email')) { //The user entered an email address
		                $result = sC_getTableData("users", "login", "email='".$input."'"); //Get the user stored login
		                if (sizeof($result) > 1) {
		                    $message = self::$t->translate("There is more than one user with the same data given. Please try to use e-mail or login.");
		                    $message_type = 'warning';
		                    //sC_redirect(''.basename($_SERVER['PHP_SELF']).'?ctg=reset_pwd&message='.urlencode($message).'&message_type='.$message_type);
							$this->redirect("login/reset", $message, $message_type);
		                    exit;
		                } else {
		                    $user = MagesterUserFactory :: factory($result[0]['login']);
		                }
		            } elseif ($this->_checkParameter($input, 'login')) { //The user entered his login name
		                $user = MagesterUserFactory :: factory($input);
		            }
		            if ($user->isLdapUser) {
		                sC_redirect(''.basename($_SERVER['PHP_SELF']).'?message='.urlencode(_LDAPUSERMUSTCONTACTADMIN.$GLOBALS['configuration']['system_email']).'&message_type=failure');
		            } else {
		                MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_FORGOTTEN_PASSWORD, "users_LOGIN" => $user->user['login'], "users_name" => $user->user['name'], "users_surname" => $user->user['surname']));
		                $message = self::$t->translate("Within minutes, you will receive an email with instructions to set your new password.");
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


	/**
	 * Create login and reset password forms
	 *
	 * @url GET /autologin/:hash
	 */
	public function autologinPage($hash)
	{
		if (isset($hash) && ($hash == 'demo-user' || $hash == 'admin-user' || $this->_checkParameter($hash, 'hex'))) {
		    try {
		        $result = $this->_getTableDataFlat("users", "login,autologin,password,user_type", "active=1 and autologin !=''");
		        $autolinks = $result['autologin'];
		        $key = array_search($hash, $autolinks);

		        if ($key !== false) {
		        	var_dump($result);
		            $user = MagesterUserFactory :: factory($result['login'][$key]);

		            $pattern = $user->user['login']."_".$user->user['timestamp'];
		            $pattern = md5($pattern.G_MD5KEY);

		            if ($hash == 'demo-user' || $hash == 'admin-user' || strcmp($pattern, $hash) == 0) {
		                $user->login($user->user['password'], true);

		                //if (isset($_GET['lessons_ID']) && sC_checkParameter($_GET['lessons_ID'], 'id')) {
		                //check for valid lesson
		                $urlArray = array();
		                foreach ($_GET as $key => $item) {
		                    if ($key != 'autologin') {
		                        $urlArray[] = $key . '=' . $item;
		                    }
		                }
		                if (count($urlArray) > 0) {
		                    setcookie('c_request', $user->user['user_type'].'.php?' . implode('&', $urlArray), time() + 300);
		                } else {
		                    setcookie('c_request', $user->user['user_type'].'.php', time() + 300);
		                }
		                //}

		                // UPDATE LAST LOGIN VALUE
		                $this->model("user/item")->setItem(array('last_login' => date("Y-m-d H:i:s")), $user->user['id']);

		                //$user_type = $user->user['user_types_ID'];

					    //$userTypes = array('administrator', 'professor', 'student');
					    //if (in_array($user_type, $userTypes)) {
							$this->redirect("/dashboard/" . $user->user['user_types_ID']);
						//} else {
						//	$this->redirect("user");
						//}
		                exit;
		            }
		        }
		    } catch (MagesterUserException $e) {
		    	var_dump($e);
		    	exit;
		    }
		}
		$this->redirect("login");
	}





	/**
	 * [Add a description]
	 *
	 * @url GET /logout
	 * @url POST /logout
	 */
	public function logoutAction()
	{
	    //session_start();			//Isn't needed here if the head session_start() is in place
	    if (isset($_SESSION['s_login']) && $_SESSION['s_login']) {
	        try {
	            $user = MagesterUserFactory :: factory($_SESSION['s_login']);
	            $user->logout(false);

                //$_SESSION = array();
                //isset($_COOKIE[session_name()]) ? setcookie(session_name(), '', time()-42000, '/') : null;
                //session_destroy();
                setcookie ("cookie_login", "", time() - 3600);
                setcookie ("cookie_password", "", time() - 3600);
                if (isset($_COOKIE['c_request'])) {
                    setcookie('c_request', '', time() - 86400);
                    unset($_COOKIE['c_request']);
                }
                unset($_COOKIE['cookie_login']); //These 2 lines are necessary, so that index.php does not think they are set
                unset($_COOKIE['cookie_password']);

	            $message = self::$t->translate("You have been logged out successfully.");
	            $message_type = 'success';

	            //Redirect user to another page, if such a configuration setting exists
	            /*
	            if ($GLOBALS['configuration']['logout_redirect']) {
	                if ($GLOBALS['configuration']['logout_redirect'] == 'close') {
	                    echo "<script>window.close();</script>";
	                } else {
	                    strpos($GLOBALS['configuration']['logout_redirect'], 'http://') === 0 ?
	                    sC_redirect("".$GLOBALS['configuration']['logout_redirect']) :
	                    header("location:http://".$GLOBALS['configuration']['logout_redirect']);
	                }
	            }
	            */
	        } catch (MagesterUserException $e) {
	            $message = $e->getMessage();
	            $message_type = 'danger';
	        }
	        // PUT HERE BECAUSE $user->logout(); destroy the session
	        $this->redirect("login", $message, $message_type);
	        var_dump($_SESSION);
	        exit;
	    } else {
	    	$this->redirect("login");
	    }
/*
		$result = $this->logoutUser();

		if ($this->getRequestedFormat() == RestFormat::HTML) {
			$this->putMessage($result['message'], "success");
			header("Location: login");
			exit;
		}
*/
	}
}
