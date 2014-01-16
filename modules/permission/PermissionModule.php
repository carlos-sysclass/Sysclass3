<?php 
class PermissionModule extends SysclassModule
{
	/**
	 * Module Entry Point
	 *
	 * @url GET /combo/items
	 */
	public function comboItensAction()
	{
		$q = $_GET['q'];
		// SEARCH BY $q;
		// USER IN LESSON
		// LEFT-SIDE
		/*
		$cond = array(
			'condition_id'		=> UsersModule::PERMISSION_IN_LESSON,
			'module'			=> "users", // IMPLEMENTS IPermissionChecker
			'data'				=> "189"
		);
		*/
		//$leftModule = $this->module($cond['module']);
		//$leftModule->checkCondition($cond['condition_id'], $cond['data']);
		//$leftModule->checkConditionByEntityId(2, $cond['condition_id'], $cond['data']);
		//$leftModule->getConditionText($cond['condition_id'], $cond['data']);

		$modules = $this->getModules("IPermissionChecker");
		$permissions = array();
		$results = array();
		foreach ($modules as $key => $module) {
			$permissions[$key] = $module->getPermissions();
			$groupItem = array(
                'text'  => $module->getName(),
            	'children'  => array()
            );
			foreach($permissions[$key] as $perm_id => $perm_item) {
				$groupItem['children'][] = array(
					'id'	=> $key."::".$perm_id,
					'name'	=> $perm_item['name']
				);
			}
			$results[] = $groupItem;
		}
		return $results;
	}
	/**
	 * Module Entry Point
	 *
	 * @url GET /get/options/:condition_id
	 */
	public function getConditionOptions($condition_id) {
		//$this->display("dialogs/add.tpl");
		list($module, $condition_id) = explode("::", $condition_id);

		$modules = $this->getModules("IPermissionChecker");
		if (array_key_exists($module, $modules)) {
			$result = $modules[$module]->getPermissionForm($condition_id);
			if ($result) {
				echo $result;
			} else {
				return $this->invalidRequestError();
			}
		} else {
			return $this->invalidRequestError();
		}
	}
}

