<?php
/**
 * @deprecated 3.2.0
 */
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

    public function getEventTypes()
    {
       $sql = sprintf(
           "SELECT
               ID as event_type_id,
               name as event_type_name,
               color as event_type_color
            FROM module_event_types et"
       );

       return $this->db->GetArray($sql);
    }
}
