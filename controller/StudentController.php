<?php 
class StudentController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public function authorize()
	{
		if (parent::authorize()) {
			// USER IS LOGGED IN, CHECK FOR TYPE
			$currentUser 	= MagesterUser::checkUserAccess(false, 'student');
			return $this->current_user->user['user_type'] == 'student';
		}
		return false;
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
        //require_once 'control_panel.php';
        parent::display('pages/dashboard/student.tpl');
	}

}
