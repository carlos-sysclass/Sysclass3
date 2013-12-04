<?php 
class AdvertisingModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets() {
    	$this->putScript("plugins/jquery-bootpag/jquery.bootpag");
        return array(
            'advertising' => array(
                //'title'     => self::$t->translate('Advertising'),
                'template'  => $this->template("advertising.block"),
                'panel'     => true
            )
        );
    }
}
