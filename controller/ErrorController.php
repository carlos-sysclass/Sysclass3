<?php 
class ErrorController extends AbstractSysclassController
{

	public function handle401()
	{
		parent::init("/401", "GET", "html");
		$this->putCss("css/pages/error");

		$this->putItem("error", "401");
		$this->putItem("error_class", "500");
		$this->putItem("error_title", self::$t->translate('Oops!  You can\'t access this resource.'));
		$this->putItem("error_message", self::$t->translate('You might want to try to...'));

		parent::display('pages/error/main.tpl');
	}

	public function handle404()
	{
		parent::init("/404", "GET", "html");
		$this->putCss("css/pages/error");

		$this->putItem("error", "404");
		$this->putItem("error_class", "404");
		$this->putItem("error_title", self::$t->translate('Oops!  You\'re lost.'));
		$this->putItem("error_message", self::$t->translate('We can not find the page you\'re looking for.'));

		parent::display('pages/error/main.tpl');
	}

	public function handle500()
	{
		parent::init("/500", "GET", "html");
		$this->putCss("css/pages/error");

		$this->putItem("error", "500");
		$this->putItem("error_class", "500");
		$this->putItem("error_title", self::$t->translate('Oops! But the system halted!'));
		$this->putItem("error_message", self::$t->translate('We can not find the page you\'re looking for.'));

		parent::display('pages/error/main.tpl');
	}

}
