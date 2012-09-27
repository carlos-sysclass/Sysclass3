<?php
/**

* MagesterExtendedModule Abstract Class file

*

* @package SysClass

* @version 1.0

*/
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}
/**

 * MagesterExtendedModule class

 *

 * This class represents a branch

 * @author Andre Kucaniz

 * @package SysClass

 * @version 1.0

 *

 * Index:

 *

 ***** General functions ********
 *
 * - appendTemplate() 		// append template to template hash list.
 * 
 * 
 ***** Module processing and appearance ********
 *
 * - getModule()
 * 
 * - getSmartyTpl()
 * 
 * - getModuleJS()
 *
 * - injectJS()	// INJECT JS FILE TO REQUEST
 * 
 * - getInjectedJS() // GET JS FILE LIST TO REQUEST
 * 
 * - getModuleCSS()
 * 
 * - injectCSS()	// INJECT CSS FILE TO REQUEST
 * 
 * - getInjectedCSS() // GET CSS FILE LIST TO REQUEST
 * 
 * - getNavigationLinks()
 * 
 * - getConfigData() 				// make module "config data", to be export TO js, and to SMARTY	=> Can be overriden
 *
 * - assignSmartyModuleVariables()
 *
 *
 ***** Action Functions ********
 *
 * - getDefaultAction()		// Function that returns default action. Can be overriden.
 * 
 * - getCurrentAction()		// Current Selected Action.
 * 
 * - defaultAction()		// Will be called if, no default action is defined. Can be overriden. 
 * 
 * - getTitle($action)		// Return the title from selected action => Can be overriden
 * 
 * - getUrl($action)		// Return the URL from selected action => Can be overriden
 *
 *
 ***** Module links ********
 *
 *
 *
 ***** Module event triggered functions ********
 *
 * - appendTemplate
 * 
 * - camelCasefying($string, $sep = "_")
 * 
 * - loadModule($name)
 * 
 * - createToken($length)
 *
 ***** Module user functions ********
 *
 * - getUserTags($user)		// Receive a user ID or Object, and inject a user TAG, depending of module context. Can be used to set a user characteristic, like (payment_pending_user)
*/

abstract class MagesterExtendedModule extends MagesterModule {
	
	protected $preActions;	
	protected $postActions;
	protected $preActionResult;
	protected $postActionResult;
	protected $eventsActionResult;
	protected $hooksActionResult;
	
	protected $_moduleData = array();
	
	protected $modules; // RECEIVE MODULES CLASS, AS A LINK
	
	
	protected $index_name = null;
	
	protected static $allModules = null;
	
	protected $templates;
	protected static $injectJS = array();
	protected static $injectScripts = array();
	protected static $injectCSS = array();

	protected static $_CONFIG = null;
	
	/* OPTIONS */
	protected $showModuleBreadcrumbs = true;
	
	
	/* UTILITY VARS */
	protected $parentObject = null;
	protected $editedUser = null;
	protected $editedCourse = null;
	protected $editedCourseClass = null;
	protected $editedPayment = null;
	protected $editedEnrollment = null;
	protected $editedPage = null;
	
	public function __construct($defined_moduleBaseUrl, $defined_moduleFolder) {
		parent::__construct($defined_moduleBaseUrl, $defined_moduleFolder);
		$this->preActions			= array();
		$this->postActions			= array();
		$this->preActionResult		= array();
		$this->postActionResult		= array();
		$this->eventsActionResult	= array();
		$this->hooksActionResult	= array();
		$this->index_name			= str_replace("module_", "", get_class($this));
		$this->modules 				= array();
		if ( empty( $this->moduleBaseUrl ) ) {
			$this->moduleBaseUrl = G_SERVERNAME . str_replace('/', '', $_SERVER['SCRIPT_NAME']).'?ctg=module&op=module_'.$this->index_name;
		}
	}

