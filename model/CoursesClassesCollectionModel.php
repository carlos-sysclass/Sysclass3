<?php
class CoursesClassesCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_classes";
        $this->id_field = "id";
        $this->mainTablePrefix = "cl";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT id, permission_access_mode, ies_id, area_id, name, description, info, active FROM mod_classes cl";

        parent::init();

    }
}
