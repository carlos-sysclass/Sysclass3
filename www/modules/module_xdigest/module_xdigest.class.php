<?php
class module_xdigest extends MagesterExtendedModule {
	
	public function getName() {
        return "XDIGEST";
    }
	
    public function getPermittedRoles() {
        return array("administrator","professor", "student");
    }
	
	public function getCenterLinkInfo() {
		$currentUser = $this -> getCurrentUser(); 
        $xuserModule = $this->loadModule("xuser");
              
		if ($xuserModule->getExtendedTypeID($currentUser) == "administrator") {
			return array('title' => _MODULE_XDIGEST_NAME,
                         'image' => $this -> moduleBaseDir . 'images/xdigest.png',
                         'link'  => $this -> moduleBaseUrl,
						 'class' => 'xdigest'
            );
        }	
	}
	
	
	public function lastNewsPostForumAction() {
	 	
	}
	
	
	
	
	
	
	public function getSmartyTpl() {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_XDIGEST_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_XDIGEST_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_XDIGEST_BASELINK" , $this -> moduleBaseLink);
  		//var_dump($this -> moduleBaseLink);
  		//exit;
       return $this -> moduleBaseDir . "templates/default.tpl";
    }
}
