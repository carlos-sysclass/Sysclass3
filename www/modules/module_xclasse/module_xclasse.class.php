<?php
/*
class MagesterCourseTree extends MagesterTree
{
	public function insertNode($node, $parentNode = false, $previousNode = false) {}
	public function removeNode($node) {}
	public function reset() {}
}
*/

class module_xclasse extends MagesterExtendedModule
{
    // Mandatory functions required for module function
    public function getName()
    {
        return "XCLASSE";
    }

    public function getPermittedRoles()
    {
        return array("administrator");
    }

    public function isLessonModule()
    {
        return false;
    }

    public function getUrl($action)
    {
    	switch ($action) {
    		case "new_course_class" : {
    			return $this -> moduleBaseUrl .
    				"&action=" . $action .
    				"&xcourse_id=" . $this->getEditedCourse()->course['id'];
    		}
	   		case "edit_course_class" : {
    			return $this -> moduleBaseUrl .
    				"&action=" . $action .
    				"&xcourse_id=" . $this->getEditedCourse()->course['id'] .
    				"&xclasse_id=" . $this->getEditedCourseClass()->classe['id'];
    		}
    		default : {
    			return parent::getUrl($action);
    		}
    	}
    }
    public function getTitle($action)
    {
    	switch ($action) {
    		case "new_course_class" : {
    			return __XCLASSE_NEWCLASS;
    		}
    		case "edit_course_class" : {
    			return __XCLASSE_EDITCLASS;
    		}
    		case "view_course_class" : {
    			return __XCLASSE_VIEW_CLASSES;
    		}
    		default : {
    			return parent::getTitle($action);
    		}
    	}
    }

    public function getDefaultAction()
    {
    	return "view_course_class";
    }

    /* ACTIONS FUNCTIONS */
    public function newCourseClassAction()
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

    	$xuserModule = $this->loadModule("xuser");

		if ($xuserModule->getExtendedTypeID($currentUser) != 'administrator' &&
			$xuserModule->getExtendedTypeID($currentUser) != 'coordenator'
		) {
			header("Location: " . $this->moduleBaseUrl);
			exit;
		}

