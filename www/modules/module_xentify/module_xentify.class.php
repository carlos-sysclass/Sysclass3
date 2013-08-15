<?php
interface IScopedEntify
{
	public function inScope($scope_type, $scope_id);
	public function getCurrentEntify();
	/* COMPARISON FUNCTIONS */
	public function checkIfSameIes($ies_id);
	public function checkIfSamePolo($polo_id);
	public function checkIfSameUser($user_id);
	public function checkIfSameUserType($user_type);
	public function checkIfSameClasse($classe_id);
	public function checkIfSameGroup($group_id);
	public function checkIfSameCourse($course_id);
}

abstract class scope
{
	protected $entify_id = null;
	protected $entify = null;

	/* RETURN CURRENT ENTIFY CLASS "FROM libraries"*/
	public function getCurrentEntify()
	{
		return $this->entify;
	}
	public function getScopeData($scope_type, $scope_id)
	{
		$data = array(
			'user_id'			=> null,
			'ies_id'			=> null,
			'polo_id'			=> null,
			'classe_id'			=> null,
			'user_type'			=> null,
			'group_id'			=> null,
			'course_id'			=> null
		);

		switch ($scope_type) {
			case 1 : { // SAME POLO AND SAME CLASS
				list($data['ies_id']) = explode(';', $scope_id);
				break;
			}
			case 2 : { // SAME POLO AND SAME CLASS
				list($data['polo_id']) = explode(';', $scope_id);
				break;
			}
			case 7 : { // SAME POLO AND SAME CLASS
				list($data['user_id']) = explode(';', $scope_id);
				break;
			}
			case 9 : { // SAME POLO AND SAME CLASS
				list($data['user_type']) = explode(';', $scope_id);
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
			case 14 : { // SAME GROUPS
				list($data['group_id'], $data['course_id']) = explode(';', $scope_id);
				break;
			}
			case 15 : { // SAME GROUPS
				list($data['ies_id'], $data['user_type']) = explode(';', $scope_id);
				break;
			}
		}
		return $data;
	}

	public function getEntifyScopeStatus($entify = null, $scope_type, $scope_id)
	{
		if (is_null($entify)) {
			$entify = $this->getCurrentEntify();
		}
		$status = array(
			'same_ies'			=> false,
			'same_polo'			=> false,
			'same_classe'		=> false,
			'same_user'			=> false,
			'same_user_type'	=> false,
			'same_group'		=> false,
			'same_course'		=> false,
			'no_overdue'		=> false,
			'overdue'			=> false
		);

		$data = $this->getScopeData($scope_type, $scope_id);

		switch ($scope_type) {
			case 1:
				// SAME IES
				$status['same_ies'] = $this->checkIfSameIes($data['ies_id']);
				break;
			case 2:
				// SAME POLO
				$status['same_polo'] = $this->checkIfSamePolo($data['polo_id']);
				break;
			case 7:
				// SAME USER (INDIVIDUAL)
				$status['same_user'] = $this->checkIfSameUser($data['user_id']);
				break;
			case 9:
				// SAME USER TYPE (INDIVIDUAL)
				$status['same_user_type'] = $this->checkIfSameUserType($data['user_type']);
				break;
			case 10: 
				// SAME POLO AND SAME CLASS				
				$status['same_polo'] = $this->checkIfSamePolo($data['polo_id']);
				$status['same_classe'] = $this->checkIfSameClasse($data['classe_id']);
				break;
			/*
			case 11 :
			case 12 : { // OVERDUE INVOICES USER
				$status['no_overdue'] = !($status['overdue'] = $this->checkUserInDebt($user));
				break;
			}
			*/
			case 13:
				$status['same_group']	= $this->checkIfSameGroup($data['group_id']);
				break;
			case 14:
				$status['same_group']	= $this->checkIfSameGroup($data['group_id']);
				$status['same_course']	= $this->checkIfSameCourse($data['course_id']);
				break;
			case 15:
				$status['same_ies'] 		= $this->checkIfSameIes($user, $data['ies_id']);
				$status['same_user_type'] 	= $this->checkIfSameUserType($user, $data['user_type']);
				break;
			default:
				return false;
		}

		return $status;
	}

	public function inScope($scope_type, $scope_id)
	{
		if ($scope_type == 0 || is_null($scope_type)) {
			return true;
		}

		$status = $this->getEntifyScopeStatus(null, $scope_type, $scope_id);

		switch ($scope_type) {
			case 0 : {
				return true;
			}
			case 1 : { // SAME IES
				return $status['same_ies'];
			}
			case 2 : { // SAME POLO
				return $status['same_polo'];
			}
			case 7 : { // SAME USER
				return $status['same_user'];
			}
			case 9 : { // SAME USER TYPE
				return $status['same_user_type'];
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
			case 13 : { // SAME USER GROUP
				/** @todo Implementar checagem de inadimplência */
				return $status['same_group'];
			}
			case 14 : { // SAME USER GROUP AND COURSE
				/** @todo Implementar checagem de inadimplência */
				return $status['same_group'] && $status['same_course'];
			}
			case 15 : { // SAME IES AND USER TYPE
				/** @todo Implementar checagem de inadimplência */
				return $status['same_ies'] && $status['same_user_type'];
			}

			default : {
				return false;
			}
		}
	}

	/* COMPARISON FUNCTIONS... RETURN FALSE ON PARENT */
	public function checkIfSameIes($ies_id)
	{
		return false;
	}
	public function checkIfSamePolo($polo_id)
	{
		return false;
	}
	public function checkIfSameUser($user_id)
	{
		return false;
	}
	public function checkIfSameUserType($user_type)
	{
		return false;
	}
	public function checkIfSameClasse($classe_id)
	{
		return false;
	}
	public function checkIfSameGroup($group_id)
	{
		return false;
	}
	public function checkIfSameCourse($course_id)
	{
		return false;
	}
}

class scopedCourse extends scope implements IScopedEntify
{
	public function __construct($course_id)
	{
		$this->entify_id = $course_id;
		$this->entify = new MagesterCourse($course_id);
	}
	/* checkForSameIes */
	public function checkIfSameIes($ies_id)
	{
		if (!is_array($ies_id)) {
			$ies_id = array($ies_id);
		}
		return in_array($this->entify->course['ies_id'], $ies_id);
	}
}

class scopedLesson extends scope implements IScopedEntify
{
	public function __construct($lesson_id)
	{
		$this->entify_id = $lesson_id;
		$this->entify = new MagesterLesson($lesson_id);
	}
	/* checkForSameIes */
	public function checkIfSameIes($ies_id)
	{
		if (!is_array($ies_id)) {
			$ies_id = array($ies_id);
		}

		if ($this->entify->lesson['ies_id'] == 0) { // TRY TO CHECK BY COURSE
			$result = sC_getTableDataFlat("lessons_to_courses lc LEFT JOIN courses c ON (lc.courses_ID = c.id)", "c.ies_id", "lc.lessons_ID = " . $this->entify->lesson['id']);
			if (count($result) == 1) {
				$lessonIesId = reset($result['ies_id']);
			} else {
				return false;
			}
		} else {
			$lessonIesId = $this->entify->lesson['ies_id'];
		}

		return in_array($lessonIesId, $ies_id);
	}
}

class scopedUser extends scope implements IScopedEntify
{
	public function __construct($login)
	{
		$this->entify_id = $login;
		$this->entify = MagesterUserFactory::factory($login);
	}
	/* checkForSameIes */
	public function checkIfSameIes($ies_id)
	{
		if (!is_array($ies_id)) {
			$ies_id = array($ies_id);
		}
		$userIes = $this->entify->getUserIes();

		foreach ($ies_id as $id) {
			if (in_array($id, $userIes)) {
				return true;
			}
		}
		return false;
	}
}

class module_xentify extends MagesterExtendedModule
{
	const XENTIFY_SEP = ';';

	public function create($type, $entify_id)
	{
		$className = "scoped" . ucfirst($type);

		if (class_exists($className)) {
			return new $className($entify_id);
		} else {
			throw new xentifyException(__XENTIFY_THIS_SCOPE_ISNT_INSTALLED);
		}
	}

    // CORE MODULE FUNCTIONS
    public function getName()
    {
        return "XENTIFY";
    }
    public function getPermittedRoles()
    {
        return array("administrator");
    }
    public function isLessonModule()
    {
        return false;
    }
	/* DATA MODEL FUNCTIONS */

	public function makeScopeFormOptions($scope_id, &$form)
	{
		// RETURN FIELD NAMES ??
		$scopeFields = array();
		switch ($scope_id) {
			case 1 : {
				$scopeFields = array('ies_id');

				$iesData = sC_getTableData("module_ies", "id, nome", "active = 1");
				$iesCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach ($iesData as $item) {
					$iesCombo[$item['id']] = $item['nome'];
				}

				$form
					->addSelect('ies_id', null, array('label'	=> __XENTIFY_IES, 'options'	=> $iesCombo))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);

				break;
			}
			case 2 : {
				$scopeFields = array('polo_id');

				$polosData = sC_getTableData("module_polos", "id, nome", "active = 1");
				$poloCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach ($polosData as $polo) {
					$poloCombo[$polo['id']] = $polo['nome'];
				}
				$form
					->addSelect('polo_id', null, array('label'	=> __XENTIFY_POLO, 'options'	=> $poloCombo))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);
				break;
			}
			case 10 : {
				$scopeFields = array('polo_id', 'classe_id');

				$polosData = sC_getTableData("module_polos", "id, nome", "active = 1");
				$poloCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach ($polosData as $polo) {
					$poloCombo[$polo['id']] = $polo['nome'];
				}
				$form
					->addSelect('polo_id', null, array('label'	=> __XENTIFY_POLO, 'options'	=> $poloCombo))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);

				$classeData = sC_getTableData(
					"classes cl LEFT JOIN courses c ON (cl.courses_ID = c.id)",
					"cl.id, c.name as course_name, cl.name as classe_name",
					"c.active = 1 AND cl.active = 1",
					"c.name ASC, cl.name ASC, cl.id"
				);
				$classeCombo = array(-1 => __SELECT_ONE_OPTION);

				foreach ($classeData as $classe) {
					if (!is_array($classeCombo[$classe['course_name']])) {
						$classeCombo[$classe['course_name']] = array();
					}
					$classeCombo[$classe['course_name']][$classe['id']] = $classe['classe_name'];
				}
				$form
					->addSelect('classe_id', null, array('label'	=> __XENTIFY_CLASSE, 'options' => $classeCombo))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);

				break;
			}
			case 13 : {
				$scopeFields = array('user_group');

				$groupsData = sC_getTableData("groups", "id, name", "active = 1");
				$groupCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach ($groupsData as $item) {
					$groupCombo[$item['id']] = $item['name'];
				}

				$userGroups[-1] = __SELECT_ONE_OPTION;
				$form
					->addSelect('user_group', null, array('label'	=> __XENTIFY_USER_TYPE, 'options'	=> $groupCombo))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);

