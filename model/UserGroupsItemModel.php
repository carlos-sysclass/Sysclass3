<?php
class UserGroupsItemModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "groups";
        $this->id_field = "id";
        $this->mainTablePrefix = "g";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, description, active, `behaviour_allow_messages` FROM groups g";

        parent::init();

    }
    public function getUsersInGroup($group_id) {
        $sql = sprintf(
            'SELECT
                ug.group_id,
                ug.user_id,
                u.login as user_login,
                u.name,
                u.surname,
                u.login,
                u.email
            FROM users_to_groups ug
            LEFT JOIN users u ON (u.id = ug.user_id)
            WHERE ug.group_id = %1$d
            /*
            UNION
            SELECT
                u.group_id,
                u.id as user_id,
                u.login as user_login,
                u.name,
                u.surname,
                u.login,
                u.email
            FROM users u 
            WHERE group_id = %1$d*/',
            $group_id
        );

        return $this->db->GetArray($sql);
    }


    public function switchUserInGroup($group_id, $user_id) {
        $sql = "SELECT COUNT(*) FROM users_to_groups WHERE group_id = %d AND user_id = '%s'";
        $checkSql = sprintf(
            $sql,
            $group_id,
            $user_id
        );

        $exists = $this->db->GetOne($checkSql);
        $exists = ($exists == 1);

        if ($exists) {
            $sql = sprintf("DELETE FROM users_to_groups WHERE group_id = %d AND user_id = %d", $group_id, $user_id);
            $result = -1;
        } else {
            $sql = sprintf("INSERT INTO users_to_groups (group_id, user_id) VALUES (%d, %d)", $group_id, $user_id);
            $result = 1;
        }
        $this->db->Execute($sql);
        return $result;
    }
}
