<?php
class module_xcontent extends MagesterExtendedModule {
	const XENTIFY_SEP = ';';
		
    // CORE MODULE FUNCTIONS
    public function getName() {
        return "XCONTENT";
    }
    public function getPermittedRoles() {
        return array("administrator", "professor", "student");
    }
    public function isLessonModule() {
        return true;
    }
    public function getTitle($action) {
    	switch($action) {
    		case "authorize_xcontent_schedule" : {
    			return "Agendamento de Conteúdo";
    		}
			case "new_schedule" : {
    			return __XCONTENT_NEW_SCHEDULE;
    		}
    		case "register_xcontent_schedule" : {
    			return __XCONTENT_SCHEDULE_REGISTER;
    		}
			case "edit_schedule_times" : {
				return __XCONTENT_EDIT_SCHEDULE;
			}
			case "view_scheduled" : {
				return __XCONTENT_VIEW_SCHEDULED;
			}
			case "view_scheduled_users" : {
				return __XCONTENT_VIEW_SCHEDULED_USERS;
			}
    		default : {
    			return parent::getTitle($action);
    		}
    	}
    }
    public function getUrl($action) {
    	switch($action) {
			case "edit_schedule_times" :
			case "view_scheduled_users" : {
				return $this->moduleBaseUrl . "&action=" . $action . "&xschedule_id=" . $_GET['xschedule_id'];
			}
    		default : {
    			return parent::getUrl($action);
    		}
    	}
    }
    public function getDefaultAction() {
    	//return "authorize_xcontent_schedule";
    	return "view_scheduled";
    }
	/*
	public function getNavigationLinks() {
		$this->showModuleBreadcrumbs = false;
		
		return parent::getNavigationLinks();
	}
    */
    
    /*
	public function addStylesheets() {
		return array("960gs/fluid/24columns");
	}
	*/
    /* BLOCK FUNCTIONS */
    public function loadContentAnalisysBlock($blockIndex = null, $blockInfo = null) {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
    	
    	// OPEN TEST RESULT
    	$testContentID = $blockInfo['unit_id'];
    	
    	try {
    		$userTest = new MagesterTest($testContentID);
    		
    		$recentUserTests = eF_getTableData(
    			"completed_tests JOIN tests ON tests_id = tests.id JOIN users ON completed_tests.users_LOGIN = users.login", 
    			"completed_tests.id, completed_tests.test, completed_tests.score, users.name as username, users.surname, completed_tests.tests_ID, tests.name, completed_tests.timestamp, completed_tests.users_LOGIN", 
    			"completed_tests.status != 'deleted' and completed_tests.users_LOGIN = '" . $this->getCurrentUser()->user['login'] . "' and completed_tests.tests_id = " . $userTest->test['id'], "timestamp DESC");
    		
    		
    		
    		if (count($recentUserTests) > 0) {
    			$userScore = is_null($recentUserTests[0]['score']) ? 0 : $recentUserTests[0]['score'];

    			// SHOW RESULT BASED ON USER SCORE
				$levels = array(
			   		1	=> array(
			   			'label'	=> 'Básico',
			   			'text'	=> 'Comunicar-se de forma simples em inglês, em situações previsíveis do dia-a-dia de trabalho ou numa viagem. Seu vocabulário é básico e permite que você fale sobre sim mesmo, sobre seu trabalho e dê detalhes sobre o que o rodeia.',
		   				'next_title'	=> 'O próximo nível do curso Idiompro é o Pré-Intermediário. Nele você será capaz de:',
			   			'next'	=> array(
			   				array(
			   					'title'	=> 'Em reunião:',
			   					'text'	=> 'Comunicar-se claramente, expressar opiniões, dar sugestões, apresentar e descrever outras pessoas, lidar com diferentes culturas em encontros profissionais ou viagens.'
			   				),
		   					array(
	   							'title'	=> 'Em entrevista:',
	   							'text'	=> 'Comunicar-se em ambientes formais, falar sobre si mesmo, sobre o que você gosta e não gosta, hobbies, carreira e desenvolvimento pessoal.'
		   					),
		   					array(
		   							'title'	=> 'Em comunicação escrita:',
		   							'text'	=> 'Escrever relatórios e e-mails simples.'
		   					)
			   			)
			   		),
					2	=> array(
			   			'label'	=> 'Pré-Intermediário',
						'text'	=> 'Compreender informações simples encontradas nas situações do dia-a-dia e manter uma conversa a respeito de assuntos do seu interesse. Consegue explorar uma grande variedade de linguagem simples com flexibilidade para expressar muito daquilo que quer transmitir. Consegue comunicar-se adequadamente em contextos profissionais de rotina.',
						'next_title'	=> 'O próximo nível do curso Idiompro é o Intermediário. Nesse nível você será capaz de:',
						'next'	=> array(
							array(
								'title'	=> 'Em reunião:',
								'text'	=> 'Comunicar-se claramente em várias situações de negócios incluindo transações financeiras e realizar apresentações de negócios com confiança.'
							),
							array(
								'title'	=> 'Em entrevista:',
								'text'	=> 'Comunicar-se claramente em entrevistas de emprego descrevendo suas atividades e qualidades e falando sobre si mesmo com mais detalhes e profundidade.'
							),
							array(
								'title'	=> 'Em comunicação escrita:',
								'text'	=> 'Escrever cartas formais e informais, inclusive reclamações, e preparar relatórios um pouco mais complexos e detalhados.'
							)
						)
							
			   		),
					3	=> array(
			   			'label'	=> 'Intermediário',
						'text'	=> 'Expressar claramente ideias e opiniões sobre uma ampla variedade de tópicos, além de compreender e trocar informações com segurança. Possui comando ativo dos aspectos essenciais da língua. Comunica-se com competência e independência em muitas situações profissionais e sociais.',
						'next_title'	=> 'O próximo nível do curso Idiompro é o Intermediário Superior. Nesse nível você será capaz de:',
						'next'	=> array(
							array(
								'title'	=> 'Em reunião:',
								'text'	=> 'Conduzir apresentações com gráficos e negociações com clareza e segurança, utilizar vocabulário relacionado às áreas de marketing, meio ambiente e tecnologia. Conduzir negócios em todo o mundo.'
							),
							array(
								'title'	=> 'Em entrevista:',
								'text'	=> 'Habilidades completas para falar sobre si mesmo e outras pessoas, seus hobbies, preferências e motivações.'
							),
							array(
								'title'	=> 'Em comunicação escrita:',
								'text'	=> 'Desenvolver relatórios contendo gráficos, pesquisas de mercado, apresentar os pros e contras e fazer comparações de dados.'
							)
						)
							
					),
					4	=> array(
			   			'label'	=> 'Intermediário Superior',
						'text'	=> 'Expressar suas opiniões com bastante clareza. Negociar e discutir com segurança. Possui comando suficiente da língua para conseguir adotar uma estrutura apropriada em várias circunstâncias diferentes. Avalia situações, identifica problemas e oferece soluções. Já consegue assumir um papel de liderança ao iniciar e conduzir uma conversa.',
						'next_title'	=> 'O próximo nível do curso Idiompro é o Avançado. Nesse nível você será capaz de:',
						'next'	=> array(
							array(
								'title'	=> 'Em reunião:',
								'text'	=> 'Comandar reuniões, mantendo o controle usando a linguagem específica do assunto com facilidade e fluência.'
							),
							array(
								'title'	=> 'Em entrevista:',
								'text'	=> 'Expressar-se com clareza e fluência, dominado com segurança uma ampla variedade de assuntos com elevado grau de exatidão.'
							),
							array(
								'title'	=> 'Em comunicação escrita:',
								'text'	=> 'Expressar-se com amplo repertório de negócios, com fornecedores e clientes, escrever planejamentos e comparativos de mercado.'
							)
						)
			   		),
					5	=> array(
			   			'label'	=> 'Superior',
						'text'	=> 'Expressar- se com clareza e fluência, dominando com segurança uma ampla variedade de linguagem, com elevado grau de exatidão. Lidar com informações complexas com segurança. Demonstrar um amplo repertório de vocabulário, estruturas e expressões coloquiais e idiomáticas. Comandar reuniões, mantendo o controle usando a linguagem específica do assunto com facilidade e fluência. Aproxima-se da competência de quem está falando sua língua-mãe.'
			   		)
				);
				
				//var_dump($recentUserTests[0]);

				if ($userScore <= 40) {
			   		$level = 1;
				} elseif ($userScore > 40 && $userScore <= 55) {
			   		$level = 2;
				} elseif ($userScore > 55 && $userScore <= 70) {
					$level = 3;
				} elseif ($userScore > 70 && $userScore <= 85) {
					$level = 4;
				} else {
					$level = 5;
				}
    		} else {
    			// Usuário ainda não fez o teste
    			
    		}
    		
    		$smarty -> assign("T_XCONTENT_USERLEVEL", $levels[$level]);
    		$smarty -> assign("T_XCONTENT_USERSCORE", $userScore);
    		    		
	    	
	    	$this->getParent()->appendTemplate(array(
		   		'title'			=> __XCONTENT_LEVEL . ":" . $levels[$level]['label'],
		   		'template'		=> $this->moduleBaseDir . 'templates/blocks/analisys_range.text.tpl',
		   		'contentclass'	=> 'blockContents'
	    	), $blockIndex);
	    	
	    	

	    	
	    	$this->injectJS("jquery/jquery-ui");
    	
	    	
	    	return true;    	
    	} catch (Exception $e) {
    		return false;    	
    	}
    }
    public function loadContentAnalisysRangeBlock($blockIndex = null, $blockInfo = null) {
    	$this->getParent()->appendTemplate(array(
	   		'title'			=> __XCONTENT_LEVELS,
	   		'template'		=> $this->moduleBaseDir . 'templates/blocks/analisys_range.table.tpl',
	   		'contentclass'	=> 'blockContents'
    	), $blockIndex);
    }
    public function loadContentScheduleListBlock($blockIndex = null, $blockInfo = null) {
    	// CHECK context (filter) LINK (polo, user_type, course, etc).
    	// OPEN TEST RESULT
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
    	
    	$xuserModule = $this->loadModule("xuser");

    	if (is_object($this->getCurrentLesson())) {
    		$currentLessonsID = array( $this->getCurrentLesson()->lesson['id'] );
    	} elseif (is_array($this->getCurrentLesson())) {
    		$currentLesson = $this->getCurrentLesson();	
    		$currentLessonsID = array( $currentLesson['id'] );
    	} else {
    		// GET USER LESSONS
    		$currentLessonsID = array_keys($currentUser->getLessons(false)); 
    	}
    	
    	if (count($currentLessonsID) == 0) {
    		return false;
    	}
    	
    	$userContentID = eF_getTableDataFlat("content", "id", sprintf("lessons_ID IN (%s)", implode(", ", $currentLessonsID)));
    	
    	$userCoursesID = eF_getTableDataFlat("users_to_courses", "courses_ID as course_id", sprintf("users_LOGIN = '%s'", $currentUser->user['login']));
    	
    	if (count($userCoursesID) == 0) {
    		$result = array();
    	} else {
	    	$result = eF_getTableData(
		    	"module_xcontent_schedule sch 
		    	LEFT JOIN module_xentify_scopes scop ON sch.xentify_scope_id = scop.id
	    			/*
		    	LEFT JOIN content cont ON schedl.content_id = cont.id
		    	LEFT JOIN lessons ON cont.lessons_ID = lessons.id
	    			*/
		    	LEFT OUTER JOIN module_xcontent_schedule_users user_schedl ON (sch.id = user_schedl.schedule_id)
		    	", 
		    	"sch.id, sch.xentify_scope_id, sch.xentify_id, user_schedl.index as selected_option, sch.block_html, sch.active",
		    	sprintf(
		    		"CURRENT_TIMESTAMP < sch.end 
		    		AND sch.active = 1
	    			AND sch.id IN (
		    			SELECT schedule_id FROM module_xcontent_schedule_contents sch_ct
		    			WHERE sch_ct.course_id IN (%s)
		    		)", implode(",", $userCoursesID['course_id'])
		    	)
		    );
    	}
    	//$userCourses = $currentUser->getUserCourses(array('return_objects' => false));
    	
	    $content_schedule_link = $this->moduleBaseUrl;
    	
    	if ($xuserModule->getExtendedTypeID($currentUser) == 'polo') {
// CHECK CURRENT USER POLO SCHEDULES
    		
    		foreach($result as $key => &$contentToSchedule) {
	    		// CHECK IF IS THE SAME scope
	    		
	    		if (!$this->isUserInScope($currentUser, $contentToSchedule['xentify_scope_id'], $contentToSchedule['xentify_id'])) {
					unset($result[$key]);
					continue;
				}
				$scheduleID = $contentToSchedule['id'];
	    	}
	    	
	    	$content_schedule_link = $this->moduleBaseUrl . "&action=view_scheduled_users&xschedule_id=" . $scheduleID;
	    	
	    	if (count($result) == 0) {
	    		return false;
	    	}
	    	
	    	$this->getParent()->appendTemplate(array(
	   			'title'			=> __XCONTENT_AUTHORIZE_SCHEDULE,
	   			'template'		=> $this->moduleBaseDir . 'templates/blocks/content.authorize.list.tpl',
	   			'contentclass'	=> 'blockContents'
    		), $blockIndex);
    		
    	} elseif ($currentUser->getType() == 'student') {
//    		var_dump($this->getUserScopeStatus($currentUser, '10', '8;14'));

    		//var_dump($result);
			foreach($result as $key => $contentToSchedule) {
				// CHECK IF IS THE SAME scope
				if (!$this->isUserInScope($currentUser, $contentToSchedule['xentify_scope_id'], $contentToSchedule['xentify_id'])) {
					unset($result[$key]);
					continue;
				} else {
					//var_dump($key);
				}
			}
			
			$content_schedule_link = $this->moduleBaseUrl . "&action=register_xcontent_schedule";
			
			if (count($result) == 0) {
				return false;
			}
			// IF HAS ACTIVE CONTENT SCHEDULE

			
	    	$this->getParent()->appendTemplate(array(
	   			'title'			=> __XCONTENT_AUTHORIZE_SCHEDULE,
	   			'template'		=> $this->moduleBaseDir . 'templates/blocks/content.schedule.list.tpl',
	   			'contentclass'	=> 'blockContents'
    		), $blockIndex);
	    	
	    	// IF NOT 
//	    	return false;
    	}
    	/*
    	foreach($result as &$item) {
    		$item['contents']	= $this->getContentsByScheduleId($item['id']);
    	}
    	
    	var_dump($result);
    	
    	

		exit;
		*/
		$smarty -> assign("T_XCONTENT_SCHEDULE_LINK", $content_schedule_link);
    	$this->assignSmartyModuleVariables();
		    /*	
    	$this->getParent()->appendTemplate(array(
	   		'title'			=> __XCONTENT_SCHEDULE,
	   		'template'		=> $this->moduleBaseDir . 'templates/blocks/content.schedule.list.tpl',
	   		'contentclass'	=> 'blockContents'
    	), $blockIndex);
*/
    	return true;    	
    }
    
