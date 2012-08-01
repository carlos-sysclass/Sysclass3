<?php
class module_xlivechat extends MagesterExtendedModule {
	
	const GET_LIST_CHAT				= 'list_chat_suporte';
	
    public function getName() {
        return "XLIVECHAT";
    }
	
    public function getPermittedRoles() {
        return array("administrator","professor", "student");
    }
	/*
    public function addScripts() {
	//	return array("tinyeditor/tinyeditor");
		
	}
    */
    
    public function onInstall() {
        eF_executeNew("drop table if exists module_xlivechat_messagechat");
        $a = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_xlivechat_messagechat` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `from` varchar(255) NOT NULL DEFAULT '',
						  `to` varchar(255) NOT NULL DEFAULT '',
						  `message` text NOT NULL,
						  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
						  `recd` int(10) unsigned NOT NULL DEFAULT '0',
						  `queue` int(11) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`id`),
						  KEY `to` (`to`),
						  KEY `from` (`from`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			");
        
   
        return $a;
    }
    
 	public function onUninstall() {
        $a = eF_executeNew("drop table module_xlivechat_messagechat;");     			
        return $a;
    }
    
    
    
 	public function getModuleJS() {
		//if (strpos(decryptUrl($_SERVER['REQUEST_URI']), $this -> moduleBaseUrl) !== false) {
			//return $this->moduleBaseDir."js/xlivechat.js";
			return $this->moduleBaseDir."js/xlivechat.js";
			//return $this->moduleBaseDir."js/jquery.js";
		//}
		
	}
	

    public function getModuleCSS() {
    	 return $this->moduleBaseDir."css/chat.css";
        //return $this->moduleBaseDir."css/screen.css";
    }

	public function getCenterLinkInfo() {
		$currentUser = $this -> getCurrentUser(); 
        $xuserModule = $this->loadModule("xuser");

        
		if ($xuserModule->getExtendedTypeID($currentUser) == "administrator") {
			return array('title' => _MODULE_XLIVECHAT_NAME,
                         'image' => $this -> moduleBaseDir . 'images/xrequest.png',
                         'link'  => $this -> moduleBaseUrl,
						 'class' => 'xlivechat'
            );
        }
		   
        
	}
   	
	
	
	public function getDefaultAction() {
	    	$smarty = $this->getSmartyVar();

	    	
	       	if ($this->getCurrentUser()->getType() == 'student') {
				$smarty -> assign("T_TYPE_USER", "student");	
				return self::GET_LIST_CHAT;
				
			} 
			if ($this->getCurrentUser()->getType() == 'administrator') {
				$smarty -> assign("T_TYPE_USER", "administrator");	
				return self::GET_LIST_CHAT;
				
			}
	}

	public function listChatSuporteAction() {
	 	$smarty = $this -> getSmartyVar();
	 	$listUser = eF_getTableData("module_xlivechat_messagechat", "*", "1","", "`from`");
		$smarty -> assign("T_LIST_USER", $listUser );
		
		
	     
		
	 	return;
	}
	
	public function xlivechatMessagensAction($userqueue) {
		$userqueue = $_POST['usersuport'];
	 	$smarty = $this -> getSmartyVar();
	 	$currentUser = $this->getCurrentUser()->user;
		$listMessagens = eF_getTableData("module_xlivechat_messagechat", "*", "`from` = '$userqueue'"); 							 //"`from` = '$userLogin' AND `from` = 'suporteult' AND `from` = 'suporteult'");;
		$smarty -> assign("T_LIST_MESSAGECHAT", $listMessagens );
		$smarty -> assign("T_LIST_TEXT_ACTION", $userqueue );
		echo $smarty->fetch($this->moduleBaseDir . "templates/includes/xlivechat_messagens.tpl");
		exit;
	}


   public function getSmartyTpl() {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_XLIVECHAT_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_XLIVECHAT_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_XLIVECHAT_BASELINK" , $this -> moduleBaseLink);
  		//var_dump($this -> moduleBaseLink);
  		//exit;
       return $this -> moduleBaseDir . "templates/default.tpl";
    }
}
?>
