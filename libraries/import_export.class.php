<?php

/**
 * Abstract class for any Import class - serves as an interface for subsequent
 * developed importers
 *
 * @package SysClass
 * @abstract
 */
abstract class MagesterImport
{
 /**
	 * The contents of the file to be imported
	 *
	 * @since 3.6.1
	 * @var string
	 * @access private
	 */
 protected $fileContents;

 /**
	 * Various options like duplicates handling are stored in the options array
	 *
	 * @since 3.6.1
	 * @var array
	 * @access private
	 */
 protected $options;

 /**
	 * Log where the results of the import are stored
	 *
	 * @since 3.6.1
	 * @var array
	 * @access private
	 */
 protected $log = array();

    /**
     * Import the data from the file following the designated options
     *
     * <br/>Example:
     * <code>
     * $importer -> import(); //returns something like /var/www/magester/upload/admin/
     * $logMessages = $importer -> getLogMessages();
     * </code>
     *
     * @return void
     * @since 3.6.1
     * @access public
     */

    /**
     * Get the log of the import operations
     *
     * <br/>Example:
     * <code>
     * $importer -> import(); //returns something like /var/www/magester/upload/admin/
     * $logMessages = $importer -> getLogMessages();
     * </code>
     *
     * @return array with subarrays "success" and "failure" each with corresponding messages
     * @since 3.6.1
     * @access public
     */
 public function getLogMessages()
 {
  return $this -> log;
 }

 private static $datatypes = false;
 public static function getImportTypes()
 {
  if (!self::$datatypes) {
   self::$datatypes = array("anything" => _IMPORTANYTHING,
          "users" => _USERS,
          "users_to_courses" => _USERSTOCOURSES);
  }

  return self::$datatypes;
 }
 public function getImportTypeName($import_type)
 {
  if (!$datatypes) {
   $datatypes = MagesterImport::getImportTypes();
  }

  return $datatypes[$import_type];
 }
 public function __construct($filename, $_options)
 {
  $this -> fileContents = file_get_contents($filename);
  $this -> options = $_options;
 }
 /*
	 * All following functions cache arrays of type "entity_name" => array("entity_ids of entities with name=entity_name")
	 */
 protected $courseNamesToIds = false;
 protected function getCourseByName($courses_name)
 {
  if (!$this -> courseNamesToIds) {
   $constraints = array ('return_objects' => false);
   $courses = MagesterCourse::getAllCourses($constraints);
   foreach ($courses as $course) {
    if (!isset($this -> courseNamesToIds[$course['name']])) {
     $this -> courseNamesToIds[$course['name']] = array($course['id']);
    } else {
     $this -> courseNamesToIds[$course['name']][] = $course['id'];
    }
   }
  }

  return $this -> courseNamesToIds[$courses_name];
 }
 private $groupNamesToIds = false;
 protected function getGroupByName($group_name)
 {
  if (!$this -> groupNamesToIds) {
   $groups = MagesterGroup::getGroups();
   foreach ($groups as $group) {
    if (!isset($this -> groupNamesToIds[$group['name']])) {
     $this -> groupNamesToIds[$group['name']] = array($group['id']);
    } else {
     $this -> groupNamesToIds[$group['name']][] = $group['id'];
    }
   }
  }

  return $this -> groupNamesToIds[$group_name];
 }
 /*
	 * Convert dates of the form dd/mm/yy to timestamps
	 */
    protected function createTimestampFromDate($date_field)
    {
        // date of event if existing, else current time
        if ($date_field != "") {
         $date_field = trim($date_field);
         // Assuming dd/mm/yy or dd-mm-yy
            $dateParts = explode("/", $date_field);
            if (sizeof($dateParts) == 1) {
             $dateParts = explode("-", $date_field);
            }
            if ($this -> options['date_format'] == "MM/DD/YYYY") {
             $timestamp = mktime(0,0,0,$dateParts[0],$dateParts[1],$dateParts[2]);
            } elseif ($this -> options['date_format'] == "YYYY/MM/DD") {
             $timestamp = mktime(0,0,0,$dateParts[2],$dateParts[0],$dateParts[1]);
            } else {
             $timestamp = mktime(0,0,0,$dateParts[1],$dateParts[0],$dateParts[2]);
            }

            return $timestamp;
        } else {
         return "";
        }
    }
 /*
	 * Create the mappings between csv columns and db attributes
	 */
 public static function getTypes($type)
 {
  switch ($type) {
   case "users":
    $users_info = array("users_login" => "login",
           "password" => "password",
           "users_email" => "email",
           "language" => "languages_NAME",
           "users_name" => "name",
           "users_surname" => "surname",
           "active" => "active",
           "user_type" => "user_type",
           "registration_date" => "timestamp");

    return $users_info;
   case "users_to_courses":
    return array("users_login" => "users_login",
         "courses_name" => "course_name",
         "course_start_date" => "from_timestamp",
         "course_user_type" => "user_type",
         "course_completed" => "completed",
         "course_comments" => "comments",
         "course_score" => "score",
         "course_active" => "active",
         "course_end_date" => "to_timestamp");
   case "users_to_groups":
    return array("users_login" => "users_login",
        "group_name" => "groups.name");
  }
 }
    /*
     * Get array of fields that are mandatory to be defined for a successfull import according to the type of import
     */
 public static function getMandatoryFields($type)
 {
  switch ($type) {
   case "users":
    return array("login" => "users_login");
   case "users_to_courses":
    return array("users_login" => "users_login",
        "course_name"=> "courses_name");
   case "users_to_groups":
    return array("users_login" => "users_login",
        "groups.name" => "group_name");
  }
 }
 public static function getOptionalFields($type)
 {
  $all = MagesterImport::getTypes($type);
  $mandatory = MagesterImport::getMandatoryFields($type);
  foreach ($mandatory as $type_name => $column) {
   unset($all[$column]);
  }

  return array_keys($all);
 }
}
/****************************************************
 * Class used to import data from csv files
 *
 */
