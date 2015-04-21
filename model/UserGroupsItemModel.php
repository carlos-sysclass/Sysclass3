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
            "SELECT
                groups_ID as group_id,
                users_LOGIN as user_login,
                u.name,
                u.surname,
                u.login,
                u.email
            FROM users_to_groups ug
            LEFT JOIN users u ON (u.login = ug.users_LOGIN)
            WHERE groups_ID = %d",
            $group_id
        );

        return $this->db->GetArray($sql);
    }


    public function switchUserInGroup($group_id, $user_login) {
        $sql = "SELECT COUNT(*) FROM users_to_groups WHERE groups_ID = %d AND users_LOGIN = '%s'";
        $checkSql = sprintf(
            $sql,
            $group_id,
            $user_login
        );

        $exists = $this->db->GetOne($checkSql);
        $exists = ($exists == 1);

        if ($exists) {
            $sql = sprintf("DELETE FROM users_to_groups WHERE groups_ID = %d AND users_LOGIN = '%s'", $group_id, $user_login);
            $result = -1;
        } else {
            $sql = sprintf("INSERT INTO users_to_groups (groups_ID, users_LOGIN) VALUES (%d, '%s')", $group_id, $user_login);
            $result = 1;
        }
        $this->db->Execute($sql);
        return $result;
    }
}