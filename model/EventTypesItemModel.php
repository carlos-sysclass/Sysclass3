<?php
class EventTypesItemModel extends AbstractSysclassModel implements ISyncronizableModel
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

    public function getEvents()
    {
       $sql = sprintf(
           "SELECT
               ID as event_type_id
            FROM module_event_types et"
       );

       return $this->db->GetArray($sql);
    }
}
