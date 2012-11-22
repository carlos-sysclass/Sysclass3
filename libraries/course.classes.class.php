<?php
/**

 * MagesterLesson Class file

 *

 * @package SysClass

 * @version 3.5.0

 */
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}

class MagesterCourseClassException extends Exception
{
 const NO_ERROR = 0;
 const LESSON_NOT_EXISTS = 201;
 const INVALID_ID = 202;
 const CANNOT_CREATE_DIR = 203;
 const INVALID_LOGIN = 204;
 const DATABASE_ERROR = 205;
 const DIR_NOT_EXISTS = 206;
 const FILESYSTEM_ERROR = 207;
 const DIRECTION_NOT_EXISTS = 208;
 const MAX_USERS_LIMIT = 209;
 const CATEGORY_NOT_EXISTS = 210;
 const INVALID_PARAMETER = 211;
 const GENERAL_ERROR = 299;
}

class MagesterCourseClass
{
 
 const MAX_NAME_LENGTH = 150;

 public $classe = array();

 protected $users = false;

 protected $conditions = false;

 protected $directory = '';

 public $options = array('theory' => 1,
                            'examples' => 1,
                            'projects' => 1,
                            'tests' => 1,
                            'survey' => 1,
                            'rules' => 1,
                            'forum' => 1,
                            'comments' => 1,
                            'news' => 1,
                            'online' => 1,
                            'chat' => 1,
                            'scorm' => 1,
                            'dynamic_periods' => 0,
                            'digital_library' => 1,
                            'calendar' => 1,
                            'new_content' => 1,
                            'glossary' => 1,
       'reports' => 1,
                            'tracking' => 1,
                            'auto_complete' => 1,
                            'content_tree' => 1,
                            'lesson_info' => 1,
       'bookmarking' => 1,
       'content_report' => 0,
          'print_content' => 1,
          'start_resume' => 1,
                            'show_percentage' => 1,
                            'show_right_bar' => 1,
                            'show_left_bar' => 0,
                            'show_student_cpanel' => 1,
                            'recurring' => 0,
                            'recurring_duration' => 0,
                            'show_content_tools' => 1,
                            'show_dashboard' => 1,
          'show_horizontal_bar' => 1,
 //'complete_next_lesson'=> 0,
          'default_positions' => '',
       'feedback' => 1);

	function __construct($courseclassId) {
		$this -> initializeDataFromSource($courseclassId);
		$this -> initializeDirectory();
		$this -> initializeOptions();
	}

	private function initializeDataFromSource($classe) {
		if (is_array($classe)) {
			$this -> classe = $classe;
			if (!isset($this -> classe['schedules'])) {
			// GET SCHEDULES FRO CLASSE
				$schedules = eF_getTableData("classes_schedules", "id, week_day, start, end", "classes_ID = " . $this -> classe['id'], "week_day ASC, start ASC");
				$this -> classe['schedules'] = $schedules;
			}
		} elseif (!$this -> validateId($classe)) {
			throw new MagesterCourseClassException(_INVALIDID, MagesterCourseClassException :: INVALID_ID);
		} else {
			$fields = array(
				"c.*",
				"c.courses_ID IS NOT NULL as has_course",
				"(SELECT COUNT(uc.users_LOGIN) FROM users_to_courses uc WHERE uc.classe_id = c.id AND uc.active = 1 AND uc.archive = 0 AND uc.user_type = 'student') as count_users"
			);
			
			$select = implode(', ', $fields);
			
			$classe = eF_getTableData("classes c", $select, "id = " . $classe);
			
			
			if (empty($classe)) {
				throw new MagesterCourseClassException(_LESSONDOESNOTEXIST, MagesterCourseClassException :: LESSON_NOT_EXISTS);
			}
			$this -> classe = $classe[0];
			
			// GET SCHEDULES FRO CLASSE
			$schedules = eF_getTableData("classes_schedules", "id, week_day, start, end", "classes_ID = " . $this -> classe['id'], "start");
			$this -> classe['schedules'] = $schedules;
		}
	}

