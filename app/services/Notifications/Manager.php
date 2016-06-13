<?php 
namespace Sysclass\Services\Notifications;

use Phalcon\Mvc\User\Component,
	Sysclass\Models\Users\User,
    Sysclass\Models\Notifications\User as UserNotification;

class Manager extends Component
{
    public function createForUser(User $user, $message, $type = "info", array $link = null, $stick = false, $unique_id = null) {

        $notifyUser = new UserNotification();

        if (!is_null($unique_id)) {
            // CHECK IF THE NOTIFICATION EXISTS
            $exists = UserNotification::findFirst(array(
                'conditions' => 'unique_id = ?0 AND user_id = ?1',
                'bind' => array($unique_id, $user->id)
            ));

            if ($exists) {
                return true;
            }
            $notifyUser->unique_id = $unique_id;

        } else {
            $random = new \Phalcon\Security\Random();
            $notifyUser->unique_id = $random->uuid();
        }

        $notifyUser->user_id = $user->id;
        $notifyUser->message = $message;
        $notifyUser->type = $type;
        if (is_array($link)) {
            $notifyUser->link_text = $link['text'];
            $notifyUser->link_href = $link['link'];
        }
        
        $notifyUser->stick = $stick;

        var_dump($notifyUser->create());

        return true;
    }
}