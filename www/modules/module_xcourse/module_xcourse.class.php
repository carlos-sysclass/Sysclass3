<?php
/*
 class MagesterCourseTree extends MagesterTree {
 public function insertNode($node, $parentNode = false, $previousNode = false) {}
 public function removeNode($node) {}
 public function reset() {}
 }
 */

class module_xcourse extends MagesterExtendedModule {
	const GET_XCOURSES				= 'get_xcourses';
	//const GET_XCOURSES_SOURCE			= 'get_xcourses_source';
	const ADD_XCOURSE				= 'add_xcourse';
	const EDIT_XCOURSE				= 'edit_xcourse';
	const EDIT_XCOURSE_CALENDAR		= 'edit_xcourse_calendar';
	const DELETE_XCOURSE			= 'delete_xcourse';
	const UPDATE_XCOURSE			= 'update_xcourse';
	const CONFIRM_USER_IN_XCOURSE	= 'confirm_user_in_xcourse';
	const UNCONFIRM_USER_IN_XCOURSE	= 'unconfirm_user_in_xcourse';
	const GET_CLASS_SCHEDULES		= 'get_class_schedules';
	const GET_XCOURSE_USERS_SOURCE	= 'get_xcourse_users_source';
	const SAVE_CLASS_SCHEDULES		= 'save_class_schedules';
	const PUT_USER_IN_COURSE		= 'put_user_in_course';
	const PUT_USER_IN_CLASS			= 'put_user_in_class';
	const UPDATE_LESSONS_ORDER				= 'update_lessons_order';
	const UPDATE_ACADEMIC_CALENDAR_SERIES	= 'update_academic_calendar_series';
	// STUDENT ACTIONS
	const VIEW_COURSE_DASHBOARD		= 'view_course_dashboard';
	const VIEW_ACADEMIC_CALENDAR	= 'view_academic_calendar';

	public function __construct($defined_moduleBaseUrl, $defined_moduleFolder) {
		parent::__construct($defined_moduleBaseUrl, $defined_moduleFolder);
		/*
		 $this->modules = array(
			'xuser'	=>
			);
			*/
		$this->preActions[] = 'checkUserPermissionAction';
		//		$this->preActions[] = 'makeEnrollmentOptions';

		//		$this->postActions[] = 'checkUserPermission';
		//$this->postActions[] = 'makeXenrollmentOptionsAction';
	}
	// Mandatory functions required for module function

	public function getName() {
		return "XCOURSE";
	}

	public function getPermittedRoles() {
		return array("administrator", "professor", "student");
	}

	public function isLessonModule() {
		return true;
	}

	public function getUrl($action) {
		switch($action) {
			case self::EDIT_XCOURSE :
			case self::EDIT_XCOURSE_CALENDAR : {
				return $this -> moduleBaseUrl .	"&action=" . $action . "&xcourse_id=" . $this->getEditedCourse()->course['id'];
			}
			default : {
				return parent::getUrl($action);
			}
		}
	}

	public function getTitle($action) {
		switch($action) {
			case self::ADD_XCOURSE : {
				return __XCOURSES_ADDXCOURSE;
			}
			case self::EDIT_XCOURSE : {
				$courseName = '<span class="username">' . $this->getEditedCourse()->course['name'] . '</span>';
				 
				return sprintf(__XCOURSE_EDITINGXCOURSE_, $courseName);
			}
			/*
			 case self::VIEW_ACADEMIC_CALENDAR : {
			 return sprintf(__XCOURSE_VIEW_LESSON_ACADEMIC_CALENDAR_, $lessonName);
			 }
			 */
			case self::EDIT_XCOURSE_CALENDAR : {
				$courseName = '<span class="username">' . $this->getEditedCourse()->course['name'] . '</span>';
				return sprintf(__XCOURSE_EDITINGXCOURSE_CALENDAR_, $courseName);
			}
			case $this->getDefaultAction() : {
				return __XCOURSES_MANAGEMENT;
			}
			default : {
				return parent::getTitle($action);
			}
		}
	}

	
	public function addScripts() {
		if ($this->getCurrentAction() == self::VIEW_COURSE_DASHBOARD) {
			return array("jquery/jquery.ganttView");
		}
		return parent::addScripts();
	}

	
	public function addStylesheets() {
		if ($this->getCurrentAction() == self::VIEW_COURSE_DASHBOARD) {
			return array("jquery/ganttView");
		}
		return parent::addStylesheets();
	}

	
	public function getDefaultAction() {
		return self::GET_XCOURSES;
	}

	
	/* CURRENT-LESSON ATTACHED MODULE PAGES */
	public function getLessonModule() {
		$result = $this->loadAcademicCalendarBlock("academic-calendar-index");
		// CHECK FOR TEMPLATING
		$smarty = $this -> getSmartyVar();
		if (count($this->templates) > 0) {
			$smarty -> assign ("T_" . $this->getName() . "_TEMPLATES", $this->templates);
		}
		$smarty -> assign ("T_" . $this->getName() . "_MOD_DATA", $this->getModuleData());
		$this->assignSmartyModuleVariables();
		return $result;
	}

	
	public function getLessonSmartyTpl() {
		return $this->getSmartyTpl();
	}
	
	
	# Retorna licoes do ultimo curso selecionado pelo usuario
	public function getMessagesLastLessonViewed($blockIndex = null) {
		$currentUser = $this->getCurrentUser();
		$xuserModule = $this->loadModule("xuser");
		if ( $xuserModule->getExtendedTypeID($currentUser) != 'student' ) {
			return false;
		}
		// GET LAST MESSAGES FROM LESSON
		if (!empty($_SESSION['s_lessons_ID'])){
			$forum_messages = eF_getTableData("	f_messages fm
											JOIN f_topics ft 
											JOIN f_forums ff 
											LEFT OUTER JOIN lessons l ON ff.lessons_ID = l.id", 
				    						"fm.title, fm.id, ft.id as topic_id, fm.users_LOGIN, fm.timestamp, l.name as lessons_name, lessons_id as show_lessons_id", 
											sprintf("ft.f_forums_ID=ff.id AND fm.f_topics_ID=ft.id AND ff.lessons_ID IN (%s) ", 
											$_SESSION['s_lessons_ID']),
				    						"timestamp DESC");
   			return array($_SESSION['s_lessons_ID'] => $forum_messages);
		} else {
			return array();
		}
	}

	
	# Carrega a porcetagem concluida, video inicial e menu do curso.
	public function loadCourseUserActivity() {
		$retorno		=	array();
		$currentUser	= 	$this->getCurrentUser();
		$xuserModule 	= 	$this->loadModule('xuser');
		$constraints 	= 	array (
								'archive'	=> false, 
								'active' 	=> true, 
								'condition' => "(uc.user_type = 'student' OR uc.user_type IN (SELECT id FROM user_types WHERE basic_user_type = 'student'))", 
								'sort' 		=> 'name'
							);
		$userCourses 	= 	$currentUser -> getUserCourses($constraints);
		

		if ( count( $userCourses ) == 0 ) {
			return false;
		}


		if (!array_key_exists('s_courses_ID', $_SESSION) || !is_numeric($_SESSION['s_courses_ID']) || !array_key_exists($_SESSION['s_courses_ID'], $userCourses)) {
			$firstCourse = reset($userCourses);
			$_SESSION['s_courses_ID'] = $firstCourse->course['id']; 
		}
		
		// 
		$found = false;
		foreach($userCourses as $course) {
			if ($_SESSION['s_courses_ID'] == $course->course['id']) {
				$found = true;
			}
		}
		if (!$found) {
			$firstCourse = reset($userCourses);
			$_SESSION['s_courses_ID'] = $firstCourse->course['id'];
		}
		
		$userCourse[$_SESSION['s_courses_ID']] = $userCourses[$_SESSION['s_courses_ID']];
		
		foreach ( $userCourse as $key => $course ) {
			
			# this must be here (before $userCourses assignment) in order to revoke a certificate if it is expired and/or re-assign a course to a student if needed
			if ( $course -> course['start_date'] && $course -> course['start_date'] > time() ) {
				$value['remaining'] = null;
			} elseif ( $course -> course['end_date'] && $course -> course['end_date'] < time() ) {
				$value['remaining'] = 0;
			} elseif ($course -> options['duration'] && $course -> course['active_in_course']) {
				if ($course -> course['active_in_course'] < $course -> course['start_date']) {
					$course -> course['active_in_course'] = $course -> course['start_date'];
				}
				$course -> course['remaining'] = $course -> course['active_in_course'] + $course -> options['duration']*3600*24 - time();
				if ($course -> course['end_date'] && $course -> course['end_date'] < $course -> course['active_in_course'] + $course -> options['duration']*3600*24) {
					$course -> course['remaining'] = $course -> course['end_date'] - time();
				}
			} else {
				$course -> course['remaining'] = null;
			}
			# Check whether the course registration is expired. If so, set $value['active_in_course'] to false, so that the effect is to appear disabled
			if ($course -> course['duration'] && $course -> course['active_in_course'] && $course -> course['duration'] * 3600 * 24 + $course -> course['active_in_course'] < time()) {
				$course -> archiveCourseUsers($course -> course['users_LOGIN']);
			}
			if ($course -> course['user_type'] != $currentUser -> user['user_type']) {
				$course -> course['different_role'] = 1;
			}
			# Get current class for course. Load current lesson for course.
			$hasCalendar 							= null;
			$courseAcademicCalendar 				= $this->getAcademicCalendar($course -> course['id'], $course -> course['classe_id'], $hasCalendar);
			
			$course -> course['academic_calendar']	= $courseAcademicCalendar;
			$lessonIndex 							= 1;
			$first_activity_item 					= reset($courseAcademicCalendar);
			$first_activity_ID 						= $first_activity_item['lesson_id'];
			$showOnlyFirst 							= true;
			foreach ( $courseAcademicCalendar as $academicItem ) {
				if ( $hasCalendar && ( $academicItem['in_progress'] || $academicItem['completed' ]) ) {
					$showOnlyFirst = false;
					break;
				}
			}
			# s_lessons_ID
			$userLessons = $currentUser -> getUserLessons();
			
			$userLessonsKeys = array_keys($userLessons);

			foreach($courseAcademicCalendar as $academicKey => $academicItem) {
				if (!in_array($academicKey, $userLessonsKeys)) {
					unset($courseAcademicCalendar[$academicKey]);
				}
			}
			
			if (!array_key_exists('s_lessons_ID', $_SESSION) || !is_numeric($_SESSION['s_lessons_ID'])) {
				reset($courseAcademicCalendar);
				$_SESSION['s_lessons_ID'] = key($courseAcademicCalendar);
			}
			
			#print_r($courseAcademicCalendar);exit;
			$courseAcademicCalendar2[$_SESSION['s_lessons_ID']] = $courseAcademicCalendar[$_SESSION['s_lessons_ID']];
			#print_r($courseAcademicCalendar2);exit;
			
			# Attach lesson data to course
			foreach ( $courseAcademicCalendar2 as $index => $academicItem ) {
				if ( $showOnlyFirst && $hasCalendar ) {
					$current_activity_ID = $first_activity_ID;
				} elseif ($academicItem['in_progress'] || $academicItem['completed'] || !$hasCalendar) {
					$current_activity_ID = $academicItem['lesson_id'];
				} else {
					continue;
				}
				
				try {
					$currentLessonObject			= new MagesterLesson($current_activity_ID);
					$currentLesson 					= $currentLessonObject -> lesson;
					$currentLesson['lesson_index']	= $lessonIndex;
					if ($currentLessonObject -> lesson['info']) {
						$order 			= array("general_description", "objectives", "assessment", "lesson_topics", "resources", "other_info", "learning_method"); // for displaying fiels sorted
						$infoSorted 	= array();
						$unserialized 	= unserialize($currentLessonObject -> lesson['info']);
						foreach ($order as $value) {
							if ($unserialized[$value] != "") {
								$infoSorted[$value] = $unserialized[$value];
							}
						}
					}
					$currentLesson['information'] 		= $infoSorted;
					$currentLesson['academic_status'] 	= $academicItem;
					$userLessonStats = MagesterStats ::getUsersLessonStatusAll($currentLessonObject, $currentUser->user['login']);
					$currentLesson['progress'] = $userLessonStats[$currentLesson['id']][$currentUser->user['login']];
					if ($academicItem['in_progress'] || $showOnlyFirst) {
						$course -> course['current_activity'] = $currentLesson;
					}
					$course -> course['activities'][$currentLesson['id']] = $currentLesson;
				} catch(Exception $e) {
					var_dump($e);
				}
				if ($showOnlyFirst && $hasCalendar) {
					break;
				}
				$lessonIndex++;
			}
			$userCourse[$key] = $course;
		}
		$userCurrentUnits = array();
		if ( sizeof ($userLessons) > 0 || sizeof($userCourse) > 0 ) {
			$retorno['T_USER_COURSE_PROGRESS'] = $userCourse;
			$userCourseSwitch = array();
			foreach ( $userCourse as $course ) {
				if ( count( $course -> course['activities'] ) > 0 ) {
					$courseLessons = array();
					foreach($course -> course['activities'] as $lesson) {
						# Link last lesson
						$currentContent = new MagesterContentTree($lesson['id']);
						$currentContent -> markSeenNodes($currentUser);
						# Content tree block
						if ($GLOBALS['configuration']['disable_tests'] != 1) {
							$iterator 			= 	new MagesterContentCourseClassFilterIterator(
														new MagesterVisitableAndEmptyFilterIterator(
															new MagesterNodeFilterIterator(
																new RecursiveIteratorIterator(
																	new RecursiveArrayIterator(
																		$currentContent -> tree
																	), 
																	RecursiveIteratorIterator :: SELF_FIRST
																), 
																array('active' => 1)
															)
														),
														$courseClass
													);
							$firstNodeIterator 	=	new MagesterContentCourseClassFilterIterator(
														new MagesterVisitableFilterIterator(
															new MagesterNodeFilterIterator(
																new RecursiveIteratorIterator(
																	new RecursiveArrayIterator(
																		$currentContent -> tree
																	), 
																	RecursiveIteratorIterator :: SELF_FIRST
																), 
																array('active' => 1)
															)
														), 
														$courseClass
													);
						} else {
							$iterator 			=	new MagesterContentCourseClassFilterIterator(
														new MagesterTheoryFilterIterator(
															new MagesterVisitableAndEmptyFilterIterator(
																new MagesterNodeFilterIterator(
																	new RecursiveIteratorIterator(
																		new RecursiveArrayIterator(
																			$currentContent -> tree
																		),
																		RecursiveIteratorIterator :: SELF_FIRST
																	),
																	array('active' => 1)
																)
															)
														), 
														$courseClass
													);
							$firstNodeIterator 	= 	new MagesterContentCourseClassFilterIterator(
														new MagesterTheoryFilterIterator(
															new MagesterVisitableFilterIterator(
																new MagesterNodeFilterIterator(
																	new RecursiveIteratorIterator(
																		new RecursiveArrayIterator(
																			$currentContent -> tree
																		), 
																		RecursiveIteratorIterator :: SELF_FIRST
																	), 
																	array('active' => 1)
																)
															)
														), 
														$courseClass
													);
						}
						$userProgress 	= MagesterStats :: getUsersLessonStatus($lesson['id'], $currentUser -> user['login']);
						$userProgress 	= $userProgress[$lesson['id']][$currentUser -> user['login']];
						$seenContent 	= MagesterStats::getStudentsSeenContent($lesson['id'], $currentUser -> user['login']);
						$seenContent 	= $seenContent[$lesson['id']][$currentUser -> user['login']];
						$result 		= eF_getTableData("users_to_lessons", "current_unit", "users_LOGIN = '".$currentUser -> user['login']."' and lessons_ID = ".$lesson['id']);
						sizeof($result) > 0 ? $userProgress['current_unit'] = $result[0]['current_unit'] : $userProgress['current_unit'] = false;
						if ( $userProgress['lesson_passed'] && !$userProgress['completed'] ) {
							if ( !$userProgress['completed'] && $currentLesson -> options['auto_complete'] ) {
								$avgScore 	= $userProgress['tests_avg_score'] ?  $userProgress['tests_avg_score'] : 100;
								$timestamp 	= _AUTOCOMPLETEDAT.': '.date("Y/m/d, H:i:s");
								$currentUser -> completeLesson( $lesson['id'], $avgScore, $timestamp );
								$userProgress['completed'] 	= 1;
								$userProgress['score'] 		= $avgScore;
								$userProgress['comments'] 	= $timestamp;
							}
						}
						# Separate if because it might have just been set completed, from the previous if
						# Separado se porque poderia ter sido definida apenas concluída, a partir do anterior, se
						if ( $userProgress['completed'] ) {
							$retorno['T_LESSON_COMPLETED'] = $userProgress['completed'];
							$link_first_last_lesson[] =	array(
															'text' 		=> _LESSONCOMPLETE, 
															'image' 	=> '32x32/success.png', 
															'href' 		=> basename($_SERVER['PHP_SELF']).'?ctg=progress&popup=1', 
															'onclick' 	=> "eF_js_showDivPopup('"._LESSONINFORMATION."', 2)", 
															'target' => 'POPUP_FRAME'
														);
						}
						# If there exists a value within the 'current_unit' attribute, it means that the student was in the lesson before. Seek the first unit that he hasn't seen yet
						if ($userProgress['current_unit']) {
							$currentUnitID = $userProgress['current_unit'];
							$firstUnseenUnit = $currentContent -> getFirstNode($firstNodeIterator);
							//Get to the first unseen unit
							while ($firstUnseenUnit && in_array($firstUnseenUnit['id'], array_keys($seenContent))) {
								$firstUnseenUnit = $currentContent -> getNextNode($firstUnseenUnit, $firstNodeIterator);
							}
							if (!$firstUnseenUnit) {
								$firstUnseenUnit = $currentContent -> getFirstNode($firstNodeIterator);
							}
							if ($currentLesson -> options['start_resume'] && $firstUnseenUnit) {
								$currentUnitID = $firstUnseenUnit['id'];
							}
						} else {
							$iterator 			=	new MagesterContentCourseClassFilterIterator(
														new MagesterVisitableFilterIterator(
															new MagesterNodeFilterIterator(
																new RecursiveIteratorIterator(
																	new RecursiveArrayIterator(
																		$currentContent -> tree
																	), 
																	RecursiveIteratorIterator :: SELF_FIRST
																)
															)
														), 
														$courseClass
													);
							$iterator -> next();
							$firstUnseenUnit 	= 	$firstUnit = $iterator -> current();
							$currentUnitID 		= 	$firstUnit['id'];
						}
						!isset( $currentUnitID ) ? $currentUnitID = 0 : null;
						if ( !array_key_exists( $course -> course['id'], $userCurrentUnits ) ) {
							$userCurrentUnits[$course->course['id']] = array();
						}
						$userCurrentUnits[$course->course['id']][$lesson['id']] = $currentUnitID;
						unset($currentUnitID);
					}
				}
			}
		}
		$retorno['T_CURRENT_UNITS'] = $userCurrentUnits;
		
		if ( !is_null($this->getParent() ) ) {
			$context = $this->getParent();
		} else {
			$context = $this;
		}
		if ( $xuserModule->getExtendedTypeID( $currentUser ) == 'polo' ) {
			return false;
		}
		$userCourse = $currentUser -> getUserCourses($constraints);
		# LOADING STUDENT GUIDANCE BY COURSE
		# CARREGANDO ORIENTAÇÃO POR ALUNO DO CURSO
		/** @todo Incluir estes dados no banco de dados */
		$raw_guidance = array();
		$raw_guidance[20] =	array (
								array (
									'title'		=> 'Aula Magna',
									'link'		=> 'public_data/pos/bioenergia/aula_magna.pdf',
									'target'	=> '_blank'
								), array (
									'title'		=> 'Manual do Aluno',
									'link'		=> 'public_data/pos/manual_do_aluno.pdf',
									'target'	=> '_blank'
								), array (
									'title'		=> 'Hábitos de estudos',
									'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
									'target'	=> '_blank'
								)
							);
		$raw_guidance[21] = array (
								array(
									'title'		=> 'Aula Magna',
									'link'		=> 'public_data/pos/engenharia/aula_magna.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Manual do Aluno',
									'link'		=> 'public_data/pos/manual_do_aluno.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Hábitos de estudos',
									'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
									'target'	=> '_blank'
								)
							);
		$raw_guidance[31] = array(
								array(
									'title'		=> 'Aula Magna',
									'link'		=> 'public_data/pos/erp/aula_magna.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Manual do Aluno',
									'link'		=> 'public_data/pos/manual_do_aluno.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Hábitos de estudos',
									'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
									'target'	=> '_blank'
								)
							);
		$raw_guidance[39] = array(
								array(
									'title'		=> 'Aula Magna',
									'link'		=> 'public_data/pos/engenharia/aula_magna.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Manual do Aluno',
									'link'		=> 'public_data/pos/manual_do_aluno.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Hábitos de estudos',
									'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
									'target'	=> '_blank'
								)
							);
		$raw_guidance[40] = array(
								array(
									'title'		=> 'Aula Magna',
									'link'		=> 'public_data/pos/engenharia/aula_magna.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Manual do Aluno',
									'link'		=> 'public_data/pos/manual_do_aluno.pdf',
									'target'	=> '_blank'
								),
								array(
									'title'		=> 'Hábitos de estudos',
									'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
									'target'	=> '_blank'
								)
							);
		foreach ( $userCourse as $course ) {
			$userCoursesnames[$course->course['id']] = $course->course['name'];
			if ( array_key_exists( $course->course['id'], $raw_guidance ) ) {
				$guidance[$course->course['id']] = $raw_guidance[$course->course['id']];
			}
		}
		$retorno['T_XCOURSE_COURSES'] 	= $userCoursesnames;
		$retorno['GUIDANCE'] 			= $guidance;
		$retorno['COURSE_ID']			= $course_id;

		return $retorno;
	}

