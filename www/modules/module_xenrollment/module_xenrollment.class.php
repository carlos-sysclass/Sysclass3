<?php
class module_xenrollment extends MagesterExtendedModule {
	/*
	 const GET_XUSERS				= 'get_xusers';
	 const GET_XUSERS_SOURCE			= 'get_xusers_source';
	 const ADD_XUSER					= 'add_xuser';
	 const EDIT_XUSER				= 'edit_xuser';
	 const DELETE_XUSER				= 'delete_xuser';
	 const UPDATE_XUSER				= 'update_xuser';
	 */

	/* Model Data */
	/** @todo Cache this data */
	protected $editedEnrollment 	= null;
	protected $editedUser 			= null;
	protected $editedCourse 		= null;

	/* AÇÕES DISPONIBILIZADAS *
	 const SHOW_LAST_XENROLLMENTS		= 'show_last_xenrollments';
	 const REGISTER_XENROLLMENT			= 'register_xenrollment';
	 const EDIT_XENROLLMENT				= 'edit_xenrollment';
	 const UNREGISTER_XENROLLMENT		= 'unregister_xenrollment';
	 const OPEN_DOCUMENTS_LIST			= 'open_documents_list';
	 const SHOW_CONTROL_PANEL			= 'show_control_panel';
	 const CHECK_USERS_XENROLLMENT		= 'check_users_xenrollment';
	 const ADD_XDOCUMENT_TO_COURSE		= 'add_xdocument_to_course';
	*/

	 /* ENROLLMENT STATUSES */
	const COMMIT_STATE			= 2;
	const ROLLBACK_STATE		= 3;
	const PAID_STATE			= 4;
	const CANCEL_PENDING_STATE	= 5;

	public function __construct($defined_moduleBaseUrl, $defined_moduleFolder) {
		parent::__construct($defined_moduleBaseUrl, $defined_moduleFolder);
		$this->preActions[] = 'checkUserPermissionAction';
		//		$this->preActions[] = 'makeEnrollmentOptions';
		//		$this->postActions[] = 'checkUserPermission';
		$this->postActions[] = 'makeXenrollmentOptionsAction';
	}

	// Mandatory functions required for module function
	public function getName() {
		return "XENROLLMENT";
	}

	public function addScripts() {
		if ($this->getCurrentAction() == self::UNREGISTER_XENROLLMENT) {
			return array('tinyeditor/packed');
		}
		return array();
	}

	public function getTitle($action) {
		switch($action) {
			case self::SHOW_LAST_XENROLLMENTS : {
				return __XENROLLMENT_SHOW_LAST_ENROLLMENT_TITLE;
			}
			case self::REGISTER_XENROLLMENT : {
				return __XENROLLMENT_REGISTER_TITLE;
			}
			case self::UNREGISTER_XENROLLMENT : {
				// GET USERNAME
				$enroll = $this->getEditedEnrollment();
				$xuserModule = $this->loadModule('xuser');
				$userObj = $xuserModule->getUserById($enroll['users_id']);
				return sprintf(
				__XENROLLMENT_UNREGISTER_TITLE_,
					'<span class="username">' . formatLogin(null, $userObj->user) . '</span>'
					);
			}
			case self::OPEN_DOCUMENTS_LIST : {
				return __XENROLLMENT_SHOW_DOCUMENTS_LIST;
			}
			case $this->getDefaultAction() :
			default : {
				return __XENROLLMENT_NAME;
			}
				
		}
		return parent::getTitle($action);
	}

	public function getUrl($action) {
		return $this->moduleBaseUrl . "&action=" . $action;
	}

	public function getPermittedRoles() {
		return array("administrator");
	}

	public function isLessonModule() {
		return false;
	}

	public function getSidebarLinkInfo() {
		 
		$xuserModule = $this->loadModule("xuser");
		$currentUser = $this -> getCurrentUser();
		 
		if (
		$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
		$xuserModule->getExtendedTypeID($currentUser) == "secretary" ||
		$xuserModule->getExtendedTypeID($currentUser) == "secretary"
		) {
			$link_of_menu = array (
			array (
	        		'id' => $this->index_name . '_menu_link',
	            	'title' => $this->getTitle(),
			//              'image' => $this -> moduleBaseDir . 'images/ies16.png',
	                '_magesterExtensions' => '1',
	                'link'  => $this -> moduleBaseUrl,
					'class' => 'archive'
					)
					);

					return array ( "user" => $link_of_menu);
		}
	}

	public function getCenterLinkInfo() {
		$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");

		if (
		$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
		$xuserModule->getExtendedTypeID($currentUser) == "coordenator" ||
		$xuserModule->getExtendedTypeID($currentUser) == "secretary"
		) {
			 
			return array(
				'title' 	=> $this->getTitle(self::SHOW_CONTROL_PANEL),
                'image' 	=> 'images/others/transparent.gif',
                'link'  	=> $this -> moduleBaseUrl.'&action=report_enrollment',
            	'image_class'	=> 'sprite32 sprite32-graduation',
				'class' => 'archive'
				);
		}

	}



	public function getDefaultAction() {
		return self::SHOW_LAST_XENROLLMENTS;
	}

	protected function getModuleData() {
		$parentData = parent::getModuleData();

		$selfData = array(
		$this->index_name . ".show_last_limit"	=> 10
		);

		return array_merge_recursive($parentData, $selfData);
	}


	/* ACTION HANDLERS */
	public function reportEnrollmentAction(){
		print 'mexer aqui';
	}

