<?php
class UnitsContentFileModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_units_content_files";
        $this->id_field = null;
        $this->mainTablePrefix = "lcf";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT
                `content_id`,
                `file_id`
            FROM `mod_units_content_files` lcf
		";

        parent::init();

    }
}
