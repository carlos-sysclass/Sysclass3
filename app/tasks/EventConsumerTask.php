<?php
namespace Sysclass\Tasks;

use Sysclass\Models\Users\User,
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

        var_dump(count($events));

        foreach($events as $event) {
            $user_id = $event->data['id'];

            $user = User::findFirstById($user_id);

            // CHECK IF THE USER IS PENDING, GENERATE THE LINK, AND SEND TO USER
            // 
            if ($user->pending == "1") {

                $user->generateConfirmHash();
                $user->save();
                //$content = $this->view->render("email/activate.email");

                $status = $this->mail->send(
                    'postmaster@sysclass.com', //$user->email, 
                    "Confirmação de conta Sysclass",
                    "email/activate.email",
                    true,
                    array(
                        'activation_link' => 
                            "http://" . $this->sysconfig->deploy->environment . ".sysclass.com/confirm/" . $user->reset_hash
                    )
                );

                var_dump($status, $user->email);
            }



            //$this->messagebus->unqueue($event->_id);

        }
        exit;
    }
}
