<?php 
class NewsModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets() {
    	$this->putScript("plugins/jquery-bootpag/jquery.bootpag");
        return array(
            'news.latest' => array(
                'title'     => self::$t->translate('News Feed'),
                'template'  => $this->template("lastest"),
                'icon'      => 'bell',
                'tools'		=> array(
                	'reload'	=> true
                ),
                'box'       => 'yellow'
            )
        );
    }
}
