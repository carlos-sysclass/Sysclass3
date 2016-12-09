<?php
namespace Sysclass\Models\Chat;

use Plico\Mvc\Model;

class Message extends Model
{
    public function initialize()
    {
        $this->setSource("mod_chat_messages");

        $this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'From', 'reusable' => true));
        //$this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));

        $this->belongsTo("chat_id", "Sysclass\\Models\\Chat\\Chat", "id",  array('alias' => 'Chat', 'reusable' => false));
    }

    public function toFullArray($manyAliases = null, $itemData = null, $extended = false) {
    	$user = $this->getDI()->get('user');
    	$result = parent::toFullArray($manyAliases, $itemData, $extended);

  		$result['mine'] = $user->id == $result['user_id'];

    	return $result;
    }

}
