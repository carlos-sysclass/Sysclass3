<?php
class LessonsContentModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_lessons_content";
        $this->id_field = "id";
        $this->mainTablePrefix = "lc";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT `id`,
                `lesson_id`,
                `type`,
                `title`,
                `info`,
                `order`,
                `active`
            FROM `mod_lessons_content` lc
		";

        parent::init();

    }
}
