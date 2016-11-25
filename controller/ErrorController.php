<?php
class ErrorController extends AbstractSysclassController
{
	protected $disallowed_extensions = array('css', 'js', 'png', 'jpeg', 'jpg', 'gif');

	public function handle401()
	{
		parent::init("/401", "GET", "html");
		$this->putCss("css/pages/error");

		$this->putItem("error", "401");
		$this->putItem("error_class", "404");
		$this->putItem("error_title", $this->translate->translate('Oops!  You can\'t access this resource.'));
		$this->putItem("error_message", $this->translate->translate('You might want to try to...'));

		parent::display('pages/error/main.tpl');
	}

	public function handle404()
	{
		$ext = end(explode(".", $_SERVER['REQUEST_URI']));
		if (in_array($ext, $this->disallowed_extensions)) {
			header('HTTP/1.0 404 Not Found');
			exit;
		}

		$this->handle503();

	}

	public function handle500()
	{
		parent::init("/500", "GET", "html");
		$this->putCss("css/pages/error");

		$this->putItem("error", "500");
		$this->putItem("error_class", "500");
		$this->putItem("error_title", $this->translate->translate('Oops! But the system halted.'));
		$this->putItem("error_message", $this->translate->translate('We can not find the page you\'re looking for.'));

		parent::display('pages/error/main.tpl');
	}

	public function handle503()
	{
		parent::init("/503", "GET", "html");
		$this->putCss("css/pages/error");

		$this->putItem("error", "503");
		$this->putItem("error_class", "500");
		$this->putItem("error_title", $this->translate->translate('Oops!  Under Development.'));
		$this->putItem("error_message", $this->translate->translate('We can not find the page you\'re looking for.'));

		parent::display('pages/error/main.tpl');
	}
}
