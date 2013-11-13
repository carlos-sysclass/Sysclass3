<?php 
class AdministratorController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public function authorize()
	{
		// INJECT HERE SESSION AUTHORIZATION CODE
		try {
		    $currentUser = MagesterUser :: checkUserAccess('administrator');
		    $smarty = $this->getSmarty();
		    $smarty->assign("T_CURRENT_USER", $currentUser);
		} catch (Exception $e) {
		    if ($e->getCode() == MagesterUserException :: USER_NOT_LOGGED_IN) {
		        setcookie('c_request', http_build_query($_GET), time() + 300);
		    }
		    $this->redirect("login", $e->getMessage() . ' (' . $e->getCode() . ')', "failure");
		    exit;
		}
		return TRUE;
	}

	protected function startAdministratorEnviroment($request) {
		$smarty = $this->getSmarty();

		//include "new_sidebar.php";
		//var_dump($GLOBALS['currentTheme']->options['sidebar_interface']);
		($request == 0) ? $ctg = "control_panel" : $ctg = $request;

		$smarty->assign("T_CTG", $ctg); //As soon as we derive the current ctg, assign it to smarty.
		$smarty->assign("T_OP", isset($_GET['op']) ? $_GET['op'] : false);

		//Create shorthands for user type, to avoid long variable names
		$_student_ = $_professor_ = $_admin_ = 0;
		if ((isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] == 'student') || (!isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_type'] == 'student')) {
		    $_student_ = 1;
		} elseif ((isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] == 'professor') || (!isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_type'] == 'professor')) {
		    $_professor_ = 1;
		} else {
		    $_admin_ = 1;
		}
		$smarty->assign("_student_", $_student_);
		$smarty->assign("_professor_", $_professor_);
		$smarty->assign("_admin_", $_admin_);

		return $ctg;
	}
	/**
	 * Create login and reset password forms
	 *
	 * @url GET /administrator
	 * @url GET /administrator/:request
	 */
	public function administratorPage($request)
	{
		$request = $this->startAdministratorEnviroment($request);
		var_dump($request);
		if ($request == 'control_panel') {
        	//require_once 'control_panel.php';
        	parent::display('pages/dashboard/administrator.tpl');
        }

	}

}
