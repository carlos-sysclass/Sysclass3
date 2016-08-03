<?php
/**
 * @deprecated 3.2.0
 */
class EventsCollectionModel extends AbstractSysclassModel implements ISyncronizableModel
{
    public function init()
    {
        $this->table_name = "module_events";
        $this->id_field = "id";
        $this->mainTablePrefix = "e";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT e.id, e.name, e.description, e.date, e.type_id, et.name as event_type_name, et.color as event_type_color FROM module_events e LEFT JOIN module_event_types et ON e.type_id = et.id";

        parent::init();
    }
}