<?php
/**
 * MagesterModule Abstract Class file
 *
 * @package SysClass
 * @version 1.0
 */

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

/**
 * MagesterModuleException class
 *
 * This class extends Exception class and is used to issue errors regarding Modules
 * @author Nick Baltas <mpaltas@magester.com.br>
 * @package SysClass\Modules\Exceptions
 * @version 1.0
 */
class MagesterModuleException extends Exception
{
    const NO_ERROR = 0;
    const BRANCH_NOT_EXISTS = 201;
    const INVALID_ID = 202;
    const FATHER_NOT_VALID = 203;
    const INVALID_LOGIN = 204;
    const DATABASE_ERROR = 205;
    const DIR_NOT_EXISTS = 206;
    const FILESYSTEM_ERROR = 207;
    const DIRECTION_NOT_EXISTS = 208;
    const GENERAL_ERROR = 299;
}


/**
 * MagesterModule class
 *
 * This class represents a branch
 * @author Nick Baltas <mpaltas@magester.com.br>
 * @package SysClass\Modules
 * @version 1.0
 *
 */
abstract class MagesterModule
{
    /**
     * Classname
     * @type string
     */
    public $className;

    /**
     * Module's base url
     * @type string
     */
    public $moduleBaseUrl;

    /**
     * Module's base directory
     * @type string
     */
    public $moduleBaseDir;

    /**
     * Module's base link
     * @type string
     */
    public $moduleBaseLink;

    /**
     * Constructor defining the relative paths for the module
     *
     * @param string $defined_moduleBaseUrl
     * @param string $defined_moduleFolder
     *
     */
    function __construct($defined_moduleBaseUrl , $defined_moduleFolder )
    {
        // Information set by running environment
        $this->className = get_class($this);
        $this->moduleBaseDir = G_MODULESPATH. $defined_moduleFolder ."/";
        $this->moduleBaseUrl = $defined_moduleBaseUrl;
        $this->moduleBaseLink = G_SERVERNAME . "modules/". $defined_moduleFolder . "/";
    }

    /**
     * Function that checks whether the module's defined components are correct
     *
     * @param string &$error Variable in which the error message will be stored
     * in case of error
     * @return boolean True if everything is OK
     */
    function diagnose(&$error)
    {
        // Check whether the roles defined are acceptable
        $roles = $this->getPermittedRoles();
        foreach ($roles as $role) {
            if ($role != 'administrator' && $role != 'student' && $role != 'professor') {
                $error = _PERMITTEDROLESMODULEERROR;
                return false;
            }
        }
        // Check existence of user defined files
        $tpl = $this->getSmartyTpl();
        if ($tpl && !is_file($tpl)) {
            $error = _SMARTYTEMPLATEDOESNOTEXIST . ": ".$tpl;
            return false;
        }
        $tpl = $this->getLessonSmartyTpl();
        if ($tpl && !is_file($tpl)) {
            $error = _SMARTYTEMPLATEDOESNOTEXIST . ": ".$tpl;
            return false;
        }
        $tpl = $this->getControlPanelSmartyTpl();
        if ($tpl && !is_file($tpl)) {
            $error = _SMARTYTEMPLATEDOESNOTEXIST . ": ".$tpl;
            return false;
        }
        $file = $this->getModuleJS();
        if ($file && !is_file($file)) {
            $error = _FILEDOESNOTEXIST . ": ".$file;
            return false;
        }
        $file = $this->getModuleCSS();
        if ($file && !is_file($file)) {
            $error = _FILEDOESNOTEXIST . ": ".$file;
            return false;
        }
        // All checks passed successfully
        return true;
    }

    // Fundamental methods
    /**
     * The name-title of the module (Mandatory)
     */
    abstract public function getName();

    /**
     * Mandatory function returning an array of permitted roles from
     * "administrator", "professor", "student"
     */
    abstract public function getPermittedRoles();

