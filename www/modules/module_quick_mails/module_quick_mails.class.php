<?php
/**
 * @author Andre
 * @copyright 2008
 */
class module_quick_mails extends MagesterExtendedModule {

	const GET_COURSE_ROLES_SEND_LIST = 'get_course_roles_send_list';

	public function getName() {
		return "QUICK_MAILS";
	}

	public function getPermittedRoles() {
		return array("professor", "student");
	}

	/* BLOCK FUNCTIONS */
	public function loadQuickContactListBlock($blockIndex = null) {
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		$this->loadModule('xuser');

		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'polo') {
			return false;
		}
		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'professor') {
		} elseif (in_array($this->modules['xuser']->getExtendedTypeID($currentUser), array('pre_enrollment', 'pre_student', 'student'))) {
			$contactList = eF_getTableData(
				"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)", 
				"qm.*, COUNT(qml.user_id)", 
				sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'contact'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
				"",
				"qm.id HAVING COUNT(qml.user_id) > 0"
			);
			foreach($contactList as &$item) {
				$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";
				$image = explode("/", $item['image']);
				$item['image'] = array(
					'size'	=> reset(explode("x", $image[0])),
					'name'	=> $image[1]
				);
			}
		} else {
			$contactList = eF_getTableData(
				"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)", 
				"qm.*, COUNT(qml.user_id)", 
				sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'contact'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
				"",
				"qm.id HAVING COUNT(qml.user_id) > 0"
			);
			foreach($contactList as &$item) {
				$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";
				$image = explode("/", $item['image']);
				$item['image'] = array(
					'size'	=> reset(explode("x", $image[0])),
					'name'	=> $image[1]
				);
			}		
		}

		$smarty -> assign("T_QUICK_MAILS_CONTACT_LIST", $contactList);

		$this->getParent()->appendTemplate(array(
	   		'title'			=> __QUICK_MAILS_CONTACTS,
    		'sub_title'		=> __QUICK_MAILS_CONTACT_US,
	   		'template'		=> $this->moduleBaseDir . 'templates/blocks/quick_mails.contacts.tpl',
	   		'contentclass'	=> 'blockContents',
    		'absoluteImagePath'	=> true
		), $blockIndex);

		$smarty -> assign("T_CURRENT_USER", $currentUser);

		$this->assignSmartyModuleVariables();
		 
		return true;
	}

	public function loadQuickFeedbackListBlock($blockIndex = null) {
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		$this->loadModule('xuser');
		 
		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'polo') {
			return false;
		}
		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'professor') {
			 
		} elseif (in_array($this->modules['xuser']->getExtendedTypeID($currentUser), array('pre_enrollment', 'pre_student', 'student'))) {
			 
			 
			$feedbackList = eF_getTableData(
	    			"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)",
	    			"qm.*, COUNT(qml.user_id)",
			sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'feedback'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
	    			"",
	    			"qm.id HAVING COUNT(qml.user_id) > 0"
	    			);

	    			foreach($feedbackList as &$item) {
	    				$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";

	    				$image = explode("/", $item['image']);

	    				$item['image'] = array(
	    				'size'	=> reset(explode("x", $image[0])),
	    				'name'	=> $image[1]
	    				);
	    			}
		} else {


			$feedbackList = eF_getTableData(
	    			"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)",
	    			"qm.*, COUNT(qml.user_id)",
			sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'feedback'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
	    			"",
	    			"qm.id HAVING COUNT(qml.user_id) > 0"
	    			);

	    			foreach($feedbackList as &$item) {
	    				$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";

	    				$image = explode("/", $item['image']);

	    				$item['image'] = array(
	    				'size'	=> reset(explode("x", $image[0])),
	    				'name'	=> $image[1]
	    				);
	    			}
	    			 
		}
		 
		$smarty -> assign("T_QUICK_MAILS_FEEDBACK_LIST", $feedbackList);
		 
		$this->getParent()->appendTemplate(array(
    			'title'			=> __QUICK_MAILS_FEEDBACK,
    			'sub_title'		=> __QUICK_MAILS_FEEDBACK_US,
    			'template'		=> $this->moduleBaseDir . 'templates/blocks/quick_mails.feedback.tpl',
    			'contentclass'	=> 'blockContents',
    			'absoluteImagePath'	=> true/*,
		'options'			=> array(
		array(
		'class'	=> 'quick_mails-open_feedback_block',
		'image'	=> 'images/icons/calendar.png'
		),
		array(
		'class'	=> 'quick_mails-open_contact_block',
		'image'	=> 'images/icons/list.png'
		)
		)
		*/
		), $blockIndex);
		 
		$smarty -> assign("T_CURRENT_USER", $currentUser);
		 
		$this->assignSmartyModuleVariables();

		return true;
	}

	public function getModule() {
		if ($this->getCurrentAction() == self::GET_COURSE_ROLES_SEND_LIST) {
			return parent::getModule();
		}
		$smarty = $this -> getSmartyVar();

		$this->loadModule("xuser");

		global $load_editor;
		$load_editor = true;
		$current_user = $this -> getCurrentUser();
		$smarty -> assign("T_MODULE_CURRENT_USER" , $current_user ->getType());

		$form = new HTML_QuickForm("module_mail_form", "post", $this ->moduleBaseUrl, "", "id = 'module_mail_form'");

		$form -> addElement('hidden', 'recipients', $_GET['rec']);

		$form -> addElement('hidden', 'email', $_GET['email']);
		$form -> addElement('hidden', 'name', $_GET['name']);

		$form -> addElement('text', 'subject',   _SUBJECT,   'class = "inputText" style = "width:400px;"');
		$form -> addElement('textarea', 'body', _BODY, 'class = "simpleEditor" style = "width:400px; height:200px"');
		//$form -> addElement('checkbox', 'email', _SENDASEMAILALSO, null, 'class = "inputCheckBox"');
		$form -> addRule('subject',   _THEFIELD.' "'._SUBJECT.'" '._ISMANDATORY,   'required', null, 'client');
		$form -> addRule('recipients',   _THEFIELD.' "'._RECIPIENTS.'" '._ISMANDATORY,   'required', null, 'client');
		$form -> addElement('file', 'attachment[0]', _ATTACHMENT, null, 'class = "inputText"');
		$form -> addElement('submit', 'submit_mail',    _SEND,    'class = "flatButton"');


		$contactList = eF_getTableData(
			"module_quick_mails_recipients, module_quick_mails_recipients_list qml", 
			"*", 
		sprintf("xuser_type = '%s'", $this->modules['xuser']->getExtendedTypeID($currentUser))
		);
		foreach($contactList as &$item) {
			$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";
				
			$image = explode("/", $item['image']);
				
			$item['image'] = array(
				'size'	=> reset(explode("x", $image[0])),
				'name'	=> $image[1]
			);
		}

		if ($form -> isSubmitted() && $form -> validate()) {
			$values = $form -> exportValues();
			/*
			 echo prepareGetTableData(
				"module_quick_mails_recipients_list qml, users u",
				"u.name, u.surname, u.login",
				sprintf("qml.recipient_id = %d AND qml.user_id = u.id", $values['recipients'])
				);
				*/
			if (is_numeric($values['recipients'])) {
				$recipients = eF_getTableData(
					"module_quick_mails_recipients qm, module_quick_mails_recipients_list qml, users u",
					"u.name, u.surname, u.login, u.email, qm.qm_group",
				sprintf("qm.id = qml.recipient_id AND qml.recipient_id = %d AND qml.user_id = u.id", $values['recipients'])
				);

				if (count($recipients) > 0) {


					foreach($recipients as $recipient) {
						$user_recipients[] = $recipient['login'];
						$mail_recipients[] = array(
								'login'	=> $recipient['login'],
								'email'	=> $recipient['email'],
								'fullname'	=> $recipient['name'] . ' ' . $recipient['surname']
						);
							
					}
					switch ($recipients[0]['qm_group']) {
						case "lesson_students":
							$lesson = new MagesterLesson($_SESSION['s_lessons_ID']);
							$lessonUsers  = $lesson -> getUsers("student", true, true);
							foreach ($lessonUsers as $value){
								$mail_recipients[] = array(
										'login'	=> $value['login'],
										'email'	=> $value['email'],
										'fullname'	=> $value['name'] . ' ' . $value['surname']
								);
								$user_recipients[] = $value['login'];
							}
							break;
					}
				}
			} else {
				$user_recipients[] = $values['email'];
				$mail_recipients[] = array(
				//    					'login'	=> $recipient['login'],
    					'email'	=> $values['email'],
    					'fullname'	=> $values['name']
				);
			}
				
			// ALWAYS SEND A E-MAIL
			$values['send_email'] = 1;
				
				
			//$list = implode(",",$mail_recipients);

			if (count($user_recipients) == 0) {
				$message      = __NO_RECIPIENTS_DEFINED;
				$message_type = 'failure';
			} else {
				$pm = new eF_PersonalMessage($_SESSION['s_login'], $user_recipients, $values['subject'], $values['body'], true);

				$attachFile = array();

				if ($_FILES['attachment']['name'][0] != "") {
					if ($_FILES['attachment']['size'][0] ==0 || $_FILES['attachment']['size'][0] > G_MAXFILESIZE ) {                                                           //If the directory could not be created, display an erro message
						$message      = _EACHFILESIZEMUSTBESMALLERTHAN." ".G_MAXFILESIZE." Bytes";
						$message_type = 'failure';
					}
					//Upload user avatar file
					 
					$pm -> sender_attachment_timestamp = time();

					$user_dir = G_UPLOADPATH.$_SESSION['s_login'].'/message_attachments/Sent/'.$pm -> sender_attachment_timestamp.'/';
					mkdir($user_dir,0755);
					$filesystem = new FileSystemTree($user_dir);
					$uploadedFile = $filesystem -> uploadFile('attachment', $user_dir, 0);

					$pm -> sender_attachment_fileId =  $uploadedFile['id'];
					$pm -> setAttachment($uploadedFile['path']);

					$attachFile[] = $uploadedFile['path'];
				}

				$result = true;

				if ($values['send_email']) {
					set_time_limit(0);
					foreach($mail_recipients as $key => $mail) {
						// PREPEND USER NAME MESSAGE
						$email_body =
						sprintf("Mensagem de: %s (%s) <%s>", $current_user->user['name'] . ' ' . $current_user->user['surname'], $current_user->user['login'], $current_user->user['email']) .
							"\n<br />" .
						$values['body'];
							
						$result = $result && eF_mail(
						// CHECK IF IS NECESSARY TO CHANGE DE SENDER E-MAIL
						//sprintf("%s <%s>", $current_user->user['name'] . ' ' . $current_user->user['surname'], $current_user->user['email']), // EMAIL FROM => COMMA SEP LIST
						null,
						//sprintf("%s <%s>", $mail['fullname'], $mail['email']), // EMAIL TO => COMMA SEP LIST
						sprintf("%s", $mail['email']), // EMAIL TO => COMMA SEP LIST
						$values['subject'], // EMAIL SUBJECT
						$email_body,	// EMAIL BODY
						$attachFile, 		// ATTACHMENTS
						false, 				// ONLY TEXT ?
						false				// SEND AS BCC ?
						);
					}
				}

				if ($result && $pm -> send($values['email'])) { // DO NOT SEND EMAIL
					$message      = _MESSAGEWASSENT;
					$message_type = 'success';
				} else {
					$message      = $pm -> errorMessage;
					$message_type = 'failure';
				}
			}
		}

		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);                  //Create a smarty renderer
		$renderer -> setRequiredTemplate (
  			 '{$html}{if $required}
        	&nbsp;<span class = "formRequired">*</span>
    		{/if}');
		$form -> setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);          //Set javascript error messages
		$form -> setRequiredNote(_REQUIREDNOTE);
		$form -> accept($renderer);                                                     //Assign this form to the renderer, so that corresponding template code is created

		$smarty -> assign('T_MODULE_MAIL_FORM', $renderer -> toArray());
		$smarty -> assign("T_MESSAGE_MAIL" , $message);
		$smarty -> assign("T_MESSAGE_MAIL_TYPE" , $message_type);
		//pr($renderer -> toArray());
		return true;
	}

	public function getSmartyTpl(){
		$smarty = $this -> getSmartyVar();
		$smarty -> assign("T_MODULE_MAIL_BASEDIR" , $this -> moduleBaseDir);
		$smarty -> assign("T_MODULE_MAIL_BASEURL" , $this -> moduleBaseUrl);
		$smarty -> assign("T_MODULE_MAIL_BASELINK", $this -> moduleBaseLink);
		return $this -> moduleBaseDir . "module.tpl";
	}

	public function getLessonModule() {
		return true;
	}

	public function getLessonSmartyTpl() {
		 
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		$this->loadModule('xuser');

		if (in_array($this->modules['xuser']->getExtendedTypeID($currentUser), array('pre_student', 'student', 'professor'))) {
			$contactList = eF_getTableData(
				"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)", 
				"qm.*, COUNT(qml.user_id)", 
			sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'contact'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
				"",
				"qm.id HAVING COUNT(qml.user_id) > 0" 
				);

				foreach($contactList as &$item) {
					$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";

					$image = explode("/", $item['image']);

					$item['image'] = array(
					'size'	=> reset(explode("x", $image[0])),
					'name'	=> $image[1]
					);
				}
		} else {
			return false;
		}

		$smarty -> assign("T_QUICK_MAILS_CONTACT_LIST", $contactList);

		$this->assignSmartyModuleVariables();

		return $this->moduleBaseDir . 'templates/includes/quick_mails.lessons.tpl';
		/*
		 $this->getParent()->appendTemplate(array(
		 'title'			=> __QUICK_MAILS_CONTACTS,
		 'template'		=> ,
		 'contentclass'	=> 'blockContents',
		 'absoluteImagePath'	=> true
		 ), $blockIndex);

		 $smarty -> assign("T_CURRENT_USER", $currentUser);


		  
		 return true;
		 */
		 
	}

	public function getLessonLinkInfo() {
		return array(
            	'title' => _MAILS_MODULEMAILS,
                         'image' => 'images/32x32/mail.png',
                         'link'  => $this -> moduleBaseUrl);

	}
	public function isLessonModule(){
		return true;
	}

	public function getCourseDashboardLinkInfo() {
		return array(
            'title' => _MAILS_MODULEMAILS,
			'image' => 'images/others/transparent.gif',
            'image_class' => 'sprite32 sprite32-mail',
			'link'  => $this -> moduleBaseUrl . "&action=" . self::GET_COURSE_ROLES_SEND_LIST
		);
	}


	/* ACTIONS FUNCTIONS */
	public function getCourseRolesSendListAction() {
		// GET LAST MESSAGES FROM LESSON
		$smarty = $this->getSmartyVar();

		if ($_GET['output'] == 'innerhtml') {
			$this -> getLessonSmartyTpl();
			$result = $smarty -> fetch($this -> moduleBaseDir . "templates/actions/" . $this->getCurrentAction() . ".tpl");
			echo $result;
			exit;
		}
	}

	/* HOOK ACTIONS FUNCTIONS */
	  
	/* DATA MODEL FUNCTIONS /*/

}
?>
