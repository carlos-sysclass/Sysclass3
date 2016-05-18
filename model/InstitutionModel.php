<?php
class InstitutionModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_institution";
		$this->id_field = "id";
        $this->mainTablePrefix = "i";

		$this->selectSql = "SELECT i.`id`,
            i.`name`,
            i.`formal_name`,
            i.`contact`,
            i.`observations`,
            i.`zip`,
            i.`address`,
            i.`number`,
            i.`address2`,
            i.`city`,
            i.`state`,
            i.`country_code`,
            i.`phone`,
            i.`active`,
            i.`website`,
            i.`facebook`,
            i.`logo_id` as logo_id,
            db.`id` as 'logo#id',
            db.`url` as 'logo#url'
        FROM `mod_institution` i
        LEFT JOIN mod_dropbox db ON (i.logo_id = db.id)";

 		parent::init();
	}
}
