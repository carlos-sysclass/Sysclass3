<?php
class AdvertisingContentFileModel extends AbstractSysclassModel implements ISyncronizableModel {

    public function init()
    {

        $this->table_name = "mod_advertising_content_files";
        $this->id_field = null;
        $this->mainTablePrefix = "acf";
        //$this->fieldsMap = array();

        $this->selectSql = "
			SELECT
                `content_id`,
                `file_id`
            FROM `mod_advertising_content_files` lcf
		";

        parent::init();

    }
}
