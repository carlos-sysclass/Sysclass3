<?php 
/**
 * @package PlicoLib\Controllers
 * @description Authentication back-end
 */
class AuthController extends PageController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public static function getMenuOption()
	{
		return false;

	}

	public function authorize()
	{
		return TRUE;

	}

	protected function onThemeRequest()
	{
		$this->setTheme('admin');

	}

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

		$this->putScript("js/jquery.validate");
		$this->putScript("js/jquery.validate.methods");

		$this->setConfig();

		parent::display('pages/auth/login.tpl');

	}
	/**
	 * Returns a JSON authentication object
	 *
	 * @url POST /login
	 * @param email string form Your username or email 
	 * @param password string form Your account password
	 * @api
	 */
	public function loginAction()
	{
		$email = $_POST['email'];
		$password = $_POST['password'];

		return $this->loginUser($email, $password);

	}

	/**
	 *
	 * @url POST /logout
	 */
	public function doLogoutAction()
	{
		$result = $this->logoutUser();

		if ($this->getRequestedFormat() == RestFormat::HTML) {
			$this->putMessage($result['message'], "success");
			header("Location: login");
			exit;
		}

		return $result;
	}
	/**
	 *
	 * @url GET /logout
	 * @param token string query Your authentication token
	 * @api
	 */
	public function logoutAction()
	{
		return $this->doLogoutAction();

	}
}
