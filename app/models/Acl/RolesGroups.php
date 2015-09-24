<?php
namespace Sysclass\Models\Acl;

use Phalcon\DI,
	Phalcon\Mvc\Model,
	Phalcon\Mvc\Model\Query;

class RolesGroups extends Model
{
    public function initialize()
    {
        $this->setSource("acl_roles_to_groups");

		$this->belongsTo("role_id", "Sysclass\\Models\\Acl\\Role", "id",  array('alias' => 'AclRole', 'reusable' => true));
		$this->belongsTo("user_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'Group', 'reusable' => true));
    }

    public static function getGroupsWithoutARole($role_id, $search = null) {
    	

    	if (is_null($search)) {
            $sql = "SELECT g.* 
            FROM Sysclass\\Models\\Users\\Group g
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesGroups rg ON (g.id = rg.group_id)
            WHERE (rg.role_id <> :role_id: OR rg.role_id IS NULL)
            ";

            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id));
    	} else {
            $sql = "SELECT g.* 
            FROM Sysclass\\Models\\Users\\Group g
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesGroups rg ON (g.id = rg.group_id)
            WHERE (rg.role_id <> :role_id: OR rg.role_id IS NULL)
            AND LOWER(g.name) LIKE LOWER(:query:)
            ";

			$query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id, 'query' => '%' . $search . '%'));
    	}

		return $users;
    }

    public static function getGroupsWithARole($role_id, $search = null) {
        if (is_null($search)) {
            $sql = "SELECT g.* 
            FROM Sysclass\\Models\\Users\\Group g
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesGroups rg ON (g.id = rg.group_id)
            WHERE (rg.role_id = :role_id:)
            ";

            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id));
        } else {
            $sql = "SELECT g.* 
            FROM Sysclass\\Models\\Users\\Group g
            LEFT OUTER JOIN Sysclass\\Models\\Acl\\RolesGroups rg ON (g.id = rg.group_id)
            WHERE (rg.role_id = :role_id:)
            AND LOWER(g.name) LIKE LOWER(:query:)
            ";

            $query = new Query($sql, DI::getDefault());
            $users   = $query->execute(array("role_id" => $role_id, 'query' => '%' . $search . '%'));
        }

        return $users;
    }

}

