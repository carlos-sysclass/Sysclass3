<?php
/**
* news Class file
*
* @package SysClass
* @version 3.6
*/
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
	exit;
}
/**
*
* @author Periklis Venakis
*
*/
class news extends MagesterEntity
{
	/**
	* The news properties
	*
	* @since 3.6.0
	* @var array
	* @access public
	*/
	public $news = array();
	/**
	* Create news
	*
	* This function is used to create news
	* <br>Example:
	* <code>
	* $fields = array("title"       => $form -> exportValue('title'),
	*       "data"        => $form -> exportValue('data'),
	*       "timestamp"   => $from_timestamp,
	*		 "expire"      => $to_timestamp,
	*       "lessons_ID"  => isset($_SESSION['s_lessons_ID']) && $_SESSION['s_lessons_ID'] ? $_SESSION['s_lessons_ID'] : 0,
	*       "users_LOGIN" => $_SESSION['s_login']);
	*
	* $news = news :: create($fields, 0));
	*
	* </code>
	*
	* @param $fields An array of data
	* @param $sendEmail Whether to send the announcement as an email as well
	* @return news The new object
	* @since 3.6.0
	* @access public
	* @static
	*/
	public static function create($fields = array(), $sendEmail = false)
	{
		error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
		$fields = array('title' 		=> $fields['title'],
                        'data' 			=> $fields['data'],
                        'timestamp' 	=> $fields['timestamp'] ? $fields['timestamp'] : time(),
                        'expire' 		=> $fields['expire'] ? $fields['expire'] : null,
			            'lessons_ID' 	=> $fields['lessons_ID'],
			        	'classe_id' 	=> $fields['classe_id'],
                        'users_LOGIN' 	=> $fields['users_LOGIN']);
		$newId 	= sC_insertTableData("news", $fields);
		$result = sC_getTableData("news", "*", "id=".$newId); //We perform an extra step/query for retrieving data, sinve this way we make sure that the array fields will be in correct order (forst id, then name, etc)
		$news = new news($result[0]['id']);
		if ($news -> news['lessons_ID']) {
			//MagesterEvent::triggerEvent(array("type" => MagesterEvent::NEW_LESSON_ANNOUNCEMENT, "users_LOGIN" => $fields['users_LOGIN'], "users_name" => $currentUser -> user['name'], "users_surname" => $currentUser -> user['surname'], "lessons_ID" => $fields['lessons_ID'], "entity_ID" => $id, "entity_name" => $news_content['title']), isset($_POST['email']));
			MagesterEvent::triggerEvent(array("type" => MagesterEvent::NEW_LESSON_ANNOUNCEMENT, "users_LOGIN" => $GLOBALS['currentUser'] -> user['login'], "users_name" => $GLOBALS['currentUser'] -> user['name'], "users_surname" => $GLOBALS['currentUser'] -> user['surname'], "lessons_ID" => $GLOBALS['currentLesson'] -> lesson['id'], "lessons_name" => $GLOBALS['currentLesson'] -> lesson['name'], "entity_name" => $fields['title'], "entity_ID" => $newId), $sendEmail);
		} else {
			MagesterEvent::triggerEvent(array("type" => MagesterEvent::NEW_SYSTEM_ANNOUNCEMENT, "users_LOGIN" => $GLOBALS['currentUser'] -> user['login'], "users_name" => $GLOBALS['currentUser'] -> user['name'], "users_surname" => $GLOBALS['currentUser'] -> user['surname'], "entity_name" => $fields['title'], "entity_ID" => $newId), $sendEmail);
		}
		MagesterSearch :: insertText($news -> news['title'], $news -> news['id'], "news", "title");
		MagesterSearch :: insertText($news -> news['data'], $news -> news['id'], "news", "data");
		return $news;
	}
	/**
	* Persist news properties
	*
	* This function can be used to persist with the database
	* any changes made to the current news object.
	* <br/>Example:
	* <code>
	* $news -> news['title'] = 'new Title';              //Change the news title
	* $news -> persist();                                   //Make the change permanent
	* </code>
	*
	* @since 3.6.0
	* @access public
	*/
	public function persist()
	{
		parent :: persist();
		MagesterSearch :: removeText('news', $this -> news['id'], 'data');
		MagesterSearch :: insertText($this -> news['data'], $this -> news['id'], "news", "data");
		MagesterSearch :: removeText('news', $this -> news['id'], 'title');
		MagesterSearch :: insertText($this -> news['title'], $this -> news['id'], "news", "title");
	}

