<?php
namespace Sysclass\Modules\Permission;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Acl\Resource as AclResource;
/**
 * Manage and control the system permission system
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/permission")
 */
class PermissionModule extends \SysclassModule implements \IBlockProvider
{
    protected function getDatatableItemOptions() {
        // TODO: THINK ABOUT MOVING THIS TO config.yml
        if ($this->request->hasQuery('block')) {
        	return array(
                'check'  => array(
                    //'icon'  		=> 'icon-check',
                    //'link'  		=> $baseLink . "block/" . $item['id'],
                    //'text' 			=> $this->translate->translate('Disabled'),
                    //'class' 		=> 'btn-sm btn-danger',
                    'type'			=> 'switch',
                    //'state'			=> 'disabled',
                    'attrs'			=> array(
			        	'data-on-color' => "success",
			        	'data-on-text' => $this->translate->translate('YES'),
			        	'data-off-color' =>"danger",
			        	'data-off-text'	=> $this->translate->translate('NO')
                    )
                )
            );
        }
    }

	const RULE_MATCH_ALL = 1;
	const RULE_MATCH_ANY = 2;
	const RULE_NOT_MATCH_ALL = 3;
	const RULE_NOT_MATCH_ANY = 4;


	// IBlockProvider
	public function registerBlocks() {
		return array(
			'permission.add' => function($data, $self) {
				$self->putComponent("modal");
        		$self->putModuleScript("dialog.permission");
        		//$this->putSectionTemplate(null, "blocks/permission");
        		$self->putSectionTemplate("foot", "dialogs/add");
        		$self->putSectionTemplate("permission", "blocks/permission");

        		return true;

			}
		);
	}

	/**
	 * Utility method to parse data for a condition_id string e return the struct
	 * @param  string $condition_id The condition ID, in "{module_id}::{cond_id}" format
	 * @return array Return the condition_id, the module name and the module itself.
	 * @deprecated
	 */
	protected function getModuleByConditionId($condition_id) {
		list($module, $condition_id) = explode("::", $condition_id);

		$modules = $this->getModules("IPermissionChecker");
		if (array_key_exists($module, $modules)) {
			return array($condition_id, $module, $modules[$module]);
		}
		return false;
	}
	/**
	 * Receive a datasource e filter (un)matched rules
	 * @param  array[] $dataItens
	 * @param  string $type
	 * @param  string $access_mode
	 * @param  string $id_field
	 * @return array[]
	 * @deprecated 3.0.1.0
	 */
	public function checkRules($dataItens, $type, $access_mode = 'permission_access_mode', $id_field = "id") {
		// TODO MAKE A WAY TO CACHE getItemsByType, in the model
		$conditions = $this->model("permission/condition")->getItemsByType($type);

		if (is_numeric($access_mode)) {
			$default_access_mode_code = $access_mode;
		} else {
			$default_access_mode_code = self::RULE_MATCH_ALL;
		}

		foreach($dataItens as $index => $item) {
			//$item['id']
			$has_condition = false;
			$match = true;
			$matches = array();
			foreach($conditions as $condition) {
				if ($condition['entity_id'] == $item[$id_field]) {
					// CHECK FOR CONDITION
					//$match
					list($condition_id, $module_key, $module) = $this->getModuleByConditionId(
						$condition['condition_id']
					);

					$matches[] = $module->checkCondition(
						$condition_id,
						$condition['data']
					);
					/*
					if (!$match) {
						break;
					}
					*/
				}
			}
			if (is_string($access_mode) && array_key_exists($access_mode, $item)) {
				$access_mode_code = $item[$access_mode];
			} else {
				$access_mode_code = $default_access_mode_code;
			}

			if ($access_mode_code == self::RULE_MATCH_ALL) {
				if (count($matches) == 0 || is_numeric(array_search(false, $matches))) {
					unset($dataItens[$index]);
				}
			} else if ($access_mode_code == self::RULE_MATCH_ANY) {
				if (count($matches) == 0 || array_search(true, $matches) === FALSE) {
					unset($dataItens[$index]);
				}
			} else if ($access_mode_code == self::RULE_NOT_MATCH_ALL) {
				if (count($matches) > 0 && is_numeric(array_search(true, $matches))) {
					unset($dataItens[$index]);
				}
			} else if ($access_mode_code == self::RULE_NOT_MATCH_ANY) {
				if (count($matches) > 0 && array_search(false, $matches) === FALSE) {
					unset($dataItens[$index]);
				}
			}
		}
		return $dataItens;
	}