	public function openXenrollmentAction() {
		$token = $this->createToken(30);

		$newID = eF_insertTableData("module_xenrollment", array(
    		"token"	=> $token 
		));
		 
		return array(
    		"id"	=> $newID,
    		"token"	=> $token
		);
	}
	public function updateXenrollmentAction($enrollment_token = null, $fields = null) {
		/** @todo RETURN IN A WAY TO UPDATE THIS opened enrollment */
		if (is_null($enrollment_token)) {
			$enrollment_token = $this->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}

		eF_updateTableData("module_xenrollment", $fields, "token = '" . $enrollment_token . "'");

		$result = eF_getTableData("module_xenrollment", "*", "token = '" . $enrollment_token . "'");

		if ($result) {
			return $result[0];
		} else {
			return array_merge(
			array(
	    			"token"	=> $token
			),
			$fields
			);
		}
	}
	public function commitXenrollmentAction($enrollment_token) {
		return $this->updateXenrollmentAction($enrollment_token, array('status_id' => self::COMMIT_STATE));
	}
	public function rollbackXenrollmentAction($enrollment_token) {
		return $this->updateXenrollmentAction($enrollment_token, array('status_id' => self::ROLLBACK_STATE));
	}

	public function registerXenrollmentAction() {
		$newEnrollment = $this->openXenrollmentAction();

		$this->getEditedEnrollment(false, $newEnrollment['id']);
		 
		if ($this->makeEnrollmentChecklist()) {
			return true;
		}
	}
	public function editXenrollmentAction() {
		// CHECK IF A ENROLLMENT EXISTS
		if (!$this->getEditedEnrollment()) {
			$token = $this->getCache("enrollent_token");
				
			$newEnrollment = false;
				
			if ($token) {
				$newEnrollment = $this->getEnrollmentByToken($token);
			}
				
			if (!$newEnrollment) {
				$this->setMessageVar(__XENROLLMENT_NO_ENROLLMENT_SELECTED, "failure");
				return false;
			}
			$this->getEditedEnrollment(false, $newEnrollment['id']);
		}
			
		if ($this->makeEnrollmentChecklist()) {
			return true;
		}
	}
	public function unregisterXenrollmentAction() {
		if ($this->makeHistoryForm(self::CANCEL_PENDING_STATE)) {
			$template = array(
				'title'			=> __ENROLLMENT_UNREGISTER,
		        'template'		=> $this->moduleBaseDir . "templates/actions/xenrollment.unregister.tpl"
		        );
		        	
		        $this->appendTemplate($template);
		}
	}
	public function showLastXenrollmentsAction() {
		$smarty = $this->getSmartyVar();
		 
		$configData = $this->getModuleData();
		 
		$options = array();
		 
		$options[] = array(
			'text' 		=> __XENROLLMENT_LIST_FILTER_ALL,
		//'hint'		=> __XENROLLMENT_LIST_FILTER_ALL_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/list_with_images.png", 
			'href' 		=> $this->moduleBaseUrl . "&action=" . $this->getCurrentAction(),
			'selected'	=> !in_array($_GET['enrollment_status'], array(1,2,4))
		);
			
		$options[] = array(
			'text' 		=> __XENROLLMENT_LIST_FILTER_ONLY_INIT,
		//			'hint'		=> __XENROLLMENT_LIST_FILTER_ONLY_INIT_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/alert.png", 
			'href' 		=> $this->moduleBaseUrl . "&action=" . $this->getCurrentAction() . "&enrollment_status=1",
			'selected'	=> $_GET['enrollment_status'] == 1
		);

		$options[] = array(
			'text' 		=> __XENROLLMENT_LIST_FILTER_ONLY_REGISTERED,
		//			'hint'		=> __XENROLLMENT_LIST_FILTER_ONLY_REGISTERED_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/facebook_like.png", 
			'href' 		=> $this->moduleBaseUrl . "&action=" . $this->getCurrentAction() . "&enrollment_status=2",
			'selected'	=> $_GET['enrollment_status'] == 2
		);
		$options[] = array(
			'text' 		=> __XENROLLMENT_LIST_FILTER_ONLY_PAID,
		//			'hint'		=> __XENROLLMENT_LIST_FILTER_ONLY_PAID_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/money.png", 
			'href' 		=> $this->moduleBaseUrl . "&action=" . $this->getCurrentAction() . "&enrollment_status=4",
			'selected'	=> $_GET['enrollment_status'] == 4
		);



		$smarty -> assign("T_" . $this->getName() . "_LIST_OPTIONS", $options);
		 
		 
		$table[] = "`module_xenrollment` enroll";
		$table[] = "LEFT JOIN module_xenrollment_statuses enroll_status ON enroll.status_id = enroll_status.id";
		$table[] = "LEFT JOIN module_ies ies ON enroll.ies_id = ies.id";
		$table[] = "LEFT JOIN users user ON enroll.users_id = user.id";
		$table[] = "LEFT JOIN courses course ON enroll.courses_id = course.id";

		$fields[] = "enroll.id";
		$fields[] = "enroll.ies_id";
		$fields[] = "ies.nome as ies_name";
		$fields[] = "enroll.users_id";
		$fields[] = "user.login";
		$fields[] = "user.name";
		$fields[] = "user.surname";
		$fields[] = "enroll.courses_id";
		$fields[] = "course.name as course_name";
		$fields[] = "enroll.data_registro";
		$fields[] = "enroll.payment_id";
		$fields[] = "enroll.status_id";
		$fields[] = "enroll_status.name as status";
		$fields[] = "enroll.tag";

		$order[] = "data_registro DESC";

		$iesID = $this->getCurrentUserIesIDs();

		$iesID[] = 0;

		$where = array(
		sprintf("enroll.ies_id IN (%s)", implode(', ', $iesID))
		);

		if (is_numeric($_GET['enrollment_status'])) {
			$where[] = sprintf("enroll.status_id = %d", $_GET['enrollment_status']);
		}

		$lastEnrollmentsData = eF_getTableData(implode(' ', $table), implode(",", $fields), implode(" AND ", $where), implode(",", $order), "", $limit);
		 
		foreach($lastEnrollmentsData as $key => $enrollItem) {
			$lastEnrollmentsData[$key]['username'] = formatLogin(null, $enrollItem);
		}
		 
		$smarty -> assign("T_LAST_ENROLLMENTS_LIST", $lastEnrollmentsData);
		 
		$template = array(
			'title'			=> $this->getTitle($this->getCurrentAction()),
	        'template'		=> $this->moduleBaseDir . "templates/actions/xenrollment.show_last.tpl",
    		'class'			=> "",
    		'contentclass'	=> ""
    		);




    		/** @todo Include this module data in a global object, with core configurations */
    		/*
    		 switch ($GLOBALS['configuration']['date_format']) {
    		 case "YYYY/MM/DD": {
    		 $date_format = 'Y/m/d'; break;
    		 }
    		 case "MM/DD/YYYY": {
    		 $date_format = 'm/d/Y'; break;
    		 }
    		 case "DD/MM/YYYY":
    		 default: {
    		 $date_format = 'd/m/Y'; break;
    		 }
    		 }
    		 switch ($GLOBALS['configuration']['time_format']) {
    		 case "HH:mm": {
    		 $time_format = 'h:i'; break;
    		 }
    		 case "HH:mm:SS":
    		 default: {
    		 $time_format = 'h:i:s'; break;
    		 }
    		 }
    		 $datetime_format = $date_format . ' ' . $time_format;

    		 $this->addModuleData("format.date", $date_format);
    		 $this->addModuleData("format.time", $time_format);
    		 $this->addModuleData("format.datetime", $datetime_format);
    		 */

    		$this->appendTemplate($template);
	}
	public function checkUsersXenrollmentAction() {

		//T_USERS_WITHOUT_ENROLLMENTS_LIST
		$toMakeEnroll = $this->getUsersToMakeEnrollmentList();

		$smarty = $this->getSmartyVar();
		$smarty -> assign("T_USERS_WITHOUT_ENROLLMENTS_LIST", $toMakeEnroll);
		 
		$template = array(
			'title'			=> $this->getTitle($this->getCurrentAction()),
	        'template'		=> $this->moduleBaseDir . "templates/actions/xenrollment.users_without_enrollment.tpl",
    		'class'			=> "",
    		"contentclass"	=> ""
    		);

    		$this->appendTemplate($template);
    		 
	}
	public function makeXenrollmentOptionsAction() {
		$selectedAction = $this->getCurrentAction();
		$smarty 		= $this -> getSmartyVar();

		if ($selectedAction != self::REGISTER_XENROLLMENT) {

			$options = array();
				
			$options[] = array(
				'text' 		=> __XENROLLMENT_SHOW_LAST_ENROLLMENT_TITLE,
				'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png", 
				'href' 		=> $this->moduleBaseUrl . '&action=' . $this->getDefaultAction()
			);
				
			$options[] = array(
				'text' 		=> __XENROLLMENT_REGISTER,
				'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png", 
				'href' 		=> $this->moduleBaseUrl . '&action=' . self::REGISTER_XENROLLMENT . "&xuser_id=" . $_GET['xuser_id'] . "&xuser_login=" . $_GET['xuser_login']
			);
				
			$options[] = array(
				'text' 		=> __XENROLLMENT_CHECK_ENROLLMENTS,
				'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png", 
				'href' 		=> $this->moduleBaseUrl . '&action=' . self::CHECK_USERS_XENROLLMENT
			);
				
			$smarty -> assign("T_" . $this->getName() . "_OPTIONS", $options);
		}

		return true;
	}

