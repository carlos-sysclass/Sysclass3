<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */

class GradesModule extends SysclassModule implements ISummarizable
{
    public function getSummary() {
        $data = array(12); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => self::$t->translate('Grades'),
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
}
