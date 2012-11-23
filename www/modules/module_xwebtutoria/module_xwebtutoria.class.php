<?php
class module_xwebtutoria extends MagesterExtendedModule
{
	const GET_XWEBTUTORIA = 'get_xwebtutoria';
	const ADD_XWEBTUTORIA = 'add_xwebtutoria';

	const _STATUS_WAIT		= 1;
	const _STATUS_REPLY		= 2;
	const _STATUS_PUBLISH	= 3;
	const _STATUS_HIDDEN	= 4;
	const _STATUS_CANCEL	= 5;

    // CORE MODULE FUNCTIONS
    public function getName()
    {
        return "XWEBTUTORIA";
    }
    public function getPermittedRoles()
    {
        return array("professor", "student");
    }
    public function isLessonModule()
    {
        return true;
    }

    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    /*
    public function getCourseDashboardLinkInfo()
    {
		return array(
			'title' 		=> __XWEBTUTORIA_NAME,
        	'image'			=> "images/others/transparent.gif",
			'image_class'	=> "sprite32 sprite32-chat",
            'link'  		=> $this -> moduleBaseUrl
		);
    }
    */

    public function addScripts()
    {
    	return array('tinyeditor/packed');
    }
    public function addStylesheets()
    {
    	return array('tinyeditor/packed');
    }
	public function getTitle($action)
	{
		switch ($action) {
			case self::ADD_XWEBTUTORIA : {
				return __XWEBTUTORIA_REGISTER;
			}
			default : {
				return parent::getTitle($action);
			}
		}
	}
    public function getDefaultAction()
    {
    	return self::GET_XWEBTUTORIA;
    }
    /* CURRENT-LESSON ATTACHED MODULE PAGES */
    public function getLessonModule()
    {
    	$result = $this->loadWebtutoriaLastItensBlock("lesson-module-index");
		// CHECK FOR TEMPLATING
		$smarty = $this -> getSmartyVar();
		//var_dump($this->templates);
		if (count($this->templates) > 0) {
			$smarty -> assign ("T_" . $this->getName() . "_TEMPLATES", $this->templates);
		}
		$smarty -> assign ("T_" . $this->getName() . "_MOD_DATA", $this->getModuleData());

		$this->assignSmartyModuleVariables();

    	return $result;
	}
	public function getLessonSmartyTpl()
	{
		return $this->getSmartyTpl();
	}

	/* BLOCK FUNCTIONS */
	public function loadWebtutoriaLastItensBlock($blockIndex = null)
	{
		if ($this->makeWebtutoriaList(false)) {

			if (!is_null($this->getParent())) {
				$context = $this->getParent();
			} else {
				$context = $this;
			}

			$context->appendTemplate(array(
		   		'title'			=> __XWEBTUTORIA_LAST_ITENS,
		   		'template'		=> $this->moduleBaseDir . 'templates/blocks/xwebtutoria.last_itens.tpl',
	    		'absoluteImagePath'	=> true,
	    		'contentclass'	=> 'blockContents',
	    		'options'		=> array(
	    			array(
	    				'text'			=> 'Adicionar pergunta',
	    				'image'			=> '16x16/add.png',
	    				'href'			=> $this->moduleBaseUrl . "&action=add_xwebtutoria"
	    			),
	    			array(
	    				'text'	=> 'Ver detalhes',
	    				'image'	=> '16x16/go_into.png',
	    				'href'	=> $this->moduleBaseUrl . "&action=" . $this->getDefaultAction()
	    			)
	    		)
	    	), $blockIndex);
		}
    	return true;
    }