	/* MOVE TO XDOCUMENTS MODULE
	 *
	 */

	public function insertCourseDocumentsAction($token = null, $fields = null) {
		//$xuserModule = $this->loadModule("xuser");
		if (is_null($token)) {
			//$token = $this->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (
		eF_checkParameter($fields['document_id'], 'id') &&
		eF_checkParameter($fields['course_id'], 'id')
		) {
			$tableFields = array('document_id', 'course_id');
			$insertFields = array();
			foreach($fields as $key => $field) {
				if (in_array($key, $tableFields)) {
					$insertFields[$key] = $field;
				}
			}
				
				
			$result = eF_insertTableData(
				"module_xdocuments_to_courses", 
			$insertFields
			);
				
			return array(
				"message" 		=> __XDOCUMENTS_INSERT_COURSE_DOCUMENT_SUCCESS,
				"message_type" 	=>  "success"
				);
		} else {
			return array(
				"message" 		=> __XDOCUMENTS_INSERT_COURSE_DOCUMENT_FAIL,
				"message_type" 	=>  "failure"
				);
		}
	}
	public function updateUserDocumentsAction($token = null, $fields = null) {
		if (is_null($token)) {
			//$token = $this->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (
		eF_checkParameter($fields['document_id'], 'id') &&
		eF_checkParameter($fields['enrollment_id'], 'id')
		) {
			$tableFields = array('status_id');
				
			foreach($fields as $key => $field) {
				if (in_array($key, $tableFields)) {
					$updateFields[$key] = $field;
				}
			}
				
			if (
			$this->getDocumentsByEnrollmentId($fields['enrollment_id'], $fields['document_id']) == FALSE
			) {
				$result = eF_insertTableData(
					"module_xenrollment_documents_status",
				array_merge(
				array(
							'document_id'	=> $fields['document_id'], 
							'enrollment_id'	=> $fields['enrollment_id']
				),
				$updateFields
				)
				);
			} else {
				$result = eF_updateTableData(
					"module_xenrollment_documents_status", 
				$updateFields,
				sprintf("document_id = %d AND enrollment_id = %d", $fields['document_id'], $fields['enrollment_id'])
				);
			}
				
			$retorno = array(
				"message" 		=> __XDOCUMENTS_UPDATE_COURSE_DOCUMENT_SUCCESS,
				"message_type" 	=>  "success",
				"object"		=> $this->getDocumentsByEnrollmentId($fields['enrollment_id'], $fields['document_id'])
			);
				
			return $retorno;
		} else {
			return array(
				"message" 		=> __XDOCUMENTS_UPDATE_COURSE_DOCUMENT_FAIL,
				"message_type" 	=>  "failure"
				);
		}
	}
	public function updateCourseDocumentsAction($token = null, $fields = null) {
		if (is_null($token)) {
			//$token = $this->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (
		eF_checkParameter($fields['document_id'], 'id') &&
		eF_checkParameter($fields['course_id'], 'id')
		) {
			$tableFields = array('required');
				
			foreach($fields as $key => $field) {
				if (in_array($key, $tableFields)) {
					$updateFields[$key] = $field;
				}
			}
				
				
			$result = eF_updateTableData(
				"module_xdocuments_to_courses", 
			$updateFields,
			sprintf("document_id = %d AND course_id = %d", $fields['document_id'], $fields['course_id'])
			);
				
			return array(
				"message" 		=> __XDOCUMENTS_UPDATE_COURSE_DOCUMENT_SUCCESS,
				"message_type" 	=>  "success"
				);
		} else {
			return array(
				"message" 		=> __XDOCUMENTS_UPDATE_COURSE_DOCUMENT_FAIL,
				"message_type" 	=>  "failure"
				);
		}
	}
	public function deleteCourseDocumentsAction($token = null, $fields = null) {
		//$xuserModule = $this->loadModule("xuser");
		if (is_null($token)) {
			//$token = $this->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (
		eF_checkParameter($fields['document_id'], 'id') &&
		eF_checkParameter($fields['course_id'], 'id')
		) {
				
			$result = eF_deleteTableData(
				"module_xdocuments_to_courses", sprintf("document_id = %d AND course_id = %d", $fields['document_id'], $fields['course_id'])
			);
				
			return array(
				"message" 		=> __XDOCUMENTS_DELETE_COURSE_DOCUMENT_SUCCESS,
				"message_type" 	=>  "success"
				);
		} else {
			return array(
				"message" 		=> __XDOCUMENTS_DELETE_COURSE_DOCUMENT_FAIL,
				"message_type" 	=>  "failure"
				);
		}
	}

