<?php 
class FrontendController extends PageController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!

	public function authorize()
	{
		// Always authorize frontend page
		return TRUE;
	}

	protected function onThemeRequest()
	{
		$this->setTheme('sysclass.frontend');
	}

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /
	 */
	public function frontendPage()
	{
		parent::display('login.tpl');
	}


}
