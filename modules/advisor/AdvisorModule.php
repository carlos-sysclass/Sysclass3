<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class AdvisorModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('advisor.overview', $widgetsIndexes)) {
        	return array(
        		'advisor.overview' => array(
                    'id'        => 'advisor-widget',
       				'template'	=> $this->template("overview.widget"),
                    'panel'     => true
        		)
        	);
        }
    }
}