	/* HOOK-ACTION HANDLERS */
	public function xuser_editXuserAction($context, $sendData = null) {
		//var_dump($context->getEditedUser());
		 
		if (
		$context->getEditedUser()->getType() != "administrator" &&
		!($this->getEditedUser() instanceof MagesterAdministrator) &&
		in_array($context->getEditedUser()->getType(), array_keys($context->getEditedUser()->getStudentRoles()))
		) {
			$smarty 		= $this -> getSmartyVar();
			$options = array();

			$options[] = array(
				'text' 		=> __XENROLLMENT_REGISTER,
				'hint'		=> __XENROLLMENT_REGISTER_HINT, 
				'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png", 
				'href' 		=> $this->moduleBaseUrl . '&action=' . self::REGISTER_XENROLLMENT . "&xuser_id=" . $context->getEditedUser()->user['id'] . "&xuser_login=" . $context->getEditedUser()->user['login']
			);
			$smarty -> assign("T_" . $this->getName() . "_COURSES_LIST_OPTIONS", $options);
				
			$xcourseModule = $this->loadModule("xcourse");
				
			$courses = $xcourseModule->getUserCoursesList($context->getEditedUser()->user['login']);
				
				
				
			/*
			 foreach($courses as $key => &$course) {
				// LOAD DOC LIST FOR COURSE
				$course['documents_list'] = $this->getCourseDocumentListUserStatus($course['id'], $context->getEditedUser()->user['id']);

				$course['documents_summary'] = array(
				'total'		=> count($course['documents_list']),
				'checked'	=> 0
				);
				foreach($course['documents_list'] as $document) {
				if ($document['status_id'] == 2) {
				$course['documents_summary']['checked']++;
				}
				}
				}
				*/
			$smarty -> assign("T_XENROLLMENT_EDITED_USER", $context->getEditedUser()->user);
				
			$smarty -> assign("T_XENROLLMENT_COURSES_LIST", $courses);

			$context->appendTemplate(
			array(
            		'title'			=> __XUSER_SHOWUSERCOURSES,
            		'template'		=> $this->moduleBaseDir . "templates/hook/xuser.edit_xuser.courses.tpl",
            		'contentclass'	=> ''
            		)
            		);
            		/*
            		 $context->appendTemplate(array(
            		 'title'			=> $this->getTitle(self::OPEN_DOCUMENTS_LIST),
            		 'template'		=> $this->moduleBaseDir . "templates/hook/xuser.edit_xuser.documents.tpl",
            		 "contentclass"	=> ""
            		 ));
            		 */
		}
	}
	public function xcourse_editXcourseAction($context, $sendData) {
		$smarty 		= $this -> getSmartyVar();

		// LOAD DOC LIST
		$documents = $this->getDocumentList();

		// LOAD DOC LIST FOR COURSE
		$courseDocuments = $this->getCourseDocumentList($context->getEditedCourse()->course['id']);

		// CLEAR DOC ALREADY IN COURSE
		$documentToAppend = array();
		foreach($documents as $doc) {
			$found = false;
			foreach($courseDocuments as $courseDoc) {
				if ($courseDoc['document_id'] == $doc['document_id']) {
					$found = true;
					break;
				} else {
						
				}
			}
			if (!$found) {
				$documentToAppend[] = $doc;
			}
		}

		$options = array();

		if (count($documentToAppend) > 0) {
			$options[] = array(
				'text' 		=> __XDOCUMENTS_ADD_TO_COURSE,
				'hint'		=> __XDOCUMENTS_ADD_TO_COURSE_HINT, 
				'image' 	=> "/themes/sysclass/images/icons/small/grey/books.png", 
				'href' 		=> "javascript: void(0);",
				'class'		=> 'xdocument-add-to-course'
				);
				$smarty -> assign("T_XDOCUMENTS_COURSE_OPTIONS", $options);
		}

		$smarty -> assign("T_XDOCUMENTS_TO_APPEND_LIST", $documentToAppend);
		$smarty -> assign("T_XDOCUMENTS_COURSES_LIST", $courseDocuments);

		$smarty -> assign("T_XENROLLMENT_EDITED_COURSE", $context->getEditedCourse()->course);

		$context->appendTemplate(array(
			'title'			=> $this->getTitle(self::OPEN_DOCUMENTS_LIST),
	        'template'		=> $this->moduleBaseDir . "templates/hook/xcourse.edit_xcourse.documents.tpl",
    		"contentclass"	=> ""
    		));


    		// MUST BE CALLED TO POPULATE SMARTY MODULE VARS
    		$this->assignSmartyModuleVariables();
	}

