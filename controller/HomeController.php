<?php 
class HomeController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /
	 * @url GET /home
	 */
	public function homeDashboardPage()
	{
		// DASHBOARD PAGE
		//$currentUser = MagesterUser::checkUserAccess();
		if (is_object($this->current_user)) {
			$user_type = $this->current_user->user['user_type'];
			if (!empty($user_type)) {
				$this->redirect($user_type);
				exit;
			}
		}
		$this->redirect("login");
		exit;
	}

}
