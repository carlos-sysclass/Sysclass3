<?php 
class AdvisorModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array()) {
        if (in_array('advisor.overview', $widgetsIndexes)) {
        	return array(
        		'advisor.overview' => array(
       				'template'	=> $this->template("overview.widget"),
                    'panel'     => true
        		)
        	);
        }
    }
}