	public function onPaymentReceivedEvent($context, $data) {
		if (eF_checkParameter($data['enrollment_id'], 'id')) {
			if (eF_checkParameter($data['parcela_index'], 'id') && $data['parcela_index'] == 1) {
				$this->addEnrollmentHistory(
				$data['enrollment_id'],
				array(
	    				'status_id' => self::PAID_STATE
				)
				);
				 
				// CHANG HERE USER TYPE TO array(user_types_ID => 0)
				 
			}
		}
	}

	/* UTILITY FUNCTIONS */
	public function makeEnrollmentChecklist() {
		if (
		$this->getCurrentUser()->getType() == "administrator"
		) {
			$newEnrollment = $this->getEditedEnrollment();
				
			if (!$newEnrollment) {
				return false;
			}
				
			$smarty 		= $this -> getSmartyVar();
				
			$xuserModule 	= $this->loadModule("xuser");
				
			$smarty -> assign ("T_XENROLLMENT_ADD_USER_URL", $xuserModule->getUrl(module_xuser::ADD_XUSER));
			$smarty -> assign ("T_XENROLLMENT_SELECT_USER_URL", $xuserModule->getUrl(module_xuser::GET_XUSERS));
				
			if ($newEnrollment['users_id'] != 0) {
				$this->getEditedUser(false, $newEnrollment['users_id']);
			}
				
			$userCoursesIDs = array();
				
			if ($this->getEditedUser()) {
				//$newEnrollment = $this->updateXenrollmentAction($newEnrollment['token'], array('users_id' => $this->getEditedUser()->user['id']));

				$url = $xuserModule->moduleBaseUrl . "&action=" .  module_xuser::EDIT_XUSER . "&xuser_id=" . $this->getEditedUser()->user['id'];

				$this->getEditedUser()->user['edit_link'] = $url;

				$smarty -> assign("T_XENROLLMENT_SELECTED_USER", $this->getEditedUser()->user);

				if ($this->getCurrentAction() == self::REGISTER_XENROLLMENT) {
					$newEnrollment = $this->updateXenrollmentAction($newEnrollment['token'], array('users_id' => $this->getEditedUser()->user['id']));
				}

					
				$xcourseModule 	= $this->loadModule("xcourse");

				$smarty -> assign ("T_XENROLLMENT_ADD_COURSE_URL", $xcourseModule->getUrl(module_xcourse::ADD_XCOURSE));
				$smarty -> assign ("T_XENROLLMENT_SELECT_COURSE_URL", $xcourseModule->getUrl(module_xcourse::GET_XCOURSES));

				if ($newEnrollment['courses_id'] != 0) {
					$result = $this->getEditedCourse(false, $newEnrollment['courses_id']);
				}

				$userCourses = $xcourseModule->getUserCoursesList($this->getEditedUser()->user['login']);

				foreach($userCourses as $key => &$course) {
					$userCoursesIDs[] = $course['id'];
					/*
						// LOAD DOC LIST FOR COURSE
						$course['documents_list'] = $this->getCourseDocumentListUserStatus($course['id'], $this->getEditedUser()->user['id']);

						$course['documents_summary'] = array(
						'total'		=> count($course['documents_list']),
						'checked'	=> 0
						);
						foreach($course['documents_list'] as $document) {
						if ($document['status_id'] == 2) {
						$course['documents_summary']['checked']++;
						}
						}
						*/
				}

				$ies = $this->getCurrentUserIesIDs();
				 
				$params = array(
	   				'active' => 1,
				//'show_catalog' => 1,
	   				'ies_id' => $ies
				);
				 
				$courses = $xcourseModule->getCoursesList($params);
				 
				foreach($courses as $key => &$course) {
					if (!in_array($course['id'], $userCoursesIDs)) {
						$course['user_in_course'] = false;
					} else {
						$course['user_in_course'] = true;
					}
				}
				 
				$smarty -> assign("T_XENROLLMENT_DISPONIBLE_COURSES_LIST", $courses);

				if ($this->getEditedCourse()) {
					if ($this->getCurrentAction() == self::REGISTER_XENROLLMENT) {
						$newEnrollment = $this->updateXenrollmentAction($newEnrollment['token'], array('courses_id' => $this->getEditedCourse()->course['id']));
					}
						
					$url = $xuserModule->moduleBaseUrl . "&action=" .  module_xcourse::EDIT_XCOURSE . "&xcourse_id=" . $this->getEditedCourse()->course['id'];
					$this->getEditedUser()->user['edit_link'] = $url;
						
						
					$selectedCourse = $this->getEditedCourse()->course;
					// LOAD DOC LIST FOR COURSE
					$selectedCourse['documents_list'] = $this->getCourseDocumentListUserStatus($selectedCourse['id'], $this->getEditedUser()->user['id']);
						
					$selectedCourse['documents_summary'] = array(
						'total'		=> count($selectedCourse['documents_list']),
						'checked'	=> 0
					);
					foreach($selectedCourse['documents_list'] as $document) {
						if ($document['status_id'] == 2) {
							$selectedCourse['documents_summary']['checked']++;
						}
					}
					$smarty -> assign("T_XENROLLMENT_SELECTED_COURSE", $selectedCourse);
						
					if ($selectedClass = $this->getClassByUserAndCourseID($newEnrollment['users_id'], $newEnrollment['courses_id'])) {
						$smarty -> assign("T_XENROLLMENT_SELECTED_CLASSE", $selectedClass->classe);
					}
					if ($selectedUserCourseLink = $this->getUserToCourseLinkByUserAndCourseID($newEnrollment['users_id'], $newEnrollment['courses_id'])) {
						$smarty -> assign("T_XENROLLMENT_SELECTED_MODALITY", $selectedUserCourseLink);
					}
						
						
						
					$course_classes = MagesterCourseClass :: convertClassesObjectsToArrays($this->getEditedCourse()->getCourseClasses());
					$smarty -> assign("T_XENROLLMENT_DISPONIBLE_CLASSES_LIST", $course_classes);
				}

				if ($newEnrollment['payment_id'] != 0) {
					$this->getEditedPayment(false, $newEnrollment['payment_id']);
				}

				$userCoursesIDs = array();

				if ($this->getEditedPayment()) {
					$paymentData = $this->getEditedPayment();
						
						
						
					if ($paymentData) {
						if ($this->getCurrentAction() == self::REGISTER_XENROLLMENT) {
							$newEnrollment = $this->updateXenrollmentAction($newEnrollment['token'], array('payment_id' => $paymentData['payment_id']));
						}
						$smarty -> assign("T_XENROLLMENT_SELECTED_PAYMENT", $paymentData);
					}
				}
			}

			$this->addModuleData('enrollment', $newEnrollment);
				
			$smarty -> assign("T_XENROLLMENT_SELECTED_ENROLLMENT", $newEnrollment);
				
			$this->appendTemplate(
			array(
            		'title'			=> __XENROLLMENT_REGISTER_STUDENT,
            		'template'		=> $this->moduleBaseDir . "templates/actions/xenrollment.register_enrollment.tpl",
			)
			);
			$this->setCache("enrollent_token", $newEnrollment['token']);
		}
	}

