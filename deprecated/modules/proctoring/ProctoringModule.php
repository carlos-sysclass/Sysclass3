<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class ProctoringModule extends SysclassModule implements IWidgetContainer
{

    public function getWidgets($widgetsIndexes = array(), $caller = null) {
        if (in_array('proctoring.overview', $widgetsIndexes)) {
            $data = array();
            // GET USER POLO
            $currentUser = $this->getCurrentUser(true);

            $data['polo'] = $currentUser->getUserPolo();
            //var_dump($data['polo']);

        	return array(
        		'proctoring.overview' => array(
       				//'title' 	=> 'User Overview',
       				'template'	=> $this->template("overview.widget"),
                    'panel'     => true,
                    //'box'       => 'blue'
                    'data'      => $data
        		)
        	);
        }
    }
}
