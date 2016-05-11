<?php
namespace Sysclass\Modules\Notification;

use Sysclass\Models\Notifications\User as UserNotification;

/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/notification")
 */

class NotificationModule extends \SysclassModule implements \IWidgetContainer
{
    
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('notification.lastest', $widgetsIndexes)) {
            $currentUser    = $this->user;

            $notifications = UserNotification::find(array(
                'conditions' => "user_id = ?0 AND (viewed = 0 OR stick = 1)",
                'bind' => array($this->user->id),
                'order' => 'timestamp DESC'
            ));

            $data = $notifications->toArray();

            //$this->putScript('plugins/jquery/jquery.scrollbox');
            $this->putModuleScript("widget");

            /**
             * @todo  Merge with system notifications
             */

            return array(
                'notification.lastest' => array(
                    'id'        => 'notification-panel',
                    'type'      => 'notification',
                    //'title'   => 'User Overview',
                    'template'  => $this->template("widgets/lastest"),
                    'panel'     => '',
                    'body'      => 'no-padding-bottom',
                    'data'      => $data
                    //'box'       => 'blue'
                )
            );
        }
    }


}
?>