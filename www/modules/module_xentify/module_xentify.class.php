<?php
class module_xentify extends MagesterExtendedModule {
	const XENTIFY_SEP = ';';
		
    // CORE MODULE FUNCTIONS
    public function getName() {
        return "XENTIFY";
    }
    public function getPermittedRoles() {
        return array("administrator");
    }
    public function isLessonModule() {
        return false;
    }
	/* DATA MODEL FUNCTIONS */
	
	private function makeScopeFormOptions($scope_id, &$form) {
		// RETURN FIELD NAMES ??
		$scopeFields = array();
		switch($scope_id) {
			case 2 : {
				$scopeFields = array('polo_id');
				
				$polosData = eF_getTableData("module_polos", "id, nome", "active = 1");
				$poloCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach($polosData as $polo) {
					$poloCombo[$polo['id']] = $polo['nome'];	
				}
				$form
					->addSelect('polo_id', null, array('label'	=> __XCONTENT_POLO, 'options'	=> $poloCombo))
					->addRule('gt', __XCONTENT_MORE_THAN_ZERO, 0);
				/*
				$classeData = eF_getTableData(
					"classes cl LEFT JOIN courses c ON (cl.courses_ID = c.id)",
					"cl.id, c.name as course_name, cl.name as classe_name", 
					"c.active = 1 AND cl.active = 1",
					"c.name ASC, cl.name ASC, cl.id"
				);
				$classeCombo = array(-1 => __SELECT_ONE_OPTION);
				
				foreach($classeData as $classe) {
					if (!is_array($classeCombo[$classe['course_name']])) {
						$classeCombo[$classe['course_name']] = array();
					}
					$classeCombo[$classe['course_name']][$classe['id']] = $classe['classe_name'];
				}
				$form
					->addSelect('classe_id', null, array('label'	=> __XCONTENT_CLASSE, 'options' => $classeCombo))
					->addRule('gt', __XCONTENT_MORE_THAN_ZERO, 0);
				*/
				break;
			} 
			case 10 : {
				$scopeFields = array('polo_id', 'classe_id');
				
				$polosData = eF_getTableData("module_polos", "id, nome", "active = 1");
				$poloCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach($polosData as $polo) {
					$poloCombo[$polo['id']] = $polo['nome'];	
				}
				$form
					->addSelect('polo_id', null, array('label'	=> __XCONTENT_POLO, 'options'	=> $poloCombo))
					->addRule('gt', __XCONTENT_MORE_THAN_ZERO, 0);
				
				$classeData = eF_getTableData(
					"classes cl LEFT JOIN courses c ON (cl.courses_ID = c.id)",
					"cl.id, c.name as course_name, cl.name as classe_name", 
					"c.active = 1 AND cl.active = 1",
					"c.name ASC, cl.name ASC, cl.id"
				);
				$classeCombo = array(-1 => __SELECT_ONE_OPTION);
				
				foreach($classeData as $classe) {
					if (!is_array($classeCombo[$classe['course_name']])) {
						$classeCombo[$classe['course_name']] = array();
					}
					$classeCombo[$classe['course_name']][$classe['id']] = $classe['classe_name'];
				}
				$form
					->addSelect('classe_id', null, array('label'	=> __XCONTENT_CLASSE, 'options' => $classeCombo))
					->addRule('gt', __XCONTENT_MORE_THAN_ZERO, 0);
				
				break;
			} 
		}
		return $scopeFields;
	}	
	
	/* DATA MODEL FUNCTIONS */
	public function getScopes($constraints = null) {
		if (is_null($constraints)) {
			$constraints = array('active' => true);
		}
		$where = array();
		if (array_key_exists('active', $constraints)) {
			$where[] = 'active = ' . ($constraints['active'] ? '1' : 0);
		}
		
		$scopeDBData = eF_getTableData("module_xentify_scopes", "*", implode(" AND ", $where));
		
		foreach($scopeDBData as &$scope) {
			$scope['fields'] = $this->getScopeFields($scope['id']);
		}
		
		return $scopeDBData;
	}
    public function getScopesForUser($user = null) {
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	$currentScopes = $this->getScopes();

    	$userScopeData = array();
    	
    	foreach($currentScopes as $scope) {
    		//$scopeData = $this->getUserScopeRelevantData($user, $scope['id']);
    		$scopeData = $this->getUserScopeRelevantData($user, $scope['id']);
    		if ($scopeData) {
    			$userScopeData[$scope['id']] = $scopeData;
    		}
    	}
    	return $userScopeData;
    }
    public function getTagsForScopes($scopeValues) {
    	$stringIndexesMapper = array(
    		2 => 'polo_id',
    		13 => 'group_ids'
    	);
    	
    	$currentScopes = $this->getScopes();
    	
    	$scopeTags = array();
    	foreach($currentScopes as $scope) {
    		// xentify_scope_id 	xentify_id 	tag
    		
    		if (array_key_exists($scope['id'], $stringIndexesMapper) && array_key_exists($scope['id'], $scopeValues)) {
    			//DB VALUE IS xentify_id = $scopes[$scopeIndex]
    			if (is_array($scopeValues[$scope['id']])) {
    				$value = implode(", ", $scopeValues[$scope['id']]);
    			} else {
					$value = "'" . $scopeValues[$scope['id']] . "'";
    			}
    			$scopeTagsDB = ef_getTableDataFlat(
    				"module_xentify_scope_tags",
    				"tag",
   					sprintf("xentify_scope_id = %d AND xentify_id IN (%s)", $scope['id'], $value)
    			);    				
    			
    			if (!is_null($scopeTagsDB['tag'])) {
    				$scopeTags += $scopeTagsDB['tag'];
    			}
    		}
    	}
   	
    	return $scopeTags;
    }
    