    /**
     * Function denoting whether the module is related to lessons
     * (and hence can be activated-deactivated) or not
     *
     * @return boolean True if this module relates to lessons
     */
    public function isLessonModule()
    {
        return false;
    }

    /**
     * Get the path of the language file
     *
     * Can be overriden to include any file
     *
     * @param string $language The intended language
     * @return string The full path to the translation file
     */
    public function getLanguageFile($language)
    {
        // TRY TO LOAD FILE FROM module_language
        /*
        $modules = sC_loadAllModules(true);

        var_dump($modules['module_language']);
        exit;

        */
        if(is_file($this->moduleBaseDir . "lang-" . $language . ".php")) {
            return $this->moduleBaseDir . "lang-" . $language . ".php";
        }
        return $this->moduleBaseDir . "lang-english.php";

    }

    /**
     * Any further actions that need to take place during installation
     *
     * Function to be executed when the module is installed to a SysClass system
     */
    public function onInstall()
    {
        return true;
    }

    /**
     * Any further actions that need to take place during uninstalling
     *
     * Function to be executed when a module is deleted from a SysClass system
     */
    public function onUninstall()
    {
        return true;
    }

    /**
     * Any further actions that need to take place during module upgrade
     *
     * Function to be executed when the module is upgraded from the link of the modules' list
     *
     * This might relate mainly to changes taking place in the database tables that have
     * been defined for this module. If the upgraded version of the module is to
     * use different tables at the SysClass database (like different or additional fields or field
     * names), then this function should take care to maintain existing data from
     * the previous module version to the new table. This could happen like that:
     *
     * 1. Create a temporary table of the form that the upgraded version of the module requires
     * 2. Parse data from the existing table
     * 3. Transform them in such a way that the newly defined table will accept
     * 4. Insert the transformed data to the newly created temp table
     * 5. Delete the initial table (from which the data have been read) for the module
     * 6. Rename the temporary table to the name that the module table needs to have
     *
     * This algorithm guarantees that if something goes wrong no data will be lost, since
     * existing data are deleted only once they have been successfully copied to the new table
     * It is noted here that if onUpgrade() is not defined, then the SysClass system will leave
     * existing module database tables and their data intact.
     */
    public function onUpgrade()
    {
        return true;
    }

    /**
     * SysClass information provided to the module
     *
     * IES stands for Instituicao de Ensino Superior
     */
    public function getCurrentIes()
    {
        global $currentIES;
        /**
         * @todo change code to get ies_code from enrollment, and can return a array of ID's
         */
        if (is_null($currentIES)) {
            $userData = $this->getCurrentUser();
            $userDetailData = MagesterUserDetails::getUserDetails($userData->user['login']);

            $currentIesID = !empty($userDetailData['ies_id']) && is_numeric($userDetailData['ies_id']) ? $userDetailData['ies_id'] : 1;
            list($currentIES) = sC_getTableData("module_ies", "*", "id = " . $currentIesID);
        }
        return $currentIES;
    }

    /**
     * Get the user of this session
     *
     * @return MagesterUser
     */
    public function getCurrentUser()
    {
        global $currentUser;
        return $currentUser;
    }

    /**
     * Get the lesson of this session
     *
     * @return MagesterLesson
     */
    public function getCurrentLesson()
    {
        global $currentLesson;
        return $currentLesson;
    }

    /**
     * Get the unit of this session
     *
     * @return MagesterUnit
     */
    public function getCurrentUnit()
    {
        global $currentUnit;
        return $currentUnit;
    }

    /**
     * Get the Smarty template handler
     *
     * @return Smarty
     */
    public function getSmartyVar()
    {
        global $smarty;
        return $smarty;
    }

    /**
     * Set the global messages variables
     *
     * @param string $message
     * @param string $message_type
     */
    public function setMessageVar($message, $message_type)
    {
        $GLOBALS['message'] = $message;
        $GLOBALS['message_type'] = $message_type;
        return true;
    }

