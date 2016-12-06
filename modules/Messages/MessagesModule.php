<?php
namespace Sysclass\Modules\Messages;
/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Users\Group as UserGroup,
    Sysclass\Models\Messages\Group as MessageGroup,
    Sysclass\Models\Messages\Message,
    Sysclass\Models\Messages\GroupReceiver,
    Sysclass\Models\Messages\UserReceiver,
    Sysclass\Models\Acl\Role;
    
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/messages")
 */
class MessagesModule extends \SysclassModule implements /* \ISummarizable, */ \IBlockProvider, /*\ISectionMenu, */ \IWidgetContainer
{
    // ISummarizable
    /*
    public function getSummary() {
        //$data = $this->dataAction();
        //return false;
        //$total = $this->getTotalUnviewed();
        return array(
            'type'  => 'primary',
            //'count' => count($data),
            'count' => 0,
            'text'  => $this->translate->translate('Emails'),
            'link'  => array(
                'text'  => $this->translate->translate('View'),
                'link'  => $this->getBasePath() . 'inbox'
            )
        );
    }
    */
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

                $teacherRole = Role::findFirstByName('Teacher');
                $users = $teacherRole->getAllUsers();

                $this->putItem("USER_RECEIVERS", $users);

                $self->putModuleScript("dialogs.messages.send");
                $self->putSectionTemplate("dialogs", "dialogs/send");

