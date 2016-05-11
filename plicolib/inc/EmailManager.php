<?php 
require_once(__DIR__ . "/../lib/phpmailer/class.phpmailer.php");
/**
 * @package PlicoLib\Managers
 */
class EmailManager extends PHPMailer {
	public function __construct($exceptions=FALSE)
	{
		parent::__construct($exceptions);

		$plicoLib = PlicoLib::instance();

		if ($plicoLib->get("mail/send/isSMTP")) {
			$this->IsSMTP();
		}
		$this->CharSet = 'UTF-8';		
		// enables SMTP debug information (for testing)
		$this->SMTPDebug  = $plicoLib->get('mail/send/debug');
		$this->SMTPAuth   = $plicoLib->get('mail/send/do_auth');
		// enable SMTP authentication
		$this->Host       = $plicoLib->get('mail/send/host');
		// sets the SMTP server
		$this->Port       = $plicoLib->get('mail/send/port');
		// set the SMTP port for the GMAIL server
		$this->Username   = $plicoLib->get('mail/send/user');
		// SMTP account username
		$this->Password   = $plicoLib->get('mail/send/pass');
		// SMTP account password
		$this->SetFrom($plicoLib->get('mail/send/from/email'), $plicoLib->get('mail/send/from/name'));
		
	}
	public function setSubject($subject)
	{
		$this->Subject = "=?UTF-8?B?".base64_encode($subject)."?=";
	}
	
}
