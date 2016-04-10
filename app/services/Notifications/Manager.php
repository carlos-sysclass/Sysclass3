<?php 
namespace Sysclass\Services\Notifications;

use Phalcon\Mvc\User\Component,
	Sysclass\Models\Users\User,
    Sysclass\Models\Notifications\User as UserNotification;

class Manager extends Component
{
    public function createForUser(User $user, $message, $type = "info", array $link = null, $stick = false) {

        $notifyUser = new UserNotification();

        $notifyUser->user_id = $user->id;
        $notifyUser->message = $message;
        $notifyUser->type = $type;
        if (is_array($link)) {
            $notifyUser->link_text = $link['text'];
            $notifyUser->link_href = $link['link'];
        }
        
        $notifyUser->stick = $stick;

        $notifyUser->create();

        return true;
    }
}