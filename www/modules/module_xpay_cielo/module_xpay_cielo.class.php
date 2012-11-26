<?php
require_once (dirname(__FILE__) . '/../module_xpay/module_xpay_submodule.interface.php');

define("XPAY_CIELO_DEV", true);

if (defined("XPAY_CIELO_DEV")) {
	/* DEV KEYS */
	define("CIELO", "1001734898");
	define("CIELO_CHAVE", "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");
} else {
	define("CIELO", "1027241163");
	define("CIELO_CHAVE", "149644d64ba539a638060b0c043361b3c2bafc4df10dc0980c934251c53cc455");
}

class module_xpay_cielo extends MagesterExtendedModule implements IxPaySubmodule
{
	protected static $subModules = null;

	protected $conf = array(
		// Opção => Autorizar transação autenticada e não-autenticada
		'authorization'					=> 2, // AUTORIZAÇÃO E AUTENTICAÇÂO
		//'authorization'					=> 4, // RECORRENTE
		'authorization'					=> 2,
		'auto_capture'					=> "false",
		// [A - Débito, 1- Crédito, 2 - loja, 3 - Administradora]
		'payment_subdivision_method'	=> 2
	);

	public function getName()
	{
		return "XPAY_CIELO";
	}

	public function getPermittedRoles()
	{
		return array("administrator", "student");
	}

	/* IxPaySubmodule INTERFACE FUNCTIONS */
	public static function getInstance()
	{
		$currentUser = self::getCurrentUser();

		$defined_moduleBaseUrl 	= G_SERVERNAME . $currentUser -> getRole() . ".php" . "?ctg=module&op=" . __CLASS__;
		$defined_moduleFolder 	= __CLASS__;

		return new self($defined_moduleBaseUrl , $defined_moduleFolder);
	}

