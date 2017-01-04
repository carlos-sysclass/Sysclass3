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
    Sysclass\Models\Acl\Role,
    Sysclass\Services\MessageBus\INotifyable,
    Sysclass\Collections\MessageBus\Event;
    
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/messages")
 */
class MessagesModule extends \SysclassModule implements \IBlockProvider, \IWidgetContainer, INotifyable
{
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
            //$this->putComponent("bootstrap-confirmation");
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

    /* INotifyable */
    public function getAllActions() {

    }

    public function processNotification($action, Event $event) {
        switch($action) {
            case "inform-receiver" : {
                // SEND EMAIL PASSWORD RESET 
                $data = $event->data;


                $message = Message::findFirstById($data['id']);

                if ($message) {

                    $users = $message->getUsers();

                    $from = $message->getFrom();

                    foreach($users as $user) {
                        $status = $this->mail->send(
                            $user->email, 
                            "Um nova mensagem recebida. Email automático, não é necessário responder.",
                            "email/" . $this->sysconfig->deploy->environment . "/messages-created.email",
                            true,
                            [
                                'user' => $user,
                                'message' => $message,
                                'from' => $from
                            ],
                            [
                                $from->email => $from->name . " " . $from->surname
                            ]
                        );
                        /*
                        $this->notification->createForUser(
                            $receiver,
                            'An user enrolled a program.',
                            'activity',
                            array(
                                'text' => "View",
                                'link' => $this->getBasePath() . "edit/" . $data['enroll_id'] . '#tab_1_3'
                            ),
                            false,
                            "ENROLL:" . "E" . $data['enroll_id'] . "U" . $user->id . "P" . $program->id
                        );
                        */
                    }

                    return array(
                        'status' => true
                    );
                }
                return array(
                    'status' => false,
                    'unqueue' => true
                );
            }
        }
    }

    public function afterModelCreate($evt, $model, $data, $model_id) {
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

        if ($data['model_id'] == "me") {
            $this->eventsManager->fire("messages:created", $this, $model->toArray());
        }
        
        return true;
    }

    protected function getDatatableItemOptions($item, $model = 'me') {
        $options = parent::getDatatableItemOptions($item, $model);
        $model_info = $this->model_info[$model];

        $trashAllowed = $this->isResourceAllowed("trash", $model_info);

        $options = array();

        $options['view']  = array(
            'link'  => 'javascript:void(0)',
            'icon'  => 'fa fa-envelope',
            'class' => 'btn-sm btn-primary tooltips',
            'attrs' => array(
               'data-original-title' => $this->translate->translate('View')
            )
        );

        if ($trashAllowed) {
            $options['remove']  = array(
                'icon'  => 'fa fa-trash',
                'class' => 'btn-sm btn-danger tooltips',
                'attrs' => array(
                    'data-original-title' => $this->translate->translate('Remove')
                )
            );
        }
        return $options;
    }
}