	public function getDefaultAction() {
		return 'default';
	}
	public function getCurrentAction() {
		return isset($_GET['action']) ? $_GET['action'] : $this->getDefaultAction();
	}
	public function setCurrentAction($action, $call_now = true) {
		$_GET['action'] = $action;
		if ($call_now) {
			return $this->callAction();
		}
		return true;
	}
	public function defaultAction() {
		return false;
	}
	public function getModule() {
		parent::getModule();
		
		/* PRE - ACTION */
		if (count($this->preActions) > 0) {
			foreach($this->preActions as $key => $preAction) {
				if (is_callable(array($this, $preAction))) {
					$this->preActionResult[$key] = $this->$preAction();
				}
			}
		}
		
		$actionResult = $this->callAction();
		
		/* POST - ACTION */
		if (count($this->postActions) > 0) {
			foreach($this->postActions as $key => $postAction) {
				if (is_callable(array($this, $postAction))) {
					$this->postActionResult[$key] = $this->$postAction();
				}
			}
		}
		
		$modules = eF_loadAllModules(true);
		
		$selectedAction = $this->getCurrentAction();
		$selectedActionFunction = $this->camelCasefying($selectedAction . "_action");
		
		// CHECK FOR HOOK Functions
		$hookFunction = $this->index_name . "_" . $selectedActionFunction;
		foreach ($modules as $module_name => $module) {
			if (is_callable(array($module, $hookFunction))) {
            	$this->hooksActionResult[$module_name] = $module->$hookFunction($this);
            	// CAL THIS TO MAKE SMARTY VARS IN MODULE CONTEXT
            	$module->assignSmartyModuleVariables();
        	}
		}
		
		foreach ($modules as $module_name => $module) {
			if (is_callable(array($module, "receiveEvent"))) {
            	$this->eventsActionResult[$module_name] = $module->receiveEvent($this, $selectedAction);
        	}
		}
		if ($_GET['output'] == 'json') {
			echo json_encode($actionResult);
			exit;
		}
		// CHECK FOR TEMPLATING
		$smarty = $this -> getSmartyVar();
		if (count($this->templates) > 0) {
			$smarty -> assign ("T_" . $this->getName() . "_TEMPLATES", $this->templates);
		}
		$smarty -> assign ("T_" . $this->getName() . "_MOD_DATA", $this->getModuleData());
		
		$this->assignSmartyModuleVariables();
		
		return true;
	}
    public function getSmartyTpl() {
    	if (file_exists($this -> moduleBaseDir . "templates/default.tpl")) {
    		return $this -> moduleBaseDir . "templates/default.tpl";
    	} elseif (file_exists($this -> moduleBaseDir . "templates/actions/" . $this->getCurrentAction() . ".tpl")) {
    		return $this -> moduleBaseDir . "templates/actions/" . $this->getCurrentAction() . ".tpl";
    	//} elseif (file_exists(G_CURRENTTHEMEPATH . "templates/module/default.tpl")) {
    	//	return G_CURRENTTHEMEPATH . "templates/module/default.tpl";
    	//} elseif (file_exists(G_DEFAULTTHEMEPATH . "templates/module/default.tpl")) {
    	//	return G_DEFAULTTHEMEPATH . "templates/module/default.tpl";
    	} else {
//    		throw new Exception("Não foi possível encontrar nenhum template para a ação solicitada");
//    		exit;
    	}
    	return '';
		
    }
	
	public function loadConfig() {
		return array();
	}
	
	public function getConfig() {
		if (!is_array(self::$_CONFIG)) {
			self::$_CONFIG = $this->loadConfig();
		}
		return new ArrayObject(self::$_CONFIG, ArrayObject::ARRAY_AS_PROPS);
	}
    
    public function getModuleJS() {
    	$jsFileName = $this->moduleBaseDir . 'js/' . $this->index_name . '.js';
    	
    	if (file_exists($jsFileName)) {
    		return $jsFileName;
    	}
    	return false;
    }
    
    public function injectJS($jsFiles) {
    	if (!is_array(self::$injectJS)) {
    		self::$injectJS = array();
    	}
    	self::$injectJS[] = $jsFiles;
    	return true;
    }
    public static function getInjectedJS() {
    	if (!is_array(self::$injectJS)) {
    		self::$injectJS = array();
    	}
    	return self::$injectJS;
    }
    public function injectScript($jsScript) {
    	if (!is_array(self::$injectScripts)) {
    		self::$injectScripts = array();
    	}
    	self::$injectScripts[] = $jsScript;
    	return true;
    }
    public static function getInjectedScripts() {
    	if (!is_array(self::$injectScripts)) {
    		self::$injectScripts = array();
    	}
    	return self::$injectScripts;
    }
    
    
    public function getModuleCSS() {
    	$cssFileName = $this->moduleBaseDir . 'css/' . $this->index_name . '.css';
    	
    	if (file_exists($cssFileName)) {
    		return $cssFileName;
    	}
    	return false;
    }
    public function injectCSS($cssFiles) {
    	if (!is_array(self::$injectCSS)) {
    		self::$injectCSS = array();
    	}
    	self::$injectCSS[] = $cssFiles;
    	return true;
    }
    public static function getInjectedCSS() {
    	if (!is_array(self::$injectCSS)) {
    		self::$injectCSS = array();
    	}
    	return self::$injectCSS;
    }
    
    
    
