<?php
namespace Sysclass\Models\Acl;

use Phalcon\DI,
	Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Query;

class RolesUsers extends Model
{
    public function initialize()
    {
        $this->setSource("acl_roles_to_users");

		$this->belongsTo("role_id", "Sysclass\\Models\\Acl\\Role", "id",  array('alias' => 'AclRole', 'reusable' => true));
		$this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User', 'reusable' => true));

    }

    public static function getUsersWithoutARole($role_id, $search = null) {
    	if (is_null($search)) {
            $sql = "SELECT u.* 
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesUsers ru ON (u.id = ru.user_id)
            WHERE (ru.role_id <> :role_id: OR ru.role_id IS NULL)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id));
    	} else {
            $sql = "SELECT u.* 
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesUsers ru ON (u.id = ru.user_id)
            WHERE (ru.role_id <> :role_id: OR ru.role_id IS NULL)
            AND LOWER(CONCAT(u.name, ' ', u.surname)) LIKE LOWER(:query:)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id, 'query' => '%' . $search . '%'));
    	}

		return $users;
    }

    public static function getUsersWithARole($role_id, $search = null) {
        if (is_null($search)) {
            $sql = "SELECT u.* 
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesUsers ru ON (u.id = ru.user_id)
            WHERE (ru.role_id = :role_id:)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id));
        } else {
            $sql = "SELECT u.* 
            FROM Sysclass\\Models\\Users\\User u
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesUsers ru ON (u.id = ru.user_id)
            WHERE (ru.role_id = :role_id:)
            AND LOWER(CONCAT(u.name, ' ', u.surname)) LIKE LOWER(:query:)
            ";
            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id, 'query' => '%' . $search . '%'));
        }

        return $users;
    }


    
}


