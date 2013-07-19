<?php
/**
 * @author Andre
 * @copyright 2008
 */
class module_quick_mails extends MagesterExtendedModule
{
	const GET_COURSE_ROLES_SEND_LIST = 'get_course_roles_send_list';
	const VIEW_LIST = 'view_list';
	const ADD_LIST_ITEM = 'add_list_item';
	const EDIT_LIST_ITEM = 'edit_list_item';
	const REMOVE_LIST_ITEM = 'remove_list_item';
	const EDIT_ITEM_DESTINATION = 'edit_item_destination';
	const TOGGLE_USER_IN_RECIPIENT_LIST = 'toggle_user_in_recipient_list';
	
	const ADD_NEW_SCOPE = 'add_new_scope';
	const DELETE_SCOPE = 'delete_scope';
	

	public function __construct($defined_moduleBaseUrl, $defined_moduleFolder)
	{
		$this->migratedActions = array(
			self::GET_COURSE_ROLES_SEND_LIST,
			self::VIEW_LIST,
			self::ADD_LIST_ITEM,
			self::EDIT_LIST_ITEM,
			self::REMOVE_LIST_ITEM,
			self::EDIT_ITEM_DESTINATION,
			self::TOGGLE_USER_IN_RECIPIENT_LIST,
			self::ADD_NEW_SCOPE,
			self::DELETE_SCOPE
		);
		parent::__construct($defined_moduleBaseUrl, $defined_moduleFolder);

	}

	public function getName()
	{
		return "QUICK_MAILS";
	}

	public function getPermittedRoles()
	{
		return array("administrator", "professor", "student");
	}

	public function getUserContactList($user = null)
	{
		if (!($user instanceof MagesterUser)) {
			$user = $this->getCurrentUser();
		}

		$xentifyModule = $this->loadModule("xentify");
		$contactListData = sC_getTableData(
			"module_quick_mails_recipients qmr
			LEFT JOIN module_quick_mails_scope scope ON (scope.recipient_id = qmr.id)
			LEFT OUTER JOIN module_quick_mails_groups qmg ON (qmr.group_id = qmg.id)",
			"scope.recipient_id, scope.xscope_id, scope.xentify_id, qmr.qm_type, qmr.link, qmr.title, qmr.image, qmr.group_id, qmg.name as group_name",
			"",
			"",
			"scope.recipient_id, scope.xscope_id, scope.xentify_id"
		);

		$contactList = array();

		foreach ($contactListData as $key => $recp) {

			if (!$xentifyModule->isUserInScope($user, $recp['xscope_id'], $recp['xentify_id'])) {
				unset($contactListData[$key]);
			} else {
				if ($recp['qm_type'] == 'link') {
					$recp['href'] = $recp['link'];
				} else {
					$recp['href']	= $this->moduleBaseUrl . "&rec=" . $recp['recipient_id'] . "&popup=1";	
				}
				$image = explode("/", $recp['image']);
				$recp['image'] = array(
						'size'	=> reset(explode("x", $image[0])),
						'name'	=> $image[1]
				);
				if (!is_array($contactList[$recp['group_id']])) {
					$contactList[$recp['group_id']] = array();
				}
				$contactList[$recp['group_id']][$recp['recipient_id']] = $recp;
			}
		}
		return $contactList;
	}

	/* BLOCK FUNCTIONS */
	public function loadQuickContactListBlock($blockIndex = null)
	{
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		$this->loadModule('xuser');

		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'polo') {
			return false;
		}
		//if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'professor') {
		//} elseif (in_array($this->modules['xuser']->getExtendedTypeID($currentUser), array('pre_enrollment', 'pre_student', 'student'))) {

//var_dump($this->getUserLinkList());
//	exit;

			$contactList = $this->getUserContactList();
		/*
		} else {
			$contactList = sC_getTableData(
				"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)",
				"qm.*, COUNT(qml.user_id)",
				sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'contact'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
				"",
				"qm.id HAVING COUNT(qml.user_id) > 0"
			);
			foreach ($contactList as &$item) {
				$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";
				$image = explode("/", $item['image']);
				$item['image'] = array(
					'size'	=> reset(explode("x", $image[0])),
					'name'	=> $image[1]
				);
			}
		}
		*/

		$smarty -> assign("T_QUICK_MAILS_CONTACT_LIST", $contactList);