	/* BLOCKS FUNCTIONS */

   	# Carrega forum do ultimo curso visualizado pelo aluno quando carregado a pagina do aluno.
	public function loadCourseForumMessagesBlock($blockIndex = null) {
		$forum_messages = $this->getMessagesLastLessonViewed($blockIndex);
		if ( $forum_messages ) {
			$smarty = $this->getSmartyVar();
			$smarty -> assign("T_BLOCK_MESSAGE", $f_title);
	  		$smarty -> assign("T_FORUM_LESSON_MESSAGE", $forum_messages);
			$this->getParent()->appendTemplate(array(
				'title'			=> __XFORUM_LAST_ENTRIES,
				'template'		=> $this->moduleBaseDir . 'templates/blocks/xcourse.forum.messages.tpl',
				'contentclass'	=> 'blockContents',
	   		), $blockIndex);
	   		return true;
		} else {
			return false;
		}
	}

	
	# Carrega bloco de video barra de progresso e menu do curso
	public function loadCourseUserActivityBlock($blockIndex = null ) {
		$dados 			= 	$this->loadCourseUserActivity();
		$currentUser	= 	$this->getCurrentUser();
		$smarty 		= 	$this->getSmartyVar();
		
		
		$smarty -> assign( 'T_USER_COURSE_PROGRESS',	$dados['T_USER_COURSE_PROGRESS'] );
		$smarty -> assign( 'T_LESSON_COMPLETED', 		$dados['T_LESSON_COMPLETED'] );
		$smarty -> assign( 'T_CURRENT_UNITS', 			$dados['T_CURRENT_UNITS'] );
		$smarty -> assign( 'T_XCOURSE_COURSES', 		$dados['T_XCOURSE_COURSES']);
		$this->getParent()->appendTemplate(
			array (
		   		'title'			=> __XCOURSE_USER_ACTIVITY,
				'sub_title'		=> "",
		   		'template'		=> $this->moduleBaseDir.'templates/blocks/xcourse.user.activity.tpl',
		   		'contentclass'	=> 'blockContents'
			), 
			$blockIndex
		);
		$this->injectJS('jquery/jquery-ui');
	}


	public function loadCurseUserLessonContentsBlock($blockIndex = null) {
		$currentUser = $this->getCurrentUser();
		foreach ($currentUser as $_currentUser){
			foreach ($_currentUser as $_user ){
				$user[] = $_user['users_LOGIN'];
			}
		}
		if ($_SERVER['HTTP_HOST'] == "SysClass.com" || $_SERVER['HTTP_HOST'] == "local.SysClass.com") {
			if ( $user[0] == "aluno" || $user[0] == "luiz.aluno") {
				$this->getParent()->appendTemplate(array(
				// 'title'			=> __XCOURSE_USER_ACTIVITY,
					'title'			=> "Aula 01",
					'sub_title'		=> "",
				   	'template'		=> $this->moduleBaseDir . 'templates/blocks/xcourse.user.lesson.contents.tpl',
				   	'contentclass'	=> 'blockContents'
				   	), $blockIndex);
			}
		}
		return true;
	}


	public function loadAcademicCalendarBlock($blockIndex = null) {
		// SE ESTA NUM DISCIPLINA, MOSTRAR SOMENTE DA DISCIPLINA,
		// SE ESTA FORA, MOSTRAR DATAS DAS DISCIPLINAS, COM POSSIBILIDADE DE EXPANSAO
		if (!is_null($this->getParent())) {
			$context 	= $this->getParent();
		} else {
			$context 	= $this;
		}
		$smarty 		= $this->getSmartyVar();
		$currentUser 	= $this->getCurrentUser();
		$xuserModule 	= $this->loadModule("xuser");
		if ($xuserModule->getExtendedTypeID($currentUser) == 'polo') {
			return false;
		}
		$userCourses = $currentUser -> getUserCourses($constraints);
		if ($currentLesson = $this->getCurrentLesson()) {
			// GET CURRENT COURSE
			if ($this->getCurrentCourse()) {
				$lessonAcademicCalendar = array();
				$hasCalendar = null;
				foreach ($userCourses as $key => $course) {
					if ($course -> course['id'] == $this->getCurrentCourse()->course['id']) {
						if ($hasCalendar) {
							$academicCalendar = $this->getAcademicCalendarSeries($course -> course['id'], $course -> course['classe_id'], $currentLesson->lesson['id']);
						} else {
							$academicCalendar = $this->getAcademicCalendarSeries($course -> course['id'], $course -> course['classe_id'], $currentLesson->lesson['id'], $hasCalendar);
						}
						$lessonAcademicCalendar[$course -> course['id']] = array(
							'lesson'	=> $currentLesson->lesson,
							'series' 	=> $academicCalendar[$currentLesson->lesson['id']]['series'] 
						);
					}
				}
			}
			$smarty -> assign("T_XCOURSE_ACADEMIC_CALENDAR", $lessonAcademicCalendar);
		} else {
			$courseAcademicCalendar = array();
			foreach ($userCourses as $key => $course) {
				$courseAcademicCalendar[$course -> course['id']] = array(
					'course'	=> $course->course,
					'lessons' 	=> $this->getAcademicCalendar($course -> course['id'], $course -> course['classe_id']) 
				);
			}
				
			$smarty -> assign("T_XCOURSE_ACADEMIC_CALENDAR", $courseAcademicCalendar);
		}
		$continue = false;

		if (isset($courseAcademicCalendar)) {
			foreach($courseAcademicCalendar as $item) {
				if ($item['lessons']) {
					$continue = true;
					break;
				}
			}
		} elseif (isset($lessonAcademicCalendar) && $hasCalendar) {
			foreach($lessonAcademicCalendar as $item) {
				if ($item['series']) {
					$continue = true;
					break;
				}
			}
		} else {
		}
		/*
		 var_dump($lessonAcademicCalendar);
		 var_dump($hasCalendar);
		 var_dump($continue);
		 exit;
		 */
		if ($continue) {
			$context->appendTemplate(array(
			   	'title'			=> __XCOURSE_ACADEMIC_CALENDAR_VIEW,
			   	'template'		=> $this->moduleBaseDir . 'templates/blocks/xcourse.academic_calendar.tpl',
			   	'contentclass'	=> 'blockContents'/*,
			'options'		=> array(
			array(
			'text'			=> 'Visualizar calendário completo do curso',	
			'image'			=> '16x16/calendar.png',
			'href'			=> $this->moduleBaseUrl . "&action=view_academic_calendar"
			)
			)
			*/
			), $blockIndex);
				
			return true;
		} else {
			return false;
		}


	}


	# public function loadStudentGuidanceAction() {}

	/* ACTIONS FUNCTIONS */
	
