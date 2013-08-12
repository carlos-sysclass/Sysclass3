<?php
require_once (dirname(__FILE__) . '/../module_xpay/module_xpay_submodule.interface.php');

define("XPAY_CIELO_DEV", false);

if (defined("XPAY_CIELO_DEV") && XPAY_CIELO_DEV) {
	/* DEV KEYS */
	define("CIELO_ULT", "1001734898");
	define("CIELO_CHAVE_ULT", "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");

	define("CIELO_POS", "1001734898");
	define("CIELO_CHAVE_POS", "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");
} else {
	define("CIELO_ULT", "1037979963");
	define("CIELO_CHAVE_ULT", "dc5abfc09e0338763d5e83605af905bebc73cdf08cc31cc7eb51a00d55bb5b33");

	// DEBUG - BRASWELL
	define("CIELO_POS", "1037979963");
	define("CIELO_CHAVE_POS", "dc5abfc09e0338763d5e83605af905bebc73cdf08cc31cc7eb51a00d55bb5b33");
}

class module_xpay_cielo extends MagesterExtendedModule implements IxPaySubmodule
{
	protected static $subModules = null;

	protected $conf = array(
		// Opção => Autorizar transação autenticada e não-autenticada
		'authorization'					=> 3, // AUTORIZAÇÃO E AUTENTICAÇÂO
		'recurring_authorization'		=> 3,
		//'authorization'					=> 4, // RECORRENTE (SOMENTE COM CAPTURA DO CARTÃO NA LOJA
		'auto_capture'					=> "true",
		// [A - Débito, 1- Crédito, 2 - loja, 3 - Administradora]
		'payment_subdivision_method'	=> 2,
		'cielo_keys'					=> array(
			1 => array(
				"CIELO"			=> CIELO_ULT,
				"CIELO_CHAVE"	=> CIELO_CHAVE_ULT
			),
			2 => array(
				"CIELO"			=> CIELO_POS,
				"CIELO_CHAVE"	=> CIELO_CHAVE_POS
			)
		)
	);

	public function getName()
	{
		return "XPAY_CIELO";
	}

	public function getPermittedRoles()
	{
		return array("administrator", "student");
	}

	public function listTransactionsAction()
	{
		/** @todo THIS LIST MUST BE LOADED BY AJAX */
		// SHOW A LIST WITH ALL CIELO TRANSACTIONS
		// THE USER CAN SEARCH, VIEW, CAPTURE AND CANCEL TRANSACTIONS
		if ($this->getCurrentUser()->getType() == 'administrator') {
			
			$smarty = $this->getSmartyVar();
		
			$transactions = sC_getTableData(
				"module_xpay_cielo_transactions trans 
				LEFT JOIN module_xpay_cielo_transactions_statuses stat ON (trans.status = stat.id) 
				LEFT JOIN module_xpay_cielo_transactions_to_invoices trans2inv ON (trans2inv.transaction_id = trans.id)
				LEFT JOIN module_xpay_course_negociation neg ON (trans2inv.negociation_id = neg.id)
				LEFT JOIN users u ON (neg.user_id = u.id)",
				"trans.id, trans2inv.negociation_id, trans2inv.invoice_index, u.login,
				trans.data, trans.tid, trans.valor, trans.bandeira, trans.produto, trans.parcelas, 
				trans.status as status_id, stat.nome as status"
			);
			
			$instances = $this->getPaymentInstances();
			
					
			foreach ($transactions as &$trans) {
				if (array_key_exists($trans['produto'], $instances['options'][$trans['bandeira']]['options'])) {
					$trans['forma_pagamento'] = $instances['options'][$trans['bandeira']]['options'][$trans['produto']];
				} else { // PARCELAMENTO
					$trans['forma_pagamento'] = sprintf("Parcelado %dx", $trans['parcelas']);
				}
			}
	
			$smarty -> assign("T_XPAY_CIELO_TRANSACTIONS", $transactions);
			
			$statusesDB = sC_getTableData("module_xpay_cielo_transactions_statuses", "id, nome");
			$statuses = array();
			foreach ($statusesDB as $statusDB) {
				$statuses[] = $statusDB['nome'];
			}
			$this->addModuleData("statuses", $statuses);

			foreach ($instances['options'] as $bandeira) {
				foreach ($bandeira['options'] as $forma) {
					$formas_pagamento[] = $forma;
				}
			}
			$formas_pagamento = array_unique($formas_pagamento);
			$this->addModuleData("formas_pagamento", $formas_pagamento);
			
			$bandeiras = array_keys($instances['options']);
			$this->addModuleData("bandeiras", $bandeiras);
		}
	}

