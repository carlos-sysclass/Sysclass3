<?php
namespace Sysclass\Models\Leads;

use Plico\Mvc\Model;

class Lead extends Model
{
    public function initialize()
    {
        $this->setSource("users");
    }

    public function beforeValidationOnCreate() {
        parent::beforeValidationOnCreate();
        $this->renewAccess();
        $this->addToDefaultGroup();
    }
    
    /*
    public function addToDefaultGroup() {
        $exists = UsersGroups::count([
            'conditions' => 'user_id = ?0 AND group_id = 2',
            'bind' => [$this->id]
        ]);

        if ($exists == 0) {
            $user_group = new UsersGroups();
            $user_group->user_id = $this->id;
            $user_group->group_id = 2;
            $user_group->save();
        }
    }
    
    public function renewAccess() {
        $expires = new \DateTime("now");
        $expires->add(new \DateInterval("P1D"));
        $this->expires_at = $expires->format("Y-m-d H:i:s");

        // GENERATE NOW AUTOLOGIN HASH
        $this->autologin = $this->createRandomPass(32);
    }
    */
}