	public function makeHistoryForm($forStatus = 0, $message = '', $enrollment_id = null) {
		// CREATING HISTORIC FORM
		$smarty = $this -> getSmartyVar();

		$xUserModule = $this->loadModule("xuser");
		//$selectedAction = $this->getCurrentAction();
		///$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		$currentUser = $this->getCurrentUser();
		if (is_null($enrollment_id)) {
			$enroll = $this->getEditedEnrollment();
		} else {
			$enroll = $this->getEditedEnrollment(true, $enrollment_id);
		}

		$defaults = array(
			'enrollment_id' => $enroll['id'],
			'status_id' 	=> $forStatus,
			'body' 			=> ''
			);
				
			if ($currentUser->getType() == 'administrator') { // IS ADMIN CANCELING A USER
			} elseif ($currentUser->getType() == 'student') { // THE IS CANCEL YOURSELF
			} else { // NOT PERMITED
				return false;
			}
			// DEFINE FORM AND ELEMENTS
			$historyForm = new HTML_QuickForm("xenrollment_history_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, false);
				
			$historyForm -> addElement('hidden', 'enrollment_id');
			$historyForm -> addElement('hidden', 'status_id');
			$historyForm -> addElement('wysiwyg', 'body', __XENROLLMENT_BODY, 'class = "full"');
			$historyForm -> addElement('submit', 'submit_xenrollment', __XENROLLMENT_SAVE, 'class = "button_colour round_all"');


			if ($historyForm -> isSubmitted() && $historyForm -> validate()) { // HANDLE FORM
				$values = $historyForm->exportValues();
					
				if ($enroll['status_id'] != $values['status_id'] && $values['status_id'] != 0) {
					eF_updateTableData(
					"module_xenrollment", 
					array('status_id' => $values['status_id']),
					'id = ' . $enroll['id']
					);
				}
					
				$fields = array(
				'enrollment_id' => $values['enrollment_id'],
				'status_id' 	=> $values['status_id'],
				'body'			=> $values['body']
				);
				eF_insertTableData("module_xenrollment_history", $fields);
				if (empty($message)) {
					$message = __XENROLLMENT_HISTORY_SAVE_SUCCESS;
				}
				$this->setMessageVar($message, "success");
			}
			// UPDATE DEFAULT VALUES
			$historyForm -> setDefaults( $defaults );
				
			$rendererHistory = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
			$historyForm -> accept($rendererHistory);
			$smarty -> assign('T_XENROLLMENT_HISTORY_FORM', $rendererHistory -> toArray());
			return true;
			 
			 
	}
	/* MAIN-INDEPENDENT MODULE PAGES */

	/* DATA MODEL FUNCTIONS /*/
	public function addEnrollmentHistory($enroll_id, $fields) {
		/*
		 CREATE TABLE IF NOT EXISTS `module_xenrollment_history` (
		 `id` mediumint(8) NOT NULL AUTO_INCREMENT,
		 `enrollment_id` mediumint(8) NOT NULL,
		 `data_registro` timestamp NULL DEFAULT NULL,
		 `status_id` mediumint(8) NOT NULL,
		 `message` varchar(255) NOT NULL,
		 PRIMARY KEY (`id`)
		 ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
		 */
		$countEnroll = eF_countTableData("module_xenrollment", "id", sprintf('id = %d AND status_id = %d',  $enroll_id, $fields['status_id']));
		if ($countEnroll[0]['count'] == 0) {
			/** @todo Checar se a mudança de status é permitida */
			 eF_updateTableData("module_xenrollment",
			 array('status_id' => $fields['status_id']),
			 sprintf('id = %d',  $enroll_id)
			 );
			 }
			  
			 $countHistory = eF_countTableData("module_xenrollment_history", "id", sprintf("enrollment_id = %d AND status_id = %d", $enroll_id, $fields['status_id']));
			 if ($countHistory[0]['count'] == 0) {
			 $insertFields = array_merge(array(
			 'enrollment_id' => $enroll_id,
			 'message'		=> ''
			 ), $fields);
			  
			 eF_insertTableData("module_xenrollment_history", $insertFields);

			 return true;
			 } else {
			 return false;
			 }
			  
			 }


			 public function getEnrollmentById($enrollment_id){
			 $result = eF_getTableData("module_xenrollment", "*", "id = '" . $enrollment_id . "'");

			 if ($result) {
			 return $result[0];
			 } else {
			 return false;
			 }
			 }
			 public function getEnrollmentByToken($enrollment_token){
			 $result = eF_getTableData("module_xenrollment", "*", "token = '" . $enrollment_token . "'");

			 if ($result) {
			 return $result[0];
			 } else {
			 return false;
			 }
			 }
			 public function getEnrollmentByUserAndCourseID($user_id, $course_id){
			 $result = eF_getTableData("module_xenrollment", "*", "users_id = '" . $user_id . "' AND courses_id = '" . $course_id . "'");

			 if ($result) {
			 return $result[0];
			 } else {
			 return false;
			 }
			 }
			 public function getEnrollmentIDsByUserId($user_id) {
			 $result = eF_getTableDataFlat("module_xenrollment", "id", "users_id = '" . $user_id . "'");

			 if ($result) {
			 return $result['id'];
			 } else {
			 return array();
			 }
			 }
			 public function getEnrollmentFieldByUserId($user_id, $field) {
			 //		echo prepareGetTableData("module_xenrollment", $field, "users_id = '" . $user_id . "'");
			 $result = eF_getTableDataFlat("module_xenrollment", $field, "users_id = '" . $user_id . "'");

			 if ($result) {
			 return $result[$field];
			 } else {
			 return array();
			 }
			 }
			 public function getClassByEnrollmentID($enrollment_id) {
			 if ($enroll = $this->getEnrollmentById($enrollment_id)) {
			 return $this->getClassByUserAndCourseID($enroll['users_id'], $enroll['courses_id']);
			 }
			 return false;
			 }
			 public function getClassByUserAndCourseID($user_id, $course_id) {
			 $xuserModule = $this->loadModule("xuser");
			 if ($xuserObject = $xuserModule->getUserById($user_id)) {
			 $result = eF_getTableDataFlat(
				"users_to_courses",
				"classe_id",
				sprintf("users_LOGIN = '%s' AND courses_ID = %d", $xuserObject->user['login'], $course_id)
				);
					
				if ($result['classe_id'][0]) {
				return new MagesterCourseClass($result['classe_id'][0]);
				}
				}
				return false;
				}
				public function getUserToCourseLinkByUserAndCourseID($user_id, $course_id) {
				$xuserModule = $this->loadModule("xuser");
				if ($xuserObject = $xuserModule->getUserById($user_id)) {
				$result = eF_getTableData(
				"users_to_courses",
				"*",
				sprintf("users_LOGIN = '%s' AND courses_ID = %d", $xuserObject->user['login'], $course_id)
				);
					
				if (count($result) > 0) {
				return $result[0];
				}
				}
				return false;
				}
				public function getUsersToMakeEnrollmentList($contraints = array()) {
				// TABLES
				$tables = array(
				"users_to_courses uc",
				"LEFT JOIN users u ON (uc.users_LOGIN = u.login)",
				"LEFT OUTER JOIN module_xuser ud ON (ud.id = u.id)",
				"LEFT JOIN courses c ON (uc.courses_ID = c.id)",
				"LEFT JOIN module_ies i ON (i.id = c.ies_id)",
				"LEFT OUTER JOIN module_pagamento pag ON (u.id  = pag.user_id)",
				"LEFT OUTER JOIN user_types ut ON (u.user_types_ID  = ut.id)"
				);

				// FIELDS
				$fields = array(
				"u.timestamp",
				"c.ies_id as ies_id",
				"i.nome as ies_name",
				"c.id as courses_id",
				"u.id as users_id",
				"pag.payment_id",
				"u.user_types_ID",
				"u.login",
				"u.name",
				"u.surname",
				"c.name as course_name",
				"ut.name as status",
				"u.active as ativo"
				);

				$ies = $this->getCurrentUserIesIDs();
				// DEFAULTS CONTRAINTS
				$params = array(
				//	   		'active' 		=> 1,
				'ies_id' 		=> $ies,
				'user_types'	=> array(6, 10, 12, 16)
				);
				$contraints = array_merge(
				$params,
				$contraints
				);

				$where = array();
				// FIXED CONTRAINTS
				$where[]	= "(SELECT id FROM module_xenrollment enroll WHERE u.id = enroll.users_id AND uc.courses_ID = enroll.courses_id LIMIT 1) IS NULL";
				$where[]	= "(
				u.id NOT IN (SELECT users_id FROM module_xenrollment enroll WHERE uc.courses_ID = enroll.courses_id) AND
				uc.courses_ID NOT IN (SELECT courses_id FROM module_xenrollment enroll WHERE u.id = enroll.users_id)
				)";

				// USER CONTRAINTS
				$where[] 	= sprintf("(u.user_types_ID IN (%s) OR u.user_type IN ('student'))", implode(",", $contraints['user_types']));
				if ($contraints['ies_id']) {
				if (!is_array($contraints['ies_id'])) {
				$contraints['ies_id'] = array($contraints['ies_id']);
				}
				$contraints['ies_id'][] = 0;
				$where[] 	= sprintf('c.ies_id IN (%s)', implode(", ", $contraints['ies_id']));
				$where[] 	= sprintf('(ud.ies_id IN (%s) OR ud.ies_id IS NULL)', implode(", ", $contraints['ies_id']));
				}
				 
				if ($contraints['active']) {
				$where[]	= 'u.active = ' . $contraints['active'];
				}
				 
				// DEFAULT ORDER
				$order = array(
				"u.active DESC",
				"u.name ASC"
				);
				/*
				echo prepareGetTableData(
				implode(' ', $tables),
				implode(', ', $fields),
				implode(' AND ', $where),
				implode(', ', $order)
				);
				*/
			$result = eF_getTableData(
			implode(' ', $tables),
			implode(', ', $fields),
			implode(' AND ', $where),
			implode(', ', $order)
			);

			if ($result) {
				$toMakeEnroll = array();
				foreach ($result as $item) {
					$item['username'] = formatLogin(null, $item);

					if ($item['timestamp'] != 0 && !is_null($item['timestamp'])) {
						$item['data_registro'] = date("Y-m-d H:i:s", $item['timestamp']);
					}

					$toMakeEnroll[] = $item;
				}
					
				return $toMakeEnroll;
			} else {
				return false;
			}
			/*
			 SELECT

			 --(SELECT id FROM module_xenrollment enroll WHERE users.id = enroll.users_id AND uc.courses_ID = enroll.courses_id) as enrollment_id,
			 --EXISTS (SELECT id FROM module_xenrollment enroll WHERE users.id = enroll.users_id AND uc.courses_ID = enroll.courses_id AND enroll.payment_id = pag.payment_id) as has_payment
			 */
		}