    /* ACTIONS FUNCTIONS */
    public function getXwebtutoriaAction()
    {
   		$currentUser = $this->getCurrentUser();

   		$this->loadModule("xuser");
   		$userClasses = $this->modules['xuser']->getUserClasses();

		if ($currentUser->getType() == 'student') { // OPENING A QUESTION
			if ($this->makeWebtutoriaList(true)) {
				return true;
			} else {
				$this->setMessageVar(__XWEBTUTORIA_NO_MESSAGES_FOUND, "failure");
				return false;
			}
		} else { // NOT PERMITED
			$this->setMessageVar(__XWEBTUTORIA_NO_ACCESS, "failure");
			return false;
		}
		return true;
    }
	public function loadXwebtutoriaConversationAction()
	{
		if ($editedWebtutoria = $this->getEditedXwebtutoria()) {
			$smarty = $this->getSmartyVar();

			$smarty -> assign("T_XWEBTUTORIA_LIST", $this->getXwebtutoriaByParentId($editedWebtutoria['id'], true));

			$this->assignSmartyModuleVariables();

			echo $smarty -> fetch($this->moduleBaseDir . 'templates/actions/load_xwebtutoria_conversation.tpl');
			exit;
		} else {
			echo __XWEBTUTORIA_NO_ITENS_FOUND;
		}
		exit;
	}
    public function addXwebtutoriaAction()
    {
        if ($this->makeRegisterForm()) {
        	return true;
        }
		return false;
    }
    /* HOOK ACTIONS FUNCTIONS */
    /* UTILITY FUNCTIONS */
    private function makeWebtutoriaList($with_childs = true)
    {
    	$smarty = $this->getSmartyVar();

		$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());

    	$this->loadModule("xuser");
    	$this->loadModule("xcourse");

    	// CHECK PERMISSION
    	$userClasses = $this->modules['xuser']->getUserClasses();

    	$webtutoriaClasses = array();
    	$webtutoriaItens = array();
    	$webtutoriaCourses = array();

    	if ($this->getCurrentCourse() == FALSE) {
    		// GET WEBTUTORIA ITENS FOR ALL USER CLASSES
    		foreach ($userClasses as $classe) {
    			$webtutoriaClasses[$classe->classe['id']] = $classe->classe;
    			$webtutoriaItens[$classe->classe['id']] = $this->getXwebtutoriaByClasseId($classe->classe['id'], $with_childs);
    			$webtutoriaCourses[$classe->classe['id']] = $this->modules['xcourse']->getCourseById($classe->classe['courses_ID'])->course;

    		}
    	} else {
    		$smarty -> assign("T_XWEBTUTORIA_IN_LESSON_MODE", true);

    		$course_id = $this->getCurrentCourse()->course['id'];
    		foreach ($userClasses as $classe) {
    			if ($classe->classe['courses_ID'] == $course_id) {
    				$webtutoriaClasses[$classe->classe['id']] = $classe->classe;
    				$webtutoriaItens[$classe->classe['id']] = $this->getXwebtutoriaByClasseId($classe->classe['id'], $with_childs);
    				$webtutoriaCourses[$classe->classe['id']] = $this->modules['xcourse']->getCourseById($classe->classe['courses_ID']);
    				break;
    			}
    		}
    	}
	/**

    	var_dump($webtutoriaCourses);
    	var_dump($webtutoriaClasses);
    	var_dump($webtutoriaItens);
	*/
    	if (count($webtutoriaCourses) == 0 || count($webtutoriaClasses) == 0) {
    		return false;
    	}

    	$smarty -> assign("T_XWEBTUTORIA_COURSES", $webtutoriaCourses);
    	$smarty -> assign("T_XWEBTUTORIA_CLASSES", $webtutoriaClasses);
    	$smarty -> assign("T_XWEBTUTORIA_ITENS", $webtutoriaItens);

