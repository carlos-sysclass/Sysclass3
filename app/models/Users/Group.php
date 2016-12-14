<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class Group extends Model
{
    protected static $_translateFields = array(
        'name'
    );
    
    public function initialize()
    {
        $this->setSource("groups");

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Acl\RolesGroups",
            "group_id", "role_id",
            "Sysclass\Models\Acl\Role",
            "id",
            array('alias' => 'Roles', 'reusable' => true)
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\Models\Users\UsersGroups",
            "group_id", "user_id",
            "Sysclass\Models\Users\User",
            "id",
            array('alias' => 'Users')
        );

        $this->hasMany(
            "behaviour_allow_messages", 
            "Sysclass\\Models\\Messages\\Group",
            "id",  array('alias' => 'MessageGroup')
        );
    }

    public function afterFetch() {
        $this->definition = json_decode($this->definition);
    }

    public function beforeValidation() {
        if ($this->dynamic == 1) {
            if (!is_null($this->definition)) {
                $this->definition = json_encode($this->definition);
            }
        }
    }
}