	# Carrega o forum por ajax
	public function loadCourseForumMessagesAction($blockIndex = null) {
		$forum_messages = $this->getMessagesLastLessonViewed($blockIndex);
		if ( $forum_messages ) {
			$smarty = $this->getSmartyVar();
			$smarty -> assign("T_BLOCK_MESSAGE", $f_title);
	  		$smarty -> assign("T_FORUM_LESSON_MESSAGE", $forum_messages);
	  		echo $smarty -> fetch($this->moduleBaseDir . 'templates/blocks/xcourse.forum.messages.tpl');
		} 
	}

	
# carrega video, barra de progresso e menu inicial por ajax
	public function loadCourseUserActivityAction($blockIndex = null ) {
		$dados 			= 	$this->loadCourseUserActivity();
		$currentUser	= 	$this->getCurrentUser();
		$smarty 		= 	$this->getSmartyVar();
		$smarty -> assign( 'T_USER_COURSE_PROGRESS',	$dados['T_USER_COURSE_PROGRESS'] );
		$smarty -> assign( 'T_LESSON_COMPLETED', 		$dados['T_LESSON_COMPLETED'] );
		$smarty -> assign( 'T_CURRENT_UNITS', 			$dados['T_CURRENT_UNITS'] );
		$smarty -> assign( 'T_XCOURSE_COURSES', 		$dados['T_XCOURSE_COURSES']);
		$this->injectJS('jquery/jquery-ui');
		echo $smarty -> fetch($this->moduleBaseDir . 'templates/blocks/xcourse.user.activity.tpl');
	}
	
	public function loadAcademicCalendarLessonAction() {
		if (!is_null($this->getParent())) {
			$context = $this->getParent();
		} else {
			$context = $this;
		}
		$smarty = $this->getSmartyVar();
		$currentUser = $this->getCurrentUser();
		$currentLessonID = $_GET['lesson_id'];
		$currentCourseID = $_GET['course_id'];
		//$currentLesson = $this->getCurrentLesson();
		$filtro_course = array('archive' => false, 'active' => true, 'condition' => "uc.courses_ID = '".$currentCourseID."'", 'sort' => 'name');
		$userCourses = $currentUser -> getUserCourses($filtro_course);
		$lessonAcademicCalendar = array();
		$hasCalendar = null;
		foreach ($userCourses as $key => $course) {
			 if ($hasCalendar) {
				$academicCalendar = $this->getAcademicCalendarSeries($course -> course['id'], $course -> course['classe_id'], $currentLessonID);
			} else {
				$academicCalendar = $this->getAcademicCalendarSeries($course -> course['id'], $course -> course['classe_id'], $currentLessonID, $hasCalendar);
			}
			$lessonAcademicCalendar[$course -> course['id']] = array(
					'lesson'	=> $currentLesson->lesson,
					'series' 	=> $academicCalendar[$currentLessonID]['series'] 
			);
		}
		//	var_dump($lessonAcademicCalendar);
		//	exit;
		$smarty -> assign("T_XCOURSE_ACADEMIC_CALENDAR", $lessonAcademicCalendar);
		$context->appendTemplate(
			array (
	   			'title'			=> __XCOURSE_ACADEMIC_CALENDAR_VIEW,
				'template'		=> $this->moduleBaseDir . 'templates/blocks/xcourse.academic_calendar.tpl',
	   			'contentclass'	=> 'blockContents'
	   		), $blockIndex
	   	);
	   	return true ;
	}

	/*
	 * grava ultimo curso selecionado pelo aluno
	 */
	function setCurrentUserLessonAction() {
		$roles 			= MagesterLessonUser :: getLessonsRoles();
		$userLessons 	= $this->getCurrentUser() -> getLessons();
		$userId			= $this->getCurrentUser() -> user['id'];
		if ( isset($_POST['lesson_id']) && eF_checkParameter($_POST['lesson_id'], 'id') ) {
			if ( !isset($_SESSION['s_lessons_ID']) || $_POST['lesson_id'] != $_SESSION['s_lessons_ID'] ) {
				unset($_SESSION['s_courses_ID']);
				if ( isset($_POST['course_id']) ) {
					$course = new MagesterCourse($_POST['course_id']);
					$eligibility = $course -> checkRules($_SESSION['s_login']);
					if ( $eligibility[$_POST['lesson_id']] == 0) {
						header("Content-Type: application/json");
						echo json_encode(
							array (
								'message'		=> __LESSON_NOT_EXISTS,
								'message_type'	=> 'failure'
							)
						);
						exit;
					}
					$_SESSION['s_courses_ID'] = $course->course['id'];
				}

				if ( in_array( $_POST['lesson_id'], array_keys($userLessons) ) ) {
					$newLesson = new MagesterLesson($_POST['lesson_id']);
					if (!isset($_POST['course_id']) && $roles[$userLessons[$_POST['lesson_id']]] == 'student' && (($newLesson -> lesson['from_timestamp'] && $newLesson -> lesson['from_timestamp'] > time()) || ($newLesson -> lesson['to_timestamp'] && $newLesson -> lesson['to_timestamp'] < time()))) {
						header("Content-Type: application/json");
						echo json_encode(array(
								'message'		=> _YOUCANNOTACCESSTHISLESSONORITDOESNOTEXIST,
								'message_type'	=> 'failure'
								));
								exit;
					}
					$_SESSION['s_lessons_ID'] = $_POST['lesson_id'];
					$_SESSION['s_type'] = $roles[$userLessons[$_POST['lesson_id']]];
					//$justVisited = 1;   // used to trigger the event when the lesson info is available
					// The justVisited flag is set to one during the first visit to this lesson
					//if ($justVisited) {
					//Trigger onLessonVisited event
					//MagesterEvent::triggerEvent(array("type" => MagesterEvent::LESSON_VISITED, "users_LOGIN" => $currentUser -> user['login'], "users_name" => $currentUser -> user['name'], "users_surname" => $currentUser -> user['surname'], "lessons_ID" => $_SESSION['s_lessons_ID']));
					//}
				} else {
					header("Content-Type: application/json");
					echo json_encode(
						array(
							'message'		=> _YOUCANNOTACCESSTHISLESSONORITDOESNOTEXIST,
							'message_type'	=> 'failure'
						)
					);
					exit;
				}
			}
		}
		// registra ultimo curso acessado pelo aluno
		eF_insertOrupdateTableData('user_last_access', array('lesson_ID'=> $_POST['lesson_id'],'course_ID'=>$_POST['course_id'],'user_ID'=>$userId), 'user_ID = '.$userId );
		
		header("Content-Type: application/json");
		echo json_encode(array(
			'new_lesson_id'	=> $_POST['lesson_id'],
			'new_course_id'	=> $_POST['course_id']
		));
		exit;
	}
	function loadContentTreeXcourseFrontAction() {
		$smarty = $this->getSmartyVar();
		$currentUser = $this->getCurrentUser();
		$constraints = array('archive' => false, 'active' => true, 'condition' => "uc.user_type = 'student'", 'sort' => 'name');
		$userCourses = $currentUser -> getUserCourses($constraints);
		$classeData = ef_getTableData("users_to_courses", "classe_id", sprintf("users_LOGIN = '%s'", $currentUser -> user['login']));
		// GET USER CLASS
		$courseClass = $classeData[0]['classe_id'];
		$lessonID = $_POST["lesson_id"];
		$courseID = $_POST['course_id'];
		$currentContent = new MagesterContentTree($lessonID);
		$currentContent -> markSeenNodes($currentUser);
		//Content tree block
		if ($GLOBALS['configuration']['disable_tests'] != 1) {
			$iterator = new MagesterContentCourseClassFilterIterator(new MagesterVisitableAndEmptyFilterIterator(new MagesterNodeFilterIterator(new RecursiveIteratorIterator(new RecursiveArrayIterator($currentContent -> tree), RecursiveIteratorIterator :: SELF_FIRST), array('active' => 1))), $courseClass);
			$firstNodeIterator = new MagesterContentCourseClassFilterIterator(new MagesterVisitableFilterIterator(new MagesterNodeFilterIterator(new RecursiveIteratorIterator(new RecursiveArrayIterator($currentContent -> tree), RecursiveIteratorIterator :: SELF_FIRST), array('active' => 1))), $courseClass);
		} else {
			$iterator = new MagesterContentCourseClassFilterIterator(
							new MagesterTheoryFilterIterator(
								new MagesterVisitableAndEmptyFilterIterator(
									new MagesterNodeFilterIterator(
										new RecursiveIteratorIterator(
											new RecursiveArrayIterator($currentContent -> tree),RecursiveIteratorIterator :: SELF_FIRST
										), array('active' => 1)
									)
								)
							), $courseClass
						);
			$firstNodeIterator = new MagesterContentCourseClassFilterIterator(new MagesterTheoryFilterIterator(new MagesterVisitableFilterIterator(new MagesterNodeFilterIterator(new RecursiveIteratorIterator(new RecursiveArrayIterator($currentContent -> tree), RecursiveIteratorIterator :: SELF_FIRST), array('active' => 1)))), $courseClass);
		}
		$html_tree = $currentContent -> toHTML($iterator, "xcourse_content_tree", array(
				'truncateNames' => 60, 
				'hideFeedback' => true, 
				'show_hide'	=> false, 
				'include_root_table' => false
		)
		);
		echo $html_tree;
		$raw_guidance = array();
		$raw_guidance[20] = array(
		array(
				'title'		=> 'Aula Magna',
				'link'		=> 'public_data/pos/bioenergia/aula_magna.pdf',
				'target'	=> '_blank'
				),
				array(
				'title'		=> 'Manual do Aluno',
				'link'		=> 'public_data/pos/bioenergia/manual_do_aluno.pdf',
				'target'	=> '_blank'
				),
				array(
				'title'		=> 'Hábitos de estudos',
				'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
				'target'	=> '_blank'
				)
				);
				$raw_guidance[21] = array(
				array(
				'title'		=> 'Aula Magna',
				'link'		=> 'public_data/pos/engenharia/aula_magna.pdf',
				'target'	=> '_blank'
				),
				array(
				'title'		=> 'Manual do Aluno',
				'link'		=> 'public_data/pos/engenharia/manual_do_aluno.pdf',
				'target'	=> '_blank'
				),
				array(
				'title'		=> 'Hábitos de estudos',
				'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
				'target'	=> '_blank'
				)
				);

				$raw_guidance[28] = array(
					
				array(
				'title'		=> 'Manual do Aluno',
				'link'		=> 'public_data/pos/ead/manual_do_aluno.pdf',
				'target'	=> '_blank'
				)
				);

				$raw_guidance[31] = array(
				array(
				'title'		=> 'Aula Magna',
				'link'		=> 'public_data/pos/erp/aula_magna.pdf',
				'target'	=> '_blank'
				),
				array(
				'title'		=> 'Manual do Aluno',
				'link'		=> 'public_data/pos/erp/manual_do_aluno.pdf',
				'target'	=> '_blank'
				),
				array(
				'title'		=> 'Hábitos de estudos',
				'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
				'target'	=> '_blank'
				)
				);

				$raw_guidance[39] = array(
				array(
			'title'		=> 'Aula Magna',
			'link'		=> 'public_data/pos/posmainframe/aula_magna.pdf',
			'target'	=> '_blank'
			),
			array(
			'title'		=> 'Manual do Aluno',
			'link'		=> 'public_data/pos/posmainframe/manual_do_aluno.pdf',
			'target'	=> '_blank'
			),
			array(
			'title'		=> 'Hábitos de estudos',
			'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
			'target'	=> '_blank'
			)
			);

			$raw_guidance[40] = array(
			array(
			'title'		=> 'Aula Magna',
			'link'		=> 'public_data/pos/posmainframe/aula_magna.pdf',
			'target'	=> '_blank'
			),
			array(
			'title'		=> 'Manual do Aluno',
			'link'		=> 'public_data/pos/posmainframe/manual_do_aluno.pdf',
			'target'	=> '_blank'
			),
			array(
			'title'		=> 'Hábitos de estudos',
			'link'		=> 'public_data/pos/habitos_de_estudo.xlsx',
			'target'	=> '_blank'
			)
			);
			if (array_key_exists($courseID, $raw_guidance)) {
				// GET CURRENT COURSE
				$guidance	= $raw_guidance[$courseID];
					
				$treeInfoGuidance .= "<ul id=\"xcourse_info_tree\" class=\"infoguidance\">";
				$treeInfoGuidance .= "	<li style=\"white-space:nowrap;\"><a>Infos</a>";
				$treeInfoGuidance .= "		<ul>";
					
				foreach ( $guidance as $item ) {
					$treeInfoGuidance .= sprintf("		<li class=\"paperclip\" style=\"white-space:nowrap;\"><a href=\"%s\">%s</a></li>", $item['link'], $item['title']);
				}
				$treeInfoGuidance .= "		</ul>";
				$treeInfoGuidance .= "	</li>";
				$treeInfoGuidance .= "</ul>";
				echo $treeInfoGuidance;
			}
			exit;
	}

	function loadLessonTopLinksAction() {
		$smarty = $this->getSmartyVar();
		$currentUser = $this->getCurrentUser();
		$loadedModules = $currentUser -> getModules();
		$lessonID = $_POST["lesson_id"];
		$courseID = $_POST['course_id'];
		$curLesson = new MagesterLesson($lessonID);
		$innertable_modules = array();
		foreach ($loadedModules as $module_key => $module) {
			$module -> moduleBaseUrl = $currentUser->getType().".php?ctg=module&op=".$module_key;
			if (isset($curLesson -> options[$module -> className]) && $curLesson -> options[$module -> className] == 1) {
				if ($_admin_) {
					//$centerLinkInfo = $module -> getCenterLinkInfo();
				} else {
					$centerLinkInfo = $module -> getLessonTopLinkInfo($lessonID, $courseID);
				}
				if ($centerLinkInfo) {
					$controlPanelOption = array(
                    	'text' => $centerLinkInfo['title'], 
                    	'image' => eF_getRelativeModuleImagePath($centerLinkInfo['image']), 
                    	'href' => $centerLinkInfo['link'],
                    	'image_class' => $centerLinkInfo['image_class']
					);
					$innertable_modules[str_replace("module_", "", $module_key)] = $controlPanelOption;
				}
			}
		}
		$smarty -> assign("T_TOP_LINKS", $innertable_modules);
		$html = $smarty -> fetch($this->moduleBaseDir . "templates/actions/load_lesson_top_links.tpl");
		echo $html;
		exit;
	}

	public function getCourseListAction($token = null, $fields = array()) {
		return $this->getCoursesList($fields);
	}