	/**
	 * [ add a description ]
	 * @deprecated 3.0.1.0
	 * @url GET /combo/items
	 */
	public function comboItensAction()
	{
		$q = $_GET['q'];
		// SEARCH BY $q;
		/*
		$cond = array(
			'condition_id'		=> UsersModule::PERMISSION_IN_LESSON,
			'module'			=> "users", // IMPLEMENTS IPermissionChecker
			'data'				=> "189"
		);
		*/

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
	 * Get the HTML snipet for a determinated condition
	 * @param  string $condition_id
	 * @return html
	 * @deprecated 3.0.1.0
	 * @url GET /get/options/:condition_id
	 */
	public function getConditionOptions($condition_id) {

		list($condition_id, $module_key, $module) = $this->getModuleByConditionId($condition_id);
		if ($module != false) {
			$result = $module->getPermissionForm($condition_id, array('teste'));
			if ($result) {
				echo $result;
			} else {
				return $this->invalidRequestError();
			}
		} else {
			return $this->invalidRequestError();
		}
	}

	/**
	 * ADD A NEW PERMISSION
	 *
	 * @url POST /item/me
	 * @deprecated 3.0.1.0
	 */
	public function createModelAction()
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			list($condition_id, $module_key, $module) = $this->getModuleByConditionId($data['condition_id']);

			//var_dump($condition_id, $module_key, $module);
			//exit;

			if ($module != false) {

				$condition_data = $module->parseFormData($condition_id, $data['data']);

				$permission = array(
					'condition_id'	=> $data['condition_id'],
					'data'			=> $condition_data
				);

				$permission['id'] = $this->model("permission/condition")->addItem($permission);
				$permission['text'] = $module->getConditionText($condition_id, $condition_data);

				// SAVE entity
				$entity = array(
					'type'			=> $data['entity']['type'],
					'entity_id'		=> $data['entity']['entity_id'],
					'condition_id'	=> $permission['id']
				);
				$entity['id'] = $this->model("permission/entity")->addItem($entity);


				//unset($entity['condition_id']);
				$permission['entity'] = $entity;

				$response = $this->createAdviseResponse($this->translate->translate("Permission created with success"), "success");
				return array_merge($response, $permission);

			} else {
				return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
			}
		} else {
			return $this->notAuthenticatedError();
		}
	}
	/**
	 * Deletes a permission/condition model
	 * @param  id $id The permission/condition ID to remove
	 * @return response     The status response from model;
	 * @url DELETE /item/me/:id
	 * @deprecated 3.0.1.0
	 */
	public function deleteModelAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$this->model("permission/condition")->deleteItem($id);
			return $this->createAdviseResponse($this->translate->translate("Permission removed with success"), "info");
		} else {
			return $this->notAuthenticatedError();
		}
	}

	/**
	 * GET ALL OR FILTERED PERMISSIONS
	 *
	 * @return json[]
	 * @deprecated 3.0.1.0 NOw a database aproch is used;.
	 */
	public function getPermissionsAction()
	{
		if ($userData = $this->getCurrentUser()) {
			// APPEND A FILTER, TO GET ONLY ITEMS FROM SENT OBJECT
			$permissionConditionModel = $this->model("permission/condition");

			$type = filter_input(INPUT_GET, 'type', FILTER_DEFAULT);
			if ($type) {
				$params['ent.type'] = $type;
			} else {
				return $this->invalidRequestError($this->translate->translate('An error ocurred. Please try reloading the page'), "error");
			}

			$entity_id = filter_input(INPUT_GET, 'entity_id', FILTER_DEFAULT);
			if ($entity_id) {
				$params['ent.entity_id'] = $entity_id;
			} else {
				return $this->invalidRequestError($this->translate->translate('An error ocurred. Please try reloading the page'), "error");
			}
			/*
			$filter = $permissionConditionModel->createFilter(
				$permissionConditionModel->mainTablePrefix . '.id',
				"ent.condition_id",
				array("operator" => "=", "quote" => false)
			);
			$permissionConditionModel->createJoin('LEFT', "mod_permission_entities ent", $filter);
			*/
			$permissionConditionModel->addFilter(array(
				'ent.type'		=> $type,
				'ent.entity_id'	=> $entity_id
			));

			$items = $permissionConditionModel->getItems($id);

			foreach($items as $index => &$item) {
				list($condition_id, $module_key, $module) = $this->getModuleByConditionId($item['condition_id']);
				$item['text'] = $module->getConditionText($condition_id, $item['data']);
			}
			return $items;

		} else {
			return $this->notAuthenticatedError();
		}
	}

	/**
     * [ add a description ]
     *
     * @url GET /items/me
     * @url GET /items/me/:type
     */
    /*
    public function getItemsAction($type)
    {

        $currentUser    = $this->getCurrentUser(true);
        //$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

        $modelRS = AclResource::find();
        $items = array();
        foreach($modelRS as $key => $item) {
            $items[$key] = $item->toArray();
            //$news[$key]['user'] = $item->getUser()->toArray();;
        }

        if ($type === 'datatable') {
            $items = array_values($items);
            $baseLink = $this->getBasePath();

            foreach($items as $key => $item) {
                // TODO THINK ABOUT MOVE THIS TO config.yml FILE
                if (array_key_exists('block', $_GET)) {
                    $items[$key]['options'] = array(
                        'check'  => array(
                            //'icon'  		=> 'icon-check',
                            //'link'  		=> $baseLink . "block/" . $item['id'],
                            //'text' 			=> $this->translate->translate('Disabled'),
                            //'class' 		=> 'btn-sm btn-danger',
                            'type'			=> 'switch',
                            //'state'			=> 'disabled',
                            'attrs'			=> array(
					        	'data-on-color' => "success",
					        	'data-on-text' => $this->translate->translate('YES'),
					        	'data-off-color' =>"danger",
					        	'data-off-text'	=> $this->translate->translate('NO')
                            )
                        )
                    );
                } else {
                    $items[$key]['options'] = array(
                        'edit'  => array(
                            'icon'  => 'fa fa-edit',
                            //'link'  => $baseLink . "edit/" . $item['id'],
                            'class' => 'btn-sm btn-primary datatable-actionable'
                        ),
                        'permission'  => array(
                            'icon'  => 'fa fa-lock',
                            'link'  => $baseLink . "set-resources/" . $item['id'],
                            'class' => 'btn-sm btn-warning'
                        ),
                        'remove'    => array(
                            'icon'  => 'icon-remove',
                            'class' => 'btn-sm btn-danger'
                        )
                    );
                }

            }
            return array(
                'sEcho'                 => 1,
                'iTotalRecords'         => count($items),
                'iTotalDisplayRecords'  => count($items),
                'aaData'                => array_values($items)
            );
        }
        return array_values($items);
    }
	*/

}