    private function getUserScopeRelevantData($user, $scope_type) {
    	if (is_null($user)) {
	    	$user = $this->getCurrentUser();
    	}
    	$data = array();
    	
    	switch($scope_type) {
    		case 2 : { // SAME POLO AND SAME CLASS
    			return $this->getUserScopePoloIndex($user);
    		}
    		case 7 : { // SAME POLO AND SAME CLASS
    			//list($data['user_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 10 : { // SAME POLO AND SAME CLASS
    			//list($data['polo_id'], $data['classe_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 13 : { // SAME GROUPS
    			//list($data['group_id']) = explode(';', $scope_id);
    			return $this->getUserGroupsIndex($user);
    		}
    	}
    	return $data;
    }
    
    
    
    public function isUserInScope($user = null, $scope_type, $scope_id) {
    	$status = $this->getUserScopeStatus($user, $scope_type, $scope_id);
    	
    	switch($scope_type) {
    		case 0 : { // SAME POLO AND SAME CLASS
    			return true;
    		}
    		case 2 : { // SAME POLO AND SAME CLASS
    			return $status['same_polo'];
    		}
    		case 7 : { // SAME POLO AND SAME CLASS
    			return $status['same_user'];
    		}
    		case 10 : { // SAME POLO AND SAME CLASS
    			return $status['same_polo'] && $status['same_classe'];
    		}
    		case 11 : { // NO OVERDUE INVOICES USER
    			/** @todo Implementar checagem de adimplência */
    			return $status['no_overdue'];
    		}
    		case 12 : { // OVERDUE INVOICES USER
    			/** @todo Implementar checagem de inadimplência */
    			return $status['overdue'];
    		}
    		case 13 : { // OVERDUE INVOICES USER
    			/** @todo Implementar checagem de inadimplência */
    			return $status['same_group'];
    		}
    		default : {
    			return false;
    		}
    	}
    }
    public function getUserScopeStatus($user = null, $scope_type, $scope_id) {
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	$status = array(
    		'same_polo'		=> false,
    		'same_classe'	=> false,
    		'same_user'		=> false,
    		'same_group'	=> false,    			
    		'no_overdue'	=> false,
    		'overdue'		=> false
	   	);
	   	$data = $this->getUserScopeData($scope_type, $scope_id);


    	switch($scope_type) {
    		case 2 : { // SAME POLO
    			$status['same_polo'] = $this->checkUserScopeSamePolo($user, $data['polo_id']);
    			break;
    		}
    		case 7 : { // SAME USER (INDIVIDUAL)
    			$status['same_user'] = $this->checkUserScopeSameUser($user, $data['user_id']);
    			break;
    		}
    		case 10 : { // SAME POLO AND SAME CLASS
    			$status['same_polo'] = $this->checkUserScopeSamePolo($user, $data['polo_id']);
    			$status['same_classe'] = $this->checkUserScopeSameClasse($user, $data['classe_id']);
    			break;
    		}
    		case 11 : 
    		case 12 : { // OVERDUE INVOICES USER
    			$status['no_overdue'] = !($status['overdue'] = $this->checkUserInDebt($user));
    			break;
    		}
    		case 13 : {
    			$status['same_group']	= $this->checkUserScopeSameGroup($user, $data['group_id']); 
    			break;
    		}
    		
    		default : {
    			return false;
    		}
    	}
    	return $status;
    }
    public function getUserScopeData($scope_type, $scope_id) {
    	/*
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	*/
    	
    	$data = array(
    		'user_id'			=> null,
			'polo_id'			=> null,
   			'classe_id'			=> null,
    		'group_id'			=> null
		);
    	
    	switch($scope_type) {
    		case 2 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 7 : { // SAME POLO AND SAME CLASS
    			list($data['user_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 10 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id'], $data['classe_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 13 : { // SAME GROUPS
    			list($data['group_id']) = explode(';', $scope_id);
    			break;
    		}  		
    	}
    	return $data;
    }
    public function getScopeFields($scopeID = null) {
    /**
     * @todo BUscar nomes dos campos de escopo do banco de dados 
     */
		$allData  = array(
			2 => array(
				array(
					'name' 	=> 'polo_name',
					'label'	=> __XCONTENT_POLO
				)
			),
			10 => array(
				array(
					'name' 	=> 'polo_name',
					'label'	=> __XCONTENT_POLO
				), 
				array(
					'name' 	=> 'classe_name',
					'label'	=> __XCONTENT_CLASSE
				)
			),
			
	   	);
	   	
	   	if (array_key_exists($scopeID, $allData)) {
	   		return $allData[$scopeID];
	   	}
	   	
	   	$result = array();
   		foreach($allData as $scope) {
   			$result = array_merge_recursive($result, $scope);
   		}
   		return $result;
    }
    public function getScopeEntifyNames($user = null, $scope_type, $scope_id) {
    	$scopeData = $this->getUserScopeData($scope_type, $scope_id);
    	
    	$data = array();
    	
    	if (eF_checkParameter($scopeData['polo_id'], 'id')) {
    		list($data['polo']) = eF_getTableData("module_polos", "*", 'id = ' . $scopeData['polo_id']);
    	}
    	if (eF_checkParameter($scopeData['classe_id'], 'id')) {
    		list($data['classe']) = eF_getTableData("classes", "*", 'id = ' . $scopeData['classe_id']);
    	}
    	
    	return $data;
    }
	public function getScopeEntifyValues($user = null, $scope_type, $scope_id) {
    	$scopeData = $this->getScopeEntifyNames(null, $scope_type, $scope_id);
    	
    	$result = array();
    	
    	if (is_array($scopeData['polo'])) {
    		$result['polo_name'] = array(
    			'label'	=> __XCONTENT_POLO,
    			'value'	=> $scopeData['polo']['nome']
    		);
    	}
    	if (is_array($scopeData['classe'])) {
    		$result['classe_name'] = array(
    			'label'	=> __XCONTENT_CLASSE,
    			'value'	=> $scopeData['classe']['name']
    		);
    	}
    	
    	return $result;
    }
	public function getUsersByScopeId($scope_type, $scope_id, $contraints = array()) {
		$scope_data = $this->getUserScopeData($scope_type, $scope_id);
		
		!empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
		
    	$allWheres = array(
			'polo'		=> sprintf("u.id IN (select id FROM module_xuser xu WHERE xu.polo_id = %d)", $scope_data['polo_id'])	
   			//'classe_id'	=> sprintf("u.id IN (select id FROM module_xuser xu WHERE xu.polo_id = %d)", $scope_data['polo_id'])
		);
		
		$scopedWhere = array();
    	
    	switch($scope_type) {
    		case 2 : { // SAME POLO AND SAME CLASS
    			$scopedWhere[] = $allWheres['polo'];
    			break;
    		}
    		default : {
    			
    		}
    	}
  		list($where, $limit, $orderby) = MagesterUser :: convertUserConstraintsToSqlParameters($constraints);
  		
  		$where = array_merge($where, $scopedWhere);
  		
		$from = "users u";
  		$select = "u.*";
		$result = eF_getTableData($from, $select, implode(" and ", $where), $orderby, "", $limit);
		
		if (!isset($constraints['return_objects']) || $constraints['return_objects'] == false) {
   			return MagesterUser :: convertDatabaseResultToUserArray($result);
  		} else {
   			return MagesterUser :: convertDatabaseResultToUserObjects($result);
  		}
	}
    
	private function getUserScopePoloIndex($user) {
		$userPolo = $user->getUserPolo(array('return_objects'	=> false));
		
		if ($userPolo) { 
			return $userPolo['id'];
		} else {
			return false;
		}
		
	}
    private function checkUserScopeSamePolo($user, $polo_id) {
		return ($this->getUserScopePoloIndex($user) == $polo_id);
    }
    
    
    private function checkUserScopeSameUser($user, $user_id) {
    	return ($user->user['id'] == $user_id);
    }
    private function checkUserScopeSameClasse($user, $classe_id) {
        $userClasses = $user->getUserCoursesClasses(array('return_objects'	=> false));
    			
    	$classesID = array();
    	foreach($userClasses as $classe) {
    		$classesID[] = $classe['id'];
    	}
    	
    	return in_array($classe_id, $classesID);
    }
    
    private function getUserGroupsIndex($user) {
    	$ids = array();
    	foreach($user->getGroups() as $group) {
    		$ids[] = $group['id'];
    	}
    	return $ids;
    }
    private function checkUserScopeSameGroup($user, $group_id) {
    	return in_array($group_id, $this->getUserGroupsIndex($user));
    }
    
    
	private function checkUserInDebt($user) {
		return $this->loadModule("xpay")->isUserInDebt($user);
	}

}
?>