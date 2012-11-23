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
	

	

	


}
