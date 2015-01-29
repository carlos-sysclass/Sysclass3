<?php
class UsersCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "users";
        $this->id_field = "id";
        //$this->fieldsMap = array();

        $this->selectSql =
        "SELECT
            u.id,
            u.login,
            u.name,
            u.surname,
            u.email,
            IFNULL(ut.basic_user_type, u.user_type) as basic_user_type,
            ut.name as user_type,
            u.timestamp as creation_time,
            UNIX_TIMESTAMP(u.last_login) as last_login
        FROM users u
        LEFT JOIN user_types ut ON (u.user_types_ID = ut.id)";

        parent::init();

    }
}
