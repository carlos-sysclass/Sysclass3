<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Resultset,
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
        $this->definition = json_decode($this->definition, true);
    }

    public function beforeValidation() {
        if ($this->dynamic == 1) {
            if (!is_null($this->definition)) {
                $this->definition = json_encode($this->definition);
            }
        }
    }

    public function hasUser(User $user) {
        if ($this->dynamic == 1) {
            $parsed = $this->getDI()->get('sqlParser')->parse($this->definition);

            $parsed['columns'] = "id";
            $parsed['hydration'] = Resultset::HYDRATE_ARRAYS;

            $result = User::find($parsed);

            $users_ids = array_column($result->toArray(), 'id');

            return in_array($user->id, $users_ids);
        } else {
            $count = $this->getUsers([
                'conditions' => "user_id = ?0",
                'bind' => [$user->id]
            ]);
            return $count->count() > 0;
        }
    }
}
