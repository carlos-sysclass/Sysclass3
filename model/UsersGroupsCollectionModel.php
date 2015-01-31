<?php
class UsersGroupsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "groups";
        $this->id_field = "id";
        $this->mainTablePrefix = "g";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, description, active FROM groups g";

        parent::init();

    }
}
