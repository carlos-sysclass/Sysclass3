<?php
/**

* sC_PersonalMessage Class file

*

* @package SysClass

* @version 1.0

*/
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}
/**

* sC_PersonalMessage class

*

* This class is used to send personal messages to system users and optionally email them

* @author Venakis Periklis <pvenakis@magester.com.br>

* @package SysClass

* @version 1.0

*/
class sC_PersonalMessage
{
    /**

     * The personal message subject

     *

     * @since 1.0

     * @var string

     * @access private

     */
    private $subject = '';
    /**

     * The personal message body

     *

     * @since 1.0

     * @var string

     * @access private

     */
    private $body = '';
    /**

     * The personal message sender

     *

     * @since 1.0

     * @var string

     * @access private

     */
    private $sender = '';
    /* Flag denoting whether recipients will be hidden or not */
    private $bcc = false;
    /**

     * The personal message recipients

     *

     * @since 1.0

     * @var array

     * @access private

     */
    private $recipients = array();
    /**

     * The personal message attachments

     *

     * @since 1.0

     * @var array

     * @access private

     */
    private $attachments = array();
    /**

     * The users data, such as email, login, message folder ids, etc

     *

     * @since 1.0

     * @var array

     * @access private

     */
    private $userData = array();
    /**

     * The forum configuration variables

     *

     * @since 1.0

     * @var array

     * @access private

     */
    private $config = array();
    /**

     * The class error message

     *

     * @since 1.0

     * @var string

     * @access public

     */
    public $errorMessage = '';
    /**

    * Class constructor

    *

    * This function is used to instantiate class variables to the message attributes:

    * Sender, recipients, subject and body. The $recipients variable may either be a

    * user login, or an array of logins.

    * If either the sender or any of the recipients are not valid system users, the constructor

    * fails.

    * <br/>Example:

    * <code>

    * $pm = new sC_PersonalMessage("professor", array("professor", "student", "admin"), 'Test subject', 'Test personal message body');

    * </code>

    *

    * @param string $sender The personal message sender

    * @param mixed $recipients An array of recipients

    * @param string $subject The personal message subject

    * @param string $body The personal message body

    * @since 1.0

    * @access public

    */
    public function __construct($sender, $recipients, $subject = '', $body = '', $bcc = true)
    {
        $this -> getUsersData(); //Retrive data for the system users, such as messages folders, emails etc
        $this -> getConfiguration();
        if ($this -> checkRecipient($sender)) { //Check if the sender is valid
            $this -> sender = $sender;
        } else {
            return false;
        }
        if (!is_array($recipients) && $this -> checkRecipient($recipients)) { //If it is a single -valid- login, convert it to array
            $this -> recipients = array($recipients);
        } elseif (is_array($recipients)) {
            foreach ($recipients as $recipient) { //Check each recipient if it is valid
                if (!$this -> checkRecipient($recipient)) {
                    return false;
                }
            }
            $this -> recipients = $recipients;
        } else { //A single login was given, but it wasn't valid

            return false;
        }
        $this -> subject = $subject ? $subject : _NOSUBJECT; //If a subject is not specified, give it _NOSUBJECT subject
        $this -> body = $body;
        $this -> bcc = $bcc;
    }
    /**

    * Send a personal message

    *

    * This function is used to send the personal message. If $email is specified,

    * the message is also emailed to the recipients

    * <br/>Example:

    * <code>

    * $pm = new sC_PersonalMessage("professor", array("professor", "student", "admin"), 'Test subject', 'Test personal message body');

    * $pm -> send();

    * </code>

    *

    * @param boolean If true, the personal message will be send as an email as well

    * @return true on success, false on error

    * @since 1.0

    * @access public

    */
    public function send($email = false)
    {
        if (sizeof($this -> recipients) == 0) {
            $this -> errorMessage = _INVALIDRECIPIENT;

            return false;
        }
        $timestamp = time();
        if ($email) { //Check if the messag should be sent as an email also. This will be sent no matter the user quotas
         $recipientsMail = array();
         foreach ($this -> recipients as $recipient) {
    if ($this -> userData[$recipient]['email'] != "") {
     $recipientsMail[] = $this -> userData[$recipient]['email'];
    } else {
     $this -> errorMessage .= $this -> userData[$recipient]['login'].' '._HASNOTANEMAILADDRESS.'<br/>';
    }
   }
   $recipientsList = implode(",", $recipientsMail);
   /*
   $this -> body =
   	_THISISAPMFROMSITE." <a href=".G_SERVERNAME.">".$GLOBALS['configuration']['site_name']."</a><br />".

   	$this -> body;
   */
   $emailBody = str_replace('##MAGESTERINNERLINK##', 'student' ,$this -> body);
   if (($result = sC_mail($this -> userData[$this -> sender]['email'], $recipientsList, $this -> subject, $emailBody, $this -> attachments, false, $this -> bcc)) !== true) {

//   				var_dump($this -> userData[$this -> sender]['email'], $recipientsList, $this -> subject, $emailBody, $this -> attachments, false, $this -> bcc);
                   $this -> errorMessage .= _THEMESSAGEWASNOTSENTASEMAIL.'<br/>';
            }
  }
        foreach ($this -> recipients as $recipient) {
            if ($this -> checkUserQuota($recipient)) {
                $fields_insert = array("users_LOGIN" => $recipient, //This message belongs to $recipient
                                        "recipient" => implode(", ", $this -> recipients), //It was sent to $recipients
                                        "sender" => $this -> sender, //It was sent by $sender
                                        "timestamp" => $timestamp,
                                        "title" => $this -> subject,
                                        "body" => $this -> body,
                                        "bcc" => $this -> bcc ? 1 : 0,
                                        "f_folders_ID"=> $this -> userData[$recipient]['folders']['Incoming'], //Deliver it to the incoming folder
                                        "viewed" => 0); //It is not viewed yet
                if ($this->attachments[0]) {
                    $attachment = new MagesterFile($this -> sender_attachment_fileId);
                    $recipient_dir = G_UPLOADPATH.$recipient.'/message_attachments/Incoming/'.$timestamp.'/';
                    mkdir($recipient_dir,0755);
                    $newFile = $attachment -> copy($recipient_dir, false, true);
                    $fields_insert["attachments"] = $newFile['id'];
                }
                $id = sC_insertTableData("f_personal_messages", $fields_insert);
                MagesterSearch :: insertText($fields_insert['body'], $id, "f_personal_messages", "data");
                MagesterSearch :: insertText($fields_insert['title'], $id, "f_personal_messages", "title");
            } else {
                $this -> errorMessage .= _YOURMESSAGETO.' '.$recipient.' '._COULDNOTBEDELIVERED.' '._BECAUSEHISMESSAGEBOXISFULL.'<br/>';
            }
        }
        if ($this -> checkUserQuota($this -> sender)) {
            $fields_insert = array("users_LOGIN" => $this -> sender, //Create the message for the sender, and put it in his Sent messages folder
                                    "recipient" => implode(", ", $this -> recipients),
                                    "sender" => $this -> sender,
                                    "timestamp" => $timestamp,
                                    "title" => $this -> subject,
                                    "body" => $this -> body,
                  "bcc" => $this -> bcc ? 1 : 0,
                                    "f_folders_ID"=> $this -> userData[$this -> sender]['folders']['Sent'],
                                    "viewed" => 0);
            if ($this->attachments[0]) {
                $attachment = new MagesterFile($this -> sender_attachment_fileId);
                $fields_insert["attachments"] = $this -> sender_attachment_fileId;
            }
            $id = sC_insertTableData("f_personal_messages", $fields_insert);
            MagesterSearch :: insertText($fields_insert['body'], $id, "f_personal_messages", "data");
            MagesterSearch :: insertText($fields_insert['title'], $id, "f_personal_messages", "title");
        } else {
            $this -> errorMessage .= _COULDNOTBECOPIEDTOYOURSENTBOX.' '._BECAUSEYOURMESSAGEBOXISFULL.'<br />';
        }
        if ($this -> errorMessage) {
            return false;
        } else {
            return true;
        }
    }
    /**

    *

    */
    public function setAttachment($filename)
    {
        $this -> attachments[] = $filename;
    }
    /**

    * Check if the Recipient is valid

    *

    * This function is used to check the validity of a personal message

    * recipient (or sender). it first checks if the login is well formed,

    * and then whether the user actually exists.

    *

    * @param string $recipient The login to check validity for

    * @return boolean true if it is a valid user, false otherwise

    * @since 1.0

    * @access private

    */
    private function checkRecipient($recipient)
    {
        if (!sC_checkParameter($recipient, 'login')) { //Is it a well-formed login

            return false;
        } else {
            if (!in_array($recipient, array_keys($this -> userData))) {
                return false;
            } else {
                return true;
            }
        }
    }
    /**

    * Get users data

    *

    * This function retrieves and builds an array with user information that is used

    * throughout the class. This information is the users logins, emails and the

    * message folders ids

    *

    * @since 1.0

    * @access private

    */
    private function getUsersData()
    {
        $result_folders = sC_getTableData("f_folders", "*"); //Get all user message folders
        $result_users = sC_getTableData("users", "login, email, user_type"); //Get all user user information
        $result_messages = sC_getTableDataFlat("f_personal_messages", "users_LOGIN");
        $messages = array_count_values($result_messages['users_LOGIN']); //Count the number of messages for each user. Nice alternative to looping queries
        foreach ($result_folders as $folder) {
            $folders[$folder['users_LOGIN']][$folder['name']] = $folder['id'];
        }
        foreach ($result_users as $user) {
      if (!isset($folders[$user['login']]['Incoming'])) {
       $id = sC_insertTableData("f_folders", array('name' => 'Incoming', 'users_LOGIN' => $user['login']));
       $folders[$user['login']]['Incoming'] = $id;
      }
      if (!isset($folders[$user['login']]['Sent'])) {
       $id = sC_insertTableData("f_folders", array('name' => 'Sent', 'users_LOGIN' => $user['login']));
       $folders[$user['login']]['Sent'] = $id;
      }
      if (!isset($folders[$user['login']]['Drafts'])) {
       $id = sC_insertTableData("f_folders", array('name' => 'Drafts', 'users_LOGIN' => $user['login']));
       $folders[$user['login']]['Drafts'] = $id;
      }
            $this -> userData[$user['login']] = $user;
            $this -> userData[$user['login']]['folders'] = $folders[$user['login']];
            $this -> userData[$user['login']]['messages'] = isset($messages[$user['login']]) ? $messages[$user['login']] : 0;
        }
    }
    /**

    * Get configuration values

    *

    * This function is used to read forum configuration values

    * and assign them to the $config array, in name/value pairs

    *

    * @since 1.0

    * @access private

    */
    private function getConfiguration()
    {
        $result = sC_getTableDataFlat("f_configuration", "*");
        sizeof($result) > 0 ? $this -> config = array_combine($result['name'], $result['value']) : $this -> config = array();
    }
    /**

    * Check a user's message quota

    *

    * This function returns true if a user doesn't exceed his messages

    * quotas (which apply only to students)

    *

    * @param string $login The user to check quotas for

    * @param boolean $check_attachment Whether to check for attachment quota as well

    * @return boolean True if quotas are not exceeded

    * @since 1.0

    * @access private

    */
    private function checkUserQuota($login, $check_attachment = false)
    {
        if ($check_attachment) {
            $total_files = 0;//@todo: was: sC_diveIntoDir(G_UPLOADPATH.$login.'/message_attachments/');
            if ($this -> config['pm_attach_quota'] && $total_files[2] > $this -> config['pm_attach_quota'] * 1024) {
                return false;
            }
        }
        if ($this -> userData[$login]['user_type'] != 'student') {
            return true;
        } elseif ($this -> config['pm_quota'] && $this -> userData[$login]['messages'] > $this -> config['pm_quota']) {
            return false;
        } else {
            return true;
        }
    }
    /**

     * Get user message folders

     *

     * This function retrieves the folders of the specified user. The folders are returned so that

     * "Incoming" is the first entry, "Sent" the 2nd, "Drafts" the 3rd and any other folders follow.

     * The array is on a id/name basis.

     * <br/>Example:

     * <code>

     * $userFolders = sC_PersonalMessage :: getUserFolders('jdoe');

     * // Returns something like: array(2 => 'Incoming', 3 => 'Sent', 4 => 'Drafts', 8 => 'My folder');

     * </code>

     * The function creates any missing directories in the user space as well

     *

     * @param mixed $user The user to retrieve folders for

     * @return array The array of folders

     * @since 3.6.0

     * @access public

     */
    public static function getUserFolders($user)
    {
        if ($user instanceof MagesterUser) {
            $user = $user -> user['login'];
        } elseif (!sC_checkParameter($user, 'login')) {
            throw new MagesterUserException(_INVALIDLOGIN.": '".$user."'", MagesterUserException :: INVALID_LOGIN);
        }
     if (!is_dir(G_UPLOADPATH.$user.'/message_attachments/')) { //Check if the messages folder for this user exists on the disk
         mkdir(G_UPLOADPATH.$user.'/message_attachments/', 0755);
     }
     $result = sC_getTableDataFlat("f_folders", "name", "users_LOGIN='$user'");
     in_array('Incoming', $result['name']) OR sC_insertTableData("f_folders", array('name' => 'Incoming', 'users_LOGIN' => $user));
     in_array('Sent', $result['name']) OR sC_insertTableData("f_folders", array('name' => 'Sent', 'users_LOGIN' => $user));
     in_array('Drafts', $result['name']) OR sC_insertTableData("f_folders", array('name' => 'Drafts', 'users_LOGIN' => $user));
     $folders = $incoming = $sent = $drafts = array();
     $result = sC_getTableData("f_folders f left outer join f_personal_messages pm on pm.f_folders_ID=f.id", "f.*, count(pm.id) as messages_num", "f.users_LOGIN='".$user."'", "", "f.id");
     foreach ($result as $value) {
         $value['pathname'] = $value['name'];
         if (!is_dir(G_UPLOADPATH.$user.'/message_attachments/'.$value['name'])) { //Check whether the folders exist physically on the disk
             mkdir(G_UPLOADPATH.$user.'/message_attachments/'.$value['name'], 0755);
         }
         if ($value['name'] == 'Incoming') {
             $value['name'] = _INCOMING;
             $incoming = array($value['id'] => $value);
         } elseif ($value['name'] == 'Sent') {
             $value['name'] = _SENT;
             $sent = array($value['id'] => $value);
         } elseif ($value['name'] == 'Drafts') {
             $value['name'] = _DRAFTS;
             $drafts = array($value['id'] => $value);
         } else {
             $folders[$value['id']] = $value;
         }
     }
     //Move default folders on top of the list
     $folders = $incoming + $sent + $drafts + $folders;
     //Get files statistics
     foreach ($folders as $key => $folder) {
   foreach (new DirectoryIterator(G_UPLOADPATH.$user.'/message_attachments/'.$folder['pathname']) as $file) {
       $folders[$key]['size'] = 0;
       if ($file -> isFile()) {
           $folders[$key]['filesize'] += $file -> getSize();
       }
   }
   $folders[$key]['filesize'] = round($folders[$key]['filesize']/1024);
     }

     return $folders;
    }
 /**

	* Delete a personal message

	*

	* This function is used to delete a message, including any attachments it may have

	*

	* @param int $msg_id The message id

	* @return bool True if the deletion was succesful

	* @version 0.1

	* @deprecated

	*/
	public static function sC_deletePersonalMessage($msg_id)
	{
		if (sC_checkParameter($msg_id, 'id')) {
			$res = sC_getTableData("f_personal_messages", "users_LOGIN, attachments, f_folders_ID", "id=".$msg_id);
			if ($_SESSION['s_login'] == $res[0]['users_LOGIN'] || $_SESSION['s_type'] == 'administrator') {
				sC_deleteTableData("f_personal_messages", "id=".$msg_id);
				if ($res[0]['attachments'] != '') {
					$attached_file = new MagesterFile($res[0]['attachments']);
                 	$attached_file -> delete();
             	}
				return true;
         	} else {
            	$message = 'You cannot delete this message';
				return $message;
         	}
		} else {
        	$message = _INVALIDID;
			return $message;
		}
	}
}
/**

 *

 * @author user

 *

 */
