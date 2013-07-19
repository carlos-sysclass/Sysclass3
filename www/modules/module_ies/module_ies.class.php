<?php

class module_ies extends MagesterExtendedModule
{
	const GET_IES				= 'get_ies';
	const ADD_IES				= 'add_ies';
	const EDIT_IES				= 'edit_ies';
	const DELETE_IES			= 'delete_ies';

    // Mandatory functions required for module function
    public function getName()
    {
        return __IES_MODULE_NAME;
    }

    public function getPermittedRoles()
    {
        return array("administrator" /*,"professor" *//*,"student"*/);
    }

    public function isLessonModule()
    {
        return false;
    }

    public function getCenterLinkInfo()
    {
      	$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");
		if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator"
		) {
			return array('title' => __IES_MODULE_NAME,
            	'image' => $this -> moduleBaseDir . 'images/ies.png',
                'link'  => $this -> moduleBaseUrl,
				'class' => 'home'
			);
		}
    }

    public function getNavigationLinks()
    {
    	$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_IES;

        $basicNavArray = array (
			array ('title' => _HOME, 'link' => "administrator.php?ctg=control_panel"),
    		array ('title' => __IES_MANAGEMENT, 'link'  => $this -> moduleBaseUrl)
		);

		if ($selectedAction == self::EDIT_IES) {
            $basicNavArray[] = array ('title' => __IES_EDIT_IES, 'link'  => $this -> moduleBaseUrl . "&action=" . self::EDIT_IES . "&ies_id=". $_GET['ies_id']);
		} elseif ($selectedAction == self::ADD_IES) {
            $basicNavArray[] = array ('title' => __IES_ADD_IES, 'link'  => $this -> moduleBaseUrl . "&action=" . self::ADD_IES);
		}

        return $basicNavArray;
    }

    public function getSidebarLinkInfo()
    {
    	$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");
		if (
			$xuserModule->getExtendedTypeID($currentUser) == "administrator"
		) {

	        $link_of_menu_clesson = array (array ('id' => 'ies_link_id1',
	                                              'title' => _MODULE_IES,
	                                              'image' => $this -> moduleBaseDir . 'images/ies16.png',
	                                              '_magesterExtensions' => '1',
	                                              'link'  => $this -> moduleBaseUrl));

	        return array ( "content" => $link_of_menu_clesson);
		}
    }

    public function getLinkToHighlight()
    {
        return 'ies_link_id1';
    }

    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule()
    {
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_IES;

		$smarty = $this -> getSmartyVar();

		$smarty -> assign("T_IES_ACTION", $selectedAction);

        // Get smarty global variable
        $smarty = $this -> getSmartyVar();

        if ($selectedAction == self::DELETE_IES && sC_checkParameter($_GET['ies_id'], 'id')) {
            sC_deleteTableData("module_ies", "id=".$_GET['ies_id']);

            header("location:". $this -> moduleBaseUrl ."&message=".urlencode(__IES_SUCCESFULLYDELETEDIESENTRY)."&message_type=success");
        } elseif (
        	$selectedAction == self::ADD_IES ||
        	($selectedAction == self::EDIT_IES && sC_checkParameter($_GET['ies_id'], 'id'))
        ) {

            // Create ajax enabled table for meeting attendants
            /*
            if ($selectedAction == self::EDIT_IES) {
 				$ies = sC_getTableData("module_ies", "*" );
                $smarty -> assign("T_IES", $ies);
            }
            */

			$modules = sC_loadAllModules(true);

            $templates = array();
            // ADD / EDIT COURSE
            if ($this->makeBasicForm()) {
	            $templates[] = array(
	            	'title'			=> ($selectedAction == self::EDIT_IES) ? __IES_EDIT_IES_BLOCK : __IES_ADD_IES_BLOCK,
	            	'template'		=> $this->moduleBaseDir . "templates/includes/ies.form.basic.tpl",
	            	'contentclass'	=> ''
	            );
            }

			foreach ($modules as $module_name => $module) {
            	if (is_callable(array($module, "receiveEvent"))) {
            		$appendTpl = $module->receiveEvent($this, $selectedAction, array('editedIes' => $this->getEditedIes()));
            		if (is_array($appendTpl)) {
	        			$templates[] =  $appendTpl;
            		}
            	}
            }

            $smarty -> assign('T_IES_FORM_TABS',
				$templates
			);

        } else {
			$ies = sC_getTableData("module_ies", "*" );
            $smarty -> assign("T_IES", $ies);

        }

        return true;
    }

    public function getSmartyTpl()
    {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_IES_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_IES_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_IES_BASELINK" , $this -> moduleBaseLink);

        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_IES;

		if (
        	$selectedAction == self::ADD_IES ||
	       	$selectedAction == self::EDIT_IES
) {
			if ($selectedAction == self::ADD_IES) {
				$smarty -> assign("T_IES_FORM_TABS_TITLE", __IES_ADD_IES_BLOCK);
			} elseif ($selectedAction == self::EDIT_IES) {
				$smarty -> assign("T_IES_FORM_TABS_TITLE", __IES_EDIT_IES_BLOCK);
			}
			$smarty -> assign('T_IES_MAIN_TEMPLATE', 'includes/ies.block.add_edit_ies.tpl');
		}

        return $this -> moduleBaseDir . "templates/default.tpl";
    }

    public function getEditedIes($reload = false, $iesID = null)
    {
    	if (!is_null($iesID)) {
    		$ies_entry = sC_getTableData("module_ies", "*", "id=".$iesID);
    		return $this->editedIes = $ies_entry[0];
    	}

    	if (!is_null($this->editedIes) && !$reload) {
    		return $this->editedIes;
    	}
    	if (sC_checkParameter($_GET['ies_id'], 'id')) {
    		$ies_entry = sC_getTableData("module_ies", "*", "id=".$_GET['ies_id']);
    		return $this->editedIes = $ies_entry[0];
		}

    	return false;
    }

    protected function makeBasicForm()
    {
    	$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_IES;

		$form = new HTML_QuickForm("ies_entry_form", "post", $_SERVER['REQUEST_URI'], "", null, true);
		$form -> addElement('hidden', 'ies_ID');

		$form -> registerRule('checkParameter', 'callback', 'sC_checkParameter');                   //Register this rule for checking user input with our function, sC_checkParameter

		$stateList = localization::getStateList();

		$form -> addElement('text', 'nome', __IES_NOME, 'class = "large"');
		$form -> addRule('nome', __IES_THEFIELDNAMEISMANDATORY, 'required', null, 'client');
		$form -> addElement('text', 'razao_social', __IES_RAZAO_SOCIAL, 'class = "large"');
		$form -> addRule('razao_social', __IES_THEFIELDRAZAOSOCIALISMANDATORY, 'required', null, 'client');
		$form -> addElement('text', 'contato', __IES_CONTATO, 'class = "large"');
		$form -> addElement('text', 'cep', __IES_CEP, 'class = "medium"');
		$form -> addElement('text', 'endereco', __IES_ENDERECO, 'class = "large"');
		$form -> addElement('text', 'numero', __IES_NUMERO, 'class = "small"');
		$form -> addElement('text', 'complemento', __IES_COMPLEMENTO, 'class = "small"');
		$form -> addElement('text', 'bairro', __IES_BAIRRO, 'class = "medium"');
		$form -> addElement('text', 'cidade', __IES_CIDADE, 'class = "medium"');
		$form -> addElement('select', 'uf', __IES_UF, $stateList, 'class = "small"');
		$form -> addElement('text', 'telefone', __IES_TELEFONE, 'class = "medium"');
		$form -> addElement('text', 'celular', __IES_CELULAR, 'class = "medium"');
		$form -> addElement('textarea', 'observacoes', __IES_OBSERVACOES, 'class = "large"');
		$form -> addElement('advcheckbox', 'active', __IES_ACTIVE, null, '', array(0, 1));

        $form -> addElement('submit', 'submit_ies', __IES_SAVE, 'class = "button_colour round_all"');

		if ($selectedAction == self::EDIT_IES) {
        	$ies_entry = $this->getEditedIes();

			$defaults = array(
				'ies_ID'		=> $ies_entry['id'],
				'nome' 			=> $ies_entry['nome'],
				'razao_social'	=> $ies_entry['razao_social'],
				'contato'		=> $ies_entry['contato'],
				'observacoes'	=> $ies_entry['observacoes'],
				'cep'			=> $ies_entry['cep'],
				'endereco'		=> $ies_entry['endereco'],
				'numero'		=> $ies_entry['numero'],
				'complemento'	=> $ies_entry['complemento'],
				'bairro'		=> $ies_entry['bairro'],
				'cidade'		=> $ies_entry['cidade'],
				'uf'			=> $ies_entry['uf'],
				'telefone'		=> $ies_entry['telefone'],
				'celular'		=> $ies_entry['celular'],
				'active'		=> (bool) ($ies_entry['active'] == '1')
			);
		} else {
                $defaults = array(
				'ies_ID'		=> -1,
                'active'		=> 1
			);
		}
        $form -> setDefaults( $defaults );

        if ($form -> isSubmitted() && $form -> validate()) {
            $fields = array(
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
				'telefone'		=> $form -> exportValue('telefone'),
				'celular'		=> $form -> exportValue('celular'),
				'active'		=> $form -> exportValue('active')
            );

			if ($selectedAction == self::EDIT_IES) {
            	$fields['id']	= $form -> exportValue('ies_ID');

				if (sC_updateTableData("module_ies", $fields, "id=".$_GET['ies_id'])) {
					header("location:".$this -> moduleBaseUrl."&message=".urlencode(__IES_SUCCESFULLYUPDATEDIESENTRY)."&message_type=success");
				} else {
					header("location:".$this -> moduleBaseUrl."&action=" . self::EDIT_IES ."&ies_id=".$_GET['ies_id']."&message=".urlencode(__IES_PROBLEMUPDATINGIESENTRY)."&message_type=failure");
				}
			} else {
				if ($result = sC_insertTableData("module_ies", $fields)) {
					header("location:".$this -> moduleBaseUrl."&action=" . self::EDIT_IES ."&ies_id=".$result."&message=".urlencode(_MODULE_IES_SUCCESFULLYINSERTEDIESENTRY)."&message_type=success&tab=users");
				} else {
					header("location:".$this -> moduleBaseUrl."&action=" . self::ADD_IES . "&message=".urlencode(__IES_PROBLEMINSERTINGIESENTRY)."&message_type=failure");
				}
			}
		}

		$smarty = $this -> getSmartyVar();

        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
        $form -> accept($renderer);

        $smarty -> assign('T_IES_BASIC_FORM', $renderer -> toArray());

        return true;
    }
}
