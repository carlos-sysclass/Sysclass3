<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class PaymentModule extends SysclassModule implements ISectionMenu
{
    // CREATE FUNCTION HERE
    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {

            //$total = $this->getTotalUnviewed();
            $total = 0;

            $currentUser = $this->getCurrentUser();
            //$currentFolder = $this->getDefaultFolder($currentUser);
            
            //$messages = $this->getUnviewedMessages(array($currentFolder));

            //$items = array(1);
            /*
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
            */
            $menuItem = array(
                'icon'      => 'money',
                'notif'     => $total,
                'text'      => self::$t->translate('You have %s due payment', $total),
                'external'  => array(
                    'link'  => $this->getBasePath(),
                    'text'  => self::$t->translate('See my statement')
                ),
                'link'  => array(
                    'link'  => $this->getBasePath(),
                    'text'  => self::$t->translate('Payments')
                ),
                'type'      => 'notification',
                'items'     => $items,
                'extended'  => true
            );

            return $menuItem;
        }
        return false;
    }
}
