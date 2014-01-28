<?php 
class InstitutionInstitutionModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_institution";
		$this->id_field = "id";
		$this->mapFields = array();

		$this->selectSql = "SELECT `id`, `permission_access_mode`, `name`, `formal_name`, `contact`, `observations`, `zip`, `address`, `number`, `address2`, `city`, `state`, `country_code`, `phone`, `active` FROM `mod_institution`";
		//`lessons_ID`, `classe_id`, 
 		parent::init();

	}
}
