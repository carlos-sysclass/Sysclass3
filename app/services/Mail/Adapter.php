<?php 
namespace Sysclass\Services\Mail;

use Phalcon\Mvc\User\Component;

class Adapter extends Component
{

    protected $_transport;
    protected $_message;
    protected $_mailer;

	  protected function clearMessage() {
		  $this->_message = null;
    }

    protected function getMessage() {
    	if (is_null($this->_message)) {
        $this->_message = \Swift_Message::newInstance();
    	}
    	return $this->_message;
    }

    protected function getMailer() {
		  if (is_null($this->_transport)) {
  			$mailSettings = $this->environment->mail;

  			$this->_transport = \Swift_SmtpTransport::newInstance(
  				$mailSettings->smtp_server,
  				$mailSettings->smtp_port,
  				$mailSettings->smtp_security
  			)
        ->setUsername($mailSettings->smtp_username)
    		->setPassword($mailSettings->smtp_password);

        $this->_mailer = \Swift_Mailer::newInstance($this->_transport);
	  	}

	  	return $this->_mailer;
    }

    public function send($to, $subject, $template, $render = false, $vars = null, $reply_to = null) {
  		//Settings
  		$mailSettings = $this->environment->mail;
  		if ($render) {
        if (is_array($vars)) {
          $this->view->setVars($vars);
        }
  			$template = $this->view->render($template);
  		}

  		//$template = $this->getTemplate($name, $params);
      //echo $template;
  		// Create the message
  		$this->getMessage()
    			->setSubject($subject)
    			->setTo($to)
    			->setFrom(array(
    				$mailSettings->from_email => $mailSettings->from_name
    			))
    			->setBody($template, 'text/html');

      if (!is_null($reply_to)) {
        $this->getMessage()->setReplyTo($reply_to);
      }

  	  	// Create the Mailer using your created Transport

      $status = $this->getMailer()->send($this->getMessage());

  		$this->clearMessage();

  		return $status;
    }

    public function attachInline($path) {
      $path = REAL_PATH . "/www/assets/sysclass.itaipu/img/logo.png";

    	$cid = $this->getMessage()
    		->setContentType("text/html")
    		->embed(\Swift_EmbeddedFile::fromPath($path));

    	return $cid;
    }
}