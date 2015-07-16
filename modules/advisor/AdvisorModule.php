<?php
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class AdvisorModule extends SysclassModule implements ISummarizable, IWidgetContainer
{
    public function getSummary() {
        $data = array(1); // FAKE, PUT HERE DUE PAYMENTS

        return array(
            'type'  => 'primary',
            'count' => $data[0],
            'text'  => self::$t->translate('Scheduled Meetings'),
            'link'  => array(
                'text'  => self::$t->translate('View'),
                'link'  => $this->getBasePath() . 'all'
            )
        );
    }
    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('advisor.chat', $widgetsIndexes) || in_array('advisor.schedule', $widgetsIndexes)) {
        	return array(
        		'advisor.chat' => array(
                    'id'        => 'advisor-widget',
       				'template'	=> $this->template("widgets/chat"),
                    'panel'     => true
        		),
                'advisor.schedule' => array(
                    'id'        => 'advisor-widget-schedule',
                    'template'  => $this->template("widgets/schedule"),
                    'panel'     => true
                )
        	);
        }
        return false;
    }
}