    /**
     * Add event to SysClass events log
     *
     * This function enables module to provide events to the log
     * Events should be UNIQUELY defined INSIDE the module
     *
     * All data required for the appearance of the log message (provided by the getEventMessage function)
     * should be defined in the second argument array.
     *
     * Example:
     * <code>
     * $define("NEW_MODULE_ENTITY_INSERTION", 1);
     * $data_array = array("id" => $id, "title" => $title);
     * $module->addEvent(NEW_MODULE_ENTITY_INSERTION, $data_array);
     * </code>
     *
     * Note:
     * Field timestamp is automatically completed<br />
     * If fields "users_LOGIN", "users_name" and "users_surname"
     *     are not defined, then the currentUser's info will be used<br />
     * If fields "lessons_ID" and "lessons_name" are defined,
     *     then this event will also be related with that lesson<br />
     * The array might contain any other fields. However, the exact same ones
     *     need to be used by getEventMessage
     * @param integer $type the unique code of the event inside
     *     the particular module scope
     * @param array $data information required by the getEventMessage
     *     function to display the related message for this event.
     *
     * @return MagesterEvent The result of the event insertion to
     *     the database or false if arguments are not correct
     * @since 3.6.0
     * @access public
     */
    public function addEvent($type, $data)
    {
        $fields = array();
        // All module related events have the same offset + the particular event's type
        $fields['type'] = MagesterEvent::MODULE_BASE_TYPE_CODE + (integer) $type;
        // This should not exist normally, just in case
        unset($data['type']);
        // The discimination between events from different modules with the same type is made
        // by the entity_ID field, which is the className of the implicated module
        $fields['entity_ID'] = $this->className;
        // Mandatory users_LOGIN, users_surname, users_name fields
        if (isset($data['users_LOGIN'])) {
            $fields['users_LOGIN'] = $data['users_LOGIN'];
            if (isset($data['surname']) && isset($data['name'])) {
                $fields['users_surname'] = $data['surname'];
                $fields['users_name'] = $data['name'];
            } else {
                $eventsUser = MagesterUserFactory::factory($data['users_LOGIN']);
                $fields['users_surname'] = $eventsUser -> user['surname'];
                $fields['users_name'] = $eventsUser -> user['name'];
            }
            // We remove data fields, to serialize all remaining ones into the entity_name field
            unset($data['users_LOGIN']);
            unset($data['users_surname']);
            unset($data['users_name']);
        } else {
            $currentUser = $this ->getCurrentUser();
            $fields['users_LOGIN'] = $currentUser -> user['login'];
            $fields['users_surname'] = $currentUser -> user['surname'];
            $fields['users_name'] = $currentUser -> user['name'];
        }
        // The lessons_ID field associates an event with a specific lesson
        if (isset($data['lessons_ID'])) {
            $fields['lessons_ID'] = $data['lessons_ID'];
            if (isset($data['lessons_name'])) {
                $fields['lessons_name'] = $data['lessons_name'];
                unset($data['lessons_name']);
            } else {
                $lesson = new MagesterLesson($fields['lessons_ID']);
                $fields['lessons_name'] = $lesson -> lesson['name'];
            }
            // We remove data fields, to serialize all remaining ones into the entity_name field
            unset($data['lessons_ID']);
        }
        // Serialize all remaining user provided data for this event, with the same labels as the ones given
        if (!empty($data)) {
            $fields['entity_name'] = serialize($data);
        }
        // Finally get current time
        $fields['timestamp'] = time();
        return MagesterEvent::triggerEvent($fields);
    }
    /**
     * Get the message associated to a particular event
     *
     * This function returns the message that should appear in
     * a log/timeline/email digest etc for this event. Data provided
     * during event insertion are now used to create the message
     * that should be returned.
     *
     *
     * Example of implementation:
     * <code>
     * public function getEventMessage($type, $data) {
     *     if ($type == 1) {
     *         $message = "User {$data['users_surname']} {$data['users_name']}";
     *         $message .= " inserted <a href='student.php?entity_ID=";
     *         $message .= "{$data['id']}'>entity</a> with title: {$data['title']}";
     *         return $message;
     *     }
     *     return false;
     * }
     * </code>
     *
     * Notes:
     * Fields "timestamp", "users_LOGIN", "users_name" and "users_surname"
     *     are ALWAYS provided<br />
     * The remaining fields are the same ones provided by the addEvent function<br />
     * The time of the event is implicitly printed by the SysClass system
     *     and should not be provided by your defined event messages<br />
     *
     * @param integer $type The unique code of the event inside the
     *     particular module scope
     * @param array $data Information as provided by the addEvent method,
     *     needed to display this message for this event.
     *
     * @return the message associated with this event and the provided data
     *     or false if no such message is to be provided
     * @since 3.6.0
     * @access public
     */
    public function getEventMessage($type, $data)
    {
        return false;
    }

