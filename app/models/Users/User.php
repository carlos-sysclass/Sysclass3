<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\RolesUsers;

class User extends Model
{
    public function initialize()
    {
        $this->setSource("users");

         //$this->skipAttributesOnCreate(array('active'));

        //$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'group'));
        //
        $this->belongsTo("language_id", "Sysclass\\Models\\I18n\\Language", "id",  array('alias' => 'language'));

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserAvatar", "user_id",  array('alias' => 'avatar'));

        $this->hasMany("id", "Sysclass\\Models\\Users\\Settings", "user_id",  array('alias' => 'settings'));

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Enrollments\\Course",
            "user_id", "course_id",
            "Sysclass\\Models\\Courses\\Course",
            "id",
            array('alias' => 'Courses')
        );

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

    }



    public function getType() {
        return $this->user_type;
    }

    public function getRoles() {
        $userRoles = $this->getUserRoles();

        $roles = $userRoles->toArray();

        $groups = $this->getUserGroups();

        foreach($groups as $group) {
            $groupRoles = $group->getRoles();
            $roles = array_merge($roles, $groupRoles->toArray());
            $roles = array_map("unserialize", array_unique(array_map("serialize", $roles)));
        }
        return $roles;
    }

    public function getDashboards() {
        $roles = $this->getRoles();
        $dashboards = array_map("strtolower", \array_column($roles, "name"));

        return $dashboards;
    }

    public function createNewLogin() {
        // SANITIZE DATA
        $depinject = \Phalcon\DI::getDefault();
        $stringHelper = $depinject->get("stringsHelper");
        
        $name = $this->name;
        $surname = $this->surname;

        $name       = trim($stringHelper->clearAccents($name));
        $surname    = trim($stringHelper->clearAccents($surname));

        if (strlen($name) > 0 && (strlen($surname) > 0)) {
            $firstname = explode(' ', $name);
            $firstname = $firstname[0];

            $lastname = explode(' ', $surname);

            if (strlen($lastname[count($lastname) - 1]) > 0) {
                $lastname = $lastname[count($lastname) - 1];
            } elseif (strlen($lastname[count($lastname) - 2]) > 0) {
                $lastname = $lastname[count($lastname) - 2];
            } elseif (strlen($lastname[count($lastname) - 3]) > 0) {
                $lastname = $lastname[count($lastname) - 3];
            } else {
                return false;
            }
        }
        $login = strtolower($firstname) . '.' .  strtolower($lastname);

        // CHECK LOGIN EXISTENCE AND ADD SEQUENCIAL NUMBERS IF NECESSARY
        $originalLogin = $login;
        $exists = -1;
        $i = 1;
        while ($exists <> 0) {
            $exists = self::count(array(
                'conditions' => "login = ?0",
                'bind' => array($login)
                //'bind' => array('admin')
            ));

            $login = $originalLogin . mt_rand(0, 1000);
        }
        return $this->login = $login;

    }

    public function createRandomPass($len = 8) {
        return substr(md5(rand().rand()), 0, $len);
    }

}
