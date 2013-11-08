<?php 
class LoginController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!

	public function authorize()
	{
		// Always authorize login page
		return TRUE;

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

		$this->putCss("css/pages/login");

		

		//$this->putScript("jquery.validate");
		//$this->putScript("jquery.validate.methods");

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
