<?php
/**
 * @deprecated 3.2.0
 */
class EventTypesCollectionModel extends AbstractSysclassModel implements ISyncronizableModel
{
    public function init()
    {
        $this->table_name = "module_event_types";
        $this->id_field = "id";
        $this->mainTablePrefix = "et";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, color FROM module_event_types et";

        parent::init();
    }
}