class f_folders extends MagesterEntity
{
    /**

     * (non-PHPdoc)

     * @see libraries/MagesterEntity#delete()

     */
    public function delete()
    {
        $folderMessages = sC_getTableData("f_personal_messages", "id", "f_folders_ID=".$this -> {$this -> entity}['id']);
        foreach ($folderMessages as $message) {
            sC_PersonalMessage :: sC_deletePersonalMessage($message['id']);
        }
        $folderDirectory = new MagesterDirectory(G_UPLOADPATH.$this -> {$this -> entity}['users_LOGIN'].'/message_attachments/'.$this -> {$this -> entity}['name']);
        $folderDirectory -> delete();
        parent :: delete();
    }
    /**

     * (non-PHPdoc)

     * @see libraries/MagesterEntity#getForm($form)

     */
    public function getForm($form)
    {
        $form -> addElement('text', 'name', _FOLDERNAME, 'class = "inputText"');
     $form -> addElement('submit', 'submit', _SUBMIT, 'class = "flatButton"');
     $form -> setDefaults(array('name' => $this -> {$this -> entity}['name']));

     return $form;
    }
    /**

     * (non-PHPdoc)

     * @see libraries/MagesterEntity#handleForm($form)

     */
    public function handleForm($form)
    {
        $values = $form -> exportValues();
        if (!sC_checkParameter($values['name'], 'filename')) {
            throw new MagesterFileException(_ILLEGALFILENAME.': '.$values['name'], MagesterFileException :: ILLEGAL_FILE_NAME);
        }
        $fields = array("name" => $values['name']);
        if (isset($_GET['add'])) {
            self :: create($fields);
        } else {
            $directory = new MagesterDirectory(G_UPLOADPATH.($this -> {$this -> entity}['users_LOGIN']).'/message_attachments/'.$this -> {$this -> entity}['name']);
            $directory -> rename(G_UPLOADPATH.($this -> {$this -> entity}['users_LOGIN']).'/message_attachments/'.$values['name']);
            $this -> {$this -> entity}['name'] = $values['name'];
            $this -> persist();
        }
    }
    /**

     *

     * @param $fields

     * @return unknown_type

     */
    public static function create($fields = array())
    {
        !isset($fields['users_LOGIN']) || !sC_checkParameter($fields['users_LOGIN'], 'login') ? $fields['users_LOGIN'] = $_SESSION['s_login'] : null;
        $directory = G_UPLOADPATH.$fields['users_LOGIN'].'/message_attachments/'.$fields['name'];
        if (!mkdir($directory, 0755)) {
            throw new MagesterFileException(_COULDNOTCREATEDIRECTORY.': '.$directory, MagesterFileException :: GENERAL_ERROR);
        }
        sC_insertTableData("f_folders", $fields);
    }
}