	public function viewRepeatableInvoicesListAction()
	{
		/** @todo THIS LIST MUST BE LOADED BY AJAX */
		// SHOW A LIST OF ALL USERS WITH CC METHODS AND REGISTERED TOKENS
		// THE USER CAN SEARCH, VIEW, CAPTURE AND CANCEL TRANSACTIONS
		if ($this->getCurrentUser()->getType() == 'administrator') {

			// GET THE LIST OFF ALL PAYMENT WITH CC METHODS
			
			if (is_null($this->getParent())) {
            	$xpayModule = $this->loadModule("xpay");
            	$this->setParent($xpayModule);
			}
			
			
			
			echo prepareGetTableData(
				"module_xpay_cielo_card_tokens tok LEFT join users u ON (tok.user_id = u.id) 
				LEFT join module_xpay_course_negociation neg ON (tok.user_id = neg.user_id)",
				"tok.user_id, 
				u.login,
				neg.id as negociation_id,
				(SELECT data FROM module_xpay_cielo_transactions WHERE negociation_id = neg.id AND status = 6 ORDER BY data DESC LIMIT 1) as last_payment,
				(SELECT valor FROM module_xpay_cielo_transactions WHERE negociation_id = neg.id AND status = 6 ORDER BY data DESC LIMIT 1) as valor,
				tok.bandeira,
				tok.cartao",
				"neg.id IN (SELECT negociation_id FROM module_xpay_cielo_transactions WHERE status = 6)"
			);
		
			$transactions = sC_getTableData(
				"module_xpay_cielo_card_tokens tok LEFT join users u ON (tok.user_id = u.id) 
				LEFT join module_xpay_course_negociation neg ON (tok.user_id = neg.user_id)",
				"tok.user_id, 
				u.login,
				neg.id as negociation_id,
				(SELECT data FROM module_xpay_cielo_transactions WHERE negociation_id = neg.id AND status = 6 ORDER BY data DESC LIMIT 1) as last_payment,
				(SELECT valor FROM module_xpay_cielo_transactions WHERE negociation_id = neg.id AND status = 6 ORDER BY data DESC LIMIT 1) as valor,
				tok.bandeira,
				tok.cartao",
				"neg.id IN (SELECT negociation_id FROM module_xpay_cielo_transactions WHERE status = 6)"
			);


			foreach($transactions as $index => $trans) {
				list($nextInvoice) = sC_getTableData(
					"module_xpay_invoices inv
					LEFT JOIN module_xpay_invoices_to_paid invpd ON (inv.negociation_id = invpd.negociation_id AND inv.invoice_index = invpd.invoice_index)
					LEFT JOIN module_xpay_paid_items pd ON (invpd.paid_id = pd.id)",
					"inv.invoice_index as next_invoice, inv.data_vencimento as next_payment, inv.valor as next_value, inv.invoice_id as next_invoice, inv.data_vencimento < NOW() as overdue",
					sprintf("inv.negociation_id = %d AND (paid IS NULL OR valor > IFNULL(invpd.full_value, pd.paid))", $trans['negociation_id']),
					"inv.data_vencimento ASC",
					null,
					"1"
				);
				if (!is_null($nextInvoice)) {
					$transactions[$index] = array_merge($trans, $nextInvoice);
				} else {
					/*
					$transactions[$index] = array_merge($trans, array(
						
					));
					*/
				}
				
			}
	
			$instances = $this->getPaymentInstances();
			
			/*		
			foreach ($transactions as &$trans) {
				if (array_key_exists($trans['produto'], $instances['options'][$trans['bandeira']]['options'])) {
					$trans['forma_pagamento'] = $instances['options'][$trans['bandeira']]['options'][$trans['produto']];
				} else { // PARCELAMENTO
					$trans['forma_pagamento'] = sprintf("Parcelado %dx", $trans['parcelas']);
				}
			}
			*/

			$smarty = $this->getSmartyVar();
			$smarty -> assign("T_XPAY_CIELO_TRANSACTIONS", $transactions);
			
			$statusesDB = sC_getTableData("module_xpay_cielo_transactions_statuses", "id, nome");
			$statuses = array();
			foreach ($statusesDB as $statusDB) {
				$statuses[] = $statusDB['nome'];
			}
			$this->addModuleData("statuses", $statuses);

			foreach ($instances['options'] as $bandeira) {
				foreach ($bandeira['options'] as $forma) {
					$formas_pagamento[] = $forma;
				}
			}
			$formas_pagamento = array_unique($formas_pagamento);
			$this->addModuleData("formas_pagamento", $formas_pagamento);
			
			$bandeiras = array_keys($instances['options']);
			$this->addModuleData("bandeiras", $bandeiras);


			$xpayModule->assignSmartyModuleVariables();
		}
	}