	private function initializeDirectory() {
		if ($this -> classe['share_folder']) {
			$this -> directory = G_COURSECLASSPATH . $this -> classe['share_folder'].'/';
		} else {
			$this -> directory = G_COURSECLASSPATH . $this -> classe['id'].'/';
		}
		if (!is_dir($this -> directory)) {
			mkdir($this -> directory, 0755);
		}
	}
 
	private function initializeOptions() {
		$this -> validateSerializedArray($this -> classe['options']) OR $this -> classe['options'] = $this -> sanitizeSerialized($this -> classe['options']);
		$options = unserialize($this -> classe['options']);
		$newOptions = array_diff_key($this -> options, $options); //$newOptions are lesson options that were added to the MagesterLesson object AFTER the lesson options serialization took place
		$this -> options = $options + $newOptions; //Set lesson options
	}
 
	private static function validateAndSanitizeCourseClassFields($courseClassFields) {
		$courseClassFields = self :: setDefaultCourseClassValues($courseClassFields);
		
		$fields = array(
			'courses_ID'		=> self :: validateAndSanitize($courseClassFields['courses_ID'], 'courses_foreign_key'),
			'start_date'		=> self :: validateAndSanitize($courseClassFields['start_date'], 'timestamp'),
			'end_date'			=> self :: validateAndSanitize($courseClassFields['end_date'], 'timestamp'),		
			'name' 				=> self :: validateAndSanitize($courseClassFields['name'], 'name'),
			'active' 			=> self :: validateAndSanitize($courseClassFields['active'], 'boolean'),
			'archive' 			=> self :: validateAndSanitize($courseClassFields['archive'], 'boolean'),
			'share_folder' 		=> self :: validateAndSanitize($courseClassFields['share_folder'], 'integer'),
			'created' 			=> self :: validateAndSanitize($courseClassFields['created'], 'boolean_or_timestamp'),
			'options' 			=> self :: validateAndSanitize($courseClassFields['options'], 'serialized'),
			'metadata' 			=> self :: validateAndSanitize($courseClassFields['metadata'], 'serialized'),
			'info' 				=> self :: validateAndSanitize($courseClassFields['info'], 'serialized'),
			'duration' 			=> self :: validateAndSanitize($courseClassFields['duration'], 'integer'),
			'languages_NAME' 	=> self :: validateAndSanitize($courseClassFields['languages_NAME'], 'languages_foreign_key'),
			'max_users' 		=> self :: validateAndSanitize($courseClassFields['max_users'], 'integer'),
//          'price' => self :: validateAndSanitize($lessonFields['price'], 'float'),
//          'publish' => self :: validateAndSanitize($lessonFields['publish'], 'boolean'),
//          'directions_ID' => self :: validateAndSanitize($lessonFields['directions_ID'], 'directions_foreign_key'),
//      	'certificate' => self :: validateAndSanitize($lessonFields['certificate'], 'text'),
//          'instance_source' => self :: validateAndSanitize($lessonFields['instance_source'], 'lessons_foreign_key'),
//      	'originating_course' => self :: validateAndSanitize($lessonFields['originating_course'], 'courses_foreign_key'));
		);

		return $fields;
	}
 
	public static function getDefaultCourseClassValues() {
		return self::setDefaultCourseClassValues(array());
	}
 
	private static function setDefaultCourseClassValues($lessonFields) {
		$defaultValues = array(
			'courses_ID'		=> 0,
			'start_date'		=> time(),
			'end_date'			=> time(),		
			'name' 				=> '',
			'active' 			=> 1,
			'archive' 			=> 0,
			'share_folder' 		=> 0,
			'created' 			=> 0,
			'options' 			=> '',
			'metadata' 			=> '',
			'info' 				=> '',
//			'description' => '',
//			'price' => 0,
			'duration' 			=> 0,
//			'show_catalog' => 1,
//			'publish' => 1,
//			'directions_ID' => 0,
			'languages_NAME' 	=> 'english',
			'max_users' 		=> 0
		);
		return array_merge($defaultValues, $lessonFields);
	}
 

