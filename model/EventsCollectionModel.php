<?php
class EventsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel
{
    public function init()
    {
        $this->table_name = "module_events";
        $this->id_field = "id";
        $this->mainTablePrefix = "e";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, name, description, date, type_id FROM module_events e";

        parent::init();
    }
}