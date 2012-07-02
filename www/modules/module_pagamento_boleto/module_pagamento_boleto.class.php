<?php

/*

* Class defining the new module

* Its name should be the same as the one provided in the module.xml file

*/
require_once("localization.class.php");

class module_pagamentoException extends Exception
{
    const DIFERENT_REGISTERTYPE = 10001;
}

class module_pagamento_boleto extends MagesterModule {
	
	//const PREVIEW_PAYMENTS_RETURN = 'pending_payments_preview';
	const GET_PAYMENTS				= 'get_payments'; // null action
	const VIEW_PROCESSED_FILE		= 'view_processed_file';
	const UPDATE_PROCESSED_FILE		= 'update_processed_file';
	
	const NOSSO_NUMERO_TEMPLATE_SEM_DV	= "%04d%02d%01d";
	const NOSSO_NUMERO_TEMPLATE		= "%04d%02d%01d%01d";
	const PAYMENT_ID_INDEX			= 0;
	const PAYMENT_PARCELA_INDEX		= 1;
	
	const _PAGAMENTO_BOLETO_MANUALPAY	= 1;
	
	public function getName() {
		return _MODULE_BOLETO_TITLE; // Pagamento com Boleto Bancário
	}
	public function getPermittedRoles() {
		return array("administrator");
	}
	
	public function addScripts() {
		return array("tinyeditor/tinyeditor");
	}
	
    public function getModuleJS() {
    	return $this->moduleBaseDir . "js/pagamento_boleto.js";
    }
    public function getModuleCSS() {
    	return $this->moduleBaseDir . "css/pagamento_boleto.css";
    }
	
	/** REQUIRED FUNCTIONS TO module_pagamento sub-modules */
	protected $parent = null;
	
	public function setParentContext($parentContext = null) {
		if (is_null($this->parent) && is_null($parentContext)) {
			$this->parent = module_pagamento::getInstance();
		} elseif (!is_null($parentContext)) {
			$this->parent = $parentContext;
		}
		
		return $this->parent;
	}
	
	public function getTitle() {
		return _PAGAMENTO_BOLETO_TITLE; // Pagamento com Boleto Bancário
	}
	
	public function getLinks() {
		return array();	
	}
	
	public function getModule() {
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_PAYMENTS;
		
		$smarty = $this -> getSmartyVar();
		
		$defaultBaseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] .'?ctg=module&op=' . __CLASS__; 
		
		$smarty -> assign("T_MODULE_PAGAMENTO_BOLETO_BASEDIR" , $this -> moduleBaseDir);
		$smarty -> assign("T_MODULE_PAGAMENTO_BOLETO_BASEURL", empty($this -> moduleBaseUrl) ? $defaultBaseUrl : $this -> moduleBaseUrl);
		$smarty -> assign("T_MODULE_PAGAMENTO_BOLETO_BASELINK", $this -> moduleBaseLink);		
		
		$smarty -> assign("T_MODULE_PAGAMENTO_ACTION", $selectedAction);
		
		if ($selectedAction == self::VIEW_PROCESSED_FILE) {
			$filename = $_REQUEST['filename'];
			
			$returnStatus = $this->processReturnFile($filename);
			
			
			$returnStatus['registros'] = $this->loadPendingPaymentDataFromFile($returnStatus['registros']);
			
			$smarty -> assign("T_PROCESS_FILE", true);
			$smarty -> assign("T_PROCESS_FILE_STATUS", $returnStatus);
			
			$ocorrencias = eF_getTableData("module_pagamento_boleto_ocorrencias", "id, description");
			
			foreach($ocorrencias as $ocorrencia) {
				$base_ocorrencias[$ocorrencia['id']] = $ocorrencia['description'];
			}
			$smarty -> assign("T_BASE_OCORRENCIAS", $base_ocorrencias);
			
			$liquidacoes = eF_getTableData("module_pagamento_boleto_liquidacao", "id, description");
					
			foreach($liquidacoes as $liquidacao) {
				$base_liquidacao[$liquidacao['id']] = $liquidacao['description'];
			}
			$smarty -> assign("T_BASE_LIQUIDACAO", $base_liquidacao);
			
			if ($_GET['ajax']) {
				$output = $smarty->fetch(
					$this->moduleBaseDir . 'templates/actions/view_processed_file.tpl',
					1024,
					1024,
					false
				);
				echo $output;
				exit;
			}
		} elseif ($selectedAction == self::UPDATE_PROCESSED_FILE) {
			$filename = $_REQUEST['filename'];
			
			$returnStatus = $this->processReturnFile($filename);
			
			$processStatusRegister = array(
				'header'	=> $returnStatus['header'],
				'registros'	=> array(),
				'optional'	=> $returnStatus['optional'],
				'footer'	=> $returnStatus['footer']
			);
			
			// CHECK IF HAVE DATA TO PROCESS
			$returnStatus['registros'] = $this->loadPendingPaymentDataFromFile($returnStatus['registros']);
			
			foreach($returnStatus['registros'] as $returnRegister) {
				if ($returnRegister['tag']['invoice_number_invalid'] === FALSE) {
					// PROCESS REGISTER
					$registerReturned = $this->updateInvoicesByFileRegister($returnRegister, $filename);
					$processStatusRegister['registros'][] = $registerReturned;
				} else {
					$processStatusRegister['registros'][] = $returnRegister;
				}
			}
			
			$smarty -> assign("T_PROCESS_FILE", true);
			$smarty -> assign("T_PROCESS_FILE_STATUS", $processStatusRegister);
			
			$ocorrencias = eF_getTableData("module_pagamento_boleto_ocorrencias", "id, description");
			
			foreach($ocorrencias as $ocorrencia) {
				$base_ocorrencias[$ocorrencia['id']] = $ocorrencia['description'];
			}
			$smarty -> assign("T_BASE_OCORRENCIAS", $base_ocorrencias);
			
			$liquidacoes = eF_getTableData("module_pagamento_boleto_liquidacao", "id, description");
					
			foreach($liquidacoes as $liquidacao) {
				$base_liquidacao[$liquidacao['id']] = $liquidacao['description'];
			}
			$smarty -> assign("T_BASE_LIQUIDACAO", $base_liquidacao);

			if ($_GET['ajax']) {
				$output = $smarty->fetch(
					$this->moduleBaseDir . 'templates/actions/update_processed_file.tpl',
					1024,
					1024,
					false
				);
				echo $output;
				exit;
			}
		}
		