	/* REST API */
	public function loadTransactionsAction()
	{
		/** @todo THIS LIST MUST BE LOADED BY AJAX */
		// SHOW A LIST WITH ALL CIELO TRANSACTIONS
		// THE USER CAN SEARCH, VIEW, CAPTURE AND CANCEL TRANSACTIONS
		if ($this->getCurrentUser()->getType() == 'administrator') {
	
			$transactions = sC_getTableData(
				"module_xpay_cielo_transactions trans
				LEFT JOIN module_xpay_cielo_transactions_statuses stat ON (trans.status = stat.id)
				LEFT JOIN module_xpay_cielo_transactions_to_invoices trans2inv ON (trans2inv.transaction_id = trans.id)
				LEFT JOIN module_xpay_course_negociation neg ON (trans2inv.negociation_id = neg.id)
				LEFT JOIN users u ON (neg.user_id = u.id)",
				"trans.id, trans2inv.negociation_id, trans2inv.invoice_index, u.login,
				trans.data, trans.tid, trans.valor, trans.bandeira, trans.produto, trans.parcelas,
				trans.status as status_id, stat.nome as status"
			);
				
			$instances = $this->getPaymentInstances();
				
			foreach ($transactions as &$trans) {
				
				$trans["bandeira_image"] = sprintf(
					"<img src=\"%simages/%s.png\" />",
					$this->moduleBaseLink,
					$trans["bandeira"]
				);
				
				if (array_key_exists($trans['produto'], $instances['options'][$trans['bandeira']]['options'])) {
					$trans['forma_pagamento'] = $instances['options'][$trans['bandeira']]['options'][$trans['produto']];
				} else { // PARCELAMENTO
					$trans['forma_pagamento'] = sprintf("Parcelado %dx", $trans['parcelas']);
				}
				$trans["options"] = sprintf(
					"<a 
						class=\"form-icon xpay-cielo-do-capture-link\" 
						onclick=\"_sysclass('load', 'xpay_cielo').doCaptureAction('%d');\" 
						href=\"javascript: void(0);\"
					>
					<img src=\"themes/default/images/others/transparent.gif\" class=\"sprite16 sprite16-arrow_right\">
					</a>",
					$trans['id']
				);
				
				$trans["bandeira_image"] = sprintf(
					"<img src=\"%simages/%s.png\" />",
					$this->moduleBaseLink,
					$trans["bandeira"]
				);
			}
	
			if ($_GET['output'] == 'json') {
				echo json_encode(array("aaData"	=> $transactions));
				exit;
			}
		}
		exit;
	}

