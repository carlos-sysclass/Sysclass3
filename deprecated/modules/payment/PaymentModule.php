<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class PaymentModule extends SysclassModule
{
    /*
    // CREATE FUNCTION HERE
    public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {

            //$total = $this->getTotalUnviewed();
            $total = 0;

            $currentUser = $this->getCurrentUser();
            //$currentFolder = $this->getDefaultFolder($currentUser);

            //$messages = $this->getUnviewedMessages(array($currentFolder));

            //$items = array(1);
            if ($total > 0) {


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
        }
        return false;
    }
    */
}
