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

        $leftbar_data = $this->getConfig("widgets\ads.leftbar.banner\context");
        $rightbar_data = $this->getConfig("widgets\ads.rightbar.banner\context");

        return array(
            'ads.leftbar.banner' => array(
                'id'        => 'advertising-leftbar-banner',
                'template'  => $this->template("widgets/leftbar.banner"),
                'data'      => $leftbar_data
            ),
            'ads.rightbar.banner' => array(
                'id'        => 'advertising-rightbar-banner',
                'template'  => $this->template("widgets/rightbar.banner"),
                'data'      => $rightbar_data
            ),
            'advertising' => array(
                //'title'     => self::$t->translate('Advertising'),
                'id'        => 'advertising-panel',
                'template'  => $this->template("advertising.block"),
                'panel'     => true
            )
        );
    }
}