	public function doCaptureAction()
	{
		if ($this->getCurrentUser()->getType() == 'administrator') {
			if (!empty($_POST['transaction_tid'])) {
				require_once (dirname(__FILE__) . '/includes/module_xpay_cielo.pedido.model.php');
				
				$tidKey = $_POST['transaction_tid'];
			
				$Pedido = new Pedido_Model();
			
				$Pedido->tid = $tidKey;

		        if (!is_null($Pedido->tid)) {
					list($coursePay) = sC_getTableData(
						"module_xpay_course_negociation", "course_id", 
						sprintf("id IN (
							SELECT negociation_id FROM `module_xpay_cielo_transactions` WHERE tid = '%s'
						)", $Pedido->tid)
					);


					$course = new MagesterCourse($coursePay['course_id']);

					$ies_id = $course->course['ies_id'];
					if (array_key_exists($ies_id, $this->conf['cielo_keys'])) {
						$chaves_cielo = $this->conf['cielo_keys'][$ies_id];
					} else {
						return false;
					}
				}
				$Pedido->dadosEcNumero = $chaves_cielo['CIELO'];
				$Pedido->dadosEcChave = $chaves_cielo['CIELO_CHAVE'];
			
				list($transaction) = sC_getTableData(
					"module_xpay_cielo_transactions trn
					LEFT JOIN module_xpay_cielo_transactions_to_invoices trn2inv ON (trn.id = trn2inv.transaction_id AND trn.negociation_id = trn2inv.negociation_id)
					LEFT JOIN module_xpay_invoices inv ON (trn2inv.negociation_id = inv.negociation_id AND trn2inv.invoice_index = inv.invoice_index)",
					"trn.id, trn.tid, trn.negociation_id, trn2inv.invoice_index, trn.data, trn.descricao, trn.bandeira, trn.status, trn.pedido_id, trn.valor as valor_total,
					inv.valor, inv.data_vencimento",
					sprintf("tid = '%s'", $Pedido -> tid)
				);
				
				$objResposta = $Pedido->RequisicaoConsulta();
				// RECARREGA INFORMAÇÕES, APÓS CAPTURA
				$consultaArray = $this->cieloReturnToArray($objResposta);
			
				switch($consultaArray['status'])
				{
					case "4":
						$status = "Autorizada";
				
						$message 		= "Pagamento capturado com sucesso";
						$message_type	= "success";
						
						$objResposta = $Pedido->RequisicaoCaptura();
						// RECARREGA INFORMAÇÕES, APÓS CAPTURA
						$consultaArray = $this->cieloReturnToArray($objResposta);
						
						$xpayModule = $this->loadModule("xpay");
							
						$xpayModule->unlockInvoice(
							$transaction['negociation_id'],
							$transaction['invoice_index']
						);
						
						// ATUALIZAR STATUS NO BANCO DE DADOS
						sC_updateTableData(
							"module_xpay_cielo_transactions",
							array("status" => $consultaArray['status']),
							sprintf("tid = '%s'", $Pedido -> tid)
						);
						
						$xpayModule->insertInvoicePayment(
							$transaction['negociation_id'],
							$transaction['invoice_index'],
							floatval($transaction['valor_total']),
							'cielo',
							$transaction['id'],
							$transaction['data']
						);
						
						// BLOCK THIS INVOICE, UNTIL THIS HAS BEEN CAPTURED
						break;
					case "6":
						// THE TRANSACTION CAN BE AUTO CAPTURED, BECAUSE DEBIT PAYMENTS
						$message 		= "Transação já capturada";
						$message_type	= "failure";
						break;
					default:
						
						// THE TRANSACTION CAN BE AUTO CAPTURED, BECAUSE DEBIT PAYMENTS
						$message 		= "Não é possível capturar uma transação neste status.";
						$message_type	= "failure";
						break;
				}
			}
			$response = array(
				'message'		=> $message,
				'message_type'	=> $message_type
			);
			
			echo json_encode($response);
			exit;
		}
		
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
					"xscope_id"		=> 1,
					"xentify_id"	=> 1,
					"options"	=> array(
						"1"	=> "Crédito à Vista",
						"A"	=> "Débito à Vista"
					),
					"default_option"	=> 1,
					"template"	=> $this->moduleBaseDir . "templates/includes/instance.options.tpl"
				),
				"mastercard"	=> array(
					"name"	=> "Mastercard",
					"xscope_id"		=> 1,
					"xentify_id"	=> 1,
					"options"	=> array(
						"1"	=> "Crédito à Vista",
						"A"	=> "Débito à Vista"
					),
					"default_option"	=> 1,
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

		return $instances['options'];
	}
	public function fetchPaymentInstanceOptionsTemplate($instance_id, $negociation_id)
	{
		$smarty = $this->getSmartyVar();
		$instances = $this->getPaymentInstances();
		$options = $instances['options'][$instance_id]['options'];
		$defaultOption = $instances['options'][$instance_id]['default_option'];

		// CHECK IF USER HAS A TOKENIZED CARD
		if (is_numeric($negociation_id)) {
			$xpayModule = $this->loadModule("xpay");

			$negociation = $xpayModule->_getNegociationById($negociation_id);
			$neg_user_id = $negociation['user_id'];

			$tokenData = $this->getUserToken($neg_user_id, $instance_id);

			if ($tokenData) {
				$options["T"] = sprintf("Cartão Registrado: <strong>%s</strong>", $tokenData['cartao']);
				$defaultOption = "T";
			}
		}
		$tpl = $instances['options'][$instance_id]['template'];
		$smarty -> assign("T_XPAY_CIELO_OPT", $options);
		$smarty -> assign("T_XPAY_CIELO_DEFAULT_OPTION", $defaultOption);

		return $smarty -> fetch($tpl);
	}
	public function fetchPaymentReceiptTemplate($negociation_id, $invoice_index)
	{
		require_once (dirname(__FILE__) . '/includes/module_xpay_cielo.pedido.model.php');
		$smarty = $this->getSmartyVar();
		
		$transactions = sC_getTableData(
			"module_xpay_cielo_transactions trn
			LEFT JOIN module_xpay_cielo_transactions_to_invoices trn2inv ON (trn.id = trn2inv.transaction_id AND trn.negociation_id = trn2inv.negociation_id)
			LEFT JOIN module_xpay_invoices inv ON (trn2inv.negociation_id = inv.negociation_id AND trn2inv.invoice_index = inv.invoice_index)",
			"trn.id, trn.tid, trn.negociation_id, trn2inv.invoice_index, trn.data, trn.descricao, trn.bandeira, trn.status, trn.pedido_id, trn.valor as valor_total,
			inv.valor, inv.data_vencimento",
			sprintf("inv.negociation_id = %d AND inv.invoice_index = %d", $negociation_id, $invoice_index)
		);

		$transaction = reset($transactions);

		list($coursePay) = sC_getTableData("module_xpay_course_negociation", "course_id", sprintf("id = %d", $negociation_id));


		$course = new MagesterCourse($coursePay['course_id']);

		$ies_id = $course->course['ies_id'];
		if (array_key_exists($ies_id, $this->conf['cielo_keys'])) {
			$chaves_cielo = $this->conf['cielo_keys'][$ies_id];
		} else {
			return false;
		}
		
		$Pedido = new Pedido_Model();
		$Pedido->dadosEcNumero = $chaves_cielo['CIELO'];
		$Pedido->dadosEcChave = $chaves_cielo['CIELO_CHAVE'];
		$Pedido->tid = $transaction['tid'];
		$objResposta = $Pedido->RequisicaoConsulta();
		$consultaArray = $this->cieloReturnToArray($objResposta);
		// Consulta situação da transação
		
		$xpayModule = $this->loadModule("xpay");
		$invoiceData = $xpayModule->_getNegociationInvoiceByIndex($transaction['negociation_id'], $transaction['invoice_index']);
		$transactionAndInvoice = array_merge($invoiceData, $transaction);
		
		$statuses = array(
			0 	=> __XPAY_CIELO_CREATED,
			1	=> "Em andamento",
			2	=> "Autenticada",
			3	=> "Não autenticada",
			4	=> "Autorizada",
			5	=> "Não autorizada",
			6	=> "Capturada",
			8	=> "Não capturada",
			9	=> __XPAY_CIELO_CANCELLED,
			10	=> "Em autenticação"
		);
		
		$status = $statuses[$consultaArray['status']];
		
		
		$smarty -> assign("T_XPAY_CIELO_STATUS", $status);
		$smarty -> assign("T_XPAY_CIELO_CONSULTA", $consultaArray);
			
		///$transaction = $xpayModule->_calculateInvoiceDetails($transaction);
		$smarty -> assign("T_XPAY_CIELO_TRANS", $transactionAndInvoice);

		/*
		$this->setMessageVar($message, $message_type);
		
		$return_link = sprintf(
			str_replace("_cielo", "", $this->moduleBaseUrl) . "&action=view_user_course_statement&negociation_id=%s&invoice_index=%s&message=%s&message_type=%s",
			$transaction['negociation_id'],
			$transaction['invoice_index'],
			$message,
			$message_type
		);
		
		$smarty -> assign("T_XPAY_CIELO_RETURN_LINK", $return_link);
		*/
		$this->assignSmartyModuleVariables();
		$this->injectCSS("printer.clean");
		
		return $smarty->fetch($this->moduleBaseDir . "templates/includes/payment.receipt.tpl");
	}