	public function getNavigationLinks($forceAction = null) {
		$currentUser = $this -> getCurrentUser();
		
		$userRole = ($currentUser -> getRole()) ? $currentUser -> getRole() : $currentUser -> getType();
		
		$navLinks =  array (
			array ('title' => _HOME, 'link' => $userRole . ".php")
		);
	
		if ($this->getCurrentCourse() && $this->getCurrentLesson()) {
			$navLinks[] = array ('title' => $this->getCurrentCourse()->course['name'], 'link' => $userRole . ".php?ctg=control_panel");
		}
		
		if ($this->showModuleBreadcrumbs) {
			$navLinks[] = array ('title' => $this->getTitle($this->getDefaultAction()), 'link' => $this->getUrl($this->getDefaultAction()));
	
			if ($this->getDefaultAction() != $this->getCurrentAction()) {
				$navLinks[] = array ('title' => $this->getTitle($this->getCurrentAction()), 'link' => $this->getUrl($this->getCurrentAction())); 
			}
		}
		
		return $navLinks;		
	}    
	
	public function getTitle($action) {
		return $this->getName();
	}
	public function getUrl($action) {
		if (is_null($action)) {
			return $this->moduleBaseUrl;
		}
		return $this->moduleBaseUrl . "&action=" . $action;
	} 
    
	protected function addModuleData($hashID, $hashValue) {
    	if (!is_array($this->_moduleData)) {
    		$this->_moduleData = array();
    	}
    	$this->_moduleData[strtolower($this->getName()) . "." . $hashID] = $hashValue;
    }
	protected function getModuleData() {
		return array_merge_recursive( 
			array(
				$this->index_name . ".baseDir" 	=> $this->moduleBaseDir,
				$this->index_name . ".baseUrl" 	=> $this->moduleBaseUrl,
				$this->index_name . ".baseLink" => $this->moduleBaseLink,
				$this->index_name . ".action" 	=> $this->getCurrentAction()
			), $this->_moduleData
		);
    }
    
    protected function assignSmartyModuleVariables() {
		$selectedAction = $this->getCurrentAction();
		$smarty = $this -> getSmartyVar();
		
        $smarty -> assign("T_" . $this->getName() . "_BASEDIR", $this -> moduleBaseDir);
        $smarty -> assign("T_" . $this->getName() . "_BASEURL", $this -> moduleBaseUrl);
        $smarty -> assign("T_" . $this->getName() . "_BASELINK", $this -> moduleBaseLink);
		$smarty -> assign("T_" . $this->getName() . "_ACTION", $selectedAction);
    }
	
