<?php
/**
 * @deprecated 3.0.0.18 Use CourseModel Class for same functionality
 */
class CoursesCollectionModel extends AbstractSysclassModel implements ISyncronizableCollection {

    public function init()
    {
        $this->table_name = "mod_courses";
        $this->id_field = "id";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT 
            `id`,
            `area_id`,
            `coordinator_id`,
            `name`,
            `description`,
            `objectives`,
            `goals`,
            `duration_units`,
            `duration_type`,
            `price_total`,
            `price_step_units`,
            `price_step_type`,
            `archive`,
            `created`,
            `active`,
            `mod_coursescol`
        FROM `mod_courses`";

        parent::init();

    }
}
