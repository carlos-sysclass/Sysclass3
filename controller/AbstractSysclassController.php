<?php 
abstract class AbstractSysclassController extends PageController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	/*
	public static function getMenuOption()
	{
		return false;

	}
	*/
	public function authorize()
	{
		// INJECT HERE SESSION AUTHORIZATION CODE
		return TRUE;
	}

	protected function onThemeRequest()
	{
		$this->setTheme('metronic');

	}
	/*
	protected function setConfig()
	{
		// MOVE THIS TO ADMIN CONFIGURATION!
		$this->putData(
			array(
				'config'	=> array(
					'dev'				=> FALSE,
					'show_locales'		=> FALSE,
					'show_themer'		=> FALSE,
					'skin'				=> "ssra",
					'gen_time'			=> time(0),
					'container_class'	=> 'login'
				)
			)
		);
	}
	*/

	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url GET /
	 * @url GET /login
	 */
	public function loginPage()
	{
		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->putData(array('page_title'	=> 'Home'));

		$this->putScript("jquery.validate");
		$this->putScript("jquery.validate.methods");

		$this->setConfig();

		parent::display('pages/auth/login.tpl');

	}
	/**
	 * 
	 *
	 * @url POST /login
	 */
	public function loginAction()
	{
		$email = $_POST['email'];
		$password = $_POST['password'];

		return $this->loginUser($email, $password);

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
