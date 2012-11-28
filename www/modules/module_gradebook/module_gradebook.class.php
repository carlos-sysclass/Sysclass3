<?php
class module_gradebook extends MagesterExtendedModule
{
	public static $state = "experimental";
	/* USE THIS OBJECT TO MANAGE ACL, */
	/* CAN BE ROLE-BASED AND/OR TYPE-BASED */
	/* EX:
	 * public static $ACL = array(
		'method_name' => array(
			'role'	=> 'financier',
			'type'	=> 'administrator'
		)
	);
	*/

	const __GRADEBBOK_DISAPPROVED = "Reprovado";
	const __GRADEBBOK_APPROVED = "Aprovado";
	const __GRADEBOOK_WAITING = "Aguardando";
	
	public static $newActions = array(
		"edit_rule_calculation", "edit_total_calculation", "students_grades", "add_group", "move_group",
		"delete_group", "add_column", "delete_column", "load_group_rules", "load_group_grades",
		"switch_lesson", "student_sheet"
	);

	public function getName()
	{
		return "GRADEBOOK";
	}

	public function getPermittedRoles()
	{
		return array("student", "professor", "administrator");
	}
	/* ACTION FUNCTIONS */
	public function editRuleCalculationAction()
	{
		/**
		 * @todo Implementar this function. Is the default action
		 */
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		$currentUser = $this->getCurrentUser();
		$smarty = $this->getSmartyVar();
		$ranges = $this->getRanges();
		
		if (
			$currentUser->getRole($this->getCurrentLesson()) == 'professor' ||
			$currentUser->getType() == 'administrator'
		) {
			$currentLesson		= $this->getSelectedLesson();
			$currentLessonID	= $currentLesson->lesson['id'];
			// get all students that have this lesson
			$lessonUsers		= $currentLesson->getUsers('student');
			$lessonColumns		= $this->getLessonColumns($currentLessonID);
			$allUsers			= $this->getLessonUsers($currentLessonID, $lessonColumns);

			if ($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
				$gradeBookLessons = $this->getGradebookLessons(
					$currentUser->getLessons(false, 'professor'),
					$currentLessonID
				);
			} else {
				$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
			}
			
			$gradebookGroups = $this->getGradebookGroups($currentLessonID);
		} else {
			return false;
		}
		$smarty->assign("T_GRADEBOOK_RANGES", $ranges);
		
		/* Add new students to GradeBook related tables */
		$result = eF_getTableData("module_gradebook_users", "users_LOGIN", "lessons_ID=".$currentLessonID);
		$allLogins = array();
		
		foreach ($result as $user) {
			array_push($allLogins, $user['users_LOGIN']);
		}
		/*
		 	if(sizeof($result) != sizeof($lessonUsers)){ // FIXME
			$lessonColumns = $this->getLessonColumns($currentLessonID);
			foreach($lessonUsers as $userLogin => $value) {
				if(!in_array($userLogin, $allLogins)) {

					$userFields = array(
							"users_LOGIN" => $userLogin,
							"lessons_ID" => $currentLessonID,
							"score" => -1,
							"grade" => '-1'
					);

					$uid = eF_insertTableData("module_gradebook_users", $userFields);

					foreach($lessonColumns as $key => $column) {

						$fieldsGrades = array(
					 		"oid" => $key,
					 		"grade" => -1,
					 		"users_LOGIN" => $userLogin
						);
							
						$type = $column['refers_to_type'];
						$id = $column['refers_to_id'];
							
						eF_insertTableData("module_gradebook_grades", $fieldsGrades);
							
						if($type != 'real_world') {
							$this->importGrades($type, $id, $key, $userLogin);
						}
					}
					$this->computeScoreGrade($lessonColumns, $ranges, $userLogin, $uid);
				}
			}
		}
		*/
		/* End */

		$lessonColumns = $this->getLessonColumns($currentLessonID);
		$allUsers = $this->getLessonUsers($currentLessonID, $lessonColumns);

		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} else {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		}
		$smarty->assign("T_GRADEBOOK_LESSON_ID", $currentLessonID);

		$smarty->assign("T_GRADEBOOK_LESSON_COLUMNS", $lessonColumns);
		$smarty->assign("T_GRADEBOOK_LESSON_USERS", $allUsers);
		$smarty->assign("T_GRADEBOOK_GRADEBOOK_LESSONS", $gradeBookLessons);
	
		$smarty->assign("T_GRADEBOOK_GROUPS", $gradebookGroups);
		
		$currentCourse = $this->getSelectedCourse();
		$currentCourseID = $currentCourse->course['id'];
		$smarty->assign("T_GRADEBOOK_COURSE_ID", $currentCourseID);

		$currentClasse = $this->getSelectedClasse();
		$currentClasseID = $currentClasse->classe['id'];
		$smarty->assign("T_GRADEBOOK_CLASSE_ID", $currentClasseID);
		$gradeBookClasses = $this->getGradebookClasses($currentCourseID);
		$smarty->assign("T_GRADEBOOK_CLASSES", $gradeBookClasses);

		$this->setMessageVar($message, $message_type);
	}
	public function editTotalCalculationAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		
		$currentLesson = $this->getSelectedLesson();
		$currentLessonID = $currentLesson->lesson['id'];
		
		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} else {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		}
		$smarty->assign("T_GRADEBOOK_LESSON_ID", $currentLessonID);
		$smarty->assign("T_GRADEBOOK_GRADEBOOK_LESSONS", $gradeBookLessons);

		$gradebookGroups = $this->getGradebookGroups($currentLessonID);
//		var_dump($gradebookGroups);
//		exit;
		$smarty->assign("T_GRADEBOOK_GROUPS", $gradebookGroups);
		//$smarty->assign("T_GRADEBOOK_GROUPS_REQUIRE_STATUSES", );
		
		$currentCourse = $this->getSelectedCourse();
		$currentCourseID = $currentCourse->course['id'];
		$smarty->assign("T_GRADEBOOK_COURSE_ID", $currentCourseID);
		$currentClasse = $this->getSelectedClasse();
		$currentClasseID = $currentClasse->classe['id'];
		$smarty->assign("T_GRADEBOOK_CLASSE_ID", $currentClasseID);
		$gradeBookClasses = $this->getGradebookClasses($currentCourseID);
		$smarty->assign("T_GRADEBOOK_CLASSES", $gradeBookClasses);
	}
	public function studentsGradesAction() {
		if ($this->getCurrentUser()->getType() != 'administrator' && $this->getCurrentUser()->getType() != 'professor') {
			return false;
		}

		
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		
		$currentLesson = $this->getSelectedLesson();
		$currentLessonID = $currentLesson->lesson['id'];
		
		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} else {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		}
		$smarty->assign("T_GRADEBOOK_LESSON_ID", $currentLessonID);
		$smarty->assign("T_GRADEBOOK_GRADEBOOK_LESSONS", $gradeBookLessons);
		$gradebookGroups = $this->getGradebookGroups($currentLessonID);
		$smarty->assign("T_GRADEBOOK_GROUPS", $gradebookGroups);
		
		$currentCourse = $this->getSelectedCourse();
		$currentCourseID = $currentCourse->course['id'];
		$smarty->assign("T_GRADEBOOK_COURSE_ID", $currentCourseID);
		$currentClasse = $this->getSelectedClasse();
		$currentClasseID = $currentClasse->classe['id'];
		
		$smarty->assign("T_GRADEBOOK_CLASSE_ID", $currentClasseID);
		$gradeBookClasses = $this->getGradebookClasses($currentCourseID);
		$smarty->assign("T_GRADEBOOK_CLASSES", $gradeBookClasses);
		
	}
	public function addGroupAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		
		if (is_numeric($_SESSION["grade_lessons_ID"])) {
			
		
			if (
				isset($_POST['name']) && strlen($_POST['name']) >= 3 &&
				isset($_POST['require_status']) && is_numeric($_POST['require_status']) &&
				isset($_POST['min_value']) && is_numeric($_POST['min_value']) &&
				isset($_POST['pass_value']) && is_numeric($_POST['pass_value']) &&
				$_POST['pass_value'] >= $_POST['min_value']
			) {
				$fields = array(
					"lesson_id"			=> $_SESSION["grade_lessons_ID"],
					"classe_id"			=> is_numeric($_SESSION["grade_classe_ID"]) ? $_SESSION["grade_classe_ID"] : 0,
					"name"				=> $_POST['name'],
					"require_status"	=> $_POST['require_status'],
					"min_value"			=> $_POST['min_value'],
					"pass_value"		=> $_POST['pass_value']
				);
				
				$fields['id'] = eF_insertTableData("module_gradebook_groups", $fields);

				$return = array(
					"message" 		=> "Registrado com sucesso",
					"message_type" 	=> "success",
					"status"		=> "ok",
					"data" => $fields
				);
				echo json_encode($return);
				exit;
			}
		}
		$return = array(
			"message" 		=> "Occoreu um erro ao tentar registrar a regra",
			"message_type" 	=> "error"
		);
		echo json_encode($return);
		exit;
	}
	public function moveGroupAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		if (
			in_array($_POST['to'], array('up', 'down')) &&
			eF_checkParameter($_POST['group_id'], "id")
		) {
			$groupID = $_POST['group_id'];
			
			$currentLesson = $this->getSelectedLesson();
			$currentLessonID = $currentLesson->lesson['id'];
			
			$groups = $this->getGradebookGroups($currentLessonID);
			
			$orderIndexes = array();
			
			$i = 0;
			foreach($groups as $index => $group) {
				$orderIndexes[$i] = $group['id'];
				$i++;
			}
			
			foreach($orderIndexes as $index => $orderGroupID) {
				if ($orderGroupID == $groupID) {
					//$group_INDEX = $index;
					if ($_POST['to'] == 'down') {
						if (array_key_exists($index+1, $orderIndexes)) {
							$aux = $orderIndexes[$index];
							$orderIndexes[$index] = $orderIndexes[$index+1];
							$orderIndexes[$index+1] = $aux;
						}
					} elseif ($_POST['to'] == 'up') {
						if (array_key_exists($index-1, $orderIndexes)) {
							$aux = $orderIndexes[$index];
							$orderIndexes[$index] = $orderIndexes[$index-1];
							$orderIndexes[$index-1] = $aux;
						}
					}
				}
			}
			$currentClasseID = is_numeric($_SESSION["grade_classe_ID"]) ? $_SESSION["grade_classe_ID"] : 0;

			
			eF_deleteTableData(
				"module_gradebook_groups_order",
				sprintf("lesson_id =%d AND classe_id = %d",
					$currentLessonID, $currentClasseID
				)
			);
			$insertFields = array();
			
			foreach($orderIndexes as $order_index => $orderGroupID) {
				
				$insertFields[] = array(
					"group_id"			=> $orderGroupID,
					"lesson_id"			=> $currentLessonID,
					"classe_id"			=> $currentClasseID,
					"order_index" => $order_index
				);
			}
			eF_insertTableDataMultiple("module_gradebook_groups_order", $insertFields);
			
			$return = array(
				"message" 		=> "Grupo movido com sucesso",
				"message_type" 	=> "success",
				"status"		=> "ok"
			);
		} else {
			$return = array(
				"message" 		=> "Occoreu um erro ao tentar mover o grupo solicitado.",
				"message_type" 	=> "error"
			);
		}
		echo json_encode($return);
		exit;
	}
	public function deleteGroupAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		if (
			is_numeric($_SESSION["grade_lessons_ID"]) &&
			isset($_POST['group_id'])
		) {
			$group_id = $_POST['group_id'];
			
			$groupData = eF_getTableData(
				"module_gradebook_groups", 
				"*",
				sprintf("lesson_id = %d AND id = %d", $_SESSION["grade_lessons_ID"], $group_id) 
			);
			
			if (count($groupData) == 0) {
				$return = array(
					"message" 		=> "Não é possível excluir grupos compartilhados entre disciplinas",
					"message_type" 	=> "failure"
				);
			} else {
				$updateStatus = eF_updateTableData(
					"module_gradebook_objects",
					array("group_id" => 1),
					sprintf("lessons_ID = %d AND group_id = %d", $_SESSION["grade_lessons_ID"], $group_id)
				);
				$deleteStatus = eF_deleteTableData(
						"module_gradebook_groups",
						sprintf("lesson_id = %d AND id = %d", $_SESSION["grade_lessons_ID"], $group_id)
				);
					
				$return = array(
					"message" 		=> "Grupo excluído com sucesso",
					"message_type" 	=> "success",
					"status"		=> "ok",
					"data"			=> $groupData[0]
				);
			}
		} else {
			$return = array(
				"message" 		=> "Occoreu um erro ao tentar excluir o grupo solicitado",
				"message_type" 	=> "error"
			);
		}
		echo json_encode($return);
		exit;
	}
	public function addColumnAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		
		$currentLesson = $this->getSelectedLesson();
		$currentLessonID = $currentLesson->lesson['id'];