                return true;
            }
        );
    }

    // IWidgetContainer
    public function getWidgets($widgetsIndexes = array(), $caller = null) {
        /*
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

            //$groups = $this->getMessageGroups();

            $recipients = $this->getMessageReceivers();

            $groupsRS = MessageGroup::find();

            foreach($groupsRS as $group) {
                $widgets[$widgetsNames[$group->id]] = array(
                    //'title'     => $this->translate->translate($group['name']),
                    'id'        => 'advisor-chat-widget-' . $group->id,
                    'title'    => $this->translate->translate($group->name),
                    'template'  => $this->template("contact-list.widget"),
                    'icon'      => " " . $group->icon,
                    'box'       => 'dark-blue messages-panel tabbable',    
                    'body'      => false,
                    'data'      => $recipients[$group->id]
                );
            }

            return $widgets;
        }
        */
        if (in_array("messages.inbox", $widgetsIndexes)) {
            $this->putCss("css/reset");

            $this->putComponent("select2");
            $this->putComponent("datatables");
            $this->putComponent("jquery-jscrollpane");

            $this->putModuleScript("portlet.messages");
            
            $this->putBlock("messages.send.dialog");

            $block_context = $this->getConfig("widgets\\messages.inbox\context");
            $this->putItem("messages_block_context", $block_context);
                /*
                $receiverGroups = UserGroup::findConnectBy(array(
                    'conditions' => 'behaviour_allow_messages > 0 AND active = 1',
                    'connect_by' => 'behaviour_allow_messages'
                ));
                */
                $receiverGroups = UserGroup::find([
                    'conditions' => 'behaviour_allow_messages > 0 AND active = 1'
                ]);

                $receivers = [];

                foreach($receiverGroups as $receiverModel) {
                    $receiverModel->translate();

                    $receivers[] = $receiverModel->toArray();
                }

                $this->putItem("messages_group_receivers", $receivers);
                /*
                $messageGroupsRS = MessageGroup::find();
                $messageGroups = array();
                foreach($messageGroupsRS as $messageGroup) {
                    $messageGroups[$messageGroup->id] = $messageGroup->name;
                }
                $this->putItem("message_groups", $messageGroups);
                */

            return array(
                "messages.inbox" => array(
                    //'title'     => $this->translate->translate($group['name']),
                    'type'      => 'messages', // USED BY JS SUBMODULE 
                    'id'        => 'messages-widget',
                    'template'  => $this->template("widgets/inbox"),
                    'box'       => 'dark-blue tabbable tabbable-left',
                    'panel'     => true,
                    'body'      => 'no-padding'
                )
            );
        }
        return false;

    }

    /*
    public function addItemRequest($model)
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
                        $receiverModel = new GroupReceivers();
                        $receiverModel->message_id = $itemModel->id;
                        $receiverModel->group_id = $group['id'];
                        $receiverModel->save();
                    }

                    foreach($data['user_id'] as $user) {
                        $receiverModel = new UserReceiver();
                        $receiverModel->message_id = $itemModel->id;
                        $receiverModel->user_id = $user['id'];
                        $receiverModel->save();
                    }

                }

                return $this->createAdviseResponse(
                    $this->translate->translate("Message created. You can check the message in your inbox."),
                    "success"
                );
            } else {
                $response = $this->createAdviseResponse($this->translate->translate("A problem ocurred when tried to save you data. Please try again."), "warning");
                return $response;
            }
        } else {
            return $this->notAuthenticatedError();
        }
    }
    */
    public function beforeModelCreate($evt, $model, $data) {
        //var_dump($model->toArray(), $data);
        //exit;
    }
    public function afterModelCreate($evt, $model, $data) {
        if (array_key_exists('group_id', $data) && is_array($data['group_id']) ) {
            //UsersGroups::find("user_id = {$userModel->id}")->delete();
            foreach($data['group_id'] as $group) {
                $receiverModel = new GroupReceiver();
                $receiverModel->message_id = $model->id;
                $receiverModel->group_id = $group['id'];
                $receiverModel->save();
            }
        }
        if (array_key_exists('user_id', $data) && is_array($data['user_id']) ) {
            foreach($data['user_id'] as $user) {
                $receiverModel = new UserReceiver();
                $receiverModel->message_id = $model->id;
                $receiverModel->user_id = $user['id'];
                $receiverModel->save();
            }
        }

        /**
          * @todo TRIGGER EVENT TO SENT THE EMAILL OR TO CREATE THE QUEUE FOR THE EMAIL OVERVIEW
         */
        
        return true;
    }


    /**
     * [getMessageGroups description]
     * @deprecated 3.0.14
     */
    /*
    protected function getMessageGroups() {
        return $this->_getTableData(
            "mod_messages_groups",
            "id as id, name, icon",
            "",
            "id ASC"
        );
    }
    */
    /**
     * [getMessageReceivers description]
     * @param  [type] $group_id [description]
     * @return [type]           [description]
     * @deprecated 3.0.14
     */
    /*
    protected function getMessageReceivers($group_id = null) {
        if (!($user instanceof MagesterUser)) {
            $user = $this->getCurrentUser();
        }
        //$xentifyModule = $this->loadModule("xentify");
        $receiversCollection = $this->model("messages/receivers/collection");

        $contactListData = $receiversCollection->getItems();


        $contactList = array();

        foreach ($contactListData as $key => $recp) {

            //if (!$xentifyModule->isUserInScope($user, $recp['xscope_id'], $recp['xentify_id'])) {
            //    unset($contactListData[$key]);
            //} else {
                $item           = array();
                $item['id']     = $recp['recipient_id'];
                $item['text']   = $this->translate->translate($recp['title']);

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
    */
    /**
     * Send Page Action
     *
     * @Get("/inbox")
     */
    public function inboxPage($recipient_id) {
        $this->putCss("css/pages/inbox");
        $this->putModuleScript("inbox");

        $this->putComponent("select2");

        $this->putModuleScript("inbox");

        $this->display("inbox.tpl");
    }

    /**
     * Attach File Action
     *
     * @url POST /attach_file
     * @deprecated 3.0.14
     */
    /*
    public function attachFile($recipient_id) {
        if (count($_FILES) > 0) {
            $fileWrapper = $this->helper("file/wrapper");

            $user = $this->getCurrentUser();
            $result = array();
            foreach($_FILES as $index => $file) {
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
    */
}