	public function getPaymentInstances()
	{
		return array(
			//'title'		=> __XPAY_CIELO_DO_PAYMENT,
			'baselink'	=> $this->moduleBaseLink,
			'options'	=> array (
				"visa"			=> array(
					'name'	=> "Visa",
					"options"	=> array(
						"1"	=> "À Vista no Cartão de Crédito",
						"A"	=> "À Vista no Cartão de Débito",
						"XX"	=> "Pagamento Mensal Automático no Cartão de Crédito"
					),
					"template"	=> $this->moduleBaseDir . "templates/includes/instance.options.tpl"
				),

				"mastercard"	=> array(
					"name"	=> "Mastercard",
					"options"	=> array(
						"1"	=> "Crédito À Vista",
						"A"	=> "Débito"
					),
					"template"	=> $this->moduleBaseDir . "templates/includes/instance.options.tpl"
				)
				/*,
				"elo"			=> array(
					'name'	=> "Elo"
				),
				"diners"		=> array(
					'name'	=> "Diners"
				),
				"discover"		=> array(
					'name'	=> "Discover"
				)
				*/
			)
		);
	}
	public function getPaymentInstanceConfig($instance_id, array $overrideOptions)
	{
		var_dump($overrideOptions);
		exit;
		return $instance_id;
	}
	public function getPaymentInstanceOptions($instance_id)
	{
		$instances = $this->getPaymentInstances($instance_id);
		
		return $instances[$instance_id]['options'];
	}
	public function fetchPaymentInstanceOptionsTemplate($instance_id)
	{
		$smarty = $this->getSmartyVar();
		$instances = $this->getPaymentInstances();
		$options = $instances['options'][$instance_id]['options'];
		$tpl = $instances['options'][$instance_id]['template'];
		$smarty -> assign("T_XPAY_CIELO_OPT", $options);
		return $smarty -> fetch($tpl);
	}
	/*
	private function createPaymentForm($payment_id, $invoice_id, array $data) {
		$form = new HTML_QuickForm("xpay_cielo_init_payment", "post", $_SERVER['REQUEST_URI'], "", null, true);
		$form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
		
		$form -> addElement("hidden", "invoice_index", $invoice_id);
		$form -> addElement("hidden", "bandeira", $data['option']);
		$allParcelas = $this->getPaymentInstanceOptions($data['option']);
		
		foreach ($parcelas as $key => $item) {
			$form -> addElement('radio', 'qtde_parcelas', $item, $item, $key, 'class="qtde_parcelas"');
		}
		
		$form -> addRule('qtde_parcelas', _THEFIELD.' "'.__XPAY_QTDE_PARCELAS.'" '._ISMANDATORY, 'required', null, 'client');
		
		$form -> addElement('submit', 'xpay_cielo_submit', __XPAY_CIELO_MAKE, 'class = "button_colour round_all"');
		
		if ($form -> isSubmitted() && $form -> validate()) {
			$values = $form->exportValues();
			$Pedido = $this->processPaymentForm($payment_id, $invoice_id, $values);
				
			if (is_null($invoice_id)) {
				$invoice_id = $values['invoice_index'];
			}
				
			//$this->injectJS("jquery/jquery.fancybox");
		
			$smarty -> assign("T_XPAY_CIELO_URL_AUTENTICACAO", $Pedido->urlAutenticacao);
			//$invoiceData = $this->getParent()->getInvoiceById($payment_id, $invoice_id);
				
			// SAVE TRANSACTION DETAILS HERE
			$fields = array(
					"negociation_id"	=> $payment_id,
					"tid"				=> $Pedido->tid,
					"pedido_id"			=> $Pedido->dadosPedidoNumero,
					"valor"				=> floatval($Pedido->dadosPedidoValor) / 100,
					"data"				=> date("Y-m-d H:i:s", strtotime($Pedido->dadosPedidoData)),
					"descricao"			=> $Pedido->dadosPedidoDescricao,
					"bandeira"			=> $Pedido->formaPagamentoBandeira,
					"produto"			=> $Pedido->formaPagamentoProduto,
					"parcelas"			=> $Pedido->formaPagamentoParcelas,
					"status"			=> $Pedido->status,
			);
			$transactionID = eF_insertTableData("module_xpay_cielo_transactions", $fields);
		
			$fieldsLink = array(
					"negociation_id"	=> $payment_id,
					"invoice_index"		=> $invoice_id,
					"transaction_id"	=> $transactionID
			);
		
			eF_insertTableData("module_xpay_cielo_transactions_to_invoices", $fieldsLink);
				
			//echo $Pedido->urlAutenticacao;
			eF_redirect($Pedido->urlAutenticacao, false, false, true);
			exit;
		}
	}
	*/
	public function initPaymentProccess($payment_id, $invoice_id, array $data)
	{
		// CREATE FORM
		$smarty = $this->getSmartyVar();

		if (!is_object($this->getParent())) {
			$currentContext = $this;
		} else {
			$currentContext = $this->getParent();
		}

		
		$Pedido = $this->processPaymentForm($payment_id, $invoice_id, $data);
		
		$smarty -> assign("T_XPAY_CIELO_URL_AUTENTICACAO", $Pedido->urlAutenticacao);
		//$invoiceData = $this->getParent()->getInvoiceById($payment_id, $invoice_id);
		/*
		// SAVE TRANSACTION DETAILS HERE
		$fields = array(
			"negociation_id"	=> $payment_id,
			"tid"				=> $Pedido->tid,
			"pedido_id"			=> $Pedido->dadosPedidoNumero,
			"valor"				=> floatval($Pedido->dadosPedidoValor) / 100,
			"data"				=> date("Y-m-d H:i:s", strtotime($Pedido->dadosPedidoData)),
			"descricao"			=> $Pedido->dadosPedidoDescricao,
			"bandeira"			=> $Pedido->formaPagamentoBandeira,
			"produto"			=> $Pedido->formaPagamentoProduto,
			"parcelas"			=> $Pedido->formaPagamentoParcelas,
			"status"			=> $Pedido->status,
		);
		$transactionID = eF_insertTableData("module_xpay_cielo_transactions", $fields);
		
		$fieldsLink = array(
			"negociation_id"	=> $payment_id,
			"invoice_index"		=> $invoice_id,
			"transaction_id"	=> $transactionID
		);
		
		eF_insertTableData("module_xpay_cielo_transactions_to_invoices", $fieldsLink);
		*/
		//echo $Pedido->urlAutenticacao;
		eF_redirect($Pedido->urlAutenticacao, false, false, true);
		exit;

		// Colocar o formulaŕio para escolha de bandeira, parcelamento, etc...

		// Criar a transação, e direcionar para o link da Cielo.. colocar num iframe, se possível.

		// codigoBandeira = [visa, mastercard, elo] (checar bandeiras disponíveis)
		// formaPagamento = [1,2,3,4,...] (é o total de parcelas) Até 6 parcelas
		// tipoParcelamento = [2 - loja, 3 - Administradora] ???? (Checar no américas) Administradora
		// capturarAutomaticamente = false // Capturar após o retorno
		// indicadorAutorizacao = 2	// Opção => Autorizar transação autenticada e não-autenticada
		//
	}