    /* ACTIONS FUNCTIONS */
	public function viewScheduledAction() {
    	$smarty = $this->getSmartyVar();
    	$scopos = $this->getScopes();
    	
    	$currentUser = $this->getCurrentUser();
    	
    	// GET ALL SCHEDULE,AND GROUP BY SCOPE
    	$smarty -> assign("T_XCONTENT_SCOPES", $scopos);
    	
    	$xuserModule = $this->loadModule("xuser");
    	$smarty -> assign("T_CURRENT_USER", $currentUser);
    	
    	$smarty -> assign("T_EXTENDED_USERTYPE", $xuserModule->getExtendedTypeID($currentUser));
    	
    	$schedules = array();
    	foreach($scopos as $escopo) {
    		$schedules[$escopo['id']] = $this->getSchedules($escopo['id']);
    	}
    	foreach($schedules as $scope_id => &$scoped_schedule) {
    		foreach($scoped_schedule as $scoped_index => &$schedule) {
    			if (
    					$xuserModule->getExtendedTypeID($currentUser) != "administrator" && 
    					$currentUser->moduleAccess['xcontent'] != 'view' &&
    					$currentUser->moduleAccess['xcontent'] != 'change'
    			) {

					// MUST CHECK IF USER IN IS SCOPE
					if (!$this->isUserInScope($currentUser, $schedule['xentify_scope_id'], $schedule['xentify_id'])) {
						unset($scoped_schedule[$scoped_index]);
						continue;
					}
				}
				if (strtotime($schedule['end']) < time()) {
					unset($scoped_schedule[$scoped_index]);
					continue;
				}
				//CURRENT_TIMESTAMP < sch.end
				$scopeData = $this->getScopeEntifyValues(null, $schedule['xentify_scope_id'], $schedule['xentify_id']);
				
				//$scopeEntifyKeys = array_keys($scopeData);
				//$smarty -> assign("T_XCONTENT_SCOPE_FIELDS", $scopeEntifyKeys);
			
				$schedule = array_merge($schedule, $scopeData);
    		}
    	}
    	
    	//var_dump($currentUser);
    	
    	$smarty -> assign("T_XCONTENT_SCHEDULES", $schedules);
    	
    	return true;
    }
	public function newScheduleAction() {
	   	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
    	
    	$xuserModule = $this->loadModule("xuser");
    	
    	if (
			$xuserModule->getExtendedTypeID($currentUser) != "administrator" && 
			$currentUser->moduleAccess['xcontent'] != 'view' &&
			$currentUser->moduleAccess['xcontent'] != 'change'
   		) {
			header("Location: " . $this->moduleBaseUrl);
			exit;
		}
    	
    	// LOAD DATA FROM xentify Module
    	$scopeData = eF_getTableData("module_xentify_scopes", "id, name, description, rules", "active = 1");
    	$scopeCombo = array(-1	=> __SELECT_ONE_OPTION);
    	foreach($scopeData as $item) {
    		$scopeCombo[$item['id']] = $item['name']; 
    	}
    	
    	//$form = new HTML_QuickForm2("xcontent_new_schedule_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
    	$form = new HTML_QuickForm2("xcontent_new_schedule_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);
    	
		//$form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
		

		$form -> addElement('hidden', 'step_index');
		$form -> addSelect('scope_id', null, array('label'	=> __XCONTENT_SCOPE, 'options'	=> $scopeCombo));
		
		$coursesObjects =  MagesterCourse::getAllCourses(array('active' => 1));
		$lessonCombo = array(-1 => __SELECT_ONE_LESSON);
		
		foreach($coursesObjects as $course) {
			$lessons = $course->getCourseLessons(array('return_objects' => false));
			if (!is_array($lessonCombo[$course->course['name']]) && count($lessons) > 0) {
				$lessonCombo[$course->course['name']] = array();
			}
			
			foreach($lessons as $lesson) {
				$lessonCombo[$course->course['name']][$lesson['id']] = $lesson['name'];
			}
		}
		$form -> addSelect('lesson_id', null, array('label'	=> __XCONTENT_LESSON, 'options' => $lessonCombo));
		
		/** @todo Incluir registro de horários */
		$form -> addText('start_date', array('alt' => 'date', 'class'	=> 'no-button'), array('label'	=> __XCONTENT_START_DATE));
		//$form -> addElement('jquerytime', 'start_time', __XCONTENT_START_DATE, 'class = "no-button"');
		$form -> addText('end_date', array('alt' => 'date', 'class'	=> 'no-button'), array('label'	=> __XCONTENT_END_DATE));
		//$form -> addElement('jquerytime', 'end_time', __XCONTENT_START_DATE, 'class = "no-button"');
		
		$form -> addSubmit('submit_schedule', null, array('label'	=> __XCONTENT_SUBMIT));

		$scopeFields = array();
		
		$values = $form->getValue();
		
		
		
		if (is_numeric($values['scope_id']) && $values['scope_id'] > 0) {
			// MAKE OPTIONS FOR SELECTED SCOPE
			$scopeFields = $this->makeScopeFormOptions($values['scope_id'], $form);
			$values = $form->getValue();
			
			$smarty -> assign("T_XCONTENT_SCOPE_FIELDS", $scopeFields);
					
			if ($form -> isSubmitted() && $form -> validate()) {
				$xentifyValues = array();
				foreach($scopeFields as $field_name) {
					$xentifyValues[] = $values[$field_name];
				}
		
				$start_date = date_create_from_format("d/m/Y", $values['start_date']);
				$end_date = date_create_from_format("d/m/Y", $values['end_date']);

				$insertValues = array(
					'xentify_scope_id'	=> $values['scope_id'],
					'xentify_id'		=> implode(self::XENTIFY_SEP, $xentifyValues)
				);
				if (is_object($start_date)) {
					$insertValues['start'] = $start_date->format("Y-m-d");
				}
				if (is_object($start_date)) {
					$insertValues['end'] = $end_date->format("Y-m-d");
				}
				//var_dump($insertValues);
				$xScheduleID = eF_insertTableData("module_xcontent_schedule", $insertValues);
				
				// INSERE VALORES E REDIRECIONA PARA
				header(sprintf("Location: " . $this->moduleBaseUrl . "&action=edit_schedule_times&xschedule_id=%s", $xScheduleID));
				exit;
			}

		} else {
			$values = array(
				'step_index'	=> 1,
				'scope_id'		=> -1
			);
		}
		

		//$form->setDefaults($values);
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));
		// Set defaults for the form elements
		
		
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');

		
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_XCONTENT_NEW_SCHEDULE_FORM', $renderer -> toArray());

