<?php
namespace Sysclass\Tasks;

use Kint;

class MailTestTask extends \Phalcon\CLI\Task
{
    public function mainAction(array $params = null)
    {
    	//Kint::dump($params);

    	$defaults = array(
    		'andre@kucaniz.com'
    	);

    	if (is_array($params)) {
	    	$params = $params + $defaults;
    	}

    	//$content = $this->view->render("email/activate.email");

		$status = $this->mail->send(
			$params[0],
			"Test Email",
			"email/activate.email",
            true
		);

        if ($status) {
            exit(0);
        } else {
            exit(1);
        }
    }
}