	private function processPaymentForm($payment_id, $invoice_id, $values)
	{
		require_once (dirname(__FILE__) . '/includes/module_xpay_cielo.pedido.model.php');

		$invoiceData = $this->getParent()->_getNegociationInvoiceByIndex($payment_id, $invoice_id);
		
		$Pedido = new Pedido_Model();

		$Pedido->formaPagamentoBandeira = $values["option"];

		if ($values["instance_option"] != "A" && $values["instance_option"] != "1") {
			$Pedido->formaPagamentoProduto = $this->conf['payment_subdivision_method'];
			$Pedido->formaPagamentoParcelas = $values["instance_option"];
		} else {
			$Pedido->formaPagamentoProduto = $values["instance_option"];
			$Pedido->formaPagamentoParcelas = 1;
		}

		$Pedido->dadosEcNumero = CIELO;
		$Pedido->dadosEcChave = CIELO_CHAVE;

		$Pedido->capturar = $this->conf['auto_capture'];
		$Pedido->autorizar = $this->conf['authorization'];
		/// CHECAR COMO INCLUIR O VALOR


		// SAME NUMBER AS BOLETO "nosso número"
		$Pedido->dadosPedidoNumero = $this->getParent()->createInvoiceID($payment_id, $invoice_id);

		$Pedido->dadosPedidoValor = $values["produto"];
		$Pedido->dadosPedidoValor = floor((floatval($invoiceData['valor']) + floatval($invoiceData['total_reajuste'])) * 100);

		$Pedido->dadosPedidoValor = 100;
		
		//$ies = reset($this->getCurrentUserIes());
		
		$currentUser = $this->getEditedUser(true, $invoiceData['user_id']);
		if ($currentUser) {
			$currentUser = $currentUser->user;
		}

		$Pedido->campoLivre =
			sprintf("%s - %s", $invoiceData['course'], $invoiceData['parcela_index'] == 1 ? "Matricula" : "Mensalidade " . ($invoiceData['parcela_index'] - 1));

		$Pedido->dadosPedidoDescricao =
			sprintf("%s - %s %s", formatLogin(null, $currentUser), $invoiceData['parcela_index'] == 1 ? "Matricula" : "Mensalidade " . ($invoiceData['parcela_index'] - 1), $invoiceData['course']);

			/*
			var_dump(mb_detect_encoding($Pedido->dadosPedidoDescricao));

			var_dump(mb_convert_encoding($Pedido->dadosPedidoDescricao, "ISO-8859-1"));
			var_dump(mb_convert_encoding($Pedido->dadosPedidoDescricao, "UTF-8"));
			exit;
			*/
		//$Pedido->campoLivre 			= mb_convert_encoding($Pedido->campoLivre, "UTF-8", "ISO-8859-1");
		//$Pedido->dadosPedidoDescricao 	= mb_convert_encoding($Pedido->dadosPedidoDescricao, "UTF-8", "ISO-8859-1");

		//$Pedido->campoLivre 			= mb_convert_encoding($Pedido->campoLivre, "ISO-8859-1", "UTF-8");
		//$Pedido->dadosPedidoDescricao 	= mb_convert_encoding($Pedido->dadosPedidoDescricao, "ISO-8859-1", "UTF-8");

		$Pedido->urlRetorno = ReturnURL();

		// ENVIA REQUISIÇÃO SITE CIELO
		$objResposta = $Pedido->RequisicaoTransacao(false);

		$Pedido->tid = (string) $objResposta->tid;
		$Pedido->pan = (string) $objResposta->pan;
		$Pedido->status = (string) $objResposta->status;

		$urlAutenticacao = "url-autenticacao";
		$Pedido->urlAutenticacao = (string) $objResposta->$urlAutenticacao;

		$currentUser = $this->getCurrentUser();

		$this->setCache(md5($currentUser->user['login'] . date("Ymd")), $Pedido->tid);

		return $Pedido;
		/*
		// Serializa Pedido e guarda na SESSION
		$StrPedido = $Pedido->ToString();
		*/
	}