    	return true;
    }
    public function makeRegisterForm()
    {
		// CREATING RESPONSIBLE FORM
        $smarty = $this -> getSmartyVar();

        $this->loadModule("xuser");
        $this->loadModule("xcourse");
        //$selectedAction = $this->getCurrentAction();
        ///$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
        $currentUser = $this->getCurrentUser();
        $classes = $this->modules['xuser']->getUserClasses();

		$classeSelect = array();
		foreach ($classes as $classe) {
			$courseObject = $this->modules['xcourse']->getCourseById($classe->classe['courses_ID']);
			$classeSelect[$classe->classe['id']] = $courseObject->course['name'] . '&nbsp;&raquo;&nbsp;' . $classe->classe['name'];
		}
		// DEFINE FORM AND ELEMENTS
		$tutoriaForm = new HTML_QuickForm("xuser_responsible_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, false);

		$tutoriaForm -> addElement('hidden', 'parent_id');
		$tutoriaForm -> addElement('hidden', 'user_id');
		$tutoriaForm -> addElement('select', 'classe_id' , __XWEBTUTORIA_SELECT_CLASS, $classeSelect,'class = "medium"');
		$tutoriaForm -> addElement('hidden', 'status_id');
		$tutoriaForm -> addElement('hidden', 'avaliation_id');
		$tutoriaForm -> addElement('wysiwyg', 'body', __XWEBTUTORIA_BODY, 'class = "full"');
		$tutoriaForm -> addElement('submit', 'submit_xwebtutoria', __XWEBTUTORIA_SAVE, 'class = "button_colour round_all"');

		if (
			$this->modules['xuser']->getExtendedTypeID($currentUser) == 'webtutor' ||
			$this->modules['xuser']->getExtendedTypeID($currentUser) == 'professor' ||
			$this->modules['xuser']->getExtendedTypeID($currentUser) == 'student'
		) { // IS A PROFESSOR REPLYING A STUDENT OR A STUDENT REPLYING A PROFESSOR

			$defaults = array(
				//'parent_id' 	=> $webtutoria['parent_id'],
				'user_id' 		=> $currentUser->user['id'],
				//'classe_id' 	=> $webtutoria['classe_id'],
				'status_id' 	=> self::_STATUS_WAIT,
				'avaliation_id' => 1,
				'body' 			=> ''
			);

			$smarty->assign("T_XWEBTUTORIA_BODY_TITLE", __XWEBTUTORIA_ADD_QUESTION);

			if ($webtutoria = $this->getEditedXwebtutoria()) {
				$classe_id = $webtutoria['classe_id'];
				$defaults['parent_id']	= $webtutoria['id'];

				$tutoriaForm->getElement('classe_id')->freeze();

				$smarty->assign("T_XWEBTUTORIA_QUESTION", $webtutoria);

				$smarty->assign("T_XWEBTUTORIA_BODY_TITLE", __XWEBTUTORIA_POST_REPLY);

				//if ($webtutoria['user_id'] != $currentUser->user['id']) {
				//} else { // NOT PERMITED
				//	$this->setMessageVar(__XWEBTUTORIA_USER_CANT_REPLY_YOURSELF, "failure");
				//	return false;
				//}
			} elseif ($currentUser->getType() == 'student') { // OPENING A QUESTION
				if (count($classes) > 1) {
					if ($this->getCurrentCourse()) {
						// GET CLASS FOR THIS COURSE
						foreach ($classes as $classe) {
	    					if ($classe->classe['courses_ID'] == $this->getCurrentCourse()->course['id']) {
	    						$classe_id = $classe->classe['id'];
	    						$this->setCurrentClasse($classe_id);

	    						$tutoriaForm->getElement('classe_id')->freeze();
	    						break;
	    					}
						}
					}
					if (!$classe_id) {
						$classe = reset($classes);
						$classe_id = $classe->classe['id'];
						if (count($classes) == 1) {
							$this->setCurrentClasse($classe_id);
						}
					}
				}
				$defaults['parent_id']	= 0;
			} else { // NOT PERMITED
				$this->setMessageVar(__XWEBTUTORIA_ONLY_STUDENT_CAN_POST_QUESTIONS, "failure");
				return false;
			}
		} else { // NOT PERMITED
			$this->setMessageVar(__XWEBTUTORIA_ONLY_STUDENT_OR_PROFESSOR_CAN_POST_QUESTIONS, "failure");
			return false;
		}
		if (!$classe_id) {
			return false;
		}

		$defaults['classe_id']	= $classe_id;

		if ($tutoriaForm -> isSubmitted() && $tutoriaForm -> validate()) { // HANDLE FORM
			$values = $tutoriaForm->exportValues();

			$fields = array(
				'parent_id' 	=> $values['parent_id'],
				'user_id' 		=> $values['user_id'],
				'classe_id' 	=> $values['classe_id'],
				'status_id'		=> $values['status_id'],
				'avaliation_id'	=> $values['avaliation_id'],
				'body'			=> $values['body']
			);
			eF_insertTableData("module_xwebtutoria", $fields);
		}
        // UPDATE DEFAULT VALUES
		$tutoriaForm -> setDefaults( $defaults );

 		$rendererTutoria = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $tutoriaForm -> accept($rendererTutoria);
		$smarty -> assign('T_XWEBTUTORIA_REGISTER_FORM', $rendererTutoria -> toArray());
		return true;
    }

    public function getEditedXwebtutoria()
    {
		if (eF_checkParameter($_GET['xwebtutoria_id'], 'id')) {
			return $this->getXwebtutoriaById($_GET['xwebtutoria_id']);
		} elseif (eF_checkParameter($_POST['xwebtutoria_id'], 'id')) {
			return $this->getXwebtutoriaById($_POST['xwebtutoria_id']);
		}
    	return false;
    }
    /* DATA MODEL FUNCTIONS */
    /*
		CREATE TABLE IF NOT EXISTS `module_xwebtutoria` (
		  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
		  `parent_id` mediumint(8) NOT NULL,
		  `user_id` mediumint(8) NOT NULL,
		  `classe_id` mediumint(8) NOT NULL,
		  `status_id` mediumint(8) NOT NULL,
		  `avaliation_id` mediumint(8) NOT NULL,
		  `body` varchar(100) NOT NULL,
		  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM;
     */

    public function getXwebtutoriaById($webtutoria_id, $with_childs = false)
    {
		$itens = $this->getXwebtutoriaByField($webtutoria_id);

		foreach ($itens as &$item) {
			if ($item && $with_childs) {
				$item['child'] = $this->getXwebtutoriaByParentId($webtutoria_id, $with_childs);
			}
			break;
		}
		return $item;
    }
    public function getXwebtutoriaByParentId($webtutoria_id, $with_childs = false)
    {
  		$itens = $this->getXwebtutoriaByField($webtutoria_id, 'parent_id');

		foreach ($itens as &$item) {
			if ($item && $with_childs) {
				$item['child'] = $this->getXwebtutoriaByParentId($item['id'], $with_childs);
			}
		}

		return $itens;
    }
    private function getXwebtutoriaByClasseId($classe_id, $with_childs = false)
    {
    	/** @todo GET ALL LIST AND MAKE TREE BY php */
		$itens = $this->getXwebtutoriaByField($classe_id, 'wt.classe_id');

		$result = array();

		foreach ($itens as $key => $item) {
			if ($item && !$with_childs && $item['parent_id'] != 0) {
				continue;
			}
			if ($item && $with_childs) {
				$item['child'] = $this->getXwebtutoriaByParentId($item['id'], $with_childs);
			}
			$result[] = $item;
		}
		foreach ($result as $key => &$item) {
			if ($item && $with_childs && $item['parent_id'] != 0) {
				unset($result[$key]);
			}
		}
		//exit;
		return $result;
			/*

    	$data = eF_getTableData(
	    	"module_xwebtutoria wt
	    	LEFT JOIN users u ON (wt.user_id = u.id)
	    	LEFT JOIN classes cl ON (wt.classe_id = cl.id)
	    	LEFT JOIN module_xwebtutoria_status stat ON (wt.status_id = stat.id)
	    	LEFT JOIN module_xwebtutoria_avaliation aval ON (wt.avaliation_id = aval.id)",
	    	"wt.id, wt.parent_id, wt.user_id, wt.classe_id, wt.status_id, wt.avaliation_id,
	    		wt.body, wt.datetime,
	    	u.login, u.name, u.surname, u.avatar,
	    	cl.name as classe, stat.name as status, aval.name as avaliation,
	    	(SELECT COUNT(id) FROM module_xwebtutoria WHERE parent_id = wt.id) as total_childs",
    		sprintf("%s AND wt.classe_id = '%d'", (($only_root) ? 'wt.parent_id = 0' : ''), $classe_id),
    		'wt.datetime DESC'
    	);
    	if (count($data) > 0) {
    		foreach ($data as &$item) {
    			$item['username'] = formatLogin(null, $item);

				try {
					$file = new MagesterFile($item['avatar']);
					list($item['avatar_width'], $item['avatar_height']) = eF_getNormalizedDims($file['path'], 50, 50);
				} catch (MagesterFileException $e) {
					$item['avatar'] = G_SYSTEMAVATARSPATH."unknown_small.png";
					$item['avatar_width'] = 50;
					$item['avatar_height'] = 50;
				}
    		}
    		return $data;
    	}
    	return false;
			 * */
    }

    private function getXwebtutoriaByField($value, $field = 'wt.id')
    {
    	/*
  		echo prepareGetTableData(
	    	"module_xwebtutoria wt
	    	LEFT JOIN users u ON (wt.user_id = u.id)
	    	LEFT JOIN classes cl ON (wt.classe_id = cl.id)
	    	LEFT JOIN module_xwebtutoria_status stat ON (wt.status_id = stat.id)
	    	LEFT JOIN module_xwebtutoria_avaliation aval ON (wt.avaliation_id = aval.id)",
	    	"wt.id, wt.parent_id, wt.user_id, wt.classe_id, wt.status_id, wt.avaliation_id,
	    		wt.body, wt.datetime,
	    	u.login, u.name, u.surname, u.avatar,
	    	cl.name as classe, stat.name as status, aval.name as avaliation,
	    	(SELECT COUNT(id) FROM module_xwebtutoria WHERE parent_id = wt.id) as total_childs",
    		sprintf("%s = '%s'", $field, $value)
    	);
		 * */
    	$data = eF_getTableData(
	    	"module_xwebtutoria wt
	    	LEFT JOIN users u ON (wt.user_id = u.id)
	    	LEFT JOIN classes cl ON (wt.classe_id = cl.id)
	    	LEFT JOIN module_xwebtutoria_status stat ON (wt.status_id = stat.id)
	    	LEFT JOIN module_xwebtutoria_avaliation aval ON (wt.avaliation_id = aval.id)",
	    	"wt.id, wt.parent_id, wt.user_id, wt.classe_id, wt.status_id, wt.avaliation_id,
	    		wt.body, wt.datetime,
	    	u.login, u.name, u.surname, u.avatar,
	    	cl.name as classe, stat.name as status, aval.name as avaliation,
	    	(SELECT COUNT(id) FROM module_xwebtutoria WHERE parent_id = wt.id) as total_childs",
    		sprintf("%s = '%s'", $field, $value)
    	);
    	if (count($data) > 0) {
    		foreach ($data as &$item) {
	    		//$item = reset($data);
	    		$item['username'] = formatLogin(null, $item);

				try {
					$file = new MagesterFile($item['avatar']);
					list($item['avatar_width'], $item['avatar_height']) = eF_getNormalizedDims($file['path'], 50, 50);
				} catch (MagesterFileException $e) {
					$item['avatar'] = G_SYSTEMAVATARSPATH."unknown_small.png";
					$item['avatar_width'] = 50;
					$item['avatar_height'] = 50;
				}
    		}
    		return $data;
    	}
    	return false;
    }

}
