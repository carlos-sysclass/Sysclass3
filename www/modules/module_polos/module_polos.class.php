<?php

class module_polos extends MagesterExtendedModule {
	
	const GET_POLOS					= 'get_polos';
	const ADD_POLO					= 'add_polo';
	const EDIT_POLO					= 'edit_polo';
	const DELETE_POLO				= 'delete_polo';
	
    // Mandatory functions required for module function
    public function getName() {
        return "POLOS";
    }

    public function getPermittedRoles() {
        return array("administrator" /*,"professor" *//*,"student"*/);
    }

    public function isLessonModule() {
        return false;
    }

    // Optional functions
    // What should happen on installing the module
    public function onInstall() {
        eF_executeNew("drop table if exists module_polos");
        $a = eF_executeNew("CREATE TABLE IF NOT EXISTS `module_polos` (
			`id` 			mediumint(8) 	NOT NULL,
			`nome` 			varchar(250) 	NOT NULL,
			`razao_social`	varchar(250) 	NOT NULL,
			`observações`	text 			DEFAULT '',
			`cep` 			varchar(15) 	NOT NULL,
			`logradouro`	varchar(150) 	NOT NULL,
			`numero` 		varchar(15) 	NOT NULL,
			`complemento` 	varchar(50) 	DEFAULT NULL,
			`bairro` 		varchar(100)	DEFAULT NULL,
			`cidade` 		varchar(100) 	NOT NULL,
			`uf`	 		varchar(20) 	NOT NULL,
			`telefone` 		varchar(20) 	DEFAULT NULL,
			`celular` 		varchar(20) 	DEFAULT NULL,
			`active` 		tinyint 		NOT NULL DEFAULT '1',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        return $a;
    }

    // And on deleting the module
    public function onUninstall() {
        $a = eF_executeNew("drop table module_polos;");

        return $a;
    }

    public function getCenterLinkInfo() {
		$currentUser = $this -> getCurrentUser();
        
        $xuserModule = $this->loadModule("xuser");
		if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
			$xuserModule->getExtendedTypeID($currentUser) == "coordenator"
		) {
        	return array('title' => _MODULE_POLOS,
                         'image' => $this -> moduleBaseDir . 'images/polos.png',
                         'link'  => $this -> moduleBaseUrl,
						'class' => 'building'
            );
        }
    }

    public function getNavigationLinks() {
    	$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_POLOS;

        $basicNavArray = array (
			array ('title' => _HOME, 'link' => "administrator.php?ctg=control_panel"),
    		array ('title' => _MODULE_POLOS_MANAGEMENT, 'link'  => $this -> moduleBaseUrl)
		);
        
		if ($selectedAction == self::EDIT_POLO) {
            $basicNavArray[] = array ('title' => _MODULE_POLOS_EDITPOLO, 'link'  => $this -> moduleBaseUrl . "&action=" . self::EDIT_POLO . "&polo_id=". $_GET['polo_id']);
		} else if ($selectedAction == self::ADD_POLO) {
            $basicNavArray[] = array ('title' => _MODULE_POLOS_ADDPOLO, 'link'  => $this -> moduleBaseUrl . "&action=" . self::ADD_POLO);
		}
        return $basicNavArray;
    }

