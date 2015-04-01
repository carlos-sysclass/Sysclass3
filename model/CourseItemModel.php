<?php
/**
 * @deprecated
 */
class CourseItemModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {
        $this->table_name = "mod_courses";
        $this->id_field = "id";
        //$this->fieldsMap = array();

        $this->selectSql = "SELECT `id`,
            `permission_access_mode`,
            `ies_id`,
            `name`,
            `name` as course_name,
            `active`,
            `archive`,
            `created`,
            `start_date`,
            `end_date`,
            `options`,
            `metadata`,
            `description`,
            `info`,
            `price`,
            `currency`,
            `enable_registration`,
            `price_registration`,
            `enable_presencial`,
            `price_presencial`,
            `enable_web`,
            `price_web`,
            `show_catalog`,
            `publish`,
            `directions_ID`,
            `languages_NAME`,
            `reset`,
            `certificate_expiration`,
            `max_users`,
            `rules`,
            `terms`,
            `instance_source`,
            `supervisor_LOGIN`
        FROM `mod_courses`";

        parent::init();

    }


}
