<?php
class module_xrequest extends MagesterExtendedModule {
	
	const GET_XREQUEST				= 'get_xrequest';
	const GET_XREQUEST_PROTOCOL		= 'get_xrequest_protocol';
	const GET_XREQUEST_SOURCE		= 'get_xrequest_source';
	const ADD_XREQUEST				= 'add_xrequest';
	const NEW_XREQUEST				= 'new_xrequest';
	const STATUS_XREQUEST			= 'status_xrequest';
	const EDIT_XREQUEST				= 'edit_xrequest';
	const DELETE_XREQUEST			= 'delete_xrequest';
	const UPDATE_XREQUEST			= 'update_xrequest';
	const HISTORIC_XREQUEST		    = 'get_xrequest_protocol_unit';
	
	const SHOW_CONTROL_PANEL		= 'show_control_panel';
	
	protected static $roles = null;
	
	
    // Mandatory functions required for module function
    public function getName() {
        //return "Requisições";
        return "XREQUEST";
    }
	
    public function getPermittedRoles() {
        return array("administrator","professor","student");
    }
	
    public function isLessonModule() {
        return false;
    }
	
	public function onInstall() {
        eF_executeNew("drop table if exists module_xrequest");
        $a = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_xrequest` (
			`id` mediumint(8) NOT NULL AUTO_INCREMENT,  
			`name` varchar(255) NOT NULL,  `type` int(11) NOT NULL DEFAULT '0',
			`email` varchar(255) NOT NULL,  `type` int(11) NOT NULL DEFAULT '0',    
			`status` int(11) NOT NULL DEFAULT '0',  
			`price` float DEFAULT NULL, 
			`dias_prazo` int(11) NOT NULL,  
			PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
			");
        
        eF_executeNew("drop table if exists module_xrequest_historic");
	    $b = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_xrequest_historic` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
					  `user_id` int(11) NOT NULL DEFAULT '0',
					  `historic` text NOT NULL,
					  `data_historic` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  `request_id` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
        
        ");
	    
	    eF_executeNew("drop table if exists module_xrequest_protocol");
		$c = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_xrequest_protocol` (
			  `id` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `user_id` int(255) NOT NULL,
			  `type` int(11) NOT NULL DEFAULT '0',
			  `desc_type` varchar(255) DEFAULT NULL,
			  `status` varchar(255) DEFAULT '0',
			  `status_id` int(11) NOT NULL,
			  `user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `data_open` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
			  `data_modificado` timestamp NULL DEFAULT NULL,
			  `descricao` text CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
        
        ");
		
		eF_executeNew("drop table if exists module_xrequest_status");
		$d = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_xrequest_status` (
							`id` mediumint(8) NOT NULL,
							`name` varchar(255) NOT NULL,
							`status` int(11) DEFAULT '0',
							PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;        
        ");
		
		$e = eF_executeNew("INSERT INTO `module_xrequest_status` (`id`, `name`, `status`) VALUES
							(1, 'Cancelado', 1),
							(2, 'Em Andamento', 1),
							(3, 'Aberto', 1),
							(4, 'Aguardando Retorno', 1),
							(5, 'Finalizado', 1);		
		");

        return ($a && $b && $c && $d && $e);
    }

    // And on deleting the module
    public function onUninstall() {
        $a = eF_executeNew("drop table module_xrequest;");
        $b = eF_executeNew("drop table module_xrequest_historic;");
        $c = eF_executeNew("drop table module_xrequest_protocol;");
		$d = eF_executeNew("drop table module_xrequest_status;");
				
       return ($a && $b && $c && $d);
    }
    
    public function addScripts() {
		return array("tinyeditor/tinyeditor");
	}
    
   
	public function getTitle($action) {
		switch($action) {
			case "get_xrequest" : {
				return __MODULE_XREQUEST_TYPES_PROTOCOL;
			}
			case "get_xrequest_protocol_unit" : {
				return __MODULE_XREQUEST_PROTOCOL;
			}
			
			default : {
				return parent::getTitle($action);
			}
			
		}
	}
    
   public function getCenterLinkInfo() {
		$currentUser = $this -> getCurrentUser();
        
        $xuserModule = $this->loadModule("xuser");
              
		if ($xuserModule->getExtendedTypeID($currentUser) == "administrator") {
			return array('title' => _MODULE_XREQUEST_NAME,
                         'image' => $this -> moduleBaseDir . 'images/xrequest.png',
                         'link'  => $this -> moduleBaseUrl,
						 'class' => 'request'
            );
        }
        
     }
	
      
    /*
	public function getNavigationLinks() {
		$currentUser = $this -> getCurrentUser();
            	return array (array ('title' => _MODULE_XREQUEST_NAME, 'link'  => $this -> moduleBaseUrl));
	}
	*/
 	public function getSidebarLinkInfo () {
	  
    	$link_menu_xrequest = array (
                              'id' => 'xrequest',
                              'title' => _MODULE_XREQUEST_NAME,
                              //'image' => $this -> moduleBaseLink . 'img/16x16/chat',
                              'MagesterExtensions' => '1',      //no extension provided up
                              'link'  => $this -> moduleBaseUrl
							  );
      return array ('tools' => array ('links'=>$link_menu_xrequest));
	  //return array ($link_menu_xrequest);
	}
	
 
    public function getDefaultAction() {
    	$smarty = $this->getSmartyVar();
       	   	
       	if ($this->getCurrentUser()->getType() == 'student') {
			$smarty -> assign("T_TYPE_USER", "student");	
			return self::GET_XREQUEST_PROTOCOL;
			
		} 
		if ($this->getCurrentUser()->getType() == 'administrator') {
			$smarty -> assign("T_TYPE_USER", "administrator");
			return self::GET_XREQUEST_PROTOCOL;
		}
    }
	/* ACTION HANDLERS */
	
	
    public function getXrequestAction($sendData) {
    	$smarty = $this->getSmartyVar();
       	$object = eF_getTableData("module_xrequest", "*");	
       	$smarty -> assign("T_REQUEST_TYPES", $object);
       	return true;
   	
    }
	
      
     public function editXrequestAction() {
		$smarty = $this->getSmartyVar();
		$idRequest  = $_GET['id'];
		$object = eF_getTableData("module_xrequest", "*", "id = $idRequest");    
		
		echo $object[0]['name'];
		
		$addForm = new HTML_QuickForm("xrequest_editxrequest_form_" . $respKey, "post", $_SERVER['REQUEST_URI'], "", null, true);
        $addForm -> addElement('hidden', 'id', $object[0]['id']);
		$addForm -> addElement('text', 'name', __XREQUEST_NAME_TYPE, "value=".$object[0]['name'] ,'class = "small"');
	    $addForm -> addElement('text', 'email', __XREQUEST_EMAIL, "value=".$object[0]['email'], 'class = "small"');
	    $addForm -> addElement('text', 'valor', __XREQUEST_VALOR_TYPE, "value=".$object[0]['price'], 'class = "small" alt="decimal"');
	    $addForm -> addElement('text', 'dias_prazo', __XREQUEST_DIASPRAZO, "value=".$object[0]['dias_pazo'], 'class = "small"');
		$addForm -> addElement('checkbox', 'status', __XREQUEST_STATUS, null, '', array(0, 1));
	    $addForm -> addElement('submit', 'submit_xrequest', _SEND);
				
		if ($addForm -> isSubmitted()) {
	         $values = $addForm->exportValues();
			 $fields = array('name'   => $values['name'], 
			 				 'status' => $values['status'],
			 				 'price' => $values['valor'],
			 				 'email' => $values['email'],
			 				 'dias_prazo' => $values['dias_prazo']  
			            );
			 
	          var_dump($fields);
	           
			 if ($selectedAction == self::EDIT_XREQUEST) {
		        eF_updateTableData("module_xrequest", $fields, "id = $idRequest");			 	
			 	$this->setMessageVar( __XREQUEST_ADDNEWREQUEST, "success");
					    
			} 
	    }
		
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $addForm-> accept($renderer);
        $smarty -> assign('T_XREQUEST_EDITREQUEST_FORM', $renderer -> toArray());
		
		return true; 
     }
     
    
	public function getXrequestProtocolAction($sendData) {
		
		$userID = $this->getCurrentUser()->user['id'];
	 	
	   	if ($this->getCurrentUser()->getType() == 'administrator') {
			$object = eF_getTableData("module_xrequest_protocol", "*", "", "data_open DESC");	
	   	} else {	
    	    $object = eF_getTableData("module_xrequest_protocol", "*", "user_id = $userID", "data_open DESC");       
	   	}	
	   
		$smarty = $this->getSmartyVar();	
		$smarty -> assign("T_REQUEST_PROTCOL_LIST", $object);
		
		return true;
    }
    
	public function getXrequestProtocolUnitAction($sendData) {

		$requestID = $_GET['id'];
		$userCurrent = $this->getCurrentUser()->user;
		
		
		
    	$smarty = $this->getSmartyVar();
       	$object = eF_getTableData("module_xrequest_protocol", "*", "id = $requestID");
              		
       	     	
       	$smarty -> assign("T_REQUEST_PROTCOL_LIST", $object);
       	
             	
       	if ($object[0]['status_id'] == 5) {
       		$smarty -> assign("T_VIEW_FORM_HISTORIC", 1);       		
       	} else {
       		$smarty -> assign("T_VIEW_FORM_HISTORIC", 0);     
       	}
       	
          	
       	
       	/* Lista Históricos */
       	
       	$lisHistoric = eF_getTableData("module_xrequest_historic","*", "request_id = $requestID", "data_historic DESC" );
       	$smarty -> assign("T_LIST_HISTORIC", $lisHistoric);
       	     	
       	
       	$valueName =  $userCurrent['name'];
		$valueName .= " ";
		$valueName .= $userCurrent['surname']." ";
		
	
		$dataAdd = date("Y-m-d H:i:s");	
       	$addForm = new HTML_QuickForm("xrequest_historic_entry_form_" . $respKey, "post", $_SERVER['REQUEST_URI'], "", null, true);
	    //$addForm -> addElement('textarea', 'historic', __MODULE_XREQUEST_NAME_TYPE, 'class = "tinyeditor"');
		$addForm -> addElement('hidden', 'username', $valueName);
       	$addForm -> addElement('textarea', 'historic',  __XREQUEST_NAME_TYPE, array('id' => 'historic', 'class' => 'tinyeditor', 'cols' => 130, 'rows' => 4));
	     $addForm -> addElement('submit', 'submit_xrequest', _SEND);
		$addForm -> addElement('checkbox', 'finaliza', _XREQUEST_FINSHREQUEST , null, '', array(0, 1));
    	$addForm -> addRule('historic',__XREQUEST_EMPYDESC, 'required');
    	
		if ($addForm -> isSubmitted() && $addForm -> validate()) {
	         $values = $addForm->exportValues();
			 $fields = array('user_name'   => $values['username'],
			 				 'user_id'     => $userCurrent['id'],
			 				 'historic'	   => $values['historic'],
			 				 'data_historic' => $dataAdd,
			 				 'request_id' => $_GET['id']	
			 						 
			            );

   
		if ($values['finaliza'] == 1 ) {
			$fishrequest = array('data_modificado' => $dataAdd,
							     'status' => "Finalizado",
							     'status_id' => "5", 
			  				 );
			eF_updateTableData("module_xrequest_protocol", $fishrequest, "id = $requestID");
		}
		
			if(count($lisHistoric) ==  0) {  				 
				if ($userCurrent['user_type'] == "administrator")  {
				$desc_statusUp = array('data_modificado' => $dataAdd,
									   'status' => "Em andamento",
										'status_id' => "2", 
					  				 );
				 eF_updateTableData("module_xrequest_protocol", $desc_statusUp, "id = $requestID");
				}	
			}
		       
		        eF_insertTableData("module_xrequest_historic", $fields);			 	
			 	$this->setMessageVar(__XREQUEST_ADDHISTORICPROTOCOL, "success");
					    
		}
				 
	   
		
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $addForm-> accept($renderer);
            $smarty -> assign('T_XREQUEST_HISTORIC_FORM', $renderer -> toArray());
		
       		return true;
     	
    }
    
	public function getXrequestStatusAction($sendData) {
    	$smarty = $this->getSmartyVar();
       	$object = eF_getTableData("module_xrequest_status", "*");	
       	$smarty -> assign("T_REQUEST_STATUS", $object);
       	return true;
   	
    }
    
    
    public function addXrequestAction($sendData) {
        $smarty = $this -> getSmartyVar();
      
    	$this->makeAddRequestForm();
		/*
    	$this->appendTemplate(
          	array(
	           	'title'			=> _MODULE_XREQUEST_ADDTYPE,
	           	'template'		=> $this->moduleBaseDir . "templates/includes/xrequest_basic_form.tpl"
	           	//,'contentclass'	=> ''
	          )
         );
         */
    	return true;
		
    }
    
    
    public function newXrequestAction($sendData) {
		$smarty = $this -> getSmartyVar();

    	$this->makeNewRequestForm();
/*
    	$this->appendTemplate(
           		array(
	            	'title'			=> _MODULE_XREQUEST_NEW,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/xrequest_newbasic_form.tpl",
	            	'contentclass'	=> ''
	            )
           	);
           	return true;
     	*/
    }

	public function statusXrequestAction($sendData) {
       $smarty = $this -> getSmartyVar();
       $this->makeStatusRequestForm();
       
       /*
       $this->appendTemplate(
           		array(
	            	'title'			=> __MODULE_XREQUEST_NEW_STATUS,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/xrequest_status_form.tpl",
	            	'contentclass'	=> ''
	            )
           	);
		*/	
       return true;
    }
    
    // Formularios
    
   private function makeStatusRequestForm() {
    $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		
		$smarty = $this -> getSmartyVar();
	
		$addForm = new HTML_QuickForm("xrequest_status_entry_form_" . $respKey, "post", $_SERVER['REQUEST_URI'], "", null, true);
	    $addForm -> addElement('text', 'name', __XREQUEST_NAME_TYPE, 'class = "small"');
		$addForm -> addElement('checkbox', 'status', __XREQUEST_STATUS, null, '', array(0, 1));
	    $addForm -> addElement('submit', 'submit_xrequest', _SEND);
				
		if ($addForm -> isSubmitted() && $addForm -> validate()) {
	         $values = $addForm->exportValues();
			 $fields = array('name'   => $values['name'], 
			 				 'status' => $values['status'] 
			            );
			
					            
			 if ($selectedAction == self::STATUS_XREQUEST) {
		        eF_insertTableData("module_xrequest_status", $fields);			 	
			 	$this->setMessageVar("_", $result['message_type']);
					    
			} 
	    }
		
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $addForm-> accept($renderer);
            $smarty -> assign("T_XREQUEST_STATUS_FORM", $renderer -> toArray());
		
       		return true;
    }
    
	private function makeAddRequestForm() {
        // CREATING RESPONSIBLE FORM
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		
		$smarty = $this -> getSmartyVar();
	
		$addForm = new HTML_QuickForm("xrequest_entry_form_" . $respKey, "post", $_SERVER['REQUEST_URI'], "", null, true);
	    $addForm -> addElement('text', 'name', __XREQUEST_NAME_TYPE, 'class = "small"');
	    $addForm -> addElement('text', 'email', __XREQUEST_EMAIL, 'class = "small"');
	    $addForm -> addElement('text', 'valor', __XREQUEST_VALOR_TYPE, 'class = "small" alt="decimal"');
	    $addForm -> addElement('text', 'dias_prazo', __XREQUEST_DIASPRAZO, 'class = "small"');
		$addForm -> addElement('checkbox', 'status', __XREQUEST_STATUS, null, '', array(0, 1));
	    $addForm -> addElement('submit', 'submit_xrequest', _SEND);
				
		if ($addForm -> isSubmitted() && $addForm -> validate()) {
	         $values = $addForm->exportValues();
			 $fields = array('name'   => $values['name'], 
			 				 'status' => $values['status'],
			 				 'price' => $values['valor'],
			 				 'email' => $values['email'],
			 				 'dias_prazo' => $values['dias_prazo']  
			            );
			 
	            
			 if ($selectedAction == self::ADD_XREQUEST) {
		        eF_insertTableData("module_xrequest", $fields);			 	
			 	$this->setMessageVar( __XREQUEST_ADDNEWREQUEST, "success");
					    
			} 
	    }
		
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $addForm-> accept($renderer);
            $smarty -> assign("T_XREQUEST_BASIC_FORM", $renderer -> toArray());
		
       		return true;
    }
    
    
	private function makeNewRequestForm() {
        // CREATING RESPONSIBLE FORM
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_XUSERS;
		
		$smarty = $this -> getSmartyVar();
		
		$userCurrent = $this->getCurrentUser()->user;
		$userCourse  = $this->getCurrentCourse()->course;

		
		$dataAdd = date("Y-m-d H:i:s");	
		
		$ano = date("Y");
		$mes = date("m");
		$course_id = $userCourse['id'];	
		$user_id = $userCurrent['id'];
		
		$countRequest = eF_getTableData("module_xrequest_protocol", "id, user_id", "user_id = $user_id");
		$segNum = count($countRequest) + 1 ;
		
		$modulo10 = $this->_module10($ano . $mes . $course_id . $user_id . $segNum );

		$numProtocol = sprintf("%04d%02d%03d%05d%02d%01d", $ano, $mes, $userCourse['id'], $userCurrent['id'], $segNum, $modulo10);
		$valueName =  $userCurrent['name'];
		$valueName .= " ";
		$valueName .= $userCurrent['surname']." ";
					
		$addForm = new HTML_QuickForm("xrequest_new_entry_form" . $respKey, "post", $_SERVER['REQUEST_URI'], "", null, true);
	    
		$addForm -> addElement('hidden', 'username', $valueName);
	    $addForm -> addElement('textarea', 'desc',  __XREQUEST_NAME_TYPE, array('id' => 'historic', 'class' => 'desc', 'cols' => 130, 'rows' => 4));
	    $addForm -> addElement('hidden', 'user_ID', $userCurrent['id']);	         
	 
	   
	    $typesRequest = eF_getTableDataFlat("module_xrequest", "id, name", "status = 1", "name DESC" );

			if (count($typesRequest) > 0) {
				$typesRequest_list = array_combine($typesRequest['id'], $typesRequest['name']);
	   			$typesRequest_list[""] = __SELECT_ONE_OPTION;
	   			$typesRequest_list = array_reverse($typesRequest_list, true);

   			} else {
   				$typesRequest_list = array(-1 => __NO_DISPONIBLE_OPTIONS, 0 => __IES_ALL_OPTIONS);
   			}
   			
   			
	  		   			
	    $addForm -> addElement('select', 'type' , __XREQUEST_TYPES_PROTOCOL, $typesRequest_list ,'class = "medium"');
		
	    $addForm -> addRule('type', __XREQUEST_EMPYTYPE, 'required');
	    $addForm -> addRule('desc', __XREQUEST_EMPYDESC, 'required');     
	    $addForm -> addElement('submit', 'submit_xrequest', _SEND);
		
	    
	    
		if ($addForm -> isSubmitted() && $addForm -> validate()) {
	         $values = $addForm->exportValues();
	         $idType = $values['type'];
	         $typesDesc = eF_getTableData("module_xrequest", "name, dias_prazo", "id = $idType", "name DESC" );

	         	
	         
	         $fields = array('id' => $numProtocol, 
			 				 'user_id' => $userCurrent['id'],
			 				 'type' => $values['type'],
			 				 'desc_type' => $typesDesc[0]['name'],
			 				 'status' => "Aberto",
	         				 'status_id' => "3",
			 				 'user_name' => $values['username'],
			 				 'data_open' => $dataAdd, 
			 				 'descricao' => $values['desc']
			 				  
			            );
			 
			    if ($selectedAction == self::NEW_XREQUEST) {
		        eF_insertTableData("module_xrequest_protocol", $fields);			 	
			 	$this->setMessageVar(__XREQUEST_OPENPROTOCOL, $fields);
					    
			} else  {
				$this->setMessageVar(__XREQUEST_ERROPROTOCOL, $fields);
			} 
	    }
		
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $addForm-> accept($renderer);
            $smarty -> assign("T_XREQUEST_NEWREQUEST_FORM", $renderer -> toArray());
		
       		return true;
    }
    

    	
	public function _module10($num) {
		$numtotal10 = 0;
		$fator = 2;
	
		// Separacao dos numeros
		for ($i = strlen($num); $i > 0; $i--) {
			// pega cada numero isoladamente
			$numeros[$i] = substr($num,$i-1,1);
			// Efetua multiplicacao do numero pelo (falor 10)
			// 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
			$temp = $numeros[$i] * $fator;
			$temp0=0;
			foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){
				$temp0+=$v;
			}
			$parcial10[$i] = $temp0; //$numeros[$i] * $fator;
			// monta sequencia para soma dos digitos no (modulo 10)
			$numtotal10 += $parcial10[$i];
			if ($fator == 2) {
				$fator = 1;
			} else {
				$fator = 2; // intercala fator de multiplicacao (modulo 10)
			}
		}
	
		// várias linhas removidas, vide função original
		// Calculo do modulo 10
		$resto = $numtotal10 % 10;
		$digito = 10 - $resto;
		if ($resto == 0) {
			$digito = 0;
		}
	
		return $digito;
	}

	

    /* Data Model functions */ 
	public function getUserById($userID) {
	}
	public function getExtendedTypeID($userObject) {
	}
	public function getExtendedTypeIDInCourse($userObject, $courseObject) {
	}
	public function getUserClasses($userID = null) {
	}
	public function getUserTags($user) {
	}
	public function getUserDetails($userID, $user_details_type = 'self') {
	}
}
?>
