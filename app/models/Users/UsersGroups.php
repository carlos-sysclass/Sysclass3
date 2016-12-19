<?php
namespace Sysclass\Models\Users;

use Phalcon\Mvc\Model,
	Phalcon\DI,
	Phalcon\Mvc\Model\Query;

class UsersGroups extends Model
{
    public function initialize()
    {
        $this->setSource("users_to_groups");

		$this->belongsTo("user_id", "Sysclass\\Models\\Users\\User", "id",  array('alias' => 'User'));
		$this->belongsTo("group_id", "Sysclass\\Models\\Users\\Group", "id",  array('alias' => 'Group', 'reusable' => true));
    }

    public static function findFirstById($parameters = NULL) {
        if (is_numeric($parameters)) {
            return parent::findFirstById($parameters);
        } else {
            $bind = explode("/", $parameters);
            return self::findFirst([
                'conditions' => "group_id = ?0 AND user_id = ?1",
                'bind' => $bind
            ]);
        }
    }


    public static function findNonGroupUsers($params) {
    	$args = $params['args'];
    	$group_id = $args['group_id'];

        $where = [];
        $params = ['group_id' => $group_id];
        $subsql = "SELECT ug.user_id FROM Sysclass\\Models\\Users\\UsersGroups ug WHERE ug.group_id = :group_id:";

        $where[] = sprintf("u.id NOT IN (%s)", $subsql);

        $sql = "SELECT u.* FROM Sysclass\\Models\\Users\\User u";

        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $query = new Query($sql, DI::getDefault());
        $users   = $query->execute($params);

        //var_dump($users->toArray());

        return $users;
    }

    public static function findGroupUsers($params) {
    	$args = $params['args'];
    	$group_id = $args['group_id'];

        $where = [];
        $params = ['group_id' => $group_id];
        $subsql = "SELECT ug.user_id FROM Sysclass\\Models\\Users\\UsersGroups ug WHERE ug.group_id = :group_id:";

        $where[] = sprintf("u.id IN (%s)", $subsql);

        $sql = "SELECT u.* FROM Sysclass\\Models\\Users\\User u";

        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $query = new Query($sql, DI::getDefault());
        $users   = $query->execute($params);

        //var_dump($users->toArray());

        return $users;
    }

    

}

