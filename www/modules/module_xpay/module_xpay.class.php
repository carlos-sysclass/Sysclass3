<?php
require_once dirname(__FILE__) . '/module_xpay_submodule.interface.php';

class module_xpay extends MagesterExtendedModule
{
	const INVOICE_ID_TEMPLATE_SEM_DV	= "%04d%02d%01d";
	const _XPAY_AUTOPAY		= 2;

	const __NUCLEO_ULT		= 13;
	const __NUCLEO_POS		= 16;

	private $rules = null;
	private $rulesTags = null;

	//	const NOSSO_NUMERO_TEMPLATE		= "%04d%02d%01d%01d";

	protected static $subModules = null;

	public function getName()
	{
		return "XPAY";
	}
	public function getPermittedRoles()
	{
		return array("administrator", "student");
	}
	public function getTitle($action)
	{
		switch ($action) {
			case "view_to_send_invoices_list" : {
				return __XPAY_VIEW_TO_SEND_INVOICES_LIST;
			}
			case "do_payment" : {
				return __XPAY_DO_PAYMENT;
			}
			case "view_user_statement" : {
				return __XPAY_USER_STATEMENT;
			}
			case "view_user_course_statement" : {
				return __XPAY_USER_COURSE_STATEMENT;
			}
			case "edit_negociation" : {
				return __XPAY_USER_COURSE_STATEMENT;
			}
			case "simulate_due_balance_negociation" : {
				return __XPAY_USER_COURSE_STATEMENT;
			}
			case "print_invoice" : {
				return __XPAY_SHOW_PAYMENTS_SUMMARY;
			}
			case "" :
			case $this->getDefaultAction() : {
				return __XPAY_MODULE_NAME;
			}
			default : {
				return parent::getTitle($action);
			}

		}
	}
	public function getUrl($action)
	{
		switch ($action) {
			case "do_payment" :
			case "view_user_statement" :
			case "view_user_course_statement" : {
				foreach ($_GET as $index => $get) {
					if (!in_array($index, array("ctg", "op", "action"))) {
						$params[] = $index . "=" .  $get;
					}
				}
				return $this->moduleBaseUrl . "&action=" . $action . "&" . implode("&", $params);
			}
			case "" : {
				return $this->moduleBaseUrl;
			}
			default : {
				return parent::getUrl($action);
			}
		}
	}
	public function getDefaultAction()
	{
		if ($this->getCurrentUser()->getType() == 'student') {
			return "view_user_statement";
		} else {
			return "show_payments_summary";
		}
	}
	public function loadConfig()
	{
		$config = array(
			'widgets'	=> array(
				'last_payments' => array(
					'count'	=> 10
				),
				'last_files' => array(
					'submodule_index'	=> 'XPAY_BOLETO',
					'count'				=> 5
				)
			)
		);
		return $config;
	}
	/* BLOCK FUNCTIONS */
	public function loadInvoicesBlock($blockIndex = null)
	{
		$smarty = $this->getSmartyVar();
		$currentUser = $this->getCurrentUser();
		// GET PAYMENT ID, AND INVOICE ID
		/*
		 * Montar um formulário para selecionar o pagamento e parcela
		* Caso somente um registro de pagamento, pré-selecionar
		* Caso o regime de pagamento não permita realizar pagamentos de parcelas fora de ordem, pré selecionar
		*/
		$xUserModule = $this->loadModule("xuser");
		$exUserType = $xUserModule->getExtendedTypeID($currentUser);
		if ($exUserType == 'student' || $exUserType == 'pre_student') {
			// CHECK IF NEGOCIATION ID IS FROM USER
			$negocData = $this->_getNegociationByContraints(array(
				'login'				=> $this->getCurrentUser()->user['login']
			));
		} else {
			return false;
		}

		if (count($negocData) == 0) {
			return false;
		}

		$today = new DateTime("today");

		foreach ($negocData['invoices'] as $invoiceIndex => $invoice) {
			$negocData['invoices'][$invoiceIndex]['overdue'] = false;
			if ($invoice['valor']+$invoice['total_reajuste'] <= $invoice['paid']) {
				unset($negocData['invoices'][$invoiceIndex]);
			}
			$date = date_create_from_format("Y-m-d H:i:s", $invoice['data_vencimento']);

			if (is_object($date)) {
				$diff = $date->diff($today);

				if ($diff->invert == 1 && $diff->days > 20) {
					unset($negocData['invoices'][$invoiceIndex]);
				} elseif ($diff->invert == 0 && $diff->days > 0) { // IT'S OVERDUE
					$negocData['invoices'][$invoiceIndex]['overdue'] = true;
				}
			}
		}

		if (count($negocData['invoices']) == 0) {
			return false;
		}


		$smarty -> assign("T_XPAY_STATEMENT", $negocData);

		$this->getParent()->appendTemplate(array(
			'title'			=> __XPAY_VIEW_MY_STATEMENT,
			'template'		=> $this->moduleBaseDir . 'templates/blocks/invoices.list.tpl',
			'contentclass'	=> 'blockContents',
			'options'		=> array(
				array(
					'href'			=> $this->moduleBaseUrl . "&action=view_user_statement",
					'image'			=> 'others/transparent.png',
					'text'			=> __XPAY_VIEW_MY_STATEMENT,
					'image-class'	=> 'sprite16 sprite16-do_pay'
				)
			)
		), $blockIndex);

		$this->assignSmartyModuleVariables();

		return true;
	}
	/* ACTIONS FUNCTIONS */
	/* DEFAULT ACTION */
	public function showPaymentsSummaryAction()
	{
		$smarty = $this->getSmartyVar();
		// BLOCOS ???
		// 1. Ultimos pagamentos recebidos [ ok ]
		// 2. Últimos arquivos enviados [ ok ]
		// 3. Ultimos boletos enviados
		// 4. Montante a Receber
		// 5. Valor recebido no último mês

		// - Últimos pagamentos recebidos
		$lastPaymentsData = $this->_getLastPaymentsList(null, $this->getConfig()->widgets['last_payments']['count']);
		$smarty -> assign("T_XPAY_LAST_PAYMENTS", $lastPaymentsData);

		// - Últimos arquivos enviados
		$currentOptions = $this->getSubmodules();

		$lastProcessedFilesData = $currentOptions[$this->getConfig()->widgets['last_files']['submodule_index']]->getProcessedFilesList($this->getConfig()->widgets['last_files']['count']);
		$smarty -> assign("T_XPAY_LAST_FILES", $lastProcessedFilesData);


		// - Débitos a mais tempo
		$smarty = $this->getSmartyVar();
		$debtsLists = $this->_getUserInDebts(10);
		foreach ($debtsLists as &$debt) {
			$debt['username'] = formatLogin(null, $debt);
		}
		$smarty->assign("T_XPAY_DEBTS_LIST", $debtsLists);

		//eF_redirect($this->moduleBaseUrl . "&action=view_to_send_invoices_list");
		//exit;
	}
	public function viewLastSendedFilesAction()
	{
		$smarty = $this->getSmartyVar();

		// - Últimos arquivos enviados
		$currentOptions = $this->getSubmodules();

		$lastProcessedFilesData = $currentOptions[$this->getConfig()->widgets['last_files']['submodule_index']]->getProcessedFilesList();

		$smarty -> assign("T_XPAY_LAST_FILES", $lastProcessedFilesData);
	}
	public function viewLastPaidInvoicesAction()
	{
		$smarty = $this->getSmartyVar();
		// BLOCOS ???
		// 1. Ultimos pagamentos recebidos
		// 2. Ultimos boletos enviados
		// 3. Montante a Receber
		// 4. VAlor recebido no último mês

		// 1. Ultimos pagamentos recebidos
		$lastPaymentsData = $this->_getLastPaymentsList();
		$smarty -> assign("T_XPAY_LAST_PAYMENTS", $lastPaymentsData);
		//eF_redirect($this->moduleBaseUrl . "&action=view_to_send_invoices_list");
		//exit;
	}
	public function viewFileDetailsAction()
	{
		$instance_id 	= $_POST['method_index'];
		$fileName		= $_POST['name'];

		$currentOptions = $this->getSubmodules();

		$currentSubModule = $currentOptions[$this->getConfig()->widgets['last_files']['submodule_index']];

		echo $currentSubModule->returnedFile2Html($instance_id, $fileName);
		exit;
	}
	public function importFileToSystemAction()
	{
		$method_index 	= $_POST['method_index'];
		$fileName		= $_POST['name'];

		$currentOptions = $this->getSubmodules();

		$currentSubModule = $currentOptions[$this->getConfig()->widgets['last_files']['submodule_index']];

		$fullFileName = $currentSubModule->getFullPathByMethodIndex($method_index, $fileName);
		$status = $currentSubModule->importFileStatusToSystem($method_index, $fullFileName);

		// TRY TO RE-IMPORT FILE

		if ($status) {
			$return = array(
					"message" 		=> "Arquivo importado com sucesso. Caso os problemas persistam, entre em contato com suporte.",
					"message_type" 	=> "success",
					"status"		=> "ok",
					"data" => $fields
			);
		} else {
			$return = array(
				"message" 		=> "Occoreu um erro ao tentar importar este arquivo",
				"message_type" 	=> "error"
			);
		}
		echo json_encode($return);
		exit;
	}
	/*
	public function migrateToNewModelAction()
	{
		$paymentData = ef_getTableData(
			"users_to_courses uc
			JOIN courses c ON (uc.courses_ID = c.id)
			JOIN users u ON (uc.users_LOGIN = u.login)",
			"u.id as user_id, c.id as course_id",
			"uc.user_type = 'student' AND c.ies_id = 3"
		);
		foreach ($paymentData as $item) {
			$this->_migrateOldPaymentToNegociation(
				$item['user_id'],
				$item['course_id']
			);
		}
		exit;
	}
	*/
	private function _migrateOldPaymentToNegociation($user_id, $course_id)
	{
		// CHECK FOR USER PAYMENTS ON OLD TABLES
		list($paymentData) = ef_getTableData(
				"module_pagamento",
				"*",
				sprintf("user_id = %d AND course_id = %d", $user_id, $course_id)
		);
		// SE JÁ ESTÁ MIGRADO, ENTÃO VAI EMBORA
		if ($paymentData['migrated'] == 1) {
			return false;
		}

		$editUser = $this->getEditedUser(true, $user_id);
		$userNegociation = $this->_getNegociationByUserEntify($editUser->user['login'], $course_id, 'course');
		// SE JÁ TEM UMA NEGOCIAÇÃO REGISTRADA, ENTÃO VAI EMBORA

		if (count($userNegociation) > 0) {
			return false;
		}
		if (is_null($paymentData)) {
			return false;
		}

		$negociationData =  array(
			'timestamp'				=> strtotime($paymentData['data_registro']),
			'user_id'				=> $user_id,
			'course_id'				=> $course_id,
			//'registration_tax'		=> 0, // GET COURSE DEFAULTS
			//'parcelas'				=> 1, // GET COURSE DEFAULTS
			'negociation_index'		=> 1,
			'active'				=> 1,
			//'vencimento_1_parcela'	=> null,
			'ref_payment_id'		=> $paymentData['payment_id']
		);

		$negociationID = eF_insertTableData("module_xpay_course_negociation", $negociationData);

		// BUSCAR AS FATURAS E MIGRAR, INCLUINDO PAGAMENTOS (OPSS!!)
		$paymentFull = $this->getPaymentById($paymentData['payment_id']);

		$newInvoices = array();
		$boleto_transactions = $paid_items = $invoices_to_paid = $manual_transactions = array();

		foreach ($paymentFull['invoices'] as $oldInvoiceIndex => $oldInvoice) {
			$vencimento = date_create_from_format("Y-m-d H:i:s", $oldInvoice['data_vencimento']);

			if ($oldInvoice['valor'] == 0) {
				continue;
			}

			$newInvoices[$oldInvoiceIndex - 1] = $this->_createInvoice(
					$negociationID, $oldInvoice['valor'], $vencimento, $oldInvoice['invoice_id'], $oldInvoiceIndex - 1, false
			);
			if ($oldInvoiceIndex == 1) {
				$newInvoices[$oldInvoiceIndex - 1]['full_price'] = $oldInvoice['valor'];
				$newInvoices[$oldInvoiceIndex - 1]['valor'] = $oldInvoice['valor'];
			}

			// DO NOT CALCULATE OLD VALUES
			// PERSIST INVOICE
			$insertData = array(
				'negociation_id'		=> $newInvoices[$oldInvoiceIndex - 1]['negociation_id'],
				'invoice_index'			=> $newInvoices[$oldInvoiceIndex - 1]['invoice_index'],
				'description'			=> $newInvoices[$oldInvoiceIndex - 1]['description'],
				'invoice_id'			=> $newInvoices[$oldInvoiceIndex - 1]['invoice_id'],
				'invoice_sha_access'	=> $newInvoices[$oldInvoiceIndex - 1]['invoice_sha_access'],
				'valor'					=> $oldInvoice['valor'],
				'data_registro'			=> $newInvoices[$oldInvoiceIndex - 1]['data_registro'],
				'data_vencimento'		=> $newInvoices[$oldInvoiceIndex - 1]['data_vencimento'],
				'locked'				=> $oldInvoice['bloqueio']
			);
			eF_insertTableData("module_xpay_invoices", $insertData);

			if ($oldInvoice['pago'] == 1 || $oldInvoice['pago'] == 2) {
				$doManual = false;
				if ($oldInvoice['pago'] == 2) {
					// BUSCA O RETORNO DO DB
					list($transactionData) = ef_getTableData(
							"module_pagamento_boleto_invoices_return",
							"*",
							sprintf("payment_id = %d AND parcela_index = %d", $paymentData['payment_id'], $oldInvoiceIndex)
					);
					if (is_null($transactionData)) {
						// DO MANUAL
						$doManual = true;
					} else {
						// Boleto pago e baixado automaticamente (boleto Itaú)
						$boleto_transactions[] = $item = array(
								'instance_id' 			=> 'itau_extensao',
								'data_registro' 		=> $transactionData['data_registro'],
								'nosso_numero' 			=> $transactionData['nosso_numero'],
								'data_pagamento' 		=> $transactionData['data_pagamento'],
								'ocorrencia_id' 		=> $transactionData['ocorrencia_id'],
								'liquidacao_id' 		=> $transactionData['liquidacao_id'],
								'valor_titulo' 			=> $transactionData['valor_titulo'],
								'valor_abatimento' 		=> $transactionData['valor_abatimento'],
								'valor_desconto' 		=> $transactionData['valor_desconto'],
								'valor_juros_multa' 	=> $transactionData['valor_juros_multa'],
								'valor_outros_creditos'	=> $transactionData['valor_outros_creditos'],
								'valor_total'			=> $transactionData['valor_total'],
								'tag' 					=> $transactionData['tag'],
								'filename' 				=> $transactionData['filename'],
						);
						$transactionID = ef_insertTableData(
								"module_xpay_boleto_transactions",
								$item
						);
						$start_timestamp = strtotime($transactionData['data_pagamento']);
					}
				}

				if ($oldInvoice['pago'] == 1 || $doManual) {
					// Boleto pago e baixado automaticamente (boleto Itaú)
					$manual_transactions[] = $item = array(
						'instance_id' 			=> '',
						'data_registro' 		=> $oldInvoice['data_vencimento'],
						'description'			=> "",
						'filename'				=> null
					);
					$transactionID = ef_insertTableData(
						"module_xpay_manual_transactions",
						$item
					);

					$start_timestamp = strtotime($oldInvoice['data_vencimento']);
				}


				$paid_items[] = $item = array(
					'transaction_id'	=> $transactionID,
					'method_id' 		=> ($oldInvoice['pago'] == 1 || $doManual) ? 'manual' : 'boleto',
					'paid' 				=> $oldInvoice['valor'],
					'start_timestamp' 	=> $start_timestamp
				);

				$paidID = ef_insertTableData(
						"module_xpay_paid_items",
						$item
				);

				$invoices_to_paid[] = $item = array(
					'negociation_id'	=> $negociationID,
					'invoice_index'		=> $oldInvoiceIndex - 1,
					'paid_id'			=> $paidID
				);

				ef_insertTableData(
					"module_xpay_invoices_to_paid",
					$item
				);
			}
		}

		// CHECK USER'S DISCOUNTS, AND PUT HIM IN YOUR RESPECTIVE GROUP.
		$currentDiscount = floatval($paymentData['desconto']);
		$discountsToGroups = array(
			10	=> 4,
			30	=> 5,
			0	=> 6
		);

		if (is_numeric($discountsToGroups[$currentDiscount])) {
			$editUser->addGroups($discountsToGroups[$currentDiscount]);
		}
		/*
		eF_deleteTableData("module_xpay_course_negociation", "id = $negociationID");
		eF_deleteTableData("module_xpay_invoices", "negociation_id = $negociationID");
		eF_deleteTableData("module_xpay_boleto_transactions");
		eF_deleteTableData("module_xpay_paid_items");
		eF_deleteTableData("module_xpay_invoices_to_paid");
		exit;
		*/
		return true;
	}
	public function migrateDiscountToNewModelAction()
	{
		// CHECK FOR USER WHICH HAVE MORE THAN 5 PERCENT
		$paymentData = ef_getTableData(
				"users_to_courses uc
				JOIN courses c ON (uc.courses_ID = c.id)
				JOIN users u ON (uc.users_LOGIN = u.login)
				JOIN module_pagamento p ON (u.id = p.user_id AND c.id = p.course_id)",
				"u.id as user_id, c.id as course_id, p.*",
				"uc.user_type = 'student' AND c.ies_id = 1 AND p.desconto <> 5 AND p.desconto <> 0 AND u.id NOT IN (SELECT rule_xentify_id FROM module_xpay_price_rules)"
		);
		foreach ($paymentData as $item) {
			$ruleID = eF_insertTableData(
				"module_xpay_price_rules",
				array(
					"description"			=> sprintf('Desconto de %d%% para pagamento pontual', $item['desconto']),
					"rule_xentify_scope_id"	=> 7,
					"rule_xentify_id"			=> $item['user_id'],
					"entify_id"				=> 1,
					"entify_absolute_id"	=> 1,
					"type_id"				=> -1,
					"percentual"			=> 1,
					"valor"					=> $item['desconto'] / 100,
					"base_price_applied"	=> 1,
					"applied_on"			=> 'once',
					"order"					=> 1,
					"active"				=> 1,
				)
			);
			eF_insertTableData(
				"module_xuser_user_tags",
				array(
					'user_id'	=> $item['user_id'],
					'tag'		=> 'is_custom'
				)
			);
			eF_insertTableData(
				"module_xpay_price_rules_tags",
				array(
					'rule_id'	=> $ruleID,
					'tag'		=> 'is_not_overdue'
				)
			);
			eF_insertTableData(
				"module_xpay_price_rules_tags",
				array(
					'rule_id'	=> $ruleID,
					'tag'		=> 'is_not_full_paid'
				)
			);

		}
		exit;
	}
	public function viewUserStatementAction()
	{
		$smarty = $this->getSmartyVar();

		if ($this->getCurrentUser()->getType() == 'professor') {
			$this->setMessageVar("Acesso Não Autorizado", "failure");
			return false;
		}
		if ($this->getCurrentUser()->getType() == 'student') {
			$_GET['xuser_id'] = null;
			$this->getEditedUser(true, $this->getCurrentUser()->user['id']);
			$smarty -> assign("T_XPAY_IS_ADMIN", false);
		}
		if ($this->getCurrentUser()->getType() == 'administrator') {
			$smarty -> assign("T_XPAY_IS_ADMIN", true);
		}

		// GET ALL DEBITS
		$userDebits = $this->_getUserModuleNegociations();

		if (count($userDebits) == 1) {
			$userDebit = reset($userDebits);
			$_GET['negociation_id'] = $userDebit['id'];
			$this->setCurrentAction("view_user_course_statement", true);

		} elseif (count($userDebits) > 1) {
			// SIMPLY SHOW TWO OR MORE NEGOCIATIONS TO THE USER CHOICES.
			$smarty -> assign("T_XPAY_NEGOCIATIONS", $userDebits);
		} else {
			// USER HAS NO NEGOCIATIONS
			if ($this->getCurrentUser()->getType() == 'student') {
				$this->setMessageVar("Ocorreu um erro ao tentar recuperar as informações de seu extrato. Por favor entre em contato com o suporte.", "warning");
				return false;
			} else {
				// LOAD ALL USER'S COURSES AND LESSONS
				$this->setCurrentAction("create_negociation", true);
			}
			//


			/*
			// PROCESS INVOICES...
			$usersTotals= array(
				'base_price'	=> 0,
				'paid'			=> 0,
				'balance'		=> 0
			);

			foreach ($userDebits['invoices'] as $statement) {
				$usersTotals['base_price'] 	+= intval($statement['base_price']);
				$usersTotals['paid']	+= intval($statement['paid']);
			}

			// .. AND GROUPS
			foreach ($userDebits['grouped'] as $group_id => $group) {
				foreach ($group as $statement) {

					$usersTotals['base_price'] 	+= intval($statement['base_price']);
					$usersTotals['paid']	+= intval($statement['paid']);

					if (!is_array($groupedStatement[$group_id])) {
						$groupedStatement[$group_id] = array(
							"user_id"			=> $statement['user_id'],
							"type" 				=> "group",
							"group_id" 			=> $group_id,
							"module"			=> "",
							"matricula" 		=> MAX_VALUE,
							"base_price"		=> 0,
							"paid"				=> 0,
							"modality_id"		=> array(),
							"negociation_index"	=> 0,
							"modality"			=> array(),
							"balance"			=> 0,
							"course_count"		=> 0,
							"lesson_count"		=> 0
						);
					}

					if ($statement['type'] == 'course') {
						$groupedStatement[$group_id]["course_count"]++;
					} elseif ($statement['type'] == 'lesson') {
						$groupedStatement[$group_id]["lesson_count"]++;
					}

					$groupedStatement[$group_id]["matricula"]	= min(array($groupedStatement[$group_id]['matricula'], $statement['matricula']));
					$groupedStatement[$group_id]["base_price"]	= $groupedStatement[$group_id]['base_price'] + $statement['base_price'];
					$groupedStatement[$group_id]["paid"]		= $groupedStatement[$group_id]['paid'] + $statement['paid'];
					$groupedStatement[$group_id]["balance"]		= $groupedStatement[$group_id]['balance'] + $statement['balance'];
					array_push($groupedStatement[$group_id]["modality_id"], $statement['modality_id']);
					array_push($groupedStatement[$group_id]["modality"], $statement['modality']);

					$groupedStatement[$group_id]["modality_id"] = array_unique($groupedStatement[$group_id]["modality_id"]);
					$groupedStatement[$group_id]["modality"] = array_unique($groupedStatement[$group_id]["modality"]);
				}

				if ($groupedStatement[$group_id]["course_count"] == 0 && $groupedStatement[$group_id]["lesson_count"] > 0) {
					$groupedStatement[$group_id]['module'] = sprintf(
						"%d Disciplinas Agrupadas",
						$groupedStatement[$group_id]["lesson_count"]
					);
				} elseif ($groupedStatement[$group_id]["course_count"] > 0 && $groupedStatement[$group_id]["lesson_count"] == 0) {
					$groupedStatement[$group_id]['module'] = sprintf(
							"%d Cursos Agrupadas",
							$groupedStatement[$group_id]["course_count"]
					);
				} else {
					$groupedStatement[$group_id]['module'] = sprintf(
						"%d Cursos e %d Lições Agrupados",
						$groupedStatement[$group_id]["course_count"],
						$groupedStatement[$group_id]["lesson_count"]
					);
				}
			}


			$usersTotals['balance'] = intval($usersTotals['base_price'])-intval($usersTotals['paid']);

			if (is_array($groupedStatement)) {
				$smarty -> assign("T_XPAY_STATEMENT", $userDebits['invoices'] + $groupedStatement);
			} else {
				$smarty -> assign("T_XPAY_STATEMENT", $userDebits['invoices']);
			}
			$smarty -> assign("T_XPAY_STATEMENT_TOTALS", $usersTotals);
			*/
		}
	}
	public function viewUserCourseStatementAction()
	{
		$smarty = $this->getSmartyVar();

		if ($this->getCurrentUser()->getType() == 'professor') {
			$this->setMessageVar("Acesso Não Autorizado", "failure");
			return false;
		}
		if ($this->getCurrentUser()->getType() == 'student') {
			$_GET['xuser_id'] = null;
			$editUser = $this->getEditedUser(true, $this->getCurrentUser()->user['id']);

			$smarty -> assign("T_XPAY_IS_ADMIN", false);
		} elseif ($this->getCurrentUser()->getType() == 'administrator') {
			$smarty -> assign("T_XPAY_IS_ADMIN", true);
			$editUser = $this->getEditedUser(true);
		}
		if (is_numeric($_GET['negociation_id']) && eF_checkParameter($_GET['negociation_id'], "id")) {
			$userNegociation = $this->_getNegociationByID($_GET['negociation_id']);
			if ($this->getCurrentUser()->getType() == 'administrator') {
				$editUser = $this->getEditedUser(true, $userNegociation['user_id']);
			} else {
				if ($editUser->user['id'] != $userNegociation['user_id']) {
		                        $this->setMessageVar("Acesso não autorizado. REF: XPAY-0004", "failure");
		                        return false;
				}
			}
		}
		if (!$userNegociation && !($editCourse = $this->getEditedCourse()) && !($editLesson = $this->getEditedLesson())) {
			return false;
		}

		if (!$userNegociation && $editLesson) {
			$entify = $editLesson->lesson;
			$entify['type']	= 'lesson';
		}
		if (!$userNegociation && $editCourse) {
			$entify = $editCourse->course;
			$entify['type']	= 'course';
		}

		//	ONLY directions_ID = 13 COURSES
		//if ($entify['directions_ID'] == self::__NUCLEO_ULT) {
		$negociation_index = $_GET['negociation_index'];

		if (!$userNegociation) {
			$userNegociation = $this->_getNegociationByUserEntify($editUser->user['login'], $entify['id'], $entify['type'], $negociation_index);
		}

		foreach ($userNegociation['invoices'] as $invoice_index => $invoice) {

			$userNegociation['invoices'][$invoice_index]['applied_rules'] = $this->_getAppliedRules($invoice);
		}

		if (count($userNegociation) == 0) {
			// CHECK IF COURSE HAS A DEFAULT STATEMENT

			// CHECK IF HAS A PAYMENT REGISTERED ON OLD module
			if ($entify['type'] == 'course') {
				if (!$this->_migrateOldPaymentToNegociation($editUser->user['id'], $entify['id'])) {
					$negociationData = $this->_createUserDefaultStatement();
				}
			} else {
				$negociationData = $this->_createUserDefaultStatement();
			}

			$userNegociation = $this->_getNegociationByUserEntify($editUser->user['login'], $entify['id'], $entify['type'], $negociationData['negociation_index']);
		} elseif (count($userNegociation['invoices']) == 0) {
			//$this->_createUserDefaultInvoices($negociationData['id']);

			//$userNegociation = $this->_getNegociationByUserCourses($editUser->user['login'], $editCourse->course['id'], $userNegociation['negociation_index']);
		}

		$smarty -> assign("T_XPAY_STATEMENT", $userNegociation);
		/*
		echo "<pre>";
		var_dump($userNegociation);
		echo "<pre>";
		exit;
		*/
		$negociationTotals = array(
			'invoices_count'	=> count($userNegociation['invoices']),
			'valor'				=> 0,
			'paid'				=> 0,
			'balance'			=> 0
		);

		foreach ($userNegociation['invoices'] as $invoice) {
			$negociationTotals['valor']	+= $invoice['valor'];
			$negociationTotals['total_reajuste']	+= $invoice['total_reajuste'];
			$negociationTotals['paid']	+= $invoice['paid'];
		}
		$negociationTotals['balance'] = intval($negociationTotals['valor'])-intval($negociationTotals['paid']);

		$smarty -> assign("T_XPAY_STATEMENT_TOTALS", $negociationTotals);

		if ($this->getCurrentUser()->getType() == 'administrator') {
			/*
			$smarty->assign("T_XPAY_BLOCK_OPTIONS", array(
				array(
					'href'          => "javascript: _sysclass('load', 'xpay').;",
					'image'         => "16x16/fds.gif"
				)
			));
			*/
		}
		/*
		$groups = eF_getTableData("groups", "id, name", "active=1");
		
		$editUser->getGroups();
		var_dump($editUser -> groups);
		
		$smarty->assign("T_XPAY_DISCOUNT_GROUPS", $groups);
		$smarty->assign("T_XPAY_USER_GROUP", $groups);
		*/
	

		return true;
	}
	public function createNegociationAction()
	{
		$smarty = $this->getSmartyVar();

		$userDebits = $this->_getUserModuleStatus();
		// STEPS
			// 1. MODULE SELECTION (REQUIRED ONLY IF USER HAS 2 OR MORE MODULES)
			// 2.
		if (count($userDebits) == 0) {
			exit;
		} elseif (count($userDebits) > 1) {
			// CREATE FORM AND SELECT CURRENT
			$this->setMessageVar("Ainda não implementado. REF: XPAY-0001", "warning");
			return false;

			$smarty->assign("T_XPAY_USER_MODULES", $userDebits);

		} elseif (count($userDebits) == 1) {
			//$smarty->assign("T_XPAY_USER_MODULES", $userDebits);

			$negociationModules = array(
				array(
					'negociation_id'	=> null,
					'lesson_id'			=> $userDebits[0]['type'] == 'lesson' ? $userDebits[0]['module_id'] : 0,
					'course_id'			=> $userDebits[0]['type'] == 'course' ? $userDebits[0]['module_id'] : 0,
					'module_type'		=> $userDebits[0]['type']
				)
			);

			$editedUser = $this->getEditedUser(true, $userDebits[0]['user_id']);

			// CHECK IF THIS USER_NEGOCIATION EXISTS
			$userNegociations = $this->_getNegociationByContraints(
				array('user_id'	=> $userDebits[0]['user_id'])
			);

			if (count($userNegociations) > 0) {
				$this->setMessageVar("Ainda não implementado. REF: XPAY-0002", "warning");
				return false;
			}
		}

		$negociation = array(
			'timestamp'				=> time(),
			'user_id'				=> $editedUser->user['id'],
			'course_id'				=> 0,
			'lesson_id'				=> 0,
			'registration_tax'		=> 0,
			'parcelas'				=> 0,
			'negociation_index'		=> 1,
			'active'				=> 1
		);

		$negociationID = ef_insertTableData(
			"module_xpay_course_negociation",
			$negociation
		);

		foreach ($negociationModules as &$module) {
			$module['negociation_id'] = $negociationID;
		}
		eF_insertTableDataMultiple("module_xpay_negociation_modules", $negociationModules);

		$_GET['negociation_id'] = $negociationID;
		$this->setCurrentAction("edit_negociation", true);
	}
	public function editNegociationAction()
	{
		$smarty = $this->getSmartyVar();

		if ($this->getCurrentUser()->getType() == 'professor') {
			$this->setMessageVar("Acesso Não Autorizado", "failure");
			return false;
		}
		if ($this->getCurrentUser()->getType() == 'student') {
			$this->setMessageVar("Acesso Não Autorizado", "failure");
			return false;
		}
		if ($this->getCurrentUser()->getType() == 'administrator') {
			$smarty -> assign("T_XPAY_IS_ADMIN", true);
		}

		if (
			is_numeric($_GET['negociation_id']) &&
			eF_checkParameter($_GET['negociation_id'], "id")
		) {
			/// VERIFICAR SE O USUÀRIO TEM ACESSO. PELA "ies_id"
			$userNegociation = $this->_getNegociationByID($_GET['negociation_id']);

			$negociationUser = $this->getEditedUser(true, $userNegociation['user_id']);
		} else {
			$this->setMessageVar("Ocorreu um erro ao tentar acessar a sua negociação. Por favor entre em contato com o suporte", "failure");
			return false;
		}
		if (count($userNegociation['invoices']) > 0) {
			// SE JÁ TIVER FATURAS PAGAS,
//			$this->setMessageVar("Ainda não implementado. REF: XPAY-0003", "warning");
//			return false;
		}

		// CRIAR FORMULÁRIO DE CAIXA DE DIALOGOS
		$form = new HTML_QuickForm2("xpay_invoice_params_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);

		$form -> addStatic('saldo_total', array(), array('label'	=> __XPAY_BALANCE));
		$form -> addText('taxa_matricula', array('class' => 'small', 'alt' => 'decimal'), array('label'	=> __XPAY_REGISTRATION_TAX));
		//$form -> addStatic('saldo_restante', array("id" => 'dd'), array('label'	=> __XPAY_SUBTOTAL));

		$form -> addText('total_parcelas', array('class' => 'small', 'alt' => 'integer'), array('label'	=> __XPAY_PARCELAS_COUNT));
		$form -> addText('vencimento_1_parcela', array('class' => 'small', 'alt' => 'date'), array('label'	=> __XPAY_VENCIMENTO_1_PARCELA));

		$due_days = array();
		for ($i = 1; $i <= 31; $i++) {
			$due_days[$i] = $i;
		}

		$form -> addSelect('dia_vencimento', array('class' => 'medium'), array('label'	=> __XPAY_DUE_DAY, 'options' => $due_days));
		//

		$form -> addSubmit('submit_invoice_params', null, array('label'	=> __XPAY_SUBMIT));

		if ($form -> isSubmitted() && $form -> validate()) {
			// INSERE VALORES E REDIRECIONA PARA
			$fields = $values = $form->getValue();

			$fields['taxa_matricula'] 		= str_replace(",", ".", str_replace(".", "", $fields['taxa_matricula']));
			$fields['vencimento_1_parcela'] = date_create_from_format("d/m/Y H:i:s", $fields['vencimento_1_parcela'] . "  00:00:00");

			$negociationParams = array(
				'full_price'			=> $userNegociation['base_price'],
				'paid'					=> $userNegociation['paid'],
				//'balance'				=> $userNegociation['full_price'] - $userNegociation['paid'],
				'taxa_matricula'		=> $fields['taxa_matricula'],
				'total_parcelas'		=> $fields['total_parcelas'],
				'vencimento_1_parcela'	=> $fields['vencimento_1_parcela'],
				'dia_vencimento'		=> $fields['dia_vencimento']
			);

			// CRIAR SIMULAÇÃO DE PARCELAMENTO
			$currentBalance = $negociationParams['full_price'];
			$invoices = array();
			$invoice_index = 1;
			$default_vencimento = 5;

			$fakeNegociationID = $userNegociation['id'];

			$nextInvoiceIndex = 0;

			// 1. CREATE INVOICE FOR ALREADY "paid" VALUES, AND LOCK
			if ($negociationParams['paid'] > 0) {

				$toLockInvoices = $toLockInvoiceIndexes = array();
				// LOCK ALL USER ALREADY PAID INVOICES
				foreach ($userNegociation['invoices'] as $invoice) {
					if ($invoice['paid'] > 0) {
						// LOCK
						$invoice['locked'] = 1;
						$invoice['sugested'] = 0;
						$invoices[] = $toLockInvoices[] = $invoice;
						$toLockInvoiceIndexes[] = $invoice['invoice_index'];

					}
				}

				/*
				$paidInvoice['locked'] 		= 1;
				$paidInvoice['description'] = __XPAY_PAID_INVOICE_DESCRIPTION;
				$paidInvoice['paid']		= $negociationParams['paid'];

				$invoices[] = $paidInvoice;
				*/
				// 2. REMOVE PAID VALUE FROM FULL PRICE
				$currentBalance -= $negociationParams['paid'];
			}

			$today = new DateTime("today");
			$monthInterval = new DateInterval('P1M');

			// 3. CREATE INVOICE FOR "taxa_matricula"
			if ($negociationParams['taxa_matricula'] > 0) {

				if (!$fields['vencimento_1_parcela']) {
					$fields['vencimento_1_parcela'] = new DateTime('today');
					$fields['vencimento_1_parcela']->add(new DateInterval('P10D'));
				}
				$firstDueDate = $fields['vencimento_1_parcela'];

				while (in_array($nextInvoiceIndex, $toLockInvoiceIndexes)) {
					$nextInvoiceIndex++;
				}


				$registrantInvoice = $this->_createInvoice(
					$fakeNegociationID, $negociationParams['taxa_matricula'], $firstDueDate, null, $nextInvoiceIndex++, false, $negociationUser, true
				);
				$registrantInvoice['description'] = __XPAY_REGISTRANT_INVOICE;
				$invoices[] = $registrantInvoice;

				// 4. REMOVE "taxa_matricula" FROM FULL PRICE
				$currentBalance -= $negociationParams['taxa_matricula'];
			} else {
				if (!$fields['vencimento_1_parcela']) {
					$firstDueDate = $fields['vencimento_1_parcela'] = new DateTime('today');
				} else {
					$firstDueDate = $fields['vencimento_1_parcela'];
				}
			}


			$nextDueDate  = new DateTime();
			$nextDueDate->setDate(
				$firstDueDate->format('Y'),
				$firstDueDate->format('m'),
				$negociationParams['dia_vencimento']
			);

			$firstNextDiff = $firstDueDate->diff($nextDueDate, false)->format("%r%d");

			if (($negociationParams['taxa_matricula'] > 0 && $firstNextDiff < 15) || $firstNextDiff < 0) {
				$nextDueDate->add($monthInterval);
			}

			// 5. DIVIDE BALANCE BY "total_parcelas"
			$valorParcela = xfloor($currentBalance / $negociationParams['total_parcelas'], 2);

			// 6. APPEND THE INVOICES VALUES
			$invoicesValues = array();
			for ($i = 1; $i <= $negociationParams['total_parcelas']; $i++) {
				$invoicesValues[$i] = $valorParcela;
			}




			// 7. CALCULATE THE REST
			$restBalance = round($currentBalance - ($valorParcela * $negociationParams['total_parcelas']), 2);

			// 8. APPEND THE REST, BY 0.01 STEP ON INVOICES
			for ($i = $negociationParams['total_parcelas']; $i >= 1; $i--) {
				if ($restBalance > 0) {
					$invoicesValues[$i]	+= 0.01;
					$restBalance 		-= 0.01;
				} else {
					break;
				}
			}



			// 9. CREATE INVOICES
			for ($i = 1; $i <= $negociationParams['total_parcelas']; $i++) {
				while (in_array($nextInvoiceIndex, $toLockInvoiceIndexes)) {
					$nextInvoiceIndex++;
				}

				$parcelationInvoice = $this->_createInvoice(
					$fakeNegociationID, $invoicesValues[$i], $nextDueDate, null, $nextInvoiceIndex++, false, $negociationUser
				);


				$parcelationInvoice['description'] = sprintf(__XPAY_NUMBERED_INVOICE, $i);
				$invoices[] = $parcelationInvoice;

				$nextDueDate->add($monthInterval);
			}

			$suggestedInvoices = array();

			foreach ($invoices as $invoice_index => $invoice) {
				if (!in_array($invoice['invoice_index'], $toLockInvoiceIndexes)) {
					$invoice['sugested'] = true;
				}
				$suggestedInvoices[] = $invoice;
			}
			///$userNegociation['sugested_invoices'] = $suggestedInvoices;
			$userNegociation['invoices'] = $suggestedInvoices;

			/*
			$negociationTotals = array(
				'invoices_count'	=> count($userNegociation['invoices']),
				'valor'				=> 0,
				'total_reajuste'	=> 0,
				'paid'				=> 0
			);

			foreach ($userNegociation['sugested_invoices'] as $invoice) {
				$negociationTotals['valor']				+= $invoice['valor'];
				$negociationTotals['total_reajuste']	+= $invoice['total_reajuste'];
				$negociationTotals['paid']				+= $invoice['paid'];
			}
			$negociationTotals['balance'] = intval($negociationTotals['valor'])+intval($negociationTotals['total_reajuste'])-intval($negociationTotals['paid']);

			$smarty -> assign("T_XPAY_STATEMENT_TOTALS", $negociationTotals);
			*/
			$smarty -> assign("T_XPAY_NEGOCIATION_IS_SUGESTED", true);

			// SAVE ON SESSION SERIALIZED NEGOCIATION BY HASH
			$negociationHash = $this->createToken(10);
			$this->setCache($negociationHash, json_encode($userNegociation));
			$smarty -> assign("T_XPAY_NEGOCIATION_HASH", $negociationHash);
			$this->addModuleData("negociation_hash", $negociationHash);

			//$values['saldo_total'] = $this->_asCurrency($userNegociation['full_price'] - $userNegociation['paid']);
			$values = array(
				'saldo_total'			=> $this->_asCurrency($userNegociation['base_price'] - $userNegociation['paid']),
				'total_parcelas'		=> $fields['total_parcelas'],
				'dia_vencimento'		=> $fields['dia_vencimento'],
				'vencimento_1_parcela'	=> $firstDueDate->format("d/m/Y"),
				'taxa_matricula'		=> $fields['taxa_matricula']
			);
		} else {
			$values = array(
					'saldo_total'		=> $this->_asCurrency($userNegociation['base_price'] - $userNegociation['paid']),
					//'saldo_restante'	=> $this->_asCurrency($userNegociation['full_price'] - $userNegociation['paid']),
					'total_parcelas'	=> 9
			);
		}

		foreach ($userNegociation['invoices'] as $invoice_index => $invoice) {
			$userNegociation['invoices'][$invoice_index]['applied_rules'] = $this->_getAppliedRules($invoice);
		}

		$smarty -> assign("T_XPAY_STATEMENT", $userNegociation);

		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));
		// Set defaults for the form elements
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_XPAY_INVOICE_PARAMS_FORM', $renderer -> toArray());
		return true;
	}
	public function updateNegociationAction()
	{
		$smarty = $this->getSmartyVar();
		
		if ($this->getCurrentUser()->getType() != 'administrator') {
			$result = array(
					"message"		=> "Acesso Não Autorizado",
					"message_type"	=> "failure"
			);
			echo json_encode($result);
			exit;
		}
		if (
				is_numeric($_POST['negociation_id']) &&
				eF_checkParameter($_POST['negociation_id'], "id")
		) {
			/// VERIFICAR SE O USUÀRIO TEM ACESSO. PELA "ies_id"
			$userNegociation = $this->_getNegociationByID($_POST['negociation_id']);
		
			$negociationUser = $this->getEditedUser(true, $userNegociation['user_id']);
		} else {
			$result = array(
				"message"		=> "Ocorreu um erro ao tentar acessar a sua negociação. Por favor entre em contato com o suporte",
				"message_type"	=> "failure"
			);
			echo json_encode($result);
			exit;
		}
		
		$fields = array(
			'send_to'
		);
		
		$updateFields = array();
		
		foreach ($fields as $field) {
			if (array_key_exists($field, $_POST) && !empty($_POST[$field])) {
				$updateFields[$field] = $_POST[$field];
			}
		}
		if (count($updateFields) == 0) {
			$result = array(
				"message"		=> "Por favor informe os campos que você deseja atualizar.",
				"message_type"	=> "warning"
			);
			echo json_encode($result);
			exit;
		} else {
			eF_updateTableData("module_xpay_course_negociation", $updateFields, sprintf('id = %d', $userNegociation['id']));
			
			$result = array(
				"message"		=> "Negociação atualizada com sucesso.",
				"message_type"	=> "success"
			);
			echo json_encode($result);
			exit;
		}
	}
	public function saveInvoicesAction()
	{
		$smarty = $this->getSmartyVar();

		$hashNegociation = $_POST['negociation_id'];
		$jsonNegociation = $this->getCache($hashNegociation);
		$userNegociation = json_decode($jsonNegociation, true, 50);

		$xuserModule = $this->loadModule("xuser");
		$negUser = $xuserModule->getUserById($userNegociation['user_id']);

		// SAVE WHERE? PERSIST OR NOT PERSIST??
		// THE "course_negociation rec" IS ALREADY BEEN SAVED. SAVE ONLY INVOICES

		//$this->saveSimulatedNegociation($userNegociation);
		// UPDATE SIMULATION STATUS
		/*
		eF_updateTableData("module_xpay_course_negociation", array("is_simulation" => 2), sprintf("user_id = %d AND course_id = %d AND is_simulation = 0", $userNegociation['user_id'], $userNegociation['course_id']));
		eF_updateTableData("module_xpay_course_negociation", array("is_simulation" => 0), sprintf("id = %d", $userNegociation['id']));
		*/
		// SAVE INVOICES
		$newInvoicesIndexes = array();
		foreach ($userNegociation['invoices'] as $invoice) {
			$vencimento = date_create_from_format("Y-m-d", $invoice['data_vencimento']);

			if ($invoice['locked'] == 1 || $invoice['paid'] > 0) {
				$newInvoicesIndexes[] = $invoice['invoice_index'];
			} elseif ($invoice['sugested'] == 1) {
				// DELETE OLD ONE
				eF_deleteTableData(
					"module_xpay_invoices",
					sprintf(
						"negociation_id = %d AND invoice_index =%d",
						$userNegociation['id'],
						$invoice['invoice_index']
					)
				);

				$newInvoicesIndexes[] = $invoice['invoice_index'];

				$this->_createInvoice(
					$userNegociation['id'],
					$invoice['valor'],
					$vencimento,
					null,
					$invoice['invoice_index'],
					true,
					$negUser,
					$invoice['is_registration_tax']
				);
			}

			eF_updateTableData(
				"module_xpay_invoices",
				array(
					'description' 	=> $invoice['description'],
					'locked' 		=> $invoice['locked']
				),
				sprintf("negociation_id = %d AND invoice_index =%d",
					$userNegociation['id'],
					$invoice['invoice_index']
				)
			);
		}
		eF_deleteTableData(
			"module_xpay_invoices",
			sprintf(
				"negociation_id = %d AND invoice_index NOT IN (%s)",
				$userNegociation['id'],
				implode(",", $newInvoicesIndexes)
			)
		);


		if ($_GET['output'] == 'json') {
			$response = array(
					"message"		=> __XPAY_SIMULATE_NEGOCIATION_SAVED,
					"message_type"	=> "success",
					"data"			=> $userNegociation
			);
			echo json_encode($response);
			exit;
		}

	}
	public function doPaymentAction()
	{
		$smarty = $this->getSmartyVar();
		$currentUser = $this->getCurrentUser();
		// GET PAYMENT ID, AND INVOICE ID
		/*
		 * Montar um formulário para selecionar o pagamento e parcela
		 * Caso somente um registro de pagamento, pré-selecionar
		 * Caso o regime de pagamento não permita realizar pagamentos de parcelas fora de ordem, pré selecionar
		 */
		$negociationID = $_GET['negociation_id'];
		$invoice_index = $_GET['invoice_index'];

		$xUserModule = $this->loadModule("xuser");

		$exUserType = $xUserModule->getExtendedTypeID($currentUser);

		if ($exUserType == 'student' || $exUserType == 'pre_student') {
			// CHECK IF NEGOCIATION ID IS FROM USER
			$negocData = $this->_getNegociationByContraints(
				array(
					'negociation_id'	=> $negociationID,
					'login'				=> $this->getCurrentUser()->user['login']
				)
			);
		} elseif (
			$exUserType == 'administrator' ||
			$exUserType == 'financier' ||
			$exUserType == 'coordenator'
) {
			$negociationID = $_GET['negociation_id'];
			$invoice_index = $_GET['invoice_index'];

			$negocData = $this->_getNegociationById($negociationID);

			$smarty -> assign("T_XPAY_IS_ADMIN", true);
		} else {
			$this->setMessageVar(__XPAY_NO_ACCESS, "failure");
			$selectedIndex = null;
			return;
		}

		if (count($negocData) == 0) {
			$this->setMessageVar(__XPAY_NONEGOCIATION_FOUND, "failure");
			$selectedIndex = null;
			return;
		}
		$negocTotals = array(
			'valor'				=> 0,
			'total_reajuste'	=> 0,
			'paid'				=> 0
		);
		foreach ($negocData['invoices'] as $invoiceIndex => $invoice) {
			if ($invoice['full_price'] <= $invoice['paid']) {
				//					unset($negocData['invoices'][$invoiceIndex]);
				//					continue;
			}
			$negocTotals['valor']			+= $invoice['valor'];
			$negocTotals['total_reajuste']	+= $invoice['total_reajuste'];
			$negocTotals['paid']			+= $invoice['paid'];
		}

		foreach ($negocData['invoices'] as $inv_index => $invoice) {
			$applied_rules = array();
			if ($invoice['total_reajuste'] <> 0) {
				foreach ($invoice['workflow'] as $workflow) {
					if (!array_key_exists($workflow['rule_id'], $applied_rules)) {
						foreach ($invoice['rules'] as $rule) {
							if ($rule['id'] == $workflow['rule_id']) {
								$currentRule = $rule;
								break;
							}
						}
						$applied_rules[$workflow['rule_id']] = array(
								'description'		=> $currentRule['description'],
								'input'				=> $workflow['input'],
								'diff'				=> $workflow['diff'],
								'repeat_acronym'	=> $currentRule['applied_on'] == 'per_day' ? __XPAY_DAYS : '',
								'count'				=> 0
						);
					}
					$applied_rules[$workflow['rule_id']]['output'] =  $workflow['output'];
					$applied_rules[$workflow['rule_id']]['count']++;
				}
			}
			$negocData['invoices'][$inv_index]['applied_rules'] = $this->_getAppliedRules($invoice);
		}


		$smarty -> assign("T_XPAY_STATEMENT", $negocData);
		$smarty -> assign("T_XPAY_STATEMENT_TOTALS", $negocTotals);

		// GET SUB MODULES FUNCTIONS
		$currentOptions = $this->getSubmodules();


		$selectedIndexes = array();
		if (count($currentOptions) > 1) {
			// MAKE FORM AND USE IT TO SELECT INDEX
			$selectedIndexes = array_keys($currentOptions);
		} elseif (count($currentOptions) == 1) {
			// ONLY ONE OPTION, SELECT AUTOMAGICALY
			$selectedIndexes[] = key($currentOptions);
		} else {
			$this->setMessageVar(__XPAY_NOPAYMENT_TYPES_DEFINED, "warning");

			$selectedIndex = null;
			return;
		}

		$paymentMethods = array();

		$form = new HTML_QuickForm("xpay_select_payment_method", "post", $_SERVER['REQUEST_URI'], "", null, true);
		$form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');

		foreach ($negocData['invoices'] as $inv_index => $invoice) {
			if ($invoice['full_price'] <= $invoice['paid']) {
				//unset($negocData['invoices'][$inv_index]);
				//continue;
			}
			$form -> addElement('radio', 'invoice_indexes', $invoice['invoice_index'], $img, $invoice['invoice_index'], 'class="xpay_methods"');
		}
		$form->setDefaults(
			array('invoice_indexes'	=> $invoice_index)
		);

		foreach ($selectedIndexes as $selectedKey => $selectedIndex) {
			$selectedPaymentMethod = $currentOptions[$selectedIndex];

			if (!$selectedPaymentMethod->paymentCanBeDone($payment_id, $invoice_id)) {
				unset($selectedIndexes[$selectedKey]);

				//	$this->setMessageVar(__XPAY_NOPAYMENT_CANT_BE_DONE, "warning");
				//	return;
				continue;
			}

			$paymentMethods[strtolower($selectedIndex)] = $selectedPaymentMethod->getPaymentInstances();

			$xentifyModule = $this->loadModule("xentify");

			$negociationUser = MagesterUserFactory::factory($negocData['login']);

			$pay_method_active_options = array();



			$scopeUser = $xentifyModule->create("user", $negocData['login']);
			$firstPayMethodOption = null;

			//$this->_log($paymentMethods['xpay_boleto']['options']);

			foreach ($paymentMethods[strtolower($selectedIndex)]['options'] as $key => $item) {
				if ($item['active'] === FALSE) {
					continue;
				}
				/*
				if (!$xentifyModule->isUserInScope($negociationUser, $item['xscope_id'], $item['xentify_id'])) {
					continue;
				}
				*/
				if (
					!$scopeUser->inScope($item['xscope_id'], $item['xentify_id'])
				) {
					continue;
				}
				$breakOuterLoop = false;
				foreach ($negocData['modules'] as $module) {
					$scopeModule = $xentifyModule->create($module['module_type'], $module['module_id']);

					if (!$scopeModule->inScope($item['xscope_id'], $item['xentify_id'])) {
						$breakOuterLoop = true;
						break;
					}
				}
				if ($breakOuterLoop) {
					continue;
				}

				// CHECAR SE O PAGAMENTO OU CURSO DO PAGAMENTO ESTÁ NO ESCOPO
				$pay_method_active_options[] = array(
					'pay_method' 		=> strtolower($selectedIndex),
					'pay_method_option'	=> $key
				);

				$img = sprintf(
					'<img src="%simages/%s.png" />',
					$paymentMethods[strtolower($selectedIndex)]['baselink'],
					empty($item['image_name']) ? $key : $item['image_name']
				);
				if (is_null($firstPayMethodOption)) {
					$firstPayMethodOption = strtolower($selectedIndex) . ":" . $key;
				}

				$form -> addElement('radio', 'pay_methods', $item['name'], $img, strtolower($selectedIndex) . ":" . $key, 'class="xpay_methods"');
			}
                        $form->setDefaults(array(
				'pay_methods'       => $firstPayMethodOption
                        ));

		}
		$smarty -> assign("T_XPAY_METHODS", $paymentMethods);

		if (count($pay_method_active_options) == 1 && $_GET['do_direct'] == 1) {
			$pay_method_active_opt = reset($pay_method_active_options);
			$_SESSION['pay_method'] 		= $pay_method_active_opt['pay_method'];
			$_SESSION['pay_method_option']	= $pay_method_active_opt['pay_method_option'];
		}

		if (
			($form -> isSubmitted() && $form -> validate()) ||
			(array_key_exists('pay_method', $_SESSION) && array_key_exists('pay_method_option', $_SESSION) && $_GET['do_direct'] == 1)
		) {
			$values = $form->exportValues();

	 		if ($form -> isSubmitted() && $form -> validate()) {
				list($pay_method, $pay_method_option) = explode(":", $values['pay_methods']);

				$_SESSION['pay_method']			= $pay_method;
				$_SESSION['pay_method_option']	= $pay_method_option;

				$invoice_index = $values['invoice_indexes'];
				$form->setDefaults($form->exportValues());
				
				if (is_numeric($values['invoice_indexes'])) {
					$invoice_index = $values['invoice_indexes'];
				}
			} else {
				list($pay_method, $pay_method_option) = array(
					$_SESSION['pay_method'],
					$_SESSION['pay_method_option']
				);
				/*
				$form->setDefaults(array(
					'pay_methods'	=>  $pay_method . ":" . $pay_method_option
				));
				*/
			}

			if (array_key_exists(strtoupper($pay_method), $currentOptions)) {
				$selectedPaymentMethod = $currentOptions[strtoupper($pay_method)];
				$selectedPaymentMethod->initPaymentProccess(
					$negociationID,
					$invoice_index,
					array(
						'option' 			=> $pay_method_option,
						"instance_option"	=> $_POST['instance_option']
					)
				);
			} else {
				unset($_SESSION['pay_method']);
				unset($_SESSION['pay_method_option']);
			}
		}

		if ($negocData['invoices'][$invoice_index]['full_price'] <= $negocData['invoices'][$invoice_index]['paid']) {
			$smarty->assign("T_XPAY_INVOICE_IS_PAID", true);
		} else {
			$smarty->assign("T_XPAY_INVOICE_IS_PAID", false);
		}

		$form -> addRule('pay_methods', _THEFIELD.' "'.__XPAY_PAYMENT_METHOD.'" '._ISMANDATORY, 'required', null, 'client');
		$form -> addElement('submit', 'xpay_submit', __XPAY_NEXT, 'class = ""');

		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
		$smarty -> assign('T_XPAY_METHOD_FORM', $renderer -> toArray());

		if ($_GET['output'] == 'dialog') {
			// JUST FETCH THE TEMPLATE AND EXIT;
			echo $smarty -> fetch($this->moduleBaseDir . "/templates/includes/do_payment.dialog.tpl");
			exit;
		}
		
		
		//$render = $renderer -> toArray();
		return true;
	}
	public function viewInstanceOptionsAction()
	{
		list($module_index, $module_option) = explode(":", $_POST['instance_index']);
		/*
		$module_index = "xpay_cielo";
		$module_option = "visa";
		*/
		// GET SUB MODULES FUNCTIONS
		$currentModules = $this->getSubmodules();
		
		if (array_key_exists(strtoupper($module_index), $currentModules)) {
			$subModuleTemplate = $currentModules[strtoupper($module_index)]->fetchPaymentInstanceOptionsTemplate($module_option);

		}
		if ($subModuleTemplate === false) {
			exit;
		}
		
		
		echo $subModuleTemplate;
		exit;
	}
	public function createPaymentAction()
	{
		$smarty = $this->getSmartyVar();
		/// GET =
		/*
		 * negociation_id	228
		 * invoice_index	3
		*/

		$values = array(
			'negociation_id' 	=> $_GET['negociation_id'],
			'invoice_index[]'	=> !is_array($_GET['invoice_index']) ? array($_GET['invoice_index']) : $_GET['invoice_index'], 	// CAN BE A ARRAY
			'method'			=> 'manual'
		);

		$nego_id		= $values['negociation_id'];
		$invoices = array();
		$subtract_value = $real_value = 0;
		foreach ($values['invoice_index[]'] as $invoice_index) {
			$invoice = $invoices[] = $this->_getNegociationInvoiceByIndex($nego_id, $invoice_index);

			$subtract_value += $invoice['valor'];
			$real_value += $invoice['full_price'];
		}
		//exit;

		// CRIAR FORMULÁRIO DE CAIXA DE DIALOGOS
		$form = new HTML_QuickForm2("xpay_create_payment_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);
		$form -> addHidden('negociation_id')->setValue($values['negociation_id']);

		foreach ($values['invoice_index[]'] as $indexes) {
			$form -> addHidden('invoice_index[]')->setValue($indexes);
		}
		$form -> addHidden('method')->setValue($values['method']);

		$form
			-> addText('real_value', array('class' => 'small', 'alt' => 'decimal'), array('label'	=> __XPAY_REAL_PAID_VALUE))
			->setValue(number_format($real_value, 2, ",", ""));
		$form
			-> addText('subtract_value', array('class' => 'small', 'alt' => 'decimal'), array('label'	=> __XPAY_TO_SUBTRACT_VALUE))
			->setValue(number_format($subtract_value, 2, ",", ""));

		$form -> addTextarea('description', array('class' => 'large'), array('label'	=> __XPAY_JUSTIFICATION));
		$form -> addSubmit('_create_payment', null, array('label'	=> __XPAY_SUBMIT));
		if ($form -> isSubmitted() && $form -> validate()) {
			// VALIDATE

			$values = $form->getValue();

			$values['real_value'] = (float) str_replace(",", ".", $values['real_value']);
			$values['subtract_value'] = (float) str_replace(",", ".", $values['subtract_value']);
			// SAVE manual transaction
			$firstPaidInvoice = reset($invoices);

			$manualTransaction = array(
				'instance_id' 			=> '',
				//'data_registro' 		=> $oldInvoice['data_vencimento'],
				'description'			=> $values['description']
			);
			$transactionID = ef_insertTableData(
				"module_xpay_manual_transactions",
				$manualTransaction
			);

			$paidItem = array(
				'transaction_id'	=> $transactionID,
				'method_id' 		=> $values['method'],
				'paid' 				=> $values['real_value'],
				'start_timestamp' 	=> time()
			);
			$paidID = ef_insertTableData(
				"module_xpay_paid_items",
				$paidItem
			);

			$invoce_index = reset($values['invoice_index']);
			$invoiceToPaid = array(
				'negociation_id'	=> $values['negociation_id'],
				'invoice_index'		=> $invoce_index,
				'paid_id'			=> $paidID
			);

			if ($values['real_value'] != $values['subtract_value']) {
				$invoiceToPaid['full_value'] = $values['subtract_value'];
			}

			ef_insertTableData(
				"module_xpay_invoices_to_paid",
				$invoiceToPaid
			);

			// with is pop-up, close and send message to parent. if not, then show message.
			if ($_GET['popup'] == 1) {
				$this->setMessageVar(__XPAY_PAYMENT_SUCCESSFULLY_CREATED, "success");
				$smarty->assign(
					"T_XPAY_MESSAGE",
					json_encode(
						array(
							'message'		=> __XPAY_PAYMENT_SUCCESSFULLY_CREATED,
							'message_type'	=> "success"
						)
					)
				);
				$smarty -> assign("T_XPAY_IS_DONE", true);
			} else {
				$this->setMessageVar(__XPAY_PAYMENT_SUCCESSFULLY_CREATED, "success");
			}
		}
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));
		// Set defaults for the form elements
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_XPAY_CREATE_PAYMENT_FORM', $renderer -> toArray());
	}
	public function editInvoiceAction()
	{
		$smarty = $this->getSmartyVar();

		$getValues = array(
			'negociation_id'	=> $_GET['negociation_id'],
			'invoice_index'		=> $_GET['invoice_index']
		);

		$invoice = $this->_getNegociationInvoiceByIndex($getValues['negociation_id'], $getValues['invoice_index']);

		//exit;

		// CRIAR FORMULÁRIO DE CAIXA DE DIALOGOS
		$form = new HTML_QuickForm2("xpay_edit_invoice_form", "post", array("action" => $_SERVER['REQUEST_URI']), true);
		$form -> addHidden('negociation_id')->setValue($getValues['negociation_id']);
		$form -> addHidden('invoice_index')->setValue($getValues['invoice_index']);

		$form
			-> addText('valor', array('class' => 'small', 'alt' => 'decimal'), array('label'	=> __XPAY_VALUE));
			//->setValue(number_format($invoice['valor'], 2, ",", ""));

		$form
			-> addText('data_vencimento', array('class' => 'small no-button' , 'alt' => 'date'), array('label'	=> __XPAY_DUE_DATE));
			//->setValue(date("d/m/Y", strtotime($invoice['data_vencimento'])));

		//$form -> addTextarea('description', array('class' => 'large'), array('label'	=> __XPAY_JUSTIFICATION));
		$form -> addSubmit('_edit_invoice', null, array('label'	=> __XPAY_SUBMIT));

		if ($form -> isSubmitted() && $form -> validate()) {
			// VALIDATE
			$values = $form->getValue();

			
			$values['valor'] = (float) str_replace(",", ".", str_replace(".", "", $values['valor']));
			$data_vencimento = date_create_from_format("d/m/Y", $values['data_vencimento']);

			if ($data_vencimento) {
				$values['data_vencimento'] = $data_vencimento->format("Y-m-d");
			}
			if (
				$getValues['negociation_id'] == $values['negociation_id'] &&
				$getValues['invoice_index'] == $values['invoice_index']
) {
				ef_updateTableData(
					"module_xpay_invoices",
					array(
						'valor'				=> $values['valor'],
						'data_vencimento'	=> $values['data_vencimento']
					),
					sprintf("negociation_id = %d AND invoice_index = %d", $values['negociation_id'], $values['invoice_index'])
				);

				$message = __XPAY_INVOICE_SUCCESSFULLY_SAVED;
				$message_type = "success";
			} else {
				$message = __XPAY_INVOICE_SAVE_ERROR;
				$message_type = "failure";
			}

			// with is pop-up, close and send message to parent. if not, then show message.
			$this->setMessageVar($message, $message_type);
			if ($_GET['popup'] == 1) {
				$smarty->assign("T_XPAY_MESSAGE", json_encode(array(
					'message'		=> $message,
					'message_type'	=> $message_type
				)));
				$smarty -> assign("T_XPAY_IS_DONE", true);
			}
		} else {
			$values['valor']			= number_format($invoice['valor'], 2, ",", "");
			$values['data_vencimento']	= date("d/m/Y", strtotime($invoice['data_vencimento']));
		}
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($values));
		// Set defaults for the form elements
		$renderer = HTML_QuickForm2_Renderer::factory('ArraySmarty');
		//$renderer = new HTML_QuickForm2_Renderer_ArraySmarty($smarty);
		$form -> render($renderer);
		$smarty -> assign('T_XPAY_EDIT_INVOICE_FORM', $renderer -> toArray());
	}
	public function viewUsersInDebtsAction()
	{
		$smarty = $this->getSmartyVar();

		$debtsLists = $this->_getUserInDebts();

		foreach ($debtsLists as &$debt) {
			$debt['username'] = formatLogin(null, $debt);
		}
		$smarty->assign("T_XPAY_LIST", $debtsLists);
	}
	public function viewUnpaidInvoicesAction()
	{
		$smarty = $this->getSmartyVar();

		$debtsLists = $this->_getInvoicesInDebts();

		foreach ($debtsLists as &$debt) {
			$debt['username'] = formatLogin(null, $debt);
		}
		$smarty->assign("T_XPAY_LIST", $debtsLists);
	}
	public function viewToSendInvoicesListAction()
	{
		$smarty = $this->getSmartyVar();
		$tables = array(
			"module_xpay_invoices inv",
			"JOIN module_xpay_course_negociation neg ON (inv.negociation_id = neg.id)",
			"LEFT OUTER JOIN module_xpay_invoices_to_paid inv2paid ON (
				inv.negociation_id = inv2paid.negociation_id AND
				inv.invoice_index = inv2paid.invoice_index
			)",
			"LEFT OUTER JOIN module_xpay_paid_items pd ON (
				inv2paid.paid_id = pd.id
			)",
			"JOIN users u ON (neg.user_id = u.id)",
			"JOIN courses c ON (neg.course_id = c.id)",
			sprintf("LEFT OUTER JOIN module_xpay_to_send_list 2send ON (
				2send.user_id = %s
			)", $this->getCUrrentUser()->user['id']),
			"LEFT OUTER JOIN module_xpay_to_send_list_item 2send_item ON (
				2send_item.send_id = 2send.id AND
				inv.negociation_id = 2send_item.negociation_id AND
				inv.invoice_index = 2send_item.invoice_index
			)",
			"LEFT OUTER JOIN module_xpay_sent_invoices_log sent_log ON (
				inv.negociation_id = sent_log.negociation_id AND
				inv.invoice_index = sent_log.invoice_index
			)"

			//"JOIN module_pagamento_invoices_status stat ON (inv.status_id = stat.id)",
			//"LEFT OUTER JOIN module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id AND pag2ies.ies_id = c.ies_id)",
			//"LEFT OUTER JOIN module_ies ies ON (pag2ies.ies_id = ies.id)"
		);
		$fields = array(
			"neg.user_id",
			"inv.negociation_id",
			"neg.course_id",
			"inv.invoice_index",
			"inv.invoice_id",
			"inv.invoice_sha_access",
			"inv.valor",
			"inv.data_registro",
			"inv.data_vencimento",
			"COUNT(pd.id) as trans_count",
			"IFNULL(SUM(pd.paid), 0) as paid",
			"MIN(pd.start_timestamp) as start_min",
			"MAX(pd.start_timestamp) as start_max",
			"u.name",
			"u.surname",
			"u.login",
			"(SELECT COUNT(invoice_index) FROM module_xpay_invoices WHERE negociation_id = neg.id) as invoice_count",
			"count(2send_item.invoice_index) as sending",
			"count(sent_log.id) as sent_count",
			"c.name as course"
		);

		//$where = $this->makeInvoicesListFilters(null, "inv.parcela_index");
		$today = new DateTime("today");
		$a15DaysInterval = new DateInterval("P15D");
		$a25DaysInterval = new DateInterval("P25D");

		$iesIds = $this->getCurrentUserIesIDs();

		$iesIds[] = 0;
		//$where[] = "inv.status_id NOT IN (3,4,5)";
		//$where[] = "inv.pago = 0";
		$where[] = "inv.locked = 0";
		$where[] = sprintf("c.ies_id IN (%s)", implode(',', $iesIds));
		$where[] = sprintf("inv.data_vencimento BETWEEN '%s' AND '%s'", $today->sub($a15DaysInterval)->format("Y-m-d"), $today->add($a15DaysInterval)->add($a25DaysInterval)->format("Y-m-d"));
		$where[] = "u.active = 1";
		$where[] = "neg.is_simulation = 0"; /// ONLY CURRENT-ACTIVE NEGOCIATIONS

		//$where[] = sprintf("inv.payment_type_id IN (SELECT payment_type_id FROM module_xpayment_types_to_xies WHERE ies_id IN (%s))", implode(',', $iesIds));

		$order = array(
			"inv.data_vencimento ASC"
		);

		$group = array(
			"inv.negociation_id",
			"inv.invoice_index"
		);

		// MAKE FILTERS
	/*
		echo prepareGetTableData(
				implode(" ", $tables),
				implode(", ", $fields),
				implode(" AND ", $where),
				implode(", ", $order),
				implode(", ", $group)
		);
	*/
		$toSendList = eF_getTableData(
				implode(" ", $tables),
				implode(", ", $fields),
				implode(" AND ", $where),
				implode(", ", $order),
				implode(", ", $group)
		);

		$invoicesList = array();

		$negociationUsers = array();

		foreach ($toSendList as $invoice) {

			if (!array_key_exists($invoice['login'], $negociationUsers)) {
				$negociationUsers[$invoice['login']] = MagesterUserFactory::factory($invoice['login']);
			}

			$invoice = $this->_calculateInvoiceDetails($invoice, $negociationUsers[$invoice['login']]);
			if ($invoice['paid'] >= $invoice['full_price']) {
				continue;
			}
			$invoice['username'] = formatLogin(null, $invoice);
			$invoicesList[] = $invoice;
		}
		$smarty->assign("T_XPAY_LIST", $invoicesList);

        $smarty->assign("T_VIEW_TO_SEND_INVOICES_LIST_OPTIONS", array(
            array(
                'href'          => "javascript: xPayMailAllInvoicesAdviseAction();",
                'image'         => "16x16/mail.gif"
            )
        ));

	}
	public function viewPaymentReceiptAction() {
		$smarty = $this->getSmartyVar();
		/*
		if ($this->getCurrentUser()->getType() == 'professor') {
			$this->setMessageVar("Acesso Não Autorizado", "failure");
			return false;
		}
		if ($this->getCurrentUser()->getType() == 'student') {
			$this->setMessageVar("Acesso Não Autorizado", "failure");
			return false;
		}
		*/
		$negociation_id = is_numeric($_GET['negociation_id']) && eF_checkParameter($_GET['negociation_id'], "id") ? $_GET['negociation_id'] : null;
		$invoice_index = is_numeric($_GET['invoice_index']) ? $_GET['invoice_index'] : null;
		
		if ($this->getCurrentUser()->getType() == 'administrator') {
			$smarty -> assign("T_XPAY_IS_ADMIN", true);
		} else {
			// CHECK IF CURRENT USER IS THE SAME NEGOCIATION USER 
			//$negioc
			return false;
		}
		if (!is_null($negociation_id) && !is_null($invoice_index)) {
			/// VERIFICAR SE O USUÀRIO TEM ACESSO. PELA "ies_id"
			$userInvoice = $this->_getNegociationInvoiceByIndex($negociation_id, $invoice_index);
		

		} else {
			$this->setMessageVar("Ocorreu um erro ao tentar acessar a sua negociação. Por favor entre em contato com o suporte", "failure");
			return false;
		}
		
		/*
		fetchPaymentReceiptTemplate
		
		
		list($module_index, $module_option) = explode(":", $_POST['instance_index']);

		// GET SUB MODULES FUNCTIONS
		
		*/
		$currentModules = $this->getSubmodules();
		
		$module_index = strtoupper("xpay_" . $userInvoice['method_id']);
		
		
		if (array_key_exists(strtoupper($module_index), $currentModules)) {
			$subModuleTemplate = $currentModules[strtoupper($module_index)]->fetchPaymentReceiptTemplate($negociation_id, $invoice_index);
		}
		if ($subModuleTemplate === false) {
			exit;
		}
		
		echo $subModuleTemplate;
		exit;
	}
	public function mailInvoicesAdviseAction()
	{
		$smarty = $this->getSmartyVar();

		$sendAll = null;
		!empty($_POST['negociation_id']) ? $negociation_id = $_POST['negociation_id'] : null;
		!empty($_POST['invoice_index']) ? $invoice_index = $_POST['invoice_index'] : null;
		$_GET['send_all'] == "true" || $_POST['send_all'] == "true" ? $sendAll = true : null;

		$status = array();

		if ($sendAll === true) {
			$sendList = $this->_getSendInvoicesList();

			foreach ($sendList as $sendItem) {
				$status[] = $this->_sendInvoiceAdvise($sendItem['negociation_id'], $sendItem['invoice_index'], $sendItem['send_id']);
			}

		} else {
			$status[] = $this->_sendInvoiceAdvise($negociation_id, $invoice_index);
		}

		if ($_GET['output'] == 'json') {
			$message		= "Mensagem Enviada com sucesso";
			$message_type 	= "success";

			echo json_encode(array(
				'message'		=> $message,
				'message_type'	=> $message_type,
				'sent'			=> $status
			));
			exit;
		} else {
			$smarty -> assign("T_XPAY_SENT_STATUS", $status);
		}



	}
	public function updateSendInvoiceStatusAction()
	{
		$active	= $_POST['active'] == 'true' ? 1 : 0;

		$data = array(
			'negociation_id'	=> $_POST['negociation_id'],
			'invoice_index'		=> $_POST['invoice_index']
		);

		// GET LAST SEND_ID, OR CREATE IF NULL
		$sendIDData = eF_getTableData(
			"module_xpay_to_send_list",
			"id",
			sprintf("user_id = %d", $this->getCurrentUser()->user['id']),
			"id DESC"
		);

		if (count($sendIDData) > 0) {
			$data['send_id'] = $sendIDData[0]['id'];
		} else {
			$data['send_id'] = eF_insertTableData("module_xpay_to_send_list", array('user_id' => $this->getCurrentUser()->user['id'], 'data_envio' => date('Y-m-d', time() + (60*60*24*5)  )));
		}


		$result = eF_countTableData(
			"module_xpay_to_send_list_item",
			"negociation_id, invoice_index",
			sprintf(
				"send_id = %d AND negociation_id = %d AND invoice_index =%d",
				$data['send_id'], $data['negociation_id'], $data['invoice_index']
			)
		);

		if ($result[0]['count'] == 0) {
			if ($active == 1) {
				eF_insertTableData("module_xpay_to_send_list_item", $data);

				$result = array(
						'message'		=> 'Fatura incluída com sucesso',
						'message_type'	=> 'success'
				);
			} else {
				eF_deleteTableData("module_xpay_to_send_list_item", sprintf(
					"send_id = %d AND negociation_id = %d AND invoice_index =%d",
					$data['send_id'], $data['negociation_id'], $data['invoice_index']
				));

				$result = array(
					'message'		=> 'Fatura excluída com sucesso',
					'message_type'	=> 'success'
				);
			}
		}
		echo json_encode($result);
		exit;
	}
	/* EVENTS HANDLERS */
	/* MODULE EVENTS RECEIVERS */
	public function onPaymentReceivedEvent($context, $data)
	{
		//$data['payment_id'], $data['parcela_index']

		if (eF_checkParameter($data['payment_id'], 'id')) {
			$dataReturn = eF_updateTableData(
				"module_pagamento_invoices",
				array(
					"pago" 		=> self::_XPAY_AUTOPAY,
					"bloqueio" 	=> 1,
					"status_id"	=> 3
				),
				sprintf("payment_id = %d AND parcela_index = %d", $data['payment_id'], $data['parcela_index'])
			);


			// GET EDITED USER
			$paymentData = $this->getPaymentById($data['payment_id']);
			if (!eF_checkParameter($data['user_id'], 'id')) {
				$data['user_id'] = $paymentData['user_id'];
			}
			if (!eF_checkParameter($data['enrollment_id'], 'id')) {
				$data['enrollment_id'] = $paymentData['enrollment_id'];
			}
		}

		// GET ENROLL ID

		// CHECK ENROLL BY USER AND COURSE_ID
		/*
		$user = $this->getEditedUser();
		$courses = $user->getUserCourses(array('condition' => 'c.price > 0 AND c.active = 1'));

		$xenrollmentModule = $this->loadModule('xenrollment');

		foreach ($courses as $course) {
			if ( ($enrollmentData = $xenrollmentModule->getEnrollmentByUserAndCourseID($user->user['id'], $course->course['id']) ) !== FALSE) {
				break;
			}
		}
		*/
		if ($data['parcela_index'] == 1) {
			// MATRICULA PAGA, CRIAR OUTRAS PARCELAS
			//$this->registerMonthlyInvoicesAction(null, $data);


			// MIGRATE FROM module_pagamento TO HERE

		}

		// ALTERAR STATUS DA MATRICULA
		$xenrollmentModule = $this->loadModule("xenrollment");
		$xenrollmentModule->onPaymentReceivedEvent($context, $data);
			/*
			// ALTERAR TIPO DO ALUNO
			$xuserModule = $this->loadModule("xuser");
			$xuserModule->onPaymentReceivedEvent($context, $data);
			*/

		// CREATE ALL OTHER INVOICES, IF parcela_index = 1



		/*
		if ($data['parcela_index'] == 1) {
			// UPDATE ENROLLMENT, SET AS PAID

		*/

		/*
		} else {
			// JUST SEND E-MAIL TO fin@americas
		}
		*/
		// if ($registerReturned['updated'] && $registerReturned['tag'] == 1) {



	}
	/* NEW DATA MODEL FUNCTIONS */
	public function lockInvoice($negociation_id, $invoice_index, $reason = _XPAY_BLOCK)
	{
		if (is_numeric($negociation_id) && is_numeric($invoice_index)) {
			eF_updateTableData(
				"module_xpay_invoices",
				array(
					'locked' => 1,
					'locked_reason' => $reason
				),
				sprintf("negociation_id = %d AND invoice_index = %d", $negociation_id, $invoice_index)
			);
			return true;
		}
		return false;
	}
	public function unlockInvoice($negociation_id, $invoice_index)
	{
		if (is_numeric($negociation_id) && is_numeric($invoice_index)) {
			eF_updateTableData(
				"module_xpay_invoices",
				array(
					'locked' => 1,
					'locked_reason' => ""
				),
				sprintf("negociation_id = %d AND invoice_index = %d", $negociation_id, $invoice_index)
			);
			return true;
		}
		return false;
	}
	
	
	private function checkAndCreateInvoices($userDebit)
	{
		/*
		array(11) {
			["user_id"]=> string(4) "2330"
			["type"]=> string(6) "lesson"
			["module_id"]=> string(3) "165"
			["module"]=> string(29) "Fundamentos e Arquitetura Web"
			["matricula"]=> string(10) "1341263403"
			["base_price"]=> string(5) "199.5"
			["paid"]=> string(1) "0"
			["modality_id"]=> string(1) "0"
			["negociation_index"]=> NULL
			["modality"]=> NULL
			["balance"]=> int(199)
		}
		*/
	}
	private function _createInvoice($negociationID, $basePrice, $vencimento = 5, $invoice_id = null, $invoice_index = -1, $persist = true, $negociationUser = false, $is_registration_tax = false)
	{
		if (!is_numeric($negociationID)) {

		}
		if ($basePrice <= 0) {
			return false;
		}
		if (!$negociationUser) {
			$negociationUser = $this->getEditedUser();
		}

		if (is_null($vencimento)) {
			$vencimento = 5;
		}

		if (is_numeric($vencimento)) {
			// CREATE A VENCIMENTO WITH now() + "$vencimento" days
			$time = mktime(0, 0, 0, date('m'), date('d') + $vencimento, date("Y"));
			$invoice['data_vencimento'] = $insertData['data_vencimento'] = date("Y-m-d", $time);
			$vencimento = date_create_from_format("Y-m-d", $invoice['data_vencimento']);
		} elseif (is_object($vencimento)) {
			$invoice['data_vencimento'] = $insertData['data_vencimento'] = $vencimento->format("Y-m-d");
		}

		if ($vencimento) {
			$today = new DateTime("today");
			$apply_days = intval($vencimento->diff($today, true)->days);
		}

		if (!is_numeric($invoice_index) || $invoice_index == -1) {
			// CREATE A NEW INVOICE ID
			$invoice['invoice_index'] = $insertData['invoice_index'] = $this->_countTotalInvoices($negociationID);
		}
		if (empty($invoice_id)) {
			// CREATE A NEW INVOICE ID
			$invoice_id = $this->_createInvoiceID($negociationID, $insertData['invoice_index']);
		}

		$invoice['invoice_index']		= $insertData['negociation_id'] 	= $negociationID;
		$invoice['invoice_index'] 		= $insertData['invoice_index']		= $invoice_index;
		$invoice['description'] 		= $insertData['description']		= '';
		$invoice['invoice_id'] 			= $insertData['invoice_id']			= $invoice_id;
		$invoice['invoice_sha_access'] 	= $insertData['invoice_sha_access']	= '';
		$invoice['valor'] 				= $insertData['valor']				= $basePrice;
		$invoice['data_registro'] 		= $insertData['data_registro']		= date("Y-m-d H:i:s");
		//$invoice['data_vencimento'] 	= $insertData['data_vencimento']	= null;
		$invoice['locked'] 				= $insertData['locked']				= 0;
		$invoice['is_registration_tax'] = $insertData['is_registration_tax'] = ($is_registration_tax) ? 1 : 0;

		if ($persist) {
			//sub-group invoice_info
			eF_insertTableData("module_xpay_invoices", $insertData);
		}

		// CALC FIELDS
		$invoice = $this->_calculateInvoiceDetails($invoice, $negociationUser);

		$invoice['trans_count']	= 0;
		$invoice['paid']			= 0;
		$invoice['start_min']	= time();
		$invoice['start_max']	= time();

		return $invoice;
	}
	private function _countTotalInvoices($negociationID)
	{
		list($lastInvoice) = eF_getTableData(
			"module_xpay_invoices",
			"MAX(invoice_index) as last_invoice",
			sprintf("negociation_id = %d", $negociationID)
		);
		if (is_null($lastInvoice['last_invoice']) || $lastInvoice['last_invoice'] < 0) {
			return 1;
		}
		$lastInvoice['last_invoice']++;

		return $lastInvoice['last_invoice'];
	}
	private function initRuleSystem()
	{
		if (is_null($this->rules)) {
			$allRules = eF_getTableData(
				"module_xpay_price_rules",
				"*",
				"",
				"entify_id ASC"
			);
			$this->rules = $allRules;
		}
		if (is_null($this->rulesTags)) {
			$allRulesTags = eF_getTableData(
					"module_xpay_price_rules_tags",
					"*"
			);
			$tagRules = array();
			foreach ($allRulesTags as $tag) {
				if (!is_array($tagRules[$tag['rule_id']])) {
					$tagRules[$tag['rule_id']] = array();
				}
				$tagRules[$tag['rule_id']][] = $tag['tag'];
			}


			$this->rulesTags = $tagRules;
		}
		return true;
	}
	private function _isRuleAndTagsMatching($ruleID, $sentTags)
	{
		if (is_array($this->rulesTags[$ruleID])) {
			foreach ($this->rulesTags[$ruleID] as $tag) {
				if (!is_array($sentTags)) {
					return false;
				}
				if (!in_array($tag, $sentTags)) {
					return false;
				}
			}
		}
		return true;
		/*
		foreach ($sentTags as $tag) {
			if (!in_array($tag, $this->rulesTags[$ruleID])) {
				return false;
			}
		}
		return true;
		*/
	}
	private function _getAppliedRules($invoice)
	{
		$applied_rules = array();
		if ($invoice['total_reajuste'] <> 0) {
			foreach ($invoice['workflow'] as $workflow) {
				if (!array_key_exists($workflow['rule_id'], $applied_rules)) {
					foreach ($invoice['rules'] as $rule) {
						if ($rule['id'] == $workflow['rule_id']) {
							$currentRule = $rule;
							break;
						}
					}
					$applied_rules[$workflow['rule_id']] = array(
							'description'		=> $currentRule['description'],
							'input'				=> $workflow['input'],
							'diff'				=> $workflow['diff'],
							'repeat_acronym'	=> $currentRule['applied_on'] == 'per_day' ? __XPAY_DAYS : '',
							'count'				=> 0
					);
				}
				$applied_rules[$workflow['rule_id']]['output'] =  $workflow['output'];
				$applied_rules[$workflow['rule_id']]['count']++;
			}
		}
		return $applied_rules;
	}
	private function _applyFullPriceCalculations($userToCalculate, $basePrice, array $contraints = null, array $sentTags = null)
	{
		$this->initRuleSystem();
		$allRules = $this->rules;
		$xentifyModule = $this->loadModule("xentify");
		$xUserModule = $this->loadModule("xuser");

		if (is_null($sentTags)) {
			$sentTags = array();
		}
		$userTags = $xUserModule->getUserTags($userToCalculate);

		$sentTags = array_merge($userTags, $sentTags);

		// REMOVE ALL OUT-SCOPE
		$lastWorkflow = $this->_createEmptyWorkflow($basePrice);

		$totalAcrescimo = 0;
		$totalDesconto 	= 0;

		foreach ($allRules as $rule_index => $rule) {
			if (!$xentifyModule->isUserInScope($userToCalculate, $rule['rule_xentify_scope_id'], $rule['rule_xentify_id'])) {
				unset($allRules[$rule_index]);
				continue;
			}
			// CHECK CONDITION (IF ANY)

			if ($this->_isRuleAndTagsMatching($rule['id'], $sentTags)) {
				if ($rule['applied_on'] == 'once') {
					$workflows[] = $lastWorkflow = $this->_applyWorkflowRule($rule, $lastWorkflow);

					if ($lastWorkflow['diff'] > 0) {
						$totalAcrescimo	+= $lastWorkflow['diff'];
					} else {
						$totalDesconto	+= ($lastWorkflow['diff']) * -1;
					}
				} elseif ($rule['applied_on'] == 'per_day') {
					$middleWorkflow = $lastWorkflow;
					$middleWorkflows = array();
					for ($i = 1; $i <= $contraints['apply_days']; $i++) {
						$middleWorkflows[] = $middleWorkflow = $this->_applyWorkflowRule($rule, $middleWorkflow);

						if ($middleWorkflow['diff'] > 0) {
							$totalAcrescimo	+= $middleWorkflow['diff'];
						} else {
							$totalDesconto	+= ($middleWorkflow['diff']) * -1;
						}
						// AGGREGATE REPEAT WORKFLOWS IN ONE FLUX
						$workflows[] =$lastWorkflow = $middleWorkflow;
					}
				} else {
					continue;
				}
			}
		}
		return array(
			'full_price'	=> $lastWorkflow['output'],
			'acrescimo'		=> $totalAcrescimo,
			'desconto'		=> $totalDesconto,
			'rules'			=> $allRules,
			'workflow'		=> $workflows
		);
	}
	private function _createEmptyWorkflow($baseValue)
	{
		return array(
				'input'			=> 0,
				'output'		=> $baseValue,
				'base_value'	=> $baseValue
		);
	}
	private function _applyWorkflowRule($rule, $lastWorkflow)
	{
		// MUST RETURN A STRUCT
		$workflow = array(
			'rule_id'		=> $rule['id'],
			'input'			=> $lastWorkflow['output'],
			'output'		=> 0,
			'base_value'	=> $lastWorkflow['base_value'],
			'diff'			=> 0
		);

		// INIT CALC
		if ($rule['base_price_applied'] == 1) {
			$base_calc = $workflow['base_value'];
		} else {
			$base_calc = $workflow['input'];
		}

		if ($rule['percentual'] == 1) {
			$ruleValue = ($base_calc * $rule['valor']) * ($rule['type_id'] > 0 ? 1 : -1);
		} else {
			$ruleValue = ($rule['valor']) * ($rule['type_id'] > 0 ? 1 : -1);
		}
		$workflow['diff']	=	$ruleValue;
		$workflow['output'] = $workflow['input'] + $workflow['diff'];

		return $workflow;
	}
	/* GETTERS */
	private function _getLastPaymentsList($constraints, $limit = null)
	{
		//$limit = null MEANS "NO LIMIT"
		/** @todo Implement $constraints rules!! */
		$where = array();
		if (is_null($constraints)) {
			$constraints = array();
		}
		if (!array_key_exists("ies_id", $constraints)) {
			$constraints['ies_id'] = array_merge(array(0), $this->getCurrentUserIesIDs());
		}

		if (is_array($constraints["ies_id"]) && count($constraints["ies_id"]) > 0) {
			$where[] = sprintf("ies_id IN (%s)", implode(", ", $constraints["ies_id"]));
		}
		$lastPaymentsList = eF_getTableData(
			"module_xpay_zzz_paid_items",
			"negociation_id, user_id, course_id, paid_id, method_id, ies_id, polo_id, polo, course_name, classe_name, nosso_numero, name, surname, login, invoice_id, invoice_index, total_parcelas, data_vencimento, data_pagamento, valor, desconto + tarifa as desconto, IFNULL(total, paid) as paid",
			implode(" AND ", $where),
			"data_pagamento DESC",
			"",
			$limit

		);
		return $lastPaymentsList;
	}
	private function _getUserModuleStatus($login = null, $module_id = null)
	{
		if (!is_null($login)) {
			$editedUser = $this->getEditedUser(true, $login);
		} else {
			$editedUser = $this->getEditedUser();
		}

		$courseItens = eF_getTableData(
			"users_to_courses uc
			LEFT JOIN courses c ON (uc.courses_ID = c.id)
			LEFT OUTER JOIN module_xpay_course_modality_prices cp ON (
				uc.courses_ID = cp.course_id AND
				uc.modality_id = cp.modality_id AND (
					( uc.from_timestamp BETWEEN cp.from_timestamp AND cp.to_timestamp ) OR
					( uc.from_timestamp > cp.from_timestamp AND cp.to_timestamp = -1) OR
					( uc.from_timestamp < cp.to_timestamp AND cp.from_timestamp = -1)
				)
			) LEFT OUTER JOIN module_xpay_course_modality cm ON (uc.modality_id = cm.id)",
			sprintf(
				'%1$d as user_id, \'course\' as type, c.id as module_id, c.name as module, uc.from_timestamp as matricula,
				IFNULL(cp.price, c.price) as base_price,
				0 as paid,
				uc.modality_id,
				(SELECT MAX(negociation_index) FROM module_xpay_course_negociation WHERE user_id = %1$d AND course_id = uc.courses_ID) as negociation_index,
				cm.name as modality',
				$editedUser->user['id']
			),
			sprintf("users_LOGIN = '%s' AND uc.modality_id != 3 AND uc.archive = 0 AND uc.active = 1", $editedUser->user['login'])
		);
		$courses_ID = array(0);
		foreach ($courseItens as $courseData) {
			// GET COURSE LESSONS, AND EXCLUDE THEN
			$courses_ID[] = $courseData['module_id'];
		}

		$lessonItens = eF_getTableData(
			"users_to_lessons ul
			LEFT join lessons l ON (ul.lessons_ID = l.id)
			LEFT OUTER join module_xpay_lesson_modality_prices lp ON (
				ul.lessons_ID = lp.lesson_id
				AND ul.modality_id = lp.modality_id AND (
					( ul.from_timestamp BETWEEN lp.from_timestamp AND lp.to_timestamp ) OR
					( ul.from_timestamp > lp.from_timestamp AND lp.to_timestamp = -1) OR
					( ul.from_timestamp < lp.to_timestamp AND lp.from_timestamp = -1)
				)
			)
			LEFT OUTER join module_xpay_lesson_modality lm ON (ul.modality_id = lm.id)",
			sprintf(
	 			'%1$d as user_id, \'lesson\' as type, l.id as module_id, l.name as module,
				ul.from_timestamp as matricula, IFNULL(lp.price, l.price) as base_price,
				0 as paid, ul.modality_id,
				(SELECT MAX(negociation_index) FROM module_xpay_lesson_negociation WHERE user_id = %1$d AND lesson_id = ul.lessons_ID) as negociation_index,
				lm.name as modality',
				$editedUser->user['id']
			),
			sprintf("
				users_LOGIN = '%s'
				AND l.course_only = 0
				AND l.id NOT IN (SELECT lessons_ID FROM lessons_to_courses WHERE courses_ID IN (%s))
				AND ul.archive = 0
				AND ul.active = 1
				",
				$editedUser->user['login'],
				implode(",", $courses_ID)
			)
		);
		$userDebitsData = $courseItens + $lessonItens;

		$userDebits = array();
		// CHECK FOR COURSE_ONLY SELECTED LESSONS
		foreach ($userDebitsData as $statement) {
			$statement['balance'] = intval($statement['base_price'])-intval($statement['paid']);
			$userDebits[] = $statement;
		}

		return $userDebits;
	}
	private function _getUserModuleNegociations($login = null, $module_id = null, $module_type = null)
	{
		if (!is_null($login)) {
			$editedUser = $this->getEditedUser(true, $login);
		} else {
			$editedUser = $this->getEditedUser();
		}

		/* FUNCTION WALKTHROUGH */
		/* STEP 1. GET ALL USER NO-SIMULATED NEGOCIATIONS */
		$userNegociations = eF_getTableDataFlat(
			'module_xpay_course_negociation neg',
			'neg.id',
			sprintf('neg.user_id = %1$d AND neg.is_simulation = 0', $editedUser->user['id'])
		);
		if (count($userNegociations['id'])) {
			foreach ($userNegociations['id'] as $negocID) {
				/* STEP 2. (**MOVE TO INNER FUNCTIONS**) CHECK IF THESE NEGOCIATION ARE GROUPED OR NOT */
				$negociation = $this->_getNegociationById($negocID);

				if (count($negociation['modules']) > 0) {
					$negociations[] = $negociation;
				}
			}
		}

		/** @todo STEP 3. GRAB ALL USER COURSES AND LESSONS WHO DOESNT HAVE NEGOCIATION, AND APPEND */

		return $negociations;
	}
	public function _getNegociationPayerByNegociationID($negociationID)
	{
		// CHECK IF IS A SINGLE OR MULTIPLE PAYER NEGOCIATION
		if (eF_checkParameter($negociationID, 'id')) {
			$sendToData = eF_getTableData(
				"module_xpay_course_negociation",
				"user_id, send_to, ref_payment_id",
				"id = " . $negociationID
			);

			if (count($sendToData) == 0) {
				return false;
			}

			$sendTo = $sendToData[0]['send_to'];
			$studentID = $sendToData[0]['user_id'];
			$xUserModule = $this->loadModule("xuser");
			if (is_null($sendTo)) {
				$oldSendTo = eF_getTableData("module_pagamento", "send_to", "payment_id = " . $sendToData[0]['ref_payment_id']);

				if (count($oldSendTo) == 0) {
					// DEFAULT TYPE
					$sendTo = 'student';
				} else {
					$sendTo = $oldSendTo[0]['send_to'];
				}
			}

			return $xUserModule->getUserDetails($studentID, $sendTo);
		}
	}
	public function _getNegociationById($negociationID)
	{
		return $this->_getNegociationByContraints(array(
			'negociation_id'	=> $negociationID
		));
	}
	private function _getNegociationByUserEntify($login = null, $module_id = null, $module_type = 'course', $negociation_index = null, $simulation_status = 0)
	{
		if (!is_array($simulation_status)) {
			$simulation_status = array($simulation_status);
		}
		$constraints = array(
			'login'				=> $login,
			'negociation_index'	=> $negociation_index,
			'simulation_status'	=> $simulation_status
		);
		if ($module_type == 'lesson') {
			$constraints['lesson_id']	= $module_id;
		} else {
			$constraints['course_id']	= $module_id;
		}
		return $this->_getNegociationByContraints($constraints);
	}
	public function _getNegociationByContraints(array $contraints = null)
	{
		$where = $order = array();

		if (!is_null($contraints['negociation_id'])) {
			$where[] = sprintf("neg.id = %d", $contraints['negociation_id']);
		}

		if (!is_null($contraints['login']) || !is_null($contraints['user_id'])) {
			$userID = is_null($contraints['login']) ? $contraints['user_id'] : $contraints['login'];
			$editedUser = $this->getEditedUser(true, $userID);
			$where[] = sprintf("neg.user_id = %d", $editedUser->user['id']);
		} else {
			$editedUser = $this->getEditedUser();
		}

		if (!is_null($contraints['course_id']) && $contraints['course_id'] != 0) {
			$editedCourse = $this->getEditedCourse(true, $contraints['course_id']);
			$where[] = sprintf("neg.course_id = %d", $editedCourse->course['id']);
		}
		if (!is_null($contraints['lesson_id']) && $contraints['lesson_id'] != 0) {
			$editedLesson = $this->getEditedLesson(true, $contraints['lesson_id']);
			$where[] = sprintf("neg.lesson_id = %d", $editedLesson->lesson['id']);
		}

		if (empty($contraints['negociation_index'])) {
			// IF NO NEGOCIATION_INDEX DEFINED, THEN RETURN THE LAST ACTIVE ONE.
			$where[] = "neg.active = 1";
		} else {
			$where[] = sprintf("negociation_index = %d", $contraints['negociation_index']);
		}

		if (empty($contraints['group_by_lesson']) || in_array($contraints['group_by'], array("lesson", "group"))) {
			$contraints['group_by'] = 'course';
		}

		if (!is_array($contraints['simulation_status'])) {
			$simulation_status = array(0);
		} else {
			$simulation_status = $contraints['simulation_status'];
		}
		$where[] = sprintf("neg.is_simulation IN (%s)", implode(",", $simulation_status));
/*
		echo  prepareGetTableData(
			"module_xpay_course_negociation neg
			LEFT OUTER JOIN module_xpay_invoices_to_paid inv2pd ON (inv2pd.negociation_id = neg.id)
			LEFT OUTER JOIN module_xpay_paid_items pd ON (inv2pd.paid_id = pd.id)
			LEFT JOIN users u ON (neg.user_id = u.id)
			",
			'neg.id, u.id as user_id, u.name, u.surname, u.login,
			neg.is_simulation, neg.course_id, neg.lesson_id,
			IFNULL(SUM(pd.paid), 0) as paid,
			neg.negociation_index',
			implode(" AND ", $where),
			"negociation_index DESC",
			"neg.id, u.id"
		);
*/
		$negociationData = ef_getTableData(
			"module_xpay_course_negociation neg
			LEFT OUTER JOIN module_xpay_invoices_to_paid inv2pd ON (inv2pd.negociation_id = neg.id)
			LEFT OUTER JOIN module_xpay_paid_items pd ON (inv2pd.paid_id = pd.id)
			LEFT JOIN users u ON (neg.user_id = u.id)
			",
			'neg.id, u.id as user_id, u.name, u.surname, u.login,
			neg.is_simulation, neg.course_id, neg.lesson_id, neg.send_to, 
			IFNULL(SUM(pd.paid), 0) as paid,
			neg.negociation_index',
			implode(" AND ", $where),
			"negociation_index DESC",
			"neg.id, u.id"
		);
		/*
		IFNULL(c.id, l.id) as module_id,
		IFNULL(c.name, l.name) as module,
		IFNULL(uc.from_timestamp, ul.from_timestamp) as matricula,
		IFNULL(IFNULL(clp.price, c.price), l.price) as base_price,
		IFNULL(uc.modality_id, ul.modality_id) as modality_id,
		cm.name as modality
		*/
		if (count($negociationData) > 0) {
			$negociationData = reset($negociationData);

			$negociationData['modules'] = array();

			if ($negociationData['lesson_id'] == 0 && $negociationData['course_id'] == 0) {
				// SEARCH ON module_xpay_negociation_modules
				$negociationData['modules'] = eF_getTableData(
					"module_xpay_negociation_modules",
					'lesson_id, course_id, module_type',
					'negociation_id = ' . $negociationData['id']
				);
			} elseif ($negociationData['lesson_id'] == 0 && $negociationData['course_id'] != 0) {
				$negociationData['modules'][] = array(
					'lesson_id'		=> 0,
					'course_id'		=> $negociationData['course_id'],
					'module_type'	=> 'course'
				);
			} elseif ($negociationData['lesson_id'] != 0) {
				$negociationData['modules'][] = array(
					'lesson_id'		=> $negociationData['lesson_id'],
					'course_id'		=> $negociationData['course_id'],
					'module_type'	=> 'lesson'
				);
			}

			/*
			if ($negociationData['lesson_id'] != 0) {
				$negociationData['module_type'] = 'lesson';
			} else {
				$negociationData['module_type'] = 'course';
			}
			if ($negociationData['modality_id'] == 3) {
				// CALCULATE PRICE PER MODULE
			}
			*/

			// GET ALL NEGOCIATION INVOICES
			$negociationData['username'] = formatLogin(null, $negociationData);

			$userNegociation = $this->getEditedUser(null, $negociationData['user_id']);

			$negociationData['acrescimo']		= 0;
			$negociationData['desconto']		= 0;
			$negociationData['invoices']		= $this->getNegociationInvoices($negociationData['id']);

			// CALCULATE BASE PRICE, BASED ON MODULES SOMATORY
			foreach ($negociationData['modules'] as $module) {
				switch ($module['module_type']) {
					case 'course' : {
						$coursePriceID[] = $module['course_id'];
						break;
					}
					case 'lesson' : {
						$lessonPriceID[] = $module['lesson_id'];
						break;
					}
				}
			}

			$priceWhere = array(
				'u.id = ' . $negociationData['user_id']
			);
			$coursePriceInfo = $lessonPriceInfo = array();

			if (count($coursePriceID) > 0) {
				$coursePriceWhere = array_merge($priceWhere, array(sprintf('c.id IN (%s)', implode(", ", $coursePriceID))));

				$coursePriceInfo = ef_getTableData(
					"users u
			 		LEFT OUTER JOIN users_to_courses uc ON (u.login = uc.users_LOGIN AND uc.modality_id <> 3)
			 		LEFT OUTER JOIN courses c ON (uc.courses_ID = c.id)
			 		LEFT OUTER JOIN module_xpay_course_modality_prices clp ON (
			 				(uc.courses_ID = clp.course_id) AND
			 				(uc.modality_id = clp.modality_id) AND (
			 					( uc.from_timestamp BETWEEN clp.from_timestamp AND clp.to_timestamp ) OR
			 					( uc.from_timestamp > clp.from_timestamp AND clp.to_timestamp = -1) OR
			 					( uc.from_timestamp < clp.to_timestamp AND clp.from_timestamp = -1)
			 				)
			 		) LEFT OUTER JOIN module_xpay_course_modality cm ON (uc.modality_id = cm.id)
			 		",
					'c.id as module_id, c.name as module, \'course\' as module_type, uc.from_timestamp as matricula,
			 		IFNULL(clp.price, c.price) as base_price,
			 		/* IFNULL(SUM(pd.paid), 0) as paid, */ uc.modality_id as modality_id, cm.name as modality',
					implode(" AND ", $coursePriceWhere),
					"",
					"c.id"
				);
			}
			if (count($lessonPriceID) > 0) {
				$lessonPriceWhere = array_merge($priceWhere, array(sprintf('l.id IN (%s)', implode(", ", $lessonPriceID))));

				$lessonPriceInfo = ef_getTableData(
					"users u
			 		LEFT OUTER JOIN users_to_lessons ul ON (u.login = ul.users_LOGIN)
			 		LEFT OUTER JOIN lessons l ON (ul.lessons_ID = l.id)
			 		LEFT OUTER JOIN module_xpay_course_modality_prices clp ON (
			 				(ul.lessons_ID = clp.lesson_id ) AND
			 				(ul.modality_id = clp.modality_id ) AND (
			 					( ul.from_timestamp BETWEEN clp.from_timestamp AND clp.to_timestamp ) OR
			 					( ul.from_timestamp > clp.from_timestamp AND clp.to_timestamp = -1) OR
			 					( ul.from_timestamp < clp.to_timestamp AND clp.from_timestamp = -1)
			 				)
			 		) LEFT OUTER JOIN module_xpay_course_modality cm ON (ul.modality_id = cm.id)
			 		",
					'l.id as module_id, l.name as module, \'lesson\' as module_type, ul.from_timestamp as matricula,
			 		IFNULL(clp.price, l.price) as base_price,
					/* IFNULL(SUM(pd.paid), 0) as paid, */ ul.modality_id as modality_id, cm.name as modality',
						implode(" AND ", $lessonPriceWhere),
						"",
						"l.id"
				);
			}

			$negociationData['modules'] = $coursePriceInfo + $lessonPriceInfo;

			/*
			if (count($negociationData['modules']) == 0) {
				return false;
			}
			*/

			foreach ($negociationData['modules'] as $module) {
				$negociationData['base_price'] += $module['base_price'];

				$module_names[] = $module['module'];
			}

			if (count($negociationData['modules']) <= 1) {
				$negociationData['module_printname'] = implode(", ", $module_names);
			} else {
				$negociationData['module_printname'] = sprintf(__XPAY_MODULE_PRINTNAME_COUNT, count($negociationData['modules']));
			}

			if (count($negociationData['invoices']) == 0) {
				// APLLY RULES ON ALL
				/*
				list(
						$negociationData['full_price'],
						$negociationData['acrescimo'],
						$negociationData['desconto'],
						$rules,
						$workflow
				) = array_values($this->_applyFullPriceCalculations(
						$userNegociation,
						$negociationData['base_price'],
						array(
						),
						array('is_not_overdue', 'is_not_full_paid')
				));
				*/
				$negociationData['full_price']	+= $negociationData['base_price'];
			} else {
				/*
				list(
						$invoice['full_price'],
						$invoice['acrescimo'],
						$invoice['desconto'],
						$invoice['rules'],
						$invoice['workflow']
				) = array_values($this->_applyFullPriceCalculations(
						$negociationUser,
						$basePrice,
						array(
								'ies_id' 	=> 0,
								'polo_id' 	=> 0,
								'course_id' => 0,
								'class_id' 	=> 0,
								'group_id' 	=> 0,
								'user_id' 	=> 0
						)
				));
				*/

				$negociationData['full_price']	= 0;
				$negociationData['paid'] 		= 0;

				foreach ($negociationData['invoices'] as $invoice) {
					$total_basePrice 				+= $invoice['valor'];
					$negociationData['full_price']	+= $invoice['full_price'];
					$negociationData['acrescimo']	+= $invoice['acrescimo'];
					$negociationData['desconto']	+= $invoice['desconto'];
					$negociationData['paid'] 		+= $invoice['paid'];
				}

				if ($total_basePrice <> $negociationData['base_price']) {
					// INVOICE BASE PRICE SUM IS DIFERENT FROM NEGOCIATION BASE PRICE TOTAL, MUST CALCULATE THE DIFF
					$totalUncovered = $negociationData['base_price'] - $total_basePrice;

					// APPLY A FULL PRICE CALCULATION ON $totalUncovered
					$negociationCalc = array();
					list(
						$negociationCalc['full_price'],
						$negociationCalc['acrescimo'],
						$negociationCalc['desconto'],
						$negociationCalc['rules'],
						$negociationCalc['workflow']
					) = array_values($this->_applyFullPriceCalculations(
						$userNegociation,
						$totalUncovered,
						array(
							'ies_id' 	=> 0,
							'polo_id' 	=> 0,
							'course_id' => 0,
							'class_id' 	=> 0,
							'group_id' 	=> 0,
							'user_id' 	=> 0
						),
						/** @todo check how to get these "tags" */
						array('is_not_registration_tax', 'is_not_overdue', 'is_not_full_paid', 'is_a_negociation')
					));

					$negociationData['full_price'] 	+= $negociationCalc['full_price'];

					if ($totalUncovered < 0 && $negociationCalc['acrescimo'] == 0) { // A SOMA DAS FATURAS É MAIOR QUE O TOTAL DO CURSO. ADICIONAR COMO ACRESCIMO.
						$negociationData['acrescimo'] 	+= abs($totalUncovered);
						$negociationData['full_price']	+= $negociationData['acrescimo'];
					} else {
						$negociationData['acrescimo'] 	+= $negociationCalc['acrescimo'];
					}
					if ($totalUncovered > 0 && $negociationCalc['desconto'] == 0) { // A SOMA DAS FATURAS É MENOR QUE O TOTAL DO CURSO. ADICIONAR COMO DESCONTO.
						$negociationData['desconto'] 	+= $totalUncovered;
						$negociationData['full_price']	-= $negociationData['desconto'];
					} else {
						$negociationData['desconto'] 	+= $negociationCalc['desconto'];
					}

					$negociationData['rules']		=  $negociationCalc['rules'];
					$negociationData['workflow']	=  $negociationCalc['workflow'];
				}
			}

			return $negociationData;
		} else {
			return array();
		}
	}
	private function getNegociationInvoices($nego_id)
	{
		$negoInvoices = eF_getTableData(
			"module_xpay_invoices inv
			LEFT JOIN module_xpay_course_negociation neg ON (inv.negociation_id = neg.id)
			LEFT OUTER JOIN module_xpay_invoices_to_paid inv2paid ON (
				inv.negociation_id = inv2paid.negociation_id AND
				inv.invoice_index = inv2paid.invoice_index
			) LEFT OUTER JOIN module_xpay_paid_items pd ON (
				inv2paid.paid_id = pd.id
			)",
			"neg.user_id, inv.negociation_id, inv.invoice_index, inv.invoice_id, inv.invoice_sha_access, 
			inv.is_registration_tax, inv.valor, inv.data_registro, inv.data_vencimento, 
			COUNT(pd.id) as trans_count, IFNULL(IFNULL(SUM(inv2paid.full_value), SUM(pd.paid)), 0) as paid, 
			pd.method_id, inv.locked, inv.locked_reason, MIN(pd.start_timestamp) as start_min, 
			MAX(pd.start_timestamp) as start_max",
			sprintf("inv.negociation_id = %d", $nego_id),
			"data_registro ASC",
			"inv.negociation_id, inv.invoice_index, inv.invoice_id, inv.invoice_sha_access,
			inv.valor, inv.data_registro, inv.data_vencimento"
		);

		// CALCULATE INVOICE DETAILS

		foreach ($negoInvoices as &$invoice) {
			// ROUND valor, paid values
			$invoice['paid'] = round($invoice['paid'], 2);

			$negociationUser = $this->getEditedUser(false, $invoice['user_id']);
			$invoice = $this->_calculateInvoiceDetails($invoice, $negociationUser);
		}

		return $negoInvoices;
	}
	public function _getNegociationInvoiceByIndex($nego_id, $invoice_index)
	{
		$negoInvoices = eF_getTableData(
			"module_xpay_invoices inv
			LEFT JOIN module_xpay_course_negociation neg ON (inv.negociation_id = neg.id)
			LEFT OUTER JOIN module_xpay_invoices_to_paid inv2paid ON (
				inv.negociation_id = inv2paid.negociation_id AND
				inv.invoice_index = inv2paid.invoice_index
			) LEFT OUTER JOIN module_xpay_paid_items pd ON (
				inv2paid.paid_id = pd.id
			)",
			"neg.user_id, inv.negociation_id, neg.course_id, inv.invoice_index, inv.invoice_id, inv.invoice_sha_access,
			inv.is_registration_tax, inv.valor, inv.data_registro, inv.data_vencimento, COUNT(pd.id) as trans_count,
			IFNULL(IFNULL(SUM(inv2paid.full_value), SUM(pd.paid)), 0) as paid, pd.method_id,
			MIN(pd.start_timestamp) as start_min, MAX(pd.start_timestamp) as start_max",
			sprintf("inv.negociation_id = %d AND inv.invoice_index = %d", $nego_id, $invoice_index),
			"data_registro ASC",
			"inv.negociation_id, inv.invoice_index, inv.invoice_id, inv.invoice_sha_access,
			inv.valor, inv.data_registro, inv.data_vencimento"
		);
		if (count($negoInvoices) > 0) {
			// CALCULATE INVOICE DETAILS
			foreach ($negoInvoices as &$invoice) {
				$negociationUser = $this->getEditedUser(false, $invoice['user_id']);
				$invoice = $this->_calculateInvoiceDetails($invoice, $negociationUser);
			}
		}

		return reset($negoInvoices);
	}
	public function _getSendInvoicesList($user_id = null)
	{
		if (is_null($user_id)) {
			$user_id = $this->getCurrentUser()->user['id'];
		}

		return eF_getTableData(
			"module_xpay_to_send_list_item send_item
			LEFT JOIN module_xpay_to_send_list send ON (send_item.send_id = send.id)",
			"send_item.send_id, send_item.negociation_id, send_item.invoice_index",
			sprintf("send.user_id = %d", $user_id)
		);
	}
	private function _calculateInvoiceDetails($invoice, $negociationUser)
	{
		$currentVencimento = date_create_from_format("Y-m-d", $invoice['data_vencimento']);
		if (!$currentVencimento)
			$currentVencimento = date_create_from_format("Y-m-d H:i:s", $invoice['data_vencimento']);

		if ($currentVencimento) {
			$today = new DateTime("today");
			$apply_days = intval($currentVencimento->diff($today, true)->days);
		}

		// FIRST GET TOTAL ALREADY PAID
		$baseValue = $invoice['valor'] - $invoice['paid'];

		list(
			$invoice['full_price'],
			$invoice['acrescimo'],
			$invoice['desconto'],
			$invoice['rules'],
			$invoice['workflow']
		) = array_values($this->_applyFullPriceCalculations(
			$negociationUser,
			$baseValue,
			array(
				'apply_days'	=> $apply_days
			),
			$this->_getInvoiceTags($invoice)
		));
		$invoice['full_price'] += $invoice['paid'];

		$invoice['total_reajuste']	= $invoice['acrescimo'] - $invoice['desconto'];
/*

		if ($baseValue > 0) {
			// CALCULATE INVOICE DETAILS BASED ON PAYMENT METHOD RULES
			$currentVencimento = date_create_from_format("Y-m-d", $invoice['data_vencimento']);
			if (!$currentVencimento) {
				$currentVencimento = date_create_from_format("Y-m-d H:i:s", $invoice['data_vencimento']);
			}

			$today = new DateTime("today");

			if ($currentVencimento && $currentVencimento->diff($today, false)->format("%r%d") > 0 ) {
				//APLICAR MULTA E JUROS, AND GET DATA FROM
				$percentualMultaAoMes = 0.02;
				$percentualJurosAoDia = 0.0033;

				$invoice['dias_vencidos'] = floor((time() - strtotime($invoice['data_vencimento'])) / 60 / 60 / 24);

				$invoice['total_multa'] = intval($baseValue) * $percentualMultaAoMes;
				$invoice['juros_dia'] = intval($baseValue) * $percentualJurosAoDia;

				$invoice['juros_dia'] = 0;

				$invoice['total_acrescimo'] = $invoice['total_multa'] +
				($invoice['juros_dia'] * $invoice['dias_vencidos']);
			} else {
				// MATRICULA OU NÃO-VENCIDA
			}
			$invoice['total_reajuste'] = $invoice['total_acrescimo'] - $invoice['total_desconto'];
		}
		*/

		return $invoice;
	}
	private function _getInvoiceTags($invoice)
	{
		// disponible tags: is_overdue, is_full_paid, is_registration_tax
		/** @todo montar um jeito mais estruturado para buscar paa "taggear" as faturas */
		/** @todo Buscar as tags disponiveis do banco */
		/** @todo Criar callbacks para checar as tags */
		$tagList = array(
			'is_overdue',
			'is_not_overdue',
			'is_full_paid',
			'is_not_full_paid',
			'is_registration_tax',
			'is_not_registration_tax',
			'is_custom',
			'is_not_custom'
		);
		$tags = array();

		//$currentVencimento = date_create_from_format("Y-m-d", $invoice['data_vencimento']);
		$currentVencimento = date_create_from_format("Y-m-d", $invoice['data_vencimento']);
		if (!$currentVencimento) {
			//$currentVencimento = date_create_from_format("Y-m-d H:i:s", $invoice['data_vencimento']);
			$currentVencimento = date_create_from_format("Y-m-d H:i:s", $invoice['data_vencimento']);
		}

		if ($currentVencimento) {
			// cheching if a weekend
			$weekday = intval($currentVencimento->format("N"));

			//1 (for Monday) through 7 (for Sunday)
			$OneDayInterval = new DateInterval("P1D");
			if ($weekday == 6) {
				//$currentVencimento->add($OneDayInterval)->add($OneDayInterval);
			} elseif ($weekday == 7) {
				//$currentVencimento->add($OneDayInterval);
			}

			$today = new DateTime("today");
			if ((intval($currentVencimento->diff($today, false)->format("%r%d")) > 0)) {
				$tags[] = 'is_overdue';
			} else {
				$tags[] = 'is_not_overdue';
			}
		}
		if ((intval($invoice['paid']) >= intval($invoice['valor']))) {
			$tags[] = 'is_full_paid';
		} else {
			$tags[] = 'is_not_full_paid';
		}
		if (intval($invoice['is_registration_tax']) == 1) {
			$tags[] = 'is_registration_tax';
		} else {
			$tags[] = 'is_not_registration_tax';
		}
		return $tags;
	}
	private function _createInvoiceID($negociation_id, $parcela_index)
	{
		$i = 0;
		do {
			$invoice_id_sem_DV = sprintf(self::INVOICE_ID_TEMPLATE_SEM_DV, substr($negociation_id, 0, 4), substr($parcela_index, 0, 2), $i);
			$invoice_id = $invoice_id_sem_DV . $this->_module10($invoice_id_sem_DV);

			// CHECK IF EXISTS, IF TRUE, GENERATE AGAIN
			$existsCount = eF_countTableData(
					// table
					"module_xpay_invoices",
					// fields
					"invoice_id",
					// where clause
					"invoice_id = '" . $invoice_id . "'"
			);
			$totalcount = $existsCount[0]['count'];
			$i++;
		} while ( $totalcount > 0 );
		return $invoice_id;
	}
	private function _getInvoicesInDebts($limit = null)
	{
		$tables = array(
			"module_xpay_invoices inv",
			"join module_xpay_course_negociation neg ON (inv.negociation_id = neg.id)",
			"LEFT OUTER join module_xpay_invoices_to_paid inv2paid ON ( inv.negociation_id = inv2paid.negociation_id AND inv.invoice_index = inv2paid.invoice_index )",
			"LEFT OUTER join module_xpay_paid_items pd ON ( inv2paid.paid_id = pd.id )",
			"join users u ON (neg.user_id = u.id)",
			"join courses c ON (neg.course_id = c.id)",
			"LEFT OUTER join module_ies ies ON (c.ies_id = ies.id)"
		);

		$fields = array(
			"neg.user_id",
			"inv.negociation_id",
			"inv.invoice_index",
			"(SELECT COUNT(invoice_index) FROM module_xpay_invoices WHERE negociation_id = neg.id) as total_parcelas",
			"neg.course_id",
			"u.name",
			"u.surname",
			"u.login",
			"c.ies_id",
			"ies.nome as ies",
			"c.name as course",
			//"COUNT(inv.invoice_index) as total_parcelas",
			"SUM(inv.valor) as valor_total",
			"IFNULL(SUM(IFNULL(inv2paid.full_value, pd.paid)), 0) as considered_paid",
			"IFNULL(SUM(pd.paid), 0) as real_paid",
			"SUM(inv.valor) - IFNULL(SUM(IFNULL(inv2paid.full_value, pd.paid)), 0) as total_debito",
			"inv.data_vencimento as data_debito_inicial"
		);

		//$where = $this->makeInvoicesListFilters(null, "inv.parcela_index");
		$today = new DateTime("today");
		$iesIds = $this->getCurrentUserIesIDs();

		$iesIds[] = 0;
		$where[] = sprintf("c.ies_id IN (%s)", implode(',', $iesIds));
		$where[] = "inv.data_vencimento < NOW()";
		$where[] = "u.active = 1";
		$where[] = "neg.is_simulation = 0"; /// ONLY CURRENT-ACTIVE NEGOCIATIONS
		//$where[] = "inv.invoice_index > 0"; /// ONLY MENSALIDADES

		//$where[] = sprintf("inv.payment_type_id IN (SELECT payment_type_id FROM module_xpayment_types_to_xies WHERE ies_id IN (%s))", implode(',', $iesIds));

		$order = array(
			"inv.data_vencimento ASC"
		);

		$group = array(
			"neg.user_id",
			"inv.negociation_id",
			"inv.invoice_index",
			"neg.course_id",
			"u.name",
			"u.surname",
			"u.login",
			"c.name /* HAVING SUM(inv.valor) > IFNULL(SUM(IFNULL(inv2paid.full_value, pd.paid)), 0)*/"
		);

		// MAKE FILTERS
		/*
		 echo prepareGetTableData(
		 		implode(" ", $tables),
		 		implode(", ", $fields),
		 		implode(" AND ", $where),
		 		implode(", ", $order),
		 		implode(", ", $group)
		 );
		*/
		$debtsLists = eF_getTableData(
			implode(" ", $tables),
			implode(", ", $fields),
			implode(" AND ", $where),
			implode(", ", $order),
			implode(", ", $group),
			$limit
		);

		return $debtsLists;
	}
	private function _getUserInDebts($limit = null)
	{
		$tables = array(
				"module_xpay_invoices inv",
				"join module_xpay_course_negociation neg ON (inv.negociation_id = neg.id)",
				"LEFT OUTER join module_xpay_invoices_to_paid inv2paid ON ( inv.negociation_id = inv2paid.negociation_id AND inv.invoice_index = inv2paid.invoice_index )",
				"LEFT OUTER join module_xpay_paid_items pd ON ( inv2paid.paid_id = pd.id )",
				"join users u ON (neg.user_id = u.id)",
				"join courses c ON (neg.course_id = c.id)",
				"LEFT OUTER join module_ies ies ON (c.ies_id = ies.id)"
		);

		$fields = array(
				"neg.user_id",
				"inv.negociation_id",
				"COUNT(inv.invoice_index) as invoice_index",
				"(SELECT COUNT(invoice_index) FROM module_xpay_invoices WHERE negociation_id = neg.id) as total_parcelas",
				"neg.course_id",
				"u.name",
				"u.surname",
				"u.login",
				"c.ies_id",
				"ies.nome as ies",
				"c.name as course",
				//"COUNT(inv.invoice_index) as total_parcelas",
				"SUM(inv.valor) as valor_total",
				"IFNULL(SUM(IFNULL(inv2paid.full_value, pd.paid)), 0) as considered_paid",
				"IFNULL(SUM(pd.paid), 0) as real_paid",
				"SUM(inv.valor) - IFNULL(SUM(IFNULL(inv2paid.full_value, pd.paid)), 0) as total_debito",
				"MIN(inv.data_vencimento) as data_debito_inicial"
		);

		//$where = $this->makeInvoicesListFilters(null, "inv.parcela_index");
		$today = new DateTime("today");
		$iesIds = $this->getCurrentUserIesIDs();

		$iesIds[] = 0;
		$where[] = sprintf("c.ies_id IN (%s)", implode(',', $iesIds));
		$where[] = "inv.data_vencimento < NOW()";
		$where[] = "u.active = 1";
		$where[] = "neg.is_simulation = 0"; /// ONLY CURRENT-ACTIVE NEGOCIATIONS
		//$where[] = "inv.invoice_index > 0"; /// ONLY MENSALIDADES

		//$where[] = sprintf("inv.payment_type_id IN (SELECT payment_type_id FROM module_xpayment_types_to_xies WHERE ies_id IN (%s))", implode(',', $iesIds));

		$order = array(
				"inv.data_vencimento ASC"
		);

				$group = array(
						"neg.user_id",
						"inv.negociation_id",
						"neg.course_id",
						"u.name",
						"u.surname",
						"u.login",
			"c.name HAVING SUM(inv.valor) > IFNULL(SUM(IFNULL(inv2paid.full_value, pd.paid)), 0)"

				);

				// MAKE FILTERS
				/*
			 echo prepareGetTableData(
			 		implode(" ", $tables),
			 		implode(", ", $fields),
			 		implode(" AND ", $where),
			 		implode(", ", $order),
			 		implode(", ", $group)
			 );
				*/
				$debtsLists = eF_getTableData(
						implode(" ", $tables),
						implode(", ", $fields),
			implode(" AND ", $where),
						implode(", ", $order),
								implode(", ", $group),
				$limit
				);

			return $debtsLists;
		}
	/* UTILITY FUNCTIONS */
	private function _sendInvoiceAdvise($negociation_id, $invoice_index, $send_id = null)
	{
		if (is_null($negociation_id)) {
			return false;
		}
		if (is_null($invoice_index)) {
			return false;
		}
		/** @todo Get Template from another datasource */
		$bodyTemplate =
			'<div>
			<div>
			<p class="MsoNormal">Caro(a) <b><span style="color: rgb(31, 73, 125);">{$USERNAME}</span></b></p>
			</div>
			<br />
			<div>
			<p class="MsoNormal"><span style="font-size: 11pt; color: rgb(31, 73, 125);">Segue o boleto referente à {$MONTH}. Clique no link abaixo para acessar o boleto.</span></p>
			</div>
			<br />
			<div>
			<p class="MsoNormal">
			<b><span style="font-size: 11pt; color: rgb(31, 73, 125);"><a href="{$ACCESS_LINK}">{$ACCESS_LINK}</a></span></b>
			</p>
			</div>
			<br />
			<div>
			<p class="MsoNormal"><span style="color: rgb(31, 73, 125);">Para
			esclarecimentos de dúvidas sobre os pagamentos entre em contato com nosso departamento
			financeiro pelo e-mail <a target="_blank"
			href="mailto:fin@americas.com.br">fin@americas.com.br</a>.</span></p>
			</div>

			<br />
			<div>
			<p class="MsoNormal"><span style="font-size: 10pt;">Atenciosamente,</span></p>
			</div>
			<div>
			<p class="MsoNormal"><span style="font-size: 10pt; color: rgb(31, 73, 125);"><br>Grupo Américas<br></span>
			<u>
			<span style="font-size: 10pt; color: blue;">
			<a target="_blank" href="http://www.americas.com.br/">www.americas.com.br</a><br>
			</span>
			</u>
			<span style="font-size: 10pt; color: rgb(31, 73, 125);">Rua Cel. Dulcídio • 517 • Lj. 79<br>
			Batel • Curitiba • PR<br>
			80420-170 • Brasil<br>
			Fone (55-41) 3016-1212&nbsp;</span></p>
			</div>
			</div>';

		$invoiceData = $this->_getNegociationInvoiceByIndex($negociation_id, $invoice_index);
		$invoiceUser = $this->getEditedUser(true, $invoiceData['user_id']);

		if (!$invoiceUser) {
			return false;
		}

		$s_data_vencimento = $invoiceData['data_vencimento'];

		if (!empty($s_data_vencimento)) {
			$mes_boleto = intval(date('m', strtotime($s_data_vencimento)));
		} else {
			$mes_boleto = intval(date('m'));
		}

		$a_meses = array(
				1  => 'Janeiro',
				2  => 'Fevereiro',
				3  => 'março',
				4  => 'Abril',
				5  => 'Maio',
				6  => 'Junho',
				7  => 'Julho',
				8  => 'Agosto',
				9  => 'Setembro',
				10 => 'outubro',
				11 => 'Novembro',
				12 => 'Dezembro'
		);

		$search = array(
				'{$USERNAME}',
				'{$MONTH}',
				'{$ACCESS_LINK}'
		);

		/*
		$repl = array(
				$invoiceUser->user['name'] . ' ' . $invoiceUser->user['surname'],
				$a_meses[$mes_boleto],
				sprintf("%sstudent.php?ctg=module&op=module_xpay&action=do_payment&negociation_id=%s&invoice_index=%s", G_SERVERNAME, $negociation_id, $invoice_index)
		);
		*/
		$link = sprintf("ctg=module&op=module_xpay&action=do_payment&negociation_id=%s&invoice_index=%s&do_direct=1", $negociation_id, $invoice_index);
		$oneWeek = new DateInterval("P1W");
		$today = new DateTime("today");

		$repl = array(
				$invoiceUser->user['name'] . ' ' . $invoiceUser->user['surname'],
				$a_meses[$mes_boleto],
				$this->createDirectAccessLink($invoiceUser->user['login'], "student", $link, $today->add($oneWeek))
		);
		$body = str_replace($search, $repl, $bodyTemplate);

		$my_email 	= "financeiro@sysclass.com";
		$my_pass	= 'pl!kua?#!*]]i$ooe1';
		$user_mail = $invoiceUser->user['email'];
//		$user_mail = "andre@kucaniz.com";

		$subject = 'Boleto ULT';

		$header = array (
			'From'                   	=> $my_email,
			'Reply-To'			=> "fin@americas.com.br",
			'To'                        => $user_mail,
			'Subject'                   => $subject,
			'Content-type'             	=> 'text/html;charset="UTF-8"',                       // if content-type is text/html, the message cannot be received by mail clients for Registration content
			'Content-Transfer-Encoding' => '7bit'
		);

		$smtp = Mail::factory('smtp', array(
				'auth'      => true,
				'host'      => "localhost",
				'password'  => $my_pass,
				'port'      => 25,
				'username'  => $my_email,
				'timeout'   => $GLOBALS['configuration']['smtp_timeout'],
				'persist' => true
			)
		);

		//$smtp->debug = true;

		$status = $smtp -> send($user_mail, $header, $body);
	        $status = $smtp -> send("fin@americas.com.br", $header, $body);
        	//$status = $smtp -> send("andre@ult.com.br", $header, $body);

		if ($status && !is_null($send_id)) {
			eF_deleteTableData(
					"module_xpay_to_send_list_item",
					sprintf("send_id = %s AND negociation_id = %s AND invoice_index = %s", $send_id, $negociation_id, $invoice_index)
			);
		}

		// ok, delete from queue, log entry
		$sentLOG = array(
			'user_id' 			=> $invoiceData['user_id'],
			'negociation_id'	=> $negociation_id,
			'invoice_index'		=> $invoice_index,
			'email'				=> $user_mail,
			'sent'				=> $status ? 1 : 0
		);

		eF_insertTableData("module_xpay_sent_invoices_log", $sentLOG);

		return $sentLOG;
	}
	public function createDirectAccessLink($userLogin, $userType, $query, $expires = false)
	{
		$hash = $this->createDirectAccessHash($userLogin, $userType, $query, $expires);

		return $url = sprintf(
			"%sservices/dl/?%s&_chk=%s", G_SERVERNAME, $query, $hash
		);
	}
	private function createDirectAccessHash($userLogin, $userType, $query, $expires = false)
	{
		$fields = array();

		$fields['user_login'] = $userLogin;
		$fields['user_type'] = $userType;
		$fields['query'] = $query;

		if (is_object($expires)) {
			$fields['expires'] = $expires->format("Y-m-d");
		}
		if (function_exists("sha1")) {
			$fields['hash'] = sha1(mt_rand() . implode(":", $fields));
		} else {
			$fields['hash'] = md5(mt_rand() . implode(":", $fields));
		}

		eF_insertTableData(
			"service_direct_link_hash",
			$fields
		);
		return $fields['hash'];
	}
	public function _asCurrency($rawValue)
	{
		global $CURRENCYSYMBOLS;
		$decimal_sep	= isset($GLOBALS['configuration']["decimal_point"]) ? $GLOBALS['configuration']["decimal_point"] : '.';
		$thousando_sep 	= isset($GLOBALS['configuration']["thousands_sep"]) ? $GLOBALS['configuration']["thousands_sep"] : '';

		$currencySymbol = $CURRENCYSYMBOLS[$GLOBALS['configuration']["currency"]];

		return $currencySymbol . " " . number_format($rawValue, 2, $decimal_sep, $thousando_sep);
	}
	public function _module10($num)
	{
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
			foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v) {
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
	/* INVESTIGATE FUNCTIONS */
	private function _getUserDebtDays($user = null)
	{
		// RETURN A ARRAY OF timestamp DEBTS, ONE PER INVOICE
		if (is_array($user)) {
			$user_id = $user['id'];
		} elseif (is_object($user)) {
			$user_id = $user->user['id'];
		} elseif (is_null($user)) {
			$user = $this->getCurrentUser();
			$user_id = $user->user['id'];
		} else {
			return false;
		}
		$debtTimes = ef_getTableData(
			"module_xpay_invoices inv LEFT JOIN module_xpay_course_negociation nego ON (inv.negociation_id = nego.id)",
			"negociation_id, invoice_id, UNIX_TIMESTAMP() - UNIX_TIMESTAMP(inv.data_vencimento) as debt_time",
			sprintf("nego.user_id = %d", $user_id)
		);

		return $debtTimes;
	}
	public function isUserInDebt($user = null)
	{
		$debtTimes = $this->_getUserDebtDays($user);

		$inDebt = false;
		foreach ($debtTimes as $debt) {
			// IF debt_time FIELD IS POSITIVE, THEN IS DEBT
			if ($debt['debt_time'] > 0) {
				$inDebt = true;
				break;
			}
		}
		return $inDebt;
	}
	/* TO_REVISE FUNCTIONS */
	private function _createUserDefaultStatement($persist = true, $is_simulation = false)
	{
		// GET ALL DEBITS
		// GET ALL PAYMENTS
		// GET IES, COURSE, CLASS, ETC... RULES.
		if (!($editUser = $this->getEditedUser())) {
			return false;
		}
		if (!($editCourse = $this->getEditedCourse()) && !($editLesson = $this->getEditedLesson())) {
			return false;
		}

		if ($editCourse) {
			$entify = $editCourse->course;
			$entify['type'] = 'course';
		}
		if ($editLesson) {
			$entify = $editLesson->lesson;
			$entify['type'] = 'lesson';
		}

		//$negociation_index = $_GET['negociation_index'];

		if (!empty($_GET['negociation_index'])) {
			$hasNegociation = ef_countTableData(
					"module_xpay_course_negociation",
					"negociation_index",
					sprintf("user_id = %d AND %s_id = %d AND negociation_index = %d",
							$editUser->user['id'],
							$entify['type'],
							$entify['id'],
							$_GET['negociation_index']
					)
			);

			if ($hasNegociation[0]['count'] == 0) {
				$negociation_index = $_GET['negociation_index'];
			}
		}

		if (!isset($negociation_index)) {
			$negociationIndexNew = ef_getTableData(
					"module_xpay_course_negociation",
					"IFNULL(MAX(negociation_index) + 1, 1) as new_index",
					sprintf("user_id = %d AND %s_id = %d",
							$editUser->user['id'],
							$entify['type'],
							$entify['id']
					)
			);

			$negociation_index = $negociationIndexNew[0]['new_index'];
		}

		$negociationData =  array(
				'timestamp'				=> time(),
				'user_id'				=> $editUser->user['id'],
				$entify['type'] . '_id'	=> $entify['id'],
				'registration_tax'		=> 0,
				'parcelas'				=> 1, // GET COURSE DEFAULTS
				'negociation_index'		=> $negociation_index,
				'active'				=> 1,
				'is_simulation'			=> ($is_simulation) ? 1 : 0,
				'vencimento_1_parcela'	=> null
		);

		if ($persist) {
			$negociationID = ef_insertTableData(
				"module_xpay_course_negociation",
				$negociationData
			);
		}
		return $negociationData;
	}
	/*
	private function _createUserDefaultInvoices($negociationID)
	{
		$negociationData = $this->_getNegociationById($negociationID);

		if (count($negociationData) == 0) {
			return false;
		}
		if (count($negociationData['invoices']) > 0) {
			return false;
		}

		if ($negociationData['base_price'] <= 0) {
			return false;
		}

		// CREATE DEFAULT INVOICES BY INDIVIDUAL, GROUP, CLASS, COURSE, POLO, IES, ETC..
		// ... OR BY SCOPE, BASED ON SCOPE PRECEDENCE
		// IF CANNOT FOUND DEFAULT INVOICES, CREATE A ONE BIG INVOICE

		$this->_createInvoice(
				$negociationData['id'],
				$negociationData['full_price'],
				date_create_from_format("d/m/Y", "20/04/2012")
		);
	}
	*/
	/* MODEL FUNCTIONS */
	public function getSubmodules()
	{
		if (is_null(self::$subModules)) {
			self::$subModules = array();
			
			$modulesDB = eF_getTableData("modules", "*", "active=1");
			$currentUser = $this->getCurrentUser();
			
			// Get all modules enabled
			foreach ($modulesDB as $module) {
				$folder = $module['position'];
				$className = $module['className'];
				$interfaces = (class_implements($className));
				
				if (array_key_exists("IxPaySubmodule", $interfaces)) {
					if (is_file(G_MODULESPATH . $folder . "/" . $className .  ".class.php")) {
						require_once G_MODULESPATH . $folder . "/" . $className . ".class.php";
					}
					
					$object = new $className("", $folder);
					
					self::$subModules[strtoupper($object->getName())] = $object->setParent($this);
				}
			}
		}
		return self::$subModules;
	}
	public function getPaymentById($payment_id)
	{
		if (eF_checkParameter($payment_id, 'id')) {
			// RETURS ONE REGISTER PER INVOICE.....
			$paymentResult = eF_getTableData(
				"`module_pagamento` pag,
				`module_pagamento_types` pag_typ,
				/* `module_pagamento_types_details` pag_typ_det, */
				`module_pagamento_invoices` inv,
				`module_pagamento_invoices_status` inv_stat,
				`users`
				LEFT OUTER JOIN `module_xuser` user_det ON (users.id = user_det.id)",
				"pag.payment_id, pag.enrollment_id, pag.user_id, pag.course_id, pag.vencimento, pag.data_inicio, pag.desconto, pag.parcelas, pag.payment_type_id, pag.payment_type_index_name, pag.data_registro, pag.send_to, " .
				"pag_typ.title as payment_type, /* pag_typ_det.name as payment_type_name, */ pag_typ.comments as payment_type_description, " .
				"users.login, users.name, users.surname, users.email, user_det.cpf, user_det.rg, user_det.endereco, user_det.numero, user_det.complemento, user_det.cidade, user_det.uf, user_det.cep, user_det.not_18, " .
				'inv.payment_type_id as inv_payment_type_id, inv.parcela_index, inv.invoice_id, inv.data_registro as invoice_data_registro, inv.data_vencimento, inv.valor, inv.valor_desconto, inv.status_id, inv.pago as inv_pago , inv.bloqueio as inv_bloqueio, inv_stat.descricao as status, inv.tag as invoice_tag',
				sprintf(
					"pag.payment_id = %d
					AND pag.payment_type_id = pag_typ.payment_type_id
					/*
					AND pag.payment_type_id = pag_typ_det.payment_type_id
					AND pag.payment_type_index_name = pag_typ_det.index_name
					*/
					AND pag.user_id = users.id
					AND pag.payment_id = inv.payment_id
					AND inv.status_id = inv_stat.id",
				$payment_id),
				"inv.payment_id, inv.parcela_index"
			);
			if (count($paymentResult) > 0) {
				/*
				$paymentData = array(
					'payment_id'				=> $paymentResult[0]['payment_id'],
					'user_id'					=> $paymentResult[0]['user_id'],
					'enrollment_id'				=> $paymentResult[0]['enrollment_id'],
					'data_inicio'				=> $paymentResult[0]['data_inicio'],
					'vencimento'				=> $paymentResult[0]['vencimento'],
					'desconto'					=> $paymentResult[0]['desconto'],
					'payment_type_id'			=> $paymentResult[0]['payment_type_id'],
					'payment_type'				=> $paymentResult[0]['payment_type'],
					'payment_type_index_name'	=> $paymentResult[0]['payment_type_index_name'],
					'payment_type_name'			=> $paymentResult[0]['payment_type_name'],
					'payment_type_description'	=> $paymentResult[0]['payment_type_description'],
					'data_registro'				=> $paymentResult[0]['data_registro'],

					'usuario'			=> array(
						'id'			=>  $paymentResult[0]['user_id'],
						'login' 		=>  $paymentResult[0]['login'],
						'name' 			=>  $paymentResult[0]['name'],
						'surname' 		=>  $paymentResult[0]['surname'],
						'email'			=>  $paymentResult[0]['email'],
						'endereco' 		=>  $paymentResult[0]['endereco'],
						'numero' 		=>  $paymentResult[0]['numero'],
						'complemento'	=>  $paymentResult[0]['complemento'],
						'cidade' 		=>  $paymentResult[0]['cidade'],
						'uf' 			=>  $paymentResult[0]['uf'],
						'cep'			=>  $paymentResult[0]['cep']
					),
					'invoices'			=> array(),
					//'current_invoice'	=> null,
					'next_invoice'		=> 1
				);
				*/
				$paymentData = array(
					'payment_id'				=> $paymentResult[0]['payment_id'],
					'user_id'					=> $paymentResult[0]['user_id'],
					'course_id'					=> $paymentResult[0]['course_id'],
					'enrollment_id'				=> $paymentResult[0]['enrollment_id'],
					'data_inicio'				=> $paymentResult[0]['data_inicio'],
					'vencimento'				=> $paymentResult[0]['vencimento'],
					'desconto'					=> $paymentResult[0]['desconto'],
					'parcelas'					=> $paymentResult[0]['parcelas'],
					'payment_type_id'			=> $paymentResult[0]['payment_type_id'],
					'payment_type'				=> $paymentResult[0]['payment_type'],
					'payment_type_index_name'	=> $paymentResult[0]['payment_type_index_name'],
					'payment_type_name'			=> $paymentResult[0]['payment_type_name'],
					'payment_type_description'	=> $paymentResult[0]['payment_type_description'],
					'data_registro'				=> $paymentResult[0]['data_registro'],
					'send_to'					=> $paymentResult[0]['send_to'],
					'invoices'			=> array(),
					//'current_invoice'	=> null,
					'next_invoice'		=> 1
				);

				$paymentData['usuario']	= array(
					'has_minor'		=> 	$paymentResult[0]['not_18'] == 1,
					'id'			=>  $paymentResult[0]['user_id'],
					'login' 		=>  $paymentResult[0]['login'],
					'name' 			=>  $paymentResult[0]['name'],
					'surname' 		=>  $paymentResult[0]['surname'],
					'email' 		=>  $paymentResult[0]['email'],
					'endereco' 		=>  $paymentResult[0]['endereco'],
					'numero' 		=>  $paymentResult[0]['numero'],
					'complemento'	=>  $paymentResult[0]['complemento'],
					'cidade' 		=>  $paymentResult[0]['cidade'],
					'uf' 			=>  $paymentResult[0]['uf'],
					'cep'			=>  $paymentResult[0]['cep'],
					'cpf'			=>  $paymentResult[0]['cpf'],
					'rg'			=>  $paymentResult[0]['rg']
				);

				if ($paymentData['send_to'] == 'parent' && $paymentResult[0]['not_18'] == 1) {
					// BUSCAR RESPONSÁVEL
					$responsibleData 		= eF_getTableData("module_xuser_responsible", "*", "type='parent' AND id = ".  $paymentResult[0]['user_id']);
					$paymentData['cliente']	= $responsibleData[0];
					/*
					$paymentData['minor']					= $paymentData['usuario'];
					$paymentData['cliente']['has_minor']	= true;

					$paymentData['cliente']['surname']		= $responsibleData[0]['surname'];
					$paymentData['cliente']['email'] 		= $responsibleData[0]['email'];
					*/
				} elseif ($paymentData['send_to'] == 'financial') {
					$responsibleData 		= eF_getTableData("module_xuser_responsible", "*", "type='financial' AND id = ".  $paymentResult[0]['user_id']);
					$paymentData['cliente']	= $responsibleData[0];
				} else {
					$paymentData['cliente']	= $paymentData['usuario'];
				}

				if ($paymentResult[0]['not_18'] == 1) {

				}
				$isNextInvoice = false;
				foreach ($paymentResult as $details) {

					$paymentData['invoices'][$details['parcela_index']] = array(
						'payment_id'		=> $details['payment_id'],
						'invoice_id'		=> $details['invoice_id'],
						'payment_type_id'	=> $details['inv_payment_type_id'],
						'parcela_index'		=> $details['parcela_index'],
						'data_registro'		=> $details['invoice_data_registro'],
						'data_vencimento' 	=> $details['data_vencimento'],
						'valor'				=> $details['valor'],
						'valor_desconto' 	=> $details['valor_desconto'],
						'status_id'			=> $details['status_id'],
						'status'			=> $details['status'],
						'pago'				=> $details['inv_pago'],
						'bloqueio'			=> $details['inv_bloqueio'],
						'tag'				=> json_decode($details['invoice_tag'], true)
					);
					/*
					if ($isNextInvoice) {
						// IF CURRENT IS 1, IS BECAUSE THE FISRT PAYMENT IS NOT PAID, SO IS THE NEXT TOO.
						if ($paymentData['current_invoice'] != 1) {
							$paymentData['next_invoice'] = $details['parcela_index'];
						}
						$isNextInvoice = false;
					}
					if ($details['status_id'] == 2) {
						$paymentData['current_invoice'] = $details['parcela_index'];
						$isNextInvoice = true;
					}
					*/
				}
				// ARRAY BASE => 1
				for ($i = count($paymentData['invoices']); $i > 1; $i--) {
					if (
						in_array($paymentData['invoices'][$i]['status_id'], array(1)) &&
						$paymentData['invoices'][$i]['bloqueio'] == 0 &&
						$paymentData['invoices'][$i]['pago'] == 0
					) {
						$paymentData['next_invoice'] = $i;
					}
				}
				// INJECT DATA IN TOKENS IN "payment_type_description"
				$paymentData['payment_type_description_tpl'] = $paymentData['payment_type_description'];
				$paymentData['payment_type_description'] = $this->substituteStringTokens($paymentData['payment_type_description'], $paymentData);

				return $paymentData;
			}
		}
		// RETURN FALSE IF RECORD NOT EXISTS
		return false;
	}
	private function substituteStringTokens($descriptionString, $paymentData)
	{
		$sear = array(
			"[_PAGAMENTO_MATRICULA]",
			"[_PAGAMENTO_PARCELAS]",
			"[_PAGAMENTO_MENSALIDADE]",
			"[_PAGAMENTO_VENCIMENTO]",
			"[_PAGAMENTO_DESCONTO]"
		);
		$repl = array(
			'R$ '. number_format($paymentData['invoices'][1]['valor'], 2, ',', '.'),
			sprintf("%02d", $paymentData['parcelas']),
			'R$ '. number_format(floatval($paymentData['invoices'][$paymentData['next_invoice']]['valor']), 2, ',', '.'),
			sprintf("%02d", $paymentData['vencimento']),
			sprintf("%0.2f%%", $paymentData['desconto'])
		);

		return str_replace($sear, $repl, $descriptionString);
	}
	public function getPayments($filter = null)
	{
		$fields = array(
				"DISTINCT pag.payment_id",
				"pag.user_id",
				"pag.data_registro",
				"pag.send_to",
				"pag.course_id",
				"users.login",
				"users.name",
				"users.surname",
				"(SELECT SUM(inv.valor)
				FROM `module_pagamento_invoices` inv
				WHERE pag.payment_id = inv.payment_id
		) as total_valor",
				"(SELECT SUM(inv.valor)
				FROM `module_pagamento_invoices` inv
				WHERE pag.payment_id = inv.payment_id AND inv.status_id = 3
		) as total_pago",
				"(SELECT parcela_index
				FROM `module_pagamento_invoices` inv
				WHERE pag.payment_id = inv.payment_id AND inv.bloqueio = 0 AND inv.pago = 0
				ORDER BY inv.parcela_index LIMIT 1
				) as next_invoice",
				/*
				"(SELECT data_vencimento
				FROM `module_pagamento_invoices` inv
				WHERE pag.payment_id = inv.payment_id AND inv.status_id = 2
				ORDER BY inv.parcela_index LIMIT 1
		) as proximo_vencimento",

				"(SELECT COUNT(inv.parcela_index)
				FROM `module_pagamento_invoices` inv
				WHERE pag.payment_id = inv.payment_id
		) as total_parcelas",

				"(SELECT COUNT(inv.parcela_index)
				FROM `module_pagamento_invoices` inv
				WHERE pag.payment_id = inv.payment_id AND inv.pago > 0
		) as total_parcelas_pagas"
			*/
		);
		/*
		 prepareGetTableData(
		 		"`module_pagamento` pag, `users`",
		 		implode(', ', $fields),
		 		sprintf("pag.user_id = users.id", $payment_id) . (!is_null($filter) ? ' AND ' . $filter : "")
		 );
		*/

		$paymentDbResult = eF_getTableData(
				"`module_pagamento` pag, `users`",
				implode(', ', $fields),
				sprintf("pag.user_id = users.id", $payment_id) . (!is_null($filter) ? ' AND ' . $filter : "")
		);
		$paymentResult = array();
		foreach ($paymentDbResult as $item) {
			$item['total_pago'] = is_null($item['total_pago']) ? 0 : $item['total_pago'];
			//$item['total_parcelas_pagas'] = is_null($item['total_parcelas_pagas']) ? 0 : $item['total_parcelas_pagas'];

			$item['total_saldo']	= $item['total_valor']	- $item['total_pago'];

			$paymentResult[] = $item;
		}
		return $paymentResult;
	}
	public function getPaymentsByUserId($userID, $completeData = true)
	{
		if (eF_checkParameter($userID, 'id')) {
			$result = array();

			if (!$completeData) {
				return $this->getPayments('user_id = ' . $userID);
			}

			$paymentIDs = eF_getTableData("module_pagamento", 'payment_id', 'user_id = ' . $userID);

			foreach ($paymentIDs as $payment) {

				$result[] = $this->getPaymentById($payment['payment_id']);
			}
			return $result;
		}
		return false;
	}
	public function getInvoiceById($payment_id, $invoice_id)
	{
		if (
			eF_checkParameter($payment_id, 'id') &&
			eF_checkParameter($invoice_id, 'id')
		) {
			$result = eF_getTableData(
				"module_pagamento_invoices inv
				LEFT JOIN module_pagamento pag ON (inv.payment_id = pag.payment_id)
				LEFT JOIN courses c ON (pag.course_id = c.id)
				",
				"inv.*, c.name as course",
				sprintf("
					inv.payment_id = %d AND inv.parcela_index = %d",
					$payment_id,
					$invoice_id
				)
			);

			if (count($result) > 0) {
				$invoice = $result[0];

				return $this->calculateInvoiceDetails($invoice);
			}
		}
		return false;
	}
	public function createInvoiceID($payment_id, $parcela_id)
	{
		$i = 0;
		do {
			$nosso_numero_sem_DV = sprintf(self::INVOICE_ID_TEMPLATE_SEM_DV, substr($payment_id, 0, 4), substr($parcela_id, 0, 2), $i);
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
			$i++;
		} while ( $totalcount > 0 );
		return $nosso_numero;
	}
	private function generateModule10($num)
	{
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
			foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v) {
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
	
	public function insertInvoicePayment($negociation_id, $invoice_index, $paid, $method, $transactionID, $data) {
		$countPaid = ef_getTableData(
			"module_xpay_paid_items",
			"id",
			sprintf("transaction_id = '%s' AND method_id = '%s'", $transactionID, $method)
		);
		
		if (count($countPaid) == 0) {
	
			$paid_items = array(
				'transaction_id'	=> $transactionID,
				'method_id' 		=> $method,
				'paid' 				=> $paid,
				'start_timestamp' 	=> strtotime($data)
			);
	
			$paidID = ef_insertTableData(
				"module_xpay_paid_items",
				$paid_items
			);
		} else { // JUST UPDATE VALUE
			$paidID = $countPaid[0]['id'];
					
			$paid_items = array(
				'paid' 			=> $paid,
			);
			ef_updateTableData(
				"module_xpay_paid_items",
				$paid_items,
				sprintf("id = %d", $paidID)
			);
					
		}

		$negociation = $this->_getNegociationByContraints(array(
			'negociation_id'	=> $negociation_id
		));

		if (count($negociation) > 0) {
			// ENCONTROU A NEGOCIAÇÃO
			//var_dump($negociation);
			$paidInvoice = $this->_getNegociationInvoiceByIndex($negociation['id'], $invoice_index);
	
			if (count($paidInvoice) > 0) {
				// ENCONTROU A FATURA
				$item = array(
					'negociation_id'	=> $negociation['id'],
					'invoice_index'		=> $invoice_index,
					'paid_id'			=> $paidID
				);
	
				if ($paid < $paidInvoice['valor'] && !$config['partial_payment']) {
					$item['full_value']	= $paidInvoice['valor'];
				}
						
				$countInv2Paid = ef_getTableData(
					"module_xpay_invoices_to_paid",
					"negociation_id",
					sprintf("negociation_id = %d AND invoice_index = %d AND paid_id = %d",
							$negociation['id'], $invoice_index, $paidID)
				);
						
				if (count($countInv2Paid) == 0) {
					ef_insertTableData(
					"module_xpay_invoices_to_paid",
					$item
				);
				} else {
				}
			}
		} else {
			return false;
		}
		
		return true;
		
	}
	
	
	/* OLD-STYLE FUNCTIONS */
	public function getCenterLinkInfo()
	{
		$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");
		if (
				$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
				$xuserModule->getExtendedTypeID($currentUser) == "financier"
		) {
			return array(
				'title' => __XPAY_CONTROL_PANEL_TITLE,
				'image' => $this -> moduleBaseDir . 'images/pagamento.png',
				'link'  => $this -> moduleBaseUrl,
				'class' => 'cash'
			);
		}
	}
	public function getSidebarLinkInfo()
	{
		$currentUser = $this -> getCurrentUser();

		$xuserModule = $this->loadModule("xuser");
		if (
				$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
				$xuserModule->getExtendedTypeID($currentUser) == "financier"
		) {

			$link_of_menu_system = array (array ('id' => 'module_xpay_link_id1',
					'title' => __XPAY_MENU_TITLE,
					//'image' => $this -> moduleBaseDir.'images/pagamento',
					//'_magesterExtensions' => '1',
					'link'  => $this -> moduleBaseUrl));
			return array ( "system" => $link_of_menu_system);
		}
	}
}
