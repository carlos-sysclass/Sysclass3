<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("users");

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserAvatar", "user_id",  array('alias' => 'avatar'));

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Users\\UserAvatar",
            "user_id", "file_id",
            "Sysclass\\Models\\Dropbox\\File",
            "id",
            array('alias' => 'Avatars', 'reusable' => true)
        );

    }

    public function getType() {
        return $this->user_type;
    }

}