	/* UTILITY FUNCTION */
    public function getParent() {
    	return $this->parentObject; 
    }
    public function setParent($parent) {
    	$this->parentObject = $parent;
    	
    	return $this;
    }
    private function _call($type = 'action', $selectedAction = null, $context = null, $index = null, $info = null) {
    	if (is_null($selectedAction)) {
    		$selectedAction = $this->getCurrentAction();
    	}
    	if (is_null($context)) {
    		$context = $this;
    	}
		$selectedActionFunction = $this->camelCasefying($selectedAction . "_" . $type);
		if (is_callable(array($this, $selectedActionFunction))) {
			if ($type == 'block') {
				return $actionResult = $this->$selectedActionFunction($index, $info);	
			} else {
				return $actionResult = $this->$selectedActionFunction($info);
			}
		} else {
			return false;
		}
    }
    public function callAction($selectedAction = null, $context = null) {
    	return $this->_call("action", $selectedAction, $context);
    }
    public function callBlock($selectedAction = null, $blockIndex = null, $context = null, $info) {
    	return $this->_call("block", $selectedAction, $context, $blockIndex, $info);
    }
    public function appendTemplate($templateData, $index = null) {
    	if (!is_null($index)) {
    		$this->templates[$index] = $templateData;
    	} else {
    		$this->templates[] = $templateData;	
    	}
    	
    	return $this;
    } 
	public function camelCasefying($string, $sep = "_") {
		$strings = explode("_", $string);
		
		foreach($strings as &$data) {
			$data = ucfirst($data);
		}
		if (count($strings) > 0) {
			$strings[0] = strtolower($strings[0]);
		}
		
		return implode('', $strings);
	}
	protected function loadModule($name) {
		if ($name == "xpayment") {
			$name = "pagamento";
		}
		$simpleName = $name;
		if ( is_null(self::$allModules) ) {
			self::$allModules = $this->loadAllModules();
			/*
			if (count(self::$allModules) == 0) {
				$modulesDB = eF_getTableData("modules","*", "active = 1");
				foreach ($modulesDB as $module) {
					$folder = $module['position'];
					$className = $module['className'];
					     	
					require_once G_MODULESPATH.$folder."/".$className.".class.php";
					if (class_exists($className)) {
						self::$allModules[$className] = new $className("", $folder);
					}
				}
			}
			 * */
		}
		if (!array_key_exists($name, self::$allModules)) {
			$name = "module_" . $name;
		}
		if (!array_key_exists($name, self::$allModules)) {
			return false;
		}
		return $this->modules[$simpleName] = self::$allModules[$name];
	}
	
	protected function loadAllModules($onlyActive = true) {
		$modules = eF_loadAllModules($onlyActive);
		
		if (count($modules) == 0) {
			$modulesDB = eF_getTableData("modules","*", $onlyActive ? "active = 1" : "");
			foreach ($modulesDB as $module) {
				$folder = $module['position'];
				$className = $module['className'];
				     	
				require_once G_MODULESPATH.$folder."/".$className.".class.php";
				if (class_exists($className)) {
					$modules[$className] = new $className("", $folder);
				}
			}
		}
		return $modules;
	} 
	
	protected function createToken($length){
		$salt = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789"; // salt to select chars from
		mt_srand((double)microtime()*1000000); // start the random generator
		$token = ""; // set the inital variable
		for ($i = 0; $i < $length; $i++) { // loop and create password
			$token = $token . substr ($salt, mt_rand() % strlen($salt), 1);
		}
		return $token;
	}
	
    // Utility method used to get and put data on session, or other cache system. 
    /** @todo (Outros métodos de cache devem ser incorporados posteriormente). */
	protected static function getStaticCache($module, $hash) {
		$key = "module_" . str_replace("module_", "", $module);
		
		if (!array_key_exists($key, $_SESSION)) {
    		$_SESSION[$key] = array();
    	}
    	if (!is_array($_SESSION[$key])) {
    		$_SESSION[$key] = array();
    	}
    	if (!array_key_exists($hash, $_SESSION[$key])) {
    		return false;
    	}
    	return $_SESSION[$key][$hash];
	}
    protected static function setStaticCache($module, $hash, $value) {
    	$key = "module_" . str_replace("module_", "", $module);
    	
    	if (!array_key_exists($key, $_SESSION)) {
    		$_SESSION[$key] = array();
    	}
    	if (!is_array($_SESSION[$key])) {
    		$_SESSION[$key] = array();
    	}
    	$_SESSION[$key][$hash] = $value;
    	return $this;
    }
    public function getCache($hash) {
    	return $this->getStaticCache(get_class($this), $hash);
	}
    public function setCache($hash, $value) {
    	return $this->setStaticCache(get_class($this), $hash, $value);
    }
	
	/* WANTED FUNCTIONS */
	/** @todo Criar uma forma de registrar o curso em que o aluno entrou, e qual é a sua turma. */
    public function getCurrentCourse() {
    	global $currentCourse;
    	if (eF_checkParameter($_GET['xcourse_id'], 'id')) {
    		self::setStaticCache("xcourse", "current_course_id", $_GET['xcourse_id']);
			return $currentCourse = new MagesterCourse($_GET['xcourse_id']);
		}
		if (eF_checkParameter($_SESSION['s_courses_ID'], 'id')) {
			return $currentCourse = new MagesterCourse($_SESSION['s_courses_ID']);
		}
		if (eF_checkParameter($_GET['from_course'], 'id')) {
			return $currentCourse = new MagesterCourse($_GET['from_course']);
		}
		
    	if (!is_null($currentCourse) && $currentCourse instanceof MagesterCourse) {
    		return $currentCourse;	
    	}
    	if ($course_id = self::getStaticCache("xcourse", "current_course_id")) {
    		return $currentCourse = new MagesterCourse($course_id);	
    	}
		
		
		return false;
	}
    public function setCurrentCourse($course_id = null) {
    	if (!is_null($course_id)) {
    		$_GET['xcourse_id'] = $course_id;
    	}
		return $this->getCurrentCourse();
	}