	public function returnPaymentAction()
	{
		require_once (dirname(__FILE__) . '/includes/module_xpay_cielo.pedido.model.php');
		$smarty = $this->getSmartyVar();

		//header("Content-type: text/plain");

		$Pedido = new Pedido_Model();
		$Pedido->dadosEcNumero = CIELO;
		$Pedido->dadosEcChave = CIELO_CHAVE;

		$tidKey = $this->getTidKey();

		if (array_key_exists($tidKey, $_POST)) {
			$Pedido->tid = $_POST[$tidKey];
		} else {
			$Pedido->tid = $this->getCache($tidKey);
		}
		$status = -1;
		// Consulta situação da transação

		if (!is_null($Pedido->tid)) {
			$objResposta = $Pedido->RequisicaoConsulta();
			$consultaArray = $this->cieloReturnToArray($objResposta);

			// DEPENDENDO DO VALOR DO STATUS, REALIZAR A CAPTURA
			if ($consultaArray['status'] == 4) {
				//var_dump($consultaArray['autorizacao']['valor']);
				$objResposta = $Pedido->RequisicaoCaptura();
				// RECARREGA INFORMAÇÕES, APÓS CAPTURA
				$consultaArray = $this->cieloReturnToArray($objResposta);
			}

			$message 		= "Pagamento em andamento";
			$message_type	= "warning";

			// ATUALIZAR STATUS NO BANCO DE DADOS
			eF_updateTableData(
				"module_xpay_cielo_transactions",
				array("status" => $consultaArray['status']),
				sprintf("tid = '%s'", $Pedido -> tid)
			);

			list($transaction) = eF_getTableData(
				"module_xpay_cielo_transactions trn
				LEFT JOIN module_xpay_cielo_transactions_to_invoices trn2inv ON (trn.id = trn2inv.transaction_id AND trn.negociation_id = trn2inv.negociation_id)
				LEFT JOIN module_xpay_invoices inv ON (trn2inv.negociation_id = inv.negociation_id AND trn2inv.invoice_index = inv.invoice_index)", 
				"trn.id, trn.tid, trn.negociation_id, trn2inv.invoice_index, trn.data, trn.descricao, trn.bandeira, trn.status, trn.pedido_id, trn.valor as valor_total, 
				inv.valor, inv.data_vencimento", 
				sprintf("tid = '%s'", $Pedido -> tid));

			$xpayModule = $this->loadModule("xpay");
			
			$invoiceData = $xpayModule->_getNegociationInvoiceByIndex($transaction['negociation_id'], $transaction['invoice_index']);
			
			$transactionAndInvoice = array_merge($invoiceData, $transaction);
			
			switch($consultaArray['status'])
			{
				case "0": { 
					$status = __XPAY_CIELO_CREATED;
					break;
				}
				case "1": {
					$status = "Em andamento";
					break;
				}
				case "2": {
					$status = "Autenticada";
					break;
				}
				case "3": {
					$status = "Não autenticada";

					$message 		= "Autenticação não concluída";
					$message_type	= "failure";

					break;
				}
				case "4": {
					$status = "Autorizada";

					break;
				}
				case "5": {
					$status = "Não autorizada";

					$message 		= "Pagamento não Autorizado";
					$message_type	= "failure";

					break;
				}
				case "6": {
					$status = "Capturada";
					
					$message 		= "Pagamento Autorizado com sucesso";
					$message_type	= "success";
					
					$xpayModule->insertInvoicePayment(
						$invoiceData['negociation_id'], 
						$invoiceData['invoice_index'], 
						floatval($transaction['valor_total']),
						'cielo',
						$transaction['id'],
						$transaction['data']
					);

					// CALL EVENTS
					/*
					$xpayModule->onPaymentReceivedEvent($this, array(
						'payment_id'	=> $transaction['payment_id'],
						'parcela_index'	=> $transaction['parcela_index']
					));
					*/
					break;
				}
				case "8": {
					$status = "Não capturada";

					$message 		= "Pagamento não Autorizado";
					$message_type	= "failure";

					break;
				}
				case "9": {
					$status = __XPAY_CIELO_CANCELLED;

					$message 		= "Pagamento Cancelado";
					$message_type	= "warning";

					// CALL EVENTS

					break;
				}
				case "10": {
					$status	= "Em autenticação";
					break;
				}
				default : {
					$status = "Transação não encontrada";
				}
			}
			$smarty -> assign("T_XPAY_CIELO_CONSULTA", $consultaArray);
			
			//$transaction = $xpayModule->calculateInvoiceDetails($transaction);
			
			$smarty -> assign("T_XPAY_CIELO_TRANS", $transactionAndInvoice);
		} else {
			$message 		= "Pagamento não efetuado";
			$message_type	= "failure";
		}
		$smarty -> assign("T_XPAY_CIELO_STATUS", $status);
/*		
		$smarty -> assign("T_XPAY_CIELO_MESSAGE_TYPE", $message_type);
		$smarty -> assign("T_XPAY_CIELO_MESSAGE", $message);
*/		
		$this->setMessageVar($message, $message_type);
		
		$return_link = sprintf(str_replace("_cielo", "", $this->moduleBaseUrl) . "&action=do_payment&negociation_id=%s&invoice_index=%s&message=%s&message_type=%s",
			$transaction['negociation_id'], 
			$transaction['invoice_index'],
			$message,
			$message_type
		);
		
		$smarty -> assign("T_XPAY_CIELO_RETURN_LINK", $return_link);
		$this->assignSmartyModuleVariables();

		return true;
	}