		$this->getParent()->appendTemplate(array(
	   		'title'			=> __QUICK_MAILS_CONTACTS,
    		'sub_title'		=> __QUICK_MAILS_CONTACT_US,
	   		'template'		=> $this->moduleBaseDir . 'templates/blocks/quick_mails.contacts.tpl',
	   		'contentclass'	=> 'blockContents',
    		//'absoluteImagePath'	=> true,
			'options'		=> array(
				array(
					'href'			=> "javascript: _sysclass('quick_mails').toggleContactListBlock(1);",
					'image'			=> 'others/transparent.png',
					'text'			=> "Extensão",
					'class'			=> 'qm_view_first_list',
					'image-class'	=> 'sprite16 sprite16-first'
				),
				array(
					'href'			=> "javascript: _sysclass('quick_mails').toggleContactListBlock(2);",
					'image'			=> 'others/transparent.png',
					'text'			=> "Pós-graduação",
					'class'			=> 'qm_view_second_list',
					'image-class'	=> 'sprite16 sprite16-second'
				)
			)
		), $blockIndex);

		$smarty -> assign("T_CURRENT_USER", $currentUser);
		$this->assignSmartyModuleVariables();

		return true;
	}

	public function loadQuickFeedbackListBlock($blockIndex = null)
	{
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		$this->loadModule('xuser');

		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'polo') {
			return false;
		}
		if ($this->modules['xuser']->getExtendedTypeID($currentUser) == 'professor') {

		} elseif (in_array($this->modules['xuser']->getExtendedTypeID($currentUser), array('pre_enrollment', 'pre_student', 'student'))) {

			$feedbackList = sC_getTableData(
	    			"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)",
	    			"qm.*, COUNT(qml.user_id)",
			sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'feedback'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
	    			"",
	    			"qm.id HAVING COUNT(qml.user_id) > 0"
	    			);

	    			foreach ($feedbackList as &$item) {
	    				$item['href']	= $this->moduleBaseUrl . "&rec=" . $item['id'] . "&popup=1";

	    				$image = explode("/", $item['image']);

	    				$item['image'] = array(
	    				'size'	=> reset(explode("x", $image[0])),
	    				'name'	=> $image[1]
	    				);
	    			}
		} else {

			$feedbackList = sC_getTableData(
	    			"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)",
	    			"qm.*, COUNT(qml.user_id)",
			sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'feedback'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
	    			"",
	    			"qm.id HAVING COUNT(qml.user_id) > 0"
	    			);

	    			foreach ($feedbackList as &$item) {
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

	public function getDefaultAction()
	{
		$xuserModule = $this->loadModule("xuser");
		$currentUser = $this->getCurrentUser();
    	if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
			$currentUser->moduleAccess['quick_mails'] == 'view' ||
			$currentUser->moduleAccess['quick_mails'] == 'change'
   		) {
    		return self::VIEW_LIST;
    	}
	}

	public function getModule()
	{
		if (in_array($this->getCurrentAction(), $this->migratedActions)) {
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

		$contactList = $this->getUserContactList();

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

				$recipients = sC_getTableData(
					"module_quick_mails_recipients qm, module_quick_mails_recipients_list qml, users u",
					"u.name, u.surname, u.login, u.email, qm.qm_group",
					sprintf("qm.id = qml.recipient_id AND qml.recipient_id = %d AND qml.user_id = u.id", $values['recipients'])
				);

				if (count($recipients) > 0) {

					foreach ($recipients as $recipient) {
						$user_recipients[] = $recipient['login'];
						$mail_recipients[] = array(
								'login'	=> $recipient['login'],
								'email'	=> $recipient['email'],
								'fullname'	=> $recipient['name'] . ' ' . $recipient['surname']
						);
					}

/*
					switch ($recipients[0]['qm_group']) {
						case "lesson_students":
							$lesson = new MagesterLesson($_SESSION['s_lessons_ID']);
							$lessonUsers  = $lesson -> getUsers("student", true, true);
							foreach ($lessonUsers as $value) {
								$mail_recipients[] = array(
										'login'	=> $value['login'],
										'email'	=> $value['email'],
										'fullname'	=> $value['name'] . ' ' . $value['surname']
								);
								$user_recipients[] = $value['login'];
							}
							break;
					}
*/
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

				$pm = new sC_PersonalMessage($this->getCurrentUser()->user['login'], $user_recipients, $values['subject'], $values['body'], true);

				$attachFile = array();

				if ($_FILES['attachment']['name'][0] != "") {
					if ($_FILES['attachment']['size'][0] ==0 || $_FILES['attachment']['size'][0] > G_MAXFILESIZE) {                                                           //If the directory could not be created, display an erro message
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

					$courses = $current_user->getUserCourses();

					if (count($courses) > 2) {
						$lessonHide = true;
					} else {
						$userLessons = $current_user->getLessons();
					}

					$courseArray = array();
					foreach ($courses as $course) {
						if (!$lessonHide) {
							$lessons = $course->getCourseLessons();
							$lessonArray = array();
							foreach ($lessons as $lesson) {
								if (in_array($lesson->lesson['id'], array_keys($userLessons))) {
									$lessonArray[] = "<li>" . $lesson->lesson['name'] . "</li>";
								}

								//$lessons = $course->getLessons()
							}

							$courseArray[] = "<li>" . "<strong>" . $course->course['name'] . "</strong><ul>" . implode(",", $lessonArray) . "</ul></li>";
						} else {
							$courseArray[] = "<li>" . $course->course['name'] . "</li>";
						}
					}

					foreach ($mail_recipients as $key => $mail) {
						// PREPEND USER NAME MESSAGE
						$email_body =
						sprintf("Mensagem de: %s <strong>(%s)</strong> &lt;%s&gt;", $current_user->user['name'] . ' ' . $current_user->user['surname'], $current_user->user['login'], $current_user->user['email']) .
							"\n<br />" .
							"Matriculado nos seguintes cursos/disciplinas:\n<br />" .
							sprintf("<ul>%s</ul>", implode("", $courseArray)) .
							"Corpo da Mensagem:\n<br /><br />" .
						$values['body'];

						$result = $result && sC_mail(
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

	public function getSmartyTpl()
	{
		if (in_array($this->getCurrentAction(), $this->migratedActions)) {
			return parent::getSmartyTpl();
		}
		$smarty = $this -> getSmartyVar();
		$smarty -> assign("T_MODULE_MAIL_BASEDIR" , $this -> moduleBaseDir);
		$smarty -> assign("T_MODULE_MAIL_BASEURL" , $this -> moduleBaseUrl);
		$smarty -> assign("T_MODULE_MAIL_BASELINK", $this -> moduleBaseLink);
		return $this -> moduleBaseDir . "module.tpl";
	}
/*
	public function getLessonModule()
	{
		return true;
	}
*/
	public function getLessonSmartyTpl()
	{
		$smarty 		= $this->getSmartyVar();
		$currentUser	= $this->getCurrentUser();
		$this->loadModule('xuser');

		if (in_array($this->modules['xuser']->getExtendedTypeID($currentUser), array('pre_student', 'student', 'professor'))) {
			$contactList = sC_getTableData(
				"module_quick_mails_recipients qm LEFT OUTER JOIN module_quick_mails_recipients_list qml ON (qm.id = qml.recipient_id)",
				"qm.*, COUNT(qml.user_id)",
			sprintf("qm.xuser_type LIKE '%%%s%%' AND qm.qm_type = 'contact'", $this->modules['xuser']->getExtendedTypeID($currentUser)),
				"",
				"qm.id HAVING COUNT(qml.user_id) > 0"
				);

				foreach ($contactList as &$item) {
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

	public function getLessonLinkInfo()
	{
		return array(
            	'title' => _MAILS_MODULEMAILS,
                         'image' => 'images/32x32/mail.png',
                         'link'  => $this -> moduleBaseUrl);

	}
	public function isLessonModule()
	{
		return true;
	}

	public function getCourseDashboardLinkInfo()
	{
		return array(
            'title' => _MAILS_MODULEMAILS,
			'image' => 'images/others/transparent.gif',
            'image_class' => 'sprite32 sprite32-mail',
			'link'  => $this -> moduleBaseUrl . "&action=" . self::GET_COURSE_ROLES_SEND_LIST
		);
	}

	/* ACTIONS FUNCTIONS */
	public function getCourseRolesSendListAction()
	{
		// GET LAST MESSAGES FROM LESSON
		$smarty = $this->getSmartyVar();

		if ($_GET['output'] == 'innerhtml') {
			$this -> getLessonSmartyTpl();
			$result = $smarty -> fetch($this -> moduleBaseDir . "templates/actions/" . $this->getCurrentAction() . ".tpl");
			echo $result;
			exit;
		}
	}
	/* ACTIONS FUNCTIONS */
	public function viewListAction()
	{
		$smarty = $this->getSmartyVar();

		/// GET =
		/*
		 * negociation_id	228
		 * invoice_index	3
		*/
		$listItems = sC_getTableData(
			"module_quick_mails_recipients qm LEFT JOIN module_quick_mails_groups qmg ON (qm.group_id = qmg.id)",
			"qm.id, qm.group_id, qmg.name as `group`, qm.title, qm.qm_type, qm.image"
		);

		$qmTypesOptions = array(
			'contact'	=> __QUICK_MAILS_CONTACT_OPTION,
			'feedback'	=> __QUICK_MAILS_FEEDBACK_OPTION,
			'link'		=> __QUICK_MAILS_LINK_OPTION
		);

		$smarty->assign("T_LIST", $listItems);
		$smarty->assign("T_LIST_TYPES", $qmTypesOptions);
	}

	public function addListItemAction()
	{
		$smarty = $this->getSmartyVar();

		/// GET =
		/*
		 * negociation_id	228
		 * invoice_index	3
		*/
		$qmGroupOptionsDB = sC_getTableData(
			"module_quick_mails_groups",
			"id, name"
		);

		$qmGroupOptions = array();
		foreach($qmGroupOptionsDB as $item) {
			$qmGroupOptions[$item['id']] = $item['name'];
		}

		$qmTypesOptions = array(
			'contact'	=> __QUICK_MAILS_CONTACT_OPTION,
			'feedback'	=> __QUICK_MAILS_FEEDBACK_OPTION,
			'link'		=> __QUICK_MAILS_LINK_OPTION
		);

		$xentifyModule = $this->loadModule("xentify");

		$entifyScopes = $xentifyModule->getScopes();

		$itemScopes = sC_getTableData(
			"module_quick_mails_scope",
			"codigo, recipient_id, xscope_id, xentify_id",
			sprintf("recipient_id = %d", $_GET['item_id'])
		);

		foreach($itemScopes as &$itemScoped) {
			$itemScoped['description'] = $xentifyModule->getScopeFullDescription(null, $itemScoped['xscope_id'], $itemScoped['xentify_id']);

		}

		$smarty->assign("T_ITEM_SCOPES", $itemScopes);
		

		// CRIAR FORMULÁRIO DE CAIXA DE DIALOGOS
		$form = new HTML_QuickForm2("quick_mail_edit_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);

		$form
			->addSelect('group_id', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_GROUP, 'options' => $qmGroupOptions));

		$form
			->addText('title', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_TITLE));

		$form
			->addSelect('qm_type', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_TYPE, 'options' => $qmTypesOptions));

		$form -> addSubmit('_save', null, array('label'	=> __QUICK_MAILS_SAVE));

		if ($form -> isSubmitted() && $form -> validate()) {
			// VALIDATE
			$values = $form->getValue();

			$insertValues = array(
				'group_id'	=> $values['group_id'],
				'title'		=> $values['title'],
				'qm_type'	=> $values['qm_type']
			);

			if ($values['image']) {
				$insertValues['image']	= $values['image'];
			}

			$itemID = sC_insertTableData("module_quick_mails_recipients", $insertValues);

			// INSERE VALORES E REDIRECIONA PARA
			header(sprintf("Location: " . $this->moduleBaseUrl . "&action=edit_list_item&item_id=%s&message=%s&message_type=success", $itemID,  __QUICK_MAILS_SUCCESS));
			exit;
		}
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));
		// Set defaults for the form elements
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_QUICK_MAILS_EDIT_FORM', $renderer -> toArray());

		$this->assignSmartyModuleVariables();
	}


	public function editListItemAction()
	{
		$smarty = $this->getSmartyVar();

		/// GET =
		/*
		 * negociation_id	228
		 * invoice_index	3
		*/
		$itemID = $_GET['item_id'];
		list($values) = sC_getTableData(
			"module_quick_mails_recipients qm LEFT JOIN module_quick_mails_groups qmg ON (qm.group_id = qmg.id)",
			"qm.id, qm.group_id, qmg.name as `group`, qm.title, qm.qm_type, qm.image, qm.link",
			sprintf("qm.id = %d", $itemID)
		);

		$qmGroupOptionsDB = sC_getTableData(
			"module_quick_mails_groups",
			"id, name"
		);

		$qmGroupOptions = array();
		foreach($qmGroupOptionsDB as $item) {
			$qmGroupOptions[$item['id']] = $item['name'];
		}

		$qmTypesOptions = array(
			'contact'	=> __QUICK_MAILS_CONTACT_OPTION,
			'feedback'	=> __QUICK_MAILS_FEEDBACK_OPTION,
			'link'		=> __QUICK_MAILS_LINK_OPTION
		);

		$xentifyModule = $this->loadModule("xentify");

		$entifyScopes = $xentifyModule->getScopes();

		$itemScopes = sC_getTableData(
			"module_quick_mails_scope",
			"codigo, recipient_id, xscope_id, xentify_id",
			sprintf("recipient_id = %d", $_GET['item_id'])
		);

		foreach($itemScopes as &$itemScoped) {
			$itemScoped['description'] = $xentifyModule->getScopeFullDescription(null, $itemScoped['xscope_id'], $itemScoped['xentify_id']);

		}

		$smarty->assign("T_ITEM_SCOPES", $itemScopes);

		$smarty->assign("T_ITEM_TYPE", $values['qm_type']);

		
		// CRIAR FORMULÁRIO DE CAIXA DE DIALOGOS
		$form = new HTML_QuickForm2("quick_mail_edit_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);
		
		$form -> addHidden('id')->setValue($values['id']);

		$form
			->addSelect('group_id', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_GROUP, 'options' => $qmGroupOptions))
			->setValue($values['group_id']);

		$form
			->addText('title', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_TITLE))
			->setValue($values['title']);

		$form
			->addStatic('qm_type', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_TYPE, 'options' => $qmTypesOptions))
			->setValue($values['qm_type']);

		if ($values['qm_type'] == 'link') {
			var_dump($values['link']);
			$form
				->addText('link', array('class' => 'large'), array('label'	=> __QUICK_MAILS_FIELD_LINK))
				->setValue($values['link']);
		}

		$form -> addSubmit('_save', null, array('label'	=> __QUICK_MAILS_SAVE));

		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));

		if ($form -> isSubmitted() && $form -> validate()) {
			// VALIDATE
			$values = $form->getValue();

			$insertValues = array(
				'group_id'	=> $values['group_id'],
				'title'		=> $values['title']
			);

			if ($values['image']) {
				$insertValues['image']	= $values['image'];
			}
			if ($values['link']) {
				$insertValues['link']	= $values['link'];
			}

			sC_updateTableData("module_quick_mails_recipients", $insertValues, sprintf("id = %d", $itemID));

			// INSERE VALORES E REDIRECIONA PARA
			header(sprintf("Location: " . $this->moduleBaseUrl . "&action=view_list&message=%s&message_type=success", __QUICK_MAILS_SUCCESS));
			exit;
		}
		
		// Set defaults for the form elements
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_QUICK_MAILS_EDIT_FORM', $renderer -> toArray());

		$this->assignSmartyModuleVariables();
	}

	public function removeListItemAction()
	{
		$xuserModule = $this->loadModule("xuser");
		$currentUser = $this->getCurrentUser();
    	if (
			$xuserModule->getExtendedTypeID($currentUser) != "administrator" &&
			$currentUser->moduleAccess['quick_mails'] != 'view' &&
			$currentUser->moduleAccess['quick_mails'] != 'change'
   		) {
			header(sprintf("Location: " . $this->moduleBaseUrl . "&message=%s&message_type=success", __QUICK_MAILS_ERROR_PERMISSION));
			exit;
		}
		if (sC_checkParameter($_GET['item_id'], "id")) {
			sc_deleteTableData(" module_quick_mails_recipients", "id = " . $_GET['item_id']);
		}
		header(sprintf("Location: " . $this->moduleBaseUrl . "&action=view_list&message=%s&message_type=success", __QUICK_MAILS_SUCCESS));
		exit;
	}

	public function editItemDestinationAction()
	{
		$smarty = $this->getSmartyVar();

		if (!sC_checkParameter($_GET['item_id'], 'id')) {
			header(sprintf("Location: " . $this->moduleBaseUrl . "&message=%s&message_type=success", __QUICK_MAILS_ERROR_PERMISSION));
			exit;
		}
		$itemID = $_GET['item_id'];

		// Assign the right values needed by the sql query
        //$limit  = (isset($_GET['limit']) && sC_checkParameter($_GET['limit'], 'uint'))  ? $_GET['limit']  : G_DEFAULT_TABLE_SIZE;
        $sort   = (isset($_GET['sort']) && sC_checkParameter($_GET['sort'], 'text'))    ? $_GET['sort']   : 'login';
        $order  = (isset($_GET['order']) && $_GET['order'] == 'desc')                   ? 'desc'          : 'asc';
        //$offset = (isset($_GET['offset']) && sC_checkParameter($_GET['offset'], 'int')) ? $_GET['offset'] : 0;
        $where = array();
        $where[] = "(qml.recipient_id = $itemID OR qml.recipient_id IS NULL)";
        $where[] = "u.archive = 0";
        $where = implode(" AND ", $where);

        // Write the sql query
		$users = sC_getTableData(
			"users u LEFT JOIN `module_quick_mails_recipients_list` qml ON (u.id = qml.user_id)",
			"qml.recipient_id, u.id, u.login, u.user_type, u.user_types_ID, u.email",
			$where,
			"qml.recipient_id DESC, $sort"
		);
        // Run the query and get the results

/*
		$totalUsers = sC_countTableData(
			"users u LEFT JOIN `module_quick_mails_recipients_list` qml ON (u.id = qml.user_id)",
			"u.id, u.login, u.user_type, u.user_types_ID",
			$where
		);
        $totalUsers = $totalUsers[0]['count'];
*/
        // Assign the template data and display it
        $languages = MagesterSystem::getLanguages(true);
        //$smarty->assign("T_LANGUAGES", $languages);
        //$smarty->assign("T_USERS_SIZE", $totalUsers);
        $smarty->assign("T_QUICK_MAILS_USERS", $users);
        $smarty->assign("T_ROLES", MagesterUser::getRoles(true));
		//$smarty -> assign("T_TABLE_SIZE", $totalEntries);

	}

	public function toggleUserInRecipientListAction()
	{
		$xuserModule = $this->loadModule("xuser");
		$currentUser = $this->getCurrentUser();
    	if (
			$xuserModule->getExtendedTypeID($currentUser) != "administrator" &&
			$currentUser->moduleAccess['quick_mails'] != 'view' &&
			$currentUser->moduleAccess['quick_mails'] != 'change'
   		) {
			echo json_encode(
				array(
					'message'		=> __QUICK_MAILS_ERROR_PERMISSION,
					'message_type'	=> 'error'
				)
			);
			exit;
		}

  		if (!sC_checkParameter($_POST['recipient_id'], 'id') || !sC_checkParameter($_POST['user_id'], 'id')) {
			echo json_encode(
				array(
					'message'		=> __QUICK_MAILS_PARAMS_ERROR,
					'message_type'	=> 'error'
				)
			);
			exit;
		}

		list($userInList) = sC_countTableData(
			"module_quick_mails_recipients_list",
			"recipient_id",
			sprintf("recipient_id = %d AND user_id = %d", $_POST['recipient_id'], $_POST['user_id'])
		);
		if ($userInList['count'] == 0) {
			sC_insertTableData(
				"module_quick_mails_recipients_list",
				array(
					'recipient_id'	=> $_POST['recipient_id'],
					'user_id'		=> $_POST['user_id']
				)
			);
			echo json_encode(
				array(
					'message'		=> __QUICK_MAILS_SUCCESS_INSERT,
					'message_type'	=> 'success'
				)
			);
		} else {
			sC_deleteTableData(
				"module_quick_mails_recipients_list",
				sprintf("recipient_id = %d AND user_id = %d", $_POST['recipient_id'], $_POST['user_id'])
			);
			echo json_encode(
				array(
					'message'		=> __QUICK_MAILS_SUCCESS_REMOVE,
					'message_type'	=> 'success'
				)
			);

		}
		exit;



		exit;
	}


	public function addNewScopeAction()
	{
		$smarty 		= $this->getSmartyVar();
    	$currentUser	= $this->getCurrentUser();

    	$xuserModule = $this->loadModule("xuser");
    	$xentifyModule = $this->loadModule("xentify");

    	$itemID = $_GET['item_id'];

    	if (
			$xuserModule->getExtendedTypeID($currentUser) != "administrator" &&
			$currentUser->moduleAccess['xentify'] != 'view' &&
			$currentUser->moduleAccess['xentify'] != 'change'
   		) {
			header(sprintf("Location: " . $this->moduleBaseUrl . "&message=%s&message_type=success", __QUICK_MAILS_ERROR_PERMISSION));
			exit;
		}

    	// LOAD DATA FROM xentify Module
    	$scopeData = sC_getTableData("module_xentify_scopes", "id, name, description, rules", "active = 1");
    	$scopeCombo = array(-1	=> __SELECT_ONE_OPTION);
    	foreach ($scopeData as $item) {
    		$scopeCombo[$item['id']] = $item['name'];
    	}

    	$form = new HTML_QuickForm2("quickmail_new_scope_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);

		//$form -> registerRule('checkParameter', 'callback', 'sC_checkParameter');

		$form -> addElement('hidden', 'step_index');
		$form -> addSelect('scope_id', null, array('label'	=> __QUICKMAIL_SCOPE, 'options'	=> $scopeCombo));

		$form -> addSubmit('submit_scope', null, array('label'	=> __QUICK_MAILS_SAVE));

		$scopeFields = array();

		$values = $form->getValue();

		if (is_numeric($values['scope_id']) && $values['scope_id'] > 0) {
			// MAKE OPTIONS FOR SELECTED SCOPE
			$scopeFields = $xentifyModule->makeScopeFormOptions($values['scope_id'], $form);
			$values = $form->getValue();

			$smarty -> assign("T_QUICK_MAIL_SCOPE_FIELDS", $scopeFields);

			if ($form -> isSubmitted() && $form -> validate()) {
				$xentifyValues = array();
				foreach ($scopeFields as $field_name) {
					$xentifyValues[] = $values[$field_name];
				}

				$insertValues = array(
					'recipient_id'	=> $itemID,
					'xscope_id'		=> $values['scope_id'],
					'xentify_id'	=> implode(module_xentify::XENTIFY_SEP, $xentifyValues)
				);

				sC_insertTableData("module_quick_mails_scope", $insertValues);

				// INSERE VALORES E REDIRECIONA PARA
				header(sprintf("Location: " . $this->moduleBaseUrl . "&action=edit_list_item&item_id=%s", $itemID));
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
		$smarty -> assign('T_QUICK_MAIL_NEW_SCOPE_FORM', $renderer -> toArray());

		return true;
	}

	public function deleteScopeAction() {
		$xuserModule = $this->loadModule("xuser");
		$currentUser = $this->getCurrentUser();
    	if (
			$xuserModule->getExtendedTypeID($currentUser) != "administrator" &&
			$currentUser->moduleAccess['xentify'] != 'view' &&
			$currentUser->moduleAccess['xentify'] != 'change'
   		) {
			header(sprintf("Location: " . $this->moduleBaseUrl . "&message=%s&message_type=success", __QUICK_MAILS_ERROR_PERMISSION));
			exit;
		}
		if (sC_checkParameter($_GET['scope_id'], "id")) {
			sc_deleteTableData("module_quick_mails_scope", "codigo = " . $_GET['scope_id']);
		}
		$itemID = $_GET['item_id'];
		header(sprintf("Location: " . $this->moduleBaseUrl . "&action=edit_list_item&item_id=%s&message=%s&message_type=success", $itemID, __QUICK_MAILS_SUCCESS));
		exit;
	}
	

	/* HOOK ACTIONS FUNCTIONS */
	/* DATA MODEL FUNCTIONS /*/

	public function getCenterLinkInfo()
	{
		$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");
		if (
				$xuserModule->getExtendedTypeID($currentUser) == "administrator"
		) {
			return array(
				'title' => __QUICK_MAILS_CONTROL_PANEL_TITLE,
				'image' => $this -> moduleBaseDir . 'images/quick_mails.png',
				'link'  => $this -> moduleBaseUrl,
				'class' => 'quick_mails'
			);
		}
	}
}
