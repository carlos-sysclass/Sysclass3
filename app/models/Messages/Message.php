<?php
namespace Sysclass\Models\Messages;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Behavior\SoftDelete;
use Sysclass\Services\Mail\Adapter as MailAdapter;
    
class Message extends Model
{
    public function initialize()
    {
        $this->setSource("mod_messages");

        // MAKE A SOFT DELETABLE TARGET
        $this->addBehavior(
            new SoftDelete(
                [
                    "field" => "deleted",
                    "value" => 1
                ]
            )
        );

        $this->belongsTo(
            "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'From')
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Messages\GroupReceiver",
            "message_id", "group_id",
            "Sysclass\Models\Users\Group",
            "id",
            array('alias' => 'Groups')
        );
        
        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Messages\UserReceiver",
            "message_id", "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'Users')
        );

    }
    
    public function afterCreate(){
    	
    	require_once REAL_PATH . "/vendor/swiftmailer/swiftmailer/lib/swift_required.php";
    	
    	$mail = new MailAdapter();
    	
    	$depinj = \Phalcon\DI::getDefault();
    	$user = $depinj->get("user");
    	
    	if( $this->reply_to ){
	    	$message = Message::findFirstById($this->reply_to);
	    	$dt = $message->toFullArray(array('Groups', 'Users'));
	    	$status = $mail->send(
    		$message->getFrom()->email,
    		"Um nova mensagem recebida. Email automático, não é necessário responder.",
    			"email/" . $this->sysconfig->deploy->environment . "/messages-created.email",
    			true,
    			[
    				'user' => $user,
    				'message' => $this,
    				'from' => $message->getFrom(),
    			],
    			[
    				$message->getFrom()->email => $message->getFrom()->name . " " . $message->getFrom()->surname ,
    			]
    		);
    	}
    }

    public function beforeValidationOnCreate() {
        $this->timestamp = time();

        if (is_null($this->user_id)) {
            $depinj = \Phalcon\DI::getDefault();
            $user = $depinj->get("user");
            $this->user_id = $user->id;
        }
    }

    public static function findTree() {
        $list = self::find(func_get_args());
        var_dump(func_get_args());
        return $list;
        //var_dump($list->toArray());
    }
}
