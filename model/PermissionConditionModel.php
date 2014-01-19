<?php 
class PermissionConditionModel extends AbstractSysclassModel implements ISyncronizableModel {

	public function init()
	{
		$this->table_name = "mod_permission_conditions";
		$this->id_field = "id";
		$this->mainTablePrefix = "perm_cond";
		/*
		$this->mapFields = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN',
			'lesson_id'				=> 'lessons_ID'
		);
		*/
		$this->selectSql = sprintf('
			SELECT 
				%1$s.`id`, %1$s.`condition_id`, %1$s.`data`,
				ent.type, ent.entity_id
				FROM `mod_permission_conditions` %1$s
				LEFT JOIN mod_permission_entities ent ON (%1$s.`id` = ent.condition_id)
			', $this->mainTablePrefix);

		parent::init();

	}

	public function deleteItem($id) {
		$this->model("permission/entity")->debug()->delete(array(
			'condition_id' => $id
		));
		return parent::deleteItem($id);
	}

	public function getItemsByType($type) {
		/*
		$filter = $this->createFilter(
			$this->mainTablePrefix . '.id', 
			"ent.condition_id", 
			array("operator" => "=", "quote" => false)
		);
		$this->createJoin('LEFT', "mod_permission_entities ent", $filter);
		*/

		$this->addFilter(
			array(
				'ent.type'		=> $type
			), 
			array("operator" => "=")
		);

		$items = $this->getItems($id);
		return $items;
	}
}