	public function initPaymentProccess($payment_id, $invoice_id, array $data)
	{
		// CREATE FORM
		//$smarty = $this->getSmartyVar();
		if (!is_object($this->getParent())) {
			$currentContext = $this;
		} else {
			$currentContext = $this->getParent();
        }

        if (is_null($this->getParent())) {
            $xpayModule = $this->loadModule("xpay");
            $this->setParent($xpayModule);
        }
		
		$Pedido = $this->processPaymentForm($payment_id, $invoice_id, $data);
		
		//$smarty -> assign("T_XPAY_CIELO_URL_AUTENTICACAO", $Pedido->urlAutenticacao);
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
	
		$transactionID = sC_insertTableData("module_xpay_cielo_transactions", $fields);
		
		$fieldsLink = array(
			"negociation_id"	=> $payment_id,
			"invoice_index"		=> $invoice_id,
			"transaction_id"	=> $transactionID
		);
		
		sC_insertTableData("module_xpay_cielo_transactions_to_invoices", $fieldsLink);

        if (!$data['return_data']) {
            //echo $Pedido->urlAutenticacao;
            //  
    		sC_redirect($Pedido->urlAutenticacao, false, false, true);
	    	exit;
        } else {
            return array(
                'link'  => $Pedido->urlAutenticacao,
                'tid'   => $Pedido->tid  
            );
//            return $Pedido->urlAutenticacao;
        }

	}

