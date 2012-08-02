<?php
/**
 * MagesterUser Class file
 *
 * @package SysClass
 */

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
 exit;
}

/**
 * User exceptions class
 *
 * @package SysClass
 */
class MagesterUserException extends Exception
{
 const NO_ERROR = 0;
 const INVALID_LOGIN = 401;
 const USER_NOT_EXISTS = 402;
 const INVALID_PARAMETER = 403;
 const USER_EXISTS = 404;
 const DATABASE_ERROR = 405;
 const USER_FILESYSTEM_ERROR = 406;
 const INVALID_TYPE = 407;
 const ALREADY_IN = 408;
 const INVALID_PASSWORD = 409;
 const USER_NOT_HAVE_LESSON = 410;
 const WRONG_INPUT_TYPE = 411;
 const USER_PENDING = 412;
 const TYPE_NOT_EXISTS = 414;
 const MAXIMUM_REACHED = 415;
 const RESTRICTED_USER_TYPE = 416;
 const USER_INACTIVE = 417;
 const USER_NOT_LOGGED_IN = 418;
 const GENERAL_ERROR = 499;
}


/**
 * Abstract class for users
 *
 * @package SysClass
 * @abstract
 */
abstract class MagesterUser
{
 /**
	 * A caching variable for user types
	 *
	 * @since 3.5.3
	 * @var array
	 * @access private
	 * @static
	 */
 private static $userRoles;

 /**
	 * The basic user types.
	 *
	 * @since 3.5.0
	 * @var array
	 * @access public
	 * @static
	 */
 public static $basicUserTypes = array('student', 'professor', 'administrator');

 /**
	 * The basic user types.
	 *
	 * @since 3.5.0
	 * @var array
	 * @access public
	 * @static
	 */
 public static $basicUserTypesTranslations = array('student' => _STUDENT, 'professor' => _PROFESSOR, 'administrator' => _ADMINISTRATOR);

 /**
	 * The user array.
	 *
	 * @since 3.5.0
	 * @var array
	 * @access public
	 */
 public $user = array();

 /**
	 * The user login.
	 *
	 * @since 3.5.0
	 * @var string
	 * @access public
	 */
 public $login = '';

 /**
	 * The user groups.
	 *
	 * @since 3.5.0
	 * @var string
	 * @access public
	 */
 public $groups = array();

 /**
	 * The user login.
	 *
	 * @since 3.5.0
	 * @var string
	 * @access public
	 */
 public $aspects = array();

 /**
	 * Whether this user authenitactes through LDAP.
	 *
	 * @since 3.5.0
	 * @var boolean
	 * @access public
	 */
 public $isLdapUser = false;

 /**
	 * The core_access sets where each user has access to
	 * @var array
	 * @since 3.5.0
	 * @access public
	 */
 public $core_access = array();

 /**
	 * Cache for modules
	 * @var array
	 * @since 3.6.1
	 * @access public
	 */
 private static $cached_modules = false;

 /**
	 * Instantiate class
	 *
	 * This function instantiates a new MagesterUser sibling object based on the given
	 * login. If $password is set, then it verifies the given password against
	 * the stored one. Either the MagesterUserFactory may be used, or directly the
	 * MagesterX class.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');			//Use factory to instantiate user object with login 'jdoe'
	 * $user = MagesterUserFactory :: factory('jdoe', 'mypass');  //Use factory to instantiate user object with login 'jdoe' and perform password verification
	 * $user = new MagesterAdministrator('jdoe')				  //Instantiate administrator user object with login 'jdoe'
	 * </code>
	 *
	 * @param string $login The user login
	 * @param string $password An enrypted password to check for the user
	 * @since 3.5.0
	 * @access public
	 */
 function __construct($user, $password = false) {
  if (!eF_checkParameter($user['login'], 'login')) {
   throw new MagesterUserException(_INVALIDLOGIN.': '.$user['login'], MagesterUserException :: INVALID_LOGIN);
  } else if ($password !== false && $password != $user['password']) {
   throw new MagesterUserException(_INVALIDPASSWORD.': '.$user, MagesterUserException :: INVALID_PASSWORD);
  }

  $this -> user = $user;
  $this -> login = $user['login'];

  $this -> user['directory'] = G_UPLOADPATH.$this -> user['login'];
  if (!is_dir($this -> user['directory'])) {
   $this -> createUserFolders();
  }
  $this -> user['password'] == 'ldap' ? $this -> isLdapUser = true : $this -> isLdapUser = false;

  //Initialize core access
  $this -> coreAccess = array();
  $this -> moduleAccess = array();
 }

 /**
	 * Creates user folders
	 * @since 3.6.4
	 * @access private
	 */
 private function createUserFolders() {
  $user_dir = G_UPLOADPATH.$this -> user['login'].'/';
  mkdir($user_dir, 0755);
  mkdir($user_dir.'message_attachments/', 0755);
  mkdir($user_dir.'message_attachments/Incoming/', 0755);
  mkdir($user_dir.'message_attachments/Sent/', 0755);
  mkdir($user_dir.'message_attachments/Drafts/', 0755);
  mkdir($user_dir.'avatars/', 0755);

  try {
   //Create database representations for personal messages folders (it has nothing to do with filsystem database representation)
   eF_insertTableDataMultiple("f_folders", array(array('name' => 'Incoming', 'users_LOGIN' => $this -> user['login']),
   array('name' => 'Sent', 'users_LOGIN' => $this -> user['login']),
   array('name' => 'Drafts', 'users_LOGIN' => $this -> user['login'])));
  } catch(Exception $e) {}

 }

 /**
	 * Get the user's upload directory
	 *
	 * This function returns the path to the user's upload directory. The path always has a trailing
	 * slash at the end.
	 * <br/>Example:
	 * <code>
	 * $path = $user -> getDirectory(); //returns something like /var/www/magester/upload/admin/
	 * </code>
	 *
	 * @return string The path to the user directory
	 * @since 3.6.0
	 * @access public
	 */
 public function getDirectory() {
  return $this -> user['directory'].'/';
 }