	public static function validateAndSanitize($field, $type) {
		try {
			self :: validate($field, $type);
		} catch (MagesterCourseClassException $e) {
			if ($e -> getCode() == MagesterCourseClassException::INVALID_PARAMETER) {
				$field = self :: sanitize($field, $type);
			} else {
				throw $e;
			}
		}
		return $field;
	}
 

 
 public static function validate($field, $type) {
  $validParameter = true;
  switch ($type) {
   case 'id': self :: validateId($field) OR $validParameter = false; break;
   case 'name': self :: validateName($field) OR $validParameter = false; break;
   case 'boolean': self :: validateBoolean($field) OR $validParameter = false; break;
   case 'float': self :: validateFloat($field) OR $validParameter = false; break;
   case 'integer': self :: validateInteger($field) OR $validParameter = false; break;
   case 'directions_foreign_key': self :: validateDirectionsForeignKey($field) OR $validParameter = false; break;
   case 'languages_foreign_key': self :: validateLanguagesForeignKey($field) OR $validParameter = false; break;
   case 'lessons_foreign_key': self :: validateLessonsForeignKey($field) OR $validParameter = false; break;
   case 'text': self :: validateText($field) OR $validParameter = false; break;
   case 'serialized': self :: validateSerialized($field) OR $validParameter = false; break;
   case 'boolean_or_timestamp': (self :: validateBoolean($field) || self :: validateTimestamp($field)) OR $validParameter = false; break;
   default: break;
  }
  if ($validParameter) {
   return true;
  } else {
   throw new MagesterCourseClassException(_INVALIDPARAMETER.' ('.$type.'): "'.$field.'"', MagesterCourseClassException::INVALID_PARAMETER);
  }
 }
 
