<?php
namespace Sysclass\Models\Users;

use Plico\Mvc\Model,
    Sysclass\Models\Acl\Resource,
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

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Enrollments\\CourseUsers",
            "user_id",
            array('alias' => 'UserCourses')
        );

        $this->hasOne("id", "Sysclass\\Models\\Users\\UserCurriculum", "id",  array('alias' => 'curriculum'));

        $this->hasMany(
            "id",
            "Sysclass\\Models\\Users\\UserPasswordRequest",
            "user_id",
            array(
                'alias' => 'PasswordRequests',
                'conditions' => 'valid_until > ?0 AND active = 1',
                'bind' => (new \DateTime('now'))->format('Y-m-d H:i:s')
            )
        );

        $this->hasManyToMany(
            "id",
            "Sysclass\\Models\\Enrollments\\CourseUsers",
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

    public function beforeValidationOnCreate() {
        if (empty($this->login)) {
            $this->login = $this->createNewLogin();
        }
        if (empty($this->password)) {
            $password = $this->createRandomPass();
            // ENCRYPT PASS
            $this->password = $this->getDi()->get('security')->hash($password);
        }
        if (is_null($this->websocket_key)) {
            $websocket_key = $this->createRandomPass();
            $this->websocket_key = $this->getDi()->get('security')->hash($websocket_key);
        }
    }

    public static function specialFind($filters) {
        $users = array();
        foreach($filters as $filter => $value) {
            switch($filter) {
                case "permission_id" : {
                    // LOAD ALL USERS HAVING THE DEFINED(S) PERMISSSION(S)
                    $users = array_merge($users, self::findByPermissionId($value));
                    break;
                }
            }
        }
        $users = array_map("unserialize", array_unique(array_map("serialize", $users)));
        return $users;
    }

    protected static function findByPermissionId($permission_id) {
        $resource = Resource::findFirstById($permission_id);
        $roles = $resource->getRoles();

        $users = array();

        foreach($roles as $role) {
            // GET DIRECT USERS
            $roleUsers = $role->getUsers();
            if ($roleUsers) {
                $users = array_merge($users, $roleUsers->toArray());
            }

            foreach($role -> getGroups () as $group) {
                
                $groupUsers = $role->getUsers();
                if ($groupUsers) {
                    $users = array_merge($users, $groupUsers->toArray());
                }
            };
        }

        $users = array_map("unserialize", array_unique(array_map("serialize", $users)));

        return $users;
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

    public function hasRole($role) {
        $roles = $this->getRoles();
        $rolesNames = array_map('strtolower', array_column($roles, 'name'));
        return in_array(strtolower($role), $rolesNames);
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

    public function generateConfirmHash() {
        $this->reset_hash = $this->createRandomPass(16);
    }


}