class MagesterImportCsv extends MagesterImport
{
 /*
	 * The separator between the file's fields
	 */
 private $separator = false;
 /*
	 * Array containing metadata about the imported data type (db attribute names, db tables, import-file accepted column names)
	 */
 private $types = false;
 /*
	 * Number of lines of the imported file
	 */
 private $lines = false;
 /*
	 * Array with the mappings between db fields and columns
	 */
 private $mappings = array();
 /*
	 * Used for initialization of data (to contain all fields)
	 */
 private $empty_data = false;
 /*
	 * Find the separator - either "," or ";"
	 */
 private function getSeparator()
 {
  if (!$this -> separator) {
   $this -> separator = ",";
   $test_line = explode($this -> separator, $this -> fileContents[0]);
   if (sizeof($test_line) > 1) {
    return $this -> separator;
   }
   $this -> separator = ";";
   $test_line = explode($this -> separator, $this -> fileContents[0]);
   if (sizeof($test_line) > 1) {
    return $this -> separator;
   }
   $this -> separator = false;
  }

  return $this -> separator;
 }
 /*
	 * Get empty data - used for caching of initialization array
	 */
 private function getEmptyData()
 {
  if (!$this -> empty_data) {
   $this -> empty_data = array();
   foreach ($this -> types as $key) {
    $this -> empty_data[$key] = "";
   }
   unset($this -> empty_data['user_type']); // the default value should never be set to ""
   unset($this -> empty_data['password']); // the default value should never be set to ""
  }

  return $this -> empty_data;
 }
 /*
	 * Split a line to its different strings as they are determined by the separator
	 */
 private function explodeBySeparator($line)
 {
  if ($this -> separator) {
   return explode($this -> separator, $this -> fileContents[$line]);
  } else {
   return $this -> fileContents[$line];
  }
 }
 /*
	 * Find the header line - the first non zero line of the csv that contains at least one of the import $type's column headers
	 * @param: the line of the header
	 */
 private function parseHeaderLine(&$headerLine)
 {
  $this -> mappings = array();
  $this -> separator = $this -> getSeparator();
  $legitimate_column_names = array_keys($this -> types);
  //pr($legitimate_column_names);
  $found_header = false;
  for ($line = 0; $line < $this -> lines; ++$line) {
   $candidate_header = $this -> explodeBySeparator($line);
   $size_of_header = sizeof($candidate_header);
   for ($header_record = 0; $header_record < $size_of_header; ++$header_record) {
    $candidate_header[$header_record] = trim($candidate_header[$header_record], "\"\r\n ");
    if ($candidate_header[$header_record] != "" && in_array($candidate_header[$header_record], $legitimate_column_names)) {
     $this -> mappings[$this -> types[$candidate_header[$header_record]]] = $header_record;
     $found_header = true;
    }
   }
   if ($found_header) {
    $headerLine = $line;

    return $this -> mappings;
   }
  }

  return false;
 }
 /*
	 * Utility function to initialize the $log array
	 */
 private function clearLog()
 {
  $this -> log = array();
  $this -> log["success"] = array();
  $this -> log["failure"] = array();
 }
 private function clear()
 {
  $this -> clearLog();
  $this -> mappings = array();
  $this -> empty_data = false;
  $this -> types = array();
 }
 /*
	 * Get existence exception and compare it against the "already exists" exception of for each different import type
	 */
 private function isAlreadyExistsException($exception_code, $type)
 {
  switch ($type) {
   case "users":
    if ($exception_code == MagesterUserException::USER_EXISTS) { return true; }
    break;
   default:
    return false;
  }

  return false;
 }
 private function cleanUpEmptyValues(&$data)
 {
  foreach ($data as $key => $info) {
   if ($info == "") {
    unset($data[$key]);
   }
  }
 }
 /*
	 * Update the data of an existing record
	 */
 private function updateExistingData($line, $type, $data)
 {
  $this -> cleanUpEmptyValues(&$data);
  try {
   switch ($type) {
    case "users":
     if (isset($data['password']) && $data['password'] != "") {
      $data['password'] = MagesterUser::createPassword($data['password']);
     }
     eF_updateTableData("users", $data, "login='".$data['login']."'"); $this -> log["success"][] = _LINE . " $line: " . _REPLACEDUSER . " " . $data['login'];
     break;
    case "users_to_courses":
     $where = "users_login='".$data['users_login']."' AND courses_ID = " . $data['courses_ID'];
     MagesterCourse::persistCourseUsers($data, $where, $data['courses_ID'], $data['users_login']);
     $this -> log["success"][] = _LINE . " $line: " . _REPLACEDEXISTINGASSIGNMENT;
     break;
    case "users_to_groups":
     break;
   }
  } catch (Exception $e) {
   $this -> log["failure"][] = _LINE . " $line: " . $e -> getMessage();
  }
 }
 private function importDataMultiple($type, $data)
 {
  try {
   switch ($type) {
    case "users_to_groups":
     foreach ($data as $value) {
      $groups_ID = current($this -> getGroupByName($value['groups.name']));
      $groups[$groups_ID][] = $value['users_login'];
     }
     foreach ($groups as $id => $groupUsers) {
      $group = new MagesterGroup($id);
      $this -> log["success"][] = _NEWGROUPASSIGNMENT . " " . $group -> group['name'];
      $group -> addUsers($groupUsers);
     }
     break;
    case "users":
     $existingUsers = eF_getTableDataFlat("users", "login, active, archive");
     $addedUsers = array();
     foreach ($data as $key => $value) {
      try {
       $newUser = MagesterUser::createUser($value, $existingUsers, false);
       $existingUsers['login'][] = $newUser -> user['login'];
       $existingUsers['active'][] = $newUser -> user['active'];
       $existingUsers['archive'][] = 0;
       $addedUsers[] = $newUser -> user['login'];
       $this -> log["success"][] = _IMPORTEDUSER . " " . $newUser -> user['login'];
      } catch (Exception $e) {
       if ($this -> options['replace_existing']) {
        if ($this -> isAlreadyExistsException($e->getCode(), $type)) {
         $this -> updateExistingData($key+2, $type, $value);
        } else {
         $this -> log["failure"][] = _LINE . " ".($key+2).": " . $e -> getMessage();// ." ". str_replace("\n", "<BR>", $e->getTraceAsString());
        }
       } else {
        $this -> log["failure"][] = _LINE . " ".($key+2).": " . $e -> getMessage();// ." ". str_replace("\n", "<BR>", $e->getTraceAsString());
       }
      }
     }
     $defaultGroup = eF_getTableData("groups", "id", "is_default = 1 AND active = 1");
     if (!empty($defaultGroup) && !empty($addedUsers)) {
      $defaultGroup = new MagesterGroup($defaultGroup[0]['id']);
      $defaultGroup -> addUsers($addedUsers);
     }
     break;
   }
  } catch (Exception $e) {
   $this -> log["failure"][] = $e -> getMessage().' ('.$e -> getCode().')';// ." ". str_replace("\n", "<BR>", $e->getTraceAsString());
  }
 }
 /*
	 * Use SysClass classes according to the type of import to store the data used
	 * @param line: the line of the imported file
	 * @param type: the import type
	 * @param type: the data of this line, formatted to be put directly into the SysClass db
	 */
 //TODO: this should be moved to the MagesterImport base class - and be used by all - the $line should probably leave though
 private function importData($line, $type, $data)
 {
  try {
   switch ($type) {
    case "users":
     $newUser = MagesterUser::createUser($data);
     $this -> log["success"][] = _LINE . " $line: " . _IMPORTEDUSER . " " . $newUser -> login;
     break;
    case "users_to_courses":
     $courses_name = trim($data['course_name']);
     $courses_ID = $this -> getCourseByName($courses_name);
     unset($data['course_name']);
     if ($courses_ID) {
//debug();
      foreach ($courses_ID as $course_ID) {
       $data['courses_ID'] = $course_ID;
       $course = new MagesterCourse($course_ID);
       $course -> addUsers($data['users_login'], (isset($data['user_type'])?$data['user_type']:"student"));
       $where = "users_login = '" .$data['users_login']. "' AND courses_ID = " . $data['courses_ID'];
       $data['completed'] ? $data['completed'] = 1 : $data['completed'] = 0;
//pr($data);pr(date("Y/m/d", $data['from_timestamp']) ."-". date("Y/m/d", $data['to_timestamp']));
       MagesterCourse::persistCourseUsers($data, $where, $data['courses_ID'], $data['users_login']);
//exit;
       $this -> log["success"][] = _LINE . " $line: " . _NEWCOURSEASSIGNMENT . " " . $courses_name . " - " . $data['users_login'];
      }
     } elseif ($courses_name != "") {
      $course = MagesterCourse::createCourse(array("name" => $courses_name));
      $this -> log["success"][] = _LINE . " $line: " . _NEWCOURSE . " " . $courses_name;
      $course -> addUsers($data['users_login'], (isset($data['user_type'])?$data['user_type']:"student"));
      $courses_ID = $course -> course['id'];
      $this -> courseNamesToIds[$courses_name] = array($courses_ID);
      $where = "users_login = '" .$data['users_login']. "' AND courses_ID = " . $courses_ID;
      MagesterCourse::persistCourseUsers($data, $where, $courses_ID, $data['users_login']);
      $this -> log["success"][] = _LINE . " $line: " . _NEWCOURSEASSIGNMENT . " " . $courses_name . " - " . $data['users_login'];
     } else {
      $this -> log["failure"][] = _LINE . " $line: " . _COULDNOTFINDCOURSE . " " . $courses_name;
     }
     break;
    case "users_to_groups":
     //debug();
     $groups_ID = $this -> getGroupByName($data['groups.name']);
     $group_name = $data['groups.name'];
     unset($data['groups.name']);
     foreach ($groups_ID as $group_ID) {
      $data['groups_ID'] = $group_ID;
      $group = new MagesterGroup($group_ID);
      $group -> addUsers(array($data['users_login']));
      $this -> log["success"][] = _LINE . " $line: " . _NEWGROUPASSIGNMENT . " " . $group_name . " - " . $data['users_login'];
     }
     break;
     //debug(false);
   }
  } catch (Exception $e) {
   if ($this -> options['replace_existing']) {
    if ($this -> isAlreadyExistsException($e->getCode(), $type)) {
     $this -> updateExistingData($line, $type, $data);
    } else {
     $this -> log["failure"][] = _LINE . " $line: " . $e -> getMessage();// ." ". str_replace("\n", "<BR>", $e->getTraceAsString());
    }
   } else {
    $this -> log["failure"][] = _LINE . " $line: " . $e -> getMessage();// ." ". str_replace("\n", "<BR>", $e->getTraceAsString());
   }
  }
 }
 /*
	 * Check whether the file contains the columns that are necessary for this import type
	 */
 private function checkImportEssentialField($type)
 {
  $mandatoryFields = MagesterImport::getMandatoryFields($type);
  $not_found = false;
  foreach ($mandatoryFields as $dbField => $columnName) {
   if (!isset($this -> mappings[$dbField])) {
    $not_found = true;
    break;
   }
  }
  if ($not_found) {
   $this -> log["failure"]["headerproblem"] = _HEADERDOESNOTINCLUDEESSENTIALCOLUMN . ": " . implode(",", $mandatoryFields);

   return false;
  } else {
   return true;
  }
 }
 /*
	 * Parse line data
	 */
 private function parseDataLine($line)
 {
  $lineContents = $this -> explodeBySeparator($line);
  $data = $this -> getEmptyData();
  foreach ($this -> mappings as $dbAttribute => $fileInfo) {
   if (strpos($dbAttribute, "timestamp") === false && $dbAttribute != "hired_on" && $dbAttribute != "left_on") {
    $data[$dbAttribute] = trim($lineContents[$fileInfo], "\r\n\"");
   } else {
    $data[$dbAttribute] = $this -> createTimestampFromDate(trim($lineContents[$fileInfo], "\r\n\""));
   }
  }
  //pr($data);pr(date("Y/m/d", $data['to_timestamp']));exit;
  return $data;
 }
 /*
	 * Main importing function
	 */
 public function import($type)
 {
  $this -> clear();
  if ($this -> lines == "") {
   $this -> log["failure"]["missingheader"] = _NOHEADERROWISDEFINEDORHEADERROWNOTCOMPATIBLEWITHIMPORTTYPE;
  } else {
   // Pairs of values <Csv column header> => <SysClass DB field>
   $this -> types = MagesterImport::getTypes($type);
   // Pairs of values <SysClass DB field> => <import file column>
   $this -> mappings = $this -> parseHeaderLine(&$headerLine);
   if ($this -> mappings) {
    if ($this -> checkImportEssentialField($type)) {
     if ($type == 'users_to_groups' || $type == 'users') {
      $data = array();
      for ($line = $headerLine+1; $line < $this -> lines; ++$line) {
       $data[] = $this -> parseDataLine($line);
      }
      $this -> importDataMultiple($type, $data);
     } else {
      for ($line = $headerLine+1; $line < $this -> lines; ++$line) {
       $data = $this -> parseDataLine($line);
       $this -> importData($line+1, $type, $data);
      }
     }
    }
   } else {
    $this -> log["failure"]["missingheader"] = _NOHEADERROWISDEFINEDORHEADERROWNOTCOMPATIBLEWITHIMPORTTYPE;
   }
  }

  return $this -> log;
 }
 /*
	 * Set the memory and time limits for an import according to the number of lines to be imported
	 */
 private function setLimits($factor = false)
 {
  if (!$factor) {
   $factor = $this->lines / 500;
  }
  if ($factor < 1) {
   return;
  }
  if ($factor > 20) {
   $factor = 20;
  }
  $maxmemory = 128 * $factor;
  $maxtime = 300 * $factor;
  ini_set("memory_limit",$maxmemory . "M");
        ini_set("max_execution_time", $maxtime);
 }
 public function __construct($filename, $_options)
 {
  $this -> fileContents = file_get_contents($filename);
  $this -> fileContents = explode("\n", trim($this -> fileContents));
  $this -> lines = sizeof($this -> fileContents);
  $this -> setLimits();
  $this -> options = $_options;
 }
}
/**
 * Import Factory class
 *
 * This class is used as a factory for import objects
 * <br/>Example
 * <code>
 * $importer = MagesterImportFactory :: factory('csv', $file, $options);
 * $importer -> import();
 * $importer -> import('users');
 * </code>
 *
 * @package SysClass
 * @version 3.6.1
 */