		if ( $this->makeClassesForm() ) {
			return true;
       	} else {
       		return false;
       	}
    }
    public function editCourseClassAction()
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

    	$xuserModule = $this->loadModule("xuser");

		if ($xuserModule->getExtendedTypeID($currentUser) != 'administrator' &&
			$xuserModule->getExtendedTypeID($currentUser) != 'coordenator'
		) {
			header("Location: " . $this->moduleBaseUrl);
			exit;
		}

		if ( $this->makeClassesForm() ) {
			return true;
       	} else {
       		return false;
       	}
    }
    public function deleteCourseClassAction()
    {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

    	$xuserModule = $this->loadModule("xuser");

		if ($xuserModule->getExtendedTypeID($currentUser) != 'administrator' &&
			$xuserModule->getExtendedTypeID($currentUser) != 'coordenator'
		) {
			header("Location: " . $this->moduleBaseUrl);
			exit;
		}

		//
		if ($this->getEditedCourseClass()) {

			$this->getEditedCourseClass()->delete();

			$redirect = "administrator.php?ctg=courses&course=" . $this->getEditedCourse() -> course['id'] . "&op=course_classes&message=".urlencode(__XCLASSE_DELETE_MESSAGE)."&message_type=success";
		} else {
			$redirect = "administrator.php?ctg=courses&course=" . $this->getEditedCourse() -> course['id'] . "&op=course_classes&message=".urlencode(__XCLASSE_ERROR_DELETE_MESSAGE)."&message_type=failure";
		}

		eF_redirect($redirect);
		exit;
    }

    public function getClassSchedulesAction($token = null, $fields = null)
    {
        $smarty = $this -> getSmartyVar();
		$smarty -> assign("T_MODULE_XCOURSE_ACTION", $selectedAction);

		// LOAD L10N DATA
        $modules = eF_loadAllModules(true);
        $l10nSection = $modules['module_language']->getSection("l10n");

        $smarty -> assign("T_L10N_DATA", $l10nSection['data']);

        // LOAD SCHEDULES FOR CLASS
		$courseClasses = $this->getEditedCourse()->getCourseClasses();

		if (eF_checkParameter($_GET['xcourse_class_id'], 'id')) {
			$courseClassID = $_GET['xcourse_class_id'];

			$smarty -> assign("T_XCOURSE_CLASS_SCHEDULES", $courseClasses[$courseClassID]->classe['schedules']);

			$smarty -> assign("T_FORM_ACTION", $this->moduleBaseUrl . '&action=' . self::SAVE_CLASS_SCHEDULES . '&xcourse_class_id=' . $courseClassID);

			echo $result = $smarty->fetch(
				$this->moduleBaseDir . '/templates/actions/' . self::GET_CLASS_SCHEDULES . '.tpl'
			);
		}
		exit;
    }
    public function saveClassSchedulesAction()
    {
		if (eF_checkParameter($_GET['xcourse_class_id'], 'id')) {
			$insertData = array(
				'week_day' 	=> $_POST['week_day']['new'],
				'start'		=> $_POST['start']['new'],
				'end' 		=> $_POST['end']['new']
			);

			unset($_POST['week_day']['new']);
			unset($_POST['start']['new']);
			unset($_POST['end']['new']);

			$updateData = array(
				'week_day' 	=> $_POST['week_day'],
				'start'		=> $_POST['start'],
				'end' 		=> $_POST['end']
			);

			$courseClassID = $_GET['xcourse_class_id'];
			$courseClass = new MagesterCourseClass($courseClassID);

			$courseClass->clearSchedule();

			foreach ($updateData['week_day'] as $index => $value) {
				$courseClass->appendSchedule(
					$updateData['week_day'][$index],
					$updateData['start'][$index],
					$updateData['end'][$index]
				);
			}

			foreach ($insertData['week_day'] as $index => $value) {
				$courseClass->appendSchedule(
					$insertData['week_day'][$index],
					$insertData['start'][$index],
					$insertData['end'][$index]
				);
			}

			if ($courseClass->persistSchedule()) {
				echo json_encode(array(
					'message'		=> __XCOURSE_CLASS_SCHEDULE_SAVE_MESSAGE,
					'message_type'	=> 'success'
				));
			} else {
				echo json_encode(array(
					'message'		=> __XCOURSE_CLASS_SCHEDULE_SAVE_ERROR_MESSAGE,
					'message_type'	=> 'error'
				));
			}
		} else {
			echo json_encode(array(
				'message'		=> __XCOURSE_CLASSID_NOT_FOUND,
				'message_type'	=> 'failure'
			));
		}
		exit;
    }

	public function editXcourseAction()
	{
       	if ( $this->makeCourseClassesForm() ) {
			$this->appendTemplate(array(
           		'title'			=> __XCOURSE_EDITXCOURSECLASSES,
           		'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.list.classes.tpl",
           		'contentclass'	=> '',
           		'class'			=> 'no_padding_color no_padding'
			));
       	}
       	$this->addModuleData('edited_course', $this->getEditedCourse()->course);
	}

    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    /* UTILITY FUNCTIONS */

    private function makeClassesForm()
    {
    	$smarty = $this->getSmartyVar();

    	if (isset($currentUser -> coreAccess['lessons']) && $currentUser -> coreAccess['lessons'] == 'hidden') {
			eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
		} elseif (isset($currentUser -> coreAccess['lessons']) && $currentUser -> coreAccess['lessons'] != 'change') {
			$_change_ = false;
		} else {
			$_change_ = true;
		}

		$formClass = new HTML_QuickForm(
			"add_courseclass_form",
			"post",
			$_SERVER['REQUEST_URI'],
			"", null, true
		);

		$formClass -> addElement('hidden', 'coursesID');
		$formClass -> addElement('hidden', 'action');
		$formClass -> addElement('hidden', 'id');

	 	$formClass -> addElement('text', 'name', _COURSECLASSNAME, 'class = "large"');
	 	$formClass -> addRule('name', _THEFIELD.' "'._COURSECLASSNAME.'" '._ISMANDATORY, 'required', null, 'client');
	 	$formClass -> addElement('advcheckbox', 'active', _ACTIVEFEM, null, null, array(0, 1));
	 	//$formClass -> addElement('advcheckbox', 'show_catalog', _SHOWCOURSEINCATALOG, null, null, array(0, 1));

	 	$formClass -> addElement('text', 'max_users', _MAXSTUDENTS, 'style = "display: none;"');
	 	$formClass -> addRule('max_users', _THEFIELD.' "'._MAXSTUDENTS.'" '._ISMANDATORY, 'numeric', null, 'client');

	 	$formClass -> addElement('jquerydate', 'start_date', _STARTDATE);
	 	$formClass -> addElement('jquerydate', 'end_date', _ENDDATE);

		$formClass -> addElement('submit', 'submit_xclasse_form', _SUBMIT);

		if (!$_change_) {
			$formClass -> freeze();
		} else {
			if ($formClass -> isSubmitted() && $formClass -> validate()) {
				$fields = $formClass -> exportValues();

				$fields = array(
					'courses_ID'		=> $formClass -> exportValue('coursesID') != 0 ? $formClass -> exportValue('coursesID') : $this->getEditedCourse()->course['id'],
					'id'				=> $formClass -> exportValue('id'),
					'name'				=> $formClass -> exportValue('name'),
					'info'				=> '',
					'active'			=> $formClass -> exportValue('active'),
					'duration'			=> 0,
					'options'			=> '',
					'languages_NAME'	=> $GLOBALS['configuration']['default_language'],
					'metadata'			=> null,
					'share_folder'		=> null,
					//'created'			=> time(),
					'max_users'			=> $formClass -> exportValue('max_users'),
					'archive'			=> 0
				);
				if (date_create_from_format('d/m/Y', $formClass -> exportValue('start_date')) !== FALSE) {
					$fields['start_date']	= date_create_from_format('d/m/Y', $formClass -> exportValue('start_date'))->format('U');
				}
				if (date_create_from_format('d/m/Y', $formClass -> exportValue('end_date')) !== FALSE) {
					$fields['end_date']	= date_create_from_format('d/m/Y', $formClass -> exportValue('end_date'))->format('U');
				}

				if (is_numeric($fields['id']) && $fields['id'] > 0) {
					// UPDATE CourseClass
					$editCourseClass = new MagesterCourseClass($fields['id']);
					$editCourseClass->classe = array_merge($editCourseClass->classe, $fields);
					$editCourseClass->persist();
					//$redirect = $this->moduleBaseUrl . "&action=edit_course_class&xcourse_id=".$this->getEditedCourse() -> course['id']."&xclasse_id=" . $editCourseClass->classe['id'] . &message=".urlencode(__XCLASSE_UPDATE_MESSAGE)."&message_type=success";

					$redirect = "administrator.php?ctg=courses&course=" . $this->getEditedCourse() -> course['id'] . "&op=course_classes&message=".urlencode(__XCLASSE_UPDATE_MESSAGE)."&message_type=success";

				} else {
					$editCourseClass = MagesterCourseClass:: createCourseClass($fields);

					//$redirect = $this->moduleBaseUrl . "&action=edit_course_class&xcourse_id=".$this->getEditedCourse() -> course['id']."&xclasse_id=" . $editCourseClass->classe['id'] . "&message=".urlencode(__XCLASSE_INSERT_MESSAGE)."&message_type=success";

					$redirect = "administrator.php?ctg=courses&course=" . $this->getEditedCourse() -> course['id'] . "&op=course_classes&message=".urlencode(__XCLASSE_UPDATE_MESSAGE)."&message_type=success";
				}

				!isset($redirect) OR eF_redirect($redirect);

				//$smarty -> assign("T_REDIRECT_PARENT_TO", basename($_SERVER['PHP_SELF'])."?ctg=courses&edit_course=" . $course->course['id'] );
				// RELOAD CLASS LIST
			}
		}


		/*
		$classes = $this->getEditedCourse() -> getCourseClasses($constraints);
		$totalEntries = $this->getEditedCourse() -> countCourseClasses($constraints);
		$xcourseClasses = MagesterCourseClass :: convertClassesObjectsToArrays($classes);
		*/
		if ($this->getEditedCourseClass(true)) {
			$courseClass = $this->getEditedCourseClass();

			$defaults = $courseClass->classe;
		} else {
			$defaults = MagesterCourseClass :: getDefaultCourseClassValues();
		}
		/* GET CONFIG DATE OPTIONS */
		$defaults['start_date']	= date('d/m/Y', $defaults['start_date']);
		$defaults['end_date']	= date('d/m/Y', $defaults['end_date']);

		unset($defaults['courses_ID']);

		$formClass->setDefaults($defaults);
		$formClass->setDefaults(array(
			'coursesID'	=> $this->getEditedCourse()->course['id']
		));

		$rendererClass = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$formClass -> accept($rendererClass);
		$smarty -> assign('T_XCLASSE_FORM', $rendererClass -> toArray());

		$smarty -> assign ("T_XCOURSE_CLASSES_LIST", $xcourseClasses);

		return true;
    }
}
