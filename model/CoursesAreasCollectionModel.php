<?php
class CoursesAreasCollectionModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_areas";
        $this->id_field = "id";
        $this->mainTablePrefix = "a";
        //$this->fieldsMap = array();

        $this->selectSql =
            "SELECT
                a.id, a.permission_access_mode, a.name, a.coordinator_id, u.name as coordinator, a.description, a.info, a.active,
                COUNT(c.id) as courses_count
            FROM mod_areas a
            LEFT JOIN users u ON (a.coordinator_id = u.id)
            LEFT JOIN mod_courses c ON (a.id = c.area_id)";

        $this->group_by = array("a.id");

        parent::init();

    }
}