	public function putUserInCourseAction($token = null, $fields = null) {
		$xuserModule = $this->loadModule("xuser");
		 
		if (is_null($token)) {
			$token = $this->getCache("enrollent_token");
		}
		if (is_null($fields)) {
			$fields = $_POST;
		}
		 
		try {
			$userObject = $xuserModule->getUserById($fields['user_id']);
			$courseObject = new MagesterCourse($fields['course_id']);

			$courseObject->addUsers($userObject->user['login'], $fields['user_type'], false, $fields['course_type']);

			if ($courseObject -> putUserInClass($userObject->user['login'], $fields['class_id'])) {
				return array_merge(
				array(
		    			'status'	=> 'ok'
		    			), $fields
		    			);
			} else {
				return array(
		    		'status'	=> 'error1'
		    		);
			}
		} catch (Exception $e) {
			return array(
	    		'status'	=> 'error2'
	    		);
		}
	}
	public function putLessonInCourseAction($token = null, $fields = null) {
		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (eF_checkParameter($fields['xcourse_id'], 'id') && eF_checkParameter($fields['xlesson_id'], 'id')) {
			$editedCourse = $this->getEditedCourse(true, $fields['xcourse_id']);
				
			try {
				!$editedCourse -> isCourseLesson($fields['xlesson_id']) ?
				$editedCourse -> addLessons($fields['xlesson_id']) :
				$editedCourse -> removeLessons($fields['xlesson_id']);

				return array(
	    			'status'	=> 'ok',
	    			'message'		=> __XCOURSE_LESSONS_UPDATE_SUCCESS,
	    			'message_type'	=> 'success'
	    			);
			} catch (Exception $e) {
				return array(
		    		'status'	=> 'error2',
		    		'message'		=> __XCOURSE_LESSONS_UPDATE_ERROR,
		    		'message_type'	=> 'success'
		    		);
			}
		} else {
			return array(
		    	'status'	=> 'error1',
		    	'message'		=> __XCOURSE_LESSONS_UPDATE_ERROR,
		    	'message_type'	=> 'success'
		    	);
		}
	}
	public function getXcourseUsersSourceAction($token = null, $fields = null) {
		$this->getDatatableSource();
		exit;
	}
	public function getClassSchedulesAction($token = null, $fields = null) {
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
	public function saveClassSchedulesAction() {
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
				
				
			foreach($updateData['week_day'] as $index => $value) {
				$courseClass->appendSchedule(
				$updateData['week_day'][$index],
				$updateData['start'][$index],
				$updateData['end'][$index]
				);
			}
				
			foreach($insertData['week_day'] as $index => $value) {
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
	public function getXcoursesAction() {
		$smarty = $this->getSmartyVar();
		//$this->loadModule("xuser");
		$currentUser = $this -> getCurrentUser();

		$this->loadModule('xuser');
		$smarty->assign("T_USER_TYPE", $currentUser->getType());
		$smarty->assign("T_USER_EXTENDED_TYPE", $this->modules['xuser']->getExtendedTypeID($currentUser->getType()));

		if ($currentUser->getType() == "administrator") {
			return $this->getXcoursesActionForAdministrator();
		} elseif ($currentUser->getType() == "professor") {
		} elseif ($currentUser->getType() == "student") {
			return $this->getXcoursesActionForStudent();
		}
	}
	protected function getXcoursesActionForAdministrator() {
		$smarty = $this->getSmartyVar();

		if (isset($this->getCurrentUser() -> coreAccess['lessons']) && $this->getCurrentUser() -> coreAccess['lessons'] == 'hidden') {
			eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
		} else if (isset($this->getCurrentUser() -> coreAccess['lessons']) && $this->getCurrentUser() -> coreAccess['lessons'] != 'change') {
			$_change_ = false;
		} else {
			$_change_ = true;
		}
		$smarty -> assign("T_MODULE_XCOURSE_CANCHANGE", $_change_);

		$sortedColumns = array('name', 'location', 'num_students', 'num_lessons', 'num_skills', 'start_date', 'end_date', 'price_presencial', 'price_web', 'created', 'active', 'operations');

		$smarty -> assign("T_DATASOURCE_SORT_BY", array_search('active', $sortedColumns));
		$smarty -> assign("T_DATASOURCE_SORT_ORDER", 'desc');
		$smarty -> assign("T_DATASOURCE_OPERATIONS", array('statistics', 'settings', 'delete'));
		$smarty -> assign("T_DATASOURCE_COLUMNS", $sortedColumns);

		$constraints = array('archive' => false, 'instance' => false);
		$constraints['required_fields'] = array('has_instances', 'location', 'num_students', 'num_lessons', 'num_skills');

		$courses = MagesterCourse :: getAllCourses($constraints);
		$totalEntries = MagesterCourse :: countAllCourses($constraints);
		$dataSource = MagesterCourse :: convertCourseObjectsToArrays($courses);

		$smarty -> assign("T_XCOURSE_DATASOURCE", $dataSource);
		$smarty -> assign("T_XCOURSE_DATASOURCE_COUNT", $totalEntries);

		$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());
	}
	protected function getXcoursesActionForStudent() {
		$smarty = $this->getSmartyVar();

		$currentUser = $this->getCurrentUser();

		$courseContraints = array('archive' => false, 'active' => 'true', 'return_objects' => false);
		$userCourses = $currentUser->getUserCourses($courseContraints);
		if ($this->getEditedCourse()) {
			// CHECK IF COURSE IS IN USER LIST
				
			if (!array_key_exists($this->getEditedCourse()->course['id'], $userCourses)) {
				// ERROR : USER NOT IN THIS COURSE
				return false;
			}
			$selectCourse = $userCourses[$this->getEditedCourse()->course['id']];
			$redirect = true;
		} elseif (count($userCourses) == 1) {
			/// IF ONLY ONE COURSE, GO TO THEN
			$selectCourse = reset($userCourses);
				
			$redirect = true;
		}

		if ($redirect) {
			$url = $this->moduleBaseUrl . "&action=" . self::VIEW_COURSE_DASHBOARD	. "&xcourse_id=" . $selectCourse['id'];
			eF_redirect($url);
		}

		$smarty -> assign("T_XCOURSE_USER_LIST", $userCourses);


		/*
		 if (isset($this->getCurrentUser() -> coreAccess['lessons']) && $this->getCurrentUser() -> coreAccess['lessons'] == 'hidden') {
			eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
			} else if (isset($this->getCurrentUser() -> coreAccess['lessons']) && $this->getCurrentUser() -> coreAccess['lessons'] != 'change') {
			$_change_ = false;
			} else {
			$_change_ = true;
			}

			$smarty -> assign("T_MODULE_XCOURSE_CANCHANGE", $_change_);

			$sortedColumns = array('name', 'location', 'num_students', 'num_lessons', 'num_skills', 'start_date', 'end_date', 'price_presencial', 'price_web', 'created', 'active', 'operations');

			$smarty -> assign("T_DATASOURCE_SORT_BY", array_search('active', $sortedColumns));
			$smarty -> assign("T_DATASOURCE_SORT_ORDER", 'desc');
			$smarty -> assign("T_DATASOURCE_OPERATIONS", array('statistics', 'settings', 'delete'));
			$smarty -> assign("T_DATASOURCE_COLUMNS", $sortedColumns);

			$constraints = array('archive' => false, 'instance' => false);
			$constraints['required_fields'] = array('has_instances', 'location', 'num_students', 'num_lessons', 'num_skills');

			$courses = MagesterCourse :: getAllCourses($constraints);
			$totalEntries = MagesterCourse :: countAllCourses($constraints);
			$dataSource = MagesterCourse :: convertCourseObjectsToArrays($courses);

			$smarty -> assign("T_XCOURSE_DATASOURCE", $dataSource);
			$smarty -> assign("T_XCOURSE_DATASOURCE_COUNT", $totalEntries);

			$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());
			*/
	}
	protected function viewCourseDashboardAction($course_id = null) {
		$smarty = $this->getSmartyVar();

		$currentUser = $this->getCurrentUser();

		$courseContraints = array('archive' => false, 'active' => 'true', 'return_objects' => false);
		$userCourses = $currentUser->getUserCourses($courseContraints);

		if (!is_null($course_id)) {
			$this->getEditedCourse(null, $course_id);
		}

		if ($this->getEditedCourse()) {
			// CHECK IF COURSE IS IN USER LIST
			if (!array_key_exists($this->getEditedCourse()->course['id'], $userCourses)) {
				// ERROR : USER NOT IN THIS COURSE
				return false;
			}
			$selectCourse = $userCourses[$this->getEditedCourse()->course['id']];
		} elseif (count($userCourses) == 1) {
			// IF ONLY ONE COURSE, GO TO THEN
			$selectCourse = reset($userCourses);
			$redirect = true;
		}


		//		"courseware"
		//		"supplementary"
		$xProjectsModule 	= $this->loadModule("xprojects");
		//		"groups"
		//		"messages"
		$quickMailsModule 	= $this->loadModule("quick_mails");
		//		"tools"
		//		"academic_calendar"
		//		"avaliations"		=> '4',
		$onsyncModule 		= $this->loadModule("onsync");
		$xWebTutoriaModule 	= $this->loadModule("xwebtutoria");
		$xForumModule 		= $this->loadModule("xforum");
		$linksModule 		= $this->loadModule("links");

		$features = array(
			"courseware"		=> array('title' => 'Material Didático (Indisponível)'),
			"supplementary"		=> array('title' => 'Material Complementar (Indisponível)'),

			"projects"			=> $xProjectsModule->getCourseDashboardLinkInfo(),
			"groups"			=> array('title' => 'Grupos (Indisponível)'),
			
			"messages"			=> $quickMailsModule->getCourseDashboardLinkInfo(),
			"tools"				=> array('title' => 'Ferramentas (Indisponível)'),
			
			"academic_calendar"	=> $this->setDashboardMode('academic_calendar')->getCourseDashboardLinkInfo(),
			"avaliations"		=> array('title' => 'Avaliações (Indisponível)'),

			"video"				=> $onsyncModule->getCourseDashboardLinkInfo(),
			"web_tutoria"		=> $xWebTutoriaModule->getCourseDashboardLinkInfo(),
			
			"forum"				=> $xForumModule->getCourseDashboardLinkInfo(),
			"links"				=> $linksModule->getCourseDashboardLinkInfo(),
		);

		$smarty -> assign("T_XCOURSE_LIST_FEATURES", $features);

		$userObject = $this->getCurrentUser();

		if (($courseObject = $this->getEditedCourse()) != FALSE) {
			$this->addModuleData('edited_course', $this->getEditedCourse()->course);
			$courseObject = $this->getEditedCourse();
			if (($lessonObject = $this->getEditedLesson()) == FALSE) {
				// GET THE FIRST LESSON NOT COMPLETED
				$courseContraints = array('archive' => false, 'active' => 'true', 'return_objects' => false);
				$courseLessons = $courseObject->getCourseLessons();

				$userLessonsStatus = $userObject->getUserStatusInCourseLessons($courseObject);
				foreach($courseLessons as $key => $course) {
					if (!$userLessonsStatus[$key]->lesson['completed']) {
						$this->getEditedLesson(null, $key);
						break;
					}
				}
			}
			$this->addModuleData('edited_lesson', $this->getEditedLesson()->lesson);
		}

		$courseName = '<span class="username">' . $this->getEditedCourse()->course['name'] . '</span>';
		$smarty -> assign("T_XCOURSE_BLOCK_TITLE", sprintf(__XCOURSE_DASHBOARD_, $courseName));

		return true;
	}
	protected function viewAcademicCalendarAction() {
		$smarty = $this->getSmartyVar();

		$currentUser = $this->getCurrentUser();

		//
		//$userCourses = $currentUser->getUserCourses($courseContraints);

		//		$userObject = new MagesterStudent("aluno");
		$this->addModuleData('edited_course', $this->getEditedCourse()->course);
		$this->addModuleData('edited_lesson', $this->getEditedLesson()->lesson);
		/*
		 if (($courseObject = $this->getEditedCourse()) != FALSE) {
		 	
			$courseObject = $this->getEditedCourse();
			if (($lessonObject = $this->getEditedLesson()) == FALSE) {
			// GET THE FIRST LESSON NOT COMPLETED
			$courseContraints = array('archive' => false, 'active' => 'true', 'return_objects' => false);
			$courseLessons = $courseObject->getCourseLessons();

			$userLessonsStatus = $userObject->getUserStatusInCourseLessons($courseObject);
			foreach($courseLessons as $key => $course) {
			if (!$userLessonsStatus[$key]->lesson['completed']) {
			$this->getEditedLesson(null, $key);
			break;
			}
			}
			}
				
			}http://ult.com.br/newsletter/news_ult_ago.html
			*/
		$lessonName = '<span class="username">' . $this->getEditedLesson()->lesson['name'] . '</span>';
		$smarty -> assign("T_XCOURSE_BLOCK_TITLE", sprintf(__XCOURSE_VIEW_LESSON_ACADEMIC_CALENDAR_, $lessonName));

		if ($_GET['output'] == 'innerhtml') {
			$tpl = $this->moduleBaseDir . "templates/actions/" . $this->getCurrentAction() . ".tpl";
				
			$result = $smarty->fetch($tpl);
			echo $result;
			exit;
		}

		/*
		 if ($this->getEditedCourse(null, $course_id)) {
			// CHECK IF COURSE IS IN USER LIST
			$userCourses = $currentUser->getUserCourses($courseContraints);
				
			if (!array_key_exists($this->getEditedCourse()->course['id'], $userCourses)) {
			// ERROR : USER NOT IN THIS COURSE
			return false;
			}
			$selectCourse = $userCourses[$this->getEditedCourse()->course['id']];
			} elseif (count($userCourses) == 1) {
			// IF ONLY ONE COURSE, GO TO THEN
			$selectCourse = reset($userCourses);
			$redirect = true;
			}

			if ($redirect) {
			$url = $this->moduleBaseUrl . "&action=" . self::VIEW_COURSE_DASHBOARD	. "&xcourse_id=" . $selectCourse['id'];
			eF_redirect($url);
			}


			$onsyncModule 		= $this->loadModule("onsync");
			//			"courseware"		=> '2',
			//			"supplementary"		=> '3',
			//			"avaliations"		=> '4',
			//			"tools"				=> '5',
			//			"groups"			=> '6',
			$xForumModule 		= $this->loadModule("xforum");
			$xWebTutoriaModule 	= $this->loadModule("xwebtutoria");
			$linksModule 		= $this->loadModule("links");

			//			"academic_calendar"	=> '10',
			$xProjectsModule 	= $this->loadModule("xprojects");
			//			"messages"			=> '12'  *** DISABLED

			$features = array(
			"video"				=> $onsyncModule->getCourseDashboardLinkInfo(),
			"courseware"		=> array('title' => 'Material Didático (Indisponível)'),
			"supplementary"		=> array('title' => 'Material Complementar (Indisponível)'),
			"avaliations"		=> array('title' => 'Avaliações (Indisponível)'),
			"tools"				=> array('title' => 'Ferramentas (Indisponível)'),
			"groups"			=> array('title' => 'Grupos (Indisponível)'),
			"forum"				=> $xForumModule->getCourseDashboardLinkInfo(),
			"web_tutoria"		=> $xWebTutoriaModule->getCourseDashboardLinkInfo(),
			"links"				=> $linksModule->getCourseDashboardLinkInfo(),
			"academic_calendar"	=> $this->setDashboardMode('academic_calendar')->getCourseDashboardLinkInfo(),
			"projects"			=> $xProjectsModule->getCourseDashboardLinkInfo(),
			"messages"			=> array('title' => 'Messages (Indisponível)')
			);

			$smarty -> assign("T_XCOURSE_LIST_FEATURES", $features);
			*/
	}
	public function addXcourseAction() {
		//$this->makeEditCourseOptions();

		if ( $this->makeBasicForm() ) {
			$this->appendTemplate(
			array(
	            	'title'			=> __XCOURSE_EDITBASICXCOURSE,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.form.basic.tpl",
	            	'contentclass'	=> ''
	            	)
	            	);
		}
	}
	public function editXcourseAction() {
		if ( $this->makeBasicForm() ) {
			$this->appendTemplate(array(
	           	'title'			=> __XCOURSE_EDITBASICXCOURSE,
	           	'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.form.basic.tpl",
	          	'contentclass'	=> ''
	          	));

	          	if ( $this->makeCourseLessonsList() ) {
	          		$this->appendTemplate(array(
			       	'title'			=> __XCOURSE_EDITXCOURSELESSONS,
			       	'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.list.lessons.tpl",
			      	'contentclass'	=> '',
			       	'class'			=> 'no_padding_color no_padding'
			       	));
	          	}
	          	 
	          	if ( $this->makeCourseUsersList() ) {
	          		$this->appendTemplate(array(
			       	'title'			=> __XCOURSE_EDITXCOURSEUSERS,
			       	'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.list.users.tpl",
			      	'contentclass'	=> '',
			       	'class'			=> 'no_padding_color no_padding'
			       	));
	          	}
	          	if ( $this->makeCourseClassesForm() ) {
	          		$this->appendTemplate(array(
            		'title'			=> __XCOURSE_EDITXCOURSECLASSES,
            		'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.list.classes.tpl",
            		'contentclass'	=> '',
            		'class'			=> 'no_padding_color no_padding'
            		));
	          	}
	          	/*
	          	 if ( $this->makeAcademicCalendarForm() ) {
	          	 $this->appendTemplate(array(
	          	 'title'			=> __XCOURSE_EDITXCOURSEACADEMICCALENDAR,
	          	 'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.list.academic_calendar.tpl",
	          	 'contentclass'	=> '',
	          	 'class'			=> ''
	          	 ));
	          	 }

	          	 */

	          	$this->addModuleData('edited_course', $this->getEditedCourse()->course);
		}
	}
	public function editXcourseCalendarAction() {
		if ( $this->makeAcademicCalendarForm() ) {
			$this->appendTemplate(array(
           		'title'			=> __XCOURSE_EDITXCOURSEACADEMICCALENDAR,
           		'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.list.academic_calendar.tpl",
           		'contentclass'	=> '',
           		'class'			=> ''
           		));
		}
		$this->addModuleData('edited_course', $this->getEditedCourse()->course);
	}
	public function updateLessonsOrderAction() {
		$fields = $_POST;

		if (eF_checkParameter($fields['xcourse_id'], 'id')) {
			$this->getEditedCourse(null, $fields['xcourse_id']);
		}

		if ($this->getEditedCourse() && is_array($fields['lessonid'])) {
			$fieldsOrder = $fields['lessonid'];
			$reverseOrder = array_reverse($fieldsOrder);
				
			$courseID = $this->getEditedCourse()->course['id'];
				
			foreach($reverseOrder as $key => $item_id) {

				$item_id = str_replace("lessonid_", "", $item_id);

				if (array_key_exists($key + 1, $reverseOrder)) {
					$previous_lessons_ID = str_replace("lessonid_", "", $reverseOrder[$key + 1]);
				} else {
					$previous_lessons_ID = 0;
				}
				eF_updateTableData(
					"lessons_to_courses", 
				array('previous_lessons_ID' => $previous_lessons_ID),
				sprintf("courses_ID = %d AND lessons_ID = %d", $courseID, $item_id)
				);
			}
			$result = array(
				'message'		=> __XCOURSE_LESSON_ORDER_UPDATE_SUCCESS,
				'message_type'	=> 'success' 
				);
					
		} else {
			$result = array(
				'message'		=> __XCOURSE_LESSON_ORDER_UPDATE_ERROR,
				'message_type'	=> 'failure' 
				);
		}
		echo json_encode($result);
		exit;
	}
	public function updateAcademicCalendarSeriesAction($fields = null) {
		if (is_null($fields)) {
			$fields = $_POST;
		}


		if (eF_checkParameter($fields['course_id'], 'id')) {
			$this->getEditedCourse(null, $fields['course_id']);
		}

		if (
		$this->getEditedCourse() &&
		eF_checkParameter($fields['lesson_id'], 'id') &&
		eF_checkParameter($fields['classe_id'], 'id') &&
		eF_checkParameter($fields['serie_id'], 'id')
		) {
			if (
			strtotime($fields['start_date']) !== FALSE &&
			strtotime($fields['end_date']) !== FALSE
			) {
				$result = eF_updateTableData(
					"module_xcourse_lesson_class_calendar_series",
				array(
						'start_date'	=> $fields['start_date'],
						'end_date'		=> $fields['end_date']
				),
				sprintf("course_id = %d AND lesson_id = %d AND classe_id = %d AND serie_id = %d",
				$this->getEditedCourse()->course['id'],
				$fields['lesson_id'],
				$fields['classe_id'],
				$fields['serie_id']
				)
				);
				$result = array(
					'message'		=> __XCOURSE_UPDATE_ACADEMIC_CALENDAR_SUCCESS,
					'message_type'	=> 'success' 
					);
			} else { // INVALID DATES
				$result = array(
					'message'		=> __XCOURSE_ERROR_INVALID_DATES,
					'message_type'	=> 'failure' 
					);
			}
		} else {
			$result = array(
				'message'		=> __XCOURSE_ERROR_INVALID_PARAMETERS,
				'message_type'	=> 'failure'
				);
		}
		echo json_encode($result);
		exit;
	}
	public function getAcademicCalendarDataAction() {
		if ($this->getEditedCourse() && eF_checkParameter($_GET['xclasse_id'], 'id')) {

			$fields = array(
				"cla_series.course_id", 
				"cla_series.lesson_id", 
				"cla_series.classe_id", 
				"cla_series.serie_id", 
				"series.name as series", 
				"cla_series.start_date", 
				"cla_series.end_date"
				);
					
				$tables = "module_xcourse_lesson_class_calendar_series cla_series
			LEFT JOIN module_xcourse_lesson_class_series as series ON (cla_series.serie_id = series.id)";
					
				$course_id = $this->getEditedCourse()->course['id'];
				$class_id = $_GET['xclasse_id'];
					
				$wheres = array();
				$wheres[] = "cla_series.course_id = " . $course_id;
				$wheres[] = "cla_series.classe_id = " . $class_id;
					
				$showLessons = array();
					
				if ($this->getEditedLesson() != FALSE) {
					$lesson_id = $this->getEditedLesson()->lesson['id'];
					$wheres[] = "cla_series.lesson_id = " . $lesson_id;

					//$lesson_calendar = true;

					$showLessons[] = $this->getEditedLesson();
				} else {
					// GET COURSE LESSONS
					$constraints = array('archive' => false, 'active' => true, 'return_objects' => true);
					$showLessons[] = $this->getEditedCourse()->getCourseLessons($constraints);
					//$lesson_calendar = false;
				}

				$orders = array(
				"start_date ASC", 
				"end_date ASC"
				);
					
				$result = eF_getTableData(
				$tables,
				implode(",", $fields),
				implode(" AND ", $wheres),
				implode(",", $orders)
				);
				/*
				 $resultSeries = array();
				 	
				 foreach($showLessons as $lessonObj) {

				 $resultItem = array(
					"id"		=> $lessonObj->lesson['id'],
					'name'		=> $lessonObj->lesson['name'],
					'series'	=> array()
					);
					foreach($result as $item) {
					if ($item['lesson_id'] == $lessonObj->lesson['id']) {
					$resultItem['series'][] = array(
					'id'	=> $item['serie_id'],
					'name'	=> $item['series'],
					'start'	=> $item['start_date'],
					'end'	=> $item['end_date']
					);
					}
					}
					$resultSeries[] = $resultItem;
					}
					*/
				$resultSeries = array();
					
				foreach($showLessons as $lessonObj) {
					foreach($result as $item) {
						if ($item['lesson_id'] == $lessonObj->lesson['id']) {

							$resultItem = array(
							"id"		=> $item['serie_id'], //$lessonObj->lesson['id'],
							//'name'		=> $item['series'], //$lessonObj->lesson['name'],
							'series'	=> array()
							);

							$resultItem['series'][] = array(
							'id'	=> $item['serie_id'],
							'name'	=> $item['series'],
							'start'	=> $item['start_date'],
							'end'	=> $item['end_date']
							);

							$resultSeries[] = $resultItem;
						}
					}

				}
					
					
				echo json_encode($resultSeries);
				exit;
		}
		return false;
	}


	/* MAIN-INDEPENDENT MODULE INFO, PAGES, TEMPLATES, ETC... */
	protected function setDashboardMode($mode) {
		$this->_dashboardMode = $mode;

		return $this;
	}
	public function getCourseDashboardLinkInfo() {
		$course_id = $this->getEditedCourse()->course['id'];
		 
		switch ($this->_dashboardMode) {
			case "academic_calendar" : {
				return array(
					'title' 		=> __XCOURSE_ACADEMIC_CALENDAR_NAME,
		        	'image'			=> "images/others/transparent.gif",
					'image_class'	=> "sprite32 sprite32-schedule",
		            'link'  		=> $this -> moduleBaseUrl . "&action=view_academic_calendar&xcourse_id=" . $course_id 
				);
			}
			default : {
				return false;
			}
		}

	}

	/* UTILITY FUNCTIONS */
	private function makeBasicForm() {
		$smarty = $this->getSmartyVar();
		$selectedAction = $this->getCurrentAction();
		 
		if (isset($currentUser -> coreAccess['lessons']) && $currentUser -> coreAccess['lessons'] == 'hidden') {
			eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
		} else if (isset($currentUser -> coreAccess['lessons']) && $currentUser -> coreAccess['lessons'] != 'change') {
			$_change_ = false;
		} else {
			$_change_ = true;
		}

		$form = new HTML_QuickForm("add_courses_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
		$form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
		$form -> addElement('text', 'name', _COURSENAME, 'class = "full"');
		$form -> addRule('name', _THEFIELD.' "'._COURSENAME.'" '._ISMANDATORY, 'required', null, 'client');
		//$form -> addRule('name', _INVALIDFIELDDATA, 'checkParameter', 'text');
			
		$schools = eF_getTableDataFlat("module_ies", "id, nome", "active = 1" );

		if (count($schools) > 0) {
			$schools = array_merge(
			array(-1 => __SELECT_ONE_OPTION),
			array_combine($schools['id'], $schools['nome'])
			);
		} else {
			$schools = array(-1 => __NO_DISPONIBLE_OPTIONS);
		}
		 
		$form -> addElement('select', 'ies_id', __IES_FORM_NAME, $schools, 'class = "full"');

		try {
			$directionsTree = new MagesterDirectionsTree();
			if (sizeof($directionsTree -> tree) == 0) {
				eF_redirect(basename($_SERVER['PHP_SELF']).'?ctg=directions&add_direction=1&message='.urlencode(_TOCREATECOURSEYOUMUSTFIRSTCREATECATEGORY).'&message_type=failure');
			}
			$directions = $directionsTree -> toPathString();
		} catch (Exception $e) {
			handleNormalFlowExceptions($e);
		}
		$form -> addElement('select', 'directions_ID', _DIRECTION, $directions, 'class = "full"'); //Append a directions select box to the form
			
		if ($GLOBALS['configuration']['onelanguage'] != true) {
			$languages = MagesterSystem :: getLanguages(true, true);
			$form -> addElement('select', 'languages_NAME', _LANGUAGE, $languages, 'class = "full"');
		}

		$form -> addElement('advcheckbox', 'enable_start_date', __XCOURSE_FORM_ENABLE_START_DATE, null, null, array(0, 1));
		$form -> addElement('jquerydate', 'start_date', __XCOURSE_FORM_START_DATE);
		$form -> addElement('advcheckbox', 'enable_end_date', __XCOURSE_FORM_ENABLE_END_DATE, null, null, array(0, 1));
		$form -> addElement('jquerydate', 'end_date', __XCOURSE_FORM_END_DATE);

		$form -> addElement('advcheckbox', 'active', _ACTIVEFEM, null, null, array(0, 1));
		$form -> addElement('advcheckbox', 'show_catalog', _SHOWCOURSEINCATALOG, null, null, array(0, 1));
		$form -> addElement('text', 'price', __XCOURSE_FORM_PRICE, 'class = "small" alt="decimal"');

		$form -> addElement('textarea', 'terms', __XCOURSE_FORM_TERMS, 'class = "full"');

		/* PRICE BY MODALIDADE */
		/*
		 $modalidades = MagesterCourse::getModalidades();

		 foreach($modalidades as $groupName => $group) {
			$elems = array();
			foreach($group['fields'] as $fieldName => $field) {
			$elems[] = $form -> addElement($field['type'], $fieldName, $field['label'], $field['attr'], null, $field['options']);
			$form -> setDefaults(array($fieldName => $field['default']));
			}
			//$form->addGroup($elems, $groupName, $group['groupLabel'], ' ');
			}
			*/
		/*

		$form -> addElement('text', 'training_hours', _TRAININGHOURS, 'class = "inputText" style = "width:50px"');


		$recurringOptions = array(0 => _NO, 'D' => _DAILY, 'W' => _WEEKLY, 'M' => _MONTHLY, 'Y' => _YEARLY);
		$recurringDurations = array('D' => array_combine(range(1, 90), range(1, 90)),
		'W' => array_combine(range(1, 52), range(1, 52)),
		'M' => array_combine(range(1, 24), range(1, 24)),
		'Y' => array_combine(range(1, 5), range(1, 5))); //Imposed by paypal interface
		$form -> addElement('select', 'recurring', _SUBSCRIPTION, $recurringOptions, 'onchange = "$(\'duration_row\').show();$$(\'span\').each(function (s) {if (s.id.match(\'_duration\')) {s.hide();}});if (this.selectedIndex) {$(this.options[this.selectedIndex].value+\'_duration\').show();} else {$(\'duration_row\').hide();}"');
		$form -> addElement('select', 'D_duration', _DAYSCONDITIONAL, $recurringDurations['D']);
		$form -> addElement('select', 'W_duration', _WEEKSCONDITIONAL, $recurringDurations['W']);
		$form -> addElement('select', 'M_duration', _MONTHSCONDITIONAL, $recurringDurations['M']);
		$form -> addElement('select', 'Y_duration', _YEARSCONDITIONAL, $recurringDurations['Y']);
		$form -> addElement('text', 'calendar_event', _CALENDAREVENT, 'class = "inputText"');
		$form -> addElement('text', 'max_users', _MAXIMUMUSERS, 'class = "inputText" style = "width:50px"');
		$form -> addElement('text', 'duration', _AVAILABLEFOR, 'style = "width:50px;"');
		$form -> addRule('duration', _THEFIELD.' "'._AVAILABLEFOR.'" '._MUSTBENUMERIC, 'numeric', null, 'client');
		*/

		if ($selectedAction == self::EDIT_XCOURSE) {
			$editCourse = new MagesterCourse($_GET['xcourse_id']);
			$smarty -> assign('T_EDIT_COURSE', $editCourse);
			$form -> setDefaults($editCourse -> options);
			$form -> setDefaults($editCourse -> course);
			$form -> setDefaults(array($editCourse -> options['recurring'].'_duration' => $editCourse -> options['recurring_duration']));

			if ($editCourse->course['start_date'] != 0) {
				$form -> setDefaults(array(
						'enable_start_date'	=> 1,
						'start_date'	=> date('d/m/Y', $editCourse->course['start_date'])
				));
			}
			if ($editCourse->course['end_date'] != 0) {
				$form -> setDefaults(array(
						'enable_end_date'	=> 1,
						'end_date'	=> date('d/m/Y', $editCourse->course['end_date'])
				));
			}
		} else {
			$form -> setDefaults(array(
					'active' 			=> 1,
					'ies_id'			=> -1,
					'show_catalog' 		=> 1,
					'price' 			=> 0,
					'enable_start_date'	=> 1,
					'start_date'		=> date('Y-m-d', mktime(0,0,0, date('m')+1, 01, date('Y'))) ,
					'enable_end_date'	=> 1,
					'end_date'			=> date('Y-m-d', mktime(0,0,0, date('m')+1, 0, date('Y')+1)),
					'languages_NAME'	=> $GLOBALS['configuration']['default_language']
			));
		}
			
		if (!$_change_) {
			$form -> freeze();
		} else {
			$form -> addElement('submit', 'submit_xcourse', _MODULE_XCOURSES_SAVE, 'class = "button_colour round_all"');
			if ($form -> isSubmitted() && $form -> validate()) {
					
				$values = $form -> exportValues();
					
				$fields = array(
						'name' => $form -> exportValue('name'),
						'ies_id' => $form -> exportValue('ies_id'),
						'directions_ID' => $form -> exportValue('directions_ID'),
						'languages_NAME' => $GLOBALS['configuration']['onelanguage'] ? $GLOBALS['configuration']['default_language'] : $form -> exportValue('languages_NAME'),
						'active' => $form -> exportValue('active'),
						'show_catalog' => $form -> exportValue('show_catalog'),
						'price' => $form -> exportValue('price'),
						'terms' => $form -> exportValue('terms')
				//'duration'	   	 => $form -> exportValue('duration') ? $form -> exportValue('duration') : null,
				//'max_users' => $form -> exportValue('max_users') ? $form -> exportValue('max_users') : null,
				//'supervisor_LOGIN' => $values['supervisor_LOGIN'] ? $values['supervisor_LOGIN'] : null
				);
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
				if ($values['enable_start_date'] == 1) {
					$fields['start_date'] = date_create_from_format($date_format, $values['start_date'])->format("U");
				} else {
					$fields['start_date'] = null;
				}
				if ($values['enable_end_date'] == 1) {
					$fields['end_date'] = date_create_from_format($date_format, $values['end_date'])->format("U");
				} else {
					$fields['end_date'] = null;
				}

					
				/*
					foreach($modalidades as $groupName => $group) {
					foreach($group['fields'] as $fieldName => $field) {
					$fields[$fieldName] = $form -> exportValue($fieldName);
					}
					}
					*/
					
					
				try {
					if ($selectedAction == self::EDIT_XCOURSE) {
						/*
							if ($fields['directions_ID'] != $editCourse -> course['directions_ID']) {
							$updateCourseInstancesCategory = true; //This means we need to update instances to match the course's new category
							}
							*/
						$editCourse -> course = array_merge($editCourse -> course, $fields);
						/*
							if ($courseSk = $editCourse -> getCourseSkill()) {
							eF_updateTableData("module_hcd_skills", array("description" => _KNOWLEDGEOFCOURSE . " " .$form -> exportValue('name')), "skill_ID = " .$courseSk['skill_ID']) ;
							}
							*/
						$message = _COURSEUPDATED;
						$redirect = $this->moduleBaseUrl . "&action=" . self::EDIT_XCOURSE . "&xcourse_id=".$editCourse -> course['id']."&message=".urlencode(_COURSEUPDATED)."&message_type=success";
					} else {
						$editCourse = MagesterCourse :: createCourse($fields);
							
						$message = _SUCCESFULLYCREATEDCOURSE;
						$redirect = $this->moduleBaseUrl . "&action=" . self::EDIT_XCOURSE . "&xcourse_id=".$editCourse -> course['id']."&message=".urlencode(_SUCCESFULLYCREATEDCOURSE)."&message_type=success";
					}

					$message_type = 'success';
					/*
						if ($form -> exportValue('price') && $form -> exportValue('recurring') && in_array($form -> exportValue('recurring'), array_keys($recurringOptions))) {
						$editCourse -> options['recurring'] = $form -> exportValue('recurring');
						if ($editCourse -> options['recurring']) {
						$editCourse -> options['recurring_duration'] = $form -> exportValue($editCourse -> options['recurring'].'_duration');
						}
						} else {
						unset($editCourse -> options['recurring']);
						}
						*/
					//$editCourse -> course['instance_source'] OR $editCourse -> options['course_code'] = $form -> exportValue('course_code');	//Instances don't have a code of their own
					//$editCourse -> options['training_hours'] = $form -> exportValue('training_hours');
					//$editCourse -> options['duration'] = $form -> exportValue('duration') ? $form -> exportValue('duration') : null;
					//$editCourse -> options['course_code'] 	 = $form -> exportValue('course_code') ? $form -> exportValue('course_code') : null;
					//$start_date = mktime(0, 0, 0, $_POST['date_Month'], $_POST['date_Day'], $_POST['date_Year']);

					$editCourse -> persist();
					/*
						if (isset($updateCourseInstancesCategory) && $updateCourseInstancesCategory) {
						eF_updateTableData("courses", array("directions_ID" => $editCourse -> course['directions_ID']), "instance_source=".$editCourse -> course['id']);
						}
						*/
					/*
						if ($form -> exportValue('branches_ID') && eF_checkParameter($form -> exportValue('branches_ID'), 'id')) {
						$result = eF_getTableDataFlat("module_hcd_course_to_branch", "branches_ID", "courses_ID=".$editCourse -> course['id']);
						if (sizeof($result['branches_ID']) == 0) {
						eF_insertTableData("module_hcd_course_to_branch", array("branches_ID" => $form -> exportValue('branches_ID'), "courses_ID" => $editCourse -> course['id']));
						} elseif (sizeof($result) == 1) {
						//Only one branch associated with this course, as a 'location'
						eF_updateTableData("module_hcd_course_to_branch", array("branches_ID" => $form -> exportValue('branches_ID')), "courses_ID=".$editCourse -> course['id']);
						}
						} else {
						}
						*/
					!isset($redirect) OR eF_redirect($redirect);
				} catch (Exception $e) {
					handleNormalFlowExceptions($e);
				}
			}
		}

		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
		$smarty -> assign('T_MODULE_XCOURSE_BASIC_FORM', $renderer -> toArray());
			
		/*
		 $modules = eF_loadAllModules(true);

		 $templates = array();
		 // ADD / EDIT COURSE
		 $templates[] = array(
		 'title'			=> __XCOURSE_EDITBASICXCOURSE,
		 'template'		=> $this->moduleBaseDir . "templates/includes/xcourse.form.basic.tpl",
		 'contentclass'	=> ''
		 );
		 */
		return true;
	}
	private function makeCourseLessonsList() {
		if ($this->getCurrentAction() == self::EDIT_XCOURSE) {

			$smarty = $this->getSmartyVar();

			$editCourse = $this->getEditedCourse();
			//$courseUsers = $editCourse -> countCourseUsers(array('archive' => false));
			//$smarty -> assign("T_COURSE_HAS_USERS", $courseUsers['count']);
			//	echo '<pre>';

			$constraints = array('archive' => false, 'active' => true, 'sort' => 'has_lesson', 'order' => 'desc') /* + createConstraintsFromSortedTable()*/;
				
			$lessons = $editCourse -> getCourseLessonsIncludingUnassigned($constraints);
			$totalEntries = $editCourse -> countCourseLessonsIncludingUnassigned($constraints);
			$dataSource = MagesterLesson :: convertLessonObjectsToArrays($lessons);

			$directionsTree = new MagesterDirectionsTree();
			$directionsPaths = $directionsTree -> toPathString();
			foreach ($dataSource as $key => $value) {
				$dataSource[$key]['directionsPath'] = $directionsPaths[$value['directions_ID']];
				$dataSource[$key]['mode'] = 'shared';
				/*
				 if ($value['instance_source']) {
					if ($value['originating_course'] == $editCourse -> course['id'] && $value['has_lesson']) {
					$dataSource[$key]['mode'] = 'unique';
					$lessonsToRemove[] = $value['instance_source'];
					} else {
					$lessonsToRemove[] = $key;
					}
					}
					*/
			}
			foreach ($lessonsToRemove as $value) { //Lesson instances that should not display in courses list
				unset($dataSource[$value]);
				$totalEntries--;
			}

			//		echo '</pre>';
			//$tableName = $_GET['ajax'];
			//$alreadySorted = 1;
			//$smarty -> assign("T_TABLE_SIZE", $totalEntries);
			//include("sorted_table.php");
				
			$smarty -> assign ("T_" . $this->getName() . '_LESSONS_LIST', $dataSource);
			return true;
		}
		return false;
	}
	private function makeCourseUsersList() {
		$smarty = $this->getSmartyVar();
		/*
		 $roles = MagesterLessonUser :: getLessonsRoles(true);
		 $smarty -> assign("T_ROLES", $roles);

		 $rolesBasic = MagesterLessonUser :: getLessonsRoles();
		 $smarty -> assign("T_BASIC_ROLES_ARRAY", $rolesBasic);

		 $constraints = array('archive' => false, 'active' => 1, 'return_objects' => false);
		 $xcourseUsers = $editCourse -> getCourseUsersIncludingUnassigned($constraints);

		 $smarty -> assign ("T_XCOURSE_USERS_LIST", $xcourseUsers);
		 */
		if ($this->getCache('xcourse_class_id') !== FALSE) {
			$js_module_data['xcourse_class_id'] = $this->getCache('xcourse_class_id');
		} else {
			$this->setCache('xcourse_class_id', ($js_module_data['xcourse_class_id'] = -1));
		}
		$this->addModuleData('xcourse.class_id', $this->getCache('xcourse.class_id'));

		$userClassesFilters = array(
		-1 	=> __XCOURSE_USERS_WITH_OR_WITHOUT_CLASS,
		0	=> __XCOURSE_USERS_WITHOUT_CLASS
		);

		// COURSE CLASSES (TURMAS)
		$constraints = array('archive' => false, 'active' => true);

		$classes = $this->getEditedCourse() -> getCourseClasses($constraints);
		$totalEntries = $this->getEditedCourse()  -> countCourseClasses($constraints);
		$xcourseClasses = MagesterCourseClass :: convertClassesObjectsToArrays($classes);

		foreach($xcourseClasses as $classe) {
			if ($classe['active'] == 1) {
				$userClassesFilters[$classe['id']] = $classe['name'];
			}
		}

		$smarty -> assign("T_USER_CLASSES_FILTERS", $userClassesFilters);

		return true;
	}
	private function makeCourseClassesForm() {
		 
		$smarty = $this->getSmartyVar();
		 
		if (isset($currentUser -> coreAccess['lessons']) && $currentUser -> coreAccess['lessons'] == 'hidden') {
			eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
		} else if (isset($currentUser -> coreAccess['lessons']) && $currentUser -> coreAccess['lessons'] != 'change') {
			$_change_ = false;
		} else {
			$_change_ = true;
		}
		 
		 
		$formClass = new HTML_QuickForm(
			"add_courseclass_form", 
			"post", 
		$_SERVER['REQUEST_URI'] . "#" . urlencode(clearStringSymbols(__XCOURSE_EDITXCOURSECLASSES)),
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
		 
		$formClass -> addElement('submit', 'submit_xcourse_class', _SUBMIT);

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
					$redirect = $this->moduleBaseUrl . "&action=" . self::EDIT_XCOURSE . "&xcourse_id=".$this->getEditedCourse() -> course['id']."&message=".urlencode(__XCOURSE_CLASS_UPDATE_MESSAGE)."&message_type=success#" . __XCOURSE_EDITXCOURSECLASSES;
				} else {
					$editCourseClass = MagesterCourseClass:: createCourseClass($fields);
					$redirect = $this->moduleBaseUrl . "&action=" . self::EDIT_XCOURSE . "&xcourse_id=".$this->getEditedCourse() -> course['id']."&message=".urlencode(__XCOURSE_CLASS_INSERT_MESSAGE)."&message_type=success#" . __XCOURSE_EDITXCOURSECLASSES;
				}

				!isset($redirect) OR eF_redirect($redirect);

				//$smarty -> assign("T_REDIRECT_PARENT_TO", basename($_SERVER['PHP_SELF'])."?ctg=courses&edit_course=" . $course->course['id'] );
				// RELOAD CLASS LIST
			}
		}
		$classes = $this->getEditedCourse() -> getCourseClasses($constraints);
		$totalEntries = $this->getEditedCourse() -> countCourseClasses($constraints);
		$xcourseClasses = MagesterCourseClass :: convertClassesObjectsToArrays($classes);


		$defaults = MagesterCourseClass :: getDefaultCourseClassValues();
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
		$smarty -> assign('T_XCOURSE_CLASS_FORM', $rendererClass -> toArray());

		$smarty -> assign ("T_XCOURSE_CLASSES_LIST", $xcourseClasses);

		return true;
	}
	private function makeAcademicCalendarForm() {
		$smarty = $this->getSmartyVar();
		/*
		 $options = array();
		 	
		 $options[] = array(
			'text' 		=> __XENROLLMENT_REGISTER,
			'hint'		=> __XENROLLMENT_REGISTER_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . '' . "&xuser_id=" . $_GET['xuser_id'] . "&xuser_login=" . $_GET['xuser_login']
			);
				
			$options[] = array(
			'text' 		=> __XENROLLMENT_CHECK_ENROLLMENTS,
			'hint'		=> __XENROLLMENT_CHECK_ENROLLMENTS_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . ''
			);
			*/
		$editCourse = $this->getEditedCourse();

		// COURSE CLASSES (TURMAS)
		$constraints = array('archive' => false, 'active' => true, 'sort' => 'name', 'order' => 'asc', 'return_objects' => false);
		$xcourseClasses = $this->getEditedCourse() -> getCourseClasses($constraints);
		$courseClassesData[0] = __SELECT_ONE_OPTION;
		foreach($xcourseClasses as $classe) {
			if ($classe['active'] == 1) {
				$courseClassesData[$classe['id']] = $classe['name'];
			}
		}


		//$courseTree = new MagesterCourseTree();
		//$smarty -> assign("T_USER_CLASSES_FILTERS", $userClassesFilters);

		$formUrl = $_SERVER['REQUEST_URI'];

		$filterForm = new HTML_QuickForm(__CLASS__ . "_view_invoices_status_form_filter", "post", $formUrl, null, null, true);

		$filterForm -> addElement('hidden', 'course_id');
		$filterForm -> addElement('select', 'classe_filter', __XCOURSE_FORM_SELECTCLASS, $courseClassesData, 'class = ""');

		/*
		 $filterForm -> addElement('jquerydate', 'start_date', __XCOURSE_FORM_SELECT_STARTDATE, 'class = "no-button"');
		 $filterForm -> addElement('jquerydate', 'end_date', __XCOURSE_FORM_SELECT_ENDDATE, 'class = "no-button"');
		 */
		//		$filterForm -> addElement('select', 'lesson_filter', __XCOURSE_FORM_SELECTLESSON, $lessonsData, 'class = ""');
		/*
		$filterForm -> addElement('submit', 'submit_apply', __XCOURSE_FORM_SUBMIT);

		$filterForm->setDefaults(array(
		'course_id'	=> $editCourse->course['id']
		));
		*/

		if ($editCourse) {
			$this->setCache("selected_course_id", $editCourse->course['id']);
		}

		if (eF_checkParameter($_GET['xclasse_id'], 'id')) {
			$this->setCache("selected_class_id", $_GET['xclasse_id']);
			$filterForm->setDefaults(array(
				'classe_filter' => $_GET['xclasse_id']
			));
				
		} else {
			if (count($courseClassesData) == 1) {
				reset($courseClassesData);
				$classe_id = key($courseClassesData);

				$url = $this->moduleBaseUrl . "&action=" . $this->getCurrentAction() . "&xcourse_id=" . $editCourse->course['id'] . "&xclasse_id=" . $classe_id;
				eF_redirect($url);
				exit;
			}
		}

		if ($filterForm -> isSubmitted() && $filterForm -> validate()) {
			// SAVE DATA, AND MAKE TO VIEW GANTT CHART
			$values = $filterForm->exportValues();
				
			$fields = array(
				'course_id'	=> $this->getCache("selected_course_id"),
				'classe_id'	=> $this->getCache("selected_class_id")
			);
				
			foreach($_POST['start_date'] as $lesson_id => $start_date) {
				$end_date = $_POST['end_date'][$lesson_id];

				if (!empty($start_date) || !empty($end_date)) {
					// CHECK NOW END DATE
					$updateFields = array();
					if (date_create_from_format('d/m/Y', $start_date) !== FALSE) {
						$startDateObject = date_create_from_format('d/m/Y', $start_date);
						$updateFields['start_date']	= $startDateObject->format('Y-m-d');
					}
					if (date_create_from_format('d/m/Y', $end_date) !== FALSE) {
						$endDateObject = date_create_from_format('d/m/Y', $end_date);
						$updateFields['end_date'] = $endDateObject->format('Y-m-d');
					}
						
					if ($startDateObject->format('u') > $endDateObject->format('u')) {
						$this->setMessageVar(__XCOURSE_START_DATE_GREATER_THAN_END_DATE, 'failure');
					}
						
						
					$result = eF_countTableData(
						"module_xcourse_lesson_class_calendar",
						"*",
					sprintf(
							"course_id = %d AND lesson_id = %d AND classe_id = %d",
					$fields['course_id'], $lesson_id, $fields['classe_id']
					)
					);
					if ($result[0]['count'] > 0) {
						// UPDATE
						$result = eF_updateTableData(
							"module_xcourse_lesson_class_calendar",
						$updateFields,
						sprintf(
								"course_id = %d AND lesson_id = %d AND classe_id = %d",
						$fields['course_id'], $lesson_id, $fields['classe_id']
						)
						);
						$this->setMessageVar(__XCOURSE_CALENDAR_UPDATED, 'success');
					} else {
						$updateFields['course_id'] = $fields['course_id'];
						$updateFields['lesson_id'] = $lesson_id;
						$updateFields['classe_id'] = $fields['classe_id'];

						$result = eF_insertTableData(
							"module_xcourse_lesson_class_calendar",
						$updateFields
						);
						$this->setMessageVar(__XCOURSE_CALENDAR_UPDATED, 'success');
					}
						
						
					// SAVE COURSE/LESSONS?CLASS/SERIES DATA
						
					//eF_deleteTableData($table)
						
					$insertFields = array(
						'course_id' => $this->getCache("selected_course_id"),
						'lesson_id' => $lesson_id,
						'classe_id'	=> $this->getCache("selected_class_id")
					);
						
					eF_deleteTableData("module_xcourse_lesson_class_calendar_series",
					sprintf("course_id = %d AND lesson_id = %s AND classe_id = %d",
					$insertFields['course_id'],
					$insertFields['lesson_id'],
					$insertFields['classe_id']
					)
					);
						
					$insertMultipleSeries = array();
						
					foreach($_POST['start_date_series'][$lesson_id] as $serie_id => $start_date_serie) {
						$end_date_series = $_POST['end_date_series'][$lesson_id][$serie_id];

						if (!empty($start_date_serie) || !empty($end_date_series)) {
							// CHECK NOW END DATE
							$serieFields = array(
								'serie_id' => $serie_id
							);
							if (date_create_from_format('d/m/Y', $start_date_serie) !== FALSE) {
								$startSerieDateObject = date_create_from_format('d/m/Y', $start_date_serie);
								$serieFields['start_date']	= $startSerieDateObject->format('Y-m-d');
							}
							if (date_create_from_format('d/m/Y', $end_date_series) !== FALSE) {
								$endSerieDateObject = date_create_from_format('d/m/Y', $end_date_series);
								$serieFields['end_date'] = $endSerieDateObject->format('Y-m-d');
							}
								
							if ($startSerieDateObject->format('u') > $endSerieDateObject->format('u')) {
								$this->setMessageVar(__XCOURSE_START_DATE_GREATER_THAN_END_DATE, 'failure');
								continue;
							}
								
							$insertMultipleSeries[] = array_merge($serieFields, $insertFields);
						}
					}
					eF_insertTableDataMultiple("module_xcourse_lesson_class_calendar_series", $insertMultipleSeries);
				}
			}
		}

		// CHECK STEP
		$step = 1;
		if ($this->getCache("selected_class_id")) {
			$step = 2;
				
			$filterForm->setDefaults(array(
				'classe_filter' => $this->getCache("selected_class_id")
			));
				
			$academicCalendar = $this->getAcademicCalendar(
			$this->getCache("selected_course_id"), $this->getCache("selected_class_id")
			);
				
			if (count($academicCalendar) > 0) {
				$academicSeries = $this->getAcademicCalendarSeries(
				$this->getCache("selected_course_id"), $this->getCache("selected_class_id")
				);

				$smarty -> assign ("T_" . $this->getName() . "_LESSONS_CALENDAR", $academicCalendar);
				$smarty -> assign ("T_" . $this->getName() . "_LESSONS_CALENDAR_SERIES", $academicSeries);
			}
		}

		$smarty -> assign ("T_" . $this->getName() . "_STEP", $step);
			
		/*
		 $constraints = array('archive' => false, 'active' => true, 'sort' => 'name', 'order' => 'asc', 'return_objects' => false);
		 $xcourseLessons = $editCourse -> getCourseLessons($constraints);

		 foreach($xcourseLessons as $lesson) {
			$lessonsData[$lesson['id']] = $lesson['name'];
			}
			$smarty ->assign("T_" . $this->getName() . "_LESSONS", $xcourseLessons);
			*/
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$filterForm -> accept($renderer);
		$smarty -> assign('T_' . $this->getName() . '_ACADEMIC_FILTER_FORM', $renderer -> toArray());
		 
		return true;
	}

	/* Data Model Functions */
	public function getAcademicCalendar($course_id, $class_id, &$has_calendar = null) {
		$editCourse = $this->getEditedCourse(null, $course_id);
	
		 
		$constraints = array('archive' => false, 'active' => true, 'return_objects' => false);
		$xcourseLessons = $editCourse -> getCourseLessons($constraints);

		$fields = array(
 			"cal.course_id", 
			"cal.lesson_id", 
			"cal.classe_id", 
			"cal.start_date", 
			"cal.end_date"
		);

		$tables = "module_xcourse_lesson_class_calendar cal";

		$orders = array(
			"start_date ASC", 
			"end_date ASC"
		);

		$calendar = array();
		$has_calendar = false;

		foreach($xcourseLessons as $lesson) {
			$wheres = array();
			$wheres[] = "cal.course_id = " . $course_id;
			$wheres[] = "cal.lesson_id = " . $lesson['id'];
			$wheres[] = "cal.classe_id = " . $class_id;
					
			$calendarDB = eF_getTableData(
				$tables,
				implode(",", $fields),
				implode(" AND ", $wheres),
				implode(",", $orders)
			);

				$calendarItem = array(
		 			"name"			=> $lesson['name'],
		 			"course_id"		=> $course_id, 
					"lesson_id"		=> $lesson['id'],  
					"classe_id"		=> $class_id,  
					"start_date"	=> null,
					"end_date"		=> null,
					"completed"		=> false,
					"in_progress"	=> false
				);
					
				if (count($calendarDB) > 0) {
					$calendarItem['start_date']	= $calendarDB[0]['start_date'];
					$calendarItem['end_date']	= $calendarDB[0]['end_date'];

					if (!is_null($calendarItem['end_date'])) {
						if (strtotime($calendarItem['end_date']) < time()) {
							$calendarItem["completed"]	= true;
						} elseif (
						!is_null($calendarItem['start_date']) &&
						strtotime($calendarItem['start_date']) < time() &&
						strtotime($calendarItem['end_date']) > time()
						) {
							$calendarItem['in_progress'] = true;
						}
					}
					$has_calendar = true;
				}
					
				$calendar[$lesson['id']] = $calendarItem;
			}
			 
			return $calendar;
	}
	private function getAcademicCalendarSeries($course, $class_id, $lesson_id = null, &$hasCalendar = null) {
		//module_xcourse_lesson_class_series
		if (eF_checkParameter($course, 'id')) {
			$editCourse = $this->getEditedCourse(null, $course);
		} else {
			$editCourse = $course;
		}
		if ($editCourse && eF_checkParameter($class_id, 'id')) {
				
			$fields = array(
				"series.id as serie_id", 
				"series.name as name", 
			);
			$tables = "module_xcourse_lesson_class_series series";
				
			$configSeries = eF_getTableData(
			$tables,
			implode(",", $fields)
			);
				
			$fields = array(
				"cla_series.course_id", 
				"cla_series.lesson_id", 
				"cla_series.classe_id", 
				"cla_series.serie_id", 
				"series.name as series", 
				"cla_series.start_date", 
				"cla_series.end_date"
				);
					
				$tables = "module_xcourse_lesson_class_series series
			LEFT OUTER JOIN module_xcourse_lesson_class_calendar_series cla_series ON (cla_series.serie_id = series.id)";
					
				$course_id = $editCourse->course['id'];
				//$class_id = $_GET['xclasse_id'];
					
				$wheres = array();
				$wheres[] = "cla_series.course_id = " . $course_id;
				$wheres[] = "cla_series.classe_id = " . $class_id;
					
				$showLessons = array();
					
				if (eF_checkParameter($lesson_id, 'id')) {
					//$lesson_id = $_GET['xlesson_id'];
					$wheres[] = "cla_series.lesson_id = " . $lesson_id;

					//$lesson_calendar = true;
					$showLessons[] = new MagesterLesson($lesson_id);
				} else {
					// GET COURSE LESSONS
					$constraints = array('archive' => false, 'active' => true, 'return_objects' => true);
					//$lesson_calendar = false;
				}

				$orders = array(
				"start_date ASC", 
				"end_date ASC"
				);
				/*
				 echo prepareGetTableData(
				 $tables,
				 implode(",", $fields),
				 implode(" AND ", $wheres),
				 implode(",", $orders)
				 );
				 */
				$definedSeries = eF_getTableData(
				$tables,
				implode(",", $fields),
				implode(" AND ", $wheres),
				implode(",", $orders)
				);
				$resultSeries = array();
					
				$hasCalendar = count($definedSeries) > 0;
					
				foreach($showLessons as $lessonObj) {
					$resultItem = array(
					"id"		=> $lessonObj->lesson['id'],
					'name'		=> $lessonObj->lesson['name'],
					'series'	=> array()
					);

					foreach($configSeries as $serie) {
							
						$resultItem['series'][$serie['serie_id']] = array(
						'serie_id'	=> $serie['serie_id'],
						'name'		=> $serie['name'],
						'start'		=> null,
						'end'		=> null
						);
						foreach($definedSeries as $item) {
							if (
							$item['lesson_id'] == $lessonObj->lesson['id'] &&
							$item['serie_id'] == $serie['serie_id']
							) {
								$resultItem['series'][$serie['serie_id']]['start'] = $item['start_date'];
								$resultItem['series'][$serie['serie_id']]['end'] = $item['end_date'];
								break;
							}
						}
					}

					$resultSeries[$lessonObj->lesson['id']] = $resultItem;

				}
					
				return $resultSeries;
		}
		return false;
	}
	public function getCourseById($courseID) {
		return new MagesterCourse($courseID);
	}
	public function getUserById($userID) {
		$userData = eF_getTableData("users", "login", "id = " . $userID);

		if ($userData) {
			return MagesterUserFactory::factory($userData[0]['login']);
		} else {
			return false;
		}
	}

	public function getCoursesList($contraints) {
		/*
		 $lessons = MagesterLesson :: getLessons();
		 $lessons = eF_multiSort($lessons, 'id', 'desc');
		 */
		//    	error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
		if ($contraints['ies_id']) {
			if (!is_array($contraints['ies_id'])) {
				$contraints['ies_id'] = array($contraints['ies_id']);
			}
			$contraints['ies_id'][] = 0;
			$where[] 	= sprintf('ies_id IN (%s)', implode(", ", $contraints['ies_id']));
		}
		 
		if ($contraints['active']) {
			$where[]	= 'active = ' . $contraints['active'];
		}
		if ($contraints['show_catalog']) {
			$where[]	= 'show_catalog = ' . $contraints['show_catalog'];
		}
		if ($contraints['directions_id']) {
			$where[] 	= 'directions_ID = ' . $contraints['directions_id'];
		}
		 
		$courseConstraints = array(
    		'condition'	=> implode(' AND ', $where)
		);
		 
		$courses = MagesterCourse :: getAllCourses($courseConstraints);

		$result = array();

		$xPaymentModule = $this->loadModule("xpayment");
		$xSkillModule = $this->loadModule("xskill");

		foreach ($courses as $key => $course) {
			// LOAD PAYMENT DETAILS
			$resultItem = $course->course;
				
				
			$resultItem['xpayment'] = $xPaymentModule->getPaymentDefaults(null, $course->course['id']);
				
				
			/*
			 echo "<course>";
			 echo "<id>".$course -> course['id']."</id>";
			 echo "<name>".$course -> course['name']."</name>";
			 echo "<active>".$course -> course['active']."</active>";
			 echo "<show_catalog>".$course -> course['show_catalog']."</show_catalog>";
			 echo "<language>".$course -> course['languages_NAME']."</language>";
			 echo "<price>";
			 echo "<value>".$course -> course['price']."</value>";
			 echo "<currency>".$GLOBALS['configuration']['currency']."</currency>";
			 if ($course -> course['enable_registration'] == 1) {
				echo "<registration>".$course -> course['price_registration']."</registration>";
				}
				if ($course -> course['enable_presencial'] == 1) {
				echo "<presencial>".$course -> course['price_presencial']."</presencial>";
				}
				if ($course -> course['enable_web']) {
				echo "<web>".$course -> course['price_web']."</web>";
				}
				echo "</price>";
					
				echo "<directions_ID>".$course -> course['directions_ID']."</directions_ID>";
				echo "<reset>".$course -> course['reset']."</reset>";
				echo "<expiration>".$course -> course['certificate_expiration']."</expiration>";
				$course_lessons = MagesterCourse::convertLessonObjectsToArrays($course->getCourseLessons());
				echo "<lessons>";
				foreach ($course_lessons as $key2 => $value2) {
				echo "<lesson>";
				echo "<id>".$value2['id']."</id>";
				echo "<name>".$value2['name']."</name>";
				echo "<previous_lessons_ID>".$value2['previous_lessons_ID']."</previous_lessons_ID>";
				echo "<direction>".$value2['directions_ID']."</direction>";
				echo "<active>".$value2['active']."</active>";
				echo "<show_catalog>".$value2['show_catalog']."</show_catalog>";
				echo "<duration>".$value2['duration']."</duration>";
				echo "<language>".$value2['languages_NAME']."</language>";
				echo "<course_only>".$value2['course_only']."</course_only>";
				echo "<price>";
				echo "<value>".$value2['price']."</value>";
				echo "<currency>".$GLOBALS['configuration']['currency']."</currency>";
				echo "</price>";
				echo "</lesson>";
				}
				echo "</lessons>";
				*/
			$course_classes = MagesterCourseClass :: convertClassesObjectsToArrays($course->getCourseClasses());
				
			$resultItem['classes'] = array();
				
			//echo "<classes>";
			foreach ($course_classes as $classKey => $classItem) {
				$resultItemClasses = array(
					"id"			=> $classItem['id'],
					"name"			=> $classItem['name'],
					"courses_ID"	=> $classItem['courses_ID'],
					"active"		=> $classItem['active'],
					"language"		=> $classItem['languages_NAME'],
					"max_users"		=> $classItem['max_users'],
					"count_users"	=> $classItem['count_users'],
					"start_date"	=> $classItem['start_date'],
					"end_date"		=> $classItem['end_date'],
					"schedules"		=> array()
				);

				// GET SCHEDULES FOR CLASS
				foreach($classItem['schedules'] as $scheduleItem) {
					$resultItemClasses['schedules'][] = array(
						"id"		=> $scheduleItem['id'], 
						"week_day"	=> $scheduleItem['week_day'],
						"start"		=> $scheduleItem['start'],
						"end"		=> $scheduleItem['end']
					);
				}
				$resultItem['classes'][] = $resultItemClasses;
			}
				
			// LOAD REQUIRED AND PROVIDED SKILLS
			$resultItem['skills'] = $xSkillModule->loadCourseSkills($resultItem['id']);
				
			$result[$key] = $resultItem;
		}
		/*
		 echo "</courses>";
		 echo "<lessons>";
		 foreach ($lessons as $key => $lesson) {
			echo "<lesson>";
			echo "<id>".$lesson['id']."</id>";
			echo "<name>".$lesson['name']."</name>";
			echo "<direction>".$lesson['directions_ID']."</direction>";
			echo "<active>".$lesson['active']."</active>";
			echo "<show_catalog>".$lesson['show_catalog']."</show_catalog>";
			echo "<duration>".$lesson['duration']."</duration>";
			echo "<language>".$lesson['languages_NAME']."</language>";
			echo "<course_only>".$lesson['course_only']."</course_only>";
			echo "<price>";
			echo "<value>".$lesson['price']."</value>";
			echo "<currency>".$GLOBALS['configuration']['currency']."</currency>";
			echo "</price>";
			echo "</lesson>";
			}
			echo "</lessons>";
			echo "</catalog>";
			echo "</xml>";
			*/
		return $result;
	}
	public function getUserCoursesList($userLogin = null) {
		if (is_null($userLogin)) {
			$userLogin = $this->getCurrentUser()->user['login'];
		}
		$editedUser = MagesterUserFactory::factory($userLogin);
		 
		$userCourses = $editedUser->getUserCourses(array('return_objects' => false));
		 
		$courses = array();
		foreach($userCourses as $userCourse) {
			if (MagesterUser :: isStudentRole($userCourse['user_type'])) {
				$course = array(
   					'id'				=> $userCourse['id'],
   					'name'				=> $userCourse['name'],
   					'course_type'		=> $userCourse['course_type'],
   					'classe_id'			=> $userCourse['classe_id'],
   					'enable_presencial'	=> $userCourse['enable_presencial'],
   					'enable_web'		=> $userCourse['enable_web'],
   					'classes'			=> array()
				);

				if ($course['course_type'] == 'Via Web') {
					$course['price'] = $userCourse['price_web'];
				} elseif ($course['course_type'] == 'Presencial') {
					$course['price'] = $userCourse['price_presencial'];
				} else {
					$course['price'] = $userCourse['price'];
					$course['course_type'] = _PAGAMENTO_COURSETYPENOSELECTED;
				}
					
					
				if ($course['classe_id'] != 0) {
					$classeObject = new MagesterCourseClass($course['classe_id']);
					$course['classe'] = $classeObject->classe;
				}
				/*
				 	
				$courseClass = MagesterCourseClass::getClassForUserCourse($editedUser->user['id'], $userCourse['id'], array('return_objects' => false));
					
				if (count($courseClass) > 0) {
				foreach($courseClass as $class) {
				$course['classes'][] = array(
				'id'			=> $class['id'],
				'name'			=> $class['name'],
				'start_date'	=> $class['start_date'],
				'end_date'		=> $class['end_date'],
				'schedules'		=> $class['schedules']
				);
				}
				}
				*/
				$courses[] = $course;
			}
		}
		return $courses;
	}
	protected function getDatatableSource() {
		$selectedAction = $this->getCurrentAction();
		 
		if ($selectedAction == self::GET_XCOURSE_USERS_SOURCE) {

			/* Array of database columns which should be read and sent back to DataTables. Use a space where
			 * you want to insert a non-database field (for example a counter or static image)
			 */
			$editCourse = new MagesterCourse($_GET['xcourse_id']);
			 
			$roles = MagesterLessonUser :: getLessonsRoles(true);

			$rolesBasic = MagesterLessonUser :: getLessonsRoles();

			$constraints = array(
				'archive' => false, 
				'active' => 1, 
				'return_objects' => false
			);
			$xcourseUsersCount = $editCourse -> countCourseUsersIncludingUnassigned($constraints);

			// APPEND WHERE CONSTRAINTS
			$aColumns = array(
				'login',
				'user_type',
				'active_in_course',
				'timestamp_completed',
              	'active_in_course',
			//'completed',
				'score',
				'operations'			
				);
					
				/*
				 Filter By => $_GET['xcourse_class_id']
				 $_GET['xcourse_class_id'] == -1 // IGNORE PARAM
				 $_GET['xcourse_class_id'] == 0 // USERS WITHOUT CLASS
				 $_GET['xcourse_class_id'] > 0 // USE THIS CLASS
				 */
				$this->setCache('xcourse_class_id', $_GET['xcourse_class_id']);
					
				switch($_GET['xcourse_class_id']) {
					case "-1" : {
						break;
					}
					case "0" : {
						$constraints['condition'] = "u.login NOT IN (
					    SELECT users_LOGIN FROM users_to_courses ucl WHERE ucl.classe_id IN (
					        SELECT id FROM classes cla WHERE cla.courses_ID = r.courses_ID
					    )
					)";
						break;
					}
					default  : {
						if (is_numeric($_GET['xcourse_class_id']) && $_GET['xcourse_class_id'] > 0) {
							$constraints['condition'] = sprintf(
							"u.login IN (
						    	SELECT users_LOGIN FROM users_to_courses ucl WHERE ucl.classe_id = %d AND ucl.classe_id IN (
						        	SELECT id FROM classes cla WHERE cla.courses_ID = r.courses_ID
						    	)
							)", $_GET['xcourse_class_id']
							);
						}
					}
				}
					
				//$sWhere = $sFixedWhere = "usr.archive = 0";
				if ( $_GET['sSearch'] != "" )
				{
					/*
					 $sWhere .= " AND (login LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
					 $sWhere .= "name LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
					 $sWhere .= "surname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%')";
					 */

					$constraints['filter'] = $_GET['sSearch'];
				}
					
					
				if ( isset( $_GET['iSortCol_0'] ) )
				{
					//$sOrder = "ORDER BY  ";
					for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
					{
						if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
						{
							$constraints['sort']	= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
							$constraints['order']	= mysql_real_escape_string( $_GET['sSortDir_'.$i] );
						}
					}

					$sOrder = substr_replace( $sOrder, "", -2 );
					/*
					 if ( $sOrder == "ORDER BY" )
					 {
					 $sOrder = "";
					 }
					 */
				}
				/*
				 * Paging
				 */
				$sLimit = "";
				if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
				{
					$constraints['limit'] 	= mysql_real_escape_string( $_GET['iDisplayLength'] );
					$constraints['offset']	= mysql_real_escape_string( $_GET['iDisplayStart'] );
				}
					
				$xcourseUsers = $editCourse -> getCourseUsersIncludingUnassigned($constraints);
				$xcourseUsersDisplayedCount = $editCourse -> countCourseUsersIncludingUnassigned($constraints);
					
				$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => intval($xcourseUsersCount),
				"iTotalDisplayRecords" => intval($xcourseUsersDisplayedCount),
				"aaData" => array()
				);
					
