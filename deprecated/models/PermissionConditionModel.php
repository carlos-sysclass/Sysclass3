<?php 
/**
 * @deprecated 3.2.0
 */
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
			'unit_id'				=> 'units_ID'
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
		$this->model("permission/entity")->delete(array(
			'condition_id' => $id
		));
		return parent::deleteItem($id);
	}

	public function getItemsByType($type) {
		$params = array(
			array(
				'ent.type'		=> $type
			), 
			array("operator" => "=")
		);

		$cacheHash = __METHOD__ . "/" . json_encode($params);

		if ($this->cacheable() && $this->hasCache($cacheHash)) {
			// TODO CHECK IF IS THERE A CACHE, AND RETURN IT.
			return $this->getCache($cacheHash);
		}

		$this->addFilter($params[0], $params[1]);

		$items = $this->getItems();
		if ($this->cacheable()) {
			// TODO CACHE RESULTS HERE
			$this->setCache($cacheHash, $items);
		}
		return $items;
	}
}