    public function getCurrentClasse() {
    	global $currentClasse;
    	if (eF_checkParameter($_GET['xclasse_id'], 'id')) {
    		self::setStaticCache("xcourse", "current_classe_id", $_GET['xclasse_id']);
			return $currentClasse = new MagesterCourseClass($_GET['xclasse_id']);
		}
    	if (!is_null($currentClasse) && $currentClasse instanceof MagesterCourseClass) {
    		return $currentClasse;	
    	}
    	if ($classe_id = self::getStaticCache("xcourse", "current_classe_id")) {
    		return $currentClasse = new MagesterCourseClass($classe_id);	
    	}
		return false;
    }
    public function setCurrentClasse($classe_id = null) {
    	if (!is_null($classe_id)) {
    		$_GET['xclasse_id'] = $classe_id;
    	}
		return $this->getCurrentClasse();
	}

	public function getEditedPage($reload = false, $page_ID = null) {
    	if (!is_null($page_ID)) {
    		$reload = true;
    	}
    	if (!is_null($this->editedPage) && !$reload) {
    		return $this->editedPage;
    	}
		if (eF_checkParameter($_GET['xpage_id'], 'id') && !eF_checkParameter($page_ID, 'id')) {
			$page_ID = $_GET['xpage_id'];
		}
		if (eF_checkParameter($page_ID, 'id')) {
			$result = eF_getTableData("module_xcms_pages", "*", "id = " . $page_ID);
    		if (count($result) > 0) {
    			$result[0]['rules'] = json_decode($result[0]['rules'], true);
				$result[0]['positions'] = json_decode($result[0]['positions'], true);
				
    			return $this->editedPage = $result[0];
    		}
		}
    	return false;
	}
	public function getCurrentUserIesIDs() {
		global $currentUserIESIds;
		if (is_null($currentUserIESIds)) { 
	   		$userObject = $this->getCurrentUser();
	   		
	   		if (
				$userObject->getType() != "administrator" && 
				in_array($userObject->getType(), array_keys($userObject->getStudentRoles()))
			) { 
	   			$userIES = eF_getTableDataFlat("courses c LEFT JOIN users_to_courses uc ON (c.id = uc.courses_ID)", "ies_id", "uc.users_LOGIN = '" . $userObject->user['login'] . "'");
	   			$currentUserIESIds = $userIES['ies_id'];
/*	   			
				$xEnrollmentModule = $this->loadModule("xenrollment");
			
		   		$userIES = $xEnrollmentModule->getEnrollmentFieldByUserId($userObject->user['id'], "ies_id");
		   		$currentUserIESIds = $userIES;
*/
			} else {
				$userIES = eF_getTableDataFlat("module_xies_to_users", "ies_id", "user_id = " . $userObject->user['id']);
				$currentUserIESIds = $userIES['ies_id'];
			}
		}
		return $currentUserIESIds;
	}
	public function getCurrentUserIes() {
		global $currentUserIES;
		if (is_null($currentUserIES)) { 
	   		$userObject = $this->getCurrentUser();
	   		
	   		if (
				$userObject->getType() != "administrator" && 
				in_array($userObject->getType(), array_keys($userObject->getStudentRoles()))
			) { 
				$xEnrollmentModule = $this->loadModule("xenrollment");
		   		$userIES = $xEnrollmentModule->getEnrollmentFieldByUserId($userObject->user['id'], "ies_id");
		   		$currentUserIES = eF_getTableData("module_ies ies", "*", "id IN (" . implode(",", $userIES) . ")");
			} else {
				$currentUserIES = eF_getTableData("module_xies_to_users ies2usr LEFT JOIN module_ies ies ON (ies2usr.ies_id = ies.id)", "ies2usr.ies_id, ies.*", "user_id = " . $userObject->user['id']);
			}
		}
		return $currentUserIES;
	}
    public function getEditedUser($reload = false, $login = null) {
    	if (!is_null($login)) {
    		if (eF_checkParameter($login, 'id')) {
	    		$xuserModule = $this->loadModule("xuser");
    			return $this->editedUser = $xuserModule->getUserById($login);
    		} else {
    			return $this->editedUser = MagesterUserFactory::factory($login);
    		}
    	}
    	
    	if (!is_null($this->editedUser) && !$reload) {
    		return $this->editedUser;
    	}
    	if (eF_checkParameter($_GET['xuser_id'], 'id')) {
    		$xuserModule = $this->loadModule("xuser");
    		
    		return $this->editedUser = $xuserModule->getUserById($_GET['xuser_id']);
		} elseif (eF_checkParameter($_GET['xuser_login'], 'login')) {
			return $this->editedUser = MagesterUserFactory::factory($_GET['xuser_login']);
		}
    	
    	return false;
    }	
    public function getEditedCourse($reload = false, $course_ID = null) {
    	try {
	    	if (!is_null($course_ID)) {
	    		return $this->editedCourse = new MagesterCourse($course_ID);
	    	}
	    	if (!is_null($this->editedCourse) && !$reload) {
	    		return $this->editedCourse;
	    	}
			if (eF_checkParameter($_GET['xcourse_id'], 'id')) {
				return $this->editedCourse = new MagesterCourse($_GET['xcourse_id']);
			}
    	} catch (Exception $e) {
    		return false;
    	}
    	return false;
    }
    public function getEditedLesson($reload = false, $lesson_ID = null) {
    	if (!is_null($lesson_ID)) {
    		return $this->editedLesson = new MagesterLesson($lesson_ID);
    	}
    	if (!is_null($this->editedLesson) && !$reload) {
    		return $this->editedLesson;
    	}
		if (eF_checkParameter($_GET['xlesson_id'], 'id')) {
			return $this->editedLesson = new MagesterLesson($_GET['xlesson_id']);
		}
		// COMPAT MODE
		if (eF_checkParameter($_GET['lessons_ID'], 'id')) {
			return $this->editedLesson = new MagesterLesson($_GET['lessons_ID']);
		}
		if ($_SESSION['s_lessons_ID']) {
			return $this->editedLesson = new MagesterLesson($_SESSION['s_lessons_ID']);
		}
    	return false;
    }
	public function getEditedPayment($reload = false, $payment_ID = null) {
    	if (!is_null($payment_ID)) {
    		$xPaymentModule = $this->loadModule("xpayment");

			return $this->editedPayment = $xPaymentModule->getPaymentById($payment_ID);
    	}
    	if (!is_null($this->editedPayment) && !$reload) {
    		return $this->editedPayment;
    	}
		if (eF_checkParameter($_GET['xpayment_id'], 'id')) {
			$xPaymentModule = $this->loadModule("xpayment");
			return $this->editedPayment = $xPaymentModule->getPaymentById($_GET['xpayment_id']);
		}
    	return false;
    }
    public function getEditedCourseClass($reload = false, $classe_id = null) {
    	if (!is_null($classe_id)) {
    		return $this->editedCourseClass = new MagesterCourseClass($classe_id);
    	}
    	if (!is_null($this->editedCourseClass) && !$reload) {
    		return $this->editedCourseClass;
    	}
		if (eF_checkParameter($_GET['xclasse_id'], 'id')) {
			return $this->editedCourseClass = new MagesterCourseClass($_GET['xclasse_id']);
		}
    	return false;
    }
    public function getEditedEnrollment($reload = false, $enroll_ID = null) {
    	if (!is_null($enroll_ID)) {
    		$xEnrollmentModule = $this->loadModule("xenrollment");
    		return $this->editedEnrollment = $xEnrollmentModule->getEnrollmentById($enroll_ID);
    	}
    	if (!is_null($this->editedEnrollment) && !$reload) {
    		return $this->editedEnrollment;
    	}
		if (eF_checkParameter($_GET['xenrollment_id'], 'id')) {
    		$xEnrollmentModule = $this->loadModule("xenrollment");
    		return $this->editedEnrollment = $xEnrollmentModule->getEnrollmentById($_GET['xenrollment_id']);
		}
    	return false;
    }
    
    public function getUserTags($user) {
    	return array();
    }
 
}
