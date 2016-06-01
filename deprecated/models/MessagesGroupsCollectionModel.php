<?php
/**
 * @deprecated 3.2.0
 */
class MessagesGroupsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_messages_groups";
        $this->id_field = "id";
        $this->mainTablePrefix = "mg";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, icon FROM mod_messages_groups mg";

        parent::init();

    }
}