 /**
	 * Create new user
	 *
	 * This function is used to create a new user in the system
	 * The user is created based on a a properties array, in which
	 * the user login, name, surname and email must be present, otherwise
	 * an MagesterUserException is thrown. Apart from these, all the other
	 * user elements are optional, and defaults will be used if they are left
	 * blank.
	 * Once the database representation is created, the constructor tries to create the
	 * user directories, G_UPLOADPATH.'login/' and message attachments subfolders. Finally
	 * it assigns a default avatar to the user. The function instantiates the user based on
	 * its type.
	 * <br/>Example:
	 * <code>
	 * $properties = array('login' => 'jdoe', 'name' => 'john', 'surname' => 'doe', 'email' => 'jdoe@example.com');
	 * $user = MagesterUser :: createUser($properties);
	 * </code>
	 *
	 * @param array $userProperties The new user properties
	 * @param array $users The list of existing users, with logins and active properties, in the form array($login => $active). It is handy to specify when creating massively users
	 * @return array with new user settings if the new user was successfully created
	 * @since 3.5.0
	 * @access public
	 */
 public static function createUser($userProperties, $users = array(), $addToDefaultGroup = true) {
 	
  if (empty($users)) {
   $users = eF_getTableDataFlat("users", "login, active, archive");
  }

  $archived = array_combine($users['login'], $users['archive']);
  foreach ($archived as $key => $value) {
   if (!$value) {
    unset($archived[$key]);
   }
  }

  //$archived = array_filter($archived, create_function('$v', 'return $v;'));
  $users = array_combine($users['login'], $users['active']);
  $activatedUsers = array_sum($users); //not taking into account deactivated users in license users count

  //$versionDetails = eF_checkVersionKey($GLOBALS['configuration']['version_key']);
  if (!isset($userProperties['login']) || !eF_checkParameter($userProperties['login'], 'login')) {
   throw new MagesterUserException(_INVALIDLOGIN.': '.$userProperties['login'], MagesterUserException :: INVALID_LOGIN);
  }
  if (in_array($userProperties['login'], array_keys($archived))) {
   throw new MagesterUserException(_USERALREADYEXISTSARCHIVED.': '.$userProperties['login'], MagesterUserException :: USER_EXISTS);
  }
  if (in_array($userProperties['login'], array_keys($users)) > 0) {
   throw new MagesterUserException(_USERALREADYEXISTS.': '.$userProperties['login'], MagesterUserException :: USER_EXISTS);
  }
  if ($userProperties['email'] && !eF_checkParameter($userProperties['email'], 'email')) {
   throw new MagesterUserException(_INVALIDEMAIL.': '.$userProperties['email'], MagesterUserException :: INVALID_PARAMETER);
  }
  if (!isset($userProperties['name'])) {
   throw new MagesterUserException(_INVALIDNAME.': '.$userProperties['name'], MagesterUserException :: INVALID_PARAMETER);
  }
  if (!isset($userProperties['surname'])) {
   throw new MagesterUserException(_INVALIDSURNAME.': '.$userProperties['login'], MagesterUserException :: INVALID_PARAMETER);
  }
  !isset($userProperties['user_type']) ? $userProperties['user_type'] = 'student' : null; //If a user type is not specified, by default make the new user student
  isset($userProperties['password']) ? $passwordNonTransformed = $userProperties['password'] : $passwordNonTransformed = $userProperties['login'];
  if ($userProperties['password'] != 'ldap') {
   !isset($userProperties['password']) ? $userProperties['password'] = MagesterUser::createPassword($userProperties['login']) : $userProperties['password'] = self :: createPassword($userProperties['password']);
  }
  //!isset($userProperties['password'])	   ? $userProperties['password']	   = md5($userProperties['login'].G_MD5KEY)		: $userProperties['password'] = md5($userProperties['password'].G_MD5KEY);		//If password is not specified, use login instead
  !isset($userProperties['email']) ? $userProperties['email'] = '' : null; // 0 means not pending, 1 means pending
  !isset($userProperties['languages_NAME']) ? $userProperties['languages_NAME'] = $GLOBALS['configuration']['default_language'] : null; //If language is not specified, use default language
  !isset($userProperties['active']) || $userProperties['active'] == "" ? $userProperties['active'] = 0 : null; // 0 means inactive, 1 means active
  !isset($userProperties['pending']) ? $userProperties['pending'] = 0 : null; // 0 means not pending, 1 means pending
  !isset($userProperties['timestamp']) || $userProperties['timestamp'] == "" ? $userProperties['timestamp'] = time() : null;
  !isset($userProperties['user_types_ID']) ? $userProperties['user_types_ID'] = 0 : null;
  eF_insertTableData("users", $userProperties);
  // Assign to the new user all skillgap tests that should be automatically assigned to every new student

  
  $newUser = MagesterUserFactory :: factory($userProperties['login']);
  $newUser -> user['password'] = $passwordNonTransformed;
  global $currentUser; // this is for running eF_loadAllModules ..needs to go somewhere else
  if (!$currentUser) {
   $currentUser = $newUser;
  }
  MagesterEvent::triggerEvent(array(
  	"type" => MagesterEvent::SYSTEM_JOIN, 
  	"users_LOGIN" => $newUser -> user['login'], 
  	"users_name" => $newUser -> user['name'], 
  	"users_surname" => $newUser -> user['surname'],
	//"users_pass"	=>  $passwordNonTransformed,
  	"entity_name" => $passwordNonTransformed)
  );
  ///MODULES1 - Module user add events
  // Get all modules (NOT only the ones that have to do with the user type)
  if (!$cached_modules) {
   $cached_modules = eF_loadAllModules();
  }
  // Trigger all necessary events. If the function has not been re-defined in the derived module class, nothing will happen
  foreach ($cached_modules as $module) {
   $module -> onNewUser($userProperties['login']);
  }
  return $newUser;
 }
 /**
	 * This function parses an array of users and verifies that they are
	 * correct and converts it to an array if it's a single entry
	 *
	 * @param mixed $users The users to verify
	 * @return array The array of verified users
	 * @since 3.6.7
	 * @access public
	 * @static
	 */
 public static function verifyUsersList($users) {
  if (!is_array($users)) {
   $users = array($users);
  }
  foreach ($users as $key => $value) {
   if ($value instanceOf MagesterUser) {
    $users[$key] = $value -> user['login'];
   } elseif (is_array($value) && isset($value['login'])) {
    $users[$key] = $value['login'];
   } elseif (is_array($value) && isset($value['users_LOGIN'])) {
    $users[$key] = $value['users_LOGIN'];
   } elseif (!eF_checkParameter($value, 'login')) {
    unset($users[$key]);
   }
  }
  return array_values(array_unique($users)); //array_values() to reindex array
 }
 /**
	 * This function parses an array of roles and verifies that they are
	 * correct, converts it to an array if it's a single entry and
	 * pads the array with extra values, if its length is less than the
	 * desired
	 *
	 * @param mixed $roles The roles to verify
	 * @param int $length The desired length of the roles array
	 * @return array The array of verified roles
	 * @since 3.6.7
	 * @access public
	 * @static
	 */
 public static function verifyRolesList($roles, $length) {
  if (!is_array($roles)) {
   $roles = array($roles);
  }
  if (sizeof($roles) < $length) {
   $roles = array_pad($roles, $length, $roles[0]);
  }
  return array_values($roles); //array_values() to reindex array
 }
 /**
	 * Check whether the specified role is of type 'student'
	 *
	 * @param mixed $role The role to check
	 * @return boolean Whether it's a 'student' role
	 * @since 3.6.7
	 * @access public
	 * @static
	 */
 public static function isStudentRole($role) {
  $courseRoles = MagesterLessonUser :: getLessonsRoles();
  if ($courseRoles[$role] == 'student') {
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Check whether the specified role is of type 'professor'
	 *
	 * @param mixed $role The role to check
	 * @return boolean Whether it's a 'professor' role
	 * @since 3.6.7
	 * @access public
	 * @static
	 */
 public static function isProfessorRole($role) {
  $courseRoles = MagesterLessonUser :: getLessonsRoles();
  if ($courseRoles[$role] == 'professor') {
   return true;
  } else {
   return false;
  }
 }
 public static function checkUserAccess($type = false, $forceType = false) {
  if ($GLOBALS['configuration']['webserver_auth']) {
   $user = MagesterUser :: checkWebserverAuthentication();
  } else if (isset($_SESSION['s_login']) && $_SESSION['s_password']) {
   $user = MagesterUserFactory :: factory($_SESSION['s_login'], false, $forceType);
  } else {
   throw new MagesterUserException(_RESOURCEREQUESTEDREQUIRESLOGIN, MagesterUserException::USER_NOT_LOGGED_IN);
  }
  if (!$user -> isLoggedIn()) {
   throw new MagesterUserException(_RESOURCEREQUESTEDREQUIRESLOGIN, MagesterUserException::USER_NOT_LOGGED_IN);
  }
  if ($user -> user['timezone']) {
   date_default_timezone_set($user -> user['timezone']);
  }
  $user -> applyRoleOptions($user -> user['user_types_ID']); //Initialize user's role options for this lesson
  if ($type && $user -> user['user_type'] != $type) {
   throw new Exception(_YOUCANNOTACCESSTHISPAGE, MagesterUserException::INVALID_TYPE);
  }
  if (!$user -> isLoggedIn()) {
   throw new MagesterUserException(_RESOURCEREQUESTEDREQUIRESLOGIN, MagesterUserException::USER_NOT_LOGGED_IN);
  }
  return $user;
 }
 public static function checkWebserverAuthentication() {
  try {
   eval('$usernameVar='.$GLOBALS['configuration']['username_variable'].';');
   if (!$usernameVar) {
    eF_redirect(G_SERVERNAME.$GLOBALS['configuration']['error_page'], true, 'top', true);
    exit;
   } else {
    try {
     $user = MagesterUserFactory :: factory($usernameVar);
     if (!$_SESSION['s_login'] || $usernameVar != $_SESSION['s_login']) {
      $user -> login($user -> user['password'], true);
     }
    } catch (MagesterUserException $e) {
     if ($e -> getCode() == MagesterUserException::USER_NOT_EXISTS && $GLOBALS['configuration']['webserver_registration']) {
      try {
       include($GLOBALS['configuration']['registration_file']);
       $user = MagesterUserFactory :: factory($usernameVar);
       if (!$_SESSION['s_login'] || $usernameVar != $_SESSION['s_login']) {
        $user -> login($user -> user['password'], true);
       }
      } catch (Exception $e) {
       eF_redirect(G_SERVERNAME.$GLOBALS['configuration']['unauthorized_page'], true, 'top', true);
       exit;
      }
     } else {
      eF_redirect(G_SERVERNAME.$GLOBALS['configuration']['unauthorized_page'], true, 'top', true);
      exit;
     }
    }
   }
  } catch (Exception $e) {
   eF_redirect(G_SERVERNAME.$GLOBALS['configuration']['unauthorized_page'], true, 'top', true);
   //header("location:".G_SERVERNAME.$GLOBALS['configuration']['unauthorized_page']);
  }
  return $user;
 }
 /**
	 * Get system users
	 *
	 * This function is used to return a list with all the users of the system
	 * <br/>Example:
	 * <code>
	 * $users = _MagesterUser :: getUsers(false);
	 * </code>
	 *
	 * @param boolean returnAdmins A flag to indicate whether to return system administrators
	 * @return array The user list
	 * @since 3.5.0
	 * @access public
	 * @static
	 */
 public static function getUsers($returnAdmins = true) {
  $users = array();
  $result = eF_getTableData("users", "LOGIN, user_type", "archive=0");
  foreach ($result as $value) {
   if ($value['user_type'] == 'administrator'){
    if ($returnAdmins){
     $users[$value['LOGIN']] = $value['LOGIN'];
    }
   } else{
    $users[$value['LOGIN']] = $value['LOGIN'];
   }
  }
  return $users;
 }
 /**
	 * Add user profile field
	 */
 public static function addUserField() {}
 /**
	 * Remove user profile field
	 */
 public static function removeUserField() {}
 /**
	 * Get user type
	 *
	 * This function returns the user basic type, one of 'administrator', 'professor',
	 * 'student'
	 * <br/>Example:
	 * <code>
	 *	  $user = MagesterUserFactory :: factory('admin');
	 *	  echo $user -> getType();			//Returns 'administrator'
	 * </code>
	 *
	 * @return string The user type
	 * @since 3.5.0
	 * @access public
	 */
 public function getType() {
  return $this -> user['user_type'];
 }
 /**
	 * Set user password
	 *
	 * This function is used to change the user password to something
	 * new.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> setPassword('somepass');
	 * </code>
	 *
	 * @param string $password The new password
	 * @return boolean true if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function setPassword($password) {
  $password_encrypted = MagesterUser::createPassword($password);
  if (eF_updateTableData("users", array("password" => $password_encrypted), "login='".$this -> user['login']."'")) {
   $this -> user['password'] = $password;
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Get user password
	 *
	 * This function returns the user password (MD5 encrypted)
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * echo $user -> getPassword();			 //echos something like '36f49e43c662986b838258ab099d0d5a'
	 * </code>
	 *
	 * @return string The user password (encrypted)
	 * @since 3.5.0
	 * @access public
	 */
 public function getPassword() {
  return $this -> user['password'];
 }
 /**
	 * Set login type
	 *
	 * This function is used to set the login type for the user. Currently this
	 * can be either 'normal' (default) or 'ldap'. Setting the login type to 'ldap'
	 * erases the user password and forces authentication through ldap server
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> setLoginType('ldap');			   //Set login type to 'ldap'
	 * $user -> setLoginType('normal', 'testpass'); //Set login type to 'normal' using password 'testpass'
	 * $user -> setLoginType();					 //Set login type to 'normal' and use default password (the user's login)
	 * </code>
	 * If the user was an ldap user and is reverted back to normal, the password is either specified
	 * or created by default to match the user's login
	 *
	 * @param string $loginType The new login type, one of 'ldap' or 'normal'
	 * @param string $password The new password, only used when converting ldap to normal accounts
	 * @return boolean True if everything is ok.
	 * @since 3.5.0
	 * @access public
	 */
 public function setLoginType($loginType = 'normal', $password = '') {
  //The user login type is specified by the password. If the password is 'ldap', the the login type is also ldap. There is no chance to mistaken normal users for ldap users, since all normal users have passwords stored in md5 format, which can never be 'ldap' (or anything like it)
  if ($loginType == 'ldap' && $this -> user['password'] != 'ldap') {
   eF_updateTableData("users", array("password" => 'ldap'), "login='".$this -> user['login']."'");
   $this -> user['password'] = 'ldap';
  } elseif ($loginType == 'normal' && $this -> user['password'] == 'ldap') {
   !$password ? $password = MagesterUser::createPassword($this -> user['login']) : null; //If a password is not specified, use the user's login name
   eF_updateTableData("users", array("password" => $password), "login='".$this -> user['login']."'");
   $this -> user['password'] = $password;
  }
  return true;
 }
 /**
	 * Get the login type
	 *
	 * This function is used to check whether the user's login type
	 * is 'normal' or 'ldap'
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> getLoginType();					 //Returns either 'normal' or 'ldap'
	 * </code>
	 *
	 * @return string Either 'normal' or 'ldap'
	 * @since 3.5.0
	 * @access public
	 */
 public function getLoginType() {
  if ($this -> user['password'] == 'ldap') {
   return 'ldap';
  } else {
   return 'normal';
  }
 }
 /**
	 * Activate user
	 *
	 * This function is used to activate the user
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> activate();
	 * </code>
	 *
	 * @return boolean True if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function activate() {
  $this -> user['active'] = 1;
  $this -> user['pending'] = 0;
  $this -> persist();
  return true;
 }
 /**
	 * Deactivate user
	 *
	 * This function is used to deactivate the user
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> deactivate();
	 * </code>
	 *
	 * @return boolean True if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function deactivate() {
  $this -> user['active'] = 0;
  $this -> persist();
  MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_USER_DEACTIVATE, "users_LOGIN" => $this -> user['login'], "users_name" => $this -> user['name'], "users_surname" => $this -> user['surname']));
  return true;
 }
 /**
	 * Set avatar image
	 *
	 * This function is used to set the user's avatar image.
	 * <br/>Example:
	 * <code>
	 * $file = new MagesterFile(32);											 //This is a file uploaded -for example- in the filesystem.
	 * $user -> setAvatar($file);
	 * </code>
	 *
	 * @param MagesterFile $file The file that will be used as avatar
	 * @return boolean True if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function setAvatar($file) {
  if (eF_updateTableData("users", array("avatar" => $file['id']), "login = '".$this -> user['login']."'")) {
   $this -> user['avatar'] = $file['id'];
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Get avatar image
	 *
	 * This function returns the file object corresponding to the user avatar
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> getAvatar();	//Returns an MagesterFile object
	 * </code>
	 *
	 * @return MagesterFile The avatar's file object
	 * @since 3.6.0
	 * @access public
	 */
 public function getAvatar() {
  if ($this -> user['avatar']) {
   $avatar = new MagesterFile($this -> user['avatar']);
  } else {
   $avatar = new MagesterFile(G_SYSTEMAVATARSURL.'unknown_small.png');
  }
  return $avatar;
 }
 /**
	 * Set user status
	 *
	 * This function is used to set the user's status.
	 * <br/>Example:
	 * <code>
	 * $user -> setStatus("Carpe Diem!");
	 * </code>
	 *
	 * @param string to be set as the new status - could be ""
	 * @return boolean True if everything is ok
	 * @since 3.6.0
	 * @access public
	 */
 public function setStatus($status) {
  if (eF_updateTableData("users", array("status" => $status), "login = '".$this -> user['login']."'")) {
   $this -> user['status'] = $status;
   MagesterEvent::triggerEvent(array("type" => MagesterEvent::STATUS_CHANGE, "users_LOGIN" => $this -> user['login'], "users_name" => $this->user['name'], "users_surname" => $this->user['surname'], "entity_name" => $status));
   //echo $status;
   if ($_SESSION['facebook_user'] && $_SESSION['facebook_details']['status']['message'] != $status) {
    $path = "../libraries/";
    require_once $path . "external/facebook-platform/php/facebook.php";
    $facebook = new Facebook($GLOBALS['configuration']['facebook_api_key'], $GLOBALS['configuration']['facebook_secret']);
    // check permissions
    $has_permission = $facebook->api_client->call_method("facebook.users.hasAppPermission", array("ext_perm"=>"status_update"));
    if($has_permission){
     $facebook->api_client->call_method("facebook.users.setStatus", array("status" => $status, "status_includes_verb" => true));
     $temp = $facebook->api_client->fql_query("SELECT status FROM user WHERE uid = " . $_SESSION['facebook_user']);
     $_SESSION['facebook_details']['status'] = $temp[0]['status'];
    }
   }
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Logs out user
	 *
	 * To log out a user, the function deletes the session information and updates the database
	 * tables.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> logout();
	 * </code>
	 *
	 * @param $destroySession Whether to destroy session data as well
	 * @return boolean True if the user was logged out succesfully
	 * @since 3.5.0
	 * @access public
	 */
 public function logout($destroySession = true) {
  // Delete FB-connect related cookies - without this code the "Session key invalid problem" appears
  if (isset($GLOBALS['configuration']['facebook_api_key']) && $GLOBALS['configuration']['facebook_api_key'] && $_COOKIE[$GLOBALS['configuration']['facebook_api_key'] . "_user"]) {
   foreach ($_COOKIE as $cookie_key => $cookie) {
    if (strpos($GLOBALS['configuration']['facebook_api_key'], $cookie) !== false) {
     unset($_COOKIE[$key]);
    }
   }
   //$path = "../libraries/";
   //require_once $path . "external/facebook-platform/php/facebook.php";
   //$facebook = new Facebook($GLOBALS['configuration']['facebook_api_key'], $GLOBALS['configuration']['facebook_secret']);
   //$facebook->clear_cookie_state();
  }
  if ($this -> user['login'] == $_SESSION['s_login']) { //Is the current user beeing logged out? If so, destroy the session.
   if ($destroySession) {
    $_SESSION = array();
    isset($_COOKIE[session_name()]) ? setcookie(session_name(), '', time()-42000, '/') : null;
    session_destroy();
    setcookie ("cookie_login", "", time() - 3600);
    setcookie ("cookie_password", "", time() - 3600);
    if (isset($_COOKIE['c_request'])) {
     setcookie('c_request', '', time() - 86400);
     unset($_COOKIE['c_request']);
    }
    unset($_COOKIE['cookie_login']); //These 2 lines are necessary, so that index.php does not think they are set
    unset($_COOKIE['cookie_password']);
   }
  } else {
   $session_path = ini_get('session.save_path');
   $session_name = eF_getTableData('logs', 'comments', 'users_LOGIN="'.$this -> user['login'].'" AND action = "login"', 'timestamp desc limit 1');
   unlink($session_path.'/sess_'.$session_name[0]['comments']);
  }
//  eF_deleteTableData("module_chat_users", "username='".$this -> user['login']."'"); //Log out user from the chat Module
  eF_deleteTableData("users_to_chatrooms", "users_LOGIN='".$this -> user['login']."'"); //Log out user from the chat
  eF_deleteTableData("chatrooms", "users_LOGIN='".$this -> user['login']."' and type='one_to_one'"); //Delete any one-to-one conversations
  $result = eF_getTableData("logs", "action", "users_LOGIN = '".$this -> user['login']."'", "timestamp desc limit 1"); //?? ??? ????? ???????? ???, ????? ??? logs ??? ????? logout, ???? ?? ????? logout ??? ??? ??? ?? ???????
  if ($result[0]['action'] != 'logout') {
   $fields_insert = array('users_LOGIN' => $this -> user['login'],
           'timestamp' => time(),
           'action' => 'logout',
           'comments' => 0,
           'session_ip' => eF_encodeIP($_SERVER['REMOTE_ADDR']));
   eF_insertTableData("logs", $fields_insert);
  }
  //eF_deleteTableData('users_online', "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("user_times", array("session_expired" => 1), "users_LOGIN='".$this -> user['login']."'");
 }
 /**
	 * Login user
	 *
	 * This function logs the user in the system, using the specified password
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> login('mypass');
	 * </code>
	 *
	 * @param string $password The password to login with
	 * @param boolean $encrypted Whether the password is already encrypted
	 * @return boolean True if the user logged in successfully
	 * @since 3.5.0
	 * @access public
	 */
 public function login($password, $encrypted = false) {
  session_regenerate_id();		//If we don't use this, then a revisiting user that was automatically logged out may have to log in twice
  unset($_SESSION['previousMainUrl']);
  unset($_SESSION['previousSideUrl']);
  unset($_SESSION['s_lesson_user_type']);
  unset($_SESSION['supervises_branches']);
  if ($this -> user['pending']) {
   throw new MagesterUserException(_USERPENDING, MagesterUserException :: USER_PENDING);
  }
  if ($this -> user['active'] == 0) {
   throw new MagesterUserException(_USERINACTIVE, MagesterUserException :: USER_INACTIVE);
  }
  if ($this -> isLdapUser) { //Authenticate LDAP user
   if (!eF_checkUserLdap($this -> user['login'], $password)) {
    throw new MagesterUserException(_INVALIDPASSWORD, MagesterUserException :: INVALID_PASSWORD);
   }
  } else { //Authenticate normal user
   if (!$encrypted) {
    $password = MagesterUser::createPassword($password);
   }
   if ($password != $this -> user['password']) {
    throw new MagesterUserException(_INVALIDPASSWORD, MagesterUserException :: INVALID_PASSWORD);
   }
  }
  if ($this -> isLoggedIn()) { //If the user is already logged in, log him out
   if (!$this -> allowMultipleLogin()) {
    $this -> logout(false);
   }
  } else if (isset($_SESSION['s_login']) && $_SESSION['s_login']) {
   try {
    $user = MagesterUserFactory :: factory($_SESSION['s_login']);
    $user -> logout(false);
   } catch (Exception $e) {}
  }
  $_SESSION['s_lessons_ID'] = ''; //@todo: Here, we should reset all session values, except for cart contents
  //if user language is deactivated or deleted, login user with system default language
  $result = eF_getTableData("languages", "name", "name='".$this -> user['languages_NAME']."' and active=1");
  if ($result[0]['name'] == $this -> user['languages_NAME']) {
   $login_language = $this -> user['languages_NAME'];
  } else {
   $login_language = $GLOBALS['configuration']['default_language'];
  }
  //Assign session variables
  $_SESSION['s_login'] = $this -> user['login'];
  $_SESSION['s_password'] = $this -> user['password'];
  $_SESSION['s_type'] = $this -> user['user_type'];
  $_SESSION['s_language'] = $login_language;
  //Insert log entry
  $fields_insert = array('users_LOGIN' => $this -> user['login'],
           'timestamp' => time(),
           'action' => 'login',
           'comments' => session_id(),
           'session_ip' => eF_encodeIP($_SERVER['REMOTE_ADDR']));
  eF_insertTableData("logs", $fields_insert);
/*
		$fields = array('users_LOGIN'   => $this -> user['login'],
							'timestamp'	 => time(),
							'timestamp_now' => time(),
							'session_ip'	=> $_SERVER['REMOTE_ADDR']);
*/
  if ($this -> isLoggedIn()) {
   //eF_updateTableData("user_times", array("session_expired" => 1), "users_LOGIN='".$this -> user['login']."'");
  }
  $result = eF_getTableData("user_times", "id", "session_id = '".session_id()."' and users_LOGIN='".$this -> user['login']."'");
  if (sizeof($result) > 0) {
   eF_updateTableData("user_times", array("session_expired" => 0), "session_id = '".session_id()."' and users_LOGIN='".$this -> user['login']."'");
  } else {
   $fields = array("session_timestamp" => time(),
       "session_id" => session_id(),
       "session_expired" => 0,
       "users_LOGIN" => $_SESSION['s_login'],
       "timestamp_now" => time(),
       "time" => 0,
       "entity" => 'system',
       "entity_id" => 0);
   eF_insertTableData("user_times", $fields);
  }
  return true;
 }
 /**
	 * Check if this user is allowed to multiple logins
	 *
	 * This function checks the current system settings and returns true
	 * if the current user is allowed to be logged in to the system more than once
	 *
	 * @return boolean true if the user is allowed to loggin more than once
	 * @since 3.5.2
	 * @access private
	 */
 private function allowMultipleLogin() {
  $multipleLogins = unserialize($GLOBALS['configuration']['multiple_logins']);
  if ($multipleLogins) {
  	//var_dump($multipleLogins['groups']);
   if (
	   (
	   	array_key_exists('users', $multipleLogins) && 
	   	in_array($this -> user['login'], $multipleLogins['users']) 
	   ) || (
	   array_key_exists('user_types', $multipleLogins) && 
	   in_array($this -> user['user_type'], $multipleLogins['user_types'])
	   ) || (
	   array_key_exists('user_types', $multipleLogins) && 
	   in_array($this -> user['user_types_ID'], $multipleLogins['user_types']) 
	   )  || (
	   	array_key_exists('groups', $multipleLogins) &&
	   	!is_null($multipleLogins['groups']) &&
	   	array_intersect(array_keys($this -> getGroups()), $multipleLogins['groups'])
	   )	
   ) {
    if ($multipleLogins['global']) { //If global allowance is set to "true", it means that the above clause, which matches the exceptions, translates to "multiple logins are prohibited for this user"
     return false;
    } else {
     return true;
    }
   } else {
    if ($multipleLogins['global']) {
     return true;
    } else {
     return false;
    }
   }
  } else {
   return false;
  }
 }
 /**
	 * Check if the user is already logged in and update his timestamp
	 *
	 * This function examines the system database to decide whether the user is still logged in and updates current time
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> refreshLogin();							   //Returns true if the user is logged in
	 * </code>
	 *
	 * @return boolean True if the user is logged in
	 * @since 3.5.2
	 * @access public
	 */
 public function refreshLogin() {
  $result = eF_getTableData('user_times', 'id', "session_expired=0 and users_LOGIN='".$this -> user['login']."'");
  if (sizeof($result) > 0) {
   eF_updateTableData("user_times", array("timestamp_now" => time()), "id='".$result[0]['id']."'");
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Get the list of users that are currently online
	 *
	 * This function is used to get a list of the users that are currently online
	 * In addition, it logs out any inactive users, based on global setting
	 * <br>Example:
	 * <code>
	 * $online = MagesterUser :: getUsersOnline();
	 * </code>
	 *
	 * @param boolean $userType Return only users of the basic type $user_type
	 * @param int $interval The idle interval above which a user is logged out. If it's not specified, no logging out takes place
	 * @return array The list of online users
	 * @since 3.5.0
	 * @access public
	 */
 public static function getUsersOnline($interval = false) {
  $usersOnline = array();
  //A user may have multiple active entries on the user_times table, one for system, one for unit etc. Pick the most recent
//  $result = eF_getTableData("user_times", "users_LOGIN, timestamp_now, session_timestamp", "session_expired=0", "timestamp_now desc");
  $result = eF_getTableData("user_times,users", "users_LOGIN, users.name, users.surname, users.user_type, users.user_type, timestamp_now, session_timestamp", "users.login=user_times.users_LOGIN and session_expired=0", "timestamp_now desc");
  
  foreach ($result as $value) {
   if (!isset($parsedUsers[$value['users_LOGIN']])) {
    //print("\ntime difference for user: ".$value['users_LOGIN'].' and interval '.$interval.' and time()='.time().' - '.$value['timestamp_now'].': '.(time() - $value['timestamp_now'])."\n");
    if (time() - $value['timestamp_now'] < $interval || !$interval) {
    	$value['login'] = $value['users_LOGIN'];
     $usersOnline[$value['users_LOGIN']] = array('login' => $value['users_LOGIN'],
             'name'		   	=> $value['name'],
             'surname'	   	=> $value['surname'],
             'formattedLogin'=> formatLogin(false, $value),
             'user_type'	 => $value['user_type'],
             'timestamp_now' => $value['timestamp_now'],
             'time' => eF_convertIntervalToTime(time() - $value['session_timestamp']));
    } else {
     MagesterUserFactory :: factory($value['users_LOGIN']) -> logout();
    }
    $parsedUsers[$value['users_LOGIN']] = true;
   }
  }
  return $usersOnline;
 }
 /**
	 * Check if the user is already logged in
	 *
	 * This function examines the system logs to decide whether the user is still logged in
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> isLoggedIn();							   //Returns true if the user is logged in
	 * </code>
	 *
	 * @return boolean True if the user is logged in
	 * @since 3.5.0
	 * @access public
	 */
 public function isLoggedIn() {
  //$result = eF_getTableData('users_online', '*', "users_LOGIN='".$this -> user['login']."'");
  $result = eF_getTableData('user_times', 'users_LOGIN', "session_expired=0 and users_LOGIN='".$this -> user['login']."'");
  if (sizeof($result) > 0) {
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Delete user
	 *
	 * This function is used to delete a user from the system.
	 * The user cannot be deleted if he is the last system administrator.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> delete();
	 * </code>
	 *
	 * @return boolean True if the user was deleted successfully
	 * @since 3.5.0
	 * @access public
	 */
 public function delete() {
  $this -> logout();
  ///MODULES2 - Module user delete events - Before anything else
  // Get all modules (NOT only the ones that have to do with the user type)
  $modules = eF_loadAllModules();
  // Trigger all necessary events. If the function has not been re-defined in the derived module class, nothing will happen
  foreach ($modules as $module) {
   $module -> onDeleteUser($this -> user['login']);
  }
  try {
   $directory = new MagesterDirectory($this -> user['directory']);
   $directory -> delete();
  } catch (MagesterFileException $e) {
   $message = _USERDIRECTORYCOULDNOTBEDELETED.': '.$e -> getMessage().' ('.$e -> getCode().')'; //This does nothing at the moment
  }
  foreach ($this -> aspects as $aspect) {
   $aspect -> delete();
  }
  calendar::deleteUserCalendarEvents($this -> user['login']);
  eF_updateTableData("f_forums", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("f_messages", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("f_topics", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("f_poll", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("chatrooms", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("chatmessages", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("news", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_updateTableData("files", array("users_LOGIN" => ''), "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("f_folders", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("f_personal_messages", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("bookmarks", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("comments", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("f_users_to_polls", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("logs", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("rules", "users_LOGIN='".$this -> user['login']."'");
  //eF_deleteTableData("users_online", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("user_times", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("users_to_surveys", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("users_to_done_surveys", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("survey_questions_done", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("lessons_timeline_topics_data", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("events", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("profile_comments", "users_LOGIN='".$this -> user['login']."'");
  //This line was in MagesterProfessor and MagesterStudent without an obvious reason. Admins may also be members of groups
  eF_deleteTableData("users_to_groups", "users_LOGIN='".$this -> user['login']."'");
  
  MagesterUserDetails::deleteDetails($this -> user['id']);
  eF_deleteTableData("c_users_link", "parent_id ='".$this -> user['id']."' OR child_id = '" . $this -> user['id'] . "'");
  
  eF_deleteTableData("users", "login='".$this -> user['login']."'");
  eF_deleteTableData("notifications", "recipient='".$this -> user['login']."'");
  MagesterEvent::triggerEvent(array("type" => MagesterEvent::SYSTEM_REMOVAL, "users_LOGIN" => $this -> user['login'], "users_name" => $this -> user['name'], "users_surname" => $this -> user['surname']));
  return true;
 }
 /**
	 * Set user type
	 *
	 * This function is used to change the basic user type
	 * @param string The new user type
	 * @since 3.5.0
	 * @access public
	 */
 public function changeType($userType) {
  if (!in_array($userType, MagesterUser :: $basicUserTypes)) {
   throw new MagesterUserException(_INVALIDUSERTYPE.': '.$userType, MagesterUser :: INVALID_TYPE);
  }
  switch ($userType) {
   case 'student':
    eF_updateTableData("users", array("user_type" => "student"), "login='".$this -> user['login']."'");
    break;
   case 'professor':
    eF_updateTableData("users", array("user_type" => "professor"), "login='".$this -> user['login']."'");
    break;
   case 'administrator':
    eF_updateTableData("users", array("user_type" => "administrator"), "login='".$this -> user['login']."'");
    break;
   default: break;
  }
 }
 /**
	 * Persist user values
	 *
	 * This function is used to store user's changed values to the database.
	 * <br/>Example:
	 * <code>
	 * $user -> surname = 'doe';							//Change object's surname
	 * $user -> persist();								  //Persist changed value
	 * </code>
	 *
	 * @return boolean True if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function persist() {
  $fields = array('password' => $this -> user['password'],
      'email' => $this -> user['email'],
      'languages_NAME' => $this -> user['languages_NAME'],
      'name' => $this -> user['name'],
      'surname' => $this -> user['surname'],
      'active' => $this -> user['active'],
      'comments' => $this -> user['comments'],
      'user_type' => $this -> user['user_type'],
      'timestamp' => $this -> user['timestamp'],
      'avatar' => $this -> user['avatar'],
      'pending' => $this -> user['pending'],
      'user_types_ID' => $this -> user['user_types_ID'],
      'viewed_license' => $this -> user['viewed_license'],
      'status' => $this -> user['status'],
      'balance' => $this -> user['balance'],
      'archive' => $this -> user['archive'],
      'additional_accounts' => $this -> user['additional_accounts'],
      'short_description' => $this -> user['short_description'],
      'autologin' => $this -> user['autologin']);
  eF_updateTableData("users", $fields, "login='".$this -> user['login']."'");
  return true;
 }
 /**
	 * Get the user groups list
	 *
	 * <br/>Example:
	 * <code>
	 * $groupsList	= $user -> getGroups();						 //Returns an array with pairs [groups id] => [employee specification for this group]
	 * </code>
	 *
	 * @return array An array of [group id] => [group ID] pairs, or an array of group objects
	 * @since 3.5.0
	 * @access public
	 */
 public function getGroups() {
  if (! $this -> groups ) {
   $result = eF_getTableData("users_to_groups ug, groups g", "g.*", "ug.users_LOGIN = '".$this -> login."' and g.id=ug.groups_ID and g.active=1");
   foreach ($result as $group) {
    $this -> groups[$group['id']] = $group;
   }
  }
  return $this -> groups;
 }
 /**
	 * Assign a group to user.
	 *
	 * This function can be used to assign a group to a user
	 * <br/>Example:
	 * <code>
	 * $user = MagesterHcdUserFactory :: factory('jdoe');
	 * $user -> addGroups(23);						 //Add a single group with id 23
	 * $user -> addGroups(array(23,24,25));			//Add multiple groups using an array
	 * </code>
	 *
	 * @return int The array of lesson ids.
	 * @since 3.5.0
	 * @access public
	 * @todo auto_projects
	 */
 public function addGroups($groupIds) {
  $this -> groups OR $this -> getGroups(); //Populate $this -> groups if it is not already filled in
  if (!is_array($groupIds)) {
   $groupIds = array($groupIds);
  }
  foreach ($groupIds as $key => $groupId) {
   if (eF_checkParameter($groupId, 'id') && !isset($this -> groups[$groupId])) {
    $group = new MagesterGroup($groupId);
    $group -> addUsers($this -> user['login'], $this -> user['user_types_ID'] ? $this -> user['user_types_ID'] : $this -> user['user_type']);
    $this -> groups[$groupId] = $groupId;
    // Register group assignment into the event log - event log only available in HCD
   }
  }
  return $this -> groups;
 }
 /**
	 * Remove groups from employee.
	 *
	 * This function can be used to remove a group from the current employee.
	 * <br/>Example:
	 * <code>
	 * $employee = MagesterHcdUserFactory :: factory('jdoe');
	 * $employee -> removeGroups(23);						  //Remove a signle group with id 23
	 * $employee -> removeGroups(array(23,24,25));			 //Remove multiple groups using an array
	 * </code>
	 *
	 * @param int $groupIds Either a single group id, or an array if ids
	 * @return int The array of group ids.
	 * @since 3.5.0
	 * @access public
	 */
 public function removeGroups($groupIds) {
  $this -> groups OR $this -> getGroups(); //Populate $this -> groups if it is not already filled in
  if (!is_array($groupIds)) {
   $groupIds = array($groupIds);
  }
  foreach ($groupIds as $key => $groupId) {
   if (eF_checkParameter($groupId, 'id') && isset($this -> groups[$groupId])) {
    $group = new MagesterGroup($groupId);
    $group -> removeUsers($this -> user['login']);
    unset($this -> groups[$key]); //Remove groups from cache array."
    // Register group assignment into the event log - event log only available in HCD
   }
  }
  return $this -> groups;
 }
 ///MODULE3
 /**
	 * Get modules for this user (according to the user type).
	 *
	 * This function can is used to get the modules for the user
	 * <br/>Example:
	 * <code>
	 * $currentUser = MagesterUserFactory :: factory('jdoe');
	 * $modules = $currentUser -> getModules();
	 * </code>
	 *
	 * @param no parameter
	 * @return int The array of modules for the user type of this user.
	 * @since 3.5.0
	 * @access public
	 */
 public function getModules() {
  $modulesDB = eF_getTableData("modules","*","active = 1");
  
  $modules = array();
  isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] ? $user_type = $_SESSION['s_lesson_user_type'] : $user_type = $this -> getType();
  
  //var_dump($this -> getType());
  
  // Get all modules enabled for this user type
  foreach ($modulesDB as $module) {
   $folder = $module['position'];
   $className = $module['className'];
   // If a module is to be updated then its class should not be loaded now
   if (!($this -> getType() == "administrator" && isset($_GET['ctg']) && $_GET['ctg'] == "control_panel" && isset($_GET['op']) && $_GET['op'] == "modules" && $_GET['upgrade'] == $className)) {
   	
    if(is_dir(G_MODULESPATH.$folder) && is_file(G_MODULESPATH.$folder."/".$className.".class.php")) {
     require_once G_MODULESPATH.$folder."/".$className.".class.php";
     if (class_exists($className)) {
      $modules[$className] = new $className($user_type.".php?ctg=module&op=".$className, $folder);
      // Got to check if this is a lesson module so as to change the moduleBasePath
      if ($modules[$className] -> isLessonModule() && isset($GLOBALS['currentLesson'])) {
		$modules[$className] -> moduleBaseUrl = 
			$this -> getRole($GLOBALS['currentLesson']) .".php?ctg=module&op=".$className;
      }
      if (!in_array($user_type, $modules[$className] -> getPermittedRoles())) {
       unset($modules[$className]);
      }
     } else {
      $message = '"'.$className .'" '. _MODULECLASSNOTEXISTSIN . ' ' .G_MODULESPATH.$folder.'/'.$className.'.class.php';
      $message_type = 'failure';
     }
    } else {
    	//var_dump(is_dir(G_MODULESPATH.$folder));
    	//var_dump(is_file(G_MODULESPATH.$folder."/".$className.".class.php"));
    	var_dump('DELETE ' . $className);
    	exit;
     eF_deleteTableData("modules","className = '".$className."'");
     $message = _ERRORLOADINGMODULE . " " . $className . " " . _MODULEDELETED;
     $message_type = "failure";
    }
   }
  }
  return $modules;
 }
 
 /**
	 * Get the login time for on e or all users in the specified time interval
	 *
	 * This function returns the login time for the specified user in the specified interval
	 * <br/>Example:
	 * <code>
	 *	  $interval['from'] = "00000000";
	 *	  $interval['to']   = time();
	 *	  $time  = MagesterUser :: getLoginTime('jdoe', $interval); //$time['jdoe'] now holds his times
	 *	  $times = MagesterUser :: getLoginTime($interval); //$times now holds an array of times for all users
	 * </code>
	 *
	 * @param mixed $login The user to calulate times for, or false for all users
	 * @param mixed An array of the form (from =>'', to=>'') or false (return the total login time)
	 * @return the total login time as an array of hours, minutes, seconds
	 * @since 3.5.0
	 * @access public
	 */
 public static function getLoginTime($login = false, $interval = array()) {
  $times = new MagesterTimes($interval);
  if ($login) {
   $result = $times -> getUserTotalSessionTime($login);
   return $times -> formatTimeForReporting($result);
  } else {
   foreach ($times -> getSystemSessionTimesForUsers() as $login => $result) {
    $userTimes[$login] = $times -> formatTimeForReporting($result);
    return $userTimes;
   }
  }
 }
 /**
	 * Archive user
	 *
	 * This function is used to archive the user object, by setting its active status to 0 and its
	 * archive status to 1
	 * <br/>Example:
	 * <code>
	 * $user -> archive();	//Archives the user object
	 * $user -> unarchive();	//Archives the user object and activates it as well
	 * </code>
	 *
	 * @since 3.6.0
	 * @access public
	 */
 public function archive() {
  $this -> user['archive'] = time();
  $this -> persist();
  $this -> deactivate();
 }
 /**
	 * Unarchive user
	 *
	 * This function is used to unarchive the user object, by setting its active status to 1 and its
	 * archive status to 0
	 * <br/>Example:
	 * <code>
	 * $user -> archive();	//Archives the user object
	 * $user -> unarchive();	//Archives the user object and activates it as well
	 * </code>
	 *
	 * @since 3.6.0
	 * @access public
	 */
 public function unarchive() {
  $this -> activate();
  $this -> user['archive'] = 0;
  $this -> persist();
 }
 /**
	 * Apply role options to object
	 *
	 * This function is used to apply role options, using the specified role
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> applyRoleOptions(4);						//Apply the role options for user type with id 4 to the $user object
	 * </code>
	 *
	 * @param int $role The role id to apply options for
	 * @since 3.5.0
	 * @access public
	 */
 public function applyRoleOptions($role = false) {
  if (!$role) {
   $role = $this -> user['user_types_ID'];
  }
  
  if ($role) {
   $result = eF_getTableData("user_types", "*", "id='".$role."'");
   unserialize($result[0]['core_access']) ? $this -> coreAccess = unserialize($result[0]['core_access']) : null;
   unserialize($result[0]['modules_access']) ? $this -> moduleAccess = unserialize($result[0]['modules_access']) : null;
  }
 }
 /**
	 * Get system roles
	 *
	 * This function is used to get all the roles in the system
	 * It returns an array where keys are the role ids and values are:
	 * - Either the role basic user types, if $getNames is false (the default)
	 * - or the role Names if $getNames is true
	 * The array is prepended with the 3 main roles, 'administrator', 'professor' and 'student'
	 * <br/>Example:
	 * <code>
	 * $roles = MagesterUser :: getRoles();
	 * </code>
	 *
	 * @param boolean $getNames Whether to return id/basic user type pairs or id/name pairs
	 * @return array The system roles
	 * @since 3.5.0
	 * @access public
	 * @static
	 */
 public static function getRoles($getNames = false) {
  //Cache results in self :: $userRoles
  if (is_null(self :: $userRoles)) {
   $roles = eF_getTableDataFlat("user_types", "*", "active=1"); //Get available roles
   self :: $userRoles = $roles;
  } else {
   $roles = self :: $userRoles;
  }
  if (sizeof($roles) > 0) {
   $getNames ? $roles = self :: $basicUserTypesTranslations + array_combine($roles['id'], $roles['name']) : $roles = array_combine(self :: $basicUserTypes, self :: $basicUserTypes) + array_combine($roles['id'], $roles['basic_user_type']);
  } else {
   $getNames ? $roles = self :: $basicUserTypesTranslations : $roles = array_combine(self :: $basicUserTypes, self :: $basicUserTypes);
  }
  return $roles;
 }
 /**
	 * Get the user profile's comments list
	 *
	 * <br/>Example:
	 * <code>
	 * $commentsList	= $user -> getProfileComments();						 //Returns an array with pairs [groups id] => [employee specification for this group]
	 * </code>
	 *
	 * @return array A sorted according to timestamp array of [comment id] => [timestamp, authors_LOGIN, authors_name, authors_surname, data] pairs, or an array of comments
	 * @since 3.6.0
	 * @access public
	 */
 public function getProfileComments() {
  if ($GLOBALS['configuration']['social_modules_activated'] & SOCIAL_FUNC_COMMENTS) {
   $result = eF_getTableData("profile_comments JOIN users ON authors_LOGIN = users.login", "profile_comments.id, profile_comments.timestamp, authors_LOGIN, users.name, users.surname, users.avatar, data", "users_LOGIN = '".$this -> user['login']."'", "timestamp DESC");
   $comments = array();
   foreach ($result as $comment) {
    $comments[$comment['id']] = $comment;
   }
   return $comments;
  } else {
   return array();
  }
 }
 /**
	 *
	 * @param $pwd
	 * @return unknown_type
	 */
 public static function createPassword($pwd, $mode = 'magester') {
  if ($mode == 'magester') {
   $encrypted = md5($pwd.G_MD5KEY);
  } else {
   $encrypted = $pwd;
  }
  return $encrypted;
 }
 /**
	 * Convert the user argument to a user login
	 *
	 * @param mixed $login The argument to convert
	 * @return string The user's login
	 * @since 3.6.3
	 * @access public
	 * @static
	 */
 public static function convertArgumentToUserLogin($login) {
  if ($login instanceof MagesterUser) {
   $login = $login -> user['login'];
  } else if (!eF_checkParameter($login, 'login')) {
   throw new MagesterUserException(_INVALIDLOGIN, MagesterUserException::INVALID_LOGIN);
  }
  return $login;
 }
 public static function convertUserObjectsToArrays($userObjects) {
  foreach ($userObjects as $key => $value) {
   if ($value instanceOf MagesterUser) {
    $userObjects[$key] = $value -> user;
   }
  }
  return $userObjects;
 }
 public static function convertUserConstraintsToSqlParameters($constraints) {
  $where = MagesterUser::addWhereConditionToUserConstraints($constraints);
  $limit = MagesterUser::addLimitConditionToConstraints($constraints);
  $order = MagesterUser::addSortOrderConditionToConstraints($constraints);
  return array($where, $limit, $order);
 }
 public static function addWhereConditionToUserConstraints($constraints) {
  $where = array();
  if (isset($constraints['archive'])) {
   $constraints['archive'] ? $where[] = 'u.archive!=0' : $where[] = 'u.archive=0';
  }
  if (isset($constraints['active'])) {
   $constraints['active'] ? $where[] = 'u.active=1' : $where[] = 'u.active=0';
  }
  if (isset($constraints['filter']) && $constraints['filter']) {
   $result = eF_describeTable("users");
   $tableFields = array();
   foreach ($result as $value) {
    $tableFields[] = "u.".$value['Field'].' like "%'.$constraints['filter'].'%"';
   }
   $where[] = "(".implode(" OR ", $tableFields).")";
  }
  if (isset($constraints['condition'])) {
   $where[] = $constraints['condition'];
  }
  if (isset($constraints['table_filters'])) {
   foreach ($constraints['table_filters'] as $constraint) {
    $where[] = $constraint['condition'];
   }
  }
  return $where;
 }
 private static function addLimitConditionToConstraints($constraints) {
  $limit = '';
  if (isset($constraints['limit']) && eF_checkParameter($constraints['limit'], 'int') && $constraints['limit'] > 0) {
   $limit = $constraints['limit'];
  }
  if ($limit && isset($constraints['offset']) && eF_checkParameter($constraints['offset'], 'int') && $constraints['offset'] >= 0) {
   $limit = $constraints['offset'].','.$limit;
  }
  return $limit;
 }
 private static function addSortOrderConditionToConstraints($constraints) {
  $order = '';
  if (isset($constraints['sort']) && eF_checkParameter($constraints['sort'], 'alnum_with_spaces')) {
   $order = $constraints['sort'];
   if (isset($constraints['order']) && in_array($constraints['order'], array('asc', 'desc'))) {
    $order .= ' '.$constraints['order'];
   }
  }
  return $order;
 }
 public static function convertDatabaseResultToUserObjects($result) {
  $roles = MagesterLessonUser::getRoles();
  $userObjects = array();
  foreach ($result as $value) {
   $userObjects[$value['login']] = MagesterUserFactory::factory($value, false, ($value['role'] ? $roles[$value['role']] : false));
  }
  return $userObjects;
 }
 public static function convertDatabaseResultToUserArray($result) {
  $userArray = array();
  foreach ($result as $value) {
   $userArray[$value['login']] = $value;
  }
  return $userArray;
 }
 
	public static function clearAccents($subject) {
		$search = array(
			'à','á','â','ã','ä','å',
			'ç',
			'è','é','ê','ë',
			'ì','í','î','ï',
			'ñ',
			'ò','ó','ô','õ','ö',
			'ù','ü','ú','ÿ',
			'À','Á','Â','Ã','Ä','Å',
			'Ç',
			'È','É','Ê','Ë',
			'Ì','Í','Î','Ï',
			'Ñ',
			'Ò','Ó','Ô','Õ','Ö',
			'Ù','Ü','Ú','Ÿ',
			"'"
		);
		$replace = array(
			'a','a','a','a','a','a',
			'c',
			'e','e','e','e',
			'i','i','i','i',
			'n',
			'o','o','o','o','o',
			'u','u','u','y',
			'A','A','A','A','A','A',
			'C',
			'E','E','E','E',
			'I','I','I','I',
			'N',
			'O','O','O','O','O',
			'U','U','U','Y',
			""
		);
		return $subject = str_replace($search, $replace, $subject);
	} 
 
 	public function generateNewLogin($name, $surname) {
 		// SANITIZE DATA
 		
 		$name 		= trim(self::clearAccents($name));
 		$surname	= trim(self::clearAccents($surname));
		
 		if (strlen($name) > 0 && (strlen($surname) > 0)) {
			$firstname = explode(' ', $name);
			$firstname = $firstname[0];
 			
			$lastname = explode(' ', $surname);
 		
		 	if (strlen($lastname[count($lastname) - 1]) > 0) {
		 		$lastname = $lastname[count($lastname) - 1];
		 	} elseif (strlen($lastname[count($lastname) - 2]) > 0) {
		 		$lastname = $lastname[count($lastname) - 2];
			} elseif (strlen($lastname[count($lastname) - 3]) > 0) {
				$lastname = $lastname[count($lastname) - 3];
		 	} else {
		 		return false;
		 	}
 		}
 		$login = strtolower($firstname) . '.' .  strtolower($lastname);
 		
 		// CHECK LOGIN EXISTENCE AND ADD SEQUENCIAL NUMBERS IF NECESSARY
 		$originalLogin = $login;
 		$i = 1;
 		while(true) {
	 		try {
				$user = MagesterUserFactory :: factory($login);
				
				$login = $originalLogin . ($i++);
			} catch (MagesterUserException $e) {
				break;
			}
 		}			
		return $login;
 	}

 	
 	
	public function generateMD5Password($len = 7) {
		return substr(md5(rand().rand()), 0, $len);
	}
	/*
	public function loadUserTags() {
		// LOAD ALL MODULES, CALL $module->getUserTags($this) AND MERGE ARRAY RESULTS. 		
		var_dump($$this->getModules());
		exit;
	}
	*/
	/*
	public function getUserTags($user) {
    	if (is_numeric($user)) {
    		$userDB = eF_getTableData("users", "login", "id = " . $user);
    		$user = $userDB[0]['login'];
    	}
    	if (is_string($user)) {
    		$user = MagesterUserFactory::factory($user);
    	}
    	if ($user instanceof MagesterUser) {
    		$user = $user->user;
    	}
    	if (is_array($user)) {
    		$userData = $user;
    	} else {
    		return false;
    	}
    	
    	$index = strtolower($this->getName());
    	
    	return array(
    		$index => $userData["user_type"]
    	);
	 }
	 */
}
/**
 * Class for administrator users
 *
 * @package SysClass
 */



class MagesterAdministrator extends MagesterUser
{
 /**
	 * Get user information
	 *
	 * This function returns the user information in an array
	 *
	 *
	 * <br/>Example:
	 * <code>
	 * $info = $user -> getInformation();		 //Get lesson information
	 * </code>
	 *
	 * @param string $user The user login to customize lesson information for
	 * @return array The user information
	 * @since 3.5.0
	 * @access public
	 */
 public function getInformation() {
  $languages = MagesterSystem :: getLanguages(true);
  $info = array();
  $info['login'] = $this -> user['login'];
  $info['name'] = $this -> user['name'];
  $info['surname'] = $this -> user['surname'];
  $info['fullname'] = $this -> user['name'] . " " . $this -> user['surname'];
  $info['user_type'] = $this -> user['user_type'];
  $info['user_types_ID'] = $this -> user['user_types_ID'];
  $info['student_lessons'] = array();
  $info['professor_lessons'] = array();
  $info['total_lessons'] = 0;
  $info['total_login_time'] = self :: getLoginTime($this -> user['login']);
  $info['language'] = $languages[$this -> user['languages_NAME']];
  $info['active'] = $this -> user['active'];
  $info['active_str'] = $this -> user['active'] ? _YES : _NO;
  $info['joined'] = $this -> user['timestamp'];
  $info['joined_str'] = formatTimestamp($this -> user['timestamp'], 'time');
  $info['avatar'] = $this -> user['avatar'];
  return $info;
 }
 public function getRole() {
  return "administrator";
 }
 /*
	 * Social _magester function
	 *
	 * For administrators it should return all users
	 */
 public function getRelatedUsers() {
  $all_users = MagesterUser::getUsers(true);
  foreach($all_users as $key=>$login) {
   if ($login == $this -> user['login']) {
    unset($all_users[$key]);
    break;
   }
  }
  return $all_users;
 }
 /**
	 *
	 * @return unknown_type
	 */
 public function getLessons() {
  return array();
 }
 public function getIssuedCertificates() {
  return array();
 }
}

class MagesterCoordenator extends MagesterAdministrator {}

class MagesterFinancial extends MagesterAdministrator {}

class MagesterSecretary extends MagesterAdministrator {}

/**
 * Class for users that may have lessons
 *
 * @package SysClass
 * @abstract
 */
abstract class MagesterLessonUser extends MagesterUser
{
 /**
	 * A caching variable for user types
	 *
	 * @since 3.5.3
	 * @var array
	 * @access private
	 * @static
	 */
 private static $lessonRoles;
 /**
	 * The user lessons array.
	 *
	 * @since 3.5.0
	 * @var array
	 * @access public
	 */
 public $lessons = false;
 /**
	 * Assign lessons to user.
	 *
	 * This function can be used to assign a lesson to the current user. If $userTypes
	 * is specified, then the user is assigned to the lesson using the specified type.
	 * By default, the user basic type is used.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> addLessons(23);						 //Add a signle lesson with id 23
	 * $user -> addLessons(23, 'professor');			//Add a signle lesson with id 23 and set the user type to 'professor'
	 * $user -> addLessons(array(23,24,25));			//Add multiple lessons using an array
	 * $user -> addLessons(array(23,24,25), array('professor', 'student', 'professor'));			//Add multiple lessons using an array for lesson ids and another for corresponding user types
	 * </code>
	 *
	 * @param mixed $lessonIds Either a single lesson id, or an array if ids
	 * @param mixed $userTypes The corresponding user types for the specified lessons
	 * @param boolean $activate Lessons will be set as active or not
	 * @return mixed The array of lesson ids or false if the lesson already exists.
	 * @since 3.5.0
	 * @access public
	 */
 public function addLessons($lessonIds, $userTypes, $activate = 1) {
  if (sizeof($this -> lessons) == 0) {
   $this -> getLessons();
  }
  if (!is_array($lessonIds)) {
   $lessonIds = array($lessonIds);
  }
  if (!is_array($userTypes)) {
   $userTypes = array($userTypes);
  }
  if (sizeof($userTypes) < sizeof($lessonIds)) {
    $userTypes = array_pad($userTypes, sizeof($lessonIds), $userTypes[0]);
  }
  if (sizeof($lessonIds) > 0) {
   $lessons = eF_getTableData("lessons", "*", "id in (".implode(",", $lessonIds).")");
   foreach ($lessons as $key => $lesson) {
    $lesson = new MagesterLesson($lesson);
    $lesson -> addUsers($this -> user['login'], $userTypes[$key], $activate);
   }
   $this -> lessons = false; //Reset lessons information
  }
  return $this -> getLessons();
 }
 /**
	 * Confirm user's lessons
	 *
	 * This function can be used to set the "active" flag of a user's lesson to "true", so that
	 * he can access the corresponding lessons.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> confirmLessons(23);						 //Confirms the lesson with id 23
	 * $user -> addLessons(array(23,24,25));			//Confirms multiple lessons using an array
	 * </code>
	 *
	 * @param mixed $lessonIds Either a single lesson id, or an array if ids
	 * @return array The array of lesson ids
	 * @since 3.6.0
	 * @access public
	 */
 public function confirmLessons($lessonIds) {
  if (sizeof($this -> lessons) == 0) {
   $this -> getLessons();
  }
  if (!is_array($lessonIds)) {
   $lessonIds = array($lessonIds);
  }
  $lessons = eF_getTableData("lessons", "*", "id in (".implode(",", $lessonIds).")");
  foreach ($lessons as $key => $lesson) {
   $lesson = new MagesterLesson($lesson);
   $lesson -> confirm($this -> user['login']);
  }
  $this -> lessons = false; //Reset lessons information
  return $this -> getLessons();
 }
 /**
	 * Remove lessons from user.
	 *
	 * This function can be used to remove a lesson from the current user.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> removeLessons(23);						  //Remove a signle lesson with id 23
	 * $user -> removeLessons(array(23,24,25));			 //Remove multiple lessons using an array
	 * </code>
	 *
	 * @param int $lessonIds Either a single lesson id, or an array if ids
	 * @return int The array of lesson ids.
	 * @since 3.5.0
	 * @access public
	 */
 public function removeLessons($lessonIds) {
  if (!is_array($lessonIds)) {
   $lessonIds = array($lessonIds);
  }
  foreach ($lessonIds as $key => $lessonID) {
   if (!eF_checkParameter($lessonID, 'id')) {
    unset($lessonIds[$key]); //Remove illegal vaues from lessons array.
   }
  }
  eF_deleteTableData("users_to_lessons", "users_LOGIN = '".$this -> user['login']."' and lessons_ID in (".implode(",", $lessonIds).")"); //delete lessons from list
  foreach ($lessonIds as $lessonId) {
   $cacheKey = "user_lesson_status:lesson:".$lessonId."user:".$this -> user['login'];
   Cache::resetCache($cacheKey);
  }
  //Timelines event
  MagesterEvent::triggerEvent(array("type" => MagesterEvent::LESSON_REMOVAL, "users_LOGIN" => $this -> user['login'], "lessons_ID" => $lessonIds));
  $userLessons = eF_getTableDataFlat("users_to_lessons", "lessons_ID, user_type", "users_LOGIN = '".$this -> user['login']."'");
  $this -> lessons = array_combine($userLessons['lessons_ID'], $userLessons['user_type']);
  return $this -> lessons;
 }
 /**
	 * Reset the user's progress in the specified lesson
	 *
	 * @param mixed $lesson The lesson to reset
	 * @since 3.6.3
	 * @access public
	 */
 public function resetProgressInLesson($lesson) {
  if (!($lesson instanceOf MagesterLesson)) {
   $lesson = new MagesterLesson($lesson);
  }
  $tracking_info = array("done_content" => "",
          "issued_certificate" => "",
          "from_timestamp" => time(),
          "to_timestamp" => null,
          "comments" => "",
          "completed" => 0,
          "current_unit" => 0,
          "score" => 0);
  eF_updateTableData("users_to_lessons", $tracking_info, "users_LOGIN='".$this -> user['login']."' and lessons_ID = ".$lesson -> lesson['id']);
  eF_deleteTableData("completed_tests", "users_LOGIN = '".$this -> user['login']."' and tests_ID in (select id from tests where lessons_ID='".$lesson -> lesson['id']."')");
  eF_deleteTableData("scorm_data", "users_LOGIN = '".$this -> user['login']."' and content_ID in (select id from content where lessons_ID='".$lesson -> lesson['id']."')");
 }
 public function resetProgressInAllLessons() {
  $tracking_info = array("done_content" => "",
          "issued_certificate" => "",
          "from_timestamp" => time(),
          "to_timestamp" => null,
          "comments" => "",
          "completed" => 0,
          "current_unit" => 0,
          "score" => 0);
  eF_updateTableData("users_to_lessons", $tracking_info, "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("completed_tests", "users_LOGIN = '".$this -> user['login']."'");
  eF_deleteTableData("scorm_data", "users_LOGIN = '".$this -> user['login']."'");
 }
 /**
	 * Reset the user's progress in the specified course
	 *
	 * @param mixed $course The course to reset
	 * @param boolean $resetLessons whether to reset lesson progress as well
	 * @since 3.6.3
	 * @access public
	 */
 public function resetProgressInCourse($course, $resetLessons = false) {
  if (!($course instanceOf MagesterCourse)) {
   $course = new MagesterLesson($course);
  }
  $tracking_info = array("issued_certificate" => "",
          "comments" => "",
          "from_timestamp" => time(),
          "to_timestamp" => 0,
          "completed" => 0,
          "score" => 0);
  eF_updateTableData("users_to_courses", $tracking_info, "users_LOGIN='".$this -> user['login']."' and courses_ID = ".$course -> course['id']);
  if ($resetLessons) {
   foreach ($course -> getCourseLessons() as $lesson) {
    $this -> resetProgressInLesson($lesson);
   }
  }
 }
 public function resetProgressInAllCourses() {
  $tracking_info = array("issued_certificate" => "",
          "comments" => "",
          "from_timestamp" => time(),
          "to_timestamp" => 0,
          "completed" => 0,
          "score" => 0);
  eF_updateTableData("users_to_courses", $tracking_info, "users_LOGIN='".$this -> user['login']."'");
 }
 /**
	 * Get the users's lessons list
	 *
	 * This function is used to get a list of ids with the users's lessons.
	 * If $returnObjects is set and true, then An array of lesson objects is returned
	 * The list is returned using the object's cache (unless $returnObjects is true).
	 * <br/>Example:
	 * <code>
	 * $lessonsList	= $user -> getLessons();						 //Returns an array with pairs [lessons id] => [user type]
	 * $lessonsObjects = $user -> getLessons(true);					 //Returns an array of lesson objects
	 * </code>
	 * If $returnObjects is specified, then each lesson in the lessons array will
	 * contain an additional field holding information on the user's lesson status
	 *
	 * @param boolean $returnObjects Whether to return lesson objects
	 * @param string $basicType If set, then return only lessons that the user has the specific basic role in them
	 * @return array An array of [lesson id] => [user type] pairs, or an array of lesson objects
	 * @since 3.5.0
	 * @access public
	 */
 public function getLessons($returnObjects = false, $basicType = false) {
  if ($this -> lessons && !$returnObjects) {
   $userLessons = $this -> lessons;
  } else {
   if ($returnObjects) {
    $userLessons = array();
    //Assign all lessons to an array, this way avoiding looping queries
    $result = eF_getTableData("lessons l, users_to_lessons ul", "l.*", "l.archive=0 and l.id=ul.lessons_ID and ul.archive = 0 and ul.users_LOGIN = '".$this -> user['login']."'", "l.name");
    foreach ($result as $value) {
     $lessons[$value['id']] = $value;
    }
    $courseLessons = array();
    $nonCourseLessons = array();
    $result = eF_getTableData("users u,users_to_lessons ul, lessons l", "ul.*, u.user_type as basic_user_type, u.user_types_ID", "l.archive=0 and l.id = ul.lessons_ID and ul.archive=0 and ul.users_LOGIN = u.login and ul.users_LOGIN = '".$this -> user['login']."' and ul.lessons_ID != 0", "l.name");
    foreach ($result as $value) {
     try {
      $lesson = new MagesterLesson($lessons[$value['lessons_ID']]);
      $lesson -> userStatus = $value;
      if ($lesson -> lesson['course_only']) {
       $courseLessons[$value['lessons_ID']] = $lesson;
      } else {
       $nonCourseLessons[$value['lessons_ID']] = $lesson;
      }
     } catch (Exception $e) {} //Do nothing in case of exception, simply do not take into account this lesson
    }
    $userLessons = $courseLessons + $nonCourseLessons;
   } else {
    $result = eF_getTableDataFlat("users_to_lessons ul, lessons l", "ul.lessons_ID, ul.user_type", "l.archive=0 and ul.archive=0 and ul.lessons_ID=l.id and ul.users_LOGIN = '".$this -> user['login']."'", "l.name");
    if (sizeof($result) > 0) {
     $this -> lessons = array_combine($result['lessons_ID'], $result['user_type']);
    } else {
     $this -> lessons = array();
    }
    foreach ($this -> lessons as $lessonId => $userType) {
     if (!$userType) { //For some reason, the user type is not set in the database. so set it now
      $userType = $this -> user['user_type'];
      $this -> lessons[$lessonId] = $userType;
      eF_updateTableData("users_to_lessons", array("user_type" => $userType), "lessons_ID=$lessonId and users_LOGIN='".$this -> user['login']."'");
      $cacheKey = "user_lesson_status:lesson:".$lessonId."user:".$this -> user['login'];
      Cache::resetCache($cacheKey);
     }
    }
    unset($userType);
    $userLessons = $this -> lessons;
   }
  }
  if ($basicType) {
   $roles = MagesterLessonUser :: getLessonsRoles();
   foreach ($userLessons as $id => $role) {
    if ($role instanceof MagesterLesson) { //$returnObjects is true
     if ($roles[$role -> userStatus['user_type']] != $basicType) {
      unset($userLessons[$id]);
     }
    } else {
     if ($roles[$role] != $basicType) {
      unset($userLessons[$id]);
     }
    }
   }
  }
  return $userLessons;
 }
 //@TODO: REPLACE getLessons
 public function getUserLessons($constraints = array()) {
  //if ($this -> lessons === false) {			//COMMENT-IN WHEN IT REPLACES getLessons()
  $this -> initializeLessons();
  //}
  $lessons = array();
  foreach ($this -> lessons as $key => $lesson) {
   if (!isset($constraints['return_objects']) || $constraints['return_objects']) {
    $lessons[$key] = new MagesterLesson($lesson);
   } else {
    $lessons[$key] = $lesson;
   }
  }
  return $lessons;
 }
 /**
	 * Initialize user lessons
	 *
	 * @since 3.6.1
	 * @access protected
	 */
 private function initializeLessons() {
  $result = eF_getTableData("users_to_lessons ul, lessons l",
          "ul.*, ul.to_timestamp as timestamp_completed, ul.from_timestamp as active_in_lesson, l.id, l.name, l.directions_ID, l.course_only, l.instance_source, l.duration,l.options,l.to_timestamp,l.from_timestamp, l.active, 1 as has_lesson",
          "l.archive = 0 and ul.archive = 0 and l.id=ul.lessons_ID and ul.users_LOGIN='".$this -> user['login']."'");
  if (empty($result)) {
   $this -> lessons = array();
  } else {
   foreach ($result as $value) {
    $this -> lessons[$value['id']] = $value;
   }
  }
 }
 public function getUserAutonomousLessons($constraints = array()) {
  $lessons = $this -> getUserLessons($constraints);
  foreach ($lessons as $key => $lesson) {
   if ($lesson -> lesson['instance_source']) {
    unset($lessons[$key]);
   }
  }
  return $lessons;
 }
 /**
	 * Get user's eligible lessons
	 *
	 * This function is used to filter the user's lessons, excluding all the lessons
	 * that he is enrolled to, but cannot access for some reason (rules, schedule, active, etc)
	 *
	 * <br/>Example:
	 * <code>
	 * $eligibleLessons = $user -> getEligibleLessons();						 //Returns an array of MagesterLesson objects
	 * </code>
	 *
	 * @return array An array of lesson objects
	 * @since 3.6.0
	 * @access public
	 * @see libraries/MagesterLessonUser#getLessons($returnObjects, $basicType)
	 */
 public function getEligibleLessons() {
  $userCourses = $this -> getUserCourses();
  $userLessons = $this -> getUserStatusInLessons(false, true);
//pr($userLessons);
  $roles = self :: getLessonsRoles();
  $roleNames = self :: getLessonsRoles(true);
  foreach ($userCourses as $course) {
   $eligible = $course -> checkRules($this -> user['login'], $userLessons);
   foreach ($eligible as $lessonId => $value) {
    if (!$value) {
     unset($userLessons[$lessonId]);
    }
   }
  }
  $eligibleLessons = array();
  foreach ($userLessons as $lesson) {
   if ($lesson -> lesson['active_in_lesson'] && (!isset($lesson -> lesson['eligible']) || (isset($lesson -> lesson['eligible']) && $lesson -> lesson['eligible']))) {
    $eligibleLessons[$lesson -> lesson['id']] = $lesson;
   }
  }
  return $eligibleLessons;
 }
 /**
	 * Get user potential lessons
	 *
	 * This function returns a list with the lessons that the user
	 * may take, but doesn't have. The list may be either a list of ids
	 * (faster) or a list of MagesterLesson objects.
	 * <br/>Example:
	 * <code>
	 * $user -> getNonLessons();			//Returns a list with potential lessons ids
	 * $user -> getNonLessons(true);		//Returns a list of MagesterLesson objects
	 * </code>
	 *
	 * @param boolean $returnObjects Whether to return a list of objects
	 * @return array The list of ids or objects
	 * @since 3.5.0
	 * @access public
	 */
 public function getNonLessons($returnObjects = false) {
  $userLessons = eF_getTableDataFlat("users_to_lessons", "lessons_ID", "archive=0 and users_LOGIN = '".$this -> user['login']."'");
  //sizeof($userLessons) > 0 ? $sql = "and id not in (".implode(",", $userLessons['lessons_ID']).")" : $sql = '';
  sizeof($userLessons) > 0 ? $sql = "active = 1 and id not in (".implode(",", $userLessons['lessons_ID']).")" : $sql = 'active = 1';
  if ($returnObjects) {
   $nonUserLessons = array();
   //$lessons		= eF_getTableData("lessons", "*", "languages_NAME='".$this -> user['languages_NAME']."'".$sql);
   $lessons = eF_getTableData("lessons", "*", $sql);
   foreach ($lessons as $value) {
    $nonUserLessons[$value['id']] = new MagesterLesson($value['id']);
   }
   return $nonUserLessons;
  } else {
   //$lessons = eF_getTableDataFlat("lessons", "*", "languages_NAME='".$this -> user['languages_NAME']."'".$sql);
   $lessons = eF_getTableDataFlat("lessons", "*", $sql);
   return $lessons['id'];
  }
 }
 /**
	 * Return only non lessons that can be selected by the student
	 *
	 * This function is similar to getNonLessons, the only difference being that it excludes lessons
	 * that can't be directly assigned, for example inactive, unpublished etc
	 *
	 * @return array The eligible lessons
	 * @since 3.6.0
	 * @access public
	 * @see MagesterLessonUser :: getNonLessons()
	 */
 public function getEligibleNonLessons() {
  $lessons = $this -> getNonLessons(true);
  foreach ($lessons as $key => $lesson) {
   if (!$lesson -> lesson['active'] || !$lesson -> lesson['publish'] || !$lesson -> lesson['show_catalog']) {
    unset($lessons[$key]);
   }
  }
  return $lessons;
 }
 public function getUserCourses($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  $select['main'] = 'c.id, uc.users_LOGIN,uc.courses_ID,uc.classe_id,uc.completed,uc.score,uc.user_type,uc.course_type,uc.issued_certificate,uc.from_timestamp as active_in_course, uc.to_timestamp, 1 as has_course';
  $select['has_instances'] = "(select count( * ) from courses c1, users_to_courses uc1 where c1.instance_source=c.id and uc1.courses_ID=c1.id and uc.users_LOGIN='".$this -> user['login']."') as has_instances";
  $select['num_lessons'] = "(select count( * ) from lessons_to_courses cl, lessons l where cl.courses_ID=c.id and l.archive=0 and l.id=cl.lessons_ID) as num_lessons";
  $select['num_students'] = "(select count( * ) from users_to_courses uc, users u where uc.courses_ID=c.id and u.archive=0 and u.login=uc.users_LOGIN and u.user_type='student') as num_students";
  $select = MagesterCourse :: convertCourseConstraintsToRequiredFields($constraints, $select);
  
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  $where[] = "c.id=uc.courses_ID and uc.users_LOGIN='".$this -> user['login']."' and uc.archive=0";
  //$result  = eF_getTableData("courses c, users_to_courses uc", $select, implode(" and ", $where), $orderby, false, $limit);
  $sql = prepareGetTableData("courses c, users_to_courses uc", implode(",", $select), implode(" and ", $where), $orderby, false, $limit);
  $result = eF_getTableData("courses, ($sql) t", "courses.*, t.*", "courses.id=t.id");
  if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
   return MagesterCourse :: convertDatabaseResultToCourseObjects($result);
  } else {
   return MagesterCourse :: convertDatabaseResultToCourseArray($result);
  }
 }
 public function countUserCourses($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  $where[] = "c.id=uc.courses_ID and uc.users_LOGIN='".$this -> user['login']."' and uc.archive=0";
  $result = eF_countTableData("courses c, users_to_courses uc", "c.id", implode(" and ", $where));
  return $result[0]['count'];
 }
 public function getUserCoursesClasses($constraints = array()) {
	!empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
	$select['course'] = 
"c.ies_id, c.description, c.price, c.enable_registration, c.price_registration, c.enable_presencial, c.price_presencial, c.enable_web, c.price_web,	
c.show_catalog, c.publish, c.directions_ID, c.reset, c.certificate_expiration, c.rules, c.terms, c.instance_source, c.supervisor_LOGIN";
	$select['classes'] = 
"cl.id, cl.courses_ID, cl.start_date, cl.end_date, cl.name, cl.info, cl.duration, cl.options, cl.languages_NAME, cl.metadata, cl.share_folder, 
cl.created, cl.max_users";
	$select['user2courses'] = "uc.classe_id as classes_ID, uc.active, uc.archive, uc.user_type";

//  $select['main'] = 'c.id, uc.users_LOGIN,uc.courses_ID,uc.completed,uc.score,uc.user_type,uc.course_type,uc.issued_certificate,uc.from_timestamp as active_in_course, uc.to_timestamp, 1 as has_course';
  //$select['has_instances'] = "(select count( * ) from courses c1, users_to_courses uc1 where c1.instance_source=c.id and uc1.courses_ID=c1.id and uc.users_LOGIN='".$this -> user['login']."') as has_instances";
  //$select['num_lessons'] = "(select count( * ) from lessons_to_courses cl, lessons l where cl.courses_ID=c.id and l.archive=0 and l.id=cl.lessons_ID) as num_lessons";
  //$select['num_students'] = "(select count( * ) from users_to_courses uc, users u where uc.courses_ID=c.id and u.archive=0 and u.login=uc.users_LOGIN and u.user_type='student') as num_students";
  
  //$select = MagesterCourse :: convertCourseConstraintsToRequiredFields($constraints, $select);
  list($where, $limit, $orderby) = MagesterCourse :: convertClassesConstraintsToSqlParameters($constraints);
  $where[] = "c.id=cl.courses_ID and uc.classe_id = cl.id AND uc.users_LOGIN='".$this -> user['login']."' and uc.archive=0";
  //$result  = eF_getTableData("courses c, users_to_courses uc", $select, implode(" and ", $where), $orderby, false, $limit);
  $sql = prepareGetTableData("courses c, classes cl, users_to_courses uc", implode(",", $select), implode(" and ", $where), $orderby, false, $limit);
  $result = eF_getTableData("courses, ($sql) t", "courses.*, t.*", "courses.id=t.courses_ID");
  
  
  if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
   return MagesterCourse :: convertDatabaseResultToClassesObjects($result);
  } else {
   return MagesterCourse :: convertDatabaseResultToClassesArray($result);
  }
 }
 public function getUserPolo($constraints = array()) {
	!empty($constraints) OR $constraints = array('active' => true);
	
	$polo = eF_getTableData(
		"module_xuser user JOIN module_polos polo ON (user.polo_id = polo.id)",
		"polo.*", 
		"user.id = " . $this->user['id']
	);
	
	if (count($polo) > 0) {
		return $polo[0];
	} else {
		return false;
	}
 }
 
 
 
 public function getUserCoursesIncludingUnassigned($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  $select['main'] = "c.id, r.courses_ID is not null as has_course, r.completed,r.score, r.from_timestamp as active_in_course";
  $select['user_type'] = "(select user_type from users_to_courses uc1 where users_login='".$this -> user['login']."' and uc1.courses_ID=c.id) as user_type";
  $select['has_instances'] = "(select count( * ) from courses l where instance_source=c.id) as has_instances";
  $select['num_lessons'] = "(select count( * ) from lessons_to_courses cl, lessons l where cl.courses_ID=c.id and l.archive=0 and l.id=cl.lessons_ID) as num_lessons";
  $select['num_students'] = "(select count( * ) from users_to_courses uc, users u where uc.courses_ID=c.id and u.archive=0 and u.login=uc.users_LOGIN and u.user_type='student') as num_students";
  $select = MagesterCourse :: convertCourseConstraintsToRequiredFields($constraints, $select);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  //$result  = eF_getTableData("courses c left outer join (select completed,score,courses_ID, from_timestamp,archive from users_to_courses where users_login='".$this -> user['login']."' and archive=0) r on c.id=r.courses_ID ", $select, implode(" and ", $where), $orderby, "", $limit);
  $sql = prepareGetTableData("courses c left outer join (select completed,score,courses_ID, from_timestamp,archive from users_to_courses where users_login='".$this -> user['login']."' and archive=0) r on c.id=r.courses_ID ", implode(",", $select), implode(" and ", $where), $orderby, "", $limit);
  $result = eF_getTableData("courses, ($sql) t", "courses.*, t.*", "courses.id=t.id");
  if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
   return MagesterCourse :: convertDatabaseResultToCourseObjects($result);
  } else {
   return MagesterCourse :: convertDatabaseResultToCourseArray($result);
  }
 }
 public function countUserCoursesIncludingUnassigned($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  //$where[] = "d.id=c.directions_ID";
  $result = eF_countTableData("courses c left outer join (select completed,score,courses_ID, from_timestamp from users_to_courses where users_login='".$this -> user['login']."' and archive=0) r on c.id=r.courses_ID ", "c.id",
  implode(" and ", $where));
  return $result[0]['count'];
 }
 /**
	 * Get all courses, signifying those that the user already has, and aggregate instance results
	 *
	 * @param array $constraints The constraints for the query
	 * @return array An array of MagesterCourse objects
	 * @since 3.6.2
	 * @access public
	 */
 public function getUserCoursesAggregatingResultsIncludingUnassigned($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  if (isset($constraints['active']) && $constraints['active']) {
   $activeSql = 'and c1.active=1';
  } else if (isset($constraints['active']) && !$constraints['active']) {
   $activeSql = 'and c1.active=0';
  } else {
   $activeSql = '';
  }
  $select['main'] = 'c.id';
  $select['user_type'] = "(select user_type from users_to_courses uc1 where users_login='".$this -> user['login']."' and uc1.courses_ID=c.id) as user_type";
  $select['score'] = "(select max(score) 	 from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as score";
  $select['completed'] = "(select max(completed) from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as completed";
  $select['to_timestamp'] = "(select max(to_timestamp) from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as to_timestamp";
  $select['active_in_course'] = "(select max(from_timestamp) from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as active_in_course";
  $select['has_course'] = "(select count(*) > 0   from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as has_course";
  $select['num_lessons'] = "(select count( * ) from lessons_to_courses cl, lessons l where cl.courses_ID=c.id and l.archive=0 and l.id=cl.lessons_ID) as num_lessons";
  $select['num_students'] = "(select count( * ) from users_to_courses uc, users u where uc.courses_ID=c.id and u.archive=0 and u.login=uc.users_LOGIN and u.user_type='student') as num_students";
  $select = MagesterCourse :: convertCourseConstraintsToRequiredFields($constraints, $select);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  //WITH THIS NEW QUERY, WE GET THE SLOW 'has_instances' PROPERTY AFTER FILTERING
  $sql = prepareGetTableData("courses c left outer join (select id from courses) r on c.id=r.id", implode(",", $select), implode(" and ", $where), $orderby, false, $limit);
  $result = eF_getTableData(
     "courses, ($sql) t",
     "courses.*, (select count(id) from courses c1 where c1.instance_source=courses.id ) as has_instances, t.*",
     "courses.id=t.id");
  //THIS WAS THE OLD QUERY, MUCH SLOWER
  //$result  = eF_getTableData("courses c left outer join (select id from courses) r on c.id=r.id", $select, implode(" and ", $where), $orderby, false, $limit);
  if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
   return MagesterCourse :: convertDatabaseResultToCourseObjects($result);
  } else {
   return MagesterCourse :: convertDatabaseResultToCourseArray($result);
  }
 }
 public function countUserCoursesAggregatingResultsIncludingUnassigned($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  //$where[] = "d.id=c.directions_ID";
  $result = eF_countTableData("courses c left outer join (select id from courses) r on c.id=r.id", "c.id",
  implode(" and ", $where));
  return $result[0]['count'];
 }
 /**
	 * The same as self::getUserCoursesAggregatingResultsIncludingUnassigned, only it has an addition "where" condition
	 * @param array $constraints
	 * @return array
	 * @since 3.6.2
	 */
 public function getUserCoursesAggregatingResults($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  $select['main'] = 'c.id';
  $select['user_type'] = "(select user_type from users_to_courses uc1 where users_login='".$this -> user['login']."' and uc1.courses_ID=c.id) as user_type";
  $select['score'] = "(select max(score) 	 from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as score";
  $select['completed'] = "(select max(completed) from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as completed";
  $select['to_timestamp'] = "(select max(to_timestamp) from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as to_timestamp";
  $select['active_in_course'] = "(select max(from_timestamp) from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as active_in_course";
  $select['has_course'] = "(select count(*) > 0   from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID) as has_course";
  $select['num_lessons'] = "(select count( * ) from lessons_to_courses cl, lessons l where cl.courses_ID=c.id and l.archive=0 and l.id=cl.lessons_ID) as num_lessons";
  $select['num_students'] = "(select count( * ) from users_to_courses uc, users u where uc.courses_ID=c.id and u.archive=0 and u.login=uc.users_LOGIN and u.user_type='student') as num_students";
  $select = MagesterCourse :: convertCourseConstraintsToRequiredFields($constraints, $select);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  if (isset($constraints['active']) && $constraints['active']) {
   $activeSql = 'and c1.active=1';
  } else if (isset($constraints['active']) && !$constraints['active']) {
   $activeSql = 'and c1.active=0';
  } else {
   $activeSql = '';
  }
  $where[] = "(select count(*) > 0 from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and c1.archive = 0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID)=1";
  //WITH THIS NEW QUERY, WE GET THE SLOW 'has_instances' PROPERTY AFTER FILTERING
  $sql = prepareGetTableData("courses c left outer join (select id from courses) r on c.id=r.id", implode(",", $select), implode(" and ", $where), $orderby, false, $limit);
  $result = eF_getTableData(
     "courses, ($sql) t",
     "courses.*, (select count(id) from courses c1 where c1.instance_source=courses.id ) as has_instances, t.*",
     "courses.id=t.id");
  //THIS WAS THE OLD QUERY, MUCH SLOWER
  //$result  = eF_getTableData("courses c left outer join (select id from courses) r on c.id=r.id", $select, implode(" and ", $where), $orderby, false, $limit);
  if (!isset($constraints['return_objects']) || $constraints['return_objects'] == true) {
   return MagesterCourse :: convertDatabaseResultToCourseObjects($result);
  } else {
   return MagesterCourse :: convertDatabaseResultToCourseArray($result);
  }
 }
 public function countUserCoursesAggregatingResults($constraints = array()) {
  !empty($constraints) OR $constraints = array('archive' => false, 'active' => true);
  list($where, $limit, $orderby) = MagesterCourse :: convertCourseConstraintsToSqlParameters($constraints);
  $where[] = "d.id=c.directions_ID";
  if (isset($constraints['active']) && $constraints['active']) {
   $activeSql = 'and c1.active=1';
  } else if (isset($constraints['active']) && !$constraints['active']) {
   $activeSql = 'and c1.active=0';
  } else {
   $activeSql = '';
  }
  $where[] = "(select count(*) > 0 from users_to_courses uc1, courses c1 where uc1.users_login='".$this -> user['login']."' and uc1.archive=0 $activeSql and c1.archive = 0 and (c1.instance_source=c.id or c1.id=c.id) and c1.id=uc1.courses_ID)=1";
  $result = eF_countTableData("directions d,courses c left outer join (select id from courses) r on c.id=r.id", "c.id",
  implode(" and ", $where));
  return $result[0]['count'];
 }
 public function filterCoursesBasedOnInstance($courses, $instanceSource) {
  foreach ($courses as $key => $course) {
   if ($course -> course['instance_source'] != $instanceSource && $course -> course['id'] != $instanceSource) {
    unset($courses[$key]);
   } else {
    $courses[$key] -> course['num_lessons'] = $course -> countCourseLessons();
   }
  }
  return $courses;
 }
 /**
	 * Return only regular courses, not instances.
	 * Assign the completion and highest instance score to the parent course, from its instances.
	 *
	 */
 public function filterCoursesWithInstanceStatus($courses) {
  foreach ($courses as $key => $course) {
   if ($course -> course['instance_source']) {
    $instanceSource = $course -> course['instance_source'];
    if ($course -> course['completed']) {
     $courses[$instanceSource] -> course['completed'] = 1;
     if ($course -> course['score'] > $courses[$instanceSource] -> course['score']) {
      $courses[$instanceSource] -> course['score'] = $course -> course['score'];
     }
    }
    unset($courses[$key]);
   } else {
    $courses[$key] -> course['num_lessons'] = $course -> countCourseLessons();
   }
  }
  return $courses;
 }
 public function getUserStatusInIndependentLessons() {
  $userLessons = $this -> getUserStatusInLessons();
  foreach ($userLessons as $key => $lesson) {
   if ($lesson -> lesson['course_only']) {
    unset($userLessons[$key]);
   }
  }
  return $userLessons;
 }
 public function getUserStatusInCourseLessons($course) {
  $userLessons = $this -> getUserStatusInLessons();
  $courseLessons = $course -> getCourseLessons();
  foreach ($userLessons as $key => $lesson) {
   if (!in_array($key, array_keys($courseLessons))) {
    unset($userLessons[$key]);
   }
  }
  return $userLessons;
 }
 public function getUserStatusInLessons($lessons = false, $onlyContent = false) {
  $userLessons = $this -> getUserLessons();
  if ($lessons !== false) {
   $lessonIds = $this -> verifyLessonsList($lessons);
   foreach ($lessonIds as $id) {
    if (in_array($id, array_keys($userLessons))) {
     $temp[$id] = $userLessons[$id];
    }
   }
   $userLessons = $temp;
  }
  foreach ($userLessons as $key => $lesson) {
   $lesson = $this -> checkUserAccessToLessonBasedOnDuration($lesson);
   if ($lesson -> lesson['user_type'] != $this -> user['user_type']) {
    $lesson -> lesson['different_role'] = 1;
   }
   $userLessons[$key] -> lesson['overall_progress'] = $this -> getUserOverallProgressInLesson($lesson);
   if (!$onlyContent) {
    $userLessons[$key] -> lesson['project_status'] = $this -> getUserProjectsStatusInLesson($lesson);
    $userLessons[$key] -> lesson['test_status'] = $this -> getUserTestsStatusInLesson($lesson);
    $userLessons[$key] -> lesson['time_in_lesson'] = $this -> getUserTimeInLesson($lesson);
   }
  }
  return $userLessons;
 }
 private function checkUserAccessToLessonBasedOnDuration($lesson) {
  //pr($lesson);
  if ($lesson -> lesson['duration'] && $lesson -> lesson['active_in_lesson']) {
   $lesson -> lesson['remaining'] = $lesson -> lesson['active_in_lesson'] + $lesson -> lesson['duration']*3600*24 - time();
  } else {
   $lesson -> lesson['remaining'] = null;
  }
  //Check whether the lesson registration is expired. If so, set $value['from_timestamp'] to false, so that the effect is to appear disabled
  if ($lesson -> lesson['duration'] && $lesson -> lesson['active_in_lesson'] && $lesson -> lesson['duration'] * 3600 * 24 + $lesson -> lesson['active_in_lesson'] < time()) {
   $lesson -> archiveLessonUsers($lesson -> lesson['users_LOGIN']);
  }
  return $lesson;
 }
 public function archiveUserCourses($courses) {
  $courses = $this -> verifyCoursesList($courses);
  foreach ($courses as $course) {
   $course = new MagesterCourse($course);
   $course -> archiveCourseUsers($this);
  }
  $this -> courses = false; //Reset users cache
  return $this -> getUserCourses();
 }
 private function verifyCoursesList($courses) {
  if (!is_array($courses)) {
   $courses = array($courses);
  }
  foreach ($courses as $key => $value) {
   if ($value instanceOf MagesterCourse) {
    $courses[$key] = $value -> course['id'];
   } elseif (!eF_checkParameter($value, 'id')) {
    unset($courses[$key]);
   }
  }
  return array_values(array_unique($courses)); //array_values() to reindex array
 }
 private function sendNotificationsRemoveUserCourses($courses) {
  foreach ($courses as $key => $course) {
   $courseIds[] = $key;
  }
  MagesterEvent::triggerEvent(array("type" => MagesterEvent::COURSE_REMOVAL,
          "users_LOGIN" => $this -> user['login'],
          "lessons_ID" => $courseIds));
 }
 public function archiveUserLessons($lessons) {
  $lessons = $this -> verifyLessonsList($lessons);
  $this -> sendNotificationsRemoveUserLessons($lessons);
  foreach ($lessons as $lesson) {
   eF_updateTableData("users_to_lessons", array("archive" => time()), "users_LOGIN='".$this -> user['login']."' and lessons_ID=$lesson");
   $cacheKey = "user_lesson_status:lesson:".$lesson."user:".$this -> user['login'];
   Cache::resetCache($cacheKey);
  }
  $this -> lessons = false; //Reset users cache
  return $this -> getLessons();
 }
 private function verifyLessonsList($lessons) {
  if (!is_array($lessons)) {
   $lessons = array($lessons);
  }
  foreach ($lessons as $key => $value) {
   if ($value instanceOf MagesterLesson) {
    $lessons[$key] = $value -> lesson['id'];
   } elseif (!eF_checkParameter($value, 'id')) {
    unset($lessons[$key]);
   }
  }
  return array_values(array_unique($lessons)); //array_values() to reindex array
 }
 private function verifyLessonObjectsList($lessons) {
  if (!is_array($lessons)) {
   $lessons = array($lessons);
  }
  $lessonsList = array();
  foreach ($lessons as $value) {
   if (!($value instanceOf MagesterLesson)) {
    $value = new MagesterLesson($value);
    $lessonsList[$value -> lesson['id']] = $value;
   }
  }
  return $lessonsList;
 }
 private function sendNotificationsRemoveUserLessons($lessons) {
  foreach ($lessons as $key => $lesson) {
   $lessonIds[] = $key;
  }
  MagesterEvent::triggerEvent(array("type" => MagesterEvent::LESSON_REMOVAL,
          "users_LOGIN" => $this -> user['login'],
          "lessons_ID" => $lessonIds));
 }
 private function getUserTimeInLesson($lesson) {
  $timeReport = new MagesterTimes();
  $userTimes = $timeReport -> getUserSessionTimeInLesson($this -> user['login'], $lesson -> lesson['id']);
  $userTimes = $timeReport -> formatTimeForReporting($userTimes);
/*
		$userTimes = MagesterStats :: getUsersTimeAll(false, false, array($lesson -> lesson['id'] => $lesson -> lesson['id']), array($this -> user['login'] => $this -> user['login']));
		$userTimes = $userTimes[$lesson -> lesson['id']][$this -> user['login']];
		$userTimes['time_string'] = '';
		if ($userTimes['total_seconds']) {
			!$userTimes['hours']   OR $userTimes['time_string'] .= $userTimes['hours']._HOURSSHORTHAND.' ';
			!$userTimes['minutes'] OR $userTimes['time_string'] .= $userTimes['minutes']._MINUTESSHORTHAND.' ';
			!$userTimes['seconds'] OR $userTimes['time_string'] .= $userTimes['seconds']._SECONDSSHORTHAND;
		}
*/
  return $userTimes;
 }
 private function getUserOverallProgressInLesson($lesson) {
  $totalUnits = $completedUnits = 0;
  $contentTree = new MagesterContentTree($lesson);
  $validUnits = array();
  foreach ($iterator = new MagesterVisitableFilterIterator(new MagesterNodeFilterIterator(new RecursiveIteratorIterator(new RecursiveArrayIterator($contentTree -> tree), RecursiveIteratorIterator :: SELF_FIRST))) as $key => $value) {
   $totalUnits++;
   $validUnits[$key] = $key;
  }
  if ($doneContent = unserialize($lesson -> lesson['done_content'])) {
   $doneContent = array_intersect($doneContent,$validUnits); // to avoid counting deleted content makriria fix
   $completedUnits = sizeof($doneContent);
  }
  if ($totalUnits) {
   $completedUnitsPercentage = round(100 * $completedUnits/$totalUnits, 2);
   return array('total' => $totalUnits,
       'completed' => $completedUnits,
       'percentage' => $completedUnitsPercentage);
  } else {
   return array('total' => 0,
       'completed' => 0,
       'percentage' => 0);
  }
 }
 private function getUserTestsStatusInLesson($lesson) {
  $completedTests = $meanTestScore = 0;
  $tests = $lesson -> getTests(true, true);
  $totalTests = sizeof($tests);
  $result = eF_getTableData("completed_tests ct, tests t", "ct.tests_ID, ct.score", "t.id=ct.tests_ID and ct.users_LOGIN='".$this -> user['login']."' and ct.archive=0 and t.lessons_ID=".$lesson -> lesson['id']);
  foreach ($result as $value) {
   if (in_array($value['tests_ID'], array_keys($tests))) {
    $meanTestScore += $value['score'];
    $completedTests++;
   }
  }
  $scormTests = $this -> getUserScormTestsStatusInLesson($lesson);
  $totalTests += sizeof($scormTests);
  foreach ($scormTests as $value) {
   $meanTestScore += $value;
   $completedTests++;
  }
  if ($totalTests) {
   $completedTestsPercentage = round(100 * $completedTests/$totalTests, 2);
   $meanTestScore = round($meanTestScore/$completedTests, 2);
   return array('total' => $totalTests,
       'completed' => $completedTests,
       'percentage' => $completedTestsPercentage,
       'mean_score' => $meanTestScore);
  } else {
   return array();
  }
 }
 private function getUserScormTestsStatusInLesson($lesson) {
  $usersDoneScormTests = eF_getTableData("scorm_data sd left outer join content c on c.id=sd.content_ID",
              "c.id, c.ctg_type, sd.users_LOGIN, sd.masteryscore, sd.lesson_status, sd.score, sd.minscore, sd.maxscore",
              "c.ctg_type = 'scorm_test' and sd.users_LOGIN = '".$this -> user['login']."' and c.lessons_ID = ".$lesson -> lesson['id']);
  $tests = array();
  foreach ($usersDoneScormTests as $doneScormTest) {
   if (is_numeric($doneScormTest['minscore']) || is_numeric($doneScormTest['maxscore'])) {
    $doneScormTest['score'] = 100 * $doneScormTest['score'] / ($doneScormTest['minscore'] + $doneScormTest['maxscore']);
   } else {
    $doneScormTest['score'] = $doneScormTest['score'];
   }
   $tests[$doneScormTest['id']] = $doneScormTest['score'];
  }
  return $tests;
 }
 private function getUserProjectsStatusInLesson($lesson) {
  $completedProjects = $meanProjectScore = 0;
  $projects = $lesson -> getProjects(true, $this);
  $totalProjects = sizeof($projects);
  foreach ($projects as $project) {
   if ($project -> project['grade'] || $project -> project['grade'] === 0) {
    $completedProjects++;
    $meanProjectScore += $project -> project['grade'];
   }
  }
  if ($totalProjects) {
   $completedProjectsPercentage = round(100 * $completedProjects/$totalProjects, 2);
   $meanProjectScore = round($meanProjectScore/$completedProjects, 2);
   return array('total' => $totalProjects,
       'completed' => $completedProjects,
       'percentage' => $completedProjectsPercentage,
       'mean_score' => $meanProjectScore);
  } else {
   return array();
  }
 }
 /**
	 * Get user certificates
	 *
	 * This function gets all certificates that have been issued for the user
	 * <br/>Example:
	 * <code>
	 * $user -> getIssuedCertificates();	   //Get an array with the information on the certificates
	 * </code>
	 *
	 * @return an array of the format [] => [course name, certificate key, date issued, date expire, issuing authority]
	 * @since 3.6.1
	 * @access public
	 */
 public function getIssuedCertificates() {
  $constraints = array('archive' => false, 'active' => true, 'condition' => 'issued_certificate != 0 or issued_certificate is not null');
  $constraints['return_objects'] = false;
  $courses = $this -> getUserCourses($constraints);
  $certificates = array();
  foreach ($courses as $course) {
   if ($certificateInfo = unserialize($course['issued_certificate'])) {
    $certificateInfo = unserialize($course['issued_certificate']);
    $courseOptions = unserialize($course['options']);
    $certificates[] = array("courses_ID" => $course['id'],
          "course_name" => $course['name'],
          "serial_number" => $certificateInfo['serial_number'],
          "grade" => $certificateInfo['grade'],
          "issue_date" => $certificateInfo['date'],
          "active" => $course['active'],
          "export_method" => $courseOptions['certificate_export_method'],
          "expiration_date"=> ($course['certificate_expiration']) ? ($certificateInfo['date'] + $course['certificate_expiration']) : _NEVER);
   }
  }
  return $certificates;
 }
 /**
	 * Assign courses to user.
	 *
	 * This function can be used to assign a course to the current user. If $userTypes
	 * is specified, then the user is assigned to the course using the specified type.
	 * By default, the user asic type is used.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> addCourses(23);						 //Add a signle course with id 23
	 * $user -> addCourses(23, 'professor');			//Add a signle course with id 23 and set the user type to 'professor'
	 * $user -> addCourses(array(23,24,25));			//Add multiple courses using an array
	 * $user -> addCourses(array(23,24,25), array('professor', 'student', 'professor'));			//Add multiple courses using an array for course ids and another for corresponding user types
	 * </code>
	 *
	 * @param mixed $courseIds Either a single course id, or an array if ids
	 * @param mixed $userTypes The corresponding user types for the specified courses
	 * @param boolean $activeate Courses will be set as active or not
	 * @return mixed The array of course ids or false if the course already exists.
	 * @since 3.5.0
	 * @access public
	 * @todo auto_projects
	 */
 public function addCourses($courses, $roles = 'student', $confirmed = true) {
  $courses = $this -> verifyCoursesList($courses);
  $roles = MagesterUser::verifyRolesList($roles, sizeof($courses));
  if (sizeof($courses) > 0) {
   $courses = eF_getTableData("courses", "*", "id in (".implode(",", $courses).")");
   foreach ($courses as $key => $course) {
    $course = new MagesterCourse($course);
    $course -> addUsers($this -> user['login'], $roles[$key], $confirmed);
   }
   $this -> courses = false; //Reset courses information
  }
  return $this -> getUserCourses();
 }
 /**
	 * Confirm user's lessons
	 *
	 * This function can be used to set the "active" flag of a user's lesson to "true", so that
	 * he can access the corresponding lessons.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> addCourses(23);						 //Confirm a signle course with id 23
	 * $user -> addCourses(array(23,24,25));			//Confirm multiple courses using an array
	 * </code>
	 *
	 * @param mixed $courseIds Either a single course id, or an array if ids
	 * @return array The array of course ids
	 * @since 3.6.0
	 * @access public
	 */
 public function confirmCourses($courses) {
  $courses = $this -> verifyCoursesList($courses);
  foreach ($courses as $key => $course) {
   $course = new MagesterCourse($course);
   $course -> confirm($this);
  }
  $this -> courses = false; //Reset courses information
  return $this -> getserUCourses();
 }
 /**
	 * Remove courses from user.
	 *
	 * This function can be used to remove a course from the current user.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> removeCourses(23);						  //Remove a signle course with id 23
	 * $user -> removeCourses(array(23,24,25));			 //Remove multiple courses using an array
	 * </code>
	 *
	 * @param int $courseIds Either a single course id, or an array if ids
	 * @return true.
	 * @since 3.5.0
	 * @access public
	 */
 public function removeCourses($courses) {
  $courseIds = $this -> verifyCoursesList($courses);
  $result = eF_getTableData("lessons_to_courses lc, users_to_courses uc", "lc.*", "lc.courses_ID=uc.courses_ID and uc.users_LOGIN = '".$this -> user['login']."'");
  foreach ($result as $value) {
   $lessonsToCourses[$value['lessons_ID']][] = $value['courses_ID'];
   $coursesToLessons[$value['courses_ID']][] = $value['lessons_ID'];
  }
  if (!empty($courseIds)) {
   $userLessonsThroughCourse = eF_getTableDataFlat("lessons_to_courses lc, users_to_courses uc", "lc.lessons_ID", "lc.courses_ID=uc.courses_ID and uc.courses_ID in (".implode(",", $courseIds).") and uc.users_LOGIN = '".$this -> user['login']."'");
   $userLessonsThroughCourse = $userLessonsThroughCourse['lessons_ID'];
  }
  eF_deleteTableData("users_to_courses", "users_LOGIN = '".$this -> user['login']."' and courses_ID in (".implode(",", $courseIds).")"); //delete courses from list
  foreach ($courseIds as $id) {
   $cacheKey = "user_course_status:course:".$id."user:".$this -> user['login'];
   Cache::resetCache($cacheKey);
  }
  MagesterEvent::triggerEvent(array("type" => MagesterEvent::COURSE_REMOVAL, "users_LOGIN" => $this -> user['login'], "lessons_ID" => $courseIds));
  foreach ($userLessonsThroughCourse as $lesson) {
   if (sizeof($lessonsToCourses[$lesson]) == 1) {
    $this -> removeLessons($lesson);
   } else if (sizeof(array_diff($lessonsToCourses[$lesson], $courseIds)) == 0) {
    $this -> removeLessons($lesson);
   }
  }
  return $true;
 }
 /**
	 * Set user role
	 *
	 * This function is used to set the specific role of this user.
	 * <br/>Example:
	 * <code>
	 * $user -> setRole(23, 'simpleUser');		  //Set this user's role to 'simpleUser' for lesson with id 23
	 * $user -> setRole(23);						//Set this user's role to the same as its basic type (for example 'student') for lesson with id 23
	 * $user -> setRole(false, 'simpleUser');	   //Set this user's role to 'simpleUser' for all lessons
	 * $user -> setRole();						  //Set this user's role to the same as its basic type (for example 'student') for all lessons
	 * </code>
	 *
	 * @param int $lessonId The lesson id
	 * @param string $userRole The new user role
	 * @return boolean true if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function setRole($lessonId = false, $userRole = false) {
  if ($userRole) {
   $fields = array("user_type" => $userRole);
  } else {
   $fields = array("user_type" => $this -> user['user_type']);
  }
  if ($lessonId && eF_checkParameter($lessonId, 'id')) {
   eF_updateTableData("users_to_lessons", $fields, "users_LOGIN='".$this -> user['login']."' and lessons_ID=$lessonId");
   $cacheKey = "user_lesson_status:lesson:".$lessonId."user:".$this -> user['login'];
   Cache::resetCache($cacheKey);
  } else {
   eF_updateTableData("users_to_lessons", $fields, "users_LOGIN='".$this -> user['login']."'");
  }
 }
 /**
	 * Get the user's role
	 *
	 * This function returns the user role for the specified lesson
	 * <br/>Example:
	 * <code>
	 * $this -> getRole(4);								 //Get the role for lesson with id 4
	 * </code>
	 *
	 * @param int $lessonId The lesson id to get the role for
	 * @return string The user role for the lesson
	 * @since 3.5.0
	 * @access public
	 */
 public function getRole($lessonId) {
  $roles = MagesterLessonUser :: getLessonsRoles();
  if ($lessonId instanceof MagesterLesson) {
   $lessonId = $lessonId -> lesson['id'];
  }
  if (in_array($lessonId, array_keys($this -> getLessons()))) {
   $result = eF_getTableData("users_to_lessons", "user_type", "users_LOGIN='".$this -> user['login']."' and lessons_ID=".$lessonId);
   return $roles[$result[0]['user_type']];
  } else {
   return false;
  }
 }
 /**
	 * Get roles applicable to lessons
	 *
	 * This function is used to get the roles in the system, that derive from professor and student
	 * It returns an array where keys are the role ids and values are:
	 * - Either the role basic user types, if $getNames is false (the default)
	 * - or the role Names if $getNames is true
	 * The array is prepended with the 2 main roles, 'professor' and 'student'
	 * <br/>Example:
	 * <code>
	 * $roles = MagesterLessonUser :: getLessonsRoles();
	 * </code>
	 *
	 * @param boolean $getNames Whether to return id/basic user type pairs or id/name pairs
	 * @return array The lesson-oriented roles
	 * @since 3.5.0
	 * @access public
	 * @static
	 */
 public static function getLessonsRoles($getNames = false) {
  //Cache results in self :: $lessonRoles
  if (is_null(self :: $lessonRoles)) {
   $roles = eF_getTableDataFlat("user_types", "*", "active=1 AND basic_user_type!='administrator'"); //Get available roles
   self :: $lessonRoles = $roles;
  } else {
   $roles = self :: $lessonRoles;
  }
  if (sizeof($roles) > 0) {
   $getNames ? $roles = array('student' => _STUDENT, 'professor' => _PROFESSOR) + array_combine($roles['id'], $roles['name']) : $roles = array('student' => 'student', 'professor' => 'professor') + array_combine($roles['id'], $roles['basic_user_type']);
  } else {
   $getNames ? $roles = array('student' => _STUDENT, 'professor' => _PROFESSOR) : $roles = array('student' => 'student', 'professor' => 'professor');
  }
  return $roles;
 }
 /**
	 * Get student roles
	 *
	 * This function returns an array with student roles, like MagesterLessonUser::getLessonsRoles
	 *
	 * @param boolean $getNames Whether to return id/basic user type pairs or id/name pairs
	 * @return array The lesson-oriented roles
	 * @since 3.6.7
	 * @access public
	 * @static
	 * @see MagesterLessonUser::getLessonsRoles
	 */
 public static function getStudentRoles($getNames = false) {
  $roles = self::getLessonsRoles();
  $roleNames = self::getLessonsRoles(true);
  foreach ($roles as $key => $value) {
   if ($value != 'student') {
    unset($roles[$key]);
    unset($roleNames[$key]);
   }
  }
  if ($getNames) {
   return $roleNames;
  } else {
   return $roles;
  }
 }
 /**
	 * Get lesson users
	 *
	 * This function returns a list with the students of all the lessons in which the current user has a professor role
	 * <br/>Example:
	 * <code>
	 *	  $user = MagesterUserFactory :: factory('professor');
	 *	  $students = $user -> getProfessorStudents();
	 * </code>
	 *
	 * @return array A list of user logins
	 * @since 3.5.0
	 * @access public
	 */
 public function getProfessorStudents(){
  $lessons = $this -> getLessons(true, 'professor');
  $students = array();
  foreach ($lessons as $lesson){
   $lesson_students = array();
   $lesson_students = $lesson -> getUsers('student');
   foreach ($lesson_students as $student){
    $students[] = $student['login'];
   }
  }
  return array_unique($students);
 }
 /**
	 * Get user information
	 *
	 * This function returns the user information in an array
	 *
	 *
	 * <br/>Example:
	 * <code>
	 * $info = $user -> getInformation();		 //Get lesson information
	 * </code>
	 *
	 * @param string $user The user login to customize lesson information for
	 * @return array The user information
	 * @since 3.5.0
	 * @access public
	 */
 public function getInformation() {
  $languages = MagesterSystem :: getLanguages(true);
  $info = array();
  $info['login'] = $this -> user['login'];
  $info['name'] = $this -> user['name'];
  $info['surname'] = $this -> user['surname'];
  $info['fullname'] = $this -> user['name'] . " " . $this -> user['surname'];
  $info['user_type'] = $this -> user['user_type'];
  $info['user_types_ID'] = $this -> user['user_types_ID'];
  $info['student_lessons'] = $this -> getLessons(true, 'student');
  $info['professor_lessons'] = $this -> getLessons(true, 'professor');
  $info['total_lessons'] = sizeof($this -> getUserLessons());
  $info['total_courses'] = sizeof($this -> getUserCourses(array('active' => true, 'return_objects' => false)));
  $info['total_login_time'] = self :: getLoginTime($this -> user['login']);
  $info['language'] = $languages[$this -> user['languages_NAME']];
  $info['active'] = $this -> user['active'];
  $info['active_str'] = $this -> user['active'] ? _YES : _NO;
  $info['joined'] = $this -> user['timestamp'];
  $info['joined_str'] = formatTimestamp($this -> user['timestamp'], 'time');
  $info['avatar'] = $this -> user['avatar'];
  return $info;
 }
 /**
	 * Get user related users
	 *
	 * This function returns all users that related to this user
	 * The relation depends on common lessons
	 *
	 * <br/>Example:
	 * <code>
	 * $myRelatedUsers = $user -> getRelatedUsers();		 //Get related users
	 * </code>
	 *
	 * @return array Of related users logins
	 * @since 3.6.0
	 * @access public
	 */
 public function getRelatedUsers() {
  $myLessons = $this ->getLessons();
  $other_users = eF_getTableDataFlat("users_to_lessons ul, users u", "distinct users_LOGIN" , "u.archive=0 and u.active=1 and ul.users_LOGIN=u.login and ul.archive=0 and lessons_ID IN ('" . implode("','", array_keys($myLessons)) . "') AND users_LOGIN <> '" . $this -> user['login'] . "'");
  $users = $other_users['users_LOGIN'];
  return $users;
 }
 /**
	 * Get the common lessons with a particular user
	 *getUsers(
	 * <br/>Example:
	 * <code>
	 * $common_lessons	= $user -> getCommonLessons('joe'); // find the common lessons between this user and 'joe'
	 * </code>
	 *
	 * @return array with pairs [lessons_id] => [lessons_id, lessons_name] referring to the common lessons of this object's user and user with login=$login
	 * @since 3.6.0
	 * @access public
	 */
 public function getCommonLessons($login) {
  $result = eF_getTableData("users_to_lessons as ul1 JOIN users_to_lessons as ul2 ON ul1.lessons_ID = ul2.lessons_ID JOIN lessons ON ul1.lessons_ID = lessons.id", "lessons.id, lessons.name", "ul1.archive=0 and ul2.archive=0 and ul1.users_LOGIN = '".$this -> user['login']."' AND ul2.users_LOGIN = '".$login."'");
  $common_lessons = array();
  foreach ($result as $common_lesson) {
   $common_lessons[$common_lesson['id']] = $common_lesson;
  }
  return $common_lessons;
 }
 /**
	 * Get skillgap tests to do
	 *
	 * This function returns an array with all skill gap tests assigned to the student
	 * <br/>Example:
	 * <code>
	 * $user -> getSkillgapTests();						   //Set the unit with id 32 in lesson 2 as seen
	 * </code>
	 *
	 * @param No parameters
	 * @return Array of tests in the form [test_id] => [id, test_name]
	 * @since 3.5.2
	 * @access public
	 */
 public function getSkillgapTests() {
  $skillgap_tests = array();
  return $skillgap_tests;
 }
 public function getUserStatusInCourses() {
 }
 public function hasCourse($course) {
  if ($course instanceOf MagesterCourse) {
   $course = $course -> course['id'];
  } elseif (!eF_checkParameter($course, 'id')) {
   throw new MagesterCourseException(_INVALIDID.": $course", MagesterCourseException :: INVALID_ID);
  }
  $result = eF_getTableData("users_to_courses", "courses_ID", "courses_ID=$course and users_LOGIN='".$this -> user['login']."' and archive=0");
  return sizeof($result) > 0;
 }
 public function getUserTypeInCourse($course) {
  if ($course instanceOf MagesterCourse) {
   $course = $course -> course['id'];
  } elseif (!eF_checkParameter($course, 'id')) {
   throw new MagesterCourseException(_INVALIDID.": $course", MagesterCourseException :: INVALID_ID);
  }
  $result = eF_getTableData("users_to_courses", "user_type", "courses_ID=$course and users_LOGIN='".$this -> user['login']."' and archive=0");
  if (!empty($result)) {
   return $result[0]['user_type'];
  } else {
   return false;
  }
 }
}
/**
 * Class for professor users
 *
 * @package SysClass
 */
class MagesterProfessor extends MagesterLessonUser
{
 /**
	 * Delete user
	 *
	 * This function is used to delete a user from the system.
	 * The user cannot be deleted if he is the last system administrator.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> delete();
	 * </code>
	 *
	 * @return boolean True if the user was deleted successfully
	 * @since 3.5.0
	 * @access public
	 */
 public function delete() {
  parent :: delete();
  eF_deleteTableData("users_to_lessons", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("users_to_courses", "users_LOGIN='".$this -> user['login']."'");
/*
		foreach ($this -> getCourses() as $id => $value) {
			$cacheKey = "user_course_status:course:".$id."user:".$this -> user['login'];
			Cache::resetCache($cacheKey);
		}
*/
 }
}
/**
 * Class for student users
 *
 * @package SysClass
 */
class MagesterStudent extends MagesterLessonUser
{
 /**
	 * Delete user
	 *
	 * This function is used to delete a user from the system.
	 * The user cannot be deleted if he is the last system administrator.
	 * <br/>Example:
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');
	 * $user -> delete();
	 * </code>
	 *
	 * @return boolean True if the user was deleted successfully
	 * @since 3.5.0
	 * @access public
	 */
 public function delete() {
  parent :: delete();
  $userDoneTests = eF_getTableData("done_tests", "id", "users_LOGIN='".$this -> user['login']."'");
  if (sizeof($userDoneTests) > 0) {
   eF_deleteTableData("done_questions", "done_tests_ID IN (".implode(",", $userDoneTests['id']).")");
   eF_deleteTableData("done_tests", "users_LOGIN='".$this -> user['login']."'");
  }
  eF_deleteTableData("users_to_lessons", "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("users_to_courses", "users_LOGIN='".$this -> user['login']."'");
/*
		foreach ($this -> getCourses() as $id => $value) {
			$cacheKey = "user_course_status:course:".$id."user:".$this -> user['login'];
			Cache::resetCache($cacheKey);
		}
*/
  eF_deleteTableData("users_to_projects", "users_LOGIN='".$this -> user['login']."'");
  //eF_deleteTableData("users_to_done_tests",   "users_LOGIN='".$this -> user['login']."'");
  eF_deleteTableData("completed_tests", "users_LOGIN='".$this -> user['login']."'");
 }
 /**
	 * Complete lesson
	 *
	 * This function is used to set the designated lesson's status
	 * to 'completed' for the current user.
	 * <br/>Example:
	 * <code>
	 * $user -> completeLesson(5, 87, 'Very good progress');									  //Complete lesson with id 5
	 * </code>
	 *
	 * @param mixed $lesson Either the lesson id, or an MagesterLesson object
	 * @param array $fields Extra fields containing the user score and any comments
	 * @return boolean true if everything is ok
	 * @since 3.5.0
	 * @access public
	 */
 public function completeLesson($lesson, $score = 100, $comments = '') {
  if (!($lesson instanceof MagesterLesson)) {
   $lesson = new MagesterLesson($lesson);
  }
  if (in_array($lesson -> lesson['id'], array_keys($this -> getLessons()))) {
   $fields = array('completed' => 1,
       'to_timestamp' => time(),
       'score' => $score,
       'comments' => $comments);
   eF_updateTableData("users_to_lessons", $fields, "users_LOGIN = '".$this -> user['login']."' and lessons_ID=".$lesson -> lesson['id']);
   //$cacheKey = "user_lesson_status:lesson:".$lesson -> lesson['id']."user:".$this -> user['login'];
   //Cache::resetCache($cacheKey);
   // Timelines event
   MagesterEvent::triggerEvent(array("type" => MagesterEvent::LESSON_COMPLETION, "users_LOGIN" => $this -> user['login'], "lessons_ID" => $lesson -> lesson['id'], "lessons_name" => $lesson -> lesson['name']));
   //Get results in lessons
   $userLessons = array();
   $result = eF_getTableData("users_to_lessons", "lessons_ID,completed,score", "users_LOGIN='".$this -> user['login']."'");
   foreach ($result as $value) {
    if ($userLessons[$value['lessons_ID']] = $value);
   }
   $lessonCourses = $lesson -> getCourses(true); //Get the courses that this lesson is part of. This way, we can auto complete a course, if it should be auto completed
   //Filter out courses that the student doesn't have
   $result = eF_getTableDataFlat("users_to_courses", "courses_ID", "users_LOGIN='".$this -> user['login']."'");
   $userCourses = $result['courses_ID'];
   foreach ($lessonCourses as $id => $course) {
    if (!in_array($id, $userCourses)) {
     unset($lessonCourses[$id]);
    }
   }
   //$userStatus = MagesterStats :: getUsersCourseStatus(array_keys($courses), $this -> user['login']);
   foreach ($lessonCourses as $course) {
    if ($course -> options['auto_complete']) {
     $constraints = array('archive' => false, 'active' => true, 'return_objects' => false);
     $courseLessons = $course -> getCourseLessons($constraints);
     $completed = $score = array();
     foreach ($courseLessons as $lessonId => $value) {
      $userLessons[$lessonId]['completed'] ? $completed[] = 1 : $completed[] = 0;
      $score[] = $userLessons[$lessonId]['score'];
     }
     if (array_sum($completed) == sizeof($completed)) { //If all the course's lessons are completed, then auto complete the course, using the mean lessons score
      $this -> completeCourse($course -> course['id'], round(array_sum($score) / sizeof($score)), _AUTOCOMPLETEDCOURSE);
     }
    }
   }
   $modules = eF_loadAllModules();
   foreach ($modules as $module) {
    $module -> onCompleteLesson($lesson -> lesson['id'],$this -> user['login']);
   }
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Complete course
	 *
	 * This function is used to set the course status to completed for
	 * the current user. If the course is set to automatically issue a
	 * certificate, the certificate is issued.
	 * <br/>Example:
	 * <code>
	 * $user -> completeCourse(5, 87, 'Very good progress');									  //Complete course with id 5
	 * </code>
	 *
	 * @param Magestermixed $course Either an MagesterCourse object or a course id
	 * @param int $score The course score
	 * @param string $comments Comments for the course completion
	 * @return boolean True if everything is ok
	 */
 public function completeCourse($course, $score, $comments) {
  if (!($course instanceof MagesterCourse)) {
   $course = new MagesterCourse($course);
  }
  $constraints = array('archive' => false, 'active' => true, 'return_objects' => false);
  $userCourses = $this -> getUserCourses($constraints);
  if (in_array($course -> course['id'], array_keys($userCourses))) {
   //keep completed date when it is set (when only score changed for example)
   $checkCompleted = $userCourses[$course -> course['id']]['to_timestamp'];
   $fields = array('completed' => 1,
       'to_timestamp' => $checkCompleted ? $checkCompleted :time(),
       'score' => $score,
       'comments' => $comments);
   $where = "users_LOGIN = '".$this -> user['login']."' and courses_ID=".$course -> course['id'];
   MagesterCourse::persistCourseUsers($fields, $where, $course -> course['id'], $this -> user['login']);
   if ($course -> options['auto_certificate']) {
    $certificate = $course -> prepareCertificate($this -> user['login']);
    $course -> issueCertificate($this -> user['login'], $certificate);
   }
   MagesterEvent::triggerEvent(array("type" => MagesterEvent::COURSE_COMPLETION, "users_LOGIN" => $this -> user['login'], "lessons_ID" => $course -> course['id'], "lessons_name" => $course -> course['name']));
   // Assign the related course skills to the employee
   return true;
  } else {
   return false;
  }
 }
 /**
	 * Set seen unit
	 *
	 * This function is used to set the designated unit as seen or not seen,
	 * according to $seen parameter. It also sets current unit to be the seen
	 * unit, if we are setting a unit as seen. Otherwise, the current unit is
	 * either leaved unchanged, or, if it matches the unset unit, it points
	 * to another seen unit.
	 * <br/>Example:
	 * <code>
	 * $user -> setSeenUnit(32, 2, true);						   //Set the unit with id 32 in lesson 2 as seen
	 * $user -> setSeenUnit(32, 2, false);						  //Set the unit with id 32 in lesson 2 as not seen
	 * </code>
	 * From version 3.5.2 and above, this function also sets the lesson as completed, if the conditions are met
	 *
	 * @param mixed $unit The unit to set status for, can be an id or an MagesterUnit object
	 * @param mixed $lesson The lesson that the unit belongs to, can be an id or an MagesterLesson object
	 * @param boolean $seen Whether to set the unit as seen or not
	 * @return boolean true if the lesson was completed as well
	 * @since 3.5.0
	 * @access public
	 */
 public function setSeenUnit($unit, $lesson, $seen) {
  if (isset($this -> coreAccess['content']) && $this -> coreAccess['content'] != 'change') { //If user type is not plain 'student' and is not set to 'change' mode, do nothing
   return true;
  }
  if ($unit instanceof MagesterUnit) { //Check validity of $unit
   $unit = $unit['id'];
  } elseif (!eF_checkParameter($unit, 'id')) {
   throw new MagesterContentException(_INVALIDID.": $unit", MagesterContentException :: INVALID_ID);
  }
  if ($lesson instanceof MagesterLesson) { //Check validity of $lesson
   $lesson = $lesson -> lesson['id'];
  } elseif (!eF_checkParameter($lesson, 'id')) {
   throw new MagesterLessonException(_INVALIDID.": $lesson", MagesterLessonException :: INVALID_ID);
  }
  $lessons = $this -> getLessons();
  if (!in_array($lesson, array_keys($lessons))) { //Check if the user is actually registered in this lesson
   throw new MagesterUserException(_USERDOESNOTHAVETHISLESSON.": ".$lesson, MagesterUserException :: USER_NOT_HAVE_LESSON);
  }
  $result = eF_getTableData("users_to_lessons", "done_content, current_unit", "users_LOGIN='".$this -> user['login']."' and lessons_ID=".$lesson);
  sizeof($result) > 0 ? $doneContent = unserialize($result[0]['done_content']) : $doneContent = array();
  $current_unit = 0;
  if ($seen) {
   $doneContent[$unit] = $unit;
   $current_unit = $unit;
  } else {
   unset($doneContent[$unit]);
   if ($unit == $result[0]['current_unit']) {
    sizeof($doneContent) ? $current_unit = end($doneContent) : $current_unit = 0;
   }
  }
  sizeof($doneContent) ? $doneContent = serialize($doneContent) : $doneContent = null;
  eF_updateTableData("users_to_lessons", array('done_content' => $doneContent, 'current_unit' => $current_unit), "users_LOGIN='".$this -> user['login']."' and lessons_ID=".$lesson);
//		$cacheKey = "user_lesson_status:lesson:".$lesson."user:".$this -> user['login'];
//		Cache::resetCache($cacheKey);
  if ($current_unit) {
   MagesterEvent::triggerEvent(array("type" => MagesterEvent::CONTENT_COMPLETION, "users_LOGIN" => $this -> user['login'], "lessons_ID" => $lesson, "entity_ID" => $current_unit));
  }
  //Set the lesson as complete, if it can be.
  $completedLesson = false;
  if ($seen) {
   $userProgress = MagesterStats :: getUsersLessonStatus($lesson, $this -> user['login']);
   $userProgress = $userProgress[$lesson][$this -> user['login']];
   if ($userProgress['lesson_passed'] && !$userProgress['completed']) {
    $lesson = new MagesterLesson($lesson);
    if ($lesson -> options['auto_complete']) {
     $userProgress['tests_avg_score'] ? $avgScore = $userProgress['tests_avg_score'] : $avgScore = 100;
     $timestamp = _AUTOCOMPLETEDAT.': '.date("Y/m/d, H:i:s");
     $this -> completeLesson($lesson, $avgScore, $timestamp);
     $completedLesson = true;
    }
   }
  }
  return $completedLesson;
 }
 /**
	 * Get the next lesson in row, or in the course, if specified
	 *
	 * @param MagesterLesson $lesson The lesson to account
	 * @param mixed $course The course to regard, or false
	 * @return int The id of the next lesson in row
	 * @since 3.6.3
	 * @access public
	 */
 public function getNextLesson($lesson, $course = false) {
  $nextLesson = false;
  if ($course) {
   ($course instanceOf MagesterCourse) OR $course = new MagesterCourse($course);
   $eligibility = new ArrayIterator($course -> checkRules($_SESSION['s_login']));
   while ($eligibility -> valid() && ($key = $eligibility -> key()) != $lesson -> lesson['id']) {
    $eligibility -> next();
   }
   $eligibility -> next();
   if ($eligibility -> valid() && $eligibility -> key() && $eligibility -> current()) {
    $nextLesson = $eligibility -> key();
   }
  } else {
   $directionsTree = new MagesterDirectionsTree();
   $userLessons = new ArrayIterator($directionsTree -> getLessonsList($this -> getUserLessons()));
   while ($userLessons -> valid() && ($key = $userLessons -> current()) != $lesson -> lesson['id']) {
    $userLessons -> next();
   }
   $userLessons -> next();
   if ($userLessons -> valid() && $userLessons -> current()) {
    $nextLesson = $userLessons -> current();
   }
  }
  return $nextLesson;
 }
}

class MagesterVisitant extends MagesterStudent {

}

class MagesterResponsible extends MagesterStudent {
	
	public function linkWithStudent($studentLogin) {
		// LINK WITH USER

		$childUser = MagesterUserFactory :: factory($studentLogin);

		$linkData = eF_getTableData('c_users_link', '*', 
			"parent_id = '" . $this->user['id'] . "'" . 
			"AND child_id = '" . $childUser->user['id'] . "'"
		);

		if (count($linkData) > 0) {
			return false;
		}
		$linkInsert = array(
			'parent_id'	=> $this->user['id'],
			'child_id'	=> $childUser->user['id']
		);

		return eF_insertTableData("c_users_link", $linkInsert);
	}
}

class MagesterPreStudent extends MagesterStudent {

}



/**
 * User Factory class
 *
 * This clas is used as a factory for user objects
 * <br/>Example:
 * <code>
 * $user = MagesterUserFactory :: factory('jdoe');
 * </code>
 *
 * @package SysClass
 * @version 3.5.0
 */
class MagesterUserFactory
{
 /**
	 * Construct user object
	 *
	 * This function is used to construct a user object, based on the user type.
	 * Specifically, it creates an MagesterStudent, MagesterProfessor, MagesterAdministrator etc
	 * An optional password verification may take place, if $password is specified
	 * If $user is a login name, the function queries database. Alternatively, it may
	 * use a prepared user array, which is mostly convenient when having to perform
	 * multiple initializations
	 * <br/>Example :
	 * <code>
	 * $user = MagesterUserFactory :: factory('jdoe');			//Use factory function to instantiate user object with login 'jdoe'
	 * $userData = eF_getTableData("users", "*", "login='jdoe'");
	 * $user = MagesterUserFactory :: factory($userData[0]);	  //Use factory function to instantiate user object using prepared data
	 * </code>
	 *
	 * @param mixed $user A user login or an array holding user data
	 * @param string $password An optional password to check against
	 * @param string $forceType Force the type to initialize the user, for example for when a professor accesses student.php as student
	 * @return MagesterUser an object of a class extending MagesterUser
	 * @since 3.5.0
	 * @access public
	 * @static
	 */
 public static function factory($user, $password = false, $forceType = false) {
  if ((is_string($user) || is_numeric($user)) && eF_checkParameter($user, 'login')) {
   $result = eF_getTableData("users", "*", "login='".$user."'");
   if (sizeof($result) == 0) {
    throw new MagesterUserException(_USERDOESNOTEXIST.': '.$user, MagesterUserException :: USER_NOT_EXISTS);
   } else if ($password !== false && $password != $result[0]['password']) {
    throw new MagesterUserException(_INVALIDPASSWORDFORUSER.': '.$user, MagesterUserException :: INVALID_PASSWORD);
   }
   $user = $result[0];
  } elseif (!is_array($user)) {
   throw new MagesterUserException(_INVALIDLOGIN.': '.$user, MagesterUserException :: INVALID_PARAMETER);
  }
  $forceType ? $userType = $forceType : $userType = $user['user_type'];
  switch ($userType) {
   case 'administrator' : 	$factory = new MagesterAdministrator($user, $password); break;
   case 'professor' : 		$factory = new MagesterProfessor($user, $password); break;
   case 'student' : 		$factory = new MagesterStudent($user, $password); break;
   case '2' : 				$factory = new MagesterCoordenator($user, $password); break;
   case '4' : 				$factory = new MagesterFinancial($user, $password); break;
   case '5' : 				$factory = new MagesterSecretary($user, $password); break;
   case '6' : 
   case '10' :
   							$factory = new MagesterPreStudent($user, $password); break;
   case '12' :
   case '16' :
   							$factory = new MagesterVisitant($user, $password); break;
   case '13' : 				$factory = new MagesterResponsible($user, $password); break;
   default: {
   		throw new MagesterUserException(_INVALIDUSERTYPE.': "'.$userType.'"', MagesterUserException :: INVALID_TYPE); break;
   }
  }
  return $factory;
 }
}

class MagesterUserDetails extends MagesterUser {
	public static function deleteDetails($id) {
		eF_deleteTableData("module_xuser", "id='" . $id . "'");
	}
	public static function injectDetails($login, $userProperties) {
		$user = MagesterUserFactory :: factory($login);
		$userProperties['id']	= $user->user['id'];
		
		self :: deleteDetails($userProperties['id']);
		eF_insertTableData("module_xuser", $userProperties);

		return true;
	}
	public static function getUserDetails($login) {
		$user = MagesterUserFactory :: factory($login);
		
		$result = eF_getTableData("module_xuser", "*", "id='" . $user->user['id'] . "'");
		
		if (count($result) > 0) {
			return $result[0];
		} else {
			return false;
		}	
	}
}
