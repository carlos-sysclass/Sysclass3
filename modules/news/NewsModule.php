<?php 
class NewsModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets() {
        return array(
            'news.latest' => array(
                'title'     => self::$t->translate('News Feed'),
                'template'  => $this->template("lastest"),
                'icon'      => 'bell'
                //'box'       => 'blue',
            )
        );
    }
}