    public function getSidebarLinkInfo() {
		$currentUser = $this -> getCurrentUser();
        
		$xuserModule = $this->loadModule("xuser");
		if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
			$xuserModule->getExtendedTypeID($currentUser) == "coordenator"
		) {
	        $link_of_menu_clesson = array (array ('id' => 'polos_link_id1',
	                                              'title' => _MODULE_POLOS,
	                                              'image' => $this -> moduleBaseDir . 'images/polos16.png',
	                                              '_magesterExtensions' => '1',
	                                              'link'  => $this -> moduleBaseUrl));
	
	        return array ( "content" => $link_of_menu_clesson);
		}
    }

    public function getLinkToHighlight() {
        return 'polos_link_id1';
    }


    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule() {
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_POLOS;
		
		$smarty = $this -> getSmartyVar();
		
		$smarty -> assign("T_MODULE_POLOS_ACTION", $selectedAction);    	
    	
        // Get smarty global variable
        $smarty = $this -> getSmartyVar();

        if ($selectedAction == self::DELETE_POLO && eF_checkParameter($_GET['polo_id'], 'id')) {
            eF_deleteTableData("module_polos", "id=".$_GET['polo_id']);
            
            header("location:". $this -> moduleBaseUrl ."&message=".urlencode(_MODULE_POLOS_SUCCESFULLYDELETEDPOLOENTRY)."&message_type=success");
        } else if (
        	$selectedAction == self::ADD_POLO || 
        	($selectedAction == self::EDIT_POLO && eF_checkParameter($_GET['polo_id'], 'id'))
        ) {

            // Create ajax enabled table for meeting attendants
            if ($selectedAction == self::EDIT_POLO) {
                if (isset($_GET['ajax']) && $_GET['ajax'] == 'poloTable') {
                    isset($_GET['limit']) && eF_checkParameter($_GET['limit'], 'uint') ? $limit = $_GET['limit'] : $limit = G_DEFAULT_TABLE_SIZE;

                    if (isset($_GET['sort']) && eF_checkParameter($_GET['sort'], 'text')) {
                        $sort = $_GET['sort'];
                        isset($_GET['order']) && $_GET['order'] == 'desc' ? $order = 'desc' : $order = 'asc';
                    } else {
                        $sort = 'login';
                    }
			
                    $polos = eF_getTableData("module_polos", "*" );

                    $users = eF_multiSort($users, $_GET['sort'], $order);
                    if (isset($_GET['filter'])) {
                        $users = eF_filterData($users , $_GET['filter']);
                    }

                    $smarty -> assign("T_USERS_SIZE", sizeof($users));

                    if (isset($_GET['limit']) && eF_checkParameter($_GET['limit'], 'int')) {
                        isset($_GET['offset']) && eF_checkParameter($_GET['offset'], 'int') ? $offset = $_GET['offset'] : $offset = 0;
                        $users = array_slice($users, $offset, $limit);
                    }
					$smarty -> assign("T_POLOS", $polos);
                    $smarty -> display($this -> getSmartyTpl());
                    exit;

                } else {
                    $polos = eF_getTableData("module_polos", "*" );
                    $smarty -> assign("T_POLOS", $polos);
                }
            }

            $form = new HTML_QuickForm("polo_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
			$form -> addElement('hidden', 'polo_ID');
			
            $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');                   //Register this rule for checking user input with our function, eF_checkParameter
            /*
            $accounts = array();
            
            foreach($this->accounts as $key => $item) {
            	$accounts[$key]	= $item['name'];
            }
            */
            
            $stateList = localization::getStateList();
            
   			$schools = eF_getTableDataFlat("module_ies", "id, nome", "active = 1" );
   			$schools = array_merge(
   				array(-1 => __SELECT_ONE_OPTION),
   				array_combine($schools['id'], $schools['nome'])
   			);
   			
            $form -> addElement('select', 'ies_id', __IES_FORM_NAME, $schools, 'class = "large"');
            $form -> addElement('text', 'nome', _MODULE_POLOS_NOME, 'class = "large"');
            $form -> addRule('nome', _MODULE_POLOS_THEFIELDNAMEISMANDATORY, 'required', null, 'client');
            $form -> addElement('text', 'razao_social', _MODULE_POLOS_RAZAO_SOCIAL, 'class = "large"');
            $form -> addRule('razao_social', _MODULE_POLOS_THEFIELDRAZAOSOCIALISMANDATORY, 'required', null, 'client');
            $form -> addElement('text', 'contato', _MODULE_POLOS_CONTATO, 'class = "large"');
			$form -> addElement('text', 'cep', _MODULE_POLOS_CEP, 'class = "medium"');
			$form -> addElement('text', 'endereco', _MODULE_POLOS_ENDERECO, 'class = "large"');
			$form -> addElement('text', 'numero', _MODULE_POLOS_NUMERO, 'class = "small"');
			$form -> addElement('text', 'complemento', _MODULE_POLOS_COMPLEMENTO, 'class = "small"');
			$form -> addElement('text', 'bairro', _MODULE_POLOS_BAIRRO, 'class = "medium"');
			$form -> addElement('text', 'cidade', _MODULE_POLOS_CIDADE, 'class = "medium"');
			$form -> addElement('select', 'uf', _MODULE_POLOS_UF, $stateList, 'class = "small"');
			$form -> addElement('text', 'telefone', _MODULE_POLOS_TELEFONE, 'class = "medium"');
			$form -> addElement('text', 'celular', _MODULE_POLOS_CELULAR, 'class = "medium"');
			$form -> addElement('textarea', 'observacoes', _MODULE_POLOS_OBSERVACOES, 'class = "large"');
			$form -> addElement('advcheckbox', 'active', _MODULE_POLOS_ACTIVE, null, '', array(0, 1));
			
            $form -> addElement('submit', 'submit_polo', _MODULE_POLOS_SAVE, 'class = "button_colour round_all"');
            
            if ($selectedAction == self::EDIT_POLO) {
                $polo_entry = eF_getTableData("module_polos", "*", "id=".$_GET['polo_id']);
                
				$defaults = array(
					'polo_ID'		=> $polo_entry[0]['id'],
					'ies_id'		=> $polo_entry[0]['ies_id'],
					'nome' 			=> $polo_entry[0]['nome'],
					'razao_social'	=> $polo_entry[0]['razao_social'],
					'contato'		=> $polo_entry[0]['contato'],
					'observacoes'	=> $polo_entry[0]['observacoes'],
					'cep'			=> $polo_entry[0]['cep'],
					'endereco'		=> $polo_entry[0]['endereco'],
					'numero'		=> $polo_entry[0]['numero'],
					'complemento'	=> $polo_entry[0]['complemento'],
					'bairro'		=> $polo_entry[0]['bairro'],
					'cidade'		=> $polo_entry[0]['cidade'],
					'uf'			=> $polo_entry[0]['uf'],
					'telefone'		=> $polo_entry[0]['telefone'],
					'celular'		=> $polo_entry[0]['celular'],
					'active'		=> (bool)($polo_entry[0]['active'] == '1')				
				);
            } else {
                $defaults = array(
					'polo_ID'		=> -1,
                	'active'		=> 1           
				);
            }
            $form -> setDefaults( $defaults );            

            if ($form -> isSubmitted() && $form -> validate()) {
            	$fields = array(
					'ies_id'		=> $form -> exportValue('ies_id'),
            		'nome' 			=> $form -> exportValue('nome'),
					'razao_social'	=> $form -> exportValue('razao_social'),
					'contato'		=> $form -> exportValue('contato'),
					'observacoes'	=> $form -> exportValue('observacoes'),
					'cep'			=> $form -> exportValue('cep'),
					'endereco'		=> $form -> exportValue('endereco'),
					'numero'		=> $form -> exportValue('numero'),
					'complemento'	=> $form -> exportValue('complemento'),
					'bairro'		=> $form -> exportValue('bairro'),
					'cidade'		=> $form -> exportValue('cidade'),
					'uf'			=> $form -> exportValue('uf'),
            		'geo_lat'		=> 0,
            		'geo_lng'		=> 0,
            		'geo_search'	=> null,
					'telefone'		=> $form -> exportValue('telefone'),
					'celular'		=> $form -> exportValue('celular'),
					'active'		=> $form -> exportValue('active')	
            	);
            	
            	// (RE)CALCULATE POLO COORDINATES
            	$xgeoMapModule = $this->loadModule("xgeomap");
            	
            	$addressData = $xgeoMapModule->createAddress(
            		$fields['endereco'],
					$fields['numero'],
					$fields['bairro'],
					$fields['cidade'],
					$fields['uf']
				);
				
	           	$poloLatLngData = $xgeoMapModule->calculateLatLngByAddress($addressData);
	           	
	           	if (count($poloLatLngData['results']) == 1) {
	           		$fields['geo_search'] = json_encode($poloLatLngData['results'][0]);
	           		$fields['geo_lat']	= $poloLatLngData['results'][0]['geometry']['location']['lat'];
		           	$fields['geo_lng']	= $poloLatLngData['results'][0]['geometry']['location']['lng'];
	           	}
            	
             	if ($selectedAction == self::EDIT_POLO) {
             		$fields['id']	= $form -> exportValue('polo_ID');
             		
					if (eF_updateTableData("module_polos", $fields, "id=".$_GET['polo_id'])) {
						header("location:".$this -> moduleBaseUrl."&message=".urlencode(_MODULE_POLOS_SUCCESFULLYUPDATEDPOLOENTRY)."&message_type=success");
					} else {
						header("location:".$this -> moduleBaseUrl."&action=" . self::EDIT_POLO ."&polo_id=".$_GET['polo_id']."&message=".urlencode(_MODULE_POLOS_PROBLEMUPDATINGPOLOENTRY)."&message_type=failure");
					}
				} else {
					if ($result = eF_insertTableData("module_polos", $fields)) {
						header("location:".$this -> moduleBaseUrl."&action=" . self::EDIT_POLO ."&polo_id=".$result."&message=".urlencode(_MODULE_POLOS_SUCCESFULLYINSERTEDPOLOENTRY)."&message_type=success&tab=users");
					} else {
						header("location:".$this -> moduleBaseUrl."&action=" . self::ADD_POLO . "&message=".urlencode(_MODULE_POLOS_PROBLEMINSERTINGPOLOENTRY)."&message_type=failure");
					}
				}
            }
            
            $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            $form -> accept($renderer);

            $smarty -> assign('T_MODULE_POLOS_FORM', $renderer -> toArray());
        } else {
			$polos = eF_getTableData("module_polos polo LEFT OUTER JOIN module_ies ies ON (polo.ies_id = ies.id)", "polo.*, ies.nome as ies" );
            $smarty -> assign("T_POLOS", $polos);
        }
        return true;
    }

    public function getSmartyTpl() {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_MODULE_POLOS_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_MODULE_POLOS_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_MODULE_POLOS_BASELINK" , $this -> moduleBaseLink);
        
        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_POLOS;
        
        //$smarty -> assign("T_MODULE_POLOS_ACTION", $selectedAction);
        
        return $this -> moduleBaseDir . "templates/default.tpl";
    }
    
    public function getPolos() {
    	$polos_entry = eF_getTableData("module_polos", "*", "active=1");
    	return $polos_entry;
    }
    
    public function loadDataPoloBlock($blockIndex = null) {
    	 
    	$smarty = $this -> getSmartyVar();
    	$currentUser = $this -> getCurrentUser();
    	$userPolo = $currentUser->getUserPolo($currentUser);
    	
    	if ( $userPolo['id'] == null ) {
    		return false;
    	} else {
		 	
    	$baseLink = "http://".$_SERVER['HTTP_HOST']."/student.php?ctg=module&op=module_quick_mails&popup=1";
		$smarty -> assign("T_MODULE_POLOS_INFO_USER_LINK", $baseLink);
    	$smarty -> assign("T_MODULE_POLOS_INFO_USER", $userPolo);
    	 	 
		$this->getParent()->appendTemplate(array(
	   		'title'			=> _MODULE_SEUPOLO,
		    'sub_title'		=> _MODULE_POLOS_ENTRE_CONTATO,
	   		'template'		=> $this->moduleBaseDir . 'templates/blocks/polo.data.tpl',
	   		'contentclass'	=> 'blockContents'
    	), $blockIndex);
   		
    	return true ;
    	}
    }
}
?>