	private function processPaymentForm($payment_id, $invoice_id, $values)
	{
		require_once (dirname(__FILE__) . '/includes/module_xpay_cielo.pedido.model.php');

		$invoiceData = $this->getParent()->_getNegociationInvoiceByIndex($payment_id, $invoice_id);

		$course = new MagesterCourse($invoiceData['course_id']);

		$ies_id = $course->course['ies_id'];
		if (array_key_exists($ies_id, $this->conf['cielo_keys'])) {
			$chaves_cielo = $this->conf['cielo_keys'][$ies_id];
		} else {
			return false;
		}
		
        $Pedido = new Pedido_Model();

		$Pedido->dadosEcNumero = $chaves_cielo['CIELO'];
		$Pedido->dadosEcChave = $chaves_cielo['CIELO_CHAVE'];

        if (empty($values["option"])) {
            $Pedido->formaPagamentoBandeira = $values["bandeira"];
        } else {
            $Pedido->formaPagamentoBandeira = $values["option"];
        }

        if (empty($values["instance_option"])) {
            $values["instance_option"] = $values["parcelas"];
        } else {
            $values["instance_option"] = $values["instance_option"];
        }
        
		if ($values["instance_option"] != "A" && $values["instance_option"] != "1" && $values["instance_option"] != "T") {
			$Pedido->formaPagamentoProduto = $this->conf['payment_subdivision_method'];
			$Pedido->formaPagamentoParcelas = $values["instance_option"];
		} else {
			if ($values["instance_option"] == 'T') {
				$Pedido->formaPagamentoProduto = 1;
			} else {
				$Pedido->formaPagamentoProduto = $values["instance_option"];	
			}
			$Pedido->formaPagamentoParcelas = 1;
		}

		$Pedido->capturar = $this->conf['auto_capture'];
		$Pedido->autorizar = $this->conf['authorization'];
		
		$Pedido->gerarToken = $values['gerar_token'] ? 'true' : 'false';
		/// CHECAR COMO INCLUIR O VALOR


		// SAME NUMBER AS BOLETO "nosso número"
		$Pedido->dadosPedidoNumero = $this->getParent()->createInvoiceID($payment_id, $invoice_id);

		//$Pedido->dadosPedidoValor = $values["produto"];
		$Pedido->dadosPedidoValor = floor((floatval($invoiceData['valor']) + floatval($invoiceData['total_reajuste'])) * 100);

		//$Pedido->dadosPedidoValor = 101;
		
		//$ies = reset($this->getCurrentUserIes());
		
		$currentUser = $this->getEditedUser(true, $invoiceData['user_id']);
		if ($currentUser) {
			$currentUser = $currentUser->user;
		}
		/** @todo Get the user lesson or course */
		$currentService = "";

        if (!empty($values['campo_livre'])) {
            $Pedido->campoLivre = $values['campo_livre'];
        } else {
    		$Pedido->campoLivre =
	    		sprintf("%s - %s", $currentService, $invoiceData['is_registration_tax'] == 1 ? "Matricula" : "Mensalidade " . ($invoiceData['invoice_index']));
        }
		$Pedido->dadosPedidoDescricao =
			sprintf("%s - %s %s", formatLogin(null, $currentUser), $invoiceData['is_registration_tax'] == 1 ? "Matricula" : "Mensalidade " . ($invoiceData['invoice_index']), $currentService);

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
        //Aceitar Parametros externos aqui...
        if (!empty($values['return_url'])) {
            $Pedido->urlRetorno = $values['return_url'];
        } else {
    		$Pedido->urlRetorno = ReturnURL();
        }

        $doNormal = false;


        if ($values['instance_option'] == 'T') {
        	// TRY TO MAKE A TOKENIZED TRANSACTION
        	$tokenData = $this->getUserToken($currentUser['id'], $values['option']);
        	if ($tokenData) {
	        	$values['token'] = $tokenData['token'];

        	}
        }

        // TRANSACAO NORMAL OU COM TOKEN ??

        if (!empty($values['token'])) {
        	list($tokenData) = sC_getTableData("module_xpay_cielo_card_tokens", "*", sprintf("token = '%s'", $values['token']));
        	
        	if (count($tokenData) > 0) {
        		$Pedido->token = $values['token'];
        		$Pedido->formaPagamentoBandeira = $tokenData["bandeira"];
        		$Pedido->autorizar = $this->conf['recurring_authorization'];
        		$objResposta = $Pedido->RequisicaoTransacao("token");
        		
        		var_dump($Pedido);
        		var_dump($objResposta);
        		
        		exit;
        	} else {
        		$doNormal = true;
        	}
        } else {
        	$doNormal = true;
        } 

        if ($doNormal) {
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
        } else {
        	// DO TOKENIZED

        	exit;
        }
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



		$tidKey = $this->getTidKey();

		if (array_key_exists($tidKey, $_POST)) {
            $Pedido->tid = $_POST[$tidKey];
        } elseif (array_key_exists($tidKey, $_GET)) {
            $Pedido->tid = $_GET[$tidKey];
        } elseif ($this->getCache($tidKey)) {
			$Pedido->tid = $this->getCache($tidKey);
        } else {
            // CHECAR TID PELO NEGOCIATION_ID, INVOICE_INDEX
            $tidData = sC_getTableData(
                "module_xpay_cielo_transactions_to_invoices trans2inv LEFT JOIN module_xpay_cielo_transactions trans ON (trans2inv.transaction_id = trans.id)
                LEFT JOIN module_xpay_course_negociation neg ON (trans2inv.negociation_id = neg.id)
                ",
                "tid",
                sprintf("trans2inv.negociation_id = %d AND invoice_index = %d AND neg.user_id = %d", $_GET['negociation_id'], $_GET['invoice_index'], $this->getCurrentUser()->user['id']),
                "data DESC LIMIT 1"
            );

            if (count($tidData) == 0) {
                $Pedido->tid = null;
            } else {
                $Pedido->tid = $tidData[0]['tid'];
            }
        }
        if (!is_null($Pedido->tid)) {
			list($coursePay) = sC_getTableData(
				"module_xpay_course_negociation", "course_id", 
				sprintf("id IN (
					SELECT negociation_id FROM `module_xpay_cielo_transactions` WHERE tid = '%s'
				)", $Pedido->tid)
			);


			$course = new MagesterCourse($coursePay['course_id']);

			$ies_id = $course->course['ies_id'];
			if (array_key_exists($ies_id, $this->conf['cielo_keys'])) {
				$chaves_cielo = $this->conf['cielo_keys'][$ies_id];
			} else {
				return false;
			}
		}
		$Pedido->dadosEcNumero = $chaves_cielo['CIELO'];
		$Pedido->dadosEcChave = $chaves_cielo['CIELO_CHAVE'];



		$status = -1;
		// Consulta situação da transação

		if (!is_null($Pedido->tid)) {
			$objResposta = $Pedido->RequisicaoConsulta();
			$consultaArray = $this->cieloReturnToArray($objResposta);
			
			// DEPENDENDO DO VALOR DO STATUS, REALIZAR A CAPTURA
			// A CAPTURA SERÁ FEITA POSTERIORMENTE
			/*
			if ($consultaArray['status'] == 4) {
				//var_dump($consultaArray['autorizacao']['valor']);
				$objResposta = $Pedido->RequisicaoCaptura();
				// RECARREGA INFORMAÇÕES, APÓS CAPTURA
				$consultaArray = $this->cieloReturnToArray($objResposta);
			}
			*/
			$message 		= "Pagamento em andamento";
			$message_type	= "warning";
			
			
			//var_dump($consultaArray);
			// ATUALIZAR STATUS NO BANCO DE DADOS
			sC_updateTableData(
				"module_xpay_cielo_transactions",
				array("status" => $consultaArray['status']),
				sprintf("tid = '%s'", $Pedido -> tid)
			);

			list($transaction) = sC_getTableData(
				"module_xpay_cielo_transactions trn
				LEFT JOIN module_xpay_cielo_transactions_to_invoices trn2inv ON (trn.id = trn2inv.transaction_id AND trn.negociation_id = trn2inv.negociation_id)
				LEFT JOIN module_xpay_invoices inv ON (trn2inv.negociation_id = inv.negociation_id AND trn2inv.invoice_index = inv.invoice_index)",
				"trn.id, trn.tid, trn.negociation_id, trn2inv.invoice_index, trn.data, trn.descricao, trn.bandeira, trn.status, trn.pedido_id, trn.valor as valor_total, 
				inv.valor, inv.data_vencimento",
				sprintf("tid = '%s'", $Pedido -> tid)
			);

			$xpayModule = $this->loadModule("xpay");
			
			$invoiceData = $xpayModule->_getNegociationInvoiceByIndex($transaction['negociation_id'], $transaction['invoice_index']);
			
			$transactionAndInvoice = array_merge($invoiceData, $transaction);
			
			switch($consultaArray['status'])
			{
				case "0":
					$status = __XPAY_CIELO_CREATED;
					break;
				case "1":
					$status = "Em andamento";
					break;
				case "2":
					$status = "Autenticada";
					break;
				case "3":
					$status = "Não autenticada";

					$message 		= "Autenticação não concluída";
					$message_type	= "failure";

					break;
				case "4":
					$status = "Autorizada";

					$message 		= "Pagamento Autorizado";
					$message_type	= "success";
					
					$xpayModule->lockInvoice(
						$invoiceData['negociation_id'],
						$invoiceData['invoice_index'],
						"Aguardando Captura"
					);
					// BLOCK THIS INVOICE, UNTIL THIS HAS BEEN CAPTURED
					break;
				case "5":
					$status = "Não autorizada";

					$message 		= "Pagamento não Autorizado";
					$message_type	= "failure";

					break;
				case "6":
					// THE TRANSACTION CAN BE AUTO CAPTURED, BECAUSE DEBIT PAYMENTS
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
				case "8":
					$status = "Não capturada";

					$message 		= "Pagamento não Autorizado";
					$message_type	= "failure";

					break;
				case "9":
					$status = __XPAY_CIELO_CANCELLED;

					$message 		= "Pagamento Cancelado";
					$message_type	= "warning";

					// CALL EVENTS

					break;
				case "10":
					$status	= "Em autenticação";
					break;
				default:
					$status = "Transação não encontrada";
			}
			
			if ($consultaArray['token']['has_token']) {
				// SAVE USER CARD TOKEN
				$tokenID = $this->saveTokenForUserCard(
					$invoiceData['user_id'],
					$consultaArray['token']["token"],
					$consultaArray['token']["cartao"],
					$consultaArray['forma_pagamento']["bandeira"],
					$consultaArray['token']["status"]
				);
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
		
		$return_link = sprintf(
			str_replace("_cielo", "", $this->moduleBaseUrl) . "&action=view_user_course_statement&negociation_id=%s&invoice_index=%s&message=%s&message_type=%s",
			$transaction['negociation_id'],
			$transaction['invoice_index'],
			$message,
			$message_type
		);
		
		$smarty -> assign("T_XPAY_CIELO_RETURN_LINK", $return_link);
		$this->assignSmartyModuleVariables();
		
		$this->injectCSS("printer.clean");

		return true;
	}
	
	private function saveTokenForUserCard($user_id, $token, $cartao, $bandeira, $status)
	{
		$tokenID = sC_insertTableData(
			"module_xpay_cielo_card_tokens", 
			array(
				"user_id"	=> $user_id,
				"token"		=> $token,
				"cartao"	=> $cartao,
				"bandeira"	=> $bandeira,
				"status_id"	=> $status
			)
		);
		return $tokenID;
	}

	private function cieloReturnToArray($xmlObject)
	{
		$DadosPedido	= "dados-pedido";
		$DataHora		= "data-hora";
		$FormaPagamento	= "forma-pagamento";
		
		$dadosToken				= "dados-token";
		$codigoToken			= "codigo-token";
		$numeroCartaoTruncado 	= "numero-cartao-truncado";

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
				"mensagem"	=> (string) $xmlObject->autorizacao->mensagem,
				"data_hora"	=> date("Y-m-d H:i:s", strtotime((string) $xmlObject->autorizacao->$DataHora)),
				"valor"		=> floatval((string) $xmlObject->autorizacao->valor) / 100,
				"lr"		=> (string) $xmlObject->autorizacao->lr,
				"arp"		=> (string) $xmlObject->autorizacao->arp
			),
			"captura" => array(
				"codigo"	=> (string) $xmlObject->captura->codigo,
				"mensagem"	=> (string) $xmlObject->captura->mensagem,
				"data_hora"	=> date("Y-m-d H:i:s", strtotime((string) $xmlObject->captura->$DataHora)),
				"valor"		=> floatval((string) $xmlObject->captura->valor) / 100
			),
			"token"	=> array(
				"has_token"	=> !empty($xmlObject->token->$dadosToken->$codigoToken),
				"token"		=> (string) $xmlObject->token->$dadosToken->$codigoToken,
				"status"	=> (string) $xmlObject->token->$dadosToken->status,
				"cartao"	=> (string) $xmlObject->token->$dadosToken->$numeroCartaoTruncado
					
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

	private function getTidKey($login)
    {
        if (!is_null($login)) {
        } else {
            $currentUser = $this->getCurrentUser();
            $login = $currentUser->user['login'];
        }
		return $tidKey = md5($login . date("Ymd"));
	}

	private function getUserToken($user_id, $bandeira) {
		if (is_numeric($user_id)) {
			$tokenDB = sC_getTableData(
				"module_xpay_cielo_card_tokens",
				"id, user_id, token, cartao, bandeira, status_id",
				sprintf("user_id = %d AND bandeira = '%s'", $user_id, $bandeira)
			);
			if (count($tokenDB) > 0) {
				return reset($tokenDB);
			}
		}
		return false;
	}
}