    /**
     * Load current SysClass scripts from the www/js folder
     *
     * @return array An array("XX","folderY/ZZ") will load
     *     www/js/XX.js and www/js/folderY/ZZ.js
     */
    public function addScripts()
    {
        return array();
    }

    /**
     * Load current SysClass css from the www/css folder
     *
     * @return array An array("XX","folderY/ZZ") will load
     *     www/css/XX.css and www/css/folderY/ZZ.css
     */
    public function addStylesheets()
    {
        return array();
    }

    /***********************************************/
    /************ DEFINING MODULE PAGES ************/
    /***********************************************/
    /***** Main - Independent module pages *******/
    /**
    * This is the function for the php code of the MAIN module pages (the ones
    * called from url:    $this->moduleBaseUrl . "&...."
    *
    * The global smarty variable may also be used here and in conjunction
    * with the getSmartyTpl() function, use php+smarty to display the page
    */
    public function getModule()
    {
        return false;
    }

    /**
     * This is the function that returns the name of the module smarty template file
     * for the appearance of the main page of the module (if one such is used)
     *
     * Example implementation:
     * <code>
     * public function getSmartyTpl() {
     *     return "{$this->moduleBaseDir}/template.tpl";
     * }
     * </code>
     *
     * @return string The full path to the template file
     */
    public function getSmartyTpl()
    {
        $smarty = $this->getSmartyVar();
        $smarty -> assign("T_MODULE_BASEDIR" , $this->moduleBaseDir);
        $smarty -> assign("T_MODULE_BASELINK" , $this->moduleBaseLink);
        $smarty -> assign("T_MODULE_BASEURL" , $this->moduleBaseUrl);
        return false;
    }

    /***** Lesson module pages *******/
    /**
    * This is the function for the php code of the module page that may
    * appear as a sub-window on the main lesson page of the current Lesson (for students/professors)
    *
    * Note: Current lesson information may be retrieved with the getCurrentLesson() function
    */
    public function getLessonModule()
    {
        return false;
    }

    /**
     * Gets the full path to the Smarty template file of the lesson
     *
     * @return string The full path to the template file of the lesson
     */
    public function getLessonSmartyTpl()
    {
        return false;
    }

    /***** Lesson content module pages *******/
    public function getContentSideInfo()
    {
        return false;
    }

    public function getContentSmartyTpl()
    {
        return false;
    }

    /**
     * Returns the title string to appear on top of the
     * content side - if such is defined
     *
     * @return string
     */
    public function getContentSideTitle()
    {
        return false;
    }

    /***** Administrator control panel *******/
    /**
    * This is the function for the php code of the module page that may
    * appear as a sub-window on the main administrator control panel page
    */
    public function getControlPanelModule()
    {
        return false;
    }

