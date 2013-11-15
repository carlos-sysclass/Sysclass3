<?php 
class HomeController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /home
	 */
	public function homeDashboardPage()
	{
		// DASHBOARD PAGE
		$currentUser = MagesterUser::checkUserAccess();
		if (is_object($currentUser)) {
			$user_type = $currentUser->user['user_type'];
			$this->redirect($user_type);
		} else {
			$this->redirect($user_type, self::$t->translate("message"), "error");
		}
		exit;
	}

}
