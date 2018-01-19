<?php
namespace Sysclass\Models\Messages;

use Phalcon\Mvc\Model;
use Sysclass\Services\Mail\Adapter as MailAdapter;


class UserReceiver extends Model
{
    public function initialize()
    {
        $this->setSource("mod_messages_to_users");

		$this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'Group', 'reusable' => true));

		$this->belongsTo("message_id", "Sysclass\\Models\\Messages\\Message", "id",  array('alias' => 'Message', 'reusable' => true));
		
    }
    
    
    public function afterCreate(){
    	
    	require_once REAL_PATH . "/vendor/swiftmailer/swiftmailer/lib/swift_required.php";
    	
    	$mail = new MailAdapter();
    	 
    	$message = Message::findFirstById($this->message_id);
    	
    	$users = $message->getUsers();
    	$from = $message->getFrom();
    	foreach ($users as $user) {
    		$mail->send(
    			$message->getFrom()->email,
    			"Um nova mensagem recebida. Email automático, não é necessário responder.",
    			"email/" . $this->sysconfig->deploy->environment . "/messages-created.email",
    			true,
    			['user' => $user,'message' => $this,'from' => $message->getFrom() ],
    			[$message->getFrom()->email => $message->getFrom()->name . " " . $message->getFrom()->surname ]
    		);
    	}
    	
    }

}