/*		
		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} else {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		}
*/		
		$tests = $currentLesson->getTests(true, true);
		$scormTests = $currentLesson->getScormTests();
		$projects = $currentLesson->getProjects(false);
			
		$groupsFull = $this->getGradebookGroups($currentLesson->lesson['id']);
		$groups = array();
		foreach($groupsFull as $group) {
			$groups[$group['id']] = $group['name'];
		}
			
		$weights = array();
		$refersTo = array("real_world" => _GRADEBOOK_REAL_WORLD_OBJECT, "progress" => _LESSONPROGRESS);
		
		for($i = 1; $i <= 10; $i++)
			$weights[$i] = $i;
		
		if($currentLesson->options['tests'] == 1) {
			foreach($tests as $key => $test)
				$refersTo['test_'.$key] = _TEST.': '.$test->test['name'];
		}
		
		if($currentLesson->options['scorm'] == 1) {
		
			foreach($scormTests as $key => $scormTest) {
			
				$scorm = eF_getTableData("content", "name", "id=".$scormTest);
				$refersTo['scormtest_'.$scormTest] = _SCORM.' '._TEST.': '.$scorm[0]['name'];
			}
		}
		
		if($currentLesson->options['projects'] == 1) {
			foreach($projects as $key => $project)
				$refersTo['project_'.$key] = _PROJECT.': '.$project['title'];
		}
	
		$form = new HTML_QuickForm("add_column_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
		$form->addElement('text', 'column_name', _GRADEBOOK_COLUMN_NAME, 'class = "inputText"');
		$form->addElement('select', 'column_group_id', __GRADEBOOK_COLUMN_GROUP, $groups);
		$form->addElement('select', 'column_weight', _GRADEBOOK_COLUMN_WEIGHT, $weights);
		$form->addElement('select', 'column_refers_to', _GRADEBOOK_COLUMN_REFERS_TO, $refersTo);
		$form->addRule('column_name', _THEFIELD.' "'._GRADEBOOK_COLUMN_NAME.'" '._ISMANDATORY, 'required', null, 'client');
		$form->addElement('submit', 'submit', _SUBMIT, 'class = "flatButton"');
	
		if($form->isSubmitted() && $form->validate()){
		
			$values = $form->exportValues();
			$fields = array(
				"name" => $values['column_name'],
				"group_id" => $values['column_group_id'],
				"weight" => $values['column_weight'],
				"lessons_ID" => $currentLessonID,
				"creator" => $_SESSION['s_login']
			);
		
			if($values['column_refers_to'] == "real_world"){
				$fields['refers_to_type'] = 'real_world';
				$fields['refers_to_id'] = -1;
			} elseif($values['column_refers_to'] == "progress") {
				$fields['refers_to_type'] = 'progress';
				$fields['refers_to_id'] = $currentLessonID;
			} else {
				$type = explode('_', $values['column_refers_to']);
				$fields['refers_to_type'] = $type[0];
				$fields['refers_to_id'] = $type[1];
			}
		
			if(($objectID = eF_insertTableData("module_gradebook_objects", $fields))) {
		
				$smarty->assign("T_GRADEBOOK_MESSAGE", _GRADEBOOK_COLUMN_SUCCESSFULLY_ADDED);
		
				foreach($lessonUsers as $userLogin => $value){
		
					$fieldsGrades = array(
						"oid" => $objectID,
						"grade" => -1,
						"users_LOGIN" => $userLogin
					);
		
					if(eF_insertTableData("module_gradebook_grades", $fieldsGrades)) {
						$smarty->assign("T_GRADEBOOK_MESSAGE", _GRADEBOOK_COLUMN_SUCCESSFULLY_ADDED);
					} else {
						$message = _GRADEBOOK_COLUMN_ADD_PROBLEM;
						$message_type = 'failure';
					}
				}
			} else {
				$message = _GRADEBOOK_COLUMN_ADD_PROBLEM;
				$message_type = 'failure';
			}
		}
		
		$renderer = prepareFormRenderer($form);
		$form->accept($renderer);
		$smarty->assign('T_GRADEBOOK_ADD_COLUMN_FORM', $renderer->toArray());
		
		if ($_GET['popup'] == 1) {
			unset($GLOBALS['message']);
			unset($GLOBALS['message_type']);
		}
		
	}
	public function deleteColumnAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		
		$currentLesson = $this->getSelectedLesson();
		$currentLessonID = $currentLesson->lesson['id'];
		
		$lessonColumns = $this->getLessonColumns($currentLessonID);
		
		if (
				isset($_POST['column_id']) &&
				eF_checkParameter($_POST['column_id'], 'id') && 
				in_array($_POST['column_id'], array_keys($lessonColumns))
		) {
			$column_id = $_POST['column_id'];
			$object = eF_getTableData("module_gradebook_objects", "creator", "id=".$column_id);
		
			//   if($object[0]['creator'] != $_SESSION['s_login']){
			//    eF_redirect($this->moduleBaseUrl."&message=".urlencode(_GRADEBOOK_NOACCESS));
			//    exit;
			//   }
		
			eF_deleteTableData("module_gradebook_objects", "id=".$column_id);
			eF_deleteTableData("module_gradebook_grades", "oid=".$column_id);
			
			$return = array(
					"message" 		=> "Coluna Excluída com sucesso",
					"message_type" 	=> "success",
					"status"		=> "ok",
					"data" => $fields
			);
			echo json_encode($return);
			exit;
		} else {
			$return = array(
					"message" 		=> "Occoreu um erro ao tentar excluir esta coluna",
					"message_type" 	=> "error"
			);
			echo json_encode($return);
			exit;
		}
	}
	public function loadGroupRulesAction() {
		if ($this->getCurrentUser()->getType() != 'administrator') {
			return false;
		}
		
		$smarty = $this->getSmartyVar();
		
		is_numeric($_POST['group_id']) ? $currentGroupID = $_POST['group_id'] : (
			is_numeric($_SESSION['gradebook_group_id']) ? $currentGroupID = $_SESSION['gradebook_group_id'] : $currentGroupID = 1
		);
		
		
		if ( is_numeric($_SESSION["grade_lessons_ID"]) ) {
			$currentLessonID = $_SESSION["grade_lessons_ID"];
			$_SESSION['gradebook_group_id'] = $currentGroupID;
			$lessonColumns = $this->getLessonColumns($currentLessonID, $currentGroupID);
			$smarty -> assign("T_GRADEBOOK_LESSON_COLUMNS", $lessonColumns);
		}
		
		$this->assignSmartyModuleVariables();
		
		$template = $this->moduleBaseDir . 'templates/actions/' . $_GET['action'] . '.tpl';
		echo $smarty->fetch($template);
		exit;
	}	
	public function loadGroupGradesAction() {
		if ($this->getCurrentUser()->getType() != 'administrator' && $this->getCurrentUser()->getType() != 'professor') {
			return false;
		}
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		
		$currentLesson = $this->getSelectedLesson();
		$currentLessonID = $currentLesson->lesson['id'];

		$currentCourse = $this->getSelectedCourse();
		$currentClasse = $this->getSelectedClasse();
		
		is_numeric($_POST['group_id']) ? $currentGroupID = $_POST['group_id'] : (
		is_numeric($_SESSION['gradebook_group_id']) ? $currentGroupID = $_SESSION['gradebook_group_id'] : $currentGroupID = 1
		);
		
		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} else {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		}

		/* Add new students to GradeBook related tables */
		$result = eF_getTableData("module_gradebook_users", "users_LOGIN", "lessons_ID=".$currentLessonID);
		$allLogins = array();
		
		foreach($result as $user)
			array_push($allLogins, $user['users_LOGIN']);
		/*
		if(sizeof($result) != sizeof($lessonUsers)){ // FIXME
		
			$lessonColumns = $this->getLessonColumns($currentLessonID, $currentGroupID);

			foreach($lessonUsers as $userLogin => $value){
		
				if(!in_array($userLogin, $allLogins)){
					
					$userFields = array(
						"users_LOGIN" => $userLogin,
						"lessons_ID" => $currentLessonID,
						"score" => -1,
						"grade" => '-1'
					);
		
					$uid = eF_insertTableData("module_gradebook_users", $userFields);
		
					foreach($lessonColumns as $key => $column) {
		
						$fieldsGrades = array(
							"oid" => $key,
							"grade" => -1,
							"users_LOGIN" => $userLogin
						);
		
						$type = $column['refers_to_type'];
						$id = $column['refers_to_id'];
		
						eF_insertTableData("module_gradebook_grades", $fieldsGrades);
		
						if($type != 'real_world')
							$this->importGrades($type, $id, $key, $userLogin);
					}
		
					$this->computeScoreGrade($lessonColumns, $ranges, $userLogin, $uid);
				}
			}
		}
		*/
		/* End */
		$lessonColumns = $this->getLessonColumns($currentLessonID, $currentGroupID);
		
		if (is_null($currentClasse)) {
			$this->checkLessonUsers($currentLessonID, $lessonColumns);
			$allUsers = $this->getLessonUsers($currentLessonID, $lessonColumns);
		} else {
			$this->checkLessonUsers($currentLessonID, $lessonColumns, $currentClasse->classe['id']);
			$allUsers = $this->getLessonUsers($currentLessonID, $lessonColumns, $currentClasse->classe['id']);
		}
		
		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} else {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		}
		
		$smarty->assign("T_GRADEBOOK_GROUP_ID", $currentGroupID);
		
 		$smarty->assign("T_GRADEBOOK_LESSON_ID", $currentLessonID);
		
		$smarty->assign("T_GRADEBOOK_LESSON_COLUMNS", $lessonColumns);
		$smarty->assign("T_GRADEBOOK_LESSON_USERS", $allUsers);
		$smarty->assign("T_GRADEBOOK_GRADEBOOK_LESSONS", $gradeBookLessons);
		
		$gradebookGroups = $this->getGradebookGroups($currentLessonID);
		$smarty->assign("T_GRADEBOOK_GROUPS", $gradebookGroups);
		
		$gradebookScores = $this->computeFinalScore($currentLessonID);
		/*
		echo "<pre>";
		var_dump($gradebookScores);
		echo "</pre>";
		*/
		$smarty->assign("T_GRADEBOOK_SCORES", $gradebookScores);
		
		/*
		$smarty = $this->getSmartyVar();
		
		is_numeric($_POST['group_id']) ? $currentGroupID = $_POST['group_id'] : (
		is_numeric($_SESSION['gradebook_group_id']) ? $currentGroupID = $_SESSION['gradebook_group_id'] : $currentGroupID = 1
		);
		
		if ( is_numeric($_SESSION["grade_lessons_ID"]) ) {
			$currentLessonID = $_SESSION["grade_lessons_ID"];
			$_SESSION['gradebook_group_id'] = $currentGroupID;
			$lessonColumns = $this->getLessonColumns($currentLessonID, $currentGroupID);
			$smarty -> assign("T_GRADEBOOK_LESSON_COLUMNS", $lessonColumns);
		}
		*/
		$this->assignSmartyModuleVariables();
		$template = $this->moduleBaseDir . 'templates/actions/' . $this->getCurrentAction() . '.tpl';
		echo $smarty->fetch($template);
		exit;

		
		
	}
	public function importStudentsGradesAction() {
		if ($this->getCurrentUser()->getType() != 'administrator' && $this->getCurrentUser()->getType() != 'professor') {
			return false;
		}
		/*
		$_POST['column_id']	= 194;
		$_POST['from']		= 'students_grades';
		$_POST['group_id']	= 7;
		*/
		$currentLesson = $this->getSelectedLesson();
		$currentLessonID = $currentLesson->lesson['id'];
		
		is_numeric($_POST['group_id']) ? $currentGroupID = $_POST['group_id'] : (
			is_numeric($_SESSION['gradebook_group_id']) ? $currentGroupID = $_SESSION['gradebook_group_id'] : $currentGroupID = 1
		);
		$lessonColumns = $this->getLessonColumns($currentLessonID, $currentGroupID);

		if(
			isset($_POST['column_id']) &&
			eF_checkParameter($_POST['column_id'], 'id')
			&& in_array($_POST['column_id'], array_keys($lessonColumns))
		) {
			$lessonUsers = $currentLesson->getUsers('student');
			$column_ID = $_POST['column_id'];
		
			$result = eF_getTableData("module_gradebook_objects", "refers_to_type, refers_to_id", "id=".$column_ID);

			$type = $result[0]['refers_to_type'];
			$id = $result[0]['refers_to_id'];
			$oid = $column_ID;
		
			foreach($lessonUsers as $userLogin => $value) {
				$this->importGrades($type, $id, $oid, $userLogin);

			}
			$return = array(
				"message" 		=> "Notas importadas com sucesso!",
				"message_type" 	=> "success",
				"status"		=> "ok"
			);
		} else {
			$return = array(
				"message" 		=> "Occoreu um erro ao tentar calcular esta coluna. Caso os problemas persistam, consulte o administrador do sistema.",
				"message_type" 	=> "error"
			);
		}
		echo json_encode($return);
		exit;
	}
	/*
	public function changeGradeAction() {
		if ($this->getCurrentUser()->getType() != 'administrator' && $this->getCurrentUser()->getType() != 'professor') {
			return false;
		}
		$newGrade = $_GET['grade'];
		
		$currentUser = $this->getCurrentUser();
		
		if ($currentUser->getType() == 'administrator' || $currentUser->getType() == 'professor') {

			try{
				if($newGrade != ''){
			
					if(eF_checkParameter($newGrade, 'uint') === false || $newGrade > 100)
						throw new MagesterContentException(_GRADEBOOK_INVALID_GRADE.': "'.$newGrade.'". '._GRADEBOOK_VALID_GRADE_SPECS,
								MagesterContentException :: INVALID_SCORE);
				}
				else
					$newGrade = -1;
			
				eF_updateTableData("module_gradebook_grades", array("grade" => $newGrade), "gid=".$_GET['gid']);
				
				
				$response = array(
					"message" 		=> "Nota alterada com sucesso",
					"message_type"	=> "success"
				);
			} catch(Exception $e) {
				$response = array(
					"message" 		=> $e->getMessage(),
					"message_type"	=> "failure"
				);
			}
		} else {
			$response = array(
				"message" 		=> "Acesso não autorizado",
				"message_type"	=> "error"
			);
		}
		echo json_encode($response);
		exit;
	}
	*/
	public function setGradeAction()
	{
		$currentUser = $this->getCurrentUser();

		$newGrade = $_POST['grade'];
		
		if ($currentUser->getType() == 'administrator' || $currentUser->getType() == 'professor') {
			try {
				if ($newGrade != '') {
					if (eF_checkParameter($newGrade, 'uint') === false || $newGrade > 100) {
						throw new MagesterContentException(
							_GRADEBOOK_INVALID_GRADE . ': "' . $newGrade . '". ' ._GRADEBOOK_VALID_GRADE_SPECS,
							MagesterContentException :: INVALID_SCORE
						);
					}
				} else {
					$newGrade = -1;
				}
				
				$login 	= $_POST['login'];
				$oid	= $_POST['oid'];

				eF_insertOrupdateTableData(
					"module_gradebook_grades",
					array(
						"grade" 		=> $newGrade,
						"oid"			=> $oid,
						"users_LOGIN"	=> $login
					),
					sprintf("oid = %d AND users_LOGIN = '%s'", $oid, $login)
				);
				
				// GET LESSONID BY OID
				$lessonID = reset(eF_getTableData("module_gradebook_objects", "lessons_ID", sprintf("id = %d", $oid)));
				
				$response = array(
					"message" 		=> "Nota alterada com sucesso",
					"message_type"	=> "success",
					"scores"		=> $this->computeFinalScore($lessonID['lessons_ID'], $login)
				);
			} catch(Exception $e) {
				$response = array(
					"message" 		=> $e->getMessage(),
					"message_type"	=> "failure"
				);
			}
		} else {
			$response = array(
				"message" 		=> "Acesso não autorizado",
				"message_type"	=> "error"
			);
		}
		echo json_encode($response);
		exit;
	}
	public function studentSheetAction()
	{
		// GENERATE BOLETIM
		$smarty = $this->getSmartyVar();
		
		$currentUser = $this->getCurrentUser();
		
		if ($currentUser->getType() == 'administrator' || $currentUser->getType() == 'professor') {
			if (isset($_GET['xuser_login'])) {
				$selectedUser = MagesterUserFactory::factory($_GET['xuser_login']);
			} else {
				// PLEASE SELECT A USER TO SHOW HIS SHEET
				//$selectedUser = MagesterUserFactory::factory('frederico.lima');
				return false;
			}
		} else {
			$selectedUser = $currentUser;
		}
	
		$userCourses = $selectedUser->getUserCourses(array('return_objects' => true));
		$userLessons = $selectedUser->getUserLessons(array('return_objects' => false));
		$userLessonsIndexes = array_keys($userLessons);
		
		$userLogin = $selectedUser->user['login'];
		$userLessons = array();
		
		if ($currentLesson = $this->getSelectedLesson()) {
			$this->addModuleData("lesson_id", $currentLesson->lesson['id']);
			$currentLessonID = $currentLesson->lesson['id'];
		}
		if ($currentCourse = $this->getSelectedCourse()) {
			$this->addModuleData("course_id", $currentCourse->course['id']);
		}
		
		foreach ($userCourses as $course) {
		
			$courseLessons = $course->getCourseLessons(array('return_objects' => false));
			
			
		
			foreach ($courseLessons as $courseLesson) {
				if (in_array($courseLesson['id'], $userLessonsIndexes)) {
					
					if (isset($currentLessonID) && $currentLessonID == $courseLesson['id']) {
						$autocompletevalue = $courseLesson['name'];
					}
					
					
					$coursesData[] = array(
						'course_id'		=> $course->course['id'],
						'course_name'	=> $course->course['name'],
						'lesson_id'		=> $courseLesson['id'],
						'lesson_name'	=> $courseLesson['name'],
						'id'			=> $course->course['id'] . "_" . $courseLesson['id'],
						'value'			=> $courseLesson['name'],
						'label'			=> $courseLesson['name']
					);
					
					$courseLesson['course_id'] = $course->course['id'];
					$courseLesson['scores'] = $this->computeFinalScore($courseLesson['id'], $userLogin);
					$courseLesson['columns'] = $this->getLessonColumns($courseLesson['id']);
					$courseLesson['groups'] = $this->getGradebookGroups($courseLesson['id']);
					
					foreach ($courseLesson['columns'] as $key => $object) {
						$result = eF_getTableData(
							"module_gradebook_grades",
							"grade",
							"oid=".$object['id']." and users_LOGIN='".$userLogin."'"
						);
						$grade = $result[0]['grade'];
							
						$grade = min(max($grade, 0), 100);
						
						$courseLesson['scores']['columns'][$object['id']] = $grade;
					}
					
					$userLessons[$courseLesson['id']] = $courseLesson;
				}
			}
		}
		$smarty->assign("T_GRADEBOOK_LESSONS_SCORES", $userLessons);
		
		// TO USE ON AUTOCOMPLETE
		$this->view()->createBlock("autocategorycomplete", ".course-lesson-autocomplete", array("source" => $coursesData, "value" => $autocompletevalue));
		
	}
	/*
	public function loadStudentLessonSheetAction()
	{

		
		$_POST['course_id'] = 13;
		$_POST['lesson_id'] = 24;
		
		
		$login = $selectedUser->user['login'];
		
		$selectedLesson = $this->getSelectedLesson($currentUser);
		$lessonID = $selectedLesson->lesson['id'];
		
		try {
			$response = $this->computeFinalScore($lessonID, $login);
		} catch (Exception $e) {
			$response = array (
				'message' 		=> $e->getMessage(),
				'message_type'	=> 'failure'
			);
		}
		
		var_dump($response);
		
		echo json_encode($response);
		exit;
	}
	*/
	public function switchLessonAction()
	{
		$currentUser = $this->getCurrentUser();
		if ($currentUser->getType() != 'administrator' && $currentUser->getType() != 'professor') {
			return false;
		}
		
		/*
		$currentLessonID = $_SESSION["grade_lessons_ID"];

		var_dump($currentLessonID);
		
		if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
			$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
		} elseif($currentUser->getType() == 'administrator') {
			$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
		} else {
			$gradeBookLessons = array();
		}
var_dump(array_keys($gradeBookLessons));
var_dump(
                        in_array($_GET['lesson_id'], array_keys($gradeBookLessons)));

*/		
		if(
			isset($_GET['lesson_id']) &&
			eF_checkParameter($_GET['lesson_id'], 'id')
		) {
			$_SESSION["grade_lessons_ID"] = $_GET['lesson_id'];
		}
		
		if(

			isset($_GET['classe_id']) &&
			eF_checkParameter($_GET['classe_id'], 'id')/* &&
			in_array($_GET['classe_id'], array_keys($gradeBookClasses)) */
		) {
			$_SESSION["grade_classes_ID"] = $_GET['classe_id'];
		} elseif ($_GET['classe_id'] == 0) {
			unset($_SESSION["grade_classes_ID"]);
		}
		
		if(
			isset($_GET['course_id']) &&
			eF_checkParameter($_GET['course_id'], 'id')/* &&
			in_array($_GET['classe_id'], array_keys($gradeBookClasses)) */
		) {
			$_SESSION["grade_courses_ID"] = $_GET['course_id'];
		//} elseif ($_GET['course_id'] == 0) {
		//	unset($_SESSION["grade_courses_ID"]);
		}
		
		if (!empty($_GET['from'])) {
			eF_redirect("location:".$this->moduleBaseUrl . "&action=" . $_GET['from']);
		} else {
			eF_redirect("location:".$this->moduleBaseUrl);
		}
		exit;
	}
	public function getDefaultAction() {
		if ($this->getCurrentUser()->getType() == 'professor') {
			return "students_grades";
		} elseif ($this->getCurrentUser()->getType() == 'administrator') {
			return "edit_rule_calculation";
		} elseif ($this->getCurrentUser()->getType() == 'student') {
			return "student_sheet";
		}
	}
	/**
	 * @todo TRANSFER THIS FUNCTION TO YOUR OWN MODULE 
	 */
	public function loadClassesAction() {
		if (is_numeric($_POST['course_id'])) {
			$courseClasses = $this->getGradebookClasses($_POST['course_id']);
			
			$classeAssoc = array();
			foreach($courseClasses as $classe) {
				$classeAssoc[$classe['id']] = $classe['name'];
			}

			echo json_encode($classeAssoc);
			exit;
		}
		return false;
	}
	

	public function getModule(){
		$result = parent::getModule();
		if (self::$state == 'experimental') {
			if ($_GET['popup'] == 1) {
				unset($GLOBALS['message']);
				unset($GLOBALS['message_type']);
			} else {
				$this->setMessageVar("Este módulo é experimental. Por favor informar quaisquer erros encontrados.", "warning");
			}
		}
		return $result;
	}
	public function getSmartyTpl(){
		
		
		if (self::$state == 'experimental') {
			if ($_GET['popup'] == 1) {
				unset($GLOBALS['message']);
				unset($GLOBALS['message_type']);
			} else {
				$this->setMessageVar("Este módulo é experimental. Por favor informar quaisquer erros encontrados.", "warning");
			}
		}
				
		if ($this->getCurrentAction()) {
			if (in_array($this->getCurrentAction(), self::$newActions)) {
				return parent::getSmartyTpl();
			}
		}

		$currentUser = $this->getCurrentUser();
		$smarty = $this->getSmartyVar();
		
		$ranges = $this->getRanges();

		$smarty->assign("T_GRADEBOOK_BASEURL", $this->moduleBaseUrl);
		$smarty->assign("T_GRADEBOOK_BASEDIR", $this->moduleBaseDir);
		$smarty->assign("T_GRADEBOOK_BASELINK", $this->moduleBaseLink);
		$smarty->assign("T_GRADEBOOK_ACTION", $_GET['action']);

		if($currentUser->getRole($this->getCurrentLesson()) == 'professor' || $currentUser->getType() == 'administrator'){
			$currentLesson = $this->getCurrentLesson();
			if (isset($_SESSION["grade_lessons_ID"]) && is_numeric($_SESSION["grade_lessons_ID"]) && $currentUser->getType() == 'administrator') {
				$currentLesson = new MagesterLesson($_SESSION["grade_lessons_ID"]);
			}
			if (isset($_GET['lessons_ID']) && is_numeric($_GET['lessons_ID']) && $currentUser->getType() == 'administrator') {
				$currentLesson = new MagesterLesson($_GET['lessons_ID']);
				$_SESSION["grade_lessons_ID"] = $_GET['lessons_ID'] ;
			}
			
			
			if (is_null($currentLesson)) {
				if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
					$allLessons = $currentUser->getLessons(true, 'professor');
				} else {
					$allLessons = MagesterLesson::getLessons(true);
				}
				$currentLesson = reset($allLessons);
				
				$_SESSION["grade_lessons_ID"] = $currentLesson->lesson['id'];
			}
			$currentLessonID = $currentLesson->lesson['id'];
			$lessonUsers = $currentLesson->getUsers('student'); // get all students that have this lesson
			$lessonColumns = $this->getLessonColumns($currentLessonID);
			$allUsers = $this->getLessonUsers($currentLessonID, $lessonColumns);
			
			if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
				$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
			} else {
				$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
			}
			
			$gradebookGroups = $this->getGradebookGroups($currentLessonID);
		}
		else if($currentUser->getRole($this->getCurrentLesson()) == 'student'){

			$currentLesson = $this->getCurrentLesson();
			$currentLessonID = $currentLesson->lesson['id'];
		}

		if(
				isset($_GET['import_grades']) &&
				eF_checkParameter($_GET['import_grades'], 'id')
				&& in_array($_GET['import_grades'], array_keys($lessonColumns))
		) {
			/*
			$object = eF_getTableData("module_gradebook_objects", "creator", "id=".$_GET['import_grades']);

			//   if($object[0]['creator'] != $_SESSION['s_login']){
			//    eF_redirect($this->moduleBaseUrl."&message=".urlencode(_GRADEBOOK_NOACCESS));
			//    exit;
			//   }

			$result = eF_getTableData("module_gradebook_objects", "refers_to_type, refers_to_id", "id=".$_GET['import_grades']);
			$type = $result[0]['refers_to_type'];
			$id = $result[0]['refers_to_id'];
			$oid = $_GET['import_grades'];

			foreach($lessonUsers as $userLogin => $value) {
				
				$this->importGrades($type, $id, $oid, $userLogin);
			}
			*/
		} elseif (
				isset($_GET['delete_column']) && 
				eF_checkParameter($_GET['delete_column'], 'id') 
				&& in_array($_GET['delete_column'], array_keys($lessonColumns))
		) {
			$object = eF_getTableData("module_gradebook_objects", "creator", "id=".$_GET['delete_column']);

			//   if($object[0]['creator'] != $_SESSION['s_login']){
			//    eF_redirect($this->moduleBaseUrl."&message=".urlencode(_GRADEBOOK_NOACCESS));
			//    exit;
			//   }

			//eF_deleteTableData("module_gradebook_objects", "id=".$_GET['delete_column']);
			//eF_deleteTableData("module_gradebook_grades", "oid=".$_GET['delete_column']);
		} elseif(isset($_GET['compute_score_grade']) && $_GET['compute_score_grade'] == '1') {

			foreach($allUsers as $uid => $student)
				$this->computeScoreGrade($lessonColumns, $ranges, $student['users_LOGIN'], $uid);
		} elseif(isset($_GET['export_excel']) && ($_GET['export_excel'] == 'one' || $_GET['export_excel'] == 'all')) {

			require_once 'Spreadsheet/Excel/Writer.php';

			$workBook = new Spreadsheet_Excel_Writer();
			$workBook->setTempDir(G_UPLOADPATH);
			$workBook->setVersion(8);
			$workBook->send('GradeBook.xls');

			if($_GET['export_excel'] == 'one'){

				$workSheet = &$workBook->addWorksheet($currentLesson->lesson['name']);
				$this->professorLessonToExcel($currentLessonID, $currentLesson->lesson['name'], $workBook, $workSheet);
			}
			else if($_GET['export_excel'] == 'all'){

				$professorLessons = $currentUser->getLessons(false, 'professor');

				foreach($professorLessons as $key => $value){

					$subLesson = new MagesterLesson($key);
					$subLessonUsers = $subLesson->getUsers('student'); // get all students that have this lesson
					$result = eF_getTableData("module_gradebook_users", "count(uid) as total_users", "lessons_ID=".$key);

					if($result[0]['total_users'] != 0){ // module installed for this lesson

						$workSheet = &$workBook->addWorksheet($subLesson->lesson['name']);
						$this->professorLessonToExcel($key, $subLesson->lesson['name'], $workBook, $workSheet);
					}
				}
			}

			$workBook->close();
			exit;
		}
		else if(
			isset($_GET['export_student_excel']) && 
			($_GET['export_student_excel'] == 'current' || $_GET['export_student_excel'] == 'all')
		) {

			require_once 'Spreadsheet/Excel/Writer.php';

			$workBook = new Spreadsheet_Excel_Writer();
			$workBook->setTempDir(G_UPLOADPATH);
			$workBook->setVersion(8);
			$workBook->send('GradeBook.xls');

			if($_GET['export_student_excel'] == 'current') {

				$workSheet = &$workBook->addWorksheet($currentLesson->lesson['name']);
				$this->studentLessonToExcel($currentLessonID, $currentLesson->lesson['name'], $currentUser, $workBook, $workSheet);
			} elseif ($_GET['export_student_excel'] == 'all') {

				$studentLessons = $currentUser->getLessons(false, 'student');

				foreach($studentLessons as $key => $value){

					// Is GradeBook installed for this lesson ?
					$installed = eF_getTableData("module_gradebook_users", "*",
							"lessons_ID=".$key." and users_LOGIN='".$currentUser->user['login']."'");
					if(sizeof($installed) != 0){

						$subLesson = new MagesterLesson($key);
						$workSheet = &$workBook->addWorksheet($subLesson->lesson['name']);
						$this->studentLessonToExcel($key, $subLesson->lesson['name'], $currentUser, $workBook, $workSheet);
					}
				}
			}

			$workBook->close();
			exit;
		}

		if(
			isset($_GET['delete_range']) && 
			eF_checkParameter($_GET['delete_range'], 'id') && 
			in_array($_GET['delete_range'], array_keys($ranges))
		) {

			try{
				eF_deleteTableData("module_gradebook_ranges", "id=".$_GET['delete_range']);
			}
			catch(Exception $e){
				handleAjaxExceptions($e);
			}

			exit;
		} elseif (
				isset($_GET['add_range']) ||
				(isset($_GET['edit_range']) && eF_checkParameter($_GET['edit_range'], 'id') && in_array($_GET['edit_range'], array_keys($ranges)))
		) {

			$grades = array();

			for($i = 1; $i <= 100; $i++)
				$grades[$i] = $i;

			isset($_GET['add_range']) ? $postTarget = "&add_range=1" : $postTarget = "&edit_range=".$_GET['edit_range'];

			$form = new HTML_QuickForm("add_range_form", "post", $this->moduleBaseUrl.$postTarget, "", null, true);
			$form->registerRule('checkParameter', 'callback', 'eF_checkParameter'); // XXX
			$form->addElement('select', 'range_from', _GRADEBOOK_RANGE_FROM, $grades);
			$form->addElement('select', 'range_to', _GRADEBOOK_RANGE_TO, $grades);
			$form->addElement('text', 'grade', _GRADEBOOK_GRADE, 'class = "inputText"');
			$form->addRule('grade', _THEFIELD.' "'._GRADEBOOK_GRADE.'" '._ISMANDATORY, 'required', null, 'client');
			$form->addRule('grade', _INVALIDFIELDDATA, 'checkParameter', 'text'); // XXX
			$form->addElement('submit', 'submit', _SUBMIT, 'class = "flatButton"');

			if(isset($_GET['edit_range'])){
				$editRange = $ranges[$_GET['edit_range']];
				$form->setDefaults($editRange);
			}

			if($form->isSubmitted() && $form->validate()){

				$error = $invalid_range = false;
				$values = $form->exportValues();
				$fields = array(
						"range_from" => $values['range_from'],
						"range_to" => $values['range_to'],
						"grade" => $values['grade']
				);

				if(isset($_GET['edit_range'])) // do not check it below ...
					unset($ranges[$_GET['edit_range']]);

				foreach($ranges as $range){

					if($range['grade'] == $fields['grade']){
						$message = _GRADEBOOK_GRADE." '".$fields['grade']."' "._GRADEBOOK_ALREADY_EXISTS;
						$message_type = 'failure';
						$error = true;
						break;
					}

					if($fields['range_from'] >= $range['range_from'] && $fields['range_to'] <= $range['range_to'])
						$invalid_range = true;

					if($fields['range_from'] >= $range['range_from'] && $fields['range_from'] <= $range['range_to'] &&
							$fields['range_to'] >= $range['range_to'])
						$invalid_range = true;

					if($fields['range_to'] >= $range['range_from'] && $fields['range_to'] <= $range['range_to'])
						$invalid_range = true;

					if($fields['range_from'] <= $range['range_from'] && $fields['range_to'] >= $range['range_to'])
						$invalid_range = true;

					if($invalid_range){
						$message = _GRADEBOOK_INVALID_RANGE.". "._GRADEBOOK_RANGE;
						$message .= " [".$range['range_from'].", ".$range['range_to']."]"." "._GRADEBOOK_ALREADY_EXISTS;
						$message_type = 'failure';
						$error = true;
						break;
					}
				}

				if($fields['range_from'] >= $fields['range_to']){
					$message = _GRADEBOOK_RANGE_FROM.' '._GRADEBOOK_GRATER_THAN.' '._GRADEBOOK_RANGE_TO;
					$message_type = 'failure';
					$error = true;
				}

				if($error == false){

					if(isset($_GET['add_range'])){

						if(eF_insertTableData("module_gradebook_ranges", $fields)){
							$smarty->assign("T_GRADEBOOK_MESSAGE", _GRADEBOOK_RANGE_SUCCESSFULLY_ADDED);
						}
						else{
							$message = _GRADEBOOK_RANGE_ADD_PROBLEM;
							$message_type = 'failure';
						}
					}
					else{
						if(eF_updateTableData("module_gradebook_ranges", $fields, "id=".$_GET['edit_range'])){
							$smarty->assign("T_GRADEBOOK_MESSAGE", _GRADEBOOK_RANGE_SUCCESSFULLY_EDITED);
						}
						else{
							$message = _GRADEBOOK_RANGE_EDIT_PROBLEM;
							$message_type = 'failure';
						}
					}
				}
			}

			$renderer = prepareFormRenderer($form);
			$form->accept($renderer);
			$smarty->assign('T_GRADEBOOK_ADD_EDIT_RANGE_FORM', $renderer->toArray());
		} else if(isset($_GET['add_column'])){
		} elseif(
				isset($_GET['edit_publish']) && 
				isset($_GET['uid']) && 
				isset($_GET['publish']) &&
				eF_checkParameter($_GET['uid'], 'id') && 
				in_array($_GET['uid'], array_keys($allUsers))
			) {
			try{
				eF_updateTableData("module_gradebook_users", array("publish" => $_GET['publish']),
						"uid=".$_GET['uid']);
			}
			catch(Exception $e){
				handleAjaxExceptions($e);
			}

			exit;
		} elseif (
			isset($_GET['change_grade']) && 
			isset($_GET['grade']) && 
			eF_checkParameter($_GET['change_grade'], 'id')
		) {
			/*
			$newGrade = $_GET['grade'];
			try{
				if($newGrade != ''){

					if(eF_checkParameter($newGrade, 'uint') === false || $newGrade > 100)
						throw new MagesterContentException(_GRADEBOOK_INVALID_GRADE.': "'.$newGrade.'". '._GRADEBOOK_VALID_GRADE_SPECS,
								MagesterContentException :: INVALID_SCORE);
				}
				else
					$newGrade = -1;

				eF_updateTableData("module_gradebook_grades", array("grade" => $newGrade), "gid=".$_GET['change_grade']);
			} catch(Exception $e) {
				header("HTTP/1.0 500");
				echo rawurlencode($e->getMessage());
			}
			
			exit;
			*/
		} else {
			$smarty->assign("T_GRADEBOOK_RANGES", $ranges);

			if($currentUser->getRole($this->getCurrentLesson()) == 'professor' || $currentUser->getRole($this->getCurrentLesson()) == 'administrator') {
				
				/* Add new students to GradeBook related tables */
				$result = eF_getTableData("module_gradebook_users", "users_LOGIN", "lessons_ID=".$currentLessonID);
				$allLogins = array();

				foreach($result as $user)
					array_push($allLogins, $user['users_LOGIN']);

				if(sizeof($result) != sizeof($lessonUsers)){ // FIXME

					$lessonColumns = $this->getLessonColumns($currentLessonID);

					foreach($lessonUsers as $userLogin => $value){

						if(!in_array($userLogin, $allLogins)){

							$userFields = array(
									"users_LOGIN" => $userLogin,
									"lessons_ID" => $currentLessonID,
									"score" => -1,
									"grade" => '-1'
							);

							$uid = eF_insertTableData("module_gradebook_users", $userFields);

							foreach($lessonColumns as $key => $column){

								$fieldsGrades = array(
										"oid" => $key,
										"grade" => -1,
										"users_LOGIN" => $userLogin
								);

								$type = $column['refers_to_type'];
								$id = $column['refers_to_id'];

								eF_insertTableData("module_gradebook_grades", $fieldsGrades);

								if($type != 'real_world')
									$this->importGrades($type, $id, $key, $userLogin);
							}

							$this->computeScoreGrade($lessonColumns, $ranges, $userLogin, $uid);
						}
					}
				}
				/* End */

				$lessonColumns = $this->getLessonColumns($currentLessonID);
				$allUsers = $this->getLessonUsers($currentLessonID, $lessonColumns);
				
				if($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
					$gradeBookLessons = $this->getGradebookLessons($currentUser->getLessons(false, 'professor'), $currentLessonID);
				} else {
					$gradeBookLessons = $this->getGradebookLessons(MagesterLesson::getLessons(), $currentLessonID);
				}
				/*
					echo '<pre>';
					var_dump($lessonColumns);
					echo '</pre>';
					exit;
				*/
				$smarty->assign("T_GRADEBOOK_LESSON_ID", $currentLessonID);
				
				$smarty->assign("T_GRADEBOOK_LESSON_COLUMNS", $lessonColumns);
				$smarty->assign("T_GRADEBOOK_LESSON_USERS", $allUsers);
				$smarty->assign("T_GRADEBOOK_GRADEBOOK_LESSONS", $gradeBookLessons);
				
				$smarty->assign("T_GRADEBOOK_GROUPS", $gradebookGroups);
				
				
			} else if($currentUser->getRole($this->getCurrentLesson()) == 'student'){

				$lessonColumns = $this->getLessonColumns($currentLessonID);
				$studentGrades = $this->getStudentGrades($currentUser, $currentLessonID, $lessonColumns);

				$smarty->assign("T_GRADEBOOK_LESSON_COLUMNS", $lessonColumns);
				$smarty->assign("T_GRADEBOOK_STUDENT_GRADES", $studentGrades);
				$smarty->assign("T_GRADEBOOK_CURRENT_LESSON_NAME", $currentLesson->lesson['name']);

				// Show all my lessons
				$studentLessons = $currentUser->getLessons(false, 'student');
				$studentLessonsNames = array();
				$studentLessonsColumns = array();
				$studentLessonsGrades = array();

				foreach($studentLessons as $key => $value){

					// Is GradeBook installed for this lesson ?
					$installed = eF_getTableData("module_gradebook_users", "*",
							"lessons_ID=".$key." and users_LOGIN='".$currentUser->user['login']."'");
					if(sizeof($installed) != 0){

						$lesson = new MagesterLesson($key);
						$columns = $this->getLessonColumns($key);
						$grades = $this->getStudentGrades($currentUser, $key, $columns);

						array_push($studentLessonsNames, $lesson->lesson['name']);
						$studentLessonsColumns[$lesson->lesson['name']] = $columns;
						$studentLessonsGrades[$lesson->lesson['name']] = $grades;
					}
				}

				$smarty->assign("T_GRADEBOOK_STUDENT_LESSON_NAMES", $studentLessonsNames);
				$smarty->assign("T_GRADEBOOK_STUDENT_LESSON_COLUMNS", $studentLessonsColumns);
				$smarty->assign("T_GRADEBOOK_STUDENT_LESSON_GRADES", $studentLessonsGrades);
			}
		}

		$this->setMessageVar($message, $message_type);

		if($currentUser->getType() == 'administrator') {
			//return $this->moduleBaseDir."module_gradebook_professor.tpl";
			//return $this->moduleBaseDir."templates/gradebook.edit.tpl";

		} else if($currentUser->getRole($this->getCurrentLesson()) == 'professor')
			return $this->moduleBaseDir."module_gradebook_professor.tpl";

		else if($currentUser->getRole($this->getCurrentLesson()) == 'student')
			return $this->moduleBaseDir."module_gradebook_student.tpl";
	}
	public function getCenterLinkInfo() {

		$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");
		if (
				$currentUser->getType() == "administrator" ||
				$xuserModule->getExtendedTypeID($currentUser) == "professor" ||
				$xuserModule->getExtendedTypeID($currentUser) == "student"
		) {
			return array(
					'title' => _GRADEBOOK_NAME,
					'image' => $this->moduleBaseDir.'images/gradebook_logo.png',
					'link' => $this->moduleBaseUrl,
					'class' => 'grade'
			);
		}
	}
	public function getLessonTopLinkInfo($lesson_id, $course_id) {
		$currentUser = $this -> getCurrentUser();
		 
		if (is_null($lesson_id)) {
			return false;
		}
		if (is_null($course_id)) {
			$course_id = 0;
		}
		 
		//var_dump($this->moduleBaseUrl);

		$xuserModule = $this->loadModule("xuser");
		if (
				$xuserModule->getExtendedTypeID($currentUser) == "professor" ||
				$xuserModule->getExtendedTypeID($currentUser) == "student"
		) {
			return array(
					'title' => _GRADEBOOK_NAME,
					'image' => $this->moduleBaseDir.'images/gradebook_logo.png',
					'link' => sprintf($this->moduleBaseUrl . "&lessons_ID=%d&from=%d", $lesson_id, $course_id)
			);
		}
	}
	public function getLessonCenterLinkInfo(){

		return array(
				'title' => _GRADEBOOK_NAME,
				'image' => $this->moduleBaseDir.'images/gradebook_logo.png',
				'link' => $this->moduleBaseUrl
		);
	}
	public function getNavigationLinks(){

		$currentUser = $this->getCurrentUser();

		if($currentUser->getType() == 'administrator'){

			return array(
					array('title' => _HOME, 'link' => $currentUser->getType().".php?ctg=control_panel"),
					array('title' => _GRADEBOOK_NAME, 'link' => $this->moduleBaseUrl)
			);
		}
		else{
			$currentLesson = $this->getCurrentLesson();
			$currentUserRole = $currentUser->getRole($currentLesson);
			$onClick = "location='".$currentUserRole.".php?ctg=lessons';top.sideframe.hideAllLessonSpecific();";

			return array(
					array('title' => _MYCOURSES, 'onclick' => $onClick),
					array('title' => $currentLesson->lesson['name'], 'link' => $currentUser->getType().".php?ctg=control_panel"),
					array('title' => _GRADEBOOK_NAME, 'link' => $this->moduleBaseUrl)
			);
		}
	}
	public function getSidebarLinkInfo(){
		$currentUser = $this -> getCurrentUser();
		 
		$xuserModule = $this->loadModule("xuser");
		 
		if (
				$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
				$xuserModule->getExtendedTypeID($currentUser) == "student"

		) {
			$currentLessonMenu = array(array(
					'id' => 'gradebook_link_1',
					'title' => _GRADEBOOK_NAME,
					'image' => $this->moduleBaseDir.'images/gradebook_logo16',
					'_magesterExtensions' => '1',
					'link' => $this->moduleBaseUrl)
			);
			return array("current_lesson" => $currentLessonMenu, 'system' => $currentLessonMenu);
		} else {
		}
	}
	public function getLinkToHighlight(){
		return 'gradebook_link_1';
	}
	public function isLessonModule(){
		return true;
	}
	public function onDeleteUser($login){

		eF_deleteTableData("module_gradebook_users", "users_LOGIN='".$login."'");
		eF_deleteTableData("module_gradebook_grades", "users_LOGIN='".$login."'");
	}
	public function onInstall(){

		eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_ranges`");
		$t1 = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_gradebook_ranges` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`range_from` int(3) NOT NULL,
				`range_to` int(3) NOT NULL,
				`grade` varchar(50) NOT NULL,
				PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_objects`");
		$t2 = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_gradebook_objects` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(50) NOT NULL,
				`weight` int(2) NOT NULL,
				`refers_to_type` varchar(50) NOT NULL,
				`refers_to_id` int(11) NOT NULL,
				`lessons_ID` int(11) NOT NULL,
				`creator` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_grades`");
		$t3 = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_gradebook_grades` (
				`gid` int(11) NOT NULL AUTO_INCREMENT,
				`oid` int(11) NOT NULL,
				`grade` int(3) NOT NULL,
				`users_LOGIN` varchar(255) NOT NULL,
				PRIMARY KEY (`gid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_users`");
		$t4 = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_gradebook_users` (
				`uid` int(11) NOT NULL AUTO_INCREMENT,
				`users_LOGIN` varchar(255) NOT NULL,
				`lessons_ID` int(11) NOT NULL,
				`score` float NOT NULL,
				`grade` varchar(50) NOT NULL,
				`publish` tinyint(1) NOT NULL DEFAULT '1',
				PRIMARY KEY (`uid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		return($t1 && $t2 && $t3 && $t4);
	}
	public function onUninstall(){

		$t1 = eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_ranges`");
		$t2 = eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_objects`");
		$t3 = eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_grades`");
		$t4 = eF_executeNew("DROP TABLE IF EXISTS `module_gradebook_users`");

		return($t1 && $t2 && $t3 && $t4);
	}

	// Inner Functions
	private function getSelectedLesson($currentUser = null)
	{
		if (is_null($currentUser)) {
			$currentUser = $this->getCurrentUser();
		}
		$currentLesson = $this->getCurrentLesson();
		
		if (isset($_SESSION["grade_lessons_ID"]) && is_numeric($_SESSION["grade_lessons_ID"])) {
			$currentLesson = new MagesterLesson($_SESSION["grade_lessons_ID"]);
		}
		if (isset($_GET['lessons_ID']) && is_numeric($_GET['lessons_ID'])) {
			$currentLesson = new MagesterLesson($_GET['lessons_ID']);
			$_SESSION["grade_lessons_ID"] = $_GET['lessons_ID'] ;
		}

		if (is_null($currentLesson)) {
			if ($currentUser->getRole($this->getCurrentLesson()) == 'professor') {
				$allLessons = $currentUser->getLessons(true, 'professor');
			} else {
				$allLessons = MagesterLesson::getLessons(true);
			}
			$currentLesson = reset($allLessons);
		
			$_SESSION["grade_lessons_ID"] = $currentLesson->lesson['id'];
		}
		return $currentLesson;
	}
	private function getSelectedCourse($currentUser = null) {
		if (is_null($currentUser)) {
			$currentUser = $this->getCurrentUser();
		}
		if (isset($_SESSION["grade_courses_ID"]) && is_numeric($_SESSION["grade_courses_ID"])) {
			$currentCourse = new MagesterCourse($_SESSION["grade_courses_ID"]);
		}
	
		if (is_null($currentCourse)) {
			$currentLesson = $this->getSelectedLesson($currentUser);
			
			$allCourses = $currentLesson->getCourses(true);
			$currentCourse = reset($allCourses);
			
			$_SESSION["grade_courses_ID"] = $currentCourse->course['id'];
		}
		return $currentCourse;
	}
	private function getSelectedClasse($currentUser = null) {
		if (is_null($currentUser)) {
			$currentUser = $this->getCurrentUser();
		}
		if (isset($_SESSION["grade_classes_ID"]) && is_numeric($_SESSION["grade_classes_ID"])) {
			$currentClasse = new MagesterCourseClass($_SESSION["grade_classes_ID"]);
		}
	
		if (is_null($currentClasse)) {
			/*
			$currentCourse = $this->getSelectedCourse($currentUser);
				
			$allCourses = $currentLesson->getCourses(true);
			$currentCourse = reset($allCourses);
				
			$_SESSION["grade_classes_ID"] = $currentCourse->course['id'];
			*/
		}
		return $currentClasse;
	}
	private function getRanges(){

		$result = eF_getTableData("module_gradebook_ranges", "*", "", "range_from");
		$ranges = array();

		foreach($result as $value)
			$ranges[$value['id']] = $value;

		return $ranges;
	}
	private function getLessonColumns($lessonID, $groupID = null){
		
		if (is_null($groupID)) { 
			$result = eF_getTableData("module_gradebook_objects", "*", "lessons_ID=".$lessonID, "id");
		} else {
			$result = eF_getTableData("module_gradebook_objects", "*", "lessons_ID=".$lessonID." AND group_id = ".$groupID, "id");
		}
		$columns = array();

		foreach($result as $value) {
			if (($value['content_name']  = $this->getColumnContent($value)) == FALSE) {
				// REMOVE COLUMN
				ef_deleteTableData("module_gradebook_objects", "id = " . $value['id']);
			} else {
				$columns[$value['id']] = $value;
			}
		}

		return $columns;
	}
	private function getColumnContent($grade_object) {
		switch($grade_object['refers_to_type']) {
			case 'test' : {
				try {
					$test = new MagesterTest($grade_object['refers_to_id']);
					return $test->test['name'];
				} catch (MagesterTestException $e) {
					if ($e->getCode() == MagesterTestException::TEST_NOT_EXISTS) {
						// REMOVE COLUMN
						return false;
					}
				}
			}
			case 'real_world' : {
				return "&mdash;&mdash;";
			}
			case 'progress' : {
				return "Progresso Atual";
			}
			
		}
		var_dump($grade_object);
		exit;
	}
	private function getLessonUsers($lessonID, $objects, $classe_id = null){
		$where = array();
		
//		$where[] = "lessons_ID = " . $lessonID;
		$where[] = sprintf("u.login IN (SELECT users_LOGIN FROM users_to_lessons WHERE lessons_ID = %d)", $lessonID);
		
		if (!is_null($classe_id)) {
			$where[] = sprintf("u.login IN (SELECT users_LOGIN FROM users_to_courses WHERE classe_id = %d)", $classe_id); 
		}
		$where[] = "(user_types_ID = 0 OR user_types_ID IS NULL)";
		$where[] = "u.user_type = 'student'";
		
		/*
		echo prepareGetTableData(
			sprintf("users u OUTER JOIN module_gradebook_users gbu ON (gbu.users_LOGIN = u.login AND gbu.lessons_ID = %d)", $lessonID),
			"gbu.uid, u.id, u.login as users_LOGIN, gbu.lessons_ID, gbu.score, gbu.grade, gbu.publish, u.active", 
			implode(" AND ", $where),
			"uid"
		);
		*/
		$result = eF_getTableData(
			sprintf("users u LEFT OUTER JOIN module_gradebook_users gbu ON (gbu.users_LOGIN = u.login AND gbu.lessons_ID = %d)", $lessonID),
			"gbu.uid, u.id, u.login as users_LOGIN, gbu.lessons_ID, gbu.score, gbu.grade, gbu.publish, u.active", 
			implode(" AND ", $where),
			"uid"
		);
		
		$userRoles = MagesterUser::getRoles(true);
		
		$users = array();

		foreach($result as $value) {
			/*
			if (is_null($value['uid'])) {
				// INSERT INTO GRADEBOOK DATA
				
				$userFields = array(
					"users_LOGIN" 	=> $value['users_LOGIN'],
					"lessons_ID"	=> $lessonID,
					"score" 		=> -1,
					"grade" 		=> '-1',
					'publish'		=> 0
				);
				
				$uid = eF_insertTableData("module_gradebook_users", $userFields);
			}
			*/

			$grades = array();
/*
			$active = eF_getTableData("users", "active", "login='".$value['users_LOGIN']."'"); // active or not ?
			$value['active'] = $active[0]['active'];
*/
			
			if($value['score'] == -1)
				$value['score'] = '-';

			if($value['grade'] == '-1')
				$value['grade'] = '-';
			
			if ($value['user_types_ID'] != 0) {
				$value['userrole'] = $userRoles[$value['user_types_ID']];
			} else {
				$value['userrole'] = $userRoles[$value['user_type']];
			}
			

			foreach($objects as $key => $object) {
				
				

				$result_ = eF_getTableData(
						"module_gradebook_grades", "gid, grade",
						"oid = ".$object['id']." and users_LOGIN='".$value['users_LOGIN']."'");

				if($result_[0]['grade'] == -1)
					$result_[0]['grade'] = '';
				
				
				$grades[$object['id']] = $result_[0];

				//array_push($grades, $result_[0]);
			}

			$value['grades'] = $grades;
			$users[$value['id']] = $value;
		}

		return $users;
	}
	private function checkLessonUsers($lessonID, $objects, $classe_id = null) {
		$updated = false;
		$gradebookUsers = $this->getLessonUsers($lessonID, $objects, $classe_id);
		
		$currentLesson = new MagesterLesson($lessonID);
		$lessonUsers = $currentLesson->getUsers('student');
		
		$allLogins = array();
		foreach($gradebookUsers as $gradeUser) {
			$allGradeLogins[] = $gradeUser['users_LOGIN'];
		}
		
		
		foreach($lessonUsers as $lessonUser) {
			if (!in_array($lessonUser['login'], $allGradeLogins)) {
				// INSERT INTO GRADEBOOK DATA
				$userFields = array(
						"users_LOGIN" 	=> $lessonUser['login'],
						"lessons_ID"	=> $lessonID,
						"score" 		=> -1,
						"grade" 		=> '-1',
						'publish'		=> 0
				);
		
				//$uid = eF_insertTableData("module_gradebook_users", $userFields);
				
				foreach($objects as $key => $column) {
					$fieldsGrades = array(
						"oid" => $key,
						"grade" => -1,
						"users_LOGIN" => $lessonUser['login']
					);
				
					// $type = $column['refers_to_type'];
					// $id = $column['refers_to_id'];
				
					eF_insertTableData("module_gradebook_grades", $fieldsGrades);
				
//					if($type != 'real_world')
//						$this->importGrades($type, $id, $key, $userLogin);
				}
				$updated = true;
				
				//$this->computeScoreGrade($lessonColumns, $ranges, $userLogin, $uid);				
				
			}
		}
		return $updated;
	}
	
	
	private function getNumberOfColumns($lessonID){

		$result = eF_getTableData("module_gradebook_objects", "count(id) as total_columns", "lessons_ID=".$lessonID);
		return $result[0]['total_columns'];
	}
	/*
	 private function getGradebookLessons($professorLessons, $currentLessonID){ // lessons where GradeBook is installed

	$lessons = array();
	unset($professorLessons[$currentLessonID]); // do not use current lesson

	foreach($professorLessons as $key => $value){

	$lesson = new MagesterLesson($key);
	$lessonUsers = $lesson->getUsers('student'); // get all students that have this lesson
	$result = eF_getTableData("module_gradebook_users", "count(uid) as total_users", "lessons_ID=".$key);

	if($result[0]['total_users'] != 0) // module installed for this lesson
	$lessons[$key] = array("id" => $key, "name" => $lesson->lesson['name']);
	}

	return $lessons;
	}
	*/
	private function getGradebookClasses($currentCourseID) {
		$courseClasses = MagesterCourseClass::getAllClasses(array('course_id' => $currentCourseID, 'return_objects' => false));

		return $courseClasses;
	}
	private function getGradebookGroups($currentLessonID, $currentClasseID = null){ // lessons where GradeBook is installed

		is_null($currentClasseID) ? $currentClasseID = 0 : null; 
		/*
		echo prepareGetTableData(
			"module_gradebook_groups grp 
			LEFT OUTER JOIN module_gradebook_groups_order ord ON (
				grp.id = ord.group_id AND 
				(grp.lesson_id = ord.lesson_id OR grp.lesson_id = 0) AND 
				(grp.classe_id = ord.classe_id OR grp.classe_id = 0)
			)",
			//"id, lesson_id, classe_id, name",
			"grp.*, ord.*",
			"grp.lesson_id IN (0, " . $currentLessonID . ") AND 
			grp.classe_id IN (0, " . $currentClasseID . ")",
			"ord.order_index, grp.id"
		);
		*/
		$result = eF_getTableData(
			"module_gradebook_groups grp 
			LEFT OUTER JOIN module_gradebook_groups_order ord ON (
				grp.id = ord.group_id AND 
				(grp.lesson_id = ord.lesson_id OR grp.lesson_id = 0) AND 
				(grp.classe_id = ord.classe_id OR grp.classe_id = 0)
			)",
			//"id, lesson_id, classe_id, name",
			"grp.id, grp.lesson_id, grp.classe_id, grp.name, grp.require_status, grp.min_value, grp.pass_value, ord.order_index",
			"grp.lesson_id IN (0, " . $currentLessonID . ") AND 
			grp.classe_id IN (0, " . $currentClasseID . ")",
			"ord.order_index, grp.id",
			"grp.id"
		);
		$gradebook = array();
		$require_statuses = array(
				1 => "Sim",
				2 => "Se abaixo de %d",
				3 => "Não"
		);
		$firstItem = reset($result);
		
		$lastValue = $firstItem['min_value'];
		foreach($result as $item) {
			$item['require_descr'] = sprintf($require_statuses[$item['require_status']], $lastValue); 
			$gradebook[] = $item;
			
			$lastValue = $item['min_value'];
		}

		return $gradebook;
	}
	private function getGradebookLessons($professorLessons, $currentLessonID){ // lessons where GradeBook is installed
		$lessons = array();
		//unset($professorLessons[$currentLessonID]); // do not use current lesson

		$lessons_ID = array_keys($professorLessons);

		$courses = MagesterCourse::getCourses(false);
		
		$result = eF_getTableData("lessons l LEFT OUTER JOIN module_gradebook_users a ON (a.lessons_ID = l.id)",
				"DISTINCT l.id as id, l.name, count(a.uid) as total_users,
				( Select b.courses_ID from lessons_to_courses b Where b.lessons_ID = l.id LIMIT 1) as course_ID
				",
				"l.id IN (".implode(",", $lessons_ID).")",
				"",
				"l.id /* HAVING count(a.uid) > 0 */"
		) ;

		$courselessons = array();
		foreach($result as $lesson) {
			 
			if (!is_array($courselessons[$lesson['course_ID']])) {
				$courselessons[$lesson['course_ID']] = array(
						'id' 	=> $lesson['course_ID'],
						'name'	=> $courses[$lesson['course_ID']]['name'],
						'lessons' => array()
				);
			}
			$courselessons[$lesson['course_ID']]['lessons'][$lesson['id']] = $lesson;
			 
		}

		 
		return $courselessons;
		 
	}
	private function getStudentGrades($currentUser, $currentLessonID, $lessonColumns){

		$grades = array();
		$i = 0;
		$user = eF_getTableData("module_gradebook_users", "*", "lessons_ID=".$currentLessonID." and users_LOGIN='".$currentUser->user['login']."'");

		if($user[0]['publish'] == 1){

			foreach($lessonColumns as $key => $column){

				$grade = eF_getTableData("module_gradebook_grades", "grade",
						"oid = ".$column['id']." and users_LOGIN='".$currentUser->user['login']."'");

				if($grade[0]['grade'] == -1)
					$grade[0]['grade'] = '-';

				$grades[$i++] = $grade[0]['grade'];
			}

			if($user[0]['score'] == -1)
				$user[0]['score'] = '-';

			if($user[0]['grade'] == '-1')
				$user[0]['grade'] = '-';

			$grades[$i++] = $user[0]['score'];
			$grades[$i] = $user[0]['grade'];
		}

		return $grades;
	}
	private function professorLessonToExcel($lessonID, $lessonName, $workBook, $workSheet){

		$headerFormat = &$workBook->addFormat(array(
				'border' => 0,
				'bold' => '1',
				'size' => '12',
				'color' => 'black',
				'fgcolor' => 22,
				'align' => 'center'));

		$titleCenterFormat = &$workBook->addFormat(array(
				'HAlign' => 'center',
				'Size' => 11,
				'Bold' => 1));

		$fieldCenterFormat = &$workBook->addFormat(array(
				'HAlign' => 'center',
				'Size' => 10));

		$lessonColumns = $this->getLessonColumns($lessonID);
		$allUsers = $this->getLessonUsers($lessonID, $lessonColumns);
		$columnsNr = $this->getNumberOfColumns($lessonID);

		$workSheet->setInputEncoding('utf-8');
		$workSheet->write(0, 0, $lessonName, $headerFormat);
		$workSheet->mergeCells(0, 0, 0, 3 + $columnsNr - 1);
		$workSheet->setColumn(0, 1 + $columnsNr - 1, 30);
		$workSheet->setColumn($columnsNr + 1, $columnsNr + 2, 15);

		$col = 1;
		$workSheet->write(2, 0, _GRADEBOOK_STUDENT_NAME, $titleCenterFormat);

		foreach($lessonColumns as $key => $value)
			$workSheet->write(2, $col++, $value['name'].' ('._GRADEBOOK_COLUMN_WEIGHT_DISPLAY.': '.$value['weight'].')', $titleCenterFormat);

		$workSheet->write(2, $col++, _GRADEBOOK_SCORE, $titleCenterFormat);
		$workSheet->write(2, $col++, _GRADEBOOK_GRADE, $titleCenterFormat);

		$col = 0;
		$row = 3;

		foreach($allUsers as $key => $student){

			$user = MagesterUserFactory::factory($student['users_LOGIN']);
			$workSheet->write($row, $col++, $user->user['name'].' '.$user->user['surname'].' ('.$user->user['login'].')', $fieldCenterFormat);

			foreach($student['grades'] as $key2 => $grade)
				$workSheet->write($row, $col++, $grade['grade'], $fieldCenterFormat);

			$workSheet->write($row, $col++, $student['score'], $fieldCenterFormat);
			$workSheet->write($row, $col++, $student['grade'], $fieldCenterFormat);

			$col = 0;
			$row++;
		}
	}
	private function studentLessonToExcel($lessonID, $lessonName, $currentUser, $workBook, $workSheet){

		$headerFormat = &$workBook->addFormat(array(
				'border' => 0,
				'bold' => '1',
				'size' => '12',
				'color' => 'black',
				'fgcolor' => 22,
				'align' => 'center'));

		$titleCenterFormat = &$workBook->addFormat(array(
				'HAlign' => 'center',
				'Size' => 11,
				'Bold' => 1));

		$fieldCenterFormat = &$workBook->addFormat(array(
				'HAlign' => 'center',
				'Size' => 10));

		$lessonColumns = $this->getLessonColumns($lessonID);
		$studentGrades = $this->getStudentGrades($currentUser, $lessonID, $lessonColumns);
		$columnsNr = $this->getNumberOfColumns($lessonID);

		$workSheet->setInputEncoding('utf-8');
		$workSheet->write(0, 0, $lessonName, $headerFormat);
		$workSheet->mergeCells(0, 0, 0, 2 + $columnsNr - 1);
		$workSheet->setColumn(0, $columnsNr - 1, 30);
		$workSheet->setColumn($columnsNr, $columnsNr + 2, 15);

		$col = 0;

		foreach($lessonColumns as $key => $value)
			$workSheet->write(2, $col++, $value['name'].' ('._GRADEBOOK_COLUMN_WEIGHT_DISPLAY.': '.$value['weight'].')', $titleCenterFormat);

		$workSheet->write(2, $col++, _GRADEBOOK_SCORE, $titleCenterFormat);
		$workSheet->write(2, $col++, _GRADEBOOK_GRADE, $titleCenterFormat);

		$col = 0;

		if(sizeof($studentGrades)){

			for($i = 0; $i < sizeof($studentGrades); $i++)
				$workSheet->write(3, $col++, $studentGrades[$i], $fieldCenterFormat);
		}
		else{
			$workSheet->write(4, 0, _GRADEBOOK_NOT_PUBLISHED, $fieldCenterFormat);
			$workSheet->mergeCells(4, 0, 4, 2 + $columnsNr - 1);
		}
	}
	private function importGrades($type, $id, $oid, $userLogin){

		if($type == 'test'){

			// XXX archive = 0 (means the last ?)
			$where = "users_LOGIN='".$userLogin."' and tests_ID=".$id." and (status='completed' or status='passed' or status='failed') ";
			$where .= "and archive=0";
			$result = eF_getTableData("completed_tests", "score", $where);

			if(sizeof($result) != 0)
				$grade = round($result[0]['score']);
			else
				$grade = -1;

		} elseif ($type == 'scormtest') {

			// XXX lesson_status field ?
			$result = eF_getTableData("scorm_data", "score", "users_LOGIN='".$userLogin."' and content_ID=".$id);

			if (sizeof($result) != 0) {

				if ($result[0]['score'] != '') {
					$grade = intval($result[0]['score']);
				} else {
					$grade = -1;
				}
			} else {
				$grade = -1;
			}
		} elseif ($type == 'project') {

			// XXX field status means ?
			$result = eF_getTableData("users_to_projects", "grade", "users_LOGIN='".$userLogin."' and projects_ID=".$id);

			if (sizeof($result) != 0) {

				if ($result[0]['grade'] != '') {
					$grade = $result[0]['grade'];
				} else {
					$grade = -1;
				}
			} else {
				$grade = -1;
			}
		} elseif ($type == 'progress') {
			$user = MagesterUserFactory::factory($userLogin);
			$progress = $user->getUserStatusInLessons(false, true);
			// actually it's not grade but progress ...
			$grade = round($progress[$id]->lesson['overall_progress']['percentage']);
		}

		eF_insertOrupdateTableData(
			"module_gradebook_grades",
			array(
				"oid" => $oid,
				"users_LOGIN" => $userLogin,
				"grade" => $grade
			),
			"oid=".$oid." and users_LOGIN='".$userLogin."'"
		);
	}
	private function computeScoreGrade($lessonColumns, $userLogin) {
		$divisionBy = 0;
		$sum = 0;
		
		///var_dump($lessonColumns);

		foreach ($lessonColumns as $key => $object) {
			$result = eF_getTableData(
				"module_gradebook_grades",
				"grade",
				"oid=".$object['id']." and users_LOGIN='".$userLogin."'"
			);
			$grade = $result[0]['grade'];
			
			$grade = min(max($grade, 0), 100);
			
			//if($grade != -1){

				$weight = $object['weight'];
				$sum += $grade * $weight;
				$divisionBy += $weight;
			//}
		}

		if ($divisionBy != 0) {

			$overallScore = round((float)($sum/$divisionBy));
			$overallGrade = '-1'; // if no range found
		/*
			foreach($ranges as $range){

				if($overallScore >= $range['range_from'] && $overallScore <= $range['range_to']){
					$overallGrade = $range['grade'];
					break;
				}
			}
		*/
		}
		else{
			$overallScore = -1;
			$overallGrade = '-1';
		}

		//eF_updateTableData("module_gradebook_users", array("score" => $overallScore, "grade" => $overallGrade), sprintf("users_LOGIN = '%s' AND lessons_ID = %d", $userLogin, $lessonID));
		
		return $overallScore;
		
	}
	private function computeFinalScore($lessonID, $login = null)
	{
		
		$selectedLesson = new MagesterLesson($lessonID);
		
		if (is_null($login)) {
			// GET ALL USERS RESULTS
			$allLessonUsers = $this->getLessonUsers($lessonID, $this->getLessonColumns($lessonID));
			$allLessonLogins = array();
			foreach ($allLessonUsers as $user) {
				$allLessonLogins[] = $user['users_LOGIN'];
			}
		} else {
			$allLessonLogins = array($login);
		}
		
		// STEP-BY-STEP:
		// 1. GET LESSON GROUPS
		$lessonGroups = $this->getGradebookGroups($lessonID);
		
		$lessonColumns = array();
		
		foreach ($allLessonLogins as $login) {
			$response = $scores = array();
			$score = 0;
			$currentUser = MagesterUserFactory::factory($login);

			// 2. FOR EACH GROUPS SCORES
			foreach ($lessonGroups as $group) {
				if (!array_key_exists($group['id'], $lessonColumns)) {
					$lessonColumns[$group['id']] = $this->getLessonColumns($lessonID, $group['id']);
				}
	
				$score = $this->computeScoreGrade($lessonColumns[$group['id']], $login);
				$scores[] = array(
					'group' 			=> $group['id'],
					//				'require_status'	=> $group['require_status'],
					'min_value'			=> $group['min_value'],
					'pass_value'		=> $group['pass_value'],
					'score' 			=> $score,
					'to_next'			=> $score >= $group['min_value'],
					'pass' 				=> $score >= $group['pass_value']
				);
			}
			
			$counter = 0;
			$finalScore = 0;
				
			$alreadyPassed = false;
	
			foreach ($scores as $score) {
				$counter++;
				if ($score['score'] > $finalScore) {
					$finalScore = $score['score'];
				}
		
				if (!$score['to_next'] && !$alreadyPassed) {
					$finalStatus = self::__GRADEBBOK_DISAPPROVED;
					// REPROVADO. NÃO ATINGIU VALOR MÍNIMO
					break;
				}
		
		
				if ($score['pass'] && !$alreadyPassed) {
					$finalStatus = "Aprovado";
					$finalStatus = self::__GRADEBBOK_APPROVED;
					$alreadyPassed = true;
				}
				if (!$score['pass'] && !$alreadyPassed) {
					$finalStatus = self::__GRADEBOOK_WAITING;
						
				}
			}
			if ($finalStatus == self::__GRADEBOOK_WAITING) {
				$finalStatus = self::__GRADEBBOK_DISAPPROVED;
			}
			
			$response = array('groups' => array());
				
			foreach ($scores as $score) {
				$response['groups'][$score['group']] = $score['score'];
				
				
			}
			// 3. GET THE LAST OR THE GREATEST SCORE E SET THEN FINAL.
			$response['final_score'] = $finalScore;
			$response['final_status'] = $finalStatus;
			
			$result[$login] = $response;

			
		}
		if (count($result) == 1) {
			return $result[$login];
		}
		return $result;
	}
}
