<?php 
class LoginController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!

	public function authorize()
	{
		// Always authorize login page
		return TRUE;

	}

	protected function createLoginForm() {
		$postTarget = "/login";
//		isset($_GET['ctg']) && $_GET['ctg'] == 'login' ? $postTarget = basename($_SERVER['PHP_SELF'])."?ctg=login" : $postTarget = basename($_SERVER['PHP_SELF'])."?index_page";
		$form = new HTML_QuickForm("login_form", "post", $postTarget, "", "class = 'login-form'", true);
		$form->removeAttribute('name');
		//$form->registerRule('checkParameter', 'callback', 'sC_checkParameter'); //Register this rule for checking user input with our function, sC_checkParameter
		$form->addElement('text', 'login', _LOGIN, 'class = "form-control placeholder-no-fix" ');
		//$form->addRule('login', _THEFIELD.' "'._LOGIN.'" '._ISMANDATORY, 'required', null, 'client');
		//$form->addRule('login', _INVALIDLOGIN, 'checkParameter', 'login');
		$form->addElement('password', 'password', _PASSWORD, 'class = "form-control placeholder-no-fix" tabindex = "0"');
		//$form->addRule('password', _THEFIELD.' "'._PASSWORD.'" '._ISMANDATORY, 'required', null, 'client');
		$form->addElement('checkbox', 'remember', _REMEMBERME, null, 'class = "inputCheckbox"');
		$form->addElement('submit', 'submit_login', _ENTER, 'class = "flatButton"');

		return $form;
	}

	protected function createResetPasswordForm() {
		$postTarget = "/login/reset";
		$form = new HTML_QuickForm("reset_password_form", "post", $postTarget, "", "class = 'forget-form'", true);
		$form->removeAttribute('name');
		//$form->registerRule('checkParameter', 'callback', 'sC_checkParameter'); //Register this rule for checking user input with our function, sC_checkParameter
		$form->addElement('text', 'login_or_pwd', _LOGINOREMAIL, 'class = "form-control placeholder-no-fix"');
		//$form->addRule('login_or_pwd', _THEFIELD.' '._ISMANDATORY, 'required', null, 'client');
		//$form->addRule('login_or_pwd', _INVALIDFIELDDATA, 'checkParameter', 'text');
		$form->addElement('submit', 'submit_reset_password', _SUBMIT, 'class="flatButton"');

		return $form;
	}

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /
	 * @url GET /login
	 */
	public function loginPage()
	{
		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->putCss("css/pages/login");
		$this->putScript("scripts/login-soft");
		
		$smarty = $this->getSmarty();
		$loginForm = $this->createLoginForm();


		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$loginForm->setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);
		$loginForm->setRequiredNote(_REQUIREDNOTE);
		$loginForm->accept($renderer);
		$smarty->assign('T_LOGIN_FORM', $renderer->toArray());

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

	/**
	 * 
	 *
	 * @url POST /login
	 */
	public function loginAction()
	{
		$form = $this->createLoginForm();

		if ($form->isSubmitted() && $form->validate()) {
		    try {
		        $user = MagesterUserFactory :: factory(trim($form->exportValue('login')));
		        if ($GLOBALS['configuration']['lock_down'] && $user->user['user_type'] != 'administrator') {
		            sC_redirect("index.php?message=".urlencode(_LOCKDOWNONLYADMINISTRATORSCANLOGIN)."&message_type=failure");
		            exit;
		        }
		        $user->login($form->exportValue('password'));
		        if ($form->exportValue('remember')) { //The user asked to remeber login (it is implemented with cookies)
		            $expire = time() + 30 * 86400; //1 month
		            setcookie("cookie_login", $_SESSION['s_login'], $expire);
		            setcookie("cookie_password", $_SESSION['s_password'], $expire);
		        } else {
		            setcookie("cookie_login", '', time() - 3600);
		            setcookie("cookie_password", '', time() - 3600);
		        }
		        // Check if the mobile version of SysClass is required - if so set a session variable accordingly
		        //sC_setMobile();
		        if ($GLOBALS['configuration']['show_license_note'] && $user->user['viewed_license'] == 0) {
		            sC_redirect("index.php?ctg=agreement");
		        } elseif ($_SESSION['login_mode']) {
		            sC_redirect("index.php?ctg=checkout&checkout=1");
		        } else {
		            MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_VISITED, "users_LOGIN" => $user->user['login'], "users_name" => $user->user['name'], "users_surname" => $user->user['surname']));
		            //LoginRedirect($user->user['user_type']);
		            $this->redirect($user->user['user_type']);
		        }
		        exit;
		    } catch (MagesterUserException $e) {
		        if ($GLOBALS['configuration']['activate_ldap']) {
		            if (!extension_loaded('ldap')) {
		                $message = $e->getMessage().'<br/>'._LDAPEXTENSIONNOTLOADED;
		                $message_type = 'failure';
		            } else {
		                $result = sC_checkUserLdap($form->exportValue('login'), $form->exportValue('password'));
		                if ($result) { //The user exists in the LDAP server
		                    $_SESSION['ldap_user_pwd'] = $form->exportValue('password'); //Keep the password temporarily in the session, it will be used in the next step
		                    sC_redirect("index.php?ctg=signup&ldap=1&login=".$form->exportValue('login'));
		                } else {
		                    $message = _LOGINERRORPLEASEMAKESURECAPSLOCKISOFF;
		                    $message_type = 'failure';
		                }
		            }
		        } elseif ($e->getCode() == MagesterUserException :: USER_PENDING) {
		            $message = $e->getMessage();
		            $message_type = 'failure';
		        } elseif ($e->getCode() == MagesterUserException :: USER_INACTIVE) {
		            $message = $e->getMessage();
		            $message_type = 'failure';
		        } else {
		            $message = _LOGINERRORPLEASEMAKESURECAPSLOCKISOFF;
		            $message_type = 'failure';
		        }
		        $form->setConstants(array("login" => $values['login'], "password" => ""));
		    } catch (Exception $e) {
		        $smarty->assign("T_EXCEPTION_TRACE", $e->getTraceAsString());
		        $message = $e->getMessage().' &nbsp;<a href = "javascript:void(0)" onclick = "sC_js_showDivPopup(\''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
		        $message_type = failure;
		    }
		}

	}

	/**
	 * 
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
		                    $message = _MORETHANONEUSERWITHSAMEMAILENTERLOGIN;
		                    $message_type = 'failure';
		                    sC_redirect(''.basename($_SERVER['PHP_SELF']).'?ctg=reset_pwd&message='.urlencode($message).'&message_type='.$message_type);
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
		                $message = _ANEMAILHASBEENSENT;
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
		if (isset($hash) && $this->_checkParameter($hash, 'hex')) {
		    try {
		        $result = $this->_getTableDataFlat("users", "login,autologin,password,user_type", "active=1 and autologin !=''");
		        
		        $autolinks = $result['autologin'];
		        $key = array_search($hash, $autolinks);


		        if ($key !== false) {
		            $user = MagesterUserFactory :: factory($result['login'][$key]);

		            $pattern = $user->user['login']."_".$user->user['timestamp'];
		            $pattern = md5($pattern.G_MD5KEY);

		            if (strcmp($pattern, $hash) == 0) {
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

		                $user_type = $user->user['user_type'];

					    $userTypes = array('administrator', 'professor', 'student');
					    if (in_array($user_type, $userTypes)) {
							$this->redirect($user_type);
						} else {
							$this->redirect("user");
						}
		                exit;
		            }
		        }
		    } catch (MagesterUserException $e) {
		    	var_dump($e);
		    }
		}

	}





	/**
	 *
	 * @url GET /logout
	 * @url POST /logout
	 */
	public function logoutAction()
	{
		$result = $this->logoutUser();

		if ($this->getRequestedFormat() == RestFormat::HTML) {
			$this->putMessage($result['message'], "success");
			header("Location: login");
			exit;
		}

		return $result;
	}
}