		return true;
    }
    public function editScheduleTimesAction() {
        $smarty = $this -> getSmartyVar();
        $currentUser = $this->getCurrentUser();
    	$xuserModule = $this->loadModule("xuser");
/*    	var_dump($currentUser->user);
    	var_dump($xuserModule->getExtendedTypeID($currentUser));
    	exit;
*/
		if (
			$xuserModule->getExtendedTypeID($currentUser) != "administrator" && 
			$currentUser->moduleAccess['xcontent'] != 'view' &&
			$currentUser->moduleAccess['xcontent'] != 'change'
   		) {
			header("Location: " . $this->moduleBaseUrl);
			exit;
		}
        // LOAD SCHEDULES FOR CLASS
        if (!eF_checkParameter($_GET['xschedule_id'], 'id')) {
        	header("Location: " . $this->moduleBaseUrl);
			exit;
        	
        }
        $scheduleID = $_GET['xschedule_id'];
        
        $smarty -> assign("T_XCONTENT_SCHEDULE_ID", $scheduleID);
/*        
        list($scheduleData) = eF_getTableData(
        	"module_xcontent_schedule sch 
        	LEFT JOIN module_xentify_scopes scp ON (sch.xentify_scope_id = scp.id)
        	LEFT JOIN content ct ON (sch.content_id = ct.id)
        	LEFT JOIN lessons l ON (ct.lessons_ID = l .id)", 
        	"sch.id, sch.content_id, ct.name as content, l.name as lesson, sch.xentify_scope_id, scp.name as scope, sch.xentify_id, sch.start, sch.end, sch.block_html, sch.active",
        	sprintf("sch.id = '%s'", $scheduleID)
        );
*/
        list($scheduleData) = eF_getTableData(
        	"module_xcontent_schedule sch 
        	LEFT JOIN module_xentify_scopes scp ON (sch.xentify_scope_id = scp.id)",
        	"sch.id, sch.xentify_scope_id, scp.name as scope, sch.xentify_id, sch.start, sch.end, sch.block_html, sch.active",
        	sprintf("sch.id = '%s'", $scheduleID)
        );
        
        $scheduleContents = $scheduleData['contents']	= $this->getContentsByScheduleId($scheduleData['id']);
        
        // GROUP BY COURSE ID, THEN LESSON ID
        $courseGrouped = $lessonForGroup = $scheduleGrouped = array();
        foreach($scheduleContents as $schedule) {
        	if (!array_key_exists($schedule['course_id'], $scheduleGrouped)) {
        		$scheduleGrouped[$schedule['course_id']] = array();
        		$courseGrouped[$schedule['course_id']] = $schedule['course'];
        	}
        	if (!array_key_exists($schedule['lesson_id'], $scheduleGrouped[$schedule['course_id']])) {
        		$scheduleGrouped[$schedule['course_id']][$schedule['lesson_id']] = array();
        		$lessonForGroup[$schedule['lesson_id']] = $schedule['lesson'];
        	}
        	$scheduleGrouped[$schedule['course_id']][$schedule['lesson_id']][] = $schedule;
        }

        $smarty -> assign("T_XCONTENT_COURSES", $courseGrouped);
        $smarty -> assign("T_XCONTENT_LESSONS", $lessonForGroup);

        $scheduleData['grouped_content'] = $scheduleGrouped;

		$scopeData = $this->getScopeEntifyValues(null, $scheduleData['xentify_scope_id'], $scheduleData['xentify_id']);
		$scopeEntifyKeys = array_keys($scopeData);
		$smarty -> assign("T_XCONTENT_SCOPE_FIELDS", $scopeEntifyKeys);
		
		$scheduleData = array_merge($scheduleData, $scopeData);
		
		$smarty -> assign("T_XCONTENT_SCHEDULE", $scheduleData);
		
		$scheduleTimesData = eF_getTableData(
			"module_xcontent_schedule_itens",
			"schedule_id, `index`, start, end, active",
			sprintf("schedule_id = %d", $scheduleID),
			"`index` ASC"
		);
		
		$smarty -> assign("T_XCONTENT_SCHEDULE_TIMES", $scheduleTimesData);

		return true;
    }
    public function appendNewContentToScheduleAction() {
    	$smarty = $this->getSmartyVar();
    	
    	// CREATING NEW CONTENT FORM
		$form = new HTML_QuickForm2("xcontent_new_schedule_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);
		
		$form -> addElement('hidden', 'schedule_id');
		$form -> addElement('hidden', 'content_id');
		
		$coursesObjects =  MagesterCourse::getAllCourses(array('active' => 1));
		$lessonCombo = array(-1 => __SELECT_ONE_LESSON);
		
		foreach($coursesObjects as $course) {
			$lessons = $course->getCourseLessons(array('return_objects' => false));
			if (!is_array($lessonCombo[$course->course['name']]) && count($lessons) > 0) {
				$lessonCombo[$course->course['name']] = array();
			}
			
			foreach($lessons as $lesson) {
				$lessonCombo[$course->course['name']][$course->course['id'] . "_" . $lesson['id']] = $lesson['name'];
			}
		}
		$form -> addSelect('lesson_id', null, array('label'	=> __XCONTENT_LESSON, 'options' => $lessonCombo));
		
		$form -> addSubmit('submit_schedule', null, array('label'	=> __XCONTENT_SUBMIT));
		
		$form -> addCheckbox('required', null, array('label'	=> __XCONTENT_REQUIRED));
		
		$values = array(
			'schedule_id'	=> $_GET['xschedule_id'],
		);
		
        $form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));
		// Set defaults for the form elements
		
		if ($form -> isSubmitted() && $form -> validate()) {
			$values = $form->getValue();
			
			list($course_id, $lesson_id) = explode("_", $values['lesson_id']);
			
			is_null($values['required']) ? $values['required'] = 0 : null;
			
			$insertData = array(
				'schedule_id'	=> $values['schedule_id'],	
				'course_id'		=> $course_id, 
				'content_id' 	=> $values['content_id'],
				'required'		=> $values['required']
			);
			
			ef_insertTableData(
				"module_xcontent_schedule_contents",
				$insertData
			);
			
			header("Location: " . $this->moduleBaseUrl . "&action=edit_schedule_times&xschedule_id=" . $insertData['schedule_id']);
			exit;
		}
		
		
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_XCONTENT_SELECT_FORM', $renderer -> toArray());
		
		return true;
    }
    public function fetchContentTreeAction() {
    	$lesson_id = $_POST['lesson_id'];
    	
    	$contentTree = new MagesterContentTree($lesson_id);
		$contentHTML = $contentTree->toHTML(
			false,						// $iterator = false, 
			"xcontent_content_tree",	// $treeId = false, 
			array(						// $options = array(), 
				'hideFeedback'	=> true,
				'onclick'		=> '',
				'selectedNode'	=> $currentContentId,
				//'custom'		=> ''
				'tree_root'		=> false,
				'selectedNode'	=> false,
				'drag'			=> false,
				'expand'		=> false,
				'show_hide'		=> false,
				'noclick'		=> true
			)
			// $scormState = array()
		);
		
		echo $contentHTML;
		exit;
    }
    public function saveScheduleTimeAction() {
		if (eF_checkParameter($_POST['xschedule_id'], 'id')) {
			
			$schedule_id = $_POST['xschedule_id'];
			$newData = array(
				'index'	=> $_POST['index'],
				'date' 	=> $_POST['date'],
				'start'	=> $_POST['start'],
				'end' 	=> $_POST['end']
			);
			
			$date = date_create_from_format("d/m/Y", $newData['date']);
			if ($date === FALSE) {
				//return message
				echo json_encode(array(
					'message'		=> __XCONTENT_ERROR_INVALID_DATE,
					'message_type'	=> 'error'
				));
				exit;
			}
			$start = strtotime($date->format("Y-m-d") . " " . $newData['start']);
			$end = strtotime($date->format("Y-m-d") . " " . $newData['end']);
			
			if (empty($_POST['start']) || $start === FALSE) {
				//return message
				echo json_encode(array(
					'message'		=> __XCONTENT_ERROR_INVALID_START_TIME,
					'message_type'	=> 'error'
				));
				exit;
			}
			if (empty($_POST['end']) || $end === FALSE) {
				//return message
				echo json_encode(array(
					'message'		=> __XCONTENT_ERROR_INVALID_END_TIME,
					'message_type'	=> 'error'
				));
				exit;
			}
			
			if ($start > $end) {
				//return message
				echo json_encode(array(
					'message'		=> __XCONTENT_ERROR_START_TIME_GREATER_THAN_END_TIME,
					'message_type'	=> 'error'
				));
				exit;				
			}
			
			if ($newData['index'] < 1) {
				// INSERT
				list($indexData) = eF_getTableData("module_xcontent_schedule_itens", "MAX(`index`) + 1 as newIndex", "schedule_id = " . $schedule_id);
				
				if (is_null($indexData['newIndex'])) {
					$index = 1;
				} else {
					$index = $indexData['newIndex'];
				}
				
				$insertData = array(
					'schedule_id' 	=> $schedule_id,
					'index'			=> $index, // CALC
					'start'			=> date("Y-m-d H:i:s", $start), // CALC
					'end'	 		=> date("Y-m-d H:i:s", $end),
					'active'		=> 1
				);
				
				eF_insertTableData("module_xcontent_schedule_itens", $insertData);
			} else {
				// UPDATE
				$index = $newData['index'];
				
				$insertData = array(
					'schedule_id' 	=> $schedule_id,
					'index'			=> $index, // CALC
					'start'			=> date("Y-m-d H:i:s", $start), // CALC
					'end'	 		=> date("Y-m-d H:i:s", $end),
					'active'		=> 1
				);
				
				$updateData = array(
					'start'			=> $insertData['start'], // CALC
					'end'	 		=> $insertData['end']
				);
				eF_updateTableData("module_xcontent_schedule_itens", $updateData, sprintf("schedule_id = %d AND `index` = %d", $schedule_id, $index));
			}
			$insertData['success'] = true;
			echo json_encode(
				array_merge(
					$insertData,
					array(
						'message'		=> __XCONTENT_SUCCESS_SCHEDULE_SAVED,
						'message_type'	=> 'success'
					)
				)
			);
			
		} else {
				//return message
			echo json_encode(array(
				'message'		=> __XCONTENT_ERROR_SCHEDULE_NOT_FOUND,
				'message_type'	=> 'error'
			));
			exit;
		}
		exit;
    }
    public function deleteScheduleTimeAction() {
		if (eF_checkParameter($_POST['xschedule_id'], 'id') && eF_checkParameter($_POST['index'], 'id')) {
			
			$schedule_id 	= $_POST['xschedule_id'];
			$index 			= $_POST['index'];

			list($insertData) = eF_getTableData("module_xcontent_schedule_itens", "*", sprintf("schedule_id = %d AND `index` = %d", $schedule_id, $index));
			
			
			$insertData['success'] = (bool)eF_deleteTableData("module_xcontent_schedule_itens", sprintf("schedule_id = %d AND `index` = %d", $schedule_id, $index));
			echo json_encode(
				array_merge(
					$insertData,
					array(
						'message'		=> __XCONTENT_SUCCESS_SCHEDULE_DELETED,
						'message_type'	=> 'success'
					)
				)
			);
		} else {
				//return message
			echo json_encode(array(
				'message'		=> __XCONTENT_ERROR_SCHEDULE_NOT_FOUND,
				'message_type'	=> 'error'
			));
			exit;
		}
		exit;
    }
    public function deleteScheduleContentAction() {
		if (is_numeric($_POST['schedule_id']) && is_numeric($_POST['course_id']) && is_numeric($_POST['content_id'])) {
			
			$schedule_id 	= $_POST['schedule_id'];
			$course_id 		= $_POST['course_id'];
			$content_id 	= $_POST['content_id'];
			
			list($insertData) = eF_getTableData(
				"module_xcontent_schedule_contents", 
				"*", 
				sprintf("schedule_id = %d AND course_id = %d AND content_id = %d", $schedule_id, $course_id, $content_id)
			);
			
			
			$insertData['success'] = (bool)eF_deleteTableData("module_xcontent_schedule_contents", sprintf("schedule_id = %d AND course_id = %d AND content_id = %d", $schedule_id, $course_id, $content_id));
			$insertData['success'] = true;
			echo json_encode(
				array_merge(
					$insertData,
					array(
						'message'		=> __XCONTENT_SUCCESS_SCHEDULE_CONTENT_DELETED,
						'message_type'	=> 'success'
					)
				)
			);
		} else {
				//return message
			echo json_encode(array(
				'message'		=> __XCONTENT_ERROR_SCHEDULE_NOT_FOUND,
				'message_type'	=> 'error'
			));
			exit;
		}
		exit;
    }
	public function viewScheduledUsersAction() {
		$smarty = $this -> getSmartyVar();
		$currentUser = $this->getCurrentUser();
		$xuserModule = $this->loadModule("xuser");
		
        // LOAD SCHEDULES FOR CLASS
        if (!eF_checkParameter($_GET['xschedule_id'], 'id')) {
        	return false;
        }
        $scheduleID = $_GET['xschedule_id'];
        
        $smarty -> assign("T_XCONTENT_SCHEDULE_ID", $scheduleID);
/*        
        list($scheduleData) = eF_getTableData(
        	"module_xcontent_schedule sch 
        	LEFT JOIN module_xentify_scopes scp ON (sch.xentify_scope_id = scp.id)
        	LEFT JOIN content ct ON (sch.content_id = ct.id)
        	LEFT JOIN lessons l ON (ct.lessons_ID = l .id)", 
        	"sch.id, sch.content_id, ct.name as content, l.name as lesson, sch.xentify_scope_id, scp.name as scope, sch.xentify_id, sch.start, sch.end, sch.block_html, sch.active",
        	sprintf("sch.id = '%s'", $scheduleID)
        );
*/
        list($scheduleData) = eF_getTableData(
        	"module_xcontent_schedule sch 
        	LEFT JOIN module_xentify_scopes scp ON (sch.xentify_scope_id = scp.id)",
        	"sch.id, sch.xentify_scope_id, scp.name as scope, sch.xentify_id, sch.start, sch.end, sch.block_html, sch.active",
        	sprintf("sch.id = '%s'", $scheduleID)
        );
        
        $scheduleData['contents']	= $this->getContentsByScheduleId($scheduleData['id']);
        
        if (
        	$xuserModule->getExtendedTypeID($currentUser) != "administrator" &&
       		$currentUser->moduleAccess['xcontent'] != 'view' &&
       		$currentUser->moduleAccess['xcontent'] != 'change'
        ) {        
			if (!$this->isUserInScope($currentUser, $scheduleData['xentify_scope_id'], $scheduleData['xentify_id'])) {
				header("Location: " . $this->moduleBaseUrl);
				exit;
			}
		}

		$scopeData = $this->getScopeEntifyValues(null, $scheduleData['xentify_scope_id'], $scheduleData['xentify_id']);
		$scopeEntifyKeys = array_keys($scopeData);
		$smarty -> assign("T_XCONTENT_SCOPE_FIELDS", $scopeEntifyKeys);
		
		$scheduleData = array_merge($scheduleData, $scopeData);
		
		$smarty -> assign("T_XCONTENT_SCHEDULE", $scheduleData);
		
		$scheduleTimesUsersData = eF_getTableData(
			"module_xcontent_schedule_users sch_u
			LEFT JOIN module_xcontent_schedule_itens it ON (it.schedule_id = sch_u.schedule_id AND it.`index` = sch_u.`index`)
			LEFT JOIN module_xcontent_schedule_contents sch_c ON (sch_u.schedule_id = sch_c.schedule_id AND sch_u.content_id = sch_c.content_id)
			LEFT JOIN users u ON (sch_u.user_id = u.id) 
			LEFT JOIN content c ON (sch_u.content_id = c.id)
			LEFT JOIN courses co ON (sch_c.course_id = co.id)
			LEFT JOIN lessons l ON (c.lessons_ID = l.id)",
			"sch_u.schedule_id, u.id as user_id, u.name, u.surname, sch_c.course_id, co.name as course, l.id as lesson_id, l.name as lesson, sch_u.content_id, c.name as content, u.login, sch_u.`index`, it.start, it.end, sch_u.liberation, it.active",
			sprintf("sch_u.schedule_id = %d", $scheduleID),
			"start ASC, course_id ASC, content_id ASC"
		);
		
		$coursesID = eF_getTableDataFlat(
			"module_xcontent_schedule_contents", 
			"course_id",
			sprintf("schedule_id = %d", $scheduleID)
		);
		
		var_dump();
		
		// GET ALL USERS ON SCOPE AND EXCLUDE ALL ALREADY SCHEDULED.
		$scopedUsers = $this->getUsersByScopeId($scheduleData['xentify_scope_id'], $scheduleData['xentify_id']);
		
		//var_dump($scopedUsers);
		// INJECT USER IN $scheduleTimesUsersData structure
		
		foreach($scopedUsers as $scopedKey => $scopedItem) {
			$found = false;
			foreach($scheduleTimesUsersData as $scheduleTime) {
				if ($scopedItem['id'] == $scheduleTime['user_id']) {
					unset($scopedUsers[$scopedKey]);
					$found = true;
				}
			}
			if ($found) {
				continue;
			}
			
			if ($xuserModule->getExtendedTypeID(MagesterUserFactory :: factory($scopedItem['login'])) != 'student') {
				continue;
			}
			
			$userDataTemplate = array(
				"schedule_id"	=> $scheduleID,
			    "user_id"		=> $scopedItem['id'],
			    "name"			=> $scopedItem['name'],
			    "surname"		=> $scopedItem['surname'],
			    "login"			=> $scopedItem['login'],
			    "index"			=> 0,
			    "start"			=> 0,
			    "end"			=> null,
			    "active"		=> 1
		    );
		    
		    // FILTER COURSES BY SCHEDULE CONTENT IDs
			// GET USER COURSE
			$userCourseClasseData = ef_getTableData(
				"users_to_courses uc LEFT JOIN courses c ON (uc.courses_ID = c.id) LEFT JOIN classes cl ON (uc.classe_id = cl.id)",
				"c.id as course_id, c.name as course_name, uc.classe_id, cl.name as classe_name",
				sprintf("uc.users_LOGIN = '%s' AND c.id IN (%s)", $scopedItem['login'], implode(", ", $coursesID['course_id']))
			);
		    
		    if (count($userCourseClasseData) == 0) {
		    	$noScheduledUser = array_merge(
		    		$userDataTemplate,
		    		array(
					    "course_id"		=> $course_id,
					    "course"		=> $course_name,
					    "lesson_id"		=> 0,
					    "lesson"		=> __XCONTENT_NO_LESSON_DEFINED,
					    "classe_id"		=> $classe_id,
					    "classe"		=> $classe_name,
					    "content_id"	=> 0,
					    "content"		=> __XCONTENT_NO_CONTENT_DEFINED,
					)
				);
			} else {
				foreach($userCourseClasseData as $userClasseRel) {
					$noScheduledUser = array_merge(
			    		$userDataTemplate,
			    		array(
						    "course_id"		=> $userClasseRel['course_id'],
						    "course"		=> $userClasseRel['course_name'],
						    "lesson_id"		=> 0,
						    "lesson"		=> __XCONTENT_NO_LESSON_DEFINED,
						    "classe_id"		=> $userClasseRel['classe_id'],
						    "classe"		=> $userClasseRel['classe_name'],
						    "content_id"	=> 0,
						    "content"		=> __XCONTENT_NO_CONTENT_DEFINED,
						)
					);
					$noScheduleTimesUsersData[] = $noScheduledUser;
				}
			}
		}
		
		// GROUP BY start
		foreach($scheduleTimesUsersData as $scheduleTime) {
			if (!is_array($scheduleTimesUsers[$scheduleTime['start']])) {
				$scheduleTimesUsers[$scheduleTime['start']] = array();
			}
			$scheduleTimesUsers[$scheduleTime['start']][] = $scheduleTime;
		}
		// GROUP BY start
		foreach($noScheduleTimesUsersData as $scheduleTime) {
			if (!is_array($noScheduleTimesUsers[$scheduleTime['start']])) {
				$noScheduleTimesUsers[$scheduleTime['start']] = array();
			}
			$noScheduleTimesUsers[$scheduleTime['start']][] = $scheduleTime;
		}
		
		$smarty -> assign("T_XCONTENT_SCHEDULE_TIME_USERS", $scheduleTimesUsers);
		$smarty -> assign("T_XCONTENT_NOSCHEDULE_TIME_USERS", $noScheduleTimesUsers);
		
		return true;
    }
    
    public function registerXcontentScheduleAction() {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
    	
    	$xuserModule = $this->loadModule("xuser");
    	
    	
    	if ($xuserModule->getExtendedTypeID($currentUser) == 'polo') {
    	} elseif ($currentUser->getType() == 'student') {
    	
    	//if (in_array($currentUser -> user['id'], array(1289, 47, 48))) {
    		 if (array_key_exists('xcontent_schedule_item', $_POST)) {
	    		$userSchedules = $_POST['xcontent_schedule_item'];
	    		
	    		$schedulesToInsert = array();
	    		foreach($userSchedules as $schedule_id => $schedule_index) {
	    			$schedulesToInsert[] = array(
						'schedule_id' 	=> $schedule_id,
						'user_id'		=> $currentUser->user['id'],
						'`index`'		=> $schedule_index
	    			);
	    			eF_deleteTableData(
	    				"module_xcontent_schedule_users",
	    				sprintf(
	    					"schedule_id = %d AND user_id = %d", 
	    					$schedule_id, 
	    					$currentUser->user['id']
	    				)
	    			);
	    			// INSERIR EVENTO NA AGENDA
	    		}
	    		eF_insertTableDataMultiple("module_xcontent_schedule_users", $schedulesToInsert);
	    		
	    		$this->setMessageVar(__XCONTENT_SUCCESS_SCHEDULE_REGISTERED, "success");
	    	}
    		
    		//$currentUser = $this->getCurrentUser();
    		
    		if ($this->getCurrentLesson()) {
    			$currentLessonsID = array( $this->getCurrentLesson()->lesson['id'] );
    		} else {
    			// GET USER LESSONS
    			$currentLessonsID = array_keys($currentUser->getLessons(false, 'student')); 
    		}
    		
    		$userCoursesID = eF_getTableDataFlat("users_to_courses", "courses_ID as course_id", sprintf("users_LOGIN = '%s'", $currentUser->user['login']));
    		
    		$result = eF_getTableData(
    				"module_xcontent_schedule sch
    				LEFT JOIN module_xentify_scopes scop ON (sch.xentify_scope_id = scop.id)
    				LEFT JOIN module_xcontent_schedule_itens cont_item ON (sch.id = cont_item.schedule_id)",
    				"sch.id, sch.xentify_scope_id, sch.xentify_id, sch.start, sch.end, sch.block_html, sch.active, COUNT(cont_item.index)",
    				sprintf(
    						"/*CURRENT_TIMESTAMP BETWEEN schedl.start AND schedl.end
    						AND */sch.active = 1
    						AND sch.id IN (
    						SELECT schedule_id FROM module_xcontent_schedule_contents sch_ct
    						WHERE sch_ct.course_id IN (%s)
    				)", implode(",", $userCoursesID['course_id'])
    				), "", "sch.id, sch.xentify_scope_id, sch.xentify_id, sch.start, sch.end, sch.block_html, sch.active HAVING COUNT(cont_item.index) > 0"
    		);    		

    		/*
    		
    		$userContentID = eF_getTableDataFlat("content", "id", sprintf("lessons_ID IN (%s)", implode(", ", $currentLessonsID)));
    		
    		$result = eF_getTableData(
	    		"module_xcontent_schedule schedl 
	    		LEFT JOIN module_xentify_scopes scop ON schedl.xentify_scope_id = scop.id
	    		LEFT JOIN content cont ON schedl.content_id = cont.id
	    		LEFT JOIN lessons ON cont.lessons_ID = lessons.id
	    		LEFT OUTER JOIN module_xcontent_schedule_users user_schedl ON (schedl.id = user_schedl.schedule_id)
	    		", 
	    		"schedl.id, schedl.content_id, schedl.xentify_scope_id, schedl.xentify_id, user_schedl.index as selected_option, schedl.block_html, cont.name, lessons.name as lesson_name", 
	    		sprintf(
	    			"CURRENT_TIMESTAMP BETWEEN schedl.start AND schedl.end 
	    			AND schedl.active = 1 
	    			AND schedl.content_id IN (%s)", implode(",", $userContentID['id'])
	    		)
	    	);
	    	*/


    		//var_dump($result);
	    	foreach($result as $key => &$contentToSchedule) {
	    		// CHECK IF IS THE SAME scope
	    		if (!$this->isUserInScope($currentUser, $contentToSchedule['xentify_scope_id'], $contentToSchedule['xentify_id'])) {
	    			unset($result[$key]);
	    			continue;
	    		} else {
	    			if (strtotime($contentToSchedule['end']) < time()) {
	    				unset($result[$key]);
	    				continue;
	    			}
	    			
	    			//var_dump($contentToSchedule);
	    			
	    			// CHECK IF USER ALREADY SCHEDULED THIS CONTENT
	    			$contentSchedules = 
		    			eF_getTableData(
		    				"module_xcontent_schedule_itens schedl", 
		    				"schedl.*, DAYOFWEEK(schedl.start) as week_day", 
		    				sprintf("schedl.schedule_id = %d", $contentToSchedule['id'])
		    			);
	    			
	    			$scheduleContents = $contentToSchedule['contents']	= $this->getContentsByScheduleId(
	    				$contentToSchedule['id'],
    					array('courses_id' => $userCoursesID['course_id'])
	    			);
	    			
	    			//var_dump($scheduleContents);
	    			//exit;
	    			// GROUP BY COURSE ID, THEN LESSON ID
	    			$courseGrouped = $lessonForGroup = $scheduleGrouped = array();
	    			foreach($scheduleContents as $schedule) {
	    				if (!array_key_exists($schedule['course_id'], $scheduleGrouped)) {
	    					$scheduleGrouped[$schedule['course_id']] = array();
	    					$courseGrouped[$schedule['course_id']] = $schedule['course'];
	    				}
	    				if (!array_key_exists($schedule['lesson_id'], $scheduleGrouped[$schedule['course_id']])) {
	    					$scheduleGrouped[$schedule['course_id']][$schedule['lesson_id']] = array();
	    					$lessonForGroup[$schedule['lesson_id']] = $schedule['lesson'];
	    				}
	    				$scheduleGrouped[$schedule['course_id']][$schedule['lesson_id']][] = $schedule;
	    			}
	    			
	    			$smarty -> assign("T_XCONTENT_COURSES", $courseGrouped);
	    			$smarty -> assign("T_XCONTENT_LESSONS", $lessonForGroup);
	    			
	    			$contentToSchedule['grouped_content'] = $scheduleGrouped;	    			
	    			
	    			
		    			
		    		// GROUP BY WEEK DAY
		    		$schedules = array();  
		    		foreach($contentSchedules as $scheduleItem) {
		    			if (!is_array($schedules[$scheduleItem['week_day']])) {
		    				$schedules[$scheduleItem['week_day']] = array();
		    			}
		    			$schedules[$scheduleItem['week_day']][] = $scheduleItem;
		    		} 
		    		
		    		$contentToSchedule['schedules'] = $schedules;
		    	
	    		}
	    	}
	    	/*
	    	echo '<pre>';
	    	var_dump($result);
	    	echo '</pre>';
	    	exit;
	    	*/
	    	
	    	
	    	// CHECK FOR POSTED VARS

	    	//if (count($result))
	    	/*
	    	BUSCAR POLO DO ALUNO
	    		- CHECAR SE EXISTE ALGUM AGENDAMENTO PENDENTE
	    		- SE SIM, VERIFICAR SE É NO MESMO ( CURSO, DISCIPLINA E TURMA) DO ALUNO
	    		- SE SIM MOSTRAR AGENDA COM O PERIODO SELECIONADO, COM AS OPÇÕES PARA MARCAR.
	    		
	    	MOSTRAR AGENDA BASEADO EM: 
	    		- Polo do aluno
	    	*/	
	    	
	    	
	    	$userPolo = $currentUser->getUserPolo(array('return_objects'	=> false));

	    	$smarty -> assign("T_XCONTENT_SCHEDULE_ITEM", __XCONTENT_SCHEDULE_REGISTER);
	    	$smarty -> assign("T_XCONTENT_SCHEDULE_CONTENTS", $result);
	    	$smarty -> assign("T_XCONTENT_USERPOLO", $userPolo);
	    	
	    	
	    	if (count($result) == 0) {
	    		return false;
	    	}
	    	
	    	$week_days = array(
	    		1	=> 'Domingo',
	    		2	=> 'Segunda-feira',
	    		3	=> 'Terça-feira',
	    		4	=> 'Quarta-feira',
	    		5	=> 'Quinta-feira',
	    		6	=> 'Sexta-feira',
	    		7	=> 'Sábado'
	    	);

	    	$smarty -> assign("T_XCONTENT_WEEKNAMES", $week_days);
	    	
	    	//$this->injectJS("jquery/jquery.weekcalendar");
	    	//$this->injectCSS("jquery/jquery.weekcalendar");
	    	
	    	return true;
    	//} else {
//    		return false;
//    	}
    	} elseif ($currentUser->getType() == 'professor') {
    	}
    	return false;
    }
    public function selectContentScheduleTimeAction() {
    	$smarty = $this->getSmartyVar();
    	
    	
    	if (!eF_checkParameter($_GET['xschedule_id'], 'id')) {
    		header("Location : " . $this->moduleBaseUrl . "&action=register_xcontent_schedule");
    		exit;
    	}
    	$schedule_id = $_GET['xschedule_id'];
    	
    	if (!eF_checkParameter($_GET['xcontent_id'], 'id')) {
    		header("Location : " . $this->moduleBaseUrl . "&action=register_xcontent_schedule");
    		exit;
    	}
    	$content_id	= $_GET['xcontent_id'];
    	
    	if (count($_POST) > 0) {
    		// FORM IS SUBMITTED
    		list($totalData) = eF_countTableData(
    			"module_xcontent_schedule_users", 
    			"schedule_id as total",
    			sprintf(
    				"schedule_id = %d AND content_id = %d AND user_id = %s",
    				$schedule_id, $content_id, $this->getCurrentUser()->user['id']
    			)
	   		);
	   		
	   		if ($totalData['count'] > 0) {
	   			$updateData = array(
		    		'`index`'	=> $_POST['xcontent_schedule_item']
	   			);    		
	   			$result = eF_updateTableData(
	   				"module_xcontent_schedule_users", 
	   				$updateData, 
	   				sprintf(
    					"schedule_id = %d AND content_id = %d AND user_id = %s",
    					$schedule_id, $content_id, $this->getCurrentUser()->user['id']
    				)
    			);
	   		} else {
	    		$insertData = array(
		    		'schedule_id'	=> $_POST['xschedule_id'],
		    		'content_id' 	=> $_POST['xcontent_id'],
		    		'user_id' 		=> $this->getCurrentUser()->user['id'],
		    		'`index`'		=> $_POST['xcontent_schedule_item'],
	    			'liberation'	=> 0
	    		);
	    		$result = eF_insertTableData("module_xcontent_schedule_users", $insertData);
	   		}
	   		
	   		if ($result) {
	   			header("Location: " . $this->moduleBaseUrl . "&action=register_xcontent_schedule" .
	   				"&message=" . urlencode(__XCONTENT_SUCCESS_SCHEDULE_REGISTERED) .
	   				"&message_type=success"
	   			);
	   			exit;
	   		} else {
	   			$this->setMessageVar(__XCONTENT_ERROR_SCHEDULE_REGISTERED, "failure");
	   		}
    	}
    	
   		list($selectedOption) = eF_getTableData(
   			"module_xcontent_schedule_users", 
   			"`index`",
   			sprintf(
   				"schedule_id = %d AND content_id = %d AND user_id = %s",
   				$schedule_id, $content_id, $this->getCurrentUser()->user['id']
  			)
  		);
    	
    	$smarty -> assign("T_XCONTENT_SCHEDULE_ID", $schedule_id);
    	$smarty -> assign("T_XCONTENT_CONTENT_ID", $content_id);
    	$smarty -> assign("T_XCONTENT_SELECTED_OPTION", $selectedOption['index']);
    	
    	
		list($contentData) = $this->getContentsByScheduleId(
			$schedule_id,
			array('contents_id' => array($content_id))
		);
		
		///var_dump($contentData);
    	
    	$smarty -> assign("T_XCONTENT_CONTENT", $contentData);
    	/*
    	$contentToSchedule['contents']	= $this->getContentsByScheduleId(
    		$contentToSchedule['id'],
    		array('courses_id' => $userCoursesID['course_id'])
    	);
    	*/
    	
    	$contentSchedules = 
    		eF_getTableData(
    			"module_xcontent_schedule_itens schedl", 
    			"schedl.*, DAYOFWEEK(schedl.start) as week_day", 
    			sprintf("schedl.schedule_id = %d", $schedule_id)
    		);
    	
    		
    	// GROUP BY WEEK DAY
    	$schedules = array();  
    	foreach($contentSchedules as $scheduleItem) {
    		if (!is_array($schedules[$scheduleItem['week_day']])) {
   				$schedules[$scheduleItem['week_day']] = array();
   			}
   			$schedules[$scheduleItem['week_day']][] = $scheduleItem;
   		} 
    	
   		$smarty -> assign("T_XCONTENT_SCHEDULE", $schedules);
    	$week_days = array(
    		1	=> 'Domingo',
    		2	=> 'Segunda-feira',
    		3	=> 'Terça-feira',
    		4	=> 'Quarta-feira',
    		5	=> 'Quinta-feira',
    		6	=> 'Sexta-feira',
    		7	=> 'Sábado'
    	);

    	$smarty -> assign("T_XCONTENT_WEEKNAMES", $week_days);
    }
    public function waitingXcontentScheduleLiberationAction() {
    	return true;
    }
    public function userContentScheduledLiberationAction() {
    	$fields = $_POST;
    	
    	$userContent = eF_getTableData(
			"module_xcontent_schedule_users", 
			"*", 
			sprintf("schedule_id = %d AND user_id = %d AND content_id = %d", $fields['schedule_id'], $fields['user_id'], $fields['content_id'])
		);
    	
    	if (count($userContent)  == 1) {
    		eF_updateTableData(
				"module_xcontent_schedule_users", 
				array('liberation' => $fields['liberation']), 
				sprintf("schedule_id = %d AND user_id = %d", $fields['schedule_id'], $fields['user_id'])
			);
			
	    	return array(
	    		"message"		=> "Usuário liberado com sucesso", 
	    		"message_type"	=> "success",
	    		"data"			=> $userContent
	    	);
    	}
    	
    	return array(
    		"message"		=> "Ocorreu um erro ao tentar liberar o usuário. Por favor tente novamente.", 
    		"message_type"	=> "failure",
    		"data"			=> $userContent
    	);
    }
    public function userContentNotScheduledLiberationAction() {
    	$fields = $_POST;
    	
    	$userContent = eF_getTableData(
			"module_xcontent_schedule_users", 
			"*",
			sprintf("schedule_id = %d AND user_id = %d AND content_id = %d", $fields['schedule_id'], $fields['user_id'], $fields['content_id'])
		);
    	
    	if (count($userContent)  == 1) {
    		eF_updateTableData(
				"module_xcontent_schedule_users", 
				array('liberation' => $fields['liberation']), 
				sprintf("schedule_id = %d AND user_id = %d", $fields['schedule_id'], $fields['user_id'])
			);
			
	    	return array(
	    		"message"		=> "Usuário liberado com sucesso", 
	    		"message_type"	=> "success",
	    		"data"			=> $userContent
	    	);
    	}
    	
    	return array(
    		"message"		=> "Ocorreu um erro ao tentar liberar o usuário. Por favor tente novamente.", 
    		"message_type"	=> "failure",
    		"data"			=> $userContent
    	);
    }
    
    public function authorizeXcontentScheduleAction() {
    	$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();
    	
    	$xuserModule = $this->loadModule("xuser");
	
    	if (
    		$xuserModule->getExtendedTypeID($currentUser) == 'polo' ||
  			$currentUser->getType() == 'administrator'
    	) {
    		if ($xuserModule->getExtendedTypeID($currentUser) == 'polo') {
    			$userIsAdmin = false;
    			
    			
				if ($this->getCurrentLesson()) {
	    			$currentLessonsID = array( $this->getCurrentLesson()->lesson['id'] );
	    		} else {
	    			$currentLessonsID = array_keys($currentUser->getLessons(false, 'student'));
	    		}
    		} else {
    			// ADMIN SEE ALL LESSONS
    			$userIsAdmin = true;
    			$currentLessonsID = array_keys(MagesterLesson::getLessons(false));
    		}

    		$userContentID = eF_getTableDataFlat("content", "id", sprintf("lessons_ID IN (%s)", implode(", ", $currentLessonsID)));
    		/*
    		$result = eF_getTableData(
	    		"module_xcontent_schedule schedl 
	    		LEFT JOIN module_xentify_scopes scop ON schedl.xentify_scope_id = scop.id
	    		", 
	    		"schedl.id, schedl.xentify_scope_id, schedl.xentify_id, schedl.block_html, ", 
	    		sprintf(
	    			"CURRENT_TIMESTAMP BETWEEN schedl.start AND schedl.end 
	    			AND schedl.active = 1 
	    			AND schedl.content_id IN (%s)", implode(",", $userContentID['id'])
	    		)
	    	);
	    	*/
    		$userCoursesID = eF_getTableDataFlat("users_to_courses", "courses_ID as course_id", sprintf("users_LOGIN = '%s'", $currentUser->user['login']));
    		
    		$result = eF_getTableData(
    				"module_xcontent_schedule sch
    				LEFT JOIN module_xentify_scopes scop ON (sch.xentify_scope_id = scop.id)

    				",
    				"sch.id, sch.xentify_scope_id, sch.xentify_id, /* user_schedl.index as selected_option, */sch.block_html, sch.active",
    				sprintf(
    					"sch.active = 1
    					AND sch.id IN (
    						SELECT schedule_id FROM module_xcontent_schedule_contents sch_ct
    						WHERE sch_ct.course_id IN (%s)
    					)", implode(",", $userCoursesID['course_id'])
    				)
    		);    		
    		
    		$AllCourses = MagesterCourse::getAllCourses(array('return_objects' => false));
    		$AllLessons = MagesterLesson::getLessons(true);
    		$AllClasses = MagesterCourseClass::getAllClasses(array('return_objects' => false));
    		$AllUsersClassesDB = eF_getTableData("users_to_courses", "users_LOGIN, classe_id", "classe_id <> 0");
	   		$paidUsers = array();
    		
    		foreach($AllUsersClassesDB as $userClass) {
    			if (array_key_exists($userClass['users_LOGIN'], $AllUsersClasses)) {
    				$AllUsersClasses[$userClass['users_LOGIN']] = array();
    			}
    			$AllUsersClasses[$userClass['users_LOGIN']][] = $userClass['classe_id'];
    		}
    		$allPolos = array();
    		if (!$userIsAdmin) {
    			$userPolo = $currentUser->getUserPolo(array('return_objects'	=> false));
    			
    			
    			$allPolos[$userPolo['id']] = $userPolo;
    		} else {
    			$allPolosDB = eF_getTableData(
    					"module_polos polo",
    					"polo.*",
    					"polo.active = 1"
    			);
    			
    			foreach($allPolosDB as $userPolo) {
    				$allPolos[$userPolo['id']] = $userPolo;
    			}
    		}
    		foreach($allPolos as $userPolo) {
    			$AllPoloData = array();
		    	foreach($result as $key => $contentToSchedule) {
		    		// CHECK IF IS THE SAME scope
		    		if (!$userIsAdmin) {
		    	    	$status = $this->getUserScopeStatus($currentUser, $contentToSchedule['xentify_scope_id'], $contentToSchedule['xentify_id']);
	    			
	    				if (!$status['same_polo']) {
		    				//unset($result[$key]);
		    				continue;
	    				}
		    		}
		    		
	    			$data = $this->getUserScopeData($contentToSchedule['xentify_scope_id'], $contentToSchedule['xentify_id']);
	    			
	    			if ($data['polo_id'] != $userPolo['id']) {
	    				continue;
	    			}
	    			
	    			// CHECK IF USER ALREADY SCHEDULED THIS CONTENT
	    			
	    			$contentClasse = $AllClasses[$data['classe_id']];
					$contentToSchedule['classe_name'] = $contentClasse['name'];
					
					$courseID = $contentClasse['courses_ID'];
	    			$contentCourse = $AllCourses[$courseID];
	    			
	    			//var_dump($AllCourses[$courseID]);
	    			$contentToSchedule['course_name'] = $contentCourse['name'];
	    			
	    			// CARREGAR USUARIOS AGENDADOS FILTER BY POLO.
	    			
	    			//if (!$userIsAdmin) {
	    				$lessonsUsers = $AllLessons[$contentToSchedule['lesson_id']]->getLessonUsers(array('return_objects' => false, 'condition' => sprintf("u.id IN (SELECT id FROM module_xuser WHERE polo_id = %s)", $userPolo['id'])));
	    				
	    				if (!array_key_exists($courseID, $paidUsers)) {
	    					$paidUserCourse = eF_getTableDataFlat("module_xenrollment enr LEFT JOIN users u ON (enr.users_id = u.id)", "u.login", sprintf("courses_id = %d AND status_id = 4", $courseID));
	    					
	    					$paidUsers[$courseID] = $paidUserCourse['login']; 
	    				}
	    				
	    				
	    				
	    				
	    				$userContentSchedules =	eF_getTableData(
	    					"module_xcontent_schedule_users sch_u
	    					LEFT JOIN module_xcontent_schedule_itens sch_item ON (sch_u.schedule_id = sch_item.schedule_id AND sch_u.index = sch_item.index)
	    					LEFT JOIN users u ON (sch_u.user_id = u.id)",
	    					"sch_u.*, sch_item.*, u.login, u.name, u.surname",
	    					sprintf("sch_u.schedule_id = %d AND u.active = 1 AND u.id IN (SELECT id FROM module_xuser WHERE polo_id = %d)", $contentToSchedule['id'], $userPolo['id'])
	    				);
	    			//} else {
	    			//	$lessonsUsers = $AllLessons[$contentToSchedule['lesson_id']]->getLessonUsers(array('return_objects' => false));
/*
	    				$userContentSchedules =	eF_getTableData(
	    					"module_xcontent_schedule_users sch_u
	    					LEFT JOIN module_xcontent_schedule_itens sch_item ON (sch_u.schedule_id = sch_item.schedule_id AND sch_u.index = sch_item.index)
	    					LEFT JOIN users u ON (sch_u.user_id = u.id)
	    					",
	    					"sch_u.*, sch_item.*, u.login, u.name, u.surname, xu.polo_id, polo.nome as polo",
	    					sprintf("sch_u.schedule_id = %d", $contentToSchedule['id'])
	    				);
	    			}
*/
	    			
//		    		var_dump($paidUsers[$courseID]);
		    		
		    		foreach($userContentSchedules as &$scheduleItem) {
		    			if (in_array($scheduleItem['login'], $paidUsers[$courseID])) {
			    			$scheduleItem['fullname'] = formatLogin(null, $scheduleItem);
			    			$scheduleItem['scheduled']	= true;
		    			} else {
		    				/*
		    				var_dump("SCHEDULED: " .$scheduleItem['login']);
		    				echo '<br />';
		    				*/
		    			}
		    			unset($lessonsUsers[$scheduleItem['login']]);
		    		}
		    		foreach($lessonsUsers as $nonScheduledUser) {
		    			//var_dump($contentToSchedule);
		    			if (in_array($nonScheduledUser['login'], $paidUsers[$courseID])) {
							if (in_array($contentClasse['id'], $AllUsersClasses[$nonScheduledUser['login']])) {
								if ($nonScheduledUser['user_type'] != 'student' || $nonScheduledUser['user_types_ID'] == '11') {
				    				continue;
				    			}
				    			
				    			$scheduleToAppend = array(
				    			    "schedule_id" 	=> $contentToSchedule['id'],
				    				"user_id"		=> $nonScheduledUser['id'],
				    				"index"			=> null,
				    				"start"			=> null,
				    				"end"			=> null,
				    				"login"			=> $nonScheduledUser['login'],
				    				"name"			=> $nonScheduledUser['name'],
				    				"surname"		=> $nonScheduledUser['surname'],
				    				"scheduled"		=> false
				    			);
				    			$scheduleToAppend['fullname'] = formatLogin(null, $scheduleToAppend);
				    			
				    			$userContentSchedules[] = $scheduleToAppend;
			    			}
		    			} else {
		    				/*
		    				var_dump("NO SCHEDULED: " . $nonScheduledUser['login']);
		    				echo '<br />';
		    				*/
		    			}
		    		}
		    		$contentToSchedule['users'] = $userContentSchedules;
		    		$AllPoloData[] = $contentToSchedule;
		    	}
		    	$allData[$userPolo['id']] = $AllPoloData; 
	    	}
	    	
	    	
	    	$smarty -> assign("T_XCONTENT_SCHEDULE_ITEM", "Agendamento da Prova Presencial");
	    	$smarty -> assign("T_XCONTENT_SCHEDULE_CONTENTS", $allData);
	    	if (!$userIsAdmin) {
	    		$smarty -> assign("T_XCONTENT_USERPOLO", $userPolo);
	    		
	    	} else {
	    		// LOAD POLO LIST
	    		
	    	}
	    	$smarty -> assign("T_XCONTENT_POLOS", $allPolos);
	    	$smarty -> assign("T_XCONTENT_IS_ADMIN", $userIsAdmin);
	    	
	    	
	    	if (count($result) == 0) {
	    		return false;
	    	}
	    	
	    	$week_days = array(
	    		1	=> 'Domingo',
	    		2	=> 'Segunda-feira',
	    		3	=> 'Terça-feira',
	    		4	=> 'Quarta-feira',
	    		5	=> 'Quinta-feira',
	    		6	=> 'Sexta-feira',
	    		7	=> 'Sábado'
	    	);

	    	$smarty -> assign("T_XCONTENT_WEEKNAMES", $week_days);
	    	
	    	return true;
    	} elseif ($currentUser->getType() == 'student') {
    	} elseif ($currentUser->getType() == 'professor') {
    	}
    	return false;
    }

	public function getUserTestScoreAction($token = null, $constraints = null) {
		if (isset($constraints['login'])) {
			$currentUser	= MagesterUserFactory :: factory($constraints['login']);
		} else {
			$currentUser	= $this->getCurrentUser();
		}
		// OPEN TEST RESULT
		$testContentID = $constraints['unit_id'];
		
		try {
			$userTest = new MagesterTest($testContentID);
			
			$recentUserTests = eF_getTableData(
					"completed_tests JOIN tests ON tests_id = tests.id JOIN users ON completed_tests.users_LOGIN = users.login",
					"completed_tests.id, completed_tests.test, completed_tests.score, users.name as username, users.surname, completed_tests.tests_ID, tests.name, completed_tests.timestamp, completed_tests.users_LOGIN",
					"completed_tests.status != 'deleted' and completed_tests.users_LOGIN = '" . $currentUser->user['login'] . "' and completed_tests.tests_id = " . $userTest->test['id'], "timestamp DESC");
		
			if (count($recentUserTests) > 0) {
				$userScore = is_null($recentUserTests[0]['score']) ? 0 : $recentUserTests[0]['score'];
				
				/** @todo Move this code to your own class */
				/*
				 Book	%			Nível					skill_id
				1A		0 - 33%		Básico
				1B		34 - 40%	Básico
				2A		41 - 48%	Pré-Intermediário
				2B		49 - 55%	Pré-Intermediário
				3A		56 - 63%	Intermediário
				3B		64 - 70%	Intermediário
				4A		71 - 78%	Intermediário Avançado
				4B		79 - 85%	Intermediário Avançado
				5A		86 - 93%	Avançado
				5B		94 - 100%	Avançado
				*/
				$skills = array();
				if ($userScore > 33) {
					$skills[] = 1;
				}
				if ($userScore > 40) {
					$skills[] = 2;
				}
				if ($userScore > 48) {
					$skills[] = 3;
				}
				if ($userScore > 55) {
					$skills[] = 4;
				}
				
				$skillToInsert = array();
				
				foreach($skills as $skill) {
					$skillToInsert[] = array(
						'user_id'	=> $currentUser->user['id'],
						'skill_id'	=> $skill
					);
				}
				ef_deleteTableData(
					"module_xskill_users",
					sprintf("user_id = %d AND skill_id IN (1,2,3,4)", $currentUser->user['id'])
				);
				ef_insertTableDataMultiple("module_xskill_users", $skillToInsert);
				
				// APPEND SKILLS TO USER, BASED ON SCORE
				if ($userScore <= 40) {
					$level = 1;
				} elseif ($userScore > 40 && $userScore <= 55) {
					$level = 2;
				} elseif ($userScore > 55 && $userScore <= 70) {
					$level = 3;
				} elseif ($userScore > 70 && $userScore <= 85) {
					$level = 4;
				} else {
					$level = 5;
				}				
				
				return array(
					'score'		=> $userScore,
					'level'		=> $level,
					'skills'	=> $skills
				);
			}
		} catch (Exception $e) {
			var_dump($e);
		}
		return array();
	}    

	public function copyScheduledContentsAction()
	{
		$smarty = $this -> getSmartyVar();
		$currentUser = $this->getCurrentUser();
		$xuserModule = $this->loadModule("xuser");
		
		if (
				$xuserModule->getExtendedTypeID($currentUser) != "administrator" &&
				$currentUser->moduleAccess['xcontent'] != 'view' &&
				$currentUser->moduleAccess['xcontent'] != 'change'
		) {
			header("Location: " . $this->moduleBaseUrl);
			exit;
		}
		
		$fromID = $_POST['orig'];
		$toID = $_POST['dest'];
		
		$contentToInsert = eF_getTableData(
			"module_xcontent_schedule_contents",
			sprintf("%d as schedule_id, course_id, content_id, required", $toID),
			sprintf("schedule_id = %d", $fromID)
		);
		
		try {
			eF_deleteTableData("module_xcontent_schedule_contents", sprintf("schedule_id = %d", $toID));
			eF_insertTableDataMultiple("module_xcontent_schedule_contents", $contentToInsert);
			
			$response = array(
				'message' => __XCONTENT_SCHEDULE_COPIED_SUCESSFULLY,
				'message_type' => 'success'
			);
		} catch (Exception $e) {
			$response = array(
				'message' => $e->getMessage(),
				'message_type' => 'failure'
			);
		}
		echo json_encode($response);
		exit;
	}
	
	/* DATA MODEL FUNCTIONS */
    public function getContentsByScheduleId($scheduleID, $filter = array())
    {
    	$userID = $this->getCurrentUser()->user['id'];
    	
    	$where = array(sprintf("sch.id = '%s'", $scheduleID));
    	if (array_key_exists('course_id', $filter)) {
    		$where[] = 'course_id = ' . $filter['course_id'];
    	}
    	if (array_key_exists('courses_id', $filter)) {
    		$where[] = sprintf('course_id IN (%s)', implode(",", $filter['courses_id']));
    	}
    	if (array_key_exists('contents_id', $filter)) {
    		$where[] = sprintf('sch_c.content_id IN (%s)', implode(",", $filter['contents_id']));
    	}
   	
        $contentData = eF_getTableData(
        	"module_xcontent_schedule sch
        	JOIN module_xcontent_schedule_contents sch_c ON  (sch.id = sch_c.schedule_id)
        	LEFT JOIN content ct ON (sch_c.content_id = ct.id)
        	LEFT JOIN lessons l ON (ct.lessons_ID = l.id)
        	LEFT JOIN courses c ON (sch_c.course_id = c.id)
        	LEFT OUTER JOIN module_xcontent_schedule_users sch_u ON (
        		sch_c.schedule_id = sch_u.schedule_id AND 
        		sch_u.user_id = " . $userID . " AND 
        		sch_c.content_id = sch_u.content_id
        	)
			LEFT OUTER JOIN module_xcontent_schedule_itens sch_i ON (
        		sch_u.schedule_id = sch_i.schedule_id AND 
        		sch_u.`index` = sch_i.`index`
        	)",
        	"sch.id as schedule_id, sch_c.course_id, c.name as course, ct.lessons_ID as lesson_id, l.name as lesson, sch_c.content_id, ct.name as content, sch_c.required, sch_u.`index` as option_index, sch_i.start as option_start, sch_i.end as option_end",
        	implode(" AND ", $where),
        	"required DESC"
        );
        return $contentData;
    }
	public function getSchedules($scopeID = null, $grouped = false) {
		$where = array(
			"schedl.active = 1"
		);
		
		if (is_numeric($scopeID)) {
			$scopeID = array($scopeID);
		}
		
		if (is_array($scopeID)) {
			$where[] = sprintf("schedl.xentify_scope_id IN (%s)", implode(", ", $scopeID));
		}
/*
    	$scheduleData = eF_getTableData(
	    	"module_xcontent_schedule schedl 
	    	LEFT JOIN module_xentify_scopes scop ON schedl.xentify_scope_id = scop.id
	    	LEFT JOIN content cont ON schedl.content_id = cont.id
	    	LEFT JOIN lessons ON cont.lessons_ID = lessons.id", 
	    	"schedl.id, schedl.content_id, schedl.xentify_scope_id, schedl.xentify_id, 
	    	schedl.start, schedl.block_html, cont.name, lessons.name as lesson_name,
	    	(SELECT MIN(module_xcontent_schedule_itens.start) FROM module_xcontent_schedule_itens WHERE schedl.id = module_xcontent_schedule_itens.schedule_id) as start, 
	    	(SELECT MAX(module_xcontent_schedule_itens.end) FROM module_xcontent_schedule_itens WHERE schedl.id = module_xcontent_schedule_itens.schedule_id) as end",
    		implode(" AND ", $where),
    		"schedl.start DESC"
	    );
 */
		/*
		echo prepareGetTableData(
				"module_xcontent_schedule schedl
				LEFT JOIN module_xentify_scopes scop ON schedl.xentify_scope_id = scop.id
				LEFT OUTER JOIN module_xcontent_schedule_contents sch_ct ON (schedl.id = sch_ct.schedule_id)
				",
				"schedl.id, schedl.xentify_scope_id, schedl.xentify_id, schedl.start, schedl.block_html,
				COUNT(DISTINCT sch_ct.course_id),
				COUNT(DISTINCT sch_ct.content_id),
				(SELECT MIN(module_xcontent_schedule_itens.start) FROM module_xcontent_schedule_itens WHERE schedl.id = module_xcontent_schedule_itens.schedule_id) as start,
				(SELECT MAX(module_xcontent_schedule_itens.end) FROM module_xcontent_schedule_itens WHERE schedl.id = module_xcontent_schedule_itens.schedule_id) as end",
				implode(" AND ", $where),
				"schedl.start DESC"

		);
		exit;
*/
    	$scheduleData = eF_getTableData(
   			"module_xcontent_schedule schedl
   			LEFT JOIN module_xentify_scopes scop ON schedl.xentify_scope_id = scop.id
   			LEFT OUTER JOIN module_xcontent_schedule_contents sch_ct ON (schedl.id = sch_ct.schedule_id)
   			",
   			"schedl.id, schedl.xentify_scope_id, schedl.xentify_id, schedl.start, schedl.block_html, " .
   			"COUNT(DISTINCT sch_ct.course_id) as total_courses, " .
   			"COUNT(DISTINCT sch_ct.content_id) as total_contents,
   			(SELECT MIN(module_xcontent_schedule_itens.start) FROM module_xcontent_schedule_itens WHERE schedl.id = module_xcontent_schedule_itens.schedule_id) as start,
   			(SELECT MAX(module_xcontent_schedule_itens.end) FROM module_xcontent_schedule_itens WHERE schedl.id = module_xcontent_schedule_itens.schedule_id) as end",
   			implode(" AND ", $where),
   			"schedl.start DESC",
   			"schedl.id, schedl.xentify_scope_id, schedl.xentify_id, schedl.start, schedl.block_html"
    	);
    	
	    $result = array();
	    if ($grouped) {
		    foreach($scheduleData as $item) {
		    	if (!is_array($result[$item['xentify_scope_id']])) {
		    		$result[$item['xentify_scope_id']] = array();
		    	}
		    	$result[$item['xentify_scope_id']][] = $item;
		    }
		} else {
			$result = $scheduleData;
		}

	    return $result;
	}
	
	/** @todo Move this functions  to your own module "xentify" */
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
    public function isUserInScope($user = null, $scope_type, $scope_id) {
    	$status = $this->getUserScopeStatus($user, $scope_type, $scope_id);
    	
    	switch($scope_type) {
    		case 0 : { // SAME POLO AND SAME CLASS
    			return true;
    		}
    		case 2 : { // SAME POLO AND SAME CLASS
    			return $status['same_polo'];
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
    		'no_overdue'	=> false,
    		'overdue'		=> false
	   	);
	   	$data = $this->getUserScopeData($scope_type, $scope_id);
	   	
    	switch($scope_type) {
    		case 2 : { // SAME POLO
    			$status['same_polo'] = $this->checkUserScopeSamePolo($user, $data['polo_id']);
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
    		default : {
    			return false;
    		}
    	}
    	return $status;
    }
    public function getUserScopeData($scope_type, $scope_id) {
    	if (is_null($user)) {
    		$user = $this->getCurrentUser();
    	}
    	$data = array(
			'polo_id'			=> null,
   			'classe_id'			=> null
		);
    	
    	switch($scope_type) {
    		case 2 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id']) = explode(';', $scope_id);
    			break;
    		}
    		case 10 : { // SAME POLO AND SAME CLASS
    			list($data['polo_id'], $data['classe_id']) = explode(';', $scope_id);
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
    
    private function checkUserScopeSamePolo($user, $polo_id) {
		$userPolo = $user->getUserPolo(array('return_objects'	=> false));
		
		return ($userPolo['id'] == $polo_id);
    }
    private function checkUserScopeSameClasse($user, $classe_id) {
        $userClasses = $user->getUserCoursesClasses(array('return_objects'	=> false));
    			
    	$classesID = array();
    	foreach($userClasses as $classe) {
    		$classesID[] = $classe['id'];
    	}
    	
    	return in_array($classe_id, $classesID);
    }

    /* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
    public function getLessonModule() {
    	$this->registerXcontentScheduleAction();
    	
    	$this->assignSmartyModuleVariables();
    	
    	return true;
    }
    public function getLessonSmartyTpl() {
    	return $this->moduleBaseDir . "templates/includes/lesson.innertable.tpl";
    	
    }
    /* OLD-STYLE FUNCTIONS */
    public function getCenterLinkInfo() {
    	$currentUser = $this -> getCurrentUser();
    	
    	;
    
    	$xuserModule = $this->loadModule("xuser");
		if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator" || 
			$currentUser->moduleAccess['xcontent'] == 'view' ||
			$currentUser->moduleAccess['xcontent'] == 'change'
    	) {
    		return array(
   				'title' => $this->getTitle($this->getDefaultAction()),
   				'image' => $this -> moduleBaseDir . 'images/xcontent.png',
   				'link'  => $this -> moduleBaseUrl,
   				'class' => 'content'
    		);
    	}
    }
    public function getSidebarLinkInfo() {
    	 
    	$xuserModule = $this->loadModule("xuser");
    	$currentUser = $this -> getCurrentUser();
    	
		if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator" || 
			$currentUser->moduleAccess['xcontent'] == 'view' ||
			$currentUser->moduleAccess['xcontent'] == 'change'
    	) {
    		$link_of_menu = array (
    				array (
    						'id' => $this->index_name . '_menu_link',
    						'title' => $this->getTitle($this->getDefaultAction()),
    						//              'image' => $this -> moduleBaseDir . 'images/xcontent.png',
    						'_magesterExtensions' => '1',
    						'link'  => $this -> moduleBaseUrl
    				)
    		);
    
    		return array ( "content" => $link_of_menu);
    	}
    }
}
