<?php 
class PaymentModule extends SysclassModule implements ISummarizable, ILinkable
{
    public function getSummary() {
        $data = array(1); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'danger',
            'count' => count($data),
            'text'  => self::$t->translate('Pay Due'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }
/*
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('news.latest', $widgetsIndexes)) {
            $this->putModuleScript("news");
            
            return array(
                'news.latest' => array(
                    'type'      => 'news', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
                    'id'        => 'news-widget',
                    'title'     => self::$t->translate('Announcements'),
                    'template'  => $this->template("news.widget"),
                    'icon'      => 'bell',
                    'box'       => 'dark-blue',
                    'tools'		=> array(
                        'search'        => true,
                    	'reload'	    => 'javascript:void(0);',
                        'collapse'      => true,
                        'fullscreen'    => true
                    )
                )
            );
        }
    }
*/
    public function getLinks() {
        $data = array(1); // FAKE, PUT HERE DUE PAYMENTS
        //if ($this->getCurrentUser(true)->getType() == 'administrator') {
            return array(
                'payment' => array(
                    array(
                        'count' => count($data),
                        'text'  => self::$t->translate('Payments'),
                        'icon'  => 'icon-money',
                        'link'  => $this->getBasePath() . 'all'
                    )
                )
            );
        //}
    }
}