 private static function validateId($field) {
  !eF_checkParameter($field, 'id') ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateName($field) {
  mb_strlen($field) > self::MAX_NAME_LENGTH ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateText($field) {
  return true;
 }
 private static function validateBoolean($field) {
  $field !== true && $field !== false ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateTimestamp($field) {
  !eF_checkParameter($field, 'timestamp') ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateSerialized($field) {
  unserialize($field) === false && $field !== serialize(false) ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateSerializedArray($field) {
  $unserialized = unserialize($field);
  $unserialized === false || !is_array($unserialized) ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateNull($field) {
  !is_null($field) ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateFloat($field) {
  !is_numeric($field) ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateInteger($field) {
  !is_numeric($field) ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateDirectionsForeignKey($field) {
  !eF_checkParameter($field, 'id') || sizeof(eF_getTableData("directions", "id", "id=".$field)) == 0 ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateLessonsForeignKey($field) {
  !eF_checkParameter($field, 'id') || sizeof(eF_getTableData("lessons", "id", "id=".$field)) == 0 ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateCoursesForeignKey($field) {
  !eF_checkParameter($field, 'id') || sizeof(eF_getTableData("courses", "id", "id=".$field)) == 0 ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateLanguagesForeignKey($field) {
  !eF_checkParameter($field, 'login') || sizeof(eF_getTableData("languages", "name", "name='".$field."'")) == 0 ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }
 private static function validateUsersForeignKey($field) {
  !eF_checkParameter($field, 'login') || sizeof(eF_getTableData("users", "login", "login='$field'")) == 0 ? $returnValue = false : $returnValue = true;
  return $returnValue;
 }

 public function sanitize($field, $type) {
  switch ($type) {
   case 'name': $field = self :: sanitizeName($field); break;
   case 'boolean': $field = self :: sanitizeBoolean($field); break;
   case 'boolean_or_timestamp': $field = self :: sanitizeBoolean($field); break;
   case 'timestamp': $field = self :: sanitizeTimestamp($field); break;
   case 'serialized': $field = self :: sanitizeSerialized($field); break;
   case 'float': $field = self :: sanitizeFloat($field); break;
   case 'integer':
   case 'id': $field = self :: sanitizeInteger($field); break;
   case 'directions_foreign_key':
   case 'languages_foreign_key':
   case 'lessons_foreign_key': $field = self :: sanitizeForeignKey($field); break;
   case 'text': default: break;
  }
  return $field;
 }
 private static function sanitizeTimestamp($field) {
  $field = time();
  return $field;
 }
 private static function sanitizeName($field) {
  $field = mb_substr($field, 0, self::MAX_NAME_LENGTH);
  return $field;
 }
 private static function sanitizeBoolean($field) {
  $field = ($field != 0);
  return $field;
 }
 private static function sanitizeSerialized($field) {
  $field = serialize(array());
  return $field;
 }
 private static function sanitizeFloat($field) {
  $field = (float)$field;
  return $field;
 }
 private static function sanitizeInteger($field) {
  $field = (int)$field;
  return $field;
 }
 private static function sanitizeForeignKey($field) {
  $field = 0;
  return $field;
 }

	public static function createCourseClass($fields) {
		is_dir(G_COURSECLASSPATH) OR mkdir(G_COURSECLASSPATH, 0755);
 	
		$fields['metadata'] = self::createCourseClassMetadata($fields);
//		$fields['directions_ID'] = self::computeNewLessonDirectionsId($fields);
		$fields['created'] = time();
		$fields['id'] = self::computeNewCourseClassId();
		$fields = self::validateAndSanitizeCourseClassFields($fields);
		
		$courseclassId = eF_insertTableData("classes", $fields);
		
		$newLesson = new MagesterCourseClass($courseclassId);
		MagesterSearch :: insertText($fields['name'], $courseclassId, "courseclasses", "title");
//		self::addNewLessonSkills($newLesson);
//		self::createLessonForum($newLesson);
//		self::createLessonChat($newLesson);
		self::notifyModuleListenersForCourseClassCreation($newLesson);
		return $newLesson;
	}

	private static function createCourseClassMetadata($fields) {
		$languages = MagesterSystem :: getLanguages(true);
		$lessonMetadata = array(
			'title' => $fields['name'],
            'creator' => isset($GLOBALS['currentUser']) ? formatLogin($GLOBALS['currentUser'] -> user['login']) : '',
            'publisher' => isset($GLOBALS['currentUser']) ? formatLogin($GLOBALS['currentUser'] -> user['login']) : '',
            'contributor' => isset($GLOBALS['currentUser']) ? formatLogin($GLOBALS['currentUser'] -> user['login']) : '',
            'date' => date("Y/m/d", time()),
            'language' => $languages[$fields['languages_NAME']],
            'type' => 'classcourse'
		);
  		$metadata = serialize($lessonMetadata);
  		return $metadata;
 	}
	private static function computeNewCourseClassId() {
		$fileSystemTree = new FileSystemTree(G_COURSECLASSPATH, true);
		foreach ($fileSystemTree -> tree as $key => $value) {
			if (preg_match("/\d+/", basename($key))) {
				$directories[] = basename($key);
			}
		}
		
		$result = eF_getTableData("classes", "max(id) as max_id");
		$firstFreeSlot = (max($result[0]['max_id'], max($directories))) + 1;
		return $firstFreeSlot;
	}
	
	public function setSchedule($scheduleData) {
		$this -> classe['schedules'] = $scheduleData;
		
		return $this;
	}
	public function clearSchedule() {
		$this -> classe['schedules'] = array();

		return $this;
	}
	
	public function appendSchedule($weekDay, $start, $end) {
		$this -> classe['schedules'][] = array(
			'week_day'	=> $weekDay,
			'start'		=> $start,
			'end'		=> $end
		);
		return $this;
	}

	public function persistSchedule() {
		eF_deleteTableData("classes_schedules", "classes_ID = " . $this->classe['id']);
		
//		var_dump($this -> classe['schedules']);

		$fields = array();
		foreach($this -> classe['schedules'] as $scheduleItem) {
			$fields[] = array(
				'classes_ID'	=> $this->classe['id'],
				'week_day'	=> $scheduleItem['week_day'],
				'start'		=> $scheduleItem['start'],
				'end'		=> $scheduleItem['end']
			);
		}
		return eF_insertTableDataMultiple("classes_schedules", $fields);
	}
	
 private static function notifyModuleListenersForCourseClassCreation($courseclass) {
  // Get all modules (NOT only the ones that have to do with the user type)
  $modules = eF_loadAllModules();
  // Trigger all necessary events. If the function has not been re-defined in the derived module class, nothing will happen
  foreach ($modules as $module) {
   $module -> onNewCourseClass($courseclass -> classe['id']);
  }
 }
	public function delete($removeFromCourse = true) {
		//$this -> initialize('all');
		eF_deleteTableData("classes", "id=".$this -> classe['id']);
		MagesterSearch :: removeText('courseclasses', $this -> classe['id'], 'title');
	}
	
	public function persist() {
		$fields = array(
			//'id'
			'courses_ID'		=> $this -> classe['courses_ID'],
			'start_date'		=> $this -> classe['start_date'],
			'end_date'			=> $this -> classe['end_date'],
			'name'				=> $this -> classe['name'],
			'info'				=> $this -> classe['info'],
			'active'			=> $this -> classe['active'],
			'duration'			=> $this -> classe['duration'],
			'options'			=> $this -> classe['options'],
			'languages_NAME'	=> $this -> classe['languages_NAME'],
			'metadata'			=> $this -> classe['metadata'],
			'share_folder'		=> $this -> classe['share_folder'],
			'max_users'			=> $this -> classe['max_users'],
			'archive'			=> $this -> classe['archive']
		);
		
		if (!eF_updateTableData("classes", $fields, "id=".$this -> classe['id'])) {
			throw new MagesterUserException(_DATABASEERROR, MagesterUserException :: DATABASE_ERROR);
		}
		MagesterSearch :: removeText('courseclasses', $this -> classe['id'], 'title'); //Refresh the search keywords
		MagesterSearch :: insertText($fields['name'], $this -> classe['id'], "courseclasses", "title");
	}
	
 public function getUsers($basicType = false, $refresh = false) {
  if ($this -> users === false || $refresh) { //Make a database query only if the variable is not initialized, or it is explicitly asked
   $this -> users = array();
   $result = eF_getTableData("users u, users_to_course uc, classes c", "u.*, uc.*", "u.user_type != 'administrator' and c.archive = 0 and uc.archive = 0 and u.archive = 0 and u.login = uc.users_LOGIN and uc.classe_id=".$this -> classe['id']);
   foreach ($result as $value) {
   	/** @todo Inject here course-class data */
    $this -> users[$value['login']] = array(
    	'login' => $value['login'],
        'email' => $value['email'],
        'name' => $value['name'],
        'surname' => $value['surname'],
//'basic_user_type' => $value['user_type'],
		'user_type' => $value['user_type'],
//'user_types_ID' => $value['user_types_ID'],
        'role' => $value['role'],
        'from_timestamp' => $value['from_timestamp'],
        'active' => $value['active'],
    	'archive' => $value['archive'],
      //  'avatar' => $value['avatar'],
      //  'completed' => $value['completed'],
      //  'timestamp_completed' => $value['timestamp_completed'],
        'partof' => 1
    );
   }
  }
  /*
  if ($basicType) {
   $users = array();
   $roles = MagesterLessonUser :: getLessonsRoles();
   foreach ($this -> users as $login => $value) {
    if ($roles[$value['role']] == $basicType) {
     $users[$login] = $value;
    }
   }
   return $users;
  } else {
   return $this -> users;
  }
  */
  return $this -> users;
 }
 
 /*

	 * Append the tables that are used from the statistics filters to the FROM table list

	 */
 public static function appendTableFiltersUserConstraints($from, $constraints) {
  if (isset($constraints['table_filters'])) {
   foreach ($constraints['table_filters'] as $constraint) {
    if (isset($constraint['table']) && isset($constraint['joinField'])) {
     $from .= " JOIN " . $constraint['table'] . " ON u.login = " . $constraint['joinField'];
    }
   }
  }
  return $from;
 }

 /**

	 * Count lesson users based on the specified constraints, including unassigned

	 * @param array $constraints The constraints for the query

	 * @return array An array of MagesterUser objects

	 * @since 3.6.3

	 * @access public

	 */
 public function countLessonUsersIncludingUnassigned($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  list($where, $limit, $orderby) = MagesterUser :: convertUserConstraintsToSqlParameters($constraints);
  $where[] = "user_type != 'administrator'";
  $select = "u.login";
  $from = "users u left outer join (select completed,score,lessons_ID,from_timestamp,users_LOGIN from users_to_lessons where lessons_ID='".$this -> lesson['id']."' and archive=0) r on u.login=r.users_LOGIN";
  $result = eF_countTableData($from, $select, implode(" and ", $where));
  return $result[0]['count'];
 }
 //TO REPLACE getUsers
 public function getLessonUsersOld($returnObjects = false) {
  if (sizeof($this -> users) == 0) {
   $this -> Users();
  }
  if ($returnObjects) {
   foreach ($this -> users as $key => $user) {
    $users[$key] = MagesterUserFactory :: factory($key);
   }
   return $users;
  } else {
   return $this -> users;
  }
 }
 private function initializeUsers() {
  $this -> lesson['total_students'] = $this -> lesson['total_professors'] = 0;
  $roles = MagesterLessonUser :: getLessonsRoles();
  $result = eF_getTableData("users_to_lessons ul, users u", "u.*, u.user_type as basic_user_type, ul.user_type as role, ul.from_timestamp as active_in_lesson, ul.score, ul.completed", "u.archive = 0 and ul.archive = 0 and ul.users_LOGIN = u.login and ul.lessons_ID=".$this -> lesson['id']);
  foreach ($result as $value) {
   $this -> users[$value['login']] = $value;
   if ($roles[$value['role']] == 'student') {
    $this -> lesson['total_students']++;
   } elseif ($roles[$value['role']] == 'professor') {
    $this -> lesson['total_professors']++;
   }
  }
 }

 public function isUserInClass($user) {
  if ($user instanceOf MagesterUser) {
   $user = $user -> user['login'];
  }
  //$roles = $this -> getPossibleLessonRoles();
  $courseUsers = $this -> getUsers();
  //var_dump($courseUsers);
  if (in_array($user, array_keys($courseUsers))) {
   return true;
  } else {
   return false;
  }
 }

public function addUser($user, $roles = 'student', $confirmed = true) {
 	//var_dump($user);
  //$users = MagesterUser::verifyUsersList($users);
  
  
  //$users = $this -> filterOutArchivedUsers($users);
  //$roles = MagesterUser::verifyRolesList($roles, sizeof($users));
  $classeUsers = array_keys($this -> getUsers());
  
//  var_dump($classeUsers);
  
  $count = sizeof($this -> getStudentUsers());
  $usersToAddToLesson = $usersToSetRoleToLesson = array();
  //foreach ($users as $key => $user) {
   $roleInLesson = $roles[$key];
   //if ($roleInLesson != 'administrator') {
    if ($this -> classe['max_users'] && $this -> classe['max_users'] <= $count++/*- && MagesterUser::isStudentRole($roleInLesson)*/) {
     throw new MagesterCourseClassException(_MAXIMUMUSERSREACHEDFORCOURSE, MagesterCourseClassException :: MAX_USERS_LIMIT);
    }
    if (!in_array($user->user['login'], $classeUsers)) { //added this to avoid adding existing user when admin changes his role
     $usersToAddToLesson[] = array(
     	'users_ID' => $user->user['id'],
     	'login' => $user->user['login'],
     	'role' => $user->user['user_type'], 
     	'archive'	=> $user->user['archive']
     	//'confirmed' => $confirmed
     );
    //} else {
    // $usersToSetRoleToLesson[] = array('login' => $user, 'role' => $roleInLesson, 'confirmed' => $confirmed);
    }
   //}
  //}
  $this -> addUsersToClasses($usersToAddToLesson);
  //$this -> setUserRolesInLesson($usersToSetRoleToLesson);
  $this -> users = false; //Reset users cache
  //return $this -> getUsers();
 }
 

 public static function convertClassesObjectsToArrays($classesObjects) {
  foreach ($classesObjects as $key => $value) {
   $classesObjects[$key] = $value -> classe;
  }
  return $classesObjects;
 }
 
	public static function getClassForUserCourse($users_ID, $courses_ID, $constraints = array()) {
		
		$userLogin = eF_getTableData("users", "login", "id='".$users_ID."'");
		
		if (count($userLogin) > 0) {
			$userLogin = $userLogin[0]['login'];
		} 
		
		$fields = array(
			"c.*"
		);
		
		!empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
		list($where, $limit, $orderby) = MagesterCourse :: convertClassesConstraintsToSqlParameters($constraints);
		
		$from = "classes c";
		$select = implode(', ', $fields);
		$where[] = sprintf('c.courses_ID = \'%1$d\' AND id IN (SELECT classe_id FROM users_to_courses WHERE courses_ID = \'%1$d\' AND users_LOGIN = \'%2$s\')', $courses_ID, $userLogin);
		//echo prepareGetTableData($from, $select, implode(" and ", $where), $orderby, false, $limit);
		$result = eF_getTableData($from, $select, implode(" and ", $where), $orderby, false, $limit);
		
		if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
			return MagesterCourse :: convertDatabaseResultToClassesObjects($result);
		} else {
			return MagesterCourse :: convertDatabaseResultToClassesArray($result);
		}
 	
 	
	}
	
	public function getRole($login) {
		$lessonUsers = $this -> getUsers();
		if (in_array($login, array_keys($lessonUsers))) {
			return $lessonUsers[$login]['role'];
		} else {
			throw new MagesterUserException(_USERDOESNOTHAVETHISLESSON.": ".$lesson, MagesterUserException :: USER_NOT_HAVE_LESSON);
		}
	}
 
	public function getStudentUsers($returnObjects = false) {
		$lessonUsers = $this -> getUsers($returnObjects);
		foreach ($lessonUsers as $key => $value) {
			if ($value instanceOf MagesterUser) {
				$value = $value -> user;
			}
			if (!MagesterUser::isStudentRole($value['role'])) {
				unset($lessonUsers[$key]);
			}
		}
		return $lessonUsers;
	}
	
	public static function getAllClasses($constraints = array()) {
		$fields = array(
			"c.*"
		);
		
		!empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
		list($where, $limit, $orderby) = MagesterCourse :: convertClassesConstraintsToSqlParameters($constraints);
		
		$from = "classes c";
		$select = implode(', ', $fields);
		//$where[] = sprintf('c.courses_ID = \'%1$d\' AND id IN (SELECT classe_id FROM users_to_courses WHERE courses_ID = \'%1$d\' AND users_LOGIN = \'%2$s\')', $courses_ID, $userLogin);
		//echo prepareGetTableData($from, $select, implode(" and ", $where), $orderby, false, $limit);
		$result = eF_getTableData($from, $select, implode(" and ", $where), $orderby, false, $limit);
		
		if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
			return MagesterCourse :: convertDatabaseResultToClassesObjects($result);
		} else {
			return MagesterCourse :: convertDatabaseResultToClassesArray($result);
		}
		
	}
}