    public function getControlPanelSmartyTpl()
    {
        $smarty = $this->getSmartyVar();
        $smarty -> assign("T_MODULE_BASEDIR" , $this->moduleBaseDir);
        $smarty -> assign("T_MODULE_BASEURL" , $this->moduleBaseUrl);
        return false;
    }

    public function getDashboardModule()
    {
        return false;
    }

    public function getDashboardSmartyTpl()
    {
        $smarty = $this->getSmartyVar();
        $smarty -> assign("T_MODULE_BASEDIR" , $this->moduleBaseDir);
        $smarty -> assign("T_MODULE_BASEURL" , $this->moduleBaseUrl);
        return false;
    }

    public function getCatalogModule()
    {
        return false;
    }

    public function getCatalogSmartyTpl()
    {
        $smarty = $this->getSmartyVar();
        $smarty -> assign("T_MODULE_BASEDIR" , $this->moduleBaseDir);
        $smarty -> assign("T_MODULE_BASEURL" , $this->moduleBaseUrl);
        return false;
    }

    public function getLandingPageModule()
    {
        return false;
    }

    public function getLandingPageSmartyTpl()
    {
        $smarty = $this->getSmartyVar();
        $smarty -> assign("T_MODULE_BASEDIR" , $this->moduleBaseDir);
        $smarty -> assign("T_MODULE_BASEURL" , $this->moduleBaseUrl);
        return false;
    }

    /**
     * Get module javascript code
     *
     * @return string The full path of the module's javascript file
     */
    public function getModuleJS()
    {
        return false;
    }

    /**
     * Get module's css
     *
     * @return string The full path of the module's css file
     */
    public function getModuleCSS()
    {
        return false;
    }

    /**
     * Get Navigational links for the top of the independent module page(s)
     *
     * Each sub-array represents a different link. Between them the "&raquo;" character is automatically inserted by the system
     *
     * Example implementation:
     * <code>
     * public function getNavigationLinks()
     * {
     *     if (isset($_GET['subpage1']))
     *     {
     *         return array(
     *             array(
     *                 'title' => "Main Page",
     *                 'link'  => $this->moduleBaseUrl
     *             ),
     *             array(
     *                 'title' => "Sub Page 1",
     *                 'link'  => $this->moduleBaseUrl . "&operation=subpage1"
     *             )
     *         );
     *     }
     *     else
     *     {
     *         // Only the default page with the module Name as title will be returned
     *         return false;
     *     }
     * }
     * </code>
     *
     * @return array An array of sub-arrays with fields:
     * <ul>
     * <li>'title': the title to appear on the link</li>
     * <li>'image': the image to appear (if image inside module folder then use ($this->moduleBaseDir) . 'imageFileName' -TODO</li>
     * <li>'link': the url of the page to be from this link</li>
     * </ul>
     *
     */
    public function getNavigationLinks()
    {
        return false;
    }

    /**
     * Get the id of the link to be highlighted by each independent module page
     *
     * Each time a module independent page is displayed a different
     * link of the left sidebar can be highlighted
     * To do this return the id of the corresponding link as defined
     * by your getSidebarLinkInfo() returned array
     *
     * Example implementation:
     * <code>
     * public function getLinkToHighlight()
     * {
     *     if (isset($_GET['management'])) {
     *         return 'other_link_id1';
     *     } else {
     *         return 'other_link_id2';
     *     }
     * }
     * </code>
     */
    public function getLinkToHighlight()
    {
        return false;
    }

    /**
     * Control Panel Module Link
     *
     * Example implementation:
     * <code>
     * public function getCenterLinkInfo()
     * {
     *     return array(
     *         'title' => "My Module",
     *         'image' => "{$this->moduleBaseDir}images/my_module.jpg"
     *     );
     * }
     * </code>
     *
     * @return array An array with fields:
     * <ul>
     * <li>'title': the title to appear on the link</li>
     * <li>'image': the image to appear (if image inside module folder
     *     then use ($this->moduleBaseDir) . 'imageFileName'</li>
     * <li>'target': POPUP or innerTable (default Innertable) - TODO</li>
     * </ul>
    */
    public function getCenterLinkInfo()
    {
        return false;
    }