		/**
		 * @todo Move To your own module "module_xdocuments"
		 */
		public function getDocumentList() {
			$tables = array(
			"module_xdocuments doc",
			"LEFT JOIN module_xdocuments_types doc_typ ON (doc.type_id = doc_typ.id)",
			);
				
			$documentsData = eF_getTableData(implode(" ", $tables),
    		'doc.document_id, doc.name, doc.description, doc.data_registro, doc.type_id, 
    		doc_typ.name as type, doc.required, doc.user_responsible, doc.user_authority'
    		);

    		return $documentsData;
		}
		public function getCourseDocumentList($course_id) {
			if (eF_checkParameter($course_id, 'id')) {
				$tables = array(
				"module_xdocuments doc",
				"LEFT JOIN module_xdocuments_types doc_typ ON (doc.type_id = doc_typ.id)",
				"LEFT JOIN module_xdocuments_to_courses doc_course ON (doc.document_id = doc_course.document_id)", 
				);
					
				$documentsData = eF_getTableData(implode(" ", $tables),
	    		'doc_course.course_id, doc.document_id, doc.name, doc.description, doc.data_registro, doc.type_id, 
	    		doc_typ.name as type, doc_course.required, doc.user_responsible, doc.user_authority',
	    		"doc_course.course_id = " . $course_id
				);

				return $documentsData;
			}
			return array();
		}
		public function getCourseDocumentListUserStatus($course_id, $user_id) {
			//$enrollmentData = $this->getUserEnrollmentsByUserId($user_id);
			$courseDocsData = $this->getCourseDocumentList($course_id);
			 
			$enrollIDs = $this->getEnrollmentIDsByUserId($user_id);
			 
			$docList = array();
			 
			if (count($enrollIDs) > 0) {
				 
				$docStatusData = eF_getTableData(
	    		"module_xenrollment enr " .
	    		"LEFT OUTER JOIN module_xenrollment_documents_status enr_stat ON (enr.id = enr_stat.enrollment_id)" . 
	    		"LEFT JOIN module_xdocuments_status doc_stat ON (enr_stat.status_id = doc_stat.id)",
	    		"enr_stat.enrollment_id, enr.courses_id as course_id, enr_stat.document_id, enr_stat.status_id, doc_stat.name as status",
				sprintf("enr.id IN (%s)", implode(',', $enrollIDs))
				);
			} else {
				$docStatusData = array();
			}


			foreach($courseDocsData as &$courseDoc) {
				$item = $courseDoc;

				$item['register_exists'] = false;
				foreach($docStatusData as $userDoc) {
					if (
					$courseDoc['course_id'] == $userDoc['course_id'] &&
					$courseDoc['document_id'] == $userDoc['document_id']
					) {
						$item = array_merge($courseDoc,$userDoc);
						$item['register_exists'] = true;
						break;
					}
				}
				$docList[] = $item;
			}
			 
			 
			return $docList;
		}
		public function getDocumentsByEnrollmentId($enrollment_id, $document_id = null) {
			//$enrollmentData = $this->getUserEnrollmentsByUserId($user_id);
			if (eF_checkParameter($enrollment_id, 'id')) {
				$enrollData = $this->getEnrollmentById($enrollment_id);
					
				$courseDocsData = $this->getCourseDocumentList($enrollData['courses_id']);
				 
				$where = array();
				$where[] = sprintf("enr.id IN (%s)", $enrollment_id);
				if (!is_null($document_id) && eF_checkParameter($document_id, 'id')) {
					$where[] = sprintf("enr_stat.document_id IN (%s)", $document_id);
				}
				$docStatusData = eF_getTableData(
		    	"module_xenrollment enr " .
		    	"LEFT OUTER JOIN module_xenrollment_documents_status enr_stat ON (enr.id = enr_stat.enrollment_id)" . 
		    	"LEFT JOIN module_xdocuments_status doc_stat ON (enr_stat.status_id = doc_stat.id)",
		    	"enr_stat.enrollment_id, enr.courses_id as course_id, enr_stat.document_id, enr_stat.status_id, doc_stat.name as status",
				implode(' AND ', $where)
				);
			} else {
				return false;
			}
			 
			$docList = array();
			 
			foreach($courseDocsData as &$courseDoc) {
				foreach($docStatusData as $userDoc) {
					if (
					$courseDoc['course_id'] == $userDoc['course_id'] &&
					$courseDoc['document_id'] == $userDoc['document_id']
					) {
						$item = array_merge($courseDoc,$userDoc);
						$docList[] = $item;
						break;
					}
				}
			}
			if (count($docList) == 0) {
				return false;
			}
			 
			if (!is_null($document_id) && eF_checkParameter($document_id, 'id')) {
				return reset($docList);
			}
			 
			return $docList;
		}
	}
	?>