<?php 
/**
 * @deprecated 3.2.0
 */
class PermissionEntityModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_permission_entities";
		$this->id_field = 'id';
		//$this->mainTablePrefix = "ent"
		/*
		$this->mapFields = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN',
			'lesson_id'				=> 'lessons_ID'
		);
		*/
		$this->selectSql = "SELECT `id`, `type`, `entity_id`, `condition_id` FROM `mod_permission_entities`";

		parent::init();

	}
}
