<?php
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Users\Group as UserGroup,
    Sysclass\Models\Messages\Group as MessageGroup,
    Sysclass\Models\Messages\Message;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class MessagesModule extends SysclassModule implements ISummarizable, IBlockProvider, ISectionMenu, IWidgetContainer
{
    // ISummarizable
    public function getSummary() {
        //$data = $this->dataAction();
        return false;
        $total = $this->getTotalUnviewed();
        return array(
            'type'  => 'primary',
            //'count' => count($data),
            'count' => $total,
            'text'  => self::$t->translate('Messages'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'inbox'
            )
        );
    }

    // IBlockProvider
    public function registerBlocks() {
        return array(
            'messages.group.allow' => function($data, $self) {
                $self->putComponent("select2");
                //$self->putModuleScript("dialog.permission");
                //$this->putSectionTemplate(null, "blocks/permission");
                //$self->putSectionTemplate("foot", "dialogs/add");
                $messagesGroupsCollection = $self->model("messages/groups/collection");

                $messageGroupItems = $messagesGroupsCollection->getItems();

                $self->putItem("block_messages_groups", $messageGroupItems);

                $self->putSectionTemplate("behaviours", "blocks/messages.group.allow");

                return true;

            },
            'messages.send.dialog' => function($data, $self) {
                // CREATE BLOCK CONTEXT
                //$self->putComponent("data-tables");
                $self->putComponent("wysihtml5");
                $self->putComponent("select2");


                $receiverGroups = UserGroup::findConnectBy(array(
                    'conditions' => 'behaviour_allow_messages > 0 AND active = 1',
                    'connect_by' => 'behaviour_allow_messages'
                ));
                $self->putItem("receivers", $receiverGroups);


                $messageGroupsRS = MessageGroup::find();
                $messageGroups = array();
                foreach($messageGroupsRS as $messageGroup) {
                    $messageGroups[$messageGroup->id] = $messageGroup->name;
                }
                $self->putItem("message_groups", $messageGroups);


                $self->putModuleScript("dialogs.messages.send");
                $self->putSectionTemplate("dialogs", "dialogs/send");

                return true;
            }
        );

    }

    // ISectionMenu
    public function getSectionMenu($section_id) {
        /*
    	if ($section_id == "topbar") {

            $total = $this->getTotalUnviewed();

            $currentUser = $this->getCurrentUser();
            $currentFolder = $this->getDefaultFolder($currentUser);

            $messages = $this->getUnviewedMessages(array($currentFolder));

            $items = array();
            foreach($messages as $msg) {
                $items[] = array(
                    'link'      => $this->getBasePath() . "view/" . $msg['id'],
                    'values' => array(
                        'photo'     => 'img/avatar2.jpg',
                        'from'      => $msg['sender'],
                        'time'      => $msg['timestamp'],
                        'message'   => substr(strip_tags($msg['body']), 0, 50) . "..."
                    )
                );
            }

    		$menuItem = array(
    			'icon' 		=> 'envelope',
    			'notif' 	=> $total,
    			'text'		=> self::$t->translate('You have %s new messages', $total),
    			'external'	=> array(
    				'link'	=> $this->getBasePath() . "inbox",
    				'text'	=> self::$t->translate('See all messages')
    			),
                'link'  => array(
                    'link'  => $this->getBasePath() . "inbox",
                    'text'  => self::$t->translate('Messages')
                ),
    			'type'		=> 'inbox',
    			'items'		=> $items,
                'extended'  => true
    		);

    		return $menuItem;
    	}
        */
    	return false;
    }
    // IWidgetContainer
    public function getWidgets($widgetsIndexes = array()) {
        $widgetsNames = array(1 => 'messages.contactus', 2 => 'messages.help', 3 => 'messages.improvements');

        if (
            in_array($widgetsNames[1], $widgetsIndexes) ||
            in_array($widgetsNames[2], $widgetsIndexes) ||
            in_array($widgetsNames[3], $widgetsIndexes)
        ) {
            //$this->putCss("plugins/bootstrap-wysihtml5/bootstrap-wysihtml5");
            //$this->putCss("plugins/bootstrap-wysihtml5/wysiwyg-color");
            //$this->putScript("plugins/bootstrap-wysihtml5/wysihtml5-0.3.0");
            //$this->putScript("plugins/bootstrap-wysihtml5/bootstrap-wysihtml5");

            //$this->putCss("plugins/bootstrap-fileupload/bootstrap-fileupload");
            //$this->putScript("plugins/bootstrap-fileupload/bootstrap-fileupload");

            //$this->putScript("plugins/jquery-validation/dist/jquery.validate.min");
            //$this->putScript("plugins/jquery-validation/dist/additional-methods.min");

            //$this->putCss("plugins/bootstrap-toastr/toastr.min");
            //$this->putScript("plugins/bootstrap-toastr/toastr.min");


            //$this->putModuleScript("messages");


            $this->putBlock("messages.send.dialog");

            $groups = $this->getMessageGroups();

            $recipients = $this->getMessageReceivers();

            foreach($groups as $group) {
                $widgets[$widgetsNames[$group['id']]] = array(
                    //'title'     => self::$t->translate($group['name']),
                    'header'    => self::$t->translate($group['name']),
                    'template'  => $this->template("contact-list.widget"),
                    'icon'      => $group['icon'],
                    'panel'     => 'dark-blue messages-panel',
                    'body'      => false,
                    'data'      => $recipients[$group['id']]
                );
            }

            return $widgets;
        }
    }

    /**
     * [ add a description ]
     *
     * @url POST /item/me
     */
    public function addItemAction($id)
    {
        $request = $this->getMatchedUrl();

        if ($userData = $this->getCurrentUser(true)) {
            //$itemModel = $this->model("user/item");
            // TODO CHECK IF CURRENT USER CAN DO THAT
            $data = $this->getHttpData(func_get_args());
            $itemModel = new Message();
            $itemModel->assign($data);

            $itemModel->user_id = $userData->id;

            if ($itemModel->save()) {

                if (array_key_exists('group_id', $data) && is_array($data['group_id']) ) {
                    //UsersGroups::find("user_id = {$userModel->id}")->delete();
                    
                    foreach($data['group_id'] as $group) {
                        /*
                        $userGroup = new UsersGroups();
                        $userGroup->user_id = $userModel->id;
                        $userGroup->group_id = $group['id'];
                        $userGroup->save();
                        */
                    }
                }

                return $this->createAdviseResponse(
                    self::$t->translate("Message created with success. You can follow thge message in your inbox."),
                    "success"
                );
            } else {
                $response = $this->createAdviseResponse(self::$t->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                return $response;
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }

    /**
     * [getMessageGroups description]
     * @deprecated 3.0.14
     */
    protected function getMessageGroups() {
        return $this->_getTableData(
            "mod_messages_groups",
            "id as id, name, icon",
            "",
            "id ASC"
        );
    }

    /**
     * [getMessageReceivers description]
     * @param  [type] $group_id [description]
     * @return [type]           [description]
     * @deprecated 3.0.14
     */
    protected function getMessageReceivers($group_id = null) {
        if (!($user instanceof MagesterUser)) {
            $user = $this->getCurrentUser();
        }
        /* CREATE AN ENTRY POINT TO xentify Module */
        //$xentifyModule = $this->loadModule("xentify");
        $receiversCollection = $this->model("messages/receivers/collection");

        $contactListData = $receiversCollection->getItems();


        /*
        $contactListData = $this->_getTableData(
            "mod_messages_recipients qmr
            LEFT OUTER JOIN mod_messages_recipients_list scope ON (scope.recipient_id = qmr.id)
            LEFT OUTER JOIN mod_messages_groups qmg ON (qmr.group_id = qmg.id)
            LEFT OUTER JOIN users u ON (scope.user_id = u.id)",
            "qmr.id as recipient_id, scope.xscope_id, scope.xentify_id, qmr.qm_type, qmr.link, qmr.title, qmr.image, qmr.group_id, qmg.name as group_name",
            "",
            "recipient_id ASC"
        );
        */
        $contactList = array();

        foreach ($contactListData as $key => $recp) {

            //if (!$xentifyModule->isUserInScope($user, $recp['xscope_id'], $recp['xentify_id'])) {
            //    unset($contactListData[$key]);
            //} else {
                $item           = array();
                $item['id']     = $recp['recipient_id'];
                $item['text']   = self::$t->translate($recp['title']);

                //if ($recp['qm_type'] == 'link') {
                //    $item['href'] = $recp['link'];
                //} else {
                $item['link']   = $this->getBasePath() . "send/" . $recp['recipient_id'] . "?popup";
                //}
                //$image = explode("/", $recp['image']);
                $item['icon'] = $recp['image'];
                $item['color'] = $recp['image_type'];

                if (!is_array($contactList[$recp['group_id']])) {
                    $contactList[$recp['group_id']] = array();
                }
                $contactList[$recp['group_id']][$recp['recipient_id']] = $item;
            //}
        }
        return $contactList;

    }

    /**
     * Send Page Action
     *
     * @url GET /inbox
     */
    public function inboxPage($recipient_id) {
        $this->putCss("css/pages/inbox");
        $this->putScript("scripts/inbox");

        $this->putModuleScript("inbox");

        $this->display("inbox.tpl");
    }

    /**
     * Send Page Action
     *
     * @url GET /send/:recipient_id
     * @url POST /send/:recipient_id
     * @deprecated 3.0.14
     */
    public function sendPage($recipient_id) {
        $current_user = $this -> getCurrentUser(true);
        /*
        //$smarty -> assign("T_MODULE_CURRENT_USER" , $current_user ->getType());
        $form = new HTML_QuickForm("mod_messages_form", "post", $_SERVER['REQUEST_URI'], "", "id = 'mod_messages_form'");

        $form -> addElement('hidden', 'recipients', $recipient_id);

        //$form -> addElement('hidden', 'email', $_GET['email']);
        //$form -> addElement('hidden', 'name', $_GET['name']);

        $form -> addElement('text', 'subject', self::$t->translate("Subject"), 'class = "form-control placeholder-no-fix"');
        $form -> addElement('textarea', 'body', self::$t->translate("Message Body"), 'class = "wysihtml5 form-control placeholder-no-fix"');
        //$form -> addElement('checkbox', 'email', _SENDASEMAILALSO, null, 'class = "inputCheckBox"');
        //$form -> addRule('subject',   _THEFIELD.' "'._SUBJECT.'" '._ISMANDATORY,   'required', null, 'client');
        //$form -> addRule('recipients',   _THEFIELD.' "'._RECIPIENTS.'" '._ISMANDATORY,   'required', null, 'client');
        $form -> addElement('file', 'attachment[0]', self::$t->translate("Attachment"), null, 'class = "form-control placeholder-no-fix"');
        //$form -> addElement('file', 'attachment[1]', self::$t->translate("Attachment"), null, 'class = "form-control placeholder-no-fix"');
        $form -> addElement('submit', 'submit_mail', _SEND);
        */
        //$contactList = $this->getUserContactList();
        /*
        if ($form -> isSubmitted() && $form -> validate()) {
            $values = $form -> exportValues();

            $userGroupsItemModel = $this->model("user/groups/item");

            if (is_numeric($values['recipients'])) {
                $recipients = $userGroupsItemModel->getUsersInGroup($values['recipients']);
                if (count($recipients) > 0) {

                    foreach ($recipients as $recipient) {
                        $user_recipients[] = $recipient['login'];
                        $mail_recipients[] = array(
                            'login' => $recipient['login'],
                            'email' => $recipient['email'],
                            'fullname'  => $recipient['name'] . ' ' . $recipient['surname']
                        );
                    }
                }
            } else {
            }

            // ALWAYS SEND A E-MAIL
            $values['send_email'] = 1;

            //$list = implode(",",$mail_recipients);

            if (count($user_recipients) == 0) {
                $message      = self::$t->translate("No recipients defined");
                $message_type = 'failure';
            } else {
                $pm = new sC_PersonalMessage($current_user->user['login'], $user_recipients, $values['subject'], $values['body'], true);

                $attachFile = array();

                if (count($_POST['attachment'])) {
                    $user = $this->getCurrentUser();

                    //if (!is_dir($user['directory'])) {
                    //    mkdir($user['directory']);
                    //}

                    $fileWrapper = $this->helper("file/wrapper");

                    foreach($_POST['attachment'] as $file_name) {
                        $filesystem = new FileSystemTree($user['directory']);

                        $file = array(
                            'error'     => 0,
                            'size'      => $fileWrapper->getFilesize($user['login'], $file_name),
                            'name'      => $file_name,
                            'tmp_name'  => $fileWrapper->getFullPath($user['login'], $file_name)
                        );

                        $uploadedFile = $filesystem -> uploadFile($file, $user_dir, 0, true);

                        $pm->sender_attachment_fileId =  $uploadedFile['id'];
                        $pm->setAttachment();
                    }

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
                        $email_body = self::$t->translate(
                            sprintf("Mensagem de: %s <strong>(%s)</strong> &lt;%s&gt;", $current_user->user['name'] . ' ' . $current_user->user['surname'], $current_user->user['login'], $current_user->user['email']) .
                                "\n<br />" .
                                "Matriculado nos seguintes cursos/disciplinas:\n<br />" .
                                sprintf("<ul>%s</ul>", implode("", $courseArray)) .
                                "Corpo da Mensagem:\n<br /><br />" .
                            $values['body']
                        );

                        $result = $result && sC_mail(
                            // CHECK IF IS NECESSARY TO CHANGE DE SENDER E-MAIL
                            //sprintf("%s <%s>", $current_user->user['name'] . ' ' . $current_user->user['surname'], $current_user->user['email']), // EMAIL FROM => COMMA SEP LIST
                            null,
                            //sprintf("%s <%s>", $mail['fullname'], $mail['email']), // EMAIL TO => COMMA SEP LIST
                            sprintf("%s", $mail['email']), // EMAIL TO => COMMA SEP LIST
                            $values['subject'], // EMAIL SUBJECT
                            $email_body,    // EMAIL BODY
                            $attachFile,        // ATTACHMENTS
                            false,              // ONLY TEXT ?
                            false               // SEND AS BCC ?
                        );
                    }
                }

                if ($result && $pm -> send($values['email'])) { // DO NOT SEND EMAIL
                    $message      = self::$t->translate("Your message was successfully sent.");
                    $message_type = 'success';
                } else {
                    $message      = $pm -> errorMessage;
                    $message_type = 'danger';
                }
            }
            // RETURN A JSON TO CLIENT
            return array(
                'message'       => $message,
                'message_type'  => $message_type
            );
            exit;
        }
        */
       /*
        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);                  //Create a smarty renderer
        $renderer -> setRequiredTemplate (
             '{$html}{if $required}
            &nbsp;<span class = "formRequired">*</span>
            {/if}');
        $form -> setJsWarnings(_BEFOREJAVASCRIPTERROR, _AFTERJAVASCRIPTERROR);          //Set javascript error messages
        $form -> setRequiredNote(_REQUIREDNOTE);
        $form -> accept($renderer);                                                     //Assign this form to the renderer, so that corresponding template code is created

        $this->putItem('MOD_MESSAGES_FORM', $renderer -> toArray());
        $this->putItem("MESSAGE_MAIL" , $message);
        $this->putItem("MESSAGE_MAIL_TYPE" , $message_type);
        */

        $this->display("send.form.tpl");
    }

    /**
     * Attach File Action
     *
     * @url POST /attach_file
     * @deprecated 3.0.14
     */
    public function attachFile($recipient_id) {
        if (count($_FILES) > 0) {
            $fileWrapper = $this->helper("file/wrapper");

            $user = $this->getCurrentUser();
            $result = array();
            foreach($_FILES as $index => $file) {
                /*
                array(5) {
                ["name"]=> string(14) "2014-11-28.txt"
                ["type"]=> string(10) "text/plain"
                ["tmp_name"]=> string(14) "/tmp/phpsYLhov"
                ["error"]=> int(0)
                ["size"]=> int(10423)
                }
                */

                $result = $fileWrapper->uploadObjectByPath(
                    $user['login'],
                    $file['name'],
                    $file['tmp_name']
                );

            }
            return $result;
        }

        return $this->invalidRequestError();
    }





    /**
     * Send Page Action
     *
     * @url GET /data/folders
     * @deprecated
     */
    public function dataFoldersAction() {
        $currentUser = $this->getCurrentUser();
        $currentFolder = $this->getDefaultFolder($currentUser);

        $folders = sC_PersonalMessage :: getUserFolders($currentUser['login']);

        return array_values($folders);
    }
    /**
     * Send Page Action
     *
     * @url GET /data/messages
     * @deprecated
     */
    public function dataAction() {
        $currentUser = $this->getCurrentUser();
        $currentFolder = $this->getDefaultFolder($currentUser);

        $folders = sC_PersonalMessage :: getUserFolders($currentUser['login']);

        $foldersID = array_keys($folders);

        $folderMessages = sC_getTableData(
            "f_personal_messages",
            "*",
            sprintf("users_LOGIN='%s' and f_folders_ID IN (%s) ", $currentUser['login'], implode(",", $foldersID)),
            "priority desc, viewed,timestamp desc"
        );

        /*

        if (isset($_GET['flag']) && sC_checkParameter($_GET['flag'], 'id')) {

            sC_updateTableData("f_personal_messages", array('priority' => 1), "id=".$_GET['flag']);

        } elseif (isset($_GET['unflag']) && sC_checkParameter($_GET['unflag'], 'id')) {

            sC_updateTableData("f_personal_messages", array('priority' => 0), "id=".$_GET['unflag']);

        } elseif (isset($_GET['read']) && sC_checkParameter($_GET['read'], 'id')) {

            sC_updateTableData("f_personal_messages", array('viewed' => 1), "id=".$_GET['read']);

        } elseif (isset($_GET['unread']) && sC_checkParameter($_GET['unread'], 'id')) {

            sC_updateTableData("f_personal_messages", array('viewed' => 0), "id=".$_GET['unread']);

        }

        isset($_GET['page']) && sC_checkParameter($_GET['page'], 'uint') ? $page = $_GET['page'] : $page = 1;

        $p_messages_per_page = sC_getTableData("f_configuration", "value", "name='personal_messages_per_page'");

        $p_messages_per_page[0]['value'] ? $p_messages_per_page = $p_messages_per_page[0]['value'] : $p_messages_per_page = 20;

        */
        // Create ajax enabled table for employees
        isset($_GET['limit']) && sC_checkParameter($_GET['limit'], 'uint') ? $limit = $_GET['limit'] : $limit = G_DEFAULT_TABLE_SIZE;
        if (isset($_GET['sort']) && sC_checkParameter($_GET['sort'], 'text')) {
            $sort = $_GET['sort'];
            isset($_GET['order']) && $_GET['order'] == 'desc' ? $order = 'desc' : $order = 'asc';
        } else {
            $sort = 'priority';
        }
        $folderMessages = sC_multiSort($folderMessages, $_GET['sort'], $order);
        if (isset($_GET['filter'])) {
            $folderMessages = sC_filterData($folderMessages , $_GET['filter']);
        }
        //$smarty -> assign("T_MESSAGES_SIZE", sizeof($folderMessages));
        if (isset($_GET['limit']) && sC_checkParameter($_GET['limit'], 'int')) {
            isset($_GET['offset']) && sC_checkParameter($_GET['offset'], 'int') ? $offset = $_GET['offset'] : $offset = 0;
            $folderMessages = array_slice($folderMessages, $offset, $limit);
        }
        // Keep only the first characters of the recipient's list
        //$subject_chars   = 50;
        //$recipient_chars = 30;
        /*

            foreach ($messages as $key => $p_message) {

                if (strlen($p_message['title']) > ($subject_chars - (($p_message['attachments'])? 4:0))) {

                    $messages[$key]['title'] = mb_substr($p_message['title'],0,$subject_chars - (($p_message['attachments'])? 4:0) - 3) . "...";

                }

                if (strlen($p_message['recipient']) > $recipient_chars) {

                    $messages[$key]['recipient'] = mb_substr($p_message['recipient'],0,$recipient_chars - 3) . "...";

                }

            }

        */
        foreach ($folderMessages as $key => $value) {
            $recipients = explode(",", $folderMessages[$key]['recipient']);
            foreach ($recipients as $k => $login) {
                $recipients[$k] = formatLogin(trim($login));
            }
            $folderMessages[$key]['recipient'] = implode(", ", $recipients);
        }
        //$smarty -> assign("T_MESSAGES", $folderMessages);
        return $folderMessages;
    }

    /* MODEL FUNCTIONS */
    /**
     * @deprecated
     */
    public function getTotalUnviewed($currentUser = null, $folder = null)
    {
        if (is_null($currentUser)) {
            $currentUser = $this->getCurrentUser(false);
        }
        if (is_null($folder)) {
            $folder = $this->getDefaultFolder($currentUser);
        }

        /** @todo CHECK FOR TRANSLATION MODE */
        $folderMessages = $this->_countTableData(
            "f_personal_messages",
            "*",
            "users_LOGIN = '".$currentUser['login']."' AND f_folders_ID=".$folder . " AND viewed = 0",
            "priority desc, viewed,timestamp desc"
        );
        return $folderMessages[0]['count'];
    }
    /**
     * @deprecated
     */
    public function getUnviewedMessages($foldersID = null) {
        $currentUser = $this->getCurrentUser();
        $currentFolder = $this->getDefaultFolder($currentUser);

        if (is_null($foldersID)) {
            $folders = sC_PersonalMessage :: getUserFolders($currentUser['login']);
            $foldersID = array_keys($folders);
        }

        $folderMessages = sC_getTableData(
            "f_personal_messages",
            "*",
            sprintf("users_LOGIN='%s' and f_folders_ID IN (%s) AND viewed = 0", $currentUser['login'], implode(",", $foldersID)),
            "priority desc, viewed,timestamp desc"
        );

        // Create ajax enabled table for employees
        isset($_GET['limit']) && sC_checkParameter($_GET['limit'], 'uint') ? $limit = $_GET['limit'] : $limit = G_DEFAULT_TABLE_SIZE;
        if (isset($_GET['sort']) && sC_checkParameter($_GET['sort'], 'text')) {
            $sort = $_GET['sort'];
            isset($_GET['order']) && $_GET['order'] == 'desc' ? $order = 'desc' : $order = 'asc';
        } else {
            $sort = 'priority';
        }
        $folderMessages = sC_multiSort($folderMessages, $_GET['sort'], $order);
        if (isset($_GET['filter'])) {
            $folderMessages = sC_filterData($folderMessages , $_GET['filter']);
        }
        //$smarty -> assign("T_MESSAGES_SIZE", sizeof($folderMessages));
        if (isset($_GET['limit']) && sC_checkParameter($_GET['limit'], 'int')) {
            isset($_GET['offset']) && sC_checkParameter($_GET['offset'], 'int') ? $offset = $_GET['offset'] : $offset = 0;
            $folderMessages = array_slice($folderMessages, $offset, $limit);
        }

        foreach ($folderMessages as $key => $value) {
            $recipients = explode(",", $folderMessages[$key]['recipient']);
            foreach ($recipients as $k => $login) {
                $recipients[$k] = formatLogin(trim($login));
            }
            $folderMessages[$key]['recipient'] = implode(", ", $recipients);
        }
        //$smarty -> assign("T_MESSAGES", $folderMessages);
        return $folderMessages;
    }
    /**
     * @deprecated
     */
    public function getDefaultFolder($currentUser) {
        $folders = sC_PersonalMessage :: getUserFolders($currentUser['login']);
        reset($folders);
        return $currentFolder = key($folders); //key($folders) is the id of the first folder, which is always the Incoming
    }

}