	/**
	* Delete the news
	*
	* This function is used to delete the current news.
	* All related information is lost, as well as files associated
	* with the news.
	* <br/>Example:
	* <code>
	* $news = new news(12);                //Instantiate news with id 12
	* $news -> delete();                            //Delete news and all associated information
	* </code>
	*
	* @since 3.6.0
	* @access public
	*/
	public function delete()
	{
		parent :: delete();
		MagesterSearch :: removeText('news', $this -> news['id'], 'title');
		MagesterSearch :: removeText('news', $this -> news['id'], 'data');
	}

	/**
	* (non-PHPdoc)
	* @see libraries/MagesterEntity#getForm($form)
	*/
	public function getForm($form)
	{
		if ($_SESSION['s_type'] == "professor") {
			$lessonsID = sC_getTableDataFlat("users_to_lessons", "lessons_id", "active=1 and archive = 0 and users_LOGIN LIKE '".$_SESSION['s_login']."'");
			foreach ($lessonsID as $_lessonID) {
				$lessonsID = $_lessonID;
			}
			$lessons = sC_getTableDataFlat("lessons", "id, name", "active=1 and id in (".implode(",", $lessonsID).")");
			if (sizeof($lessons) > 0) {
				//Get every lesson's name
				$lessons = array_combine($lessons['id'], $lessons['name']);
			}

			$courseID = sC_getTableDataFlat("users_to_courses", "courses_id", "active=1 and archive = 0  and users_LOGIN LIKE '".$_SESSION['s_login']."'");
			foreach ($courseID as $_courseID) {
				$courseID = $_courseID;
			}

			$allClass = sC_getTableDataFlat("classes", "id, name", "active=1 and courses_id = ".$_SESSION['s_courses_ID']);
			if (sizeof($allClass) > 0) {
				//Get every lesson's name
				$allClass = array_combine($allClass['id'], $allClass['name']);
			}
		}
		if ($_SESSION['s_type'] == "administrator") {
			$lessons = sC_getTableDataFlat("lessons", "id, name", "active=1");
			if (sizeof($lessons) > 0) {
				//Get every lesson's name
				$lessons = array_combine($lessons['id'], $lessons['name']);
			}
			$allClass = sC_getTableDataFlat("classes", "id, name", "active=1");
			if (sizeof($allClass) > 0) {
				//Get every lesson's name
				$allClass = array_combine($allClass['id'], $allClass['name']);
			}
		}
		$sidenote = '<a href = "javascript:void(0)" onclick = "Element.extend(this).up().select(\'select\').each(function (s) {s.options.selectedIndex=0;})">'._CLEAR.'</a>';
		$form -> addElement('text', 'title', _ANNOUNCEMENTTITLE, 'class = "inputText"');
		if ($_SESSION['s_type'] == "professor" || $_SESSION['s_type'] == "administrator") {
			//$form -> addElement('select', 'courses', _LESSON, $courseID);
			$form -> addElement('select', 'lessons', _LESSON, array(0 => "Todas as disciplinas" ) + $lessons);
			$form -> addElement('select', 'classes', _COURSECLASS,array(0 => _COURSEALLCLASS ) + $allClass);
		}
		$form -> addRule('title', _THEFIELD.' "'._ANNOUNCEMENTTITLE.'" '._ISMANDATORY, 'required', null, 'client');
		$form -> addElement('static', 'toggle_editor_code', 'toggleeditor_link');
		$form -> addElement('textarea', 'data', _ANNOUNCEMENTBODY, 'class = "simpleEditor inputTextarea" style = "width:98%;height:7em;"');
		$form -> addElement($this -> createDateElement($form, 'timestamp', _VISIBLEFROM));
		$form -> addElement('static', 'sidenote', $sidenote);
		$form -> addElement($this -> createDateElement($form, 'expire', _EXPIRESAT, array('addEmptyOption' => true)));
		$form -> addElement('checkbox', 'calendar', _CREATECALENDAREVENT, null, 'class = "inputCheckBox"');
		$form -> addElement('checkbox', 'email', _SENDASEMAILALSO, null, 'class = "inputCheckBox"');
		$form -> addElement('submit', 'submit', _ANNOUNCEMENTADD, 'class = "flatButton"');
		$form -> setDefaults(array('title' => $this -> news['title'],
              'data' => $this -> news['data'],
              'lessons'	=> $this -> news['lessons_ID'],
              'timestamp' => $this -> news['timestamp'] ? $this -> news['timestamp'] : time(),
              'expire' => $this -> news['timestamp'] ? $this -> news['expire'] : time()+(86400*30)));
		return $form;
	}