		return false;
	}
	
	public function getSmartyTpl() {
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : "";
		
		$smarty = $this -> getSmartyVar();
		
		$smarty -> assign("T_PAGAMENTO_BOLETO_BASEDIR" , $this -> moduleBaseDir);
		$smarty -> assign("T_PAGAMENTO_BOLETO_BASEURL", $this -> moduleBaseUrl);
		$smarty -> assign("T_PAGAMENTO_BOLETO_BASELINK", $this -> moduleBaseLink);
		
		if ( $selectedAction == self::VIEW_PROCESSED_FILE && !$_GET['ajax']) {
		} elseif ( $selectedAction == self::UPDATE_PROCESSED_FILE && !$_GET['ajax']) {
		}
		return $this->moduleBaseDir . 'templates/default.tpl';
	}
	public function getNavigationLinks() {
		$this->setParentContext();
		$navLinks = $this->parent->getNavigationLinks(module_pagamento::UPDATE_INVOICES_STATUS);
		
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_PAYMENTS;
		
		if ($selectedAction == self::VIEW_PROCESSED_FILE || $selectedAction == self::UPDATE_PROCESSED_FILE) {
			$filename = $_REQUEST['filename'];
			
			$simpleFilename = str_replace($this->moduleBaseDir . 'retorno/', "", $filename);
		
			$navLinks[] = array ('title' => sprintf(__PAGAMENTO_BOLETO_PROCESSED_FILE_DETAILS, '<span class="filename">' . $simpleFilename . '</span>'), 'link' => $this -> moduleBaseUrl . "&action=" . self::VIEW_PROCESSED_FILE . "&filename=" . $filename );
		}
		return $navLinks;
	}
	
	public function processAction($action = module_pagamento::SHOW, $getData = array(), $parentContext = null) {
		
		$this->getModule();
		
		$smarty = $this->getSmartyVar();
		
		switch($action) {
			case module_pagamento::CREATE_PAYMENT_TYPE : {
				break;
			}
			case module_pagamento::EDIT_PAYMENT_TYPE  : {
				return $this->getDataFormTemplate($getData['payment_type_id']);
			}
			case module_pagamento::GET_INVOICE : { 
				if (array_key_exists('invoice_index', $getData)) {
					$invoice_index = $getData['invoice_index'];
				} else {
					$invoice_index = $getData['next_invoice'];
				}
				$nextInvoice = $getData['invoices'][$invoice_index];

				if (
					array_key_exists('payment_type_id', $nextInvoice) &&
					eF_checkParameter($nextInvoice['payment_type_id'], 'id')
				) {
					if ($boleto = $this->getBoletoByInvoice($getData, $nextInvoice)) {
						return $boleto;
					}
				}
				return false;
			}
			case module_pagamento::UPDATE_INVOICES_STATUS : {
				// MAKE UPLAOD FORM
				$this->getUploadInvoicesFormTemplate();
				
				return $this->moduleBaseDir . 'templates/actions/update_invoices_status.tpl';
			}
			/*
			case module_pagamento::PENDING_PAYMENTS :
			case self::PREVIEW_PAYMENTS_RETURN : {
				$this->loadPendingPaymentsTpl($parentContext);
				return $this -> moduleBaseDir . "module_pagamento_boleto.tpl";
			}
			case module_pagamento::SHOW : 
			default : {
				// SHOW DEFAULT FORM
				return $this -> moduleBaseDir . "module_pagamento_boleto.tpl";
			}
			*/
		}
		return false;
	}
	
	private function generateNossoNumero($payment_id, $parcela_id) {
		do {
			$nosso_numero_sem_DV = sprintf(self::NOSSO_NUMERO_TEMPLATE_SEM_DV, substr($payment_id, 0, 4), substr($parcela_id, 0, 2), 0);
			$nosso_numero = $nosso_numero_sem_DV . $this->generateModule10($nosso_numero_sem_DV);
			
			// CHECK IF EXISTS, IF TRUE, GENERATE AGAIN
			$existsCount = eF_countTableData(
				// table 
				"module_pagamento_invoices", 	
				// fields
				"invoice_id",
			  	// where clause
			  	"invoice_id = '" . $nosso_numero . "'"
			);
			$totalcount = $existsCount[0]['count'];
		} while( $totalcount > 0 );
		return $nosso_numero;
	}
	
	protected function makeBoletoFromInvoice($payment, $invoice, $dadosboleto = null) {
		
		$paymentType = $this->parent->getPaymentTypeById($invoice['payment_type_id']);
		
		$dadosfixos = $paymentType['tag'];
		$cliente = $payment['cliente'];
		// DADOS DO BOLETO PARA O SEU CLIENTE
		//$dias_de_prazo_para_pagamento = 3;
		//$taxa_boleto = 2.95;
		if (is_null($dadosboleto)) {
			$dadosboleto = array(
				"data_documento"		=> date("d/m/Y"), // Data de emissão do Boleto
				"data_processamento"	=> date("d/m/Y") // Data de processamento do boleto (opcional)	
			);
			
			if (!empty($invoice['invoice_id'])) {
				$nossoNumero = $invoice['invoice_id'];
			} else {
				$nossoNumero = $this->generateNossoNumero($payment['payment_id'], $invoice['parcela_index']);	
			}
		
			$dadosboleto["nosso_numero"] = $nossoNumero;  // Nosso numero - REGRA: Máximo de 8 caracteres!
			$dadosboleto["numero_documento"] = $nossoNumero;	// Num do pedido ou nosso numero
		}

		if (!empty($invoice['invoice_id'])) {
			$dadosboleto["nosso_numero"] = $invoice['invoice_id'];
		}
		
		$dadosboleto["sacado"] 		= $cliente['name'] . ' ' . $cliente['surname'];
		$dadosboleto["endereco1"] 	= $cliente['endereco'] . ' ' . $cliente['numero'] . ' ' . $cliente['complemento'];
		$dadosboleto["endereco2"] 	= $cliente['cidade'] . ' - ' .$cliente['uf'] . ' -  CEP: ' . $cliente['cep'];
		
		// Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
		if ($invoice['parcela_index'] == 1) {
			// ADJUST data_vencimento FROM prazo_pagamento_matricula
			if (!is_null($invoice['data_vencimento']) && strtotime($invoice['data_vencimento']) > time()) {
				$dadosboleto["data_vencimento"] = date("d/m/Y", strtotime($invoice['data_vencimento']));
			} else {
				$dadosboleto["data_vencimento"] = date("d/m/Y", time() + ($dadosfixos['prazo_pagamento_matricula'] * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
			}
			
			$dadosboleto["valor_boleto"] = number_format($invoice['valor'], 2, ',', '');
		} else {
			// CALCULATE DATA DE VENCIMENTO

			if (is_null($invoice['data_vencimento'])) {
				$month = date('m', strtotime($payment['data_registro'])) + $invoice['parcela_index'];
				
				if (intval($payment['vencimento']) < date('d', strtotime($payment['data_registro']))) {
					// SE FOI REGISTRADO APÓS O MESMO DIA REGISTRADO DO VENCIMENTO, ENTÂO JOGAR UM MÊS PRA FRENTE
					$month;
				}
				
				
				$invoice['data_vencimento'] = date('Y-m-d H:i:s', mktime(0, 0, 0,
					$month,
					intval($payment['vencimento']), 
					date('Y', strtotime($payment['data_registro']))
				));
			}
			$dadosboleto["data_vencimento"] = date("d/m/Y", strtotime($invoice['data_vencimento']));
			// CHECK IF IS VENCIDO!!
			if (strtotime($invoice['data_vencimento']) > time()) {
				$dadosboleto["valor_boleto"] = number_format($invoice['valor'], 2, ',', '');
			} else {
				$dadosboleto["valor_boleto"] = number_format($invoice['valor'], 2, ',', '');
			}
		}
		
		// SAVE tag Field 
		return $dadosboleto;
	}

	protected function makeBoletoIntructionsSubstitute($payment, $invoice, $instructions) {
		/** @todo Buscar estes valores abaixo através da configuração fixas do módulo  */

		$percentualMultaAoMes = 0.02;
		$percentualJurosAoDia = 0.0033;
		
		$decimal_sep	= isset($GLOBALS['configuration']["decimal_point"]) ? $GLOBALS['configuration']["decimal_point"] : '.';
		$thousando_sep 	= isset($GLOBALS['configuration']["thousands_sep"]) ? $GLOBALS['configuration']["thousands_sep"] : '';
		
		/** @todo Incluir está funcionalidade em uma biblioteca em separado.  */
		global $CURRENCYSYMBOLS;
		$currencySymbol = $CURRENCYSYMBOLS[$GLOBALS['configuration']["currency"]];
		
		$search = array(
			"##multa.total##",
			"##vencimento##",
			"##juros.dia.total##",
			"##desconto.percentual##"
		);
		
		$replace = array(
			$currencySymbol . ' ' . number_format(intval($invoice['valor']) * $percentualMultaAoMes, 2, $decimal_sep, $thousando_sep),
			date('d/m/Y', strtotime($invoice['data_vencimento'])),
			$currencySymbol . ' ' . number_format(intval($invoice['valor']) * $percentualJurosAoDia, 2, $decimal_sep, $thousando_sep),
			sprintf("%d%%", number_format($payment['desconto']))
		);

		return str_replace($search, $replace, $instructions);
	}

	protected function getBoletoByInvoice($payment, $invoice) {
		$paymentType = $this->parent->getPaymentTypeById($invoice['payment_type_id']);
		
		if ($paymentType) {
			// GET CLIENTE (STUDENT) DATA
			$cliente = $payment['cliente'];

			$dadosfixos = $paymentType['tag'];
			$dadosinvoice = $invoice['tag'];
	
//			if (is_null($dadosboleto)) {
//				$dadosboleto = array();
				// NEED TO CREATE tag FIELD OFF INVOICE
			$dadosinvoice = $this->makeBoletoFromInvoice($payment, $invoice, $dadosinvoice);


			
			if ($invoice['parcela_index'] == 1 && !empty($dadosfixos['instrucoes_matricula'])) {
				$dadosfixos['instrucoes'] = $this->makeBoletoIntructionsSubstitute($payment, $invoice, $dadosfixos['instrucoes_matricula']);
			} else {
				$dadosfixos['instrucoes'] = $this->makeBoletoIntructionsSubstitute($payment, $invoice, $dadosfixos['instrucoes']);	
			}
			
			$this->parent->updateInvoiceById(
				$payment['payment_id'], 
				$invoice['parcela_index'], 
				array('invoice_id'	=> $dadosinvoice['nosso_numero'], 'tag' => json_encode($dadosinvoice))
			);
			
			$dadosboleto = array_merge($dadosfixos, $dadosinvoice);
			
			

			ob_start();
			
			// NÃO ALTERAR!
			include($this -> moduleBaseDir . "classes/funcoes_itau.php");

			include($this -> moduleBaseDir . "classes/layout_itau.php");    		
			$boleto_html = ob_get_contents();
			ob_clean();
						
			$result = array(
				'dados'		=> $dadosboleto,
				'html'	 	=> $boleto_html,
				'output'	=> 'blank'
			);
			
			return $result;
		}
		return false;
	}
	
	private function generateModule10($num) {
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
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
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
	
	
   	
	/** FORMULÁRIOS DE CONFIGURAÇÃO */
	private function getDataForm($payment_type_id) {
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

		$form = new HTML_QuickForm(__CLASS__ . "_configuration", "post", $_SERVER['REQUEST_URI'], "", null, true);
	
		$form -> addElement('text', 'agencia', _BOLETO_AGENCIA_LABEL, array('class' => 'medium', 'title' => 'Número da agencia, sem o digito'));
		// Num da conta, sem digito
		$contaElem[] = $form -> createElement('text', 'conta', _BOLETO_CONTA_LABEL, array('class' => 'small', 'style'	=> 'float: left; margin-right: 5px;', 'title' => 'Número da conta, sem o digito'));
		// Digito do Num da conta
		$contaElem[] = $form -> createElement('text', 'conta_dv', _BOLETO_CONTA_DV_LABEL, array('class' => 'medium', 'style' => 'width: 30px', 'title' => 'Dígito Verificador da conta'));
		$form->addGroup($contaElem, 'conta_corrente', _BOLETO_CONTA_LABEL, ' ');
	
		// DADOS PERSONALIZADOS - ITAÚ
		// Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157
		$form -> addElement('select', 'carteira', _BOLETO_CARTEIRA_LABEL, array('175' => '175 - Cobrança não registrada'), array('class' => 'medium'));
		
		// DADOS DO CEDENTE
		//$dadosboleto[""] = "Boleto";
		$form -> addElement('text', 'identificacao', _BOLETO_CEDENTE_IDENTIFICACAO_LABEL, 'class = "full"');
		//$dadosboleto[""] = "03.252.893/0001-50";
		// CPF OU CNPJ, COM (ou SEM) FORMATAÇÃO
		$form -> addElement('text', 'cpf_cnpj', _BOLETO_CEDENTE_CNPJ_LABEL, 'class = "medium cnpj"');

		//$dadosboleto[""] = "Rua Coronel Dulcídio, 517 - Shopping Novo Batel, Piso D, Loja 79 - Batel";
		$form -> addElement('text', 'cep', _BOLETO_CEDENTE_CEP_LABEL, 'class = "small"');
		// endereco
		$form -> addElement('text', 'logradouro', _BOLETO_CEDENTE_LOGRADOURO_LABEL, 'class = "full"');
		
		$form -> addElement('text', 'numero', _BOLETO_CEDENTE_NUMERO_LABEL, 'class = "small"');
		
		$form -> addElement('text', 'complemento', _BOLETO_CEDENTE_COMPLEMENTO_LABEL, 'class = "medium"');
		
		$form -> addElement('text', 'bairro', _BOLETO_CEDENTE_BAIRRO_LABEL, 'class = "large"');
		// cidade_uf
		$form -> addElement('text', 'cidade', _BOLETO_CEDENTE_CIDADE_LABEL, 'class = "large"');
		
		$stateList = localization::getStateList();
		//http://cep.republicavirtual.com.br/web_cep.php?cep={$cep}&formato=json
		$form -> addElement('select', 'uf' , _BOLETO_CEDENTE_UF_LABEL, $stateList, 'class = "small"');
		
		//$dadosboleto[""] = "AMÉRICAS INTERNACIONAL LTDA.";
		$form -> addElement('text', 'cedente', _BOLETO_CEDENTE_NOME_LABEL, 'class = "full"');
		
		// INFORMACOES PARA O CLIENTE
		
		$form -> addElement('textarea', 'demonstrativo', _BOLETO_DEMOSTRATIVO_LABEL, array('id' => 'boleto-demonstrativo', 'class' => 'tinyeditor', 'rows' => 4));
		//$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
		//$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon";
		//$dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
		
		$form -> addElement('textarea', 'instrucoes_matricula', __XPAYMENT_BOLETO_INSTRUCOES_MATRICULA_LABEL, array('id' => 'boleto-instrucoes-matricula', 'class' => 'tinyeditor', 'rows' => 3));
		$form -> addElement('textarea', 'instrucoes', _BOLETO_INSTRUCOES_LABEL, array('id' => 'boleto-instrucoes', 'class' => 'tinyeditor', 'rows' => 3));
		//$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
		//$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
		//$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
		//$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";
		

		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		//$dadosboleto["quantidade"] = "";
		$form -> addElement('text', 'quantidade', _BOLETO_QUANTIDADE_LABEL, 'class = "small"');
		//$dadosboleto["valor_unitario"] = "";
		$form -> addElement('text', 'valor_unitario', _BOLETO_VLRUNITARIO_LABEL, 'class = "small"');
		//$dadosboleto["aceite"] = "";
		$form -> addElement('text', 'aceite', _BOLETO_ACEITE_LABEL, 'class = "small"');
		//$dadosboleto["especie"] = "R$";
		$form -> addElement('text', 'especie', _BOLETO_ESPECIE_LABEL, 'class = "small"');
		//$dadosboleto["especie_doc"] = "";
		$form -> addElement('text', 'especie_doc', _BOLETO_ESPECIE_DOC_LABEL, 'class = "small"');
		
		// INFORMAÇÕES GERAIS
		//$dadosboleto['prazo_pagamento_matricula'] = 3;
		$form -> addElement('text', 'prazo_pagamento_matricula', _BOLETO_PRAZO_MATRICULA_LABEL, 'class = "small"');
		
		$form -> addElement('submit', 'submit_preview', _BOLETO_VISUALIZAR_LABEL);
		$form -> addElement('submit', 'submit_apply', _MODULE_PAGAMENTO_BOLETO_SUBMIT);
		
		return $this->populateDataForm($payment_type_id, $form);
	}
	
	private function populateDataForm($payment_type_id, $form = null) {
		if (is_null($form)) {
			$form = $this->getDataForm($payment_type_id);
		}
		
		$paymentData = $this->parent->getPaymentTypeById($payment_type_id);
		
		if (is_array($paymentData['tag'])) {
			$form->setDefaults($paymentData['tag']);
		}
		
		return $form;
	}
	
	private function processDataForm($payment_type_id) {
		
		$form = $this->getDataForm($payment_type_id);
		
		if ($form -> isSubmitted() && $form -> validate()) {
			if (!is_null($form->exportValue('submit_apply'))) {
				$values = $form->exportValues();
				unset($values['submit_apply']);
				$tagData = json_encode($values);
				
				if ($this->parent->updatePaymentTypeById($payment_type_id, array('tag'	=> $tagData))) {
					$this->setMessageVar(_MODULE_PAGAMENTO_BOLETO_PAYMENT_TYPE_SAVE_SUCCESS, 'success');
				} else {
					$this->setMessageVar(_UNDEFINEDERROR, 'warning');
				}
				return $this->populateDataForm($payment_type_id, $form);
			} else {
				$this->setMessageVar(_UNDEFINEDERROR, 'warning');
			}
		} else {
			return false;
		}
	}
	
	private function getDataFormTemplate($payment_type_id) {
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
		
//		$form = $this->getDataForm($payment_type_id);
		
		if ($this->processDataForm($payment_type_id)) {
			$form = $this->populateDataForm($payment_type_id, $form);
		} else {
			$form = $this->getDataForm($payment_type_id);
		}
		
		$smarty = $this->getSmartyVar();
			
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
	 	$formRender = $renderer -> toArray();
		 				
		$smarty -> assign('T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM', $formRender);
	
		return $this->moduleBaseDir . 'templates/actions/edit_payment_type.tpl';
	}
	
	
	
	/** FORMULÁRIOS DE UPLOAD */
	private function getUploadInvoicesForm() {
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

		$form = new HTML_QuickForm(__CLASS__ . "_upload_invoices", "post", $_SERVER['REQUEST_URI'], "", null, true);
		
		$form -> addElement('text', 'file_title', _MODULE_PAGAMENTO_BOLETO_FILE_TITLE, 'class = "large"');
		
		$form -> addElement('file', 'file_upload', _MODULE_PAGAMENTO_BOLETO_RETURN_FILE, 'class = "large"');
		$form -> setMaxFileSize(FileSystemTree :: getUploadMaxSize() * 1024); //getUploadMaxSize returns size in KB
		
		//FileSystemTree :: getUploadMaxSize() * 1024
		
		$form -> addElement('submit', 'submit_apply', _MODULE_PAGAMENTO_BOLETO_SUBMIT);
		
		return $this->populateUploadInvoicesForm($form);
	}
	
	private function populateUploadInvoicesForm($form = null) {
		if (is_null($form)) {
			$form = $this->getUploadInvoicesForm();
		}
		/*
		$paymentData = $this->parent->getPaymentTypeById($payment_type_id);
		
		if (is_array($paymentData['tag'])) {
			$form->setDefaults($paymentData['tag']);
		}
		*/
		return $form;
	}
		
	private function processUploadInvoicesForm() {
		
		$iesData = $this->getCurrentIes();
		
		$iesID = $iesData['id'];
		
		$form = $this->getUploadInvoicesForm();
		
		if ($form -> isSubmitted() && $form -> validate()) {
			if (!is_null($form->exportValue('submit_apply'))) {
				//$values = $form->exportValues();
				
				$file =& $form->getElement('file_upload');

				$destDir = $this -> moduleBaseDir . 'retorno/';
				
				$fileData = $file->getValue();
				
				$originalFileName = basename($fileData['name'], "RET");
				
				//$fileOriginalName = $fileData;
				
				$count = 1;
				do {
					$retnumbers = sprintf("%d-%04d", date("Ymd"), $count);
					$retModule10 = $this->generateModule10($retnumbers);
					$retFileName = sprintf("%s-%s-%04d-%d.ret", date("Y_m_d"), $originalFileName, $count, $retModule10); 
					$count++;
				} while(file_exists($destDir . 'proc/' . $retFileName));
				
				if ($file->moveUploadedFile($destDir . 'proc/', $retFileName)) {
					// SEND MESSAGE
					$returnStatus = $this->processReturnFile($destDir . 'proc/' . $retFileName);
					
					// GET PAYMENT TYPE ID FROM FILE
					$agencia	= $returnStatus['header']['agencia']['parseddata'];
					$conta 		= $returnStatus['header']['conta']['parseddata'];
					
					$paymentTypes = $this->parent->getPaymentTypes();
					$selectedPaymentType = null;
					foreach($paymentTypes as $paymentType) {
						$payTypeAgencia = $paymentType['tag']['agencia'];
						$payTypeCC = $paymentType['tag']['conta_corrente']['conta'];
						
						if ($agencia == $payTypeAgencia && $conta == $payTypeCC) {
							$selectedPaymentType = $paymentType;
							break;
						}
					}
					if (!is_null($selectedPaymentType)) {
						// MOVE $destDir . 'proc/' . $retFileName TO $destDir . $selectedPaymentType['payment_type_id']. '/' . $retFileName;
						rename(
							$destDir . 'proc/' . $retFileName,
							$returnFileName = $destDir . $selectedPaymentType['payment_type_id']. '/' . $retFileName
						);
					} else {
						rename(
							$destDir . 'proc/' . $retFileName,
							$returnFileName = $destDir . 'unknown/' . $retFileName
						);
					}
					$this->moduleBaseUrl = empty($this->moduleBaseUrl) ? '/' . $_SESSION['s_type']. ".php?ctg=module&op=" . __CLASS__ : $this->moduleBaseUrl;
					
					$url = $this->moduleBaseUrl . "&action=update_processed_file&filename=" . urlencode($returnFileName);
					
					eF_redirect($url);
					exit;
					
					
/*					


					$smarty -> assign("T_PROCESS_FILE", true);
					$smarty -> assign("T_IMPORT_FILE_STATUS", $returnStatus);
					$ocorrencias = eF_getTableData("module_pagamento_boleto_ocorrencias", "*");
					
					foreach($ocorrencias as $ocorrencia) {
						$base_ocorrencias[$ocorrencia['codigo']] = $ocorrencia['descricao'];
					}
					$smarty -> assign("T_BASE_OCORRENCIAS", $base_ocorrencias);
					
					$liquidacoes = eF_getTableData("module_pagamento_boleto_liquidacao", "codigo, descricao");
					
					foreach($liquidacoes as $liquidacao) {
						$base_liquidacao[$liquidacao['codigo']] = $liquidacao['descricao'];
					}
					$smarty -> assign("T_BASE_LIQUIDACAO", $base_liquidacao);
					
					
	//				$smarty -> assign("T_IMPORT_FILE_STATUS", $returnStatus['footer']);
					

					*/
					
					
					
					
				} else {
					$this->setMessageVar(_MODULE_PAGAMENTO_BOLETO_SEND_FILE_ERROR, "error");
				}				
				
				return $this->populateUploadInvoicesForm($payment_type_id, $form);
			} else {
				$this->setMessageVar(_UNDEFINEDERROR, 'warning');
			}
		} else {
			return false;
		}
	}
	
	private function getUploadInvoicesFormTemplate() {
		//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors
		
//		$form = $this->getDataForm($payment_type_id);
		
		if ($this->processUploadInvoicesForm()) {
			$form = $this->populateUploadInvoicesForm($form);
		} else {
			$form = $this->getUploadInvoicesForm();
		}
		
		$smarty = $this->getSmartyVar();
			
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
	 	$formRender = $renderer -> toArray();
		 				
		$smarty -> assign('T_MODULE_PAGAMENTO_BOLETO_FILE_FORM', $formRender);
	
		return $this->moduleBaseDir . 'templates/actions/update_invoices_status.tpl';
	}
	
	/** MODEL FUNCTIONS, DATA MANIPULATION */
	private function getPaymentIdFromNossoNumero($nosso_numero) {
		
		$paymentInfo = sscanf($nosso_numero, self::NOSSO_NUMERO_TEMPLATE);
		$paymentIds = $this->parent->getPaymentIdByInvoiceId($nosso_numero, false);
		
		if ($paymentIds === FALSE || count($paymentIds) == 0) {
			// TENTAR BUSCAR O PAGAMENTO PARCELA, PELA INVOICE
			$paymentID = $paymentInfo[self::PAYMENT_ID_INDEX];
			$parcelaIndex = $paymentInfo[self::PAYMENT_PARCELA_INDEX];
			
			$paymentDbInfo = $this->parent->getPaymentById($paymentID);

			if ($paymentDbInfo === FALSE || count($paymentDbInfo) == 0) {
				return false;
			}
			// CHECK IF INVOICE INDEX EXISTS
			if (!array_key_exists($parcelaIndex, $paymentDbInfo['invoices'])) {
				return false;
			}
			
			$invoiceNumberDiffers = true;
			// $invoiceNumberDuplicate = false;
		} elseif (count($paymentIds) > 1) {
			// TENTAR BUSCAR O PAGAMENTO PARCELA, PELA INVOICE
			$paymentID = $paymentInfo[self::PAYMENT_ID_INDEX];
			$parcelaIndex = $paymentInfo[self::PAYMENT_PARCELA_INDEX];
			if (!in_array($paymentID, $paymentIds)) {
				return false;
			}
			$invoiceNumberDiffers = false;
			// $invoiceNumberDuplicate = true;
		} else {
			$paymentID 		= $paymentIds[0];
			$parcelaIndex 	= $paymentInfo[self::PAYMENT_PARCELA_INDEX];
			
			$invoiceNumberDiffers = false;
			// $invoiceNumberDuplicate = false;
		}
		
		if (!$paymentDbInfo) {
			$paymentDbInfo = $this->parent->getPaymentById($paymentID);
		}
		
		/** @todo Substituir a chamada "$this->parent->getPaymentById", por alguma que busque somente o cliente */
		return array_merge($paymentDbInfo, array('parcela_index' => $parcelaIndex, 'invoice_number_invalid' => $invoiceNumberDiffers)); 
	}

	private function getClienteFromNossoNumero($nosso_numero) {
		
		$paymentInfo = sscanf($nosso_numero, self::NOSSO_NUMERO_TEMPLATE);
		$paymentIds = $this->parent->getPaymentIdByInvoiceId($nosso_numero, false);
		
		if ($paymentIds === FALSE || count($paymentIds) == 0) {
			// TENTAR BUSCAR O PAGAMENTO PARCELA, PELA INVOICE
			$paymentID = $paymentInfo[self::PAYMENT_ID_INDEX];
			$parcelaIndex = $paymentInfo[self::PAYMENT_PARCELA_INDEX];
			
			$paymentDbInfo = $this->parent->getPaymentById($paymentID);

			if ($paymentDbInfo === FALSE || count($paymentDbInfo) == 0) {
				return __PAGAMENTO_BOLETO_SACADO_NAO_ENCONTRADO;
			}
			// CHECK IF INVOICE INDEX EXISTS
			if (!array_key_exists($parcelaIndex, $paymentDbInfo['invoices'])) {
				return __PAGAMENTO_BOLETO_NAO_EXISTE;
			}
		} elseif (count($paymentIds) > 1) {
			// TENTAR BUSCAR O PAGAMENTO PARCELA, PELA INVOICE
			$paymentID = $paymentInfo[self::PAYMENT_ID_INDEX];
			$parcelaIndex = $paymentInfo[self::PAYMENT_PARCELA_INDEX];

			if (!in_array($paymentID, $paymentIds)) {
				return __PAGAMENTO_BOLETO_SACADO_NAO_ENCONTRADO;
			}
			$paymentDbInfo = $this->parent->getPaymentById($paymentID);
		} else {
			$paymentDbInfo = $this->parent->getPaymentById($paymentIds[0]);
		}
		/** @todo Substituir a chamada "$this->parent->getPaymentById", por alguma que busque somente o cliente */
		if ($paymentDbInfo == FALSE || count($paymentDbInfo) == 0) {
			return __PAGAMENTO_BOLETO_SACADO_NAO_ENCONTRADO;
		} 
		return $paymentDbInfo['cliente']['name'] . ' ' . $paymentDbInfo['cliente']['surname']; 
	}
	
	/** PROCESSAMENTO DE RETORNO */
	private function updateInvoicesByFileRegister($register, $filename = null) {
		

/*
CREATE TABLE IF NOT EXISTS `module_pagamento_boleto_invoices_return`(
  `payment_id` mediumint(8) NOT NULL,
  `parcela_index` int(11) NOT NULL DEFAULT '1',
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nosso_numero` text NOT NULL,
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `ocorrencia_id` VARCHAR( 3 ) NULL,
  `liquidacao_id` VARCHAR( 3 ) NULL,
  `valor_titulo` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_abatimento` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_desconto` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_juros_multa` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_outros_creditos` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `valor_total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tag` text NOT NULL,
  PRIMARY KEY (`payment_id`,`parcela_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
*/
		$register['updated'] = false;
		
		$insertData = array(
			'payment_id'			=> $register['tag']['payment_id'],
			'parcela_index'			=> $register['tag']['parcela_index'],
			//'data_registro'			=> '',
			'nosso_numero'			=> $register['nosso_numero']['parseddata'],
			'data_pagamento'		=> date('Y-m-d', $register['data_ocorrencia']['parseddata']),
			'ocorrencia_id'			=> $register['cod_ocorrencia']['parseddata'],
			'liquidacao_id'			=> $register['cod_liquidacao']['parseddata'],
			'valor_titulo'			=> $register['valor_titulo']['parseddata'],
			'valor_abatimento'		=> $register['valor_abatimento']['parseddata'],
			'valor_desconto'		=> $register['valor_desconto']['parseddata'],
			'valor_juros_multa'		=> $register['valor_juros_multa']['parseddata'],
			'valor_outros_creditos'	=> $register['valor_outros_creditos']['parseddata'],
			'valor_total'			=> $register['valor_total']['parseddata'],
			'tag'					=> json_encode($register['tag']),
			'filename'				=> $filename
		);
		
		// SE REGISTRO DE RETORNO JÁ EXISTE, ENTÂO ATUALIZAR.
		$dataReturn = eF_insertOrupdateTableData(
			"module_pagamento_boleto_invoices_return", 
			$insertData, 
			sprintf("payment_id = %d AND parcela_index = %d", $insertData['payment_id'], $insertData['parcela_index'])
		);
		
		if ($dataReturn) {
			$register['updated'] = true;
	
			$this->parent->onPaymentReceivedEvent($this, array(
				'payment_id'	=> $register['tag']['payment_id'],
				'enrollment_id'	=> $register['tag']['enrollment_id'],
				'parcela_index'	=> $register['tag']['parcela_index']
			));
		}
		return $register;		
	}
	
	
	private function loadPendingPaymentDataFromFile($fileRegisters) {
		$this->setParentContext();
		
		foreach($fileRegisters as $register) {
			$paymentIDData = $this->getPaymentIdFromNossoNumero($register['nosso_numero']['originaldata']);
			
//			var_dump($paymentIDData);
			
			if (!array_key_exists('tag', $register) || is_array($register['tag'])) {
				$register['tag'] = array();
			}
			if ($paymentIDData === FALSE) {
				$paymentIDData = array(
					'invoice_number_invalid'	=> true
				);
			}
			$register['tag'] = array_merge($register['tag'], $paymentIDData);
			
			//var_dump(empty(trim($register['nome_sacado']["originaldata"])));
			
			if (trim($register['nome_sacado']["originaldata"]) == "") {
				$register['nome_sacado']["formatteddata"] = 
					$register['nome_sacado']["parseddata"] = 
						$this->getClienteFromNossoNumero($register['nosso_numero']['originaldata']);
			}

			$result[] = $register;
		}
		return $result;
	}
	
	private function processReturnFile($fullFileName) {
		// CHECK FILE INTEGRITY AND IMPORT INFORMATION
		// STEPS: 
		// 1. MOSTRAR TODOS OS BOLETOS ENCONTRADOS NO SISTEMA, E MARCAR COM O STATUS VINDO DO ARQUIVO.
		// 2. MOSTRAR OS BOLETOS NÃO ENCONTRADOS, PARA RESOLUÇÂO OU REGISTRO
		// 3. MOSTRAR O STATUS DA POSSÍVEL IMPORTAÇÃO

		

		// 1. Carregar todos os boletos encontrados no arquivo, e marcar quais foram encontrados no sistema (ordenar por itens não encontrados).
		$lines = file($fullFileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		//$handle = fopen($destDir . $retFileName, 'r');
		
		//echo $result;
		
		$result = array();
				
		// BEGIN PROCESS FILE
		// HEADER FILE
		foreach($lines as $key => $line) {
			if ($line{0} === "0") {
				$result['header'] = $this->processReturnFileHeader($line);
			} elseif ($line{0} === "1") {
				$result['registros'][] = $this->processReturnFileTransaction($line);
			} elseif ($line{0} === "4") {
				$result['optional'][] = $this->processReturnFileOptional($line);
			} elseif ($line{0} === "9") {
				$result['footer'] = $this->processReturnFileFooter($line);
			}
		}
		return $result;
	}
		
	private function processReturnFileHeader($fileLine) {
		if ($fileLine{0} != 0) {
			throw new module_pagamento_boletoException(_PAGAMENTO_REGISTERTYPEISDIFERENT, module_pagamentoException::DIFERENT_REGISTERTYPE);
		}
			
		$headerParameters = array(
		// DESCRIÇÃO				INTERVALO	TIPO	DEFAULT				OBSERVAÇÃO
		// TIPO DE REGISTRO			001 001		9(01)	0					
			'tipo'						=> array(
				'label'			=> 'Tipo de Registro',
				'description'	=> 'IDENTIFICAÇÃO DO REGISTRO HEADER',
				'type'			=> 'int',
				'size'			=> 1
			),
		// CÓDIGO DE RETORNO       	002 002  	9(01) 	2
			'cod_retorno'				=> array(
				'label'			=> 'Código de Retorno',
				'description'	=> 'IDENTIFICAÇÃO DO ARQUIVO RETORNO',
				'type'			=> 'int',
				'size'			=> 1
			),
		// LITERAL DE RETORNO      	003 009 	X(07)  	RETORNO				
			'retorno'					=> array(
				'label'			=> 'Retorno',
				'description'	=> 'IDENTIFICAÇÃO. POR EXTENSO DO TIPO DE MOVIMENTO',
				'type'			=> 'string',
				'size'			=> 7
			),
		// CÓDIGO DO SERVIÇO       	010 011  	9(02) 	01					
			'cod_servico'				=> array(
				'label'			=> 'Código do Serviço',
				'description'	=> 'IDENTIFICAÇÃO DO TIPO DE SERVIÇO',
				'type'			=> 'int',
				'size'			=> 2
			),
		// LITERAL DE SERVIÇO      	012 026 	X(15)  	COBRANCA			
			'servico'					=> array(
				'label'			=> 'Serviço',
				'description'	=> 'IDENTIFICAÇÃO POR EXTENSO DO TIPO DE SERVIÇO',
				'type'			=> 'string',
				'size'			=> 15
			),
		// AGÊNCIA                 	027 030  	9(04)	""					
			'agencia'					=> array(
				'label'			=> 'Agência',
				'description'	=> 'AGÊNCIA MANTENEDORA DA CONTA',
				'type'			=> 'int',
				'size'			=> 4
			),
		// ZEROS                   	031 032  	9(02)	“00”				
			'complemento1'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DE REGISTRO',
				'type'			=> 'null',
				'size'			=> 2
			),
		// CONTA                   	033 037  	9(05)	""					
			'conta'						=> array(
				'label'			=> 'Conta',
				'description'	=> 'NÚMERO DA CONTA CORRENTE DA EMPRESA',
				'type'			=> 'int',
				'size'			=> 5
			),
		// DAC                     	038 038  	9(01)	""					
			'dac'						=> array(
				'label'			=> 'DV da Conta',
				'description'	=> 'DÍGITO DE AUTO CONFERÊNCIA AG/CONTA EMPRESA',
				'type'			=> 'int',
				'size'			=> 1
			),
		// BRANCOS                 	039 046 	X(08)	""					
			'complemento2'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 8
			),
		// NOME DA EMPRESA         	047 076 	X(30)	""					
			'nome_empresa'				=> array(
				'label'			=> 'Nome da Empresa',
				'description'	=> 'NOME POR EXTENSO DA "EMPRESA MÃE”',
				'type'			=> 'string',
				'size'			=> 30
			),
		// CÓDIGO DO BANCO         	077 079  	9(03)	341					
			'cod_banco'					=> array(
				'label'			=> 'Código do Banco',
				'description'	=> 'NÚMERO DO BANCO NA CÂMARA DE COMPENSAÇÃO',
				'type'			=> 'int',
				'size'			=> 3
			),
		// NOME DO BANCO           	080 094 	X(15)	BANCO ITAU SA		
			'nome_banco'				=> array(
				'label'			=> 'Nome do Banco',
				'description'	=> 'NOME POR EXTENSO DO BANCO COBRADOR',
				'type'			=> 'string',
				'size'			=> 15
			),
		// DATA DE GERAÇÃO         	095 100  	9(06)	DDMMAA				
			'data_geracao'				=> array(
				'label'			=> 'Data da Geração',
				'description'	=> 'DATA DE GERAÇÃO DO ARQUIVO',
				'type'			=> 'date',
				'size'			=> 6
			),
		// DENSIDADE               	101 105  	9(05)	""					
			'densidade'					=> array(
				'label'			=> 'Densidade',
				'description'	=> 'UNIDADE DA DENSIDADE',
				'type'			=> 'int',
				'size'			=> 5
			),
		// UNIDADE DE DENSID.      	106 108 	X(03)	BPI					
			'uni_densidade'				=> array(
				'label'			=> 'Uni. Densidade',
				'description'	=> 'DENSIDADE DE GRAVAÇÃO DO ARQUIVO',
				'type'			=> 'string',
				'size'			=> 3
			),
		// No SEQ. ARQUIVO RET.    	109 113  	9(05)	""					
			'nro_seq_arquivo_retorno'	=> array(
				'label'			=> 'Sequencial do Arquivo',
				'description'	=> 'NÚMERO SEQÜENCIAL DO ARQUIVO RETORNO',
				'type'			=> 'int',
				'size'			=> 5
			),
		// DATA DE CRÉDITO         	114 119  	9(06)	DDMMAA				
			'data_credito'				=> array(
				'label'			=> 'Data do Crédito',
				'description'	=> 'DATA DE CRÉDITO DOS LANÇAMENTOS',
				'type'			=> 'date',
				'size'			=> 6
			),
		// BRANCOS					120 394 	X(275)	""					
			'complemento3'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 275
			),
		// NÚMERO SEQÜENCIAL		395 400  	9(06)	000001				
			'nro_sequencial_registro'	=> array(
				'label'			=> 'Sequencial do Registro',
				'description'	=> 'NÚMERO SEQÜENCIAL DO REGISTRO NO ARQUIVO',
				'type'			=> 'int',
				'size'			=> 6
			)
		);
		$sizes = array();
		foreach($headerParameters as $param) {
			$sizes[] = $param['size'];
		}
		
		$headerData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$headerData = array_combine(array_keys($headerParameters), $headerData);
		
		$result = array();
		
		foreach($headerParameters as $headerName => $headerItem) {
			$toMerge = $this->coercionDataByType($headerData[$headerName], $headerItem['type'], $headerItem['format']);
			$result[$headerName] = array_merge($headerItem, $toMerge);
		}
		
		return $result;
		
	}
	
	private function processReturnFileTransaction($fileLine) {
		if ($fileLine{0} != 1) {
			throw new module_pagamento_boletoException(_PAGAMENTO_REGISTERTYPEISDIFERENT, module_pagamentoException::DIFERENT_REGISTERTYPE);
		}
		
		$transParameters = array(
		// TIPO DE REGISTRO			001 001    9(01)   	1				
			'tipo'						=> array(
				'label'			=> 'Tipo de Registro',
				'description'	=> 'IDENTIFICAÇÃO DO REGISTRO TRANSAÇÃO',
				'type'			=> 'int',
				'size'			=> 1
			),
		// CÓDIGO DE INSCRIÇÃO		002 003    9(02)   	01=CPF 02=CNPJ	
			'cod_inscricao'				=> array(
				'label'			=> 'Código da Inscrição',
				'description'	=> 'IDENTIFICAÇÃO DO TIPO DE INSCRIÇÃO/EMPRESA',
				'type'			=> 'int',
				'size'			=> 2
			),
		// NÚMERO DE INSCRIÇÃO      004 017    9(14)					
			'num_inscricao'				=> array(
				'label'			=> 'Número da Inscrição',
				'description'	=> 'NÚMERO DE INSCRIÇÃO DA EMPRESA (CPF/CNPJ)',
				'type'			=> 'string',
				'size'			=> 14
			),
		// AGÊNCIA                  018 021    9(04)					
			'agencia'					=> array(
				'label'			=> 'Número da Inscrição',
				'description'	=> 'AGÊNCIA MANTENEDORA DA CONTA',
				'type'			=> 'int',
				'size'			=> 4
			),
		// ZEROS                    022 023    9(02)	“00”			
			'complemento1'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DE REGISTRO',
				'type'			=> 'null',
				'size'			=> 2
			),
		// CONTA                    024 028    9(05)					
			'conta'						=> array(
				'label'			=> 'Conta',
				'description'	=> 'NÚMERO DA CONTA CORRENTE DA EMPRESA',
				'type'			=> 'int',
				'size'			=> 5
			),
		// DAC                      029 029    9(01)					
			'dac'						=> array(
				'label'			=> 'DV da Conta',
				'description'	=> 'DÍGITO DE AUTO CONFERÊNCIA AG/CONTA EMPRESA',
				'type'			=> 'int',
				'size'			=> 1
			),
		// BRANCOS                  030 037    X(08)					
			'complemento2'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DE REGISTRO',
				'type'			=> 'null',
				'size'			=> 8
			),
		// USO DA EMPRESA           038 062    X(25)   NOTA 2			
			'uso_da_empresa'			=> array(
				'label'			=> 'Uso da Empresa',
				'description'	=> 'IDENTIFICAÇÃO DO TÍTULO NA EMPRESA',
				'type'			=> 'string',
				'size'			=> 25
			),
		// NOSSO NÚMERO             063 070    9(08)					
			'nosso_numero'				=> array(
				'label'			=> 'Nosso Número',
				'description'	=> 'IDENTIFICAÇÃO DO TÍTULO NO BANCO',
				'type'			=> 'string',
				'size'			=> 8
			),
		// BRANCOS                  071 082    X(12)					
			'complemento3'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 12
			),
		// CARTEIRA                 083 085    9(03)   NOTA 5			
			'carteira'					=> array(
				'label'			=> 'Carteira',
				'description'	=> 'NUMERO DA CARTEIRA',
				'type'			=> 'int',
				'size'			=> 3
			),
		// NOSSO NÚMERO             086 093    9(08)   NOTA 3			IDENTIFICAÇÃO DO TÍTULO NO BANCO
			'nosso_numero2'				=> array(
				'label'			=> 'Nosso Número',
				'description'	=> 'COMPLEMENTO DE REGISTRO',
				'type'			=> 'string',
				'size'			=> 8
			),
		// DAC NOSSO NÚMERO         094 094    9(01)   NOTA 3			
			'dac_nosso_numero2'			=> array(
				'label'			=> 'DV Nosso Número',
				'description'	=> 'DAC DO NOSSO NÚMERO',
				'type'			=> 'int',
				'size'			=> 1
			),
		// BRANCOS                  095 107    X(13)					COMPLEMENTO DO REGISTRO
			'complemento4'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 13
			),
		// CARTEIRA                 108 108    X(01)   NOTA 5			
			'cod_carteira'				=> array(
				'label'			=> 'Código da Carteira',
				'description'	=> 'CÓDIGO DA CARTEIRA',
				'type'			=> 'string',
				'size'			=> 1
			),
		// CÓD. DE OCORRÊNCIA       109 110    9(02)   NOTA 17			
			'cod_ocorrencia'			=> array(
				'label'			=> 'Código da Ocorrência',
				'description'	=> 'IDENTIFICAÇÃO DA OCORRÊNCIA',
				'type'			=> 'int',
				'size'			=> 2
			),
		// DATA DE OCORRÊNCIA       111 116    9(06)   DDMMAA			
			'data_ocorrencia'			=> array(
				'label'			=> 'Data de Ocorrência',
				'description'	=> 'DATA DE OCORRÊNCIA NO BANCO',
				'type'			=> 'date',
				'size'			=> 6
			),
		// No DO DOCUMENTO          117 126    X(10)   NOTA 18			
			'numero_documento'			=> array(
				'label'			=> 'Número do Documento',
				'description'	=> 'No DO DOCUMENTO DE COBRANÇA (DUPL, NP ETC)',
				'type'			=> 'string',
				'size'			=> 10
			),
		// NOSSO NÚMERO             127 134    9(08)					
			'confirmacao_nosso_numero'	=> array(
				'label'			=> 'Data de Ocorrência',
				'description'	=> 'CONFIRMAÇÃO DO NÚMERO DO TÍTULO NO BANCO',
				'type'			=> 'int',
				'size'			=> 8
			),
		// BRANCOS                  135 146    X(12)					
			'comlemento5'				=> array(
				'label'			=> 'COMPLEMENTO DO REGISTRO',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 12
			),
		// VENCIMENTO               147 152    9(06)   DDMMAA			
			'data_vencimento'			=> array(
				'label'			=> 'Vencimento do Título',
				'description'	=> 'DATA DE VENCIMENTO DO TÍTULO',
				'type'			=> 'date',
				'size'			=> 6
			),
		// VALOR DO TÍTULO          153 165 9(11)V9(2)					
			'valor_titulo'				=> array(
				'label'			=> 'Valor Título',
				'description'	=> 'VALOR NOMINAL DO TÍTULO',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// CÓDIGO DO BANCO          166 168    9(03)					
			'cod_banco_compensacao'		=> array(
				'label'			=> 'Código do Banco',
				'description'	=> 'NÚMERO DO BANCO NA CÂMARA DE COMPENSAÇÃO',
				'type'			=> 'int',
				'size'			=> 3
			),
		// AGÊNCIA COBRADORA        169 172    9(04)   NOTA 9			
			'cod_agencia_compensacao'	=> array(
				'label'			=> 'Código da Agência',
				'description'	=> 'AG. COBRADORA, AG. DE LIQUIDAÇÃO OU BAIXA',
				'type'			=> 'int',
				'size'			=> 4
			),
		// DAC AG. COBRADORA        173 173    9(01)					
			'dac_agencia_compensacao'	=> array(
				'label'			=> 'DAC da Agência Cobradora',
				'description'	=> 'DAC DA AGÊNCIA COBRADORA',
				'type'			=> 'int',
				'size'			=> 1
			),
		// ESPÉCIE                  174 175    9(02)   NOTA 10			
			'especie'					=> array(
				'label'			=> 'Espécie do Título',
				'description'	=> 'ESPÉCIE DO TÍTULO',
				'type'			=> 'int',
				'size'			=> 2
			),
		// TARIFA DE COBRANÇA       176 188 9(11)V9(2)					
			'tarifa'					=> array(
				'label'			=> 'Tarifa de cobrança',
				'description'	=> 'VALOR DA DESPESA DE COBRANÇA',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// BRANCOS                  189 214    X(26)					
			'complemento6'					=> array(
				'label'			=> 'Espécie do Título',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 26
			),
		// VALOR DO IOF             215 227 9(11)V9(2)					
			'valor_iof'					=> array(
				'label'			=> 'IOF',
				'description'	=> 'VALOR DO IOF A SER RECOLHIDO (NOTAS SEGURO)',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// VALOR ABATIMENTO         228 240 9(11)V((2) NOTA 19			
			'valor_abatimento'				=> array(
				'label'			=> 'Valor Abatimento',
				'description'	=> 'VALOR DO ABATIMENTO CONCEDIDO',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// DESCONTOS                241 253 9(11)V9(2) NOTA 19			
			'valor_desconto'		=> array(
				'label'			=> 'Valor Desconto',
				'description'	=> 'VALOR DO DESCONTO CONCEDIDO',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// VALOR PRINCIPAL          254 266 9(11)V9(2)					
			'valor_total'					=> array(
				'label'			=> 'Valor Total',
				'description'	=> 'VALOR LANÇADO EM CONTA CORRENTE',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// JUROS DE MORA/MULTA      267 279 9(11)V9(2)					
			'valor_juros_multa'				=> array(
				'label'			=> 'Espécie do Título',
				'description'	=> 'VALOR DE MORA E MULTA PAGOS PELO SACADO',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// OUTROS CRÉDITOS          280 292 9(11)V9(2)					
			'valor_outros_creditos'			=> array(
				'label'			=> 'Espécie do Título',
				'description'	=> 'VALOR DE OUTROS CRÉDITOS',
				'type'			=> 'float13',
				'size'			=> 13
			),
		// BOLETO DDA               293 293    X(01)   NOTA 34			
			'boleto_dda'					=> array(
				'label'			=> 'Indicador DDA',
				'description'	=> 'INDICADOR DE BOLETO DDA',
				'type'			=> 'string',
				'size'			=> 1
			),
		// BRANCOS                  294 295    X(02)					
			'complemento7'					=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 2
			),
		// DATA CRÉDITO             296 301    X(06)   DDMMAA			
			'data_credito'					=> array(
				'label'			=> 'Data de Crédito',
				'description'	=> 'DATA DE CRÉDITO DESTA LIQUIDAÇÃO',
				'type'			=> 'date',
				'size'			=> 6
			),
		// INSTR.CANCELADA          302 305    9(04)   NOTA 20			
			'instrucao_cancelada'			=> array(
				'label'			=> 'Código da instrução cancelada',
				'description'	=> 'CÓDIGO DA INSTRUÇÃO CANCELADA',
				'type'			=> 'int',
				'size'			=> 4
			),
		// BRANCOS                  306 311    X(06)					
			'complemento8'					=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 6
			),
		// ZEROS                    312 324    9(13)					
			'complemento9'					=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 13
			),
		// NOME DO SACADO           325 354    X(30)
			'nome_sacado'					=> array(
				'label'			=> 'Nome do Sacado',
				'description'	=> 'NOME DO SACADO',
				'type'			=> 'string',
				'size'			=> 30
			),
		// BRANCOS                  355 377    X(23)
			'complemento10'					=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 23
			),
		// ERROS                    378 385    X(08)   NOTA 20			
			'registros_cancelados'					=> array(
				'label'			=> 'Registros Rejeitados',
				'description'	=> 'REGISTROS REJEITADOS OU ALEGAÇÃO DO SACADO',
				'type'			=> 'null',
				'size'			=> 8
			),
		// BRANCOS                  386 392    X(07)					
			'complemento11'					=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 7
			),
			
		// CÓD. DE LIQUIDAÇÃO       393 394    X(02)   NOTA 28			
			'cod_liquidacao'			=> array(
				'label'			=> 'Código da liquidação',
				'description'	=> 'MEIO PELO QUAL O TÍTULO FOI LIQUIDADO',
				'type'			=> 'string',
				'size'			=> 2
			),
			
		// NÚMERO SEQÜENCIAL        395 400    9(06)					NÚMERO SEQÜENCIAL DO REGISTRO NO ARQUIVO
			'nro_sequencial_registro'	=> array(
				'label'			=> 'Sequencial do Registro',
				'description'	=> 'NÚMERO SEQÜENCIAL DO REGISTRO NO ARQUIVO',
				'type'			=> 'int',
				'size'			=> 6
			)
		);
			
		$sizes = array();
		foreach($transParameters as $param) {
			$sizes[] = $param['size'];
		}
		
		$transData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$transData = array_combine(array_keys($transParameters), $transData);
		
		$result = array();
		
		foreach($transParameters as $transName => $transItem) {
			$toMerge = $this->coercionDataByType($transData[$transName], $transItem['type'], $transItem['format']);
			$result[$transName] = array_merge($transItem, $toMerge);
		}
		
		return $result;
	}
	
	private function processReturnFileOptional($fileLine) {
		if ($fileLine{0} != 4) {
			throw new module_pagamento_boletoException(_PAGAMENTO_REGISTERTYPEISDIFERENT, module_pagamentoException::DIFERENT_REGISTERTYPE);
		}
		
		return array();
	}
	
	private function processReturnFileFooter($fileLine) {
		if ($fileLine{0} != 9) {
			throw new module_pagamento_boletoException(_PAGAMENTO_REGISTERTYPEISDIFERENT, module_pagamentoException::DIFERENT_REGISTERTYPE);
		}
		
		$footerParameters = array(
		// DESCRIÇÃO				INTERVALO	TIPO		DEFAULT			OBSERVAÇÃO
		// TIPO DE REGISTRO			001 001    	9(01)   	9 				
			'tipo'						=> array(
				'label'			=> 'Tipo de Registro',
				'description'	=> 'IDENTIFICAÇÃO DO REGISTRO TRAILER',
				'type'			=> 'int',
				'size'			=> 1
			),
		// CÓDIGO DE RETORNO		002 002   	9(01)   	2 				
			'cod_retorno'						=> array(
				'label'			=> 'Código de Retorno',
				'description'	=> 'IDENTIFICAÇÃO DE ARQUIVO RETORNO',
				'type'			=> 'int',
				'size'			=> 1
			),
		// CÓDIGO DE SERVIÇO		003 004    	9(02)   	01				
			'cod_servico'						=> array(
				'label'			=> 'Código do Serviço',
				'description'	=> 'IDENTIFICAÇÃO DO TIPO DE SERVIÇO',
				'type'			=> 'int',
				'size'			=> 2
			),
		// CÓDIGO DO BANCO			005 007    	9(03)   	341				
			'cod_banco'						=> array(
				'label'			=> 'Código do Banco',
				'description'	=> 'IDENTIFICAÇÃO DO BANCO NA COMPENSAÇÃO',
				'type'			=> 'int',
				'size'			=> 3
			),
		// BRANCOS					008 017    	X(10)						
			'complemento1'						=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DE REGISTRO',
				'type'			=> 'null',
				'size'			=> 10
			),
		// QTDE. DE TÍTULOS			018 025    	9(08)   	NOTA 21			
			'qtde_cobr_simples'						=> array(
				'label'			=> 'Qtde Cobr. Simples',
				'description'	=> 'QTDE. DE TÍTULOS EM COBR. SIMPLES',
				'type'			=> 'int',
				'format'		=> "%03d",			
				'size'			=> 8
			),
		// VALOR TOTAL				026 039 	9(12)V9(2) 	NOTA 21			
			'valor_total_simples'						=> array(
				'label'			=> 'Valor Cobr. Simples',
				'description'	=> 'VR TOTAL DOS TÍTULOS EM COBRANÇA SIMPLES',
				'type'			=> 'float14',
				'size'			=> 14
			),
		// AVISO BANCÁRIO			040 047    	X(08)   	NOTA 22			
			'aviso_bancario_simples'	=> array(
				'label'			=> 'Aviso Bancário Cobr. Simples',
				'description'	=> 'REFERÊNCIA DO AVISO BANCÁRIO',
				'type'			=> 'string',
				'size'			=> 8
			),
		// BRANCOS					048 057    	X(10)						
			'complemento2'						=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 10
			),
		// QTDE. DE TÍTULOS			058 065    	9(08)   	NOTA 21			
			'qtde_cobr_vinculada'						=> array(
				'label'			=> 'Qtde Cobr. Vinculada',
				'description'	=> 'QTDE DE TÍTULOS EM COBRANÇA/VINCULADA',
				'type'			=> 'int',
				'format'		=> "%03d",
				'size'			=> 8
			),
		// VALOR TOTAL				066 079 	9(12)V9(2)	NOTA 21			
			'valor_total_vinculada'						=> array(
				'label'			=> 'Valor Cobr. Vinculada',
				'description'	=> 'VR TOTAL DOS TÍTULOS EM COBRANÇA/VINCULADA',
				'type'			=> 'float14',
				'size'			=> 14
			),
		// AVISO BANCÁRIO			080 087    	X(08)   	NOTA 22			
			'aviso_bancario_vinculada'	=> array(
				'label'			=> 'Aviso Bancário Cobr. Vinculada',
				'description'	=> 'REFERÊNCIA DO AVISO BANCÁRIO',
				'type'			=> 'string',
				'size'			=> 8
			),
		// BRANCOS					088 177    	X(90)						
			'complemento3'				=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 90
			),
		// QTDE. DE TÍTULOS			178 185    	9(08)   	NOTA 21			
			'qtde_cobr_direta'						=> array(
				'label'			=> 'Qtde Cobr. Direta',
				'description'	=> 'QTDE. DE TÍTULOS EM COBR. DIRETA./ESCRITURAL',
				'type'			=> 'int',
				'size'			=> 8
			),
		// VALOR TOTAL				186 199 	9(12)V9(2) 	NOTA 21			
			'valor_total_direta'						=> array(
				'label'			=> 'Valor Cobr. Direta',
				'description'	=> 'VR TOTAL DOS TÍTULOS EM COBR. DIRETA/ESCRIT.',
				'type'			=> 'float14',
				'size'			=> 14
			),
		// AVISO BANCÁRIO			200 207    	X(08)   	NOTA 22			
			'aviso_bancario_direta'		=> array(
				'label'			=> 'Aviso Bancário Cobr. Direta',
				'description'	=> 'REFERÊNCIA DO AVISO BANCÁRIO',
				'type'			=> 'string',
				'size'			=> 8
			),
		// CONTROLE DO ARQUIVO		208 212    	9(05)						
			'nro_seq_arquivo_retorno'						=> array(
				'label'			=> 'Sequencial do Arquivo',
				'description'	=> 'NÚMERO SEQÜENCIAL DO ARQUIVO RETORNO',
				'type'			=> 'int',
				'size'			=> 5
			),
		// QTDE DE DETALHES			213 220    	9(08)						
			'qtde_registros'						=> array(
				'label'			=> 'Qtde de Registros',
				'description'	=> 'QUANTIDADE DE REGISTROS DE TRANSAÇÃO',
				'type'			=> 'int',
				'size'			=> 8
			),
		// VLR TOTAL INFORMADO		221 234		9(12)V9(2)					
			'valor_total'						=> array(
				'label'			=> 'Valor Total',
				'description'	=> 'VALOR DOS TÍTULOS INFORMADOS NO ARQUIVO',
				'type'			=> 'float14',
				'size'			=> 14
			),
		// BRANCOS					235 394   	X(160)						
			'complemento4'						=> array(
				'label'			=> '',
				'description'	=> 'COMPLEMENTO DO REGISTRO',
				'type'			=> 'null',
				'size'			=> 160
			),
		// NÚMERO SEQÜENCIAL		395 400    	9(06)						
			'nro_sequencial_registro'	=> array(
				'label'			=> 'Sequencial do Registro',
				'description'	=> 'NÚMERO SEQÜENCIAL DO REGISTRO NO ARQUIVO',
				'type'			=> 'int',
				'size'			=> 6
			)
		);
		
		$sizes = array();
		foreach($footerParameters as $param) {
			$sizes[] = $param['size'];
		}
		
		$footerData = $this->chunkSplitLineBySizes($fileLine, $sizes);
		$footerData = array_combine(array_keys($footerParameters), $footerData);
		
		$result = array();
		
		foreach($footerParameters as $footerName => $footerItem) {
			$toMerge = $this->coercionDataByType($footerData[$footerName], $footerItem['type'], $footerItem['format']);
			
			$result[$footerName] = array_merge($footerItem, $toMerge);
		}		
		return $result;
	}
	
	private function chunkSplitLineBySizes($string, array $sizes) {
		$start = 0;
		$result = array();
		foreach($sizes as $size) {
			$result[] = substr($string, $start, $size);
			$start += $size;
		}
		return $result;
	}
	
	private function coercionDataByType($fieldData, $fieldType, $fieldFormat = null) {
		$result = array(
			'originaldata' => $fieldData,
			'parseddata' 	=> '',
			'formatteddata'	=> ''
		);
		
		
		switch($fieldType) {
			case 'null' : {
				$result['parseddata'] = $result['formatteddata'] = null;
				break;
			}
			case 'string' : {
				$result['parseddata'] = $result['formatteddata'] = trim($fieldData);
				break;
			}
			case 'int' : {
				$result['parseddata'] = intval($fieldData);
				if (is_null($fieldFormat)) {
					$fieldFormat = "%d";
				}
				$result['formatteddata'] = sprintf($fieldFormat, $fieldData);
				
				break;
			}
			case 'date' : {
				$splited = $this->chunkSplitLineBySizes($fieldData, array(2,2,2));
				$isoDate = $splited[2] . '-' .$splited[1] . '-' . $splited[0]; 
				$result['parseddata'] = strtotime($isoDate);
				if (is_null($fieldFormat)) {
					$fieldFormat = 'd/m/Y';
				}
				$result['formatteddata'] = date($fieldFormat, $result['parseddata']);
				break;
			}
			
			case 'float13' :
			case 'float14' : {
				$sizes = ($fieldType == 'float14' ? array(12,2) : array(11,2));
				$splited = $this->chunkSplitLineBySizes($fieldData, $sizes);
				$strFloat = $splited[0] . '.' . $splited[1];
				
				$result['parseddata'] = floatval($strFloat);
				
				if (is_null($fieldFormat)) {
					$fieldFormat = 'R$ %1.2f';
				}
				$result['formatteddata'] = str_replace(".", ",", sprintf($fieldFormat, $result['parseddata']));
				break;
			}
			
			default : {
				$result['parseddata'] = $result['formatteddata'] = $fieldData;
				break;
			}
		}
		
		return $result;
	}
	
	/** REVISAR ESTAS FUNÇÕES */
	/*
	protected function installData($dadosboleto = null) {
		if (is_null($dadosboleto)) {
			// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
			// DADOS DA SUA CONTA - ITAÚ
			$dadosboleto["agencia"] = "3835"; // Num da agencia, sem digito
			
			$dadosboleto["conta_corrente"] = array(
				"conta"		=> "46000",	// Num da conta, sem digito
				"conta_dv" 	=> "6" 	// Digito do Num da conta
			);
		
	//		$dadosboleto["conta"] = "13877";	// Num da conta, sem digito
	//		$dadosboleto["conta_dv"] = "4"; 	// Digito do Num da conta
		
			// DADOS PERSONALIZADOS - ITAÚ
			$dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157
			
			// SEUS DADOS
			$dadosboleto["identificacao"] = "Boleto";
			$dadosboleto["cpf_cnpj"] = "03.252.893/0001-50";
			$dadosboleto["cedente"] = "AMÉRICAS INTERNACIONAL LTDA.";
			
			$dadosboleto["cep"] 		= "80420-170";
			$dadosboleto["logradouro"] 	= "Rua Coronel Dulcídio";
			$dadosboleto["numero"] 		= "517";
			$dadosboleto["complemento"] = "Shopping Novo Batel, Piso D, Loja 79";
			$dadosboleto["bairro"] = "Batel";
			$dadosboleto["cidade"] = "Curitiba";
			$dadosboleto["uf"] = "PR";

	//		// INFORMACOES PARA O CLIENTE
	//		$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
	//		$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon";
	//		$dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
	//		$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
	//		$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
	//		$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
	//		$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";
	
			// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
			$dadosboleto["quantidade"] = "";
			$dadosboleto["valor_unitario"] = "";
			$dadosboleto["aceite"] = "";		
			$dadosboleto["especie"] = "R$";
			$dadosboleto["especie_doc"] = "";
			
			// INFORMAÇÕES GERAIS
			$dadosboleto['prazo_pagamento_matricula'] = 3;
			
			// INFORMACOES PARA O CLIENTE
			$dadosboleto['demonstrativo'] =
				"Pagamento de Compra na Loja Nonononono\n" .
				"Mensalidade referente a nonon nonooon nononon\n" .
				"BoletoPhp - http://www.boletophp.com.br\n";
			
			$dadosboleto['instrucoes'] =
				"- Sr. Caixa, cobrar multa de 2% após o vencimento\n" .
				"- Receber até 10 dias após o vencimento\n" . 
				"- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br\n" . 
				"&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br\n";
		}
		if (is_array($dadosboleto)) {
			
			if (array_key_exists('accountID', $dadosboleto) && $dadosboleto['accountID'] <= 0) {
				eF_insertTableData(
				
				);
			} else {
				eF_updateTableData(
					"module_pagamento_types", 
					array('tag' => serialize($dadosboleto)), 
					sprintf(
						"payment_type_id = %d AND module_class_name = '%s",
						$dadosboleto['accountID'],
						get_class($this)
					)
				);
				
			}
			//	eF_updateTableData("c_payments_types", array('tag' => serialize($dadosboleto)), "module_class_name = '" . get_class($this) . "'");
				
				
			return true;	
		}
		return false;	
	}
	
	public function onNewPaymentRegistered($payment_id, $payment_type) {
		// PaymentStatus:
		// 1. registrado, 2. Emitido, 3. Pago, 4. Cancelado
		// PEGAR PRIMEIRO BOLETO COM STATUS REGISTRADO E EXPORTAR EM URL FORMA DO SysClass
		// GERAR BOLETO NO VALOR DA PARCELA COM STATUS 2..... SETAR O STATUS PARA 3.
		
		$result = array(
			'success'	=> true
		);
		
		// EXPORTAR O BOLETO ATIVO AO USUÁRIO
		$boleto = $this->getActiveBoletoByPaymentId($payment_id);
		
		// SALVAR BOLETO NA PASTA DO USUÁRIO
		
		// SALVAR E EM LUGAR ACESSÍVEL ATRAVÉS DE HASH  
		$filename = G_ROOTPATH . 'boletos/' . $boleto['hash_access'] . '.html';
		
		if (file_put_contents ( $filename , $boleto['html'] ) !== FALSE) {
			
			$result['success']	= true;
			$result['link'] = G_SERVERNAME . "boleto.php?id=" . $boleto['hash_access'];
		}
		return $result;		
	}
	
	private function getInstallData() {
		$dadosfixosData = eF_getTableData("c_payments_types", "tag", "module_class_name = '" . get_class($this) . "'");
		
		if (count($dadosfixosData) > 0 && isset($dadosfixosData[0]['tag'])) {
			if (empty($dadosfixosData[0]['tag'])) {
				$this->installData();
				$dadosfixosData = eF_getTableData("c_payments_types", "tag", "module_class_name = '" . get_class($this) . "'");
			}
			$dadosfixos = unserialize($dadosfixosData[0]['tag']);

			$dadosboleto["conta"] 		= $dadosboleto["conta_corrente"]['conta'];	// Num da conta, sem digito
			$dadosboleto["conta_dv"]	= $dadosboleto["conta_corrente"]['conta_dv']; 	// Digito do Num da conta
			
			$dadosfixos["endereco"] = $dadosfixos["logradouro"] . ", " . $dadosfixos["numero"] . "- " . $dadosfixos["complemento"];
			$dadosfixos["cidade_uf"] = $dadosfixos["bairro"] . ', ' . $dadosfixos["cidade"] . ", " . localization::getStateNameById($dadosfixos["uf"]) .  "CEP " . $dadosfixos["cep"];
			
			$demostrativoArray = explode("\n", $dadosfixos['demostrativo']);
			if (count($demostrativoArray) > 0) {
				foreach($demostrativoArray as $index => $itemLine) {
					$dadosfixos['demostrativo' . $index] = $itemLine;
				}
				
			} 
			$instrucoesArray = explode("\n", $dadosfixos['instrucoes']);
			
			if (count($instrucoesArray) > 0) {
				foreach($instrucoesArray as $index => $itemLine) {
					$dadosfixos['instrucoes' . $index] = $itemLine;
				}
				
			}
			
			return $dadosfixos;
		} else {
			return false;
		}
		
	}
		
   	public function getBoletoDetails($hash_id) {
   		$boletoData = eF_getTableData(
			// table 
			"users, c_payments, c_payments_parcelas, module_pagamento_types_details",
			// fields
			"c_payments_parcelas.hash_id as ID, 
			c_payments.payment_id, 
			users.login as user, 
			c_payments_parcelas.valor_desconto, 
			c_payments_parcelas.valor, 
			c_payments.data_registro as data_emissao, 
			module_pagamento_types_details.name as payment_type_name, 
			c_payments_parcelas.sha1_access, 
			c_payments_parcelas.parcela_index,
			c_payments_parcelas.payment_type,
			c_payments_parcelas.data_vencimento,
			c_payments_parcelas.status",
   		
		  	// where clause
			" users.id = c_payments.user_id" . 
			" AND c_payments.payment_id = c_payments_parcelas.payment_id" .
			" AND c_payments_parcelas.payment_type = module_pagamento_types_details.index_name" .
   			" AND c_payments_parcelas.hash_id = '" . $hash_id . "'" .
			//" AND c_payments_parcelas.status = 2" .
			// busca somente boletos
			" AND c_payments_parcelas.payment_type IN (SELECT index_name FROM module_pagamento_types_details WHERE payment_type_id = 1)"
			// order clause
		);
		
		if (is_array($boletoData) && count($boletoData) > 0) {
			//var_dump($boletoData);
			$boleto_vencido = false;
			return $boletoData[0];
			//return $this->formatPaymentRow($boletoData[0], $boleto_vencido);
		}
		return false;
   	}
   	   	
   	public function getPendingPayments() {
   		$boletosData = eF_getTableData(
			// table 
			"users, c_payments, c_payments_parcelas, module_pagamento_types_details",
			// fields
			"c_payments_parcelas.hash_id as ID, users.login as user, c_payments_parcelas.valor_desconto, c_payments_parcelas.valor, c_payments.data_registro as data_emissao, module_pagamento_types_details.name",
		  	// where clause
			" users.id = c_payments.user_id" . 
			" AND c_payments.payment_id = c_payments_parcelas.payment_id" .
			" AND c_payments_parcelas.payment_type = module_pagamento_types_details.index_name" .
			" AND c_payments_parcelas.status = 2" .
			// busca somente boletos
			" AND c_payments_parcelas.payment_type IN (SELECT index_name FROM module_pagamento_types_details WHERE payment_type_id = 1)",
			// order clause
			"c_payments.data_registro DESC"
		);
				
		foreach($boletosData as $key => $boleto) {
			$boletosData[$key] = $this->formatPaymentRow($boleto);
		}
		
		return $boletosData;
   	}
   	
	private function loadPendingPaymentsTpl($parentContext = null) {
		$smarty = $this -> getSmartyVar();
		
		$boletosData = $this->getPendingPayments();
				
		$smarty -> assign("T_PAGAMENTO_PENDENTES", $boletosData);
		$smarty -> assign("T_PAGAMENTO_PENDENTES_COUNT", sizeof($boletosData));
		
		if (!is_null($parentContext)) {
			$formAction = $parentContext -> moduleBaseUrl . "&action=" . self::PREVIEW_PAYMENTS_RETURN;
		} else {
			$formAction = $this -> moduleBaseUrl . "&action=" . self::PREVIEW_PAYMENTS_RETURN;
		}
		
		
		$form = new HTML_QuickForm("send_return_file", "post", $formAction, "", null, true);
		$form -> addElement('file', 'file_upload', _PAGAMENTO_FILE_LABEL, 'class = "inputText"');
		$form -> setMaxFileSize(FileSystemTree :: getUploadMaxSize() * 1024); //getUploadMaxSize returns size in KB
   		$form -> addElement('submit', 'submit_upload_file', _APPLYPROFILECHANGES, 'class = "flatButton"');

 		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
 		$form -> accept($renderer);
 		$smarty -> assign('T_FILE_UPLOAD_FORM', $renderer -> toArray());
 				
 		if ($form -> isSubmitted() && $form -> validate()) {
			$file =& $form->getElement('file_upload');
			$retFileName = sprintf("%s.%04d.ret", date("Y-m-d-h-i-s"), mt_rand(0, 9999));
			$destDir = $this -> moduleBaseDir . 'retorno/';
			if ($file->moveUploadedFile($destDir, $retFileName)) {
				// SEND MESSAGE
				$this->setMessageVar(_PAGAMENTO_ARQUIVO_ENVIADO_SUCESSO, "success");
				
				$returnStatus = $this->processReturnFile($destDir . $retFileName);
				
				$smarty -> assign("T_IMPORTED_FILE", true);
				$smarty -> assign("T_IMPORT_FILE_STATUS", $returnStatus);
				
				$ocorrencias = eF_getTableData("module_pagamento_boleto_ocorrencias", "*");
				
				foreach($ocorrencias as $ocorrencia) {
					$base_ocorrencias[$ocorrencia['codigo']] = $ocorrencia['descricao'];
				}
				$smarty -> assign("T_BASE_OCORRENCIAS", $base_ocorrencias);
				
				$liquidacoes = eF_getTableData("module_pagamento_boleto_liquidacao", "codigo, descricao");
				
				foreach($liquidacoes as $liquidacao) {
					$base_liquidacao[$liquidacao['codigo']] = $liquidacao['descricao'];
				}
				$smarty -> assign("T_BASE_LIQUIDACAO", $base_liquidacao);
				
				
//				$smarty -> assign("T_IMPORT_FILE_STATUS", $returnStatus['footer']);
				
			} else {
				$this->setMessageVar(_PAGAMENTO_ARQUIVO_ENVIADO_ERROR, "error");
			}
		}
	}   	
   	   	
   	private function formatPaymentRow($dbRow, $vencido) {
		if ($vencido) {
			$dbRow['valor_string'] = formatPrice($dbRow['valor']);
		} else {
			$dbRow['valor_string'] = formatPrice($dbRow['valor_desconto']);
		}
			
		$dbRow['data_emissao'] = strtotime($dbRow['data_emissao']);
		
		return $dbRow;
   	}
   	
	public function getActiveBoletoByPaymentId($payment_id) {
		
		$dadosfixos = $this->getInstallData();
		
		if ($dadosfixos !== FALSE) {
			
			$boletoData = eF_getTableData(
				// table 
				"c_payments_parcelas", 	
				// fields
				"payment_id, parcela_index, payment_type, valor_desconto, valor, status",
			  	// where clause
			  	"payment_id = '" . $payment_id . "'" .
				" AND payment_module = '" . get_class($this) . "'" . 
				" AND status = 2",
				// order clause
				"parcela_index"
			);
			
			if (count($boletoData) > 0 && count($boletoData[0] > 0)) {
				$boleto = $boletoData[0];
				
				$nosso_numero = $this->generateNossoNumero($payment_id, $boleto['parcela_index']);
				
				$result = array(
					'nosso_numero' 	=> $nosso_numero,
					'boleto'		=> $boleto
				);
				
				$hash_access = sha1(serialize($result));
				
				eF_updateTableData(
					"c_payments_parcelas", 
					array(
						'hash_id' => $nosso_numero,
						'sha1_access'	=> $hash_access,
						'data_vencimento' => date("Y-m-d", time() + ($dadosfixos['prazo_pagamento_matricula'] * 86400))
					),
				  	"payment_id = '" . $payment_id . "'" .
					" AND parcela_index = '" . $boleto['parcela_index'] . "'" . 
					" AND payment_module = '" . get_class($this) . "'" . 
					" AND status = 2"
				);
				
				return $this->getBoleto($nosso_numero);
			}
		}
		return false;
	}
	*/
}
?>