class MagesterImportFactory
{
    /**
     * Construct import object
     *
     * This function is used to construct an import object which can be
     * of any type: MagesterCsvImport
     *
     * <br/>Example :
     * <code>
     * $file = $filesystem -> uploadFile('upload_file');
     * $user = MagesterImportFactory :: factory('csv', $file, array("keep_duplicates" => 1);            //Use factory function to instantiate user object with login 'jdoe'
     * </code>
     *
     * @param string the type the importer: currently only 'csv' is supported $importerType
     * @param file $filename
     * @param options $various import options
     * @return MagesterImport an object of a class extending MagesterImport
     * @since 3.6.1
     * @access public
     * @static
     */
    public static function factory($importerType, $file, $options = false)
    {
     if (!($file instanceof MagesterFile)) {
      $file = new MagesterFile($file);
     }
     switch ($importerType) {
      case 'csv' : $factory = new MagesterImportCsv($file['path'], $options); break;
     }

        return $factory;
    }
}
// -----------------------------------------------------------------------------------------------------------------
/**
 * Abstract class for any Export class - serves as an interface for subsequent
 * developed exporters
 *
 * @package SysClass
 * @abstract
 */
abstract class MagesterExport
{
 /**
	 * Various options like duplicates handling are stored in the options array
	 *
	 * @since 3.6.1
	 * @var array
	 * @access protected
	 */
 protected $options;
 /**
	 * The lines that should finally be exported are written in this array
	 *
	 * @since 3.6.1
	 * @var array
	 * @access protected
	 */
 protected $lines = array();
    /**
     * Export the data from the file following the designated options
     *
     * <br/>Example:
     * <code>
     * $exporter -> export(); //returns something like /var/www/magester/upload/admin/
     * $logMessages = $exporter -> getLogMessages();
     * </code>
     *
     * @return void
     * @since 3.6.1
     * @access public
     */
 public abstract function export($type);
 public static function getExportTypes()
 {
  $datatypes = array("users" => _USERS,
         "users_to_courses" => _USERSTOCOURSES);

  return $datatypes;
 }
 /*
	 * Create the mappings between csv columns and db attributes
	 */
 public static function getTypes($type)
 {
  switch ($type) {
   case "users":
    $users_info = array("users_login" => "login",
           "password" => "password",
           "users_email" => "email",
           "language" => "languages_NAME",
           "users_name" => "name",
           "users_surname" => "surname",
           "active" => "active",
           "user_type" => "user_type",
           "registration_date" => "timestamp");

    return $users_info;
   case "users_to_courses":
    return array("users_login" => "users_login",
         "courses_name" => "courses.name",
         "course_start_date" => "users_to_courses.from_timestamp",
         "course_user_type" => "users_to_courses.user_type",
         "course_completed" => "users_to_courses.completed",
         "course_comments" => "users_to_courses.comments",
         "course_score" => "users_to_courses.score",
         "course_active" => "users_to_courses.active",
         "course_end_date" => "users_to_courses.to_timestamp");
   case "users_to_groups":
    return array("users_login" => "users_login",
        "group_name" => "groups.name");
  }
 }
 public function __construct($_options)
 {
  $this -> options = $_options;
  if ($this -> options['date_format'] == "MM/DD/YYYY") {
   $this -> options['date_new_format'] = "m/d/Y";
  } elseif ($this -> options['date_format'] == "YYYY/MM/DD") {
   $this -> options['date_new_format'] = "Y/m/d";
  } else {
   $this -> options['date_new_format'] = "d/m/Y";
  }
 }
 private $courseNamesToIds = false;
 protected function getCourseByName($courses_name)
 {
  if (!$courseNamesToIds) {
   $courses = MagesterCourse::getCourses();
   foreach ($courses as $course) {
    if (!isset($courseNamesToIds[$course['name']])) {
     $courseNamesToIds[$course['name']] = array($course['id']);
    } else {
     $courseNamesToIds[$course['name']][] = $course['id'];
    }
   }
  }

  return $courseNamesToIds[$courses_name];
 }
 private $groupNamesToIds = false;
 protected function getGroupByName($group_name)
 {
  if (!$groupNamesToIds) {
   $groups = MagesterGroup::getGroups();
   foreach ($groups as $group) {
    if (!isset($groupNamesToIds[$group['name']])) {
     $groupNamesToIds[$group['name']] = array($group['id']);
    } else {
     $groupNamesToIds[$group['name']][] = $group['id'];
    }
   }
  }

  return $groupNamesToIds[$group_name];
 }
 /*
	 * Convert dates of the form dd/mm/yy to timestamps
	 */
    protected function createDatesFromTimestamp($timestamp)
    {
        // date of event if existing, else current time
        if ($timestamp != "" && $timestamp != 0) {
   return date($this -> options['date_new_format'], $timestamp);
        } else {
         return "";
        }
    }
}
/****************************************************
 * Class used to export data from csv files
 *
 */
