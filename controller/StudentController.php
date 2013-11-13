<?php 
class StudentController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public function authorize()
	{
		$smarty = $this->getSmarty();
		// INJECT HERE SESSION AUTHORIZATION CODE
		try {
		    $currentUser 	= MagesterUser::checkUserAccess(false, 'student');
		    if ($currentUser->user['user_type'] == 'administrator') {
		        throw new Exception(_ADMINISTRATORCANNOTACCESSLESSONPAGE, MagesterUserException::RESTRICTED_USER_TYPE);
		    }
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

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /student
	 * @url GET /student/dashboard
	 */
	public function studentDashboardPage()
	{
		// DASHBOARD PAGE
		var_dump(1);
        //require_once 'control_panel.php';
        parent::display('pages/dashboard/student.tpl');
	}

}