    /**
     * Get the info regarding to the link on the main control panel
     *
     *  Example implementation:
     * <code>
     * public function getCenterLinkInfo()
     * {
     *     return array(
     *         'title' => "My Module",
     *         'image' => "{$this->moduleBaseDir}images/my_module.jpg"
     *     );
     * }
     * </code>
     * @return array An array with fields:
     * <ul>
     * <li>'title': the title to appear on the link</li>
     * <li>'titl'image': the image to appear (if image inside module folder then use ($this->moduleBaseDir) . 'imageFileName'</li>
     * <li>'titl'target': POPUP or innerTable (default Innertable) - TODO</li>
    */
    public function getLessonCenterLinkInfo()
    {
        return false;
    }

    /**
     * Get the lesson top link info
     *
     * @param integer $lesson_id
     * @param integer $course_id
     */
    public function getLessonTopLinkInfo($lesson_id, $course_id)
    {
        return false;
    }

    /**
    * Get infos regarding to link(s) on the menu(s) of the left side control panel
    *
    *  Example implementation:
    * <code>
    * public function getSidebarLinkInfo()
    * {
    *     $link_of_menu_system = array(
    *         array(
    *             'id'    => 'system_link_id',
    *             'title' => 'My System Related Module Part 1',
    *             'link'  => $this->moduleBaseUrl . "&module_op=system_operation"
    *
    *              // no extension in the filename,
    *             'image' => '16x16/pens',
    *
    *             // question_type_free_text.png and pens.gif must exist in 16x16
    *             '_magesterExtensions' => '1',
    *         ),
    *         array(
    *             'id'    => 'system_link_id2',
    *             'title' => 'My System Related Module Part 2',
    *             'image' => '16x16/pencil2.png',
    *             'link'  => $this->moduleBaseUrl . "&module_op=system_operation"
    *         )
    *     );
    *
    *     $link_of_module_menus  = array(
    *         array(
    *             'id'    => "other_link_id1",
    *             'title' => "Main Module",
    *             'link'  => $this->moduleBaseUrl
    *
    *              // no extension in the filename
    *             'image' => "{$this->moduleBaseDir}images/my_module_pic",
    *
    *             // my_module_pic.gif and my_module_pic.png
    *             // must exist in "{$this->moduleBaseDir}images/"
    *             '_magesterExtensions' => "1",
    *         ),
    *         array(
    *             'id'    => 'other_link_id2',
    *             'title' => 'Second Module Page',
    *             'image' => '16x16/attachment.png',
    *             'link'  => $this->moduleBaseUrl . '&module_operat=2'
    *         )
    *     );
    *
    *     return array(
    *         "system" => $link_of_menu_system,
    *         "other"  => array(
    *             'menuTitle' => 'My Module Menu',
    *             'links' => $link_of_module_menus
    *         )
    *     );
    * }
    * </code>
    * @return array An array of arrays with fields:
    * <ul>
    * <li>'menu': defines the menu(s) where links will appear
    *     "system" | "lessons" | "users" | "organization" |
    *     "tools" | "current_lesson" | "other".
    *     If "other" is selected then an additional "menuTitle" field can
    *     be defined for the Title of the menu
    *     -- multiple other menus may be defined - TODO</li>
    * <li>'id': a unique id of the link within the module
    *     (and NOT within the entire SysClass) framework.
    *     This id is used for link highlighting purposes
    *     with highlightLink()</li>
    * <li>'title': the title to appear on the link</li>
    * <li>'image': the image to appear next to the link
    *     (if image inside module folder then use
    *     "{$this->moduleBaseDir)}imageFileName"</li>
    * <li>'_magesterExtensions': you may optionally define two images
    *     for each link: one .png and .gif, which will appear under
    *     FF and IE respectively.
    *     The filename (without the extension) and the path of the two
    *     pictures must be the same. If '_magesterExtensions' => 1,
    *     then do not use an extension to the image filename</li>
    * <li>'link': the url of the page to be displayed in the main window</li>
    * <li>'target': POPUP or mainTable (default Innertable) - TODO</li>
    * </ul>
    */
    public function getSidebarLinkInfo()
    {
        return false;
    }

    //the following two can also become a module-aspect in User
    /**
     * Code to execute when a user with login = $login has been registered
     *
     * @param string $login Login of the user being added
     */
    public function onNewUser($login)
    {
        return false;
    }

    /**
     * Code to execute when a user with login = $login is deleted
     *
     * @param string $login Login of the user being deleted
     */
    public function onDeleteUser($login)
    {
        return false;
    }

    //the following two can also become a module-aspect in Lesson
    /**
     * Code to execute when a lesson with id = $lessonId has been registered
     *
     * @param integer $lessonId Id of the lesson being added
     */
    public function onNewLesson($lessonId)
    {
        return false;
    }

    /**
     * Code to execute when a lesson with id = $lessonId is deleted
     *
     * @param integer $lessonId Id of the lesson being deleted
     */
    public function onDeleteLesson($lessonId)
    {
        return false;
    }

    //the following two can also become a module-aspect in CourseClass
    /**
     * Code to execute when a course classe with id = $courseclassId
     * has been registered
     *
     * @param integer $courseclassId Id of the courseclass being added
     */
    public function onNewCourseClass($courseclassId)
    {
        return false;
    }

    /**
     * Code to execute when a course classe with id = $courseclassId is deleted
     *
     * @param integer $courseclassId Id of the courseclass being deleted
     */
    public function onDeleteCourseClass($courseclassId)
    {
        return false;
    }


    /**
     * Code to execute when a lesson with id = $lessonId is exported.
     *
     * This function should return an array with all information (like DB values)
     * that need to be stored into the exported lesson file
     *
     *  Example implementation:
     * <code>
     * public function onExportLesson($lessonId) {
     *     $data = sC_getTableData("myModule", "*", "lessons_ID = $lessonId");
     *     $data['myModuleVersion'] = "3.5beta";
     *     return $data;
     * }
     * </code>
     *
     * @param integer $lessonId Id of the lesson being exported
     *
     */
    public function onExportLesson($lessonId)
    {
        return false;
    }

    /**
     * Code to execute when a lesson with id = $lessonId is imported.
     *
     * This function gets $data as argument which is in the exact same format
     * as it was exported by the onExportLesson function.
     *
     * Example implementation (in accordance with the above given export example):
     * <code>
     * public function onExportLesson($lessonId, $data) {
     *     echo "My module's version is {$data['myModuleVersion']}";
     *     unset($data['myModuleVersion']);
     *     foreach ($data as $record) {
     *         sC_insertTableData("myModule", $record);
     *     }
     *     return true;
     * }
     * </code>
     * @param integer $lessonId Id of the lesson being imported
     * @param $data
     */
    public function onImportLesson($lessonId, $data)
    {
        return false;
    }

    /**
     * Code to execute when a lesson with id = $lessonId is completed
     *
     * @param integer $lessonId Id of the lesson being imported
     * @param $login
     */
    public function onCompleteLesson($lessonId, $login)
    {
        return false;
    }
    /**
     * Code to execute every time a new page is loaded
     *
     * For system events
     */
    public function onNewPageLoad()
    {
        return false;
    }

    /**
     * Code to execute when the page finish loading the Smarty template
     */
    public function onPageFinishLoadingSmartyTpl()
    {
        return false;
    }

    /**
     * Code to execute when a new user is added to a course
     *
     * @param string $login
     * @param integer $courseID
     */
    public function onNewUserOnCourse($login, $courseID)
    {
        return false;
    }
}