				$activeString = '
				<button class="%2$s skin_colour round_all activateUserLink" title = "%1$s" %3$s>
				<img 
					class = "ajaxHandle" 
					src = "/' . G_CURRENTTHEMEURL . '/images/icons/small/white/alert_2.png"
					width="16" 
					height="16"
					alt = "%1$s" 
					>
				</button>';
					
				$canChange = false;

				if (
				!isset($this->getCurrentUser()->coreAccess['users']) ||
				$this->getCurrentUser()->coreAccess['users'] == 'change'
				) {
					$canChange = true;
				}
					
				foreach($xcourseUsers as $xcourseUser) {
					$operationButtons = array();
						
					$row = array();
					$row["DT_RowId"] 	= "xcourse_user_" . $xcourseUser['id'];

					$url = $_SESSION['s_type'] . '.php?ctg=module&op=module_xuser' . "&action=edit_xuser&xuser_login=" . $xcourseUser[ 'login' ];

					$row["login"] = sprintf(
					'<a href = "%s" class = "editLink" %s>%s</a>',
					$url,
					$xcourseUser['active_in_course'] == 0 ? 'style="color:red;"' : '',
					formatLogin(null, $xcourseUser)
					);

					$row['user_type']			= $roles[$xcourseUser['user_type']];

					$row['active_in_course']	= !is_null($xcourseUser['active_in_course']) && $xcourseUser['active_in_course'] != 0 ? strftime('%d/%m/%Y', $xcourseUser['active_in_course']) : '';

					$row['timestamp_completed']	= !is_null($xcourseUser['timestamp_completed']) && $xcourseUser['timestamp_completed'] != 0 ? strftime('%d/%m/%Y', $xcourseUser['timestamp_completed']) : '';

					/** @todo Checar função para formatar inteiros, decimais, monetários, percentuais, etc.. */
					$row['score']				= str_replace('.', ',', sprintf("%0.2f", $xcourseUser['score']) . '%');


					if (is_null($xcourseUser['active_in_course'])) {
						$operationButtons[] = sprintf('
						<button class="%2$s skin_colour round_all enrollUser" title = "%1$s">
							<img 
								src = "/' . G_CURRENTTHEMEURL . '/images/icons/small/white/books.png"
								width="16" 
								height="16"
								alt = "%1$s" 
								>
						</button>',
						__XCOURSE_ENROLLUSER_HINT,
						""
						);
					} elseif ($xcourseUser['active_in_course'] != 0) {
						$operationButtons[] = sprintf(
						$activeString,
						_DEACTIVATE,
						"green",
						($canChange) ?
							'onclick = "xcourse_confirmUser(this, ' . $editCourse->course['id'] . ', \'' . $xcourseUser['login'] . '\'); "' : 
							"" 
							);
					} else {
						$operationButtons[] = sprintf(
						$activeString,
						_ACTIVATE,
						"red",
						($canChange) ?
							'onclick = "xcourse_confirmUser(this, ' . $editCourse->course['id'] . ', \'' . $xcourseUser['login'] . '\'); "' : 
							"" 
							);
					}

					$row['operations']			= sprintf('
					<div class="button_display">
					%s
					</div>', implode(' ', $operationButtons)
					);


					$output['aaData'][] = $row;

				}
				header("Content-Type: application/javascript");

				//usort($output['aaData'], create_function('$first, $last', 'return $first["user_type_name"] < $last["user_type_name"] ? -1 : 1;'));
					
				echo json_encode( $output );
				exit;
		}
	}
}
?>