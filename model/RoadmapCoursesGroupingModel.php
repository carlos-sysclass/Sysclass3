<?php
class RoadmapCoursesGroupingModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_roadmap_courses_grouping";
        $this->id_field = "id";
        $this->mainTablePrefix = "cg";
        //$this->fieldsMap = array();

        $this->selectSql =
            "SELECT cg.`id`, cg.`course_id`, cg.`name`, cg.`start`, cg.`end`, cg.`active` FROM `mod_roadmap_courses_grouping` cg";

        parent::init();

    }
}