	/**
	* (non-PHPdoc)
	* @see libraries/MagesterEntity#handleForm($form)
	*/
	public function handleForm($form)
	{
		$values = $form -> exportValues();
		$timestamp = mktime($values['timestamp']['H'], $values['timestamp']['i'], 0, $values['timestamp']['M'], $values['timestamp']['d'], $values['timestamp']['Y']);
		$expire = mktime($values['expire']['H'], $values['expire']['i'], 0, $values['expire']['M'], $values['expire']['d'], $values['expire']['Y']);
		if (isset($_GET['edit'])) {
			$this -> news["title"] = $values['title'];
			$this -> news["data"] = $values['data'];
			$this -> news["timestamp"] = $timestamp;
			$this -> news["expire"] = $expire;
			$this -> persist();
		} else {
			$lesson_ID = isset($_SESSION['s_lessons_ID']) && $values['classes']>0 ? $_SESSION['s_lessons_ID'] : 0;
			$fields = array("title" => $values['title'],
                            "data" => $values['data'],
                            "timestamp" => $timestamp,
					        "classe_id" => $values['classes'],
					        "expire" => $expire,
					        //"lessons_ID" => $lesson_ID,
					        "lessons_ID" => $values['lessons'],
					        "users_LOGIN" => $_SESSION['s_login']);
			$news = self :: create($fields, isset($_POST['email']));
			$this -> news = $news;
		}
		if ($values['calendar']) {
			$calendarFields = array('data' => $fields['data'],
                         'timestamp' => $timestamp,
                         'active' => 1,
             'private' => 0,
             'type' => $fields['lessons_ID'] ? 'lesson' : '',
             'foreign_ID' => $fields['lessons_ID'] ? $fields['lessons_ID'] : 0,
                         'users_LOGIN' => $_SESSION['s_login']);
			calendar :: create($calendarFields);
		}
	}

	/**
	* Get announcements
	*
	* This function gets the lesson announcements (news). It returns an array holding the announcement title, id
	* and timestamp.
	* <br/>Example:
	* <code>
	* $news = news ::: getNews();
	* print_r($news);
	* //Returns:
	*Array
	*(
	*    [0] => Array
	*        (
	*            [title] => announcement 1
	*            [id] => 3
	*            [timestamp] => 1125751731
	*            [users_LOGIN] => admin
	*        )
	*
	*    [1] => Array
	*        (
	*            [title] => Important announcem...
	*            [id] => 5
	*            [timestamp] => 1125751012
	*            [users_LOGIN] => peris
	*        )
	*)
	* </code>
	*
	* @param mixed $lessonId The lesson id or an array of ids
	* @param boolean $check_expire Whether to return only announcements that are valid for the current date
	* @return array The news array
	* @since 3.6.0
	* @static
	* @access public
	*/
	public static function getNews($lessonId, $checkExpire = false)
	{
		if ($checkExpire) {
			$expireString = " and (n.expire=0 OR n.expire >=".time().") AND n.timestamp<=".time();
			//$expireString = " AND n.timestamp<=".time();   // check why it was here hot talking into account expire. makriria 15/3/2010
		}
		if (is_array($lessonId) && !empty($lessonId)) {
			foreach ($lessonId as $key => $value) {
				if (!sC_checkParameter($value, 'id')) {
					unset($lessonId[$key]);
				}
			}
			if (!empty($lessonId)) {
				//$result = sC_getTableData("news n, users u", "n.*, u.surname, u.name", "n.users_LOGIN = u.login".$expireString." and n.lessons_ID in (".implode(",", $lessonId).")", "n.timestamp desc, n.id desc");
				$result = sC_getTableData("news n, users u", "n.*, u.surname, u.name", "n.users_LOGIN = u.login".$expireString." and n.lessons_ID in (".implode(",", $lessonId).")", "n.id desc");
				$news = array();
				foreach ($result as $value) {
					$interval = time() - $value['timestamp'];
					$value['time_since'] = sC_convertIntervalToTime(abs($interval), true).' '.($interval > 0 ? _AGO : _REMAININGPLURAL);
					$news[$value['id']] = $value;
					$news[$value['id']]['data_strip'] = strip_tags($value['data']);
				}
			}
			return $news;
		}
		//We don't have an "else" statement here, because in case the check in the above if removed all elements of lessonId (they were not ids), this part of code will be executed and the function won't fail
		if (!sC_checkParameter($lessonId, 'id')) {
			$lessonId = 0;
		}
		$result = sC_getTableData("news n, users u", "n.*, u.surname, u.name", "n.users_LOGIN = u.login".$expireString." and n.lessons_ID=$lessonId", "n.timestamp desc, n.id desc");
		$news = array();
		foreach ($result as $value) {
			$interval = time() - $value['timestamp'];
			$value['time_since'] = sC_convertIntervalToTime(abs($interval), true).' '.($interval > 0 ? _AGO : _REMAININGPLURAL);
			$news[$value['id']] = $value;
			$news[$value['id']]['data_strip'] = strip_tags($value['data']);
		}
		return $news;
	}
}
