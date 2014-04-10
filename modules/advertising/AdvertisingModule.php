<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * Manage and control the advertising system strategy
 * @package Sysclass\Modules
 */
class AdvertisingModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array()) {
    	//$this->putScript("plugins/jquery-bootpag/jquery.bootpag");
        return array(
            'advertising' => array(
                //'title'     => self::$t->translate('Advertising'),
                'id'        => 'advertising-panel',
                'template'  => $this->template("advertising.block"),
                'panel'     => true
            )
        );
    }
}