				break;
			}
			case 15 : {
				$scopeFields = array('ies_id', 'user_type');

				$iesData = sC_getTableData("module_ies", "id, nome", "active = 1");
				$iesCombo = array(-1 => __SELECT_ONE_OPTION);
				foreach ($iesData as $item) {
					$iesCombo[$item['id']] = $item['nome'];
				}

				$form
					->addSelect('ies_id', null, array('label'	=> __XENTIFY_IES, 'options'	=> $iesCombo))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);

				$userRoles = MagesterUser::getRoles(true);
				$userRoles[-1] =  __SELECT_ONE_OPTION;
				$form
					->addSelect('user_type', null, array('label'	=> __XENTIFY_USER_TYPE, 'options'	=> $userRoles))
					->addRule('gt', __XENTIFY_MORE_THAN_ZERO, 0);

				break;
			}
		}
		return $scopeFields;
	}

	/* DATA MODEL FUNCTIONS */
	public function getScopes($constraints = null)
	{
		if (is_null($constraints)) {
			$constraints = array('active' => true);
		}
		$where = array();
		if (array_key_exists('active', $constraints)) {
			$where[] = 'active = ' . ($constraints['active'] ? '1' : 0);
		}

		$scopeDBData = sC_getTableData("module_xentify_scopes", "*", implode(" AND ", $where));

		foreach ($scopeDBData as &$scope) {
			$scope['fields'] = $this->getScopeFields($scope['id']);
		}

		return $scopeDBData;
	}
    public function getScopesForUser($user = null)
    {
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	$currentScopes = $this->getScopes();

    	$userScopeData = array();

    	foreach ($currentScopes as $scope) {
    		//$scopeData = $this->getUserScopeRelevantData($user, $scope['id']);
    		$scopeData = $this->getUserScopeRelevantData($user, $scope['id']);
    		if ($scopeData) {
    			$userScopeData[$scope['id']] = $scopeData;
    		}
    	}
    	return $userScopeData;
    }
    public function getTagsForScopes($scopeValues)
    {
    	$stringIndexesMapper = array(
    		2 => 'polo_id',
    		13 => 'group_ids'
    	);

    	$currentScopes = $this->getScopes();

    	$scopeTags = array();
    	foreach ($currentScopes as $scope) {
    		// xentify_scope_id 	xentify_id 	tag

    		if (array_key_exists($scope['id'], $stringIndexesMapper) && array_key_exists($scope['id'], $scopeValues)) {
    			//DB VALUE IS xentify_id = $scopes[$scopeIndex]
    			if (is_array($scopeValues[$scope['id']])) {
    				$value = implode(", ", $scopeValues[$scope['id']]);
    			} else {
					$value = "'" . $scopeValues[$scope['id']] . "'";
    			}
    			$scopeTagsDB = sC_getTableDataFlat(
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

    private function getUserScopeRelevantData($user, $scope_type)
    {
    	if (is_null($user)) {
	    	$user = $this->getCurrentUser();
    	}
    	$data = array();

    	switch ($scope_type) {
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

    public function isUserInScope($user = null, $scope_type, $scope_id)
    {
    	if ($scope_type == 0 || is_null($scope_type)) {
    		return true;
    	}

    	$status = $this->getUserScopeStatus($user, $scope_type, $scope_id);

    	switch ($scope_type) {
    		case 0 : { // SAME POLO AND SAME CLASS
    			return true;
    		}
    		case 1 : { // SAME IES
    			return $status['same_ies'];
    		}
    		case 2 : { // SAME POLO
    			return $status['same_polo'];
    		}
    		case 7 : { // SAME USER
    			return $status['same_user'];
    		}
    		case 9 : { // SAME USER TYPE
    			return $status['same_user_type'];
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
    		case 13 : { // SAME USER GROUP
    			/** @todo Implementar checagem de inadimplência */
    			return $status['same_group'];
    		}
    		case 14: // SAME USER GROUP AND COURSE
    			/** @todo Implementar checagem de inadimplência */
    			return $status['same_group'] && $status['same_course'];
    		case 15: // SAME IES AND USER TYPE
    			/** @todo Implementar checagem de inadimplência */
    			return $status['same_ies'] && $status['same_user_type'];
    		case 16:// SAME NEGOCIATION
    			/** @todo Implementar checagem de inadimplência */
    			return $status['same_negociation_id'];
    		case 17:// SAME NEGOCIATION
    			/** @todo Implementar checagem de inadimplência */
    			return $status['same_ies'] && $status['same_group'];

    		default : {
    			return false;
    		}
    	}
    }
    public function getUserScopeStatus($user = null, $scope_type, $scope_id)
    {
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	$status = array(
    		'same_ies'				=> false,
    		'same_polo'				=> false,
    		'same_classe'			=> false,
    		'same_user'				=> false,
    		'same_user_type'		=> false,
    		'same_group'			=> false,
   			'same_course'			=> false,
    		'same_negociation_id'	=> false,
    		'no_overdue'			=> false,
    		'overdue'				=> false
	   	);
	   	$data = $this->getUserScopeData($scope_type, $scope_id);

    	switch ($scope_type) {
    		case 1 : { // SAME POLO
    			$status['same_ies'] = $this->checkUserScopeSameIes($user, $data['ies_id']);
    			break;
    		}
    		case 2 : { // SAME POLO
    			$status['same_polo'] = $this->checkUserScopeSamePolo($user, $data['polo_id']);
    			break;
    		}
    		case 7 : { // SAME USER (INDIVIDUAL)
    			$status['same_user'] = $this->checkUserScopeSameUser($user, $data['user_id']);
    			break;
    		}
    		case 9 : { // SAME USER (INDIVIDUAL)
    			$status['same_user_type'] = $this->checkUserScopeSameUserType($user, $data['user_type']);
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
    		case 14 : {
    			$status['same_group']	= $this->checkUserScopeSameGroup($user, $data['group_id']);
    			$status['same_course']	= $this->checkUserScopeSameCourse($user, $data['course_id']);
    			break;
    		}
    		case 15 : {
    			$status['same_ies'] 		= $this->checkUserScopeSameIes($user, $data['ies_id']);
    			$status['same_user_type'] 	= $this->checkUserScopeSameUserType($user, $data['user_type']);
    			break;
    		}
    		case 16:
    			$status['same_negociation_id'] 		= $this->checkUserScopeSameNegociation($user, $data['negociation_id']);
    			break;
    		case 17:
    			$status['same_ies'] = $this->checkUserScopeSameIes($user, $data['ies_id']);
    			$status['same_group']	= $this->checkUserScopeSameGroup($user, $data['group_id']);
    			break;
    		default:
    			return false;
    	}
    	return $status;
    }
    public function getUserScopeData($scope_type, $scope_id)
    {
    	/*
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	*/

    	$data = array(
    		'user_id'			=> null,
	   		'ies_id'			=> null,
			'polo_id'			=> null,
   			'classe_id'			=> null,
    		'user_type'			=> null,
    		'group_id'			=> null,
   			'course_id'			=> null,
    		'negociation_id'	=> null,
    		'invoice_index'		=> null
		);

    	switch ($scope_type) {
    		case 1 : { // SAME POLO AND SAME CLASS
    			list($data['ies_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 2 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 7 : { // SAME POLO AND SAME CLASS
    			list($data['user_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 9 : { // SAME POLO AND SAME CLASS
    			list($data['user_type']) = explode(';', $scope_id);
    			break;
    		}
    		case 10 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id'], $data['classe_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 13: // SAME GROUPS
    			list($data['group_id']) = explode(';', $scope_id);
    			break;
    		case 14: // SAME GROUPS
    			list($data['group_id'], $data['course_id']) = explode(';', $scope_id);
    			break;
    		case 15: // SAME GROUPS
    			list($data['ies_id'], $data['user_type']) = explode(';', $scope_id);
    			break;
    		case 16:  // SAME NEGOCIATION
    			list($data['negociation_id']) = explode(';', $scope_id);
    			break;
    		case 17: // SAME GROUPS
    			list($data['ies_id'], $data['group_id']) = explode(';', $scope_id);
    			break;
    		/*
   			case 17:  // SAME NEGOCIATION
   				list($data['negociation_id'], $data['invoice_index']) = explode(';', $scope_id);
   				break;
   			/*/
    				 
    	}
    	return $data;
    }

    public function getScopeFields($scopeID = null)
    {
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
			)
	   	);

	   	if (array_key_exists($scopeID, $allData)) {
	   		return $allData[$scopeID];
	   	}

	   	$result = array();
   		foreach ($allData as $scope) {
   			$result = array_merge_recursive($result, $scope);
   		}
   		return $result;
    }
    public function getScopeEntifyNames($user = null, $scope_type, $scope_id)
    {
    	$scopeData = $this->getUserScopeData($scope_type, $scope_id);

    	$data = array();

		if (sC_checkParameter($scopeData['ies_id'], 'id')) {
			list($data['ies']) = sC_getTableData("module_ies", "*", 'id = ' . $scopeData['ies_id']);
		}
    	if (sC_checkParameter($scopeData['polo_id'], 'id')) {
    		list($data['polo']) = sC_getTableData("module_polos", "*", 'id = ' . $scopeData['polo_id']);
    	}
    	if (sC_checkParameter($scopeData['classe_id'], 'id')) {
    		list($data['classe']) = sC_getTableData("classes", "*", 'id = ' . $scopeData['classe_id']);
    	}
    	if (sC_checkParameter($scopeData['group_id'], 'id')) {
    		list($data['group']) = sC_getTableData("groups", "*", 'id = ' . $scopeData['group_id']);	
    	}
    	if (!is_null($scopeData['user_type'])) {
    		$userRoles = MagesterUser::getRoles(true);
    		$data['user_type_name'] = $userRoles[$scopeData['user_type']];
    	}

    	return $data;
    }
    public function getScopeFullDescription($user = null, $scope_type, $scope_id) {
    	$scopeNames = $this->getScopeEntifyNames(null, $scope_type, $scope_id);

    	$search = array(
    		"{ies}",
			"{polo}",
			"{user_class}",
    		"{group}",
    		"{user_type_name}"
    	);
    	$replace = array(
    		$scopeNames['ies']['nome'],
    		$scopeNames['polo']['nome'],
			$scopeNames['classe']['name'],
			$scopeNames['group']['name'],
    		$scopeNames['user_type_name']
    	);

		switch ($scope_type) {
    		case 1 : { // SAME POLO AND SAME CLASS
    			return str_replace($search, $replace, __XENTIFY_SAME_IES_SCOPE);
    			break;
    		}
    		case 2 : { // SAME POLO AND SAME CLASS
    			return str_replace($search, $replace, __XENTIFY_SAME_POLO_SCOPE);
    			break;
    		}
    		/*
    		case 7 : { // SAME POLO AND SAME CLASS
    			list($data['user_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 9 : { // SAME POLO AND SAME CLASS
    			list($data['user_type']) = explode(';', $scope_id);
    			break;
    		}
    		*/
    		case 10 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id'], $data['classe_id']) = explode(';', $scope_id);
    			return str_replace($search, $replace, __XENTIFY_SAME_POLO_SAME_CLASS_SCOPE);
    			break;
    		}
    		case 13: // SAME GROUPS
    			return str_replace($search, $replace, __XENTIFY_SAME_GROUP_SCOPE);
    			break;
    		/*
    		case 14: // SAME GROUPS
    			list($data['group_id'], $data['course_id']) = explode(';', $scope_id);
    			break;
    		*/
    		case 15: // SAME GROUPS
    			return str_replace($search, $replace, __XENTIFY_SAME_IES_SAME_USER_TYPE_SCOPE);
    			break;
    		/*
    		case 16:  // SAME NEGOCIATION
    			list($data['negociation_id']) = explode(';', $scope_id);
    			break;
    		/*
   			case 17:  // SAME NEGOCIATION
   				list($data['negociation_id'], $data['invoice_index']) = explode(';', $scope_id);
   				break;
   			*/
    	}
    	return $data;
    }
	public function getScopeEntifyValues($user = null, $scope_type, $scope_id)
	{
    	$scopeData = $this->getScopeEntifyNames(null, $scope_type, $scope_id);

//var_dump($scopeData);

    	$result = array();


    	if (is_array($scopeData['polo'])) {
    		$result['polo_name'] = array(
    			'label'	=> __XCONTENT_POLO,
    			'value'	=> $scopeData['polo']['nome']
    		);
    	}

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
	public function getUsersByScopeId($scope_type, $scope_id, $contraints = array())
	{
		$scope_data = $this->getUserScopeData($scope_type, $scope_id);

		!empty($constraints) OR $constraints = array('archive' => false, 'active' => true);

    	$allWheres = array(
			'polo'		=> sprintf("u.id IN (select id FROM module_xuser xu WHERE xu.polo_id = %d)", $scope_data['polo_id'])
   			//'classe_id'	=> sprintf("u.id IN (select id FROM module_xuser xu WHERE xu.polo_id = %d)", $scope_data['polo_id'])
		);

		$scopedWhere = array();

    	switch ($scope_type) {
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
		$result = sC_getTableData($from, $select, implode(" and ", $where), $orderby, "", $limit);

		if (!isset($constraints['return_objects']) || $constraints['return_objects'] == false) {
   			return MagesterUser :: convertDatabaseResultToUserArray($result);
  		} else {
   			return MagesterUser :: convertDatabaseResultToUserObjects($result);
  		}
	}

	private function getUserScopeIesIndex($user)
	{
		$userIes = $user->getUserIes();

		return $userIes;
	}
	private function checkUserScopeSameIes($user, $ies_id)
	{
		return in_array($ies_id, $this->getUserScopeIesIndex($user));
	}

	private function getUserScopePoloIndex($user)
	{
		$userPolo = $user->getUserPolo(array('return_objects'	=> false));

		if ($userPolo) {
			return $userPolo['id'];
		} else {
			return false;
		}

	}
    private function checkUserScopeSamePolo($user, $polo_id)
    {
		return ($this->getUserScopePoloIndex($user) == $polo_id);
    }

    private function checkUserScopeSameUser($user, $user_id)
    {
    	return ($user->user['id'] == $user_id);
    }
    private function checkUserScopeSameClasse($user, $classe_id)
    {
        $userClasses = $user->getUserCoursesClasses(array('return_objects'	=> false));

    	$classesID = array();
    	foreach ($userClasses as $classe) {
    		$classesID[] = $classe['id'];
    	}

    	return in_array($classe_id, $classesID);
    }

    private function getUserTypeIndex($user)
    {
	   	if ($user instanceof MagesterUser) {
    		return $user->user['user_types_ID'] == "0" ? $user->getType() :  $user->user['user_types_ID'];
    	}
    	return false;
    }
    private function checkUserScopeSameUserType($user, $user_type)
    {
    	return $user_type == $this->getUserTypeIndex($user);
    }

    private function getUserGroupsIndex($user)
    {
    	$ids = array();
    	if ($user instanceof MagesterUser) {
    		foreach ($user->getGroups() as $group) {
    			$ids[] = $group['id'];
    		}
    	}
    	return $ids;
    }
    private function checkUserScopeSameGroup($user, $group_id)
    {
    	return in_array($group_id, $this->getUserGroupsIndex($user));
    }
    private function getUserCoursesIndex($user)
    {
    	$ids = array();

    	if ($user instanceof MagesterLessonUser) {
    		$constraints = array('archive' => false, 'active' => true, 'return_objects' => false);
    		$userCourses = $user-> getUserCourses($constraints);

    		foreach ($userCourses as $course) {
    			$ids[] = $course['id'];
    		}
    	}
    	return $ids;
    }
    private function checkUserScopeSameCourse($user, $course_id)
    {
    	return in_array($course_id, $this->getUserCoursesIndex($user));
    }
	
    private function getUserNegociationsIndex($user)
    {
    	$result = sC_getTableDataFlat("module_xpay_course_negociation", "id", sprintf("user_id = %d", $user->user['id']));
    	return $result['id'];
    }
    private function checkUserScopeSameNegociation($user, $negociation_id)
    {
    	return in_array($negociation_id, $this->getUserNegociationsIndex($user));
    }
    
	private function checkUserInDebt($user)
	{
		return $this->loadModule("xpay")->isUserInDebt($user);
	}
}