class MagesterExportCsv extends MagesterExport
{
 /*
	 * The separator between the file's fields
	 */
 private $separator = false;
 /*
	 * Array containing metadata about the exported data type (db attribute names, db tables, export-file accepted column names)
	 */
 private $types = false;
 /*
	 * Find the header line - the first non zero line of the csv that contains at least one of the export $type's column headers
	 * @param: the line of the header
	 */
 private function setHeaderLine($type)
 {
  $this -> types = MagesterExport::getTypes($type);
  if ($type == "users") {
   unset($this -> types['password']);
  }
  $this -> lines[] = implode($this -> separator, array_keys($this -> types));
 }
 private function clear()
 {
  $this -> lines = array();
 }
 /*
	 * Use SysClass classes according to the type of export to store the data used
	 * @param line: the line of the exported file
	 * @param type: the export type
	 * @param type: the data of this line, formatted to be put directly into the SysClass db
	 */
 private function exportData($data)
 {
  foreach ($data as $info) {
         unset($info['password']);
         foreach ($info as $field => $value) {
          if (!(strpos($field, "timestamp") === false) || $field=="hired_on" || $field=="left_on") {
           $info[$field] = $this -> createDatesFromTimestamp($value);
          }
         }
         $this -> lines[] = implode($this -> separator, $info);
     }
 }
 /*
	 * Get data to be exported
	 */
 private function getData($type)
 {
  switch ($type) {
   case "users":
     return eF_getTableData($type, implode(",", $this -> types), "archive = 0");
   case "users_to_courses":
     return eF_getTableData("users_to_courses JOIN courses ON courses.id = users_to_courses.courses_ID", implode(",", $this -> types), "");
   case "users_to_groups":
     return eF_getTableData("users_to_groups JOIN groups ON groups.id = users_to_groups.groups_ID", implode(",", $this -> types), "");
   return eF_getTableData($type, implode(",", $this -> types), "archive = 0");
  }
 }
 /*
	 * Write the exported file
	 */
 private function writeFile($type)
 {
     if (!is_dir($GLOBALS['currentUser'] -> user['directory']."/temp")) {
         mkdir($GLOBALS['currentUser'] -> user['directory']."/temp", 0755);
     }
     file_put_contents($GLOBALS['currentUser'] -> user['directory']."/temp/magester_".$type.".csv", implode("\n", $this -> lines));
     $file = new MagesterFile($GLOBALS['currentUser'] -> user['directory']."/temp/magester_".$type.".csv");

     return $file;
 }
 /*
	 * Main exporting function
	 */
 public function export($type)
 {
  $this -> clear();
  $this -> setHeaderLine($type);
  $data = $this -> getData($type);
  $this -> exportData($data);

  return $this -> writeFile($type);
 }
 public function __construct($_options)
 {
  $this -> options = $_options;
  if ($this -> options['date_format'] == "MM/DD/YYYY") {
   $this -> options['date_new_format'] = "m/d/Y";
  } elseif ($this -> options['date_format'] == "YYYY/MM/DD") {
   $this -> options['date_new_format'] = "Y/m/d";
  } else {
   $this -> options['date_new_format'] = "d/m/Y";
  }
  if (isset($this -> options['separator'])) {
   $this -> separator = $this -> options['separator'];
  } else {
   $this -> separator = ",";
  }
 }
}
/**
 * Export Factory class
 *
 * This class is used as a factory for export objects
 * <br/>Example
 * <code>
 * $exporter = MagesterExportFactory :: factory('csv', $file, $options);
 * $exporter -> export();
 * $exporter -> export('users');
 * </code>
 *
 * @package SysClass
 * @version 3.6.1
 */
class MagesterExportFactory
{
    /**
     * Construct export object
     *
     * This function is used to construct an export object which can be
     * of any type: MagesterCsvExport
     *
     * <br/>Example :
     * <code>
     * $file = $filesystem -> uploadFile('upload_file');
     * $user = MagesterExportFactory :: factory('csv', $file, array("keep_duplicates" => 1);            //Use factory function to instantiate user object with login 'jdoe'
     * </code>
     *
     * @param string the type the exporter: currently only 'csv' is supported $exporterType
     * @param file $filename
     * @param options $various export options
     * @return MagesterExport an object of a class extending MagesterExport
     * @since 3.6.1
     * @access public
     * @static
     */
    public static function factory($exporterType, $options = false)
    {
     switch ($exporterType) {
      case 'csv' : $factory = new MagesterExportCsv($options); break;
     }

        return $factory;
    }
}