	private function cieloReturnToArray($xmlObject)
	{
		$DadosPedido	= "dados-pedido";
		$DataHora		= "data-hora";
		$FormaPagamento	= "forma-pagamento";

		return array(
			'dados_pedido' => array(
				"numero"	=> (string) $xmlObject->$DadosPedido->numero,
				"valor"		=> floatval((string) $xmlObject->$DadosPedido->valor) / 100,
				"moeda"		=> (string) $xmlObject->$DadosPedido->moeda,
				"data_hora"	=> date("Y-m-d H:i:s", strtotime((string) $xmlObject->$DadosPedido->$DataHora)),
				"idioma"	=> (string) $xmlObject->$DadosPedido->numero
			),
			'forma_pagamento' => array(
				"bandeira"	=> (string) $xmlObject->$FormaPagamento->bandeira,
				"produto"	=> (string) $xmlObject->$FormaPagamento->produto,
				"parcelas"	=> (string) $xmlObject->$FormaPagamento->parcelas
			),
			"status"	=> (string) $xmlObject->status,
			"autenticacao" => array(
				"codigo"	=> (string) $xmlObject->autenticacao->codigo,
				"mensagem"	=> (string) $xmlObject->autenticacao->mensagem,
				"data_hora"	=> date("Y-m-d H:i:s", strtotime((string) $xmlObject->autenticacao->$DataHora)),
				"valor"		=> floatval((string) $xmlObject->autenticacao->valor) / 100,
				"eci"		=> (string) $xmlObject->autenticacao->eci
			),
			"autorizacao" => array(
				"codigo"	=> (string) $xmlObject->autorizacao->codigo,
				"codigo"	=> (string) $xmlObject->autorizacao->mensagem,
				"data_hora"	=> date("Y-m-d H:i:s", strtotime((string) $xmlObject->autorizacao->$DataHora)),
				"valor"		=> floatval((string) $xmlObject->autorizacao->valor) / 100,
				"lr"		=> (string) $xmlObject->autorizacao->lr,
				"arp"		=> (string) $xmlObject->autorizacao->arp
			),
			"captura" => array(
				"codigo"	=> (string) $xmlObject->captura->codigo,
				"codigo"	=> (string) $xmlObject->captura->mensagem,
				"data_hora"	=> date("Y-m-d H:i:s", strtotime((string) $xmlObject->captura->$DataHora)),
				"valor"		=> floatval((string) $xmlObject->captura->valor) / 100
			)
		);
	}

	public function paymentCanBeDone($payment_id, $invoice_id)
	{
		return true;
	}
	public function getInvoiceStatusById($payment_id, $invoice_id)
	{
	}
	private function getTidKey()
	{
		$currentUser = $this->getCurrentUser();
		return $tidKey = md5($currentUser->user['login'] . date("Ymd"));
	}

}
