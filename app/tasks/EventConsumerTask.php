<?php
namespace Sysclass\Tasks;

use Sysclass\Models\Users\User,
    Phalcon\Script\Color,
    Kint;

class EventConsumerTask extends \Phalcon\CLI\Task
{
    public function mainAction(array $params = null)
    {
    	$events = $this->messagebus->receive();

        foreach($events as $event) {
            var_dump($event->toArray());
        }
        exit;
    }

    public function userSignupAction(array $params = null)
    {
        $events = $this->messagebus->receive(false, 'user', 'signup');

        foreach($events as $event) {
            $user_id = $event->data['id'];

            $user = User::findFirstById($user_id);

            // CHECK IF THE USER IS PENDING, GENERATE THE LINK, AND SEND TO USER
            // 
            if ($user->pending == "1" || !$this->configuration->get("signup_must_approve")) {
                $user->generateConfirmHash();
                $user->save();
                //$content = $this->view->render("email/activate.email");
                //
                $status = $this->mail->send(
                    $user->email, 
                    "Confirmação de Matrícula Projeto Itaipu Envolve",
                    "email/" . $this->sysconfig->deploy->environment . "/activate.email",
                    true,
                    array(
                        'activation_link' => 
                            "http://" . $this->sysconfig->deploy->environment . ".sysclass.com/confirm/" . $user->reset_hash
                    )
                );
            }
            $this->messagebus->unqueue($event->_id);

            echo sprintf("processed event user:signup #%s with data %s\n", $event->_id, json_encode($event->data));
        }
    }

    public function processEventsAction(array $params = null)
    {
        $result = $this->messagebus->processEvents();

        foreach($result['messages'] as $message) {
            fwrite(STDERR, Color::{$message['type']}($message['message']));    
        }
    }

}
