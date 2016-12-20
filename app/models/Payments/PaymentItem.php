<?php
namespace Sysclass\Models\Payments;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\Resource,
    Sysclass\Models\Acl\RolesUsers;

class PaymentItem extends Model
{
    public function initialize()
    {
       $this->setSource("mod_payment_itens");
       
       $this->belongsTo("id_status", "Sysclass\\Models\\Payments\\PaymentStatus", "id",  array('alias' => 'Status', 'reusable' => true));

       $this->belongsTo("payment_id", "Sysclass\\Models\\Payments\\Payment", "id",  array('alias' => 'payment'));

        //$this->skipAttributesOnCreate(array('active'));

        //$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'group'));
        /*
        $this->belongsTo("language_id", "Sysclass\\Models\\I18n\\Language", "id",  array('alias' => 'language'));

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserAvatar", "user_id",  array('alias' => 'avatar'));

        $this->hasMany("id", "Sysclass\\Models\\Users\\Settings", "user_id",  array('alias' => 'settings'));

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Users\\UserAvatar",
            "user_id", "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'Avatars', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesUsers",
            "user_id", "role_id",
            "Sysclass\Models\Acl\Role",
            "id",
            array('alias' => 'UserRoles', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Users\UsersGroups",
            "user_id", "group_id",
            "Sysclass\Models\Users\Group",
            "id",
            array('alias' => 'UserGroups')
        );
        */

    }

    public function listByUser($params) {
        //pega o ID do usuario da sessao
        $di = \Phalcon\DI::getDefault();
        $user = $di->get('user');
        
        $payment = Payment::findFirst(array(
            'conditions' => 'user_id = ?0',
            'bind' => array($user->id)
        ));

        return $payment->getItems();
    }
}
