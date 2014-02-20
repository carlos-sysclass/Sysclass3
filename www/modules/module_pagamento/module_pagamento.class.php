<?php
/*

* Class defining the new module

* Its name should be the same as the one provided in the module.xml file

*/
class module_pagamento extends MagesterExtendedModule
{

	/** @todo: pensar em criar uma classe-pai para todos os sub-modulos... colocando as funções obrigatórias nela (abstratas) */
	const CREATE_PAYMENT_TYPE		= 'create_payment_type';
	const EDIT_PAYMENT_TYPE			= 'edit_payment_type';
	const DELETE_PAYMENT_TYPE		= 'delete_payment_type';
	const GET_PAYMENT_TYPES			= 'get_payment_types';

	const SAVE_USER_PAYMENT			= 'save_user_payment';

	const SAVE_USER_INVOICE			= 'save_user_invoice';

	const UPDATE_INVOICE			= "update_invoice";

	const SEND_INVOICES				= "send_invoices";

	const GET_INVOICE				= 'get_invoice';

	const GET_PAYMENTS				= 'get_payments';

	const VIEW_INVOICES_STATUS		= 'view_invoices_status';
	const VIEW_INVOICES_PENDING		= 'view_invoices_pending';

	const UPDATE_INVOICES_STATUS	= 'update_invoices_status';

	const REGISTER_ENROLLMENT_PAYMENT	= 'register_enrollment_payment';
	const REGISTER_MONTHLY_INVOICES		= 'register_monthly_invoices';

	const VIEW_SENDED_INVOICES_LIST		= 'view_sended_invoices_list';
	const VIEW_TO_SEND_INVOICES_LIST	= 'view_to_send_invoices_list';

	const INSERT_INTO_SEND_LIST			= 'insert_into_send_list';
	const REMOVE_FROM_SEND_LIST			= 'remove_from_send_list';

	// REVISAR!!!!
	const SHOW = 'show';
	const PENDING_PAYMENTS			= 'pending_payments';

	const _XPAYMENT_AUTOPAY		= 2;

	//const GET_FATURA				= 'get_fatura';

	/* INVOICE FIXED INDEXES (CANCELATION, NEGOCIATION, ETC.. */
	const CANCEL_INVOICE_INDEX		= 99;

	//protected $submodules = null;
	protected $avaliableSubModules = array();

	public static function getInstance()
	{
		$currentUser = self::getCurrentUser();

		$defined_moduleBaseUrl 	= G_SERVERNAME . $currentUser -> getRole() . ".php" . "?ctg=module&op=" . __CLASS__;
		$defined_moduleFolder 	= __CLASS__;

		return new self($defined_moduleBaseUrl , $defined_moduleFolder);
	}

	public function getName()
	{
		return "PAGAMENTO";
	}

	public function getPermittedRoles()
	{
		return array("administrator", "student");
		//return array_values(array("administrator" => "administrator") + array_keys(MagesterLessonUser :: getStudentRoles()));
	}

	public function getDefaultAction()
	{
		return self::VIEW_INVOICES_STATUS;
	}

	/* MODULE BLOCKS */
	public function loadDueInvoicesBlock($blockIndex = null)
	{
		$userID = $this->getCurrentUser()->user['id'];

		$payments = $this->getPaymentsByUserId($userID);

		// MOSTRAR SOMENTE EMITIDOS
		foreach ($payments as $pay_key => $payment) {
			foreach ($payment['invoices'] as $inv_key => $invoice) {
				if ($invoice['status_id'] != 2 || $invoice['bloqueio'] == 1 || strtotime($invoice['data_vencimento']) > time() + (10 * 86400)) {
					unset($payments[$pay_key]['invoices'][$inv_key]);
				}
			}
		}

		if (count($payment['invoices']) > 0) {
			$smarty = $this -> getSmartyVar();
			$smarty -> assign ("T_PAYMENT", $payments[0]);

			$this->getParent()->appendTemplate(array(
			   	'title'			=> __PAGAMENTO_DASHBOARD_LIST,
			   	'template'		=> $this->moduleBaseDir . 'templates/blocks/xpayment.due.invoices.tpl',
			   	'contentclass'	=> 'blockContents'
	    	), $blockIndex);

	    	$this->assignSmartyModuleVariables();

			return true;
		}

		return false;
	}

	/* MODULE ACTIONS */
	public function generateInvoiceLinkAction($enrollment_id_or_token = null, $fields)
	{
		error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

		$enrollmentModule = $this->loadModule('xenrollment');

		if (sC_checkParameter($enrollment_id_or_token, 'id')) {
			$enrollmentData = $enrollmentModule->getEnrollmentById($enrollment_id_or_token);
		} else {
			$enrollmentData = $enrollmentModule->getEnrollmentByToken($enrollment_id_or_token);
		}

		if (!$enrollmentData) {
			return false;
		}

		$paymentData = $this->getPaymentById($enrollmentData['payment_id']);

		$invoice_index = isset($fields['parcela_index']) ? $fields['parcela_index'] : 1;

		// GET NEXT INVOICE AND MODULE TO PROCESS
		$nextInvoice = $paymentData['invoices'][$invoice_index];
		$paymentType = $this->getPaymentTypeById($nextInvoice['payment_type_id']);
		/*
		var_dump($enrollmentData);
		var_dump($paymentData);
		var_dump($paymentData['invoices']);
		var_dump($nextInvoice['payment_type_id']);
		var_dump($paymentType);
		exit;
		*/
		$subModule = $this->getSubModule($paymentType['module_class_name']);

		$result = $subModule->processAction(
			self::GET_INVOICE,
			array_merge(
				$paymentData,
				array('invoice_index' => $invoice_index)
			)
		);

        $invoice_sha_access = sha1(serialize($result));

		if (
			$this->updateInvoiceById($enrollmentData['payment_id'], $invoice_index,
				array(
					'invoices_sha_access' => $invoice_sha_access,
					'status_id' => 2
				)
			)
		) {

	        $filename = sprintf(
				G_ROOTPATH . 'boletos/%s.html',
				$invoice_sha_access
			);

			$result = file_put_contents($filename, $result['html']);

			$invoice_link = G_SERVERNAME . 'boleto.php?id=' . $invoice_sha_access;

			return array(
				'payment_id'	=> $enrollmentData['payment_id'],
				'parcela_index'	=> $invoice_index,
				'link'			=> $invoice_link
			);
		} else {
			return array(
				'payment_id'	=> $enrollmentData['payment_id'],
				'parcela_index'	=> $invoice_index,
				'link'			=> 'error'
			);
		}
	}
	public function registerEnrollmentPaymentAction($enrollment_id_or_token = null, $fields)
	{
		$enrollmentModule = $this->loadModule('xenrollment');

		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (sC_checkParameter($enrollment_id_or_token, 'id')) {
			$enrollmentData = $enrollmentModule->getEnrollmentById($enrollment_id_or_token);
		} elseif (!empty($enrollment_id_or_token)) {
			$enrollmentData = $enrollmentModule->getEnrollmentByToken($enrollment_id_or_token);
		} elseif (sC_checkParameter($fields['enrollment_id'], 'id')) {
			$enrollmentData = $enrollmentModule->getEnrollmentById($fields['enrollment_id']);
		}

		$xenrollmentModule = $this->loadModule('xenrollment');

		if (!$enrollmentData) {
			// CHECK ENROLL BY USER AND COURSE_ID

			$user = $this->getEditedUser();
			$courses = $user->getUserCourses(array('condition' => 'c.price > 0 AND c.active = 1'));

			$updateEnrollPayment = false;

			foreach ($courses as $course) {
				if ( ($enrollmentData = $xenrollmentModule->getEnrollmentByUserAndCourseID($user->user['id'], $course->course['id']) ) == FALSE) {
					// REGISTER MATRICULATION
					$enrollmentData = $xenrollmentModule->openXenrollmentAction();

					$enrollmentData = $xenrollmentModule->updateXenrollmentAction(
						$enrollmentData['token'],
						array(
							'users_id'		=> $user->user['id'],
							'courses_id'	=>  $course->course['id']
						)
					);

					$updateEnrollPayment = true;
				}
			}
		}
		$paymentDefaults = $this->getPaymentDefaults($enrollmentData['ies_id'], $enrollmentData['courses_id'], $enrollmentData['users_id']);
		$payment_data = array_merge($paymentDefaults, $fields, array('enrollment_id' => $enrollmentData['id']));

		// INSERT PAYMENT
		// CLEARING NO NECESSARY FIELDS FOR INSERT
		$payKeys = array("enrollment_id", "user_id", "vencimento", "desconto", "payment_type_id", "data_inicio", "emitir_vencidos", "course_id", "parcelas");
		foreach ($payment_data as $payKey => $payData) {
			if (in_array($payKey, $payKeys)) {
				$paymentToInsert[$payKey] = $payData;
			}
		}

		$payment_id = sC_insertTableData("module_pagamento", $paymentToInsert);

		// LINK WITH COURSES
		/** @todo IGNORE THIS LINKED DATA... LINK IS IN xenrollment MODULE */
		$insertCourses = array();
		//foreach ($coursesIDs as $courseID) {
			$insertCourses[] = array(
				'payment_id'	=> $payment_id,
				'course_id'		=> $enrollmentData['courses_id']
			);
		//}
		$result = sC_insertTableDataMultiple("module_pagamento_courses_to_payments", $insertCourses);

		// GET USER LOGIN
		$userModule = $this->loadModule('xuser');

		$userObject = $userModule->getUserById($enrollmentData['users_id']);

		// CREATE PAYMENT INVOICES
//		$userObject = MagesterUserFactory::factory($user);

		$userCourses = $userObject->getUserCourses(array('return_objects' => false));

   		$courseValues = array();
   		$coursematriculas = array();

   		foreach ($userCourses as $userCourse) {
   			if (MagesterUser :: isStudentRole($userCourse['user_type']) && $userCourse['id'] == $enrollmentData['courses_id']) {
   				if ($userCourse['course_type'] == 'Via Web') {
   					$courseValues[$userCourse['id']] = $userCourse['price_web'];
   				} elseif ($userCourse['course_type'] == 'Presencial') {
   					$courseValues[$userCourse['id']] = $userCourse['price_presencial'];
				} elseif ($userCourse['course_type'] == 'professional') {
					$courseValues[$userCourse['id']] = $userCourse['price_professional'];
				} elseif ($userCourse['course_type'] == 'vip') {
					$courseValues[$userCourse['id']] = $userCourse['price_vip'];
   				} else {
   					$courseValues[$userCourse['id']] = $userCourse['price'];
   				}
   				if ($userCourse['enable_registration'] == 1) {
   					if ($userCourse['price_registration'] == 0) {
   						$coursematriculas[$userCourse['id']] = $courseValues[$userCourse['id']] / $payment_parcelas;
   					} else {
   						$coursematriculas[$userCourse['id']] = $userCourse['price_registration'];
   					}
   				} else {
   					$coursematriculas[$userCourse['id']] = 0;
   				}
   			}
   		}

   		$payment_parcelas = $payment_data['parcelas'];

   		$totalCobrado = array_sum($courseValues);

   		$valor_matricula = array_sum($coursematriculas);

   		$totalCobrado = $totalCobrado - $valor_matricula;
   		if ($payment_parcelas == 1) {
   			$valor_mensalidade = $totalCobrado;
   		} else {
   			$valor_mensalidade = $totalCobrado / ($payment_parcelas - 1);
   		}
   		$valor_mensalidade_desconto = $valor_mensalidade - ($valor_mensalidade * ($payment_data['desconto'] / 100));

   		$invoices = array();

   		for ($index = 1; $index <= $payment_parcelas; $index++) {

   			$invoiceInsert = array(
   				'payment_id' 		=> $payment_id,
				'parcela_index' 	=> $index,
				'payment_type_id' 	=> $payment_data['payment_type_id'],
				'invoice_id' 		=> "",
				'valor_desconto'	=> ($index == 1 && $userCourse['enable_registration'] == 1) ? $valor_matricula : $valor_mensalidade_desconto,
				'valor'				=> ($index == 1 && $userCourse['enable_registration'] == 1) ? $valor_matricula : $valor_mensalidade,
   				'status_id'			=> 1,
   				'bloqueio'			=> 0
   			);

   			if ($index == 1 && $userCourse['enable_registration'] == 1) {
   				$data_vencimento = null;
   				$invoiceInsert['status_id'] = 1;
   				$invoiceInsert['data_vencimento'] = null;
   			} else {
   				/** @warning !!!INVOICES WILL BE CREATED AFTER REGISTRATION PAYMENT!!! */
   				/*
   				$dataInicioCobranca = strtotime($payment_data['data_inicio']);

   				$data_vencimento = mktime(
   					0, 0, 0,
   					date('m', $dataInicioCobranca) + ($index - 2),
   					$payment_data['vencimento'],
   					date('Y', $dataInicioCobranca)
   				);

   				$invoiceInsert['data_vencimento']	= date('Y-m-d H:i:s', $data_vencimento);
   				if ($payment_data['emitir_vencidos'] == 0 && $data_vencimento < time()) {
   					$invoiceInsert['bloqueio'] = 1;
   					//$invoiceInsert['status_id'] = 5;
   				}
   				*/
   			}

   			$invoices[] = $invoiceInsert;
   			break;
   		}
   		$result = sC_insertTableDataMultiple("module_pagamento_invoices", $invoices);

   		if ($updateEnrollPayment || $enrollmentData['payment_id'] == 0) {

   			$enrollmentData = $xenrollmentModule->updateXenrollmentAction(
				$enrollmentData['token'],
				array(
					'payment_id'	=> $payment_id
				)
			);
  		}
		return array(
			"status"	=> 'ok',
    		"id"	=> $payment_id,
    		"data"	=> $this->getPaymentById($payment_id),
			"message"		=> __XPAYMENT_USER_PAYMENT_REGISTER,
			"message_type"	=> "success"
    	);
	}
	public function registerMonthlyInvoicesAction($enrollment_id_or_token = null, $fields)
	{
		$enrollmentModule = $this->loadModule('xenrollment');

		if (is_null($fields)) {
			$fields = $_POST;
		}

		if (sC_checkParameter($enrollment_id_or_token, 'id')) {
			$enrollmentData = $enrollmentModule->getEnrollmentById($enrollment_id_or_token);
		} elseif (!empty($enrollment_id_or_token)) {
			$enrollmentData = $enrollmentModule->getEnrollmentByToken($enrollment_id_or_token);
		} elseif (sC_checkParameter($fields['enrollment_id'], 'id')) {
			$enrollmentData = $enrollmentModule->getEnrollmentById($fields['enrollment_id']);
		}

		// Checar se a matrícula esta paga
		if (!$enrollmentData) {
			// CHECK ENROLL BY USER AND COURSE_ID
			$user = $this->getEditedUser();
			$courses = $user->getUserCourses(array('condition' => 'c.price > 0 AND c.active = 1'));

			$xenrollmentModule = $this->loadModule('xenrollment');

			foreach ($courses as $course) {
				if ( ($enrollmentData = $xenrollmentModule->getEnrollmentByUserAndCourseID($user->user['id'], $course->course['id']) ) !== FALSE) {
					break;
				}
			}
		}

		if (!$enrollmentData) {
			$this->setMessageVar(__XPAYMENT_NO_ENROLL_FOUND, "failure");
		}

		$paymentDefaults = $this->getPaymentDefaults($enrollmentData['ies_id'], $enrollmentData['courses_id'], $enrollmentData['users_id']);
		$payment_data = array_merge($paymentDefaults, $fields, array('enrollment_id' => $enrollmentData['id']));

		// GET USER LOGIN
		$userModule = $this->loadModule('xuser');
		$userObject = $userModule->getUserById($enrollmentData['users_id']);

		$userCourses = $userObject->getUserCourses(array('return_objects' => false));

   		$courseValues = array();
   		$coursematriculas = array();

   		foreach ($userCourses as $userCourse) {
   			if (MagesterUser :: isStudentRole($userCourse['user_type']) && $userCourse['id'] == $enrollmentData['courses_id']) {
   				if ($userCourse['course_type'] == 'Via Web') {
   					$courseValues[$userCourse['id']] = $userCourse['price_web'];
   				} elseif ($userCourse['course_type'] == 'Presencial') {
   					$courseValues[$userCourse['id']] = $userCourse['price_presencial'];
   				} else {
   					$courseValues[$userCourse['id']] = $userCourse['price'];
   				}
   				if ($userCourse['enable_registration'] == 1) {
   					if ($userCourse['price_registration'] == 0) {
   						$coursematriculas[$userCourse['id']] = $courseValues[$userCourse['id']] / $payment_parcelas;
   					} else {
   						$coursematriculas[$userCourse['id']] = $userCourse['price_registration'];
   					}
   				} else {
   					$coursematriculas[$userCourse['id']] = 0;
   				}
   			}
   		}

   		$payment_parcelas = $payment_data['parcelas'];

   		$totalCobrado = array_sum($courseValues);

   		$valor_matricula = array_sum($coursematriculas);

   		$totalCobrado = $totalCobrado - $valor_matricula;

   		$valor_mensalidade = $totalCobrado / ($payment_parcelas - 1);
   		$valor_mensalidade_desconto = $valor_mensalidade - ($valor_mensalidade * ($payment_data['desconto'] / 100));

   		$invoices = array();

   		for ($index = 2; $index <= $payment_parcelas; $index++) {

   			$invoiceInsert = array(
   				'payment_id' 		=> $payment_data['payment_id'],
				'parcela_index' 	=> $index,
				'payment_type_id' 	=> $payment_data['payment_type_id'],
				'invoice_id' 		=> "",
				'valor_desconto'	=> ($index == 1) ? $valor_matricula : $valor_mensalidade_desconto,
				'valor'				=> ($index == 1) ? $valor_matricula : $valor_mensalidade,
   				'status_id'			=> 1,
   				'bloqueio'			=> 0
   			);

			$dataInicioCobranca = strtotime($payment_data['data_inicio']);

			$data_vencimento = mktime(
				0, 0, 0,
				date('m', $dataInicioCobranca) + ($index - 2),
				$payment_data['vencimento'],
				date('Y', $dataInicioCobranca)
			);

			$invoiceInsert['data_vencimento']	= date('Y-m-d H:i:s', $data_vencimento);
			if ($payment_data['emitir_vencidos'] == 0 && $data_vencimento < time()) {
				$invoiceInsert['bloqueio'] = 1;
			}
			// GET IF INVOICES EXISTS
			/*
			echo prepareGetTableData("module_pagamento_invoices", "payment_id",
   				sprintf(
   					"payment_id = %d AND parcela_index = %d",
   					$invoiceInsert['payment_id'],
   					$invoiceInsert['parcela_index']
   				)
   			);
			*/
   			$invoicesCount = sC_countTableData("module_pagamento_invoices", "payment_id",
   				sprintf(
   					"payment_id = %d AND parcela_index = %d",
   					$invoiceInsert['payment_id'],
   					$invoiceInsert['parcela_index']
   				)
   			);

   			if ($invoicesCount[0]['count'] > 0) {
   				sC_updateTableData("module_pagamento_invoices", $invoiceInsert, sprintf(
   					"payment_id = %d AND parcela_index = %d",
   					$invoiceInsert['payment_id'],
   					$invoiceInsert['parcela_index']
   				));
   			} else {
	   			$invoices[] = $invoiceInsert;
   			}
   		}
   		/*
   		*/
   		$result = sC_insertTableDataMultiple("module_pagamento_invoices", $invoices);

   		/*
   		if ($updateEnrollPayment) {
   			$enrollmentData = $xenrollmentModule->updateXenrollmentAction(
				$enrollmentData['token'],
				array(
					'payment_id'	=> $payment_id
				)
			);
  		}
   		*/
		return array(
			"status"	=> 'ok',
    		"id"	=> $payment_id,
    		"data"	=> $this->getPaymentById($payment_id),
			"message"		=> __XPAYMENT_USER_PAYMENT_REGISTER,
			"message_type"	=> "success"
    	);
	}

	public function viewInvoicesStatusAction()
	{
		$smarty = $this->getSmartyVar();

		$this->makePaymentOptions();

        $table = "
        	module_pagamento_boleto_invoices_return ret
			LEFT JOIN module_pagamento pag ON (ret.payment_id = pag.payment_id)
			LEFT JOIN users ON (pag.user_id = users.id)
			LEFT JOIN module_pagamento_invoices inv ON (inv.payment_id = ret.payment_id AND inv.parcela_index = ret.parcela_index)
			LEFT OUTER JOIN module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id)
			LEFT OUTER JOIN module_ies ies ON (pag2ies.ies_id = ies.id)
		";

        $fields = array(
	        "ret.payment_id",
        	"GROUP_CONCAT(pag2ies.ies_id ORDER BY pag2ies.ies_id ASC SEPARATOR ',') as ies_id",
        	"GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies",
        	"(select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = ret.payment_id) as total_parcelas",
	        "ret.parcela_index",
	        "ret.nosso_numero",
        	"users.id as user_id",
	        "users.login",
	        "users.name",
	        "users.surname",
	        "ret.data_pagamento",
	        "ret.valor_titulo",
	        "ret.valor_desconto",
	        "ret.valor_total",
        	"inv.data_vencimento",
	        "ret.filename"
		);

		$formWhere = $this->makeInvoicesListFilters();

		$permittedIes = array(0);
		if (count($this->getCurrentUserIesIDs()) > 0) {
			$permittedIes = array_merge($permittedIes, $this->getCurrentUserIesIDs());
		}
		$where = array(
			sprintf("pag2ies.ies_id IN (%s)", implode(",", $permittedIes))
		);

		// CREATE SENDED FILTERS
		$where = array_merge($where, $formWhere);

		//echo prepareGetTableData($table, implode(', ', $fields), implode(" AND ", $where), "ret.data_pagamento DESC, users.name ASC", "ret.payment_id, ret.parcela_index");
		$paidInvoicesData = sC_getTableData($table, implode(', ', $fields), implode(" AND ", $where), "ret.data_pagamento DESC, users.name ASC", "ret.payment_id, ret.parcela_index");

		foreach ($paidInvoicesData as &$invoiceItem) {
			$invoiceItem['username']	= formatLogin(null, $invoiceItem);
		}
        $smarty -> assign("T_PAGAMENTO_PAID_INVOICES", $paidInvoicesData);

   		$disableListOptions = array(
   			"VALOR_DESCONTO"	=> true,
   			"OPTIONS"			=> true
   		);

   		$smarty -> assign("T_PAGAMENTO_INVOICE_DISABLE_FIELDS", $disableListOptions);
	}
	public function viewInvoicesPendingAction()
	{
		$smarty = $this->getSmartyVar();

		$this->makePaymentOptions();;

		$table = "
			module_pagamento_invoices inv
			JOIN module_pagamento pag ON (pag.payment_id = inv.payment_id)
			JOIN users ON (pag.user_id = users.id)
			LEFT OUTER JOIN module_xpayment_types_to_xies pag2ies ON (pag.payment_type_id = pag2ies.payment_type_id)
			LEFT OUTER JOIN module_ies ies ON (pag2ies.ies_id = ies.id)
		";

        $fields = array(
			"inv.payment_type_id",
			"GROUP_CONCAT(pag2ies.ies_id ORDER BY pag2ies.ies_id ASC SEPARATOR ',') as ies_id",
			"GROUP_CONCAT(ies.nome ORDER BY pag2ies.ies_id ASC SEPARATOR '/') as ies",
        	"inv.parcela_index",
        	"(select count(payment_id) FROM module_pagamento_invoices WHERE payment_id = inv.payment_id) as total_parcelas",
			"inv.payment_id",
			"inv.invoice_id AS nosso_numero",
			"users.id as user_id",
			"users.login",
			"users.name",
			"users.surname",
			"inv.data_vencimento",
			"inv.valor as valor_total"
		);

		$formWhere = $this->makeInvoicesListFilters(null, 'inv.parcela_index');

		$permittedIes = array(0);
		if (count($this->getCurrentUserIesIDs()) > 0) {
			$permittedIes = array_merge($permittedIes, $this->getCurrentUserIesIDs());
		}
		$where = array(
			sprintf("pag2ies.ies_id IN (%s)", implode(",", $permittedIes))
		);

		$where[] = "inv.pago = 0";
		$where[] = "inv.data_vencimento < CURRENT_DATE";
		$where[] = "inv.data_vencimento <> '0000-00-00'";

		$where = array_merge($where, $formWhere);

		$group = array(
			"inv.payment_id",
			"inv.parcela_index"
		);

		//echo prepareGetTableData($table, implode(', ', $fields), implode(" AND ", $where), "inv.data_vencimento DESC", implode(", ", $group));
		$unpaidInvoicesData = sC_getTableData($table, implode(', ', $fields), implode(" AND ", $where), "inv.data_vencimento DESC", implode(", ", $group));

		foreach ($unpaidInvoicesData as &$invoiceItem) {
			$invoiceItem['username']	= formatLogin(null, $invoiceItem);
		}

		$smarty -> assign("T_PAGAMENTO_PENDING_INVOICES", $unpaidInvoicesData);
	}
	public function viewSendedInvoicesListAction()
	{
		$smarty = $this->getSmartyVar();

		$this->makePaymentOptions();

		$emailList = sC_getTableData(
			"module_xpayment_send_invoices_log log
			LEFT JOIN users u ON (log.user_send_id = u.id)
			LEFT JOIN module_xpayment_send_invoices_log_item item ON (log.id = item.send_invoice_id)",
			"log.id, log.data_registro, u.id as user_id, u.name, u.surname, u.login,
			COUNT(case when item.send=1 then 1 else NULL end) as total_sucesso,
			COUNT(case when item.send=0 then 1 else NULL end) as total_erro",
			"",
			"log.data_registro DESC",
			"log.id"
		);

		foreach ($emailList as &$item) {
			$item['username'] = formatLogin(null, $item);
		}

		$smarty->assign("T_XPAYMENT_EMAIL_LIST", $emailList);

	}
	public function viewToSendInvoicesListAction()
	{
		$smarty = $this->getSmartyVar();
		$this->makePaymentOptions();

		$tables = array(
			"module_pagamento_invoices inv",
			"JOIN module_pagamento pag ON (inv.payment_id = pag.payment_id)",
			"JOIN users u ON (pag.user_id = u.id)",
			"JOIN courses c ON (pag.course_id = c.id)",
			"JOIN module_pagamento_invoices_status stat ON (inv.status_id = stat.id)",
			"LEFT OUTER JOIN module_xpayment_types_to_xies pag2ies ON (inv.payment_type_id = pag2ies.payment_type_id AND pag2ies.ies_id = c.ies_id)",
			"LEFT OUTER JOIN module_ies ies ON (pag2ies.ies_id = ies.id)"
		);
		$fields = array(
			"pag.payment_id",
			"c.id as course_id",
			"u.id as user_id",
			"inv.status_id",
			"stat.descricao as status",
			"inv.valor",
			"inv.parcela_index",
			"(SELECT COUNT(parcela_index) FROM module_pagamento_invoices WHERE payment_id = pag.payment_id) as parcela_total",
			"u.name",
			"u.surname",
			"u.login",
			"c.name as curso",
			"inv.invoice_id as nosso_numero",
			"inv.data_vencimento",
			"(SELECT status_id FROM module_pagamento_invoices WHERE parcela_index = 1 AND payment_id = inv.payment_id)",
			"CASE (SELECT pago FROM module_pagamento_invoices WHERE parcela_index = 1 AND payment_id = inv.payment_id)
				WHEN 1 THEN 'Baixa Manual'
				WHEN 2 THEN 'Baixa Automática'
			END as tipo_pagto"
		);

		$where = $this->makeInvoicesListFilters(null, "inv.parcela_index");

		$iesIds = $this->getCurrentUserIesIDs();
		$iesIds[] = 0;
		$where[] = "inv.status_id NOT IN (3,4,5)";
		$where[] = "inv.pago = 0";
		$where[] = "inv.bloqueio = 0";
		$where[] = sprintf("inv.payment_type_id IN (SELECT payment_type_id FROM module_xpayment_types_to_xies WHERE ies_id IN (%s))", implode(',', $iesIds));

		$order = array(
			"inv.data_vencimento ASC"
		);
		// MAKE FILTERS
/*
		echo prepareGetTableData(
			implode(" ", $tables),
			implode(", ", $fields),
			implode(" AND ", $where),
			implode(", ", $order)
		);
*/
		$toSendList = sC_getTableData(
			implode(" ", $tables),
			implode(", ", $fields),
			implode(" AND ", $where),
			implode(", ", $order)
		);

		switch ($GLOBALS['configuration']['date_format']) {
			case "YYYY/MM/DD": {
				$date_format = 'Y/m/d'; break;
			}
			case "MM/DD/YYYY": {
				$date_format = 'm/d/Y'; break;
			}
			case "DD/MM/YYYY":
			default: {
				$date_format = 'd/m/Y'; break;
			}
		}

		foreach ($toSendList as &$item) {
			$item['username'] = formatLogin(null, $item);
			$valor_string = number_format($item['valor'], 2, $GLOBALS['configuration']['decimal_point'], $GLOBALS['configuration']['thousands_sep']);
		    $currency = $GLOBALS['CURRENCYSYMBOLS'][$GLOBALS['configuration']['currency']];
		    $GLOBALS['configuration']['currency_order'] ? $valor_string = $currency.$valor_string : $valor_string = $valor_string.$currency;
			$item['valor_string'] = $valor_string;

			$item['data_vencimento_string'] = date($date_format, strtotime($item['data_vencimento']));
		}

		$smarty->assign("T_XPAYMENT_LIST", $toSendList);

		// GETTING CURRENT LIST

		// GET LAST SEND_ID, OR CREATE IF NULL
		$sendIDData = sC_getTableData(
			"module_xpayment_to_send_list",
			"id",
			sprintf("user_id = %d", $this->getCurrentUser()->user['id']),
			"id DESC"
		);

		if (count($sendIDData) > 0) {
			$send_id = $sendIDData[0]['id'];
			$tables = array(
				"module_pagamento_invoices inv",
				"JOIN module_pagamento pag ON (inv.payment_id = pag.payment_id)",
				"JOIN users u ON (pag.user_id = u.id)",
				"JOIN module_xpayment_to_send_list_item send ON (inv.payment_id = send.payment_id AND inv.parcela_index = send.parcela_index)",
				"JOIN module_xpayment_to_send_list list ON (send.send_id = list.id)"
			);
			$fields = array(
				"pag.payment_id",
				"inv.parcela_index",
				"u.name",
				"u.surname",
				"(SELECT COUNT(parcela_index) FROM module_pagamento_invoices WHERE payment_id = pag.payment_id) as parcela_total",
				"inv.data_vencimento",
				"inv.valor"
			);

			$toSendList = sC_getTableData(
				implode(" ", $tables),
				implode(", ", $fields),
				sprintf("list.user_id = %d AND send.send_id = %d", $this->getCurrentUser()->user['id'], $send_id)
			);
		} else {
			$toSendList = array();
			//$data['send_id'] = sC_insertTableData("module_xpayment_to_send_list", array('data_envio' => date('Y-m-d', time() + (60*60*24*10)  )));
		}

		$smarty->assign("T_XPAYMENT_TO_SEND_LIST", $toSendList);
	}

	public function insertIntoSendListAction()
	{
		$data = array(
			'send_id'		=> $_POST['send_id'],
			'payment_id'	=> $_POST['payment_id'],
			'parcela_index' => $_POST['parcela_index']
		);

		if (is_null($data['send_id'])) {
			// GET LAST SEND_ID, OR CREATE IF NULL
			$sendIDData = sC_getTableData(
				"module_xpayment_to_send_list",
				"id",
				sprintf("user_id = %d", $this->getCurrentUser()->user['id']),
				"id DESC"
			);

			if (count($sendIDData) > 0) {
				$data['send_id'] = $sendIDData[0]['id'];
			} else {
				$data['send_id'] = sC_insertTableData("module_xpayment_to_send_list", array('user_id' => $this->getCurrentUser()->user['id'], 'data_envio' => date('Y-m-d', time() + (60*60*24*10)  )));

			}
		}

		$result = sC_countTableData(
			"module_xpayment_to_send_list_item",
			"payment_id, parcela_index",
			sprintf(
				"send_id = %d AND payment_id = %d AND parcela_index =%d",
				$data['send_id'], $data['payment_id'], $data['parcela_index']
			)
		);

		if ($result[0]['count'] == 0) {
			sC_insertTableData("module_xpayment_to_send_list_item", $data);
		}
		echo json_encode(array(
			'message'		=> 'Fatura incluída com sucesso',
			'message_type'	=> 'success'
		));
		exit;
	}
	public function removeFromSendListAction()
	{
		$data = array(
			'send_id'		=> $_POST['send_id'],
			'payment_id'	=> $_POST['payment_id'],
			'parcela_index' => $_POST['parcela_index']
		);

		if (is_null($data['send_id'])) {
			// GET LAST SEND_ID
			$sendIDData = sC_getTableData(
				"module_xpayment_to_send_list_item",
				"send_id",
				sprintf(
					"payment_id = %d AND parcela_index = %d",
					$data['payment_id'], $data['parcela_index']
				),
				"send_id DESC"
			);

			if (count($sendIDData) > 0) {
				$data['send_id'] = $sendIDData[0]['send_id'];
			} else {
				return false;
			}
		}
		$result = sC_countTableData(
			"module_xpayment_to_send_list_item",
			"payment_id, parcela_index",
			sprintf(
				"send_id = %d AND payment_id = %d AND parcela_index =%d",
				$data['send_id'], $data['payment_id'], $data['parcela_index']
			)
		);

		if ($result[0]['count'] > 0) {
			sC_deleteTableData("module_xpayment_to_send_list_item", sprintf(
				"send_id = %d AND payment_id = %d AND parcela_index =%d",
				$data['send_id'], $data['payment_id'], $data['parcela_index']
			));
		}
		echo json_encode(array(
			'message'		=> 'Fatura excluída com sucesso',
			'message_type'	=> 'success'
		));
		exit;
	}

	/* MODULE EVENTS RECEIVERS */
	public function onPaymentReceivedEvent($context, $data)
	{
		//$data['payment_id'], $data['parcela_index']

		if (sC_checkParameter($data['payment_id'], 'id')) {
			$dataReturn = sC_updateTableData(
				"module_pagamento_invoices",
				array(
					"pago" 		=> self::_XPAYMENT_AUTOPAY,
					"bloqueio" 	=> 1,
					"status_id"	=> 3
				),
				sprintf("payment_id = %d AND parcela_index = %d", $data['payment_id'], $data['parcela_index'])
			);

			// GET EDITED USER
			$paymentData = $this->getPaymentById($data['payment_id']);
			if (!sC_checkParameter($data['user_id'], 'id')) {
				$data['user_id'] = $paymentData['user_id'];
			}
			if (!sC_checkParameter($data['enrollment_id'], 'id')) {
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
			$this->registerMonthlyInvoicesAction(null, $data);
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
//					if ($registerReturned['updated'] && $registerReturned['tag'] == 1) {

	}

	/* HOOK-ACTIONS */
	public function xenrollment_registerXenrollmentAction($context = null)
	{
		/// CREATE EDIT PAYMENT DIALOG FORM
		$this->makeEditUserPaymentForm($context);
	}
	public function xenrollment_editXenrollmentAction($context = null)
	{
		/// CREATE EDIT PAYMENT DIALOG FORM
		$this->makeEditUserPaymentForm($context);
	}
	/*
	public function xenrollment_unregisterXenrollmentAction($context = null)
	{
		$smarty = $this->getSmartyVar();
		// LOAD PAYMENT DATA FROM $data['user'].
		$enroll = $context->getEditedEnrollment();

		if (is_numeric($enroll['payment_id']) && $enroll['payment_id'] > 0) {
			$payment = $this->getPaymentById($enroll['payment_id']);
		} else {
			// SEM REGISTRO DE PAGAMENTO. CRIAR UM NOVO????
			return false;
		}

		$beforeCancelInvoices = $payment['invoices'];
		$beforeCancelInvoicesSummary = $this->calculateInvoiceSummary($beforeCancelInvoices);

		$smarty -> assign("T_PAGAMENTO_BEFORE_INVOICES", $beforeCancelInvoices);
		$smarty -> assign("T_PAGAMENTO_BEFORE_INVOICE_SUMMARY", $beforeCancelInvoicesSummary);

		if (($afterCancelInvoices = $this->getCache("cancelation-invoices-". $enroll['id'])) === FALSE) {
			$afterCancelInvoices = $this->filterInvoiceByStatus(
				$beforeCancelInvoices,
				array(
					'pago'	=> 0, 'bloqueio' => 0, 'status_id' => '1'
				),
				false
			);
			$afterCancelInvoices[self::CANCEL_INVOICE_INDEX] = $this->createCancelationInvoice($payment['invoices']);

			$this->setCache("cancelation-invoices-". $enroll['id'], $afterCancelInvoices[self::CANCEL_INVOICE_INDEX]);
		}
		$afterCancelInvoicesSummary = $this->calculateInvoiceSummary($afterCancelInvoices);

		$smarty -> assign("T_PAGAMENTO_AFTER_INVOICES", $afterCancelInvoices);
		$smarty -> assign("T_PAGAMENTO_AFTER_INVOICE_SUMMARY", $afterCancelInvoicesSummary);

		$disableListOptions = array(
			"VALOR_DESCONTO"	=> true,
			"OPTIONS"			=> true
		);

		// CACHE AfterCancelInvoices

		$smarty -> assign("T_PAGAMENTO_INVOICE_DISABLE_FIELDS", $disableListOptions);

		$context->appendTemplate(array(
			'title'			=> __PAGAMENTO_XENROLLMENT_UNREGISTER,
			'template'		=> $this->moduleBaseDir . 'templates/hook/xenrollment.unregister_xenrollment.tpl'
		));

		return true;

	}
	*/
	public function getModule()
	{
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::VIEW_INVOICES_STATUS;

		$migratedActions = array(
			self::REGISTER_ENROLLMENT_PAYMENT,
			self::REGISTER_MONTHLY_INVOICES,
			self::VIEW_INVOICES_STATUS,
			self::VIEW_INVOICES_PENDING,
			self::VIEW_SENDED_INVOICES_LIST,
			self::VIEW_TO_SEND_INVOICES_LIST,
			self::INSERT_INTO_SEND_LIST,
			self::REMOVE_FROM_SEND_LIST
		);

		if (in_array($selectedAction, $migratedActions)) {
			return parent::getModule();
		}

		$smarty = $this -> getSmartyVar();

		$userRole = $this->getCurrentUser()->getType();

		$smarty -> assign("T_MODULE_PAGAMENTO_ACTION", $selectedAction);

		if ($selectedAction == self::GET_INVOICE) {
			// GET MODULE TO GENERATE, PASS GET DATA TO IT.
			/** @TODO CHECAR SE O FOI PASSADO O "invoice_id" OU SE FOI PASSADO "payment_id, parcela_index" */

			if (
				sC_checkParameter($_GET['payment_id'], 'id') &&
				$paymentData = $this->getPaymentById($_GET['payment_id'])
			) {

				$invoice_index = -1;

				if (array_key_exists('invoice_id', $_GET)) {
					if (array_key_exists($_GET['invoice_id'], $paymentData['invoices'])) {
						$invoice_index = $_GET['invoice_id'];
					}
				}

				if (array_key_exists('invoice_id', $_GET)) {
					foreach ($paymentData['invoices'] as $invoiceIndex => $invoiceData) {
						if ($invoiceData['invoice_id'] == $_GET['invoice_id']) {
							$invoice_index = $invoiceIndex;
							break;
						}
					}
					if ($invoice_index == -1) {
						 echo __PAGAMENTO_INVOICE_NOT_FOUND;
						 exit;
					}
				}

				if ($invoice_index == -1) {
					$invoice_index = $paymentData['next_invoice'];
				}

				// GET NEXT INVOICE AND MODULE TO PROCESS
				$nextInvoice = $paymentData['invoices'][$invoice_index];
				$paymentType = $this->getPaymentTypeById($nextInvoice['payment_type_id']);
				$subModule = $this->getSubModule($paymentType['module_class_name']);

				$result = $subModule->processAction(
					self::GET_INVOICE,
					array_merge(
						$paymentData,
						array('invoice_index' => $invoice_index)
					)
				);
				if ($result['output'] == 'blank') {
					echo $result['html'];
					exit;
				} else {
					return $result['html'];
				}
			} else {
				$this->setMessageVar(_MODULE_PAGAMENTO_PAYMENT_NOT_EXISTS, 'error');
			}
		} elseif ($selectedAction == self::SEND_INVOICES) {

			/*
			$payments = $this->getPayments(
				"(payment_id IN (SELECT pag.payment_id
FROM module_pagamento_invoices inv
LEFT JOIN module_pagamento pag ON (inv.payment_id = pag.payment_id)
LEFT JOIN users u ON (pag.user_id = u.id)
LEFT JOIN courses c ON (pag.course_id = c.id)
WHERE inv.data_vencimento > '2011-09-05'
AND inv.data_vencimento < '2011-09-30'
AND parcela_index = 2
AND pag.payment_type_id = 1
AND (SELECT pago FROM module_pagamento_invoices WHERE parcela_index = 1 AND payment_id = inv.payment_id) <> 0
AND c.id NOT IN (26, 30)
AND u.active = 1))");
			*/

			// LISTA DE PAGAMENTOS A SEREM ENVIADOS
			// PAGOS: mas com vencimento em outubro
			/*
			227
			172
			180
			179
			178
			183
			*/

			//$payments = $this->getPayments("payment_id IN (41, 42, 43, 44, 45, 50, 51, 52, 54,     57, 58, 60, 61,    180, 95, 222, 179, 119, 171, 183, 196, 123, 140, 178, 134, 227, 189, 146, 172, 154, 167, 181, 191, 173, 117, 168, 166, 182, 225, 170, 125, 103, 217)");
			//$payments = $this->getPayments("payment_id IN (41, 42, 43, 44, 45, 50, 51, 52, 54, 57, 58, 60, 61,          95, 222, 119, 171, 196, 123, 140, 134, 189, 146, 154, 167, 181, 191, 173, 117, 168, 166, 182, 225, 170, 125, 103, 217)");

			// MAINFRAME B
			//$payments = $this->getPayments("payment_id IN (    41, 42, 43, 44, 45, 46,     50, 51, 52, 53, 54,     57, 58, 60, 61)");

			//IN (SELECT id FROM module_xpayment_to_send_list WHERE user_id = %d)", $this->getCurrentUser()->user['id'])

			$sendIDData = sC_getTableData(
				"module_xpayment_to_send_list",
				"id",
				sprintf("user_id = %d", $this->getCurrentUser()->user['id']),
				"id DESC"
			);

			if (count($sendIDData) == 0) {
				$this->setMessageVar("Nenhuma lista de envio", "error");
				return true;
			} else {
				$send_id = $sendIDData[0]['id'];
			}

			$payments = sC_getTableData(
				"module_xpayment_to_send_list_item",
				"payment_id, parcela_index",
				sprintf("send_id = %d", $send_id)
			);

			if (count($payments) == 0) {
				$this->setMessageVar("Nenhuma fatura na lista para envio", "error");
			} else {

				error_reporting( E_ALL & ~E_NOTICE );
					ini_set("display_errors", true);
						define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

				$sendInvoiceId = sC_insertTableData("module_xpayment_send_invoices_log", array(
					'user_send_id'	=> $this->getCurrentUser()->user['id']
				));
				//$payments = $this->getPayments("payment_id IN ()");



	            foreach ($payments as $key => $payment) {

	               	$paymentData = $this->getPaymentById($payment['payment_id']);
	               	//$invoice_index = $paymentData['next_invoice'];

	               	$invoice_index = $payment['parcela_index'];
	               	/*
	               	foreach ($paymentData['invoices'] as $index => $invoice) {
	               		if (
	               			strtotime($invoice['data_vencimento']) > time() && // vencimento entre hoje
							strtotime($invoice['data_vencimento']) < time() + (86400 * 10) // e 10 dias pra frente
	               		) {
							$invoice_index = $index;
	               			break;
	               		} else {

	               		}
	               	}
	               	*/

	               	//$invoice_index = $paymentData['next_invoice'];
	               	if (is_null($invoice_index)) {
	               		continue;
	               	}



					// GET NEXT INVOICE AND MODULE TO PROCESS
					$nextInvoice = $paymentData['invoices'][$invoice_index];
					$paymentType = $this->getPaymentTypeById($nextInvoice['payment_type_id']);
					$subModule = $this->getSubModule($paymentType['module_class_name']);

					$paymentData['cliente']['usuario'] = formatLogin(null, $paymentData['cliente']);

					$data = array(
						'payment_id'		=> $paymentData['payment_id'],
						'parcela_index'		=> $nextInvoice['parcela_index'],
						'email'				=> $paymentData['cliente']['email'],
						'vencimento'		=> $nextInvoice['data_vencimento'],
						'user_id'			=> $paymentData['cliente']['id'],
						'cliente'			=> $paymentData['cliente']['usuario'],
						'total_parcelas'	=> count($paymentData['invoices']),
						'has_minor'			=> $paymentData['cliente']['has_minor'],
						'has_financier'		=> false
					);


					//sC_getTableData("module_xuser_responsible", "*", "type = 'financial' AND id = ")

					// ATUALIZANDO STATUS PARA EMITIDO
					$this->updateInvoiceById($payment['payment_id'], $invoice_index, array('status_id' => 2));

		 			$result = $subModule->processAction(
						self::GET_INVOICE,
						array_merge(
							$paymentData,
							array('invoice_index' => $invoice_index)
						)
					);
	               	$invoice_sha_access = sha1(serialize($result));

	               	$this->updateInvoiceById($payment['payment_id'], $invoice_index, array('invoices_sha_access' => $invoice_sha_access ));

	               	$filename = sprintf(
						G_ROOTPATH . 'boletos/%s.html',
						$invoice_sha_access
					);

					var_dump($paymentData);
					exit;

					$log_fields = array(
						'send_invoice_id'	=> $sendInvoiceId,
						'payment_id'		=> $paymentData['payment_id'],
						'parcela_index'		=> $nextInvoice['parcela_index'],
						'email'				=> $paymentData['cliente']['email'],
						'vencimento'		=> $nextInvoice['data_vencimento'],
						'send'				=> 1
					);

					sC_insertTableData("module_xpayment_send_invoices_log_item", $log_fields);
					$result = file_put_contents($filename, $result['html']);

					if (!$result) {
						sC_updateTableData(
							"module_xpayment_send_invoices_log_item",
							array('send' => 0),
							sprintf("
							send_invoice_id = %d AND
							payment_id = %d AND
							parcela_index = %d
							", $log_fields['send_invoice_id'], $log_fields['payment_id'], $log_fields['parcela_index']
							)
						);
						continue;
					}

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
								<b><span style="font-size: 11pt; color: rgb(31, 73, 125);">{$ACCESS_LINK}</span></b>
							</p>
							</div>
							<div>
								<p class="MsoNormal"><span style="font-size: 11pt; color: rgb(31, 73, 125);">
									Lembrando que o devido desconto de pontualidade encontra-se no corpo do boleto. O mesmo é de inteira responsabilidade do aluno até o vencimento.
								</span></p>
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

					$search = array(
	                	'{$USERNAME}',
	                	'{$MONTH}',
	                	'{$ACCESS_LINK}'
					);
					$repl = array(
	                	$paymentData['cliente']['name'] . ' ' . $paymentData['cliente']['surname'],
	                	'Setembro',
	                	'http://new.magester.net/boleto.php?id=' . $invoice_sha_access
					);

	               	$body = str_replace($search, $repl, $bodyTemplate);

	               	$my_email = "fin@americas.com.br";
					$user_mail = $paymentData['cliente']['email'];
					//$user_mail = "kucaniz@arcanjoweb.com";

					$subject = 'Boleto Américas';

	                $header = array (
	                	'From'                   	=> $my_email,
		                'Reply-To'				 	=> $my_email,
		                'To'                        => $user_mail,
		                'Subject'                   => $subject,
		                'Content-type'             	=> 'text/html;charset="UTF-8"',                       // if content-type is text/html, the message cannot be received by mail clients for Registration content
		                'Content-Transfer-Encoding' => '7bit'
					);
	                /*
	                $smtp = Mail::factory('smtp', array(
	                	'auth'      => $GLOBALS['configuration']['smtp_auth'] ? true : false,
	                	'host'      => $GLOBALS['configuration']['smtp_host'],
	                	'password'  => $GLOBALS['configuration']['smtp_pass'],
	                	'port'      => $GLOBALS['configuration']['smtp_port'],
	                	'username'  => $GLOBALS['configuration']['smtp_user'],
	                	'timeout'   => $GLOBALS['configuration']['smtp_timeout'])
	                );
	                */
	                $smtp = Mail::factory('mail');

					if ($smtp -> send($user_mail, $header, $body)) {
	                	// CHECK IF USER IS 18 AGE OLDER.
	                    $status = false;

		                if ($paymentData['cliente']['has_minor']) {
		                	/*
							$data['minor'] = array(
								'email'				=> $paymentData['minor']['email'],
								'user_id'			=> $paymentData['minor']['id'],
								'cliente'			=> $paymentData['minor']['name'] . ' ' . $paymentData['minor']['surname']
							);

							$search = array(
					            '{$USERNAME}',
					            '{$MONTH}',
					            '{$ACCESS_LINK}'
							);
							$repl = array(
			                	$paymentData['minor']['name'] . ' ' . $paymentData['minor']['surname'],
			                	'Agosto',
			                	'http://new.magester.net/boleto.php?id=' . $invoice_sha_access
							);

			                $bodyMinor = str_replace($search, $repl, $bodyTemplate);

			                $my_email = "fin@americas.com.br";
							$user_mail = $paymentData['minor']['email'];
							//$user_mail = "kucaniz@arcanjoweb.com";

			                $subject = 'Boleto Américas';

			                $header = array (
			                	'From'                   	=> $my_email,
				                'Reply-To'				 	=> $my_email,
				                'To'                        => $user_mail,
				                'Subject'                   => $subject,
				                'Content-type'             	=> 'text/html;charset="UTF-8"',                       // if content-type is text/html, the message cannot be received by mail clients for Registration content
				                'Content-Transfer-Encoding' => '7bit'
							);
			                $smtp = Mail::factory('smtp', array(
			                	'auth'      => $GLOBALS['configuration']['smtp_auth'] ? true : false,
			                	'host'      => $GLOBALS['configuration']['smtp_host'],
			                	'password'  => $GLOBALS['configuration']['smtp_pass'],
			                	'port'      => $GLOBALS['configuration']['smtp_port'],
			                	'username'  => $GLOBALS['configuration']['smtp_user'],
			                	'timeout'   => $GLOBALS['configuration']['smtp_timeout'])
			                );

		                    if ($smtp -> send($user_mail, $header, $bodyMinor)) {
		                    	$status = true;
		                    }
		                    */
		                	$status = true;
						} else {
		                	$status = true;
						}

		                if ($status) {
		                	$count++;


		                  	// SE ENVIADO CORRETAMENTE PARA O ALUNO, ENVIAR PRA fin@americas.com.br.
			               	$my_email = "fin@americas.com.br";
							$user_mail = "fin@americas.com.br";

			            	$subject = 'Boleto Américas';

		                    $header = array (
			                	'From'                   	=> $my_email,
				                'Reply-To'				 	=> $my_email,
				                'To'                        => $user_mail,
				                'Subject'                   => $subject,
				                'Content-type'             	=> 'text/html;charset="UTF-8"',                       // if content-type is text/html, the message cannot be received by mail clients for Registration content
				                'Content-Transfer-Encoding' => '7bit'
							);
			                $smtp = Mail::factory('smtp', array(
			                	'auth'      => $GLOBALS['configuration']['smtp_auth'] ? true : false,
			                	'host'      => $GLOBALS['configuration']['smtp_host'],
			                	'password'  => $GLOBALS['configuration']['smtp_pass'],
			                	'port'      => $GLOBALS['configuration']['smtp_port'],
			                	'username'  => $GLOBALS['configuration']['smtp_user'],
			                	'timeout'   => $GLOBALS['configuration']['smtp_timeout'])
			                );

							if ($smtp -> send($user_mail, $header, $body)) {

			            	}
						} else {
							$error++;
							sC_updateTableData(
								"module_xpayment_send_invoices_log_item",
								array('send' => 0),
								sprintf("
								send_invoice_id = %d AND
								payment_id = %d AND
								parcela_index = %d
								", $log_fields['send_invoice_id'], $log_fields['payment_id'], $log_fields['parcela_index']
								)
							);
						}

						sC_deleteTableData(
							"module_xpayment_to_send_list_item",
							sprintf("send_id =%d AND payment_id = %d AND parcela_index = %d", $send_id, $payment['payment_id'], $payment['parcela_index'])
						);

					}
				}
				/*
				var_dump($count);
				var_dump($error);
				*/
				$this->setMessageVar(sprintf("%02d Boletos enviados com sucesso", $count), "success");
			}
		} elseif ($selectedAction == self::SAVE_USER_INVOICE &&
			!($this -> getCurrentUser()->isStudentRole($userRole) || $this -> getCurrentUser()->isProfessorRole($userRole))
		) {

			if (
				sC_checkParameter($_GET['payment_id'], 'id') &&
				sC_checkParameter($_GET['invoice_index'], 'id')
			) {
				$result = true;
				if (is_numeric($_POST['status_id'])) {
					$result = $result && $this->updateInvoiceById($_GET['payment_id'], $_GET['invoice_index'], array('status_id' => $_POST['status_id']));
				}
				if (strtotime($_POST['vencimento']) !== FALSE) {
					$result = $result && $this->updateInvoiceById($_GET['payment_id'], $_GET['invoice_index'], array('data_vencimento' => $_POST['vencimento']));
				}

				echo json_encode(array(
					'success'	=> $result,
					'message'	=> ($result) ? _PAGAMENTO_UPDATEINVOICESUCCESS : _PAGAMENTO_UNKNOWERROR
				));
			} else {
				echo json_encode(array(
					'success'	=> false,
					'message'	=> _PAGAMENTO_INVALIDARGUMENTS
				));
			}
			exit;
		} elseif ($selectedAction == self::UPDATE_INVOICE &&
			!($this -> getCurrentUser()->isStudentRole($userRole) || $this -> getCurrentUser()->isProfessorRole($userRole))
		) {
			if (
				sC_checkParameter($_POST['payment_id'], 'id') &&
				sC_checkParameter($_POST['parcela_index'], 'id') &&
				is_array($_POST['fields'])
			) {
				$result = true;

				$fields = $_POST['fields'];

				$result = $result && $this->updateInvoiceById(
					$_POST['payment_id'],
					$_POST['parcela_index'],
					$fields
				);

				if ($fields['pago'] != 0) {
					// IS A PAYMENT REGISTER... CALL EVENT

					$data = array_merge(array(
						'payment_id'	=> $_POST['payment_id'],
						'parcela_index'	=> $_POST['parcela_index'],
					), $fields);

					$this->onPaymentReceivedEvent($context, $data);
				}

				echo json_encode(array(
					'success'	=> $result,
					'message'	=> ($result) ? _PAGAMENTO_UPDATEINVOICESUCCESS : _PAGAMENTO_UNKNOWERROR
				));
			} else {
				echo json_encode(array(
					'success'	=> false,
					'message'	=> _PAGAMENTO_INVALIDARGUMENTS
				));
			}
			exit;
		} elseif ($selectedAction == self::SAVE_USER_PAYMENT &&
			!($this -> getCurrentUser()->isStudentRole($userRole) || $this -> getCurrentUser()->isProfessorRole($userRole))
		) {
			// SAVE PAYMENT, RETURN STATUS AND A STRING WITH METHOD DESCRIPTION
			//if (array_key_exists('_qf__module_pagamento_payment_type_select', $_POST)) { // TO CHECK FORM ORIGIN

				if (sC_checkParameter($_GET['xuser_login'], 'login')) {

					$userObject = MagesterUserFactory::factory($_GET['xuser_login']);

					$courses = $userObject->getUserCourses();

					$coursesIDs = array_keys($courses);
					// CHECK COURSES WITHOUT PAYMENT REGISTERED

					$payment_data = array(
						'user_id'			=> $userObject->user['id'],
						'vencimento'		=> is_numeric($_POST['vencimento']) ? $_POST['vencimento'] : 5,
						'desconto'			=> is_numeric(str_replace(',', '.', $_POST['desconto'])) ? str_replace(',', '.', $_POST['desconto']) : 5,
						'payment_type_id'	=> is_numeric($_POST['payment_type_id']) ? $_POST['payment_type_id'] : 1,
						/** @todo Inserir opção para selecionar estes dados abaixo de cobrança... Incluir "default" no registro de curso. */
						'data_inicio'		=> date_create_from_format('d/m/Y', $_POST['data_inicio'])->format('Y-m-01'),
						'emitir_vencidos'	=> '0'
					);

					$payment_parcelas = is_numeric($_POST['parcelas']) ? $_POST['parcelas'] : 10;

					if (sC_checkParameter($_GET['payment_id'], 'id')) {
						// Payment ID Sent. UPDATE!!!
						$payment_id = $_GET['payment_id'];
						// GET TOTAL DE PARCELAS, E DESCONTO, TO CHANGE DE ACORDO

						// UPDATE PAYMENT_TABLE
						sC_updateTableData("module_pagamento", $payment_data, "payment_id = " . $payment_id);


						// RECALCULATE VENCIMENTOS
						$invoices = array();
   						for ($index = 1; $index <= $payment_parcelas; $index++) {

   							$invoicesUpdate = array();

   							if ($index == 1) {
	   							$data_vencimento = null;
								if (date_create_from_format('d/m/Y', $_POST['data_matricula']) !== FALSE) {
	   								$invoicesUpdate['data_vencimento']	= date_create_from_format('d/m/Y', $_POST['data_matricula'])->format('Y-m-d');
								}
   							} else {
   								$dataInicioCobranca = strtotime($payment_data['data_inicio']);

	   							$data_vencimento = mktime(
	   								0, 0, 0,
	   								date('m', $dataInicioCobranca) + ($index - 2), // -2 => PORQUE A PRIMEIRA PARCELA TEM INDEX = 2 E A MATRICULA NAO CONTA NO CÁLCULO
	   								$payment_data['vencimento'],
	   								date('Y', $dataInicioCobranca)
	   							);

   								$invoicesUpdate['data_vencimento']	= date('Y-m-d H:i:s', $data_vencimento);

	   							if ($payment_data['emitir_vencidos'] == 0 && $data_vencimento < time()) {
//	   								$invoicesUpdate['status_id'] = 1;
	   								$invoicesUpdate['bloqueio'] = 1;
//	   								$invoicesUpdate['pago'] = 1;
	   							} else {
	   								$invoicesUpdate['bloqueio'] = 0;
	   							}
   							}

	   						sC_updateTableData(
	   							"module_pagamento_invoices",
	   							$invoicesUpdate,
	   							sprintf("payment_id = %d AND parcela_index = %d", $payment_id, $index)
	   						);

   						}
						// RECALCULATE INVOICES

						// RECALCULATE DESCONTO
						sC_executeNew(
							'UPDATE module_pagamento_invoices ' .
							'SET valor_desconto = valor - (valor * (' . $payment_data['desconto'] . ' / 100)) ' .
							'WHERE ' . sprintf("payment_id = %s AND status_id IN (%s)", $payment_id, implode(',', array(1,5)))
						);


   						/*

						"module_pagamento_invoices",


   						valor_desconto

						$invoices = array();
   						for ($index = 1; $index <= $payment_parcelas; $index++) {





   							$invoicesUpdate = array();

   							if ($index == 1) {
	   							$data_vencimento = null;
	   							$invoicesUpdate['status_id'] = 5;
   							} else {
   								$dataInicioCobranca = strtotime($payment_data['data_inicio']);

	   							$data_vencimento = mktime(
	   								0, 0, 0,
	   								date('m', $dataInicioCobranca) + ($index - 2), // -2 => PORQUE A PRIMEIRA PARCELA TEM INDEX = 2 E A MATRICULA NAO CONTA NO CÁLCULO
	   								$payment_data['vencimento'],
	   								date('Y', $dataInicioCobranca)
	   							);

   								$invoicesUpdate['data_vencimento']	= date('Y-m-d H:i:s', $data_vencimento);

	   							if ($payment_data['emitir_vencido$payment_ids'] == 0 && $data_vencimento < time()) {
	   								$invoicesUpdate['status_id'] = 5;
	   							}
   							}

	   						sC_updateTableData(
	   							"module_pagamento_invoices",
	   							$invoicesUpdate,
	   							sprintf("payment_id = %d AND parcela_index = %d", $payment_id, $index)
	   						);
   						}
   						*/

						$payment_id = $_GET['payment_id'];

						$paymentData = $this->getPaymentById($payment_id);

						echo json_encode(array(
							'success'				=> true,
							'payment_id'			=> $payment_id,
							'method_description'	=> sprintf("<strong>%s</strong> - %s", $paymentData['payment_type'], $paymentData['payment_type_description'])
						));

						exit;
					} else {
						$result = $this->registerEnrollmentPaymentAction(null, $payment_data);

						echo json_encode(array(
							'success'				=> true,
							'payment_id'			=> $result['id'],
							'method_description'	=> sprintf("<strong>%s</strong> - %s", $result['data']['payment_type'], $result['data']['payment_type_description'])
						));

						exit;
					}


				}
			//}
			echo json_encode(array(
				'success'	=> true,
				'method_description'	=> 'Descrição do método'
			));
			exit;
		} else {
			if (!($this -> getCurrentUser()->isStudentRole($userRole) || $this -> getCurrentUser()->isProfessorRole($userRole))) {

				$this->makePaymentOptions();
			}

			if (($this -> getCurrentUser()->isStudentRole($userRole) || $this -> getCurrentUser()->isProfessorRole($userRole))) {
				/*
				if ($selectedAction == self::GET_PAYMENTS) {
				} else {
				*/

					$payments = $this->getPayments();
					$payments = $this->getPaymentsByUserId($this -> getCurrentUser() ->user['id']);
					$smarty -> assign("T_MODULE_PAGAMENTO_PAYMENTS", $payments);
				//}

			} else { // ONLY ADMINISTRATOR
				if ($selectedAction == self::GET_PAYMENTS) {
					$payments = $this->getPayments();
					$smarty -> assign("T_MODULE_PAGAMENTO_PAYMENTS", $payments);
  				} elseif ($selectedAction == self::VIEW_INVOICES_STATUS) {

  				} elseif ($selectedAction == self::VIEW_INVOICES_PENDING) {

				} elseif ($selectedAction == self::UPDATE_INVOICES_STATUS) {

					//$subModule = $this->getSubModule($defaults[0]['module_class_name']);

					$submodules = $this->getAvaliableSubModules(true);

					$invoicesSubModules = array();
					foreach ($submodules as $subModule) {

						// RETURN AS STRING (TEMPLATE FILENAME)
						$subModuleFileTemplate = $subModule->processAction(self::UPDATE_INVOICES_STATUS,
							$_GET,
							$this
						);

						if (file_exists($subModuleFileTemplate)) {
							$invoicesSubModules[] = array(
								'title'		=> $subModule->getTitle(),
								'links'		=> $subModule->getLinks(),
								'template'	=> $subModuleFileTemplate
							);
						}
					}




					$smarty -> assign('T_MODULE_PAGAMENTO_INVOICE_SUBMODULES', $invoicesSubModules );

					if (count($invoicesSubModules) == 0) {
						$this->setMessageVar(_MODULE_PAGAMENTO_NO_SUBMODULES_FOUND, 'warning');
					}
				} elseif ($selectedAction == self::GET_PAYMENT_TYPES) {
					$payment_types = sC_getTableData(
						"module_pagamento_types",
						"payment_type_id, data_registro, title, comments, module_class_name",
						"active = 1",
						"data_registro DESC"
					);

					$smarty -> assign("T_MODULE_PAGAMENTO_PAYMENTS_TYPES", $payment_types);

				} elseif ($selectedAction == self::DELETE_PAYMENT_TYPE) {
					if (sC_checkParameter($_GET['payment_type_id'], 'id')) {
						if (sC_deleteTableData("module_pagamento_types", sprintf("payment_type_id = %d", $_GET['payment_type_id']))) {
							$this->setMessageVar(_MODULE_PAGAMENTO_PAYMENT_TYPE_DELETE_SUCCESS, 'success');

							$url = sprintf(
								$this->moduleBaseUrl . "&action=%s&message=%s&message_type=%s",
								self::GET_PAYMENT_TYPES,
								urlencode(_MODULE_PAGAMENTO_PAYMENT_TYPE_DELETE_SUCCESS),
								'success'
							);

							sC_redirect($url);
						} else {
							$this->setMessageVar(_UNDEFINEDERROR, 'warning');
						}
					} else {
						$this->setMessageVar(_UNDEFINEDERROR, 'warning');
					}
				} elseif (
					$selectedAction == self::CREATE_PAYMENT_TYPE ||
					$selectedAction == self::EDIT_PAYMENT_TYPE
) {
					$avaliableSubmodules = $this->getAvaliableSubModules();

					$avaliableSubmodules = array_merge(
						array(-1	=> _MODULE_PAGAMENTO_SELECTMODULE),
						$avaliableSubmodules
					);

					$form = new HTML_QuickForm(__CLASS__ . "_add_payment_type", "post", $_SERVER['REQUEST_URI'], null, null, true);

					/*
					$form -> addElement('hidden', 'payment_basic_data');
					$form->setDefaults(array('payment_basic_data' => 1));
					*/
					$form -> addElement('hidden', 'payment_type_id');
					$form -> addElement('select', 'module_class_name', _MODULE_PAGAMENTO_MODULE_NAME, $avaliableSubmodules, array('class' => 'full'));
					$form -> addElement('text', 'title', _MODULE_PAGAMENTO_TITLE, 'class = "full"');
					$form -> addElement('textarea', 'comments', _MODULE_PAGAMENTO_COMMENTS, array('class' => 'full', 'rows' => 4));
					$form -> addElement('checkbox', 'active', _MODULE_PAGAMENTO_ACTIVE);

					$form -> addElement('submit', 'submit_apply', _MODULE_PAGAMENTO_SUBMIT);

					if ($selectedAction == self::CREATE_PAYMENT_TYPE) {
						$form->setDefaults(array(
							'module_class_name'	=> -1,
							'active'			=> 1
						));
					} elseif ($selectedAction == self::EDIT_PAYMENT_TYPE) {
						if (sC_checkParameter($_GET['payment_type_id'], 'id')) {
							$form->getElement('module_class_name')->freeze();

							$defaults = sC_getTableData("module_pagamento_types", "*", sprintf("payment_type_id = %d", $_GET['payment_type_id']));

							$form->setDefaults($defaults[0]);
							// LOAD SUB-MODULE FORM TO EDITING

							$subModule = $this->getSubModule($defaults[0]['module_class_name']);

							//$subModule	= $subModules[$defaults[0]['module_class_name']];

							// RETURN AS STRING (TEMPLATE FILENAME)
							$subModuleFileTemplate = $subModule->processAction(self::EDIT_PAYMENT_TYPE,
								$_GET,
								$this
							);

							$smarty -> assign('T_SUBMODULE_TEMPLATE', $subModuleFileTemplate);
							$smarty -> assign('T_SUBMODULE_TITLE', $subModule->getTitle());
							$smarty -> assign('T_SUBMODULE_LINKS', $subModule->getLinks());

							$smarty -> assign('T_EDIT_TABS',
								array(
									array(
										'title' => _MODULE_PAGAMENTO_EDIT_PAYMENT_TYPE
									),
									array(
										'title' => $subModule->getTitle()
									)
								)
							);
						}
					}

					if ($form -> isSubmitted() && $form -> validate()) {
						if (!is_null($form->exportValue('submit_apply'))) {
							// FIRST SAVE THE DATA FROM module_pagamento
							$values = $form->exportValues();

							$paymentData = array(
								'title'				=> $values['title'],
								'comments'			=> $values['comments'],
								'module_class_name'	=> $values['module_class_name'],
								'active'			=> $values['active']
							);

							if ($selectedAction == self::CREATE_PAYMENT_TYPE) {
								$result = sC_insertTableData("module_pagamento_types", $paymentData);

								if (!$result) {
									$this->setMessageVar(_UNDEFINEDERROR, 'warning');
								} else {
									$this->setMessageVar(_MODULE_PAGAMENTO_PAYMENT_TYPE_SAVE_SUCCESS, 'success');

									$url = sprintf(
										$this->moduleBaseUrl . "&action=%s&payment_type_id=%d&message=%s&message_type=%s",
										self::EDIT_PAYMENT_TYPE,
										$result,
										urlencode(_MODULE_PAGAMENTO_PAYMENT_TYPE_SAVE_SUCCESS),
										'success'
									);

									sC_redirect($url);
								}
							} elseif ($selectedAction == self::EDIT_PAYMENT_TYPE) {
								$result = sC_updateTableData("module_pagamento_types", $paymentData, sprintf("payment_type_id = %d", $values['payment_type_id']));

								if (!$result) {
									$this->setMessageVar(_UNDEFINEDERROR, 'warning');
								} else {
									$this->setMessageVar(_MODULE_PAGAMENTO_PAYMENT_TYPE_SAVE_SUCCESS, 'success');
								}
							}
						} else {

						}
					}

					$smarty = $this->getSmartyVar();

					$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
					$form -> accept($renderer);
					$formRender = $renderer -> toArray();

					$smarty -> assign('T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM', $formRender);
				}
			}
		}
		return false;
	}

	public function getSmartyTpl()
	{
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::SHOW;

		$migratedActions = array(
			self::REGISTER_ENROLLMENT_PAYMENT,
			self::VIEW_INVOICES_STATUS,
			self::VIEW_INVOICES_PENDING,
			self::VIEW_SENDED_INVOICES_LIST,
			self::VIEW_TO_SEND_INVOICES_LIST
		);

		if (in_array($selectedAction, $migratedActions)) {
			return parent::getSmartyTpl();
		}


		$smarty = $this -> getSmartyVar();

		$smarty -> assign("T_MODULE_PAGAMENTO_BASEDIR" , $this -> moduleBaseDir);
		$smarty -> assign("T_MODULE_PAGAMENTO_BASEURL", $this -> moduleBaseUrl);
		$smarty -> assign("T_MODULE_PAGAMENTO_BASELINK", $this -> moduleBaseLink);

		if ($selectedAction == self::GET_INVOICE) {
			return false;
		}



		return $this -> moduleBaseDir . "templates/default.tpl";
	}

	public function getControlPanelModule()
	{
		return false;
	}


	public function getDashboardModule()
	{
		// GET USER PAYMENTS LIST

		$userID = $this->getCurrentUser()->user['id'];

		$payments = $this->getPaymentsByUserId($userID);



		// MOSTRAR SOMENTE EMITIDOS
		foreach ($payments as $pay_key => $payment) {
			foreach ($payment['invoices'] as $inv_key => $invoice) {
				if ($invoice['status_id'] != 2) {
					unset($payments[$pay_key]['invoices'][$inv_key]);
				}
			}
		}
//		var_dump($payments);
//		exit;

		$smarty = $this -> getSmartyVar();
		$smarty -> assign ("T_PAYMENT", $payments[0]);

		return true;

	}

	public function getDashboardSmartyTpl()
	{
		//$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::SHOW;

		$smarty = $this -> getSmartyVar();

		$smarty -> assign("T_MODULE_PAGAMENTO_BASEDIR" , $this -> moduleBaseDir);
		$smarty -> assign("T_MODULE_PAGAMENTO_BASEURL", $this -> moduleBaseUrl);
		$smarty -> assign("T_MODULE_PAGAMENTO_BASELINK", $this -> moduleBaseLink);
/*
		if ($selectedAction == self::GET_INVOICE) {
			return false;
		}
*/
//		echo $this -> moduleBaseDir . "templates/dashboard.tpl";
		return $this -> moduleBaseDir . "templates/dashboard.tpl";
	}

    public function getCenterLinkInfo()
    {
    	return false;
    	/*
    	$currentUser = $this -> getCurrentUser();

        $xuserModule = $this->loadModule("xuser");
		if (
        	$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
        	$xuserModule->getExtendedTypeID($currentUser) == "financier"
        ) {
            return array('title' => _PAGAMENTOS,
                         'image' => $this -> moduleBaseDir . 'images/pagamento.png',
                         'link'  => $this -> moduleBaseUrl);
        }
        */
    }

	public function getNavigationLinks($forceAction = null)
	{
		$currentUser = $this -> getCurrentUser();
		$navLinks =  array (
			array ('title' => _HOME, 'link' => $currentUser -> getRole() . ".php"),
			array ('title' => _MODULE_PAGAMENTO, 'link' => $this -> moduleBaseUrl)
		);


		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::SHOW;

		$selectedAction = !is_null($forceAction) ? $forceAction : $selectedAction;

		if (
			$selectedAction == self::GET_PAYMENT_TYPES ||
			$selectedAction == self::CREATE_PAYMENT_TYPE ||
			$selectedAction == self::EDIT_PAYMENT_TYPE
) {
			$navLinks[] = array ('title' => _MODULE_PAGAMENTO_PAYMENT_TYPES, 'link' => $this -> moduleBaseUrl . "&action=" . self::GET_PAYMENT_TYPES);

			if ($selectedAction == self::CREATE_PAYMENT_TYPE) {
				$navLinks[] = array ('title' => _MODULE_PAGAMENTO_ADD_PAYMENT_TYPE, 'link' => $this -> moduleBaseUrl . "&action=" . self::CREATE_PAYMENT_TYPE);
			} elseif ($selectedAction == self::EDIT_PAYMENT_TYPE) {
				$navLinks[] = array ('title' => _MODULE_PAGAMENTO_EDIT_PAYMENT_TYPE, 'link' => $this -> moduleBaseUrl . "&action=" . self::EDIT_PAYMENT_TYPE . '&payment_type_id=' . $_GET['payment_type_id'] );
			}
		} elseif ($selectedAction == self::GET_PAYMENTS) {
			$navLinks[] = array ('title' => _MODULE_PAGAMENTO_PAYMENTS, 'link' => $this -> moduleBaseUrl . "&action=" . self::GET_PAYMENTS );
		} elseif ($selectedAction == self::UPDATE_INVOICES_STATUS) {
			$navLinks[] = array ('title' => _MODULE_PAGAMENTO_UPDATE_INVOICES_STATUS, 'link' => $this -> moduleBaseUrl . "&action=" . self::UPDATE_INVOICES_STATUS );
		 } elseif ($selectedAction == self::VIEW_INVOICES_STATUS) {
		 	$navLinks[] = array ('title' => __PAGAMENTO_VIEW_INVOICES_STATUS_TITLE, 'link' => $this -> moduleBaseUrl . "&action=" . self::VIEW_INVOICES_STATUS );


		 }



		return $navLinks;
	}

   	public function getSidebarLinkInfo()
   	{
   		return false;
   		/*
		$currentUser = $this -> getCurrentUser();

        $xuserModule = $this->loadModule("xuser");
		if (
        	$xuserModule->getExtendedTypeID($currentUser) == "administrator" ||
        	$xuserModule->getExtendedTypeID($currentUser) == "financier"
        ) {

	       	$link_of_menu_system = array (array ('id' => 'module_boleto_link_id1',
	                                          	'title' => _MODULE_PAGAMENTO,
	                                           	'image' => $this -> moduleBaseDir.'images/pagamento',
	                                           	'_magesterExtensions' => '1',
	                                           	'link'  => $this -> moduleBaseUrl));
       		return array ( "system" => $link_of_menu_system);
        }
        */
   	}
    public function addScripts()
    {
    	return array("jquery/jquery.filetree");
    }

    private function makePaymentOptions()
    {
    	$smarty = $this->getSmartyVar();

    	$selectedAction = $this->getCurrentAction();

		$options = array();
	/*
		$options[] = array(
			'text' 		=> _MODULE_PAGAMENTO_PAYMENTS,
			'hint'		=> __PAGAMENTO_PAYMENT_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/graph.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . self::GET_PAYMENTS,
			'selected'	=> $selectedAction == self::GET_PAYMENTS,

		);
	*/
		$options[] = array(
			'text' 		=> __PAGAMENTO_VIEW_INVOICES_STATUS,
			// 'hint'		=> __PAGAMENTO_VIEW_INVOICES_STATUS_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/graph.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . self::VIEW_INVOICES_STATUS,
			'selected'	=> $selectedAction == self::VIEW_INVOICES_STATUS
		);
		$options[] = array(
			'text' 		=> __XPAYMENT_VIEW_INVOICES_PENDING,
			// 'hint'		=> __PAGAMENTO_VIEW_INVOICES_PENDING_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_download.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . self::VIEW_INVOICES_PENDING,
			'selected'	=> $selectedAction == self::VIEW_INVOICES_PENDING
		);
		$options[] = array(
			'text' 		=> __PAGAMENTO_UPDATE_INVOICES_STATUS,
			// 'hint'		=> __PAGAMENTO_UPDATE_INVOICES_STATUS_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/cloud_upload.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . self::UPDATE_INVOICES_STATUS,
			'selected'	=> $selectedAction == self::UPDATE_INVOICES_STATUS
		);
		$options[] = array(
			'text' 		=> __XPAYMENT_VIEW_TO_SEND_LIST,
			// 'hint'		=> __PAGAMENTO_UPDATE_INVOICES_STATUS_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/mail.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . self::VIEW_TO_SEND_INVOICES_LIST,
			'selected'	=> $selectedAction == self::VIEW_TO_SEND_INVOICES_LIST
		);

		$options[] = array(
			'text' 		=> _MODULE_PAGAMENTO_PAYMENT_TYPES,
			// 'hint'		=> __PAGAMENTO_PAYMENT_TYPES_HINT,
			'image' 	=> "/themes/sysclass/images/icons/small/grey/cog_2.png",
			'href' 		=> $this->moduleBaseUrl . '&action=' . self::GET_PAYMENT_TYPES,
			'selected'	=> $selectedAction == self::GET_PAYMENT_TYPES
		);

		$smarty -> assign("T_" . $this->getName() . "_OPTIONS", $options);
	}

	private function makeInvoicesListFilters($iesField = null, $parcelaField = null)
	{
		// MAKING FORM FILTERS
		if (is_null($iesField)) {
			$iesField = 'pag2ies.ies_id';
		}
		if (is_null($parcelaField)) {
			$parcelaField = 'ret.parcela_index';
		}

		$smarty = $this->getSmartyVar();


		$filterForm = new HTML_QuickForm(__CLASS__ . "_view_invoices_status_form_filter", "post", $_SERVER['REQUEST_URI'], null, null, true);

		$userIES = $this->getCurrentUserIes();

		if (count($userIES) > 1) {
			$iesData = array();

			$iesData[0] = __SELECT_ONE_OPTION;

			foreach ($userIES as $iesItem) {
				$iesData[$iesItem['id']] = $iesItem['nome'];
			}

			$filterForm -> addElement('select', 'ies_id', __XPAYMENT_SELECT_IES, $iesData, 'class = ""');
		}

		$filterForm -> addElement('advcheckbox', 'show_grouped', __XPAYMENT_SHOW_GROUPED_FILTER, null, '', array(0, 1));

		$filterForm -> addElement('jquerydate', 'start_date', __XPAYMENT_START_END_DATE_FILTER, 'class = "no-button"');
		$filterForm -> addElement('jquerydate', 'end_date', __XPAYMENT_UNTIL, 'class = "no-button"');


		$iesIds = $this->getCurrentUserIesIDs();
		$iesIds[] = 0;

		$courses = MagesterCourse::getAllCourses(array(
			'return_objects'	=> false,
			'active'			=> true,
			'ies_id'			=> $iesIds
		));

		$course_list[-1] = __XCOURSE_ALL_COURSES;
		foreach ($courses as $course) {
			$course_list[$course['id']] = $course['name'];
		}

		$filterForm -> addElement('select', 'courses', __XPAYMENT_COURSE_LIST, $course_list, 'class = ""');

		$parcelaTypeData = array(
			0	=> __XPAYMENT_LIST_FILTER_ALL,
			1 	=> __XPAYMENT_LIST_FILTER_ONLY_REGISTRATION,
			-2 	=> __XPAYMENT_LIST_FILTER_ONLY_NO_REGISTRATION,
			2	=> "Parcela n&ordm; 2",
			3	=> "Parcela n&ordm; 3",
			4	=> "Parcela n&ordm; 4",
			5	=> "Parcela n&ordm; 5",
			6	=> "Parcela n&ordm; 6",
			7	=> "Parcela n&ordm; 7",
			8	=> "Parcela n&ordm; 8",
			9	=> "Parcela n&ordm; 9",
			10	=> "Parcela n&ordm; 10"
		);

		$filterForm -> addElement('select', 'parcela_type', __XPAYMENT_SHOW_ONLY, $parcelaTypeData, 'class = ""');
		$filterForm -> addElement('submit', 'submit_apply', __XPAYMENT_FILTER_APPLY);

		$formWhere = array();

		$showGrouped = false;

		if ($filterForm->isSubmitted() && $filterForm->validate()) {

			switch ($GLOBALS['configuration']['date_format']) {
				case "YYYY/MM/DD": {
					$date_format = 'Y/m/d'; break;
				}
				case "MM/DD/YYYY": {
					$date_format = 'm/d/Y'; break;
				}
				case "DD/MM/YYYY":
				default: {
					$date_format = 'd/m/Y'; break;
				}
			}


			if ($filterForm->elementExists('ies_id')) {;
				$iesID = $filterForm->exportValue('ies_id');
				if ($iesID > 0) {
					$formWhere[] = sprintf("%s = %d", $iesField, $iesID);
				}
			}
			if ($filterForm->elementExists('parcela_type')) {;
				$parcela_type = $filterForm->exportValue('parcela_type');
				if ($parcela_type == -1) {
					$formWhere[] = $parcelaField . " = 1";

				} elseif ($parcela_type == -2) {
					$formWhere[] = $parcelaField . " > 1";
				} elseif ($parcela_type > 0) {
					$formWhere[] = $parcelaField . " = " . $parcela_type;
				}
			}

			if ($filterForm->elementExists('show_grouped')) {
				$showGrouped = $filterForm->exportValue('show_grouped') == 1;
			}

			if ($filterForm->elementExists('start_date')) {

				list($day, $month, $year) = sscanf($filterForm->exportValue('start_date'), '%02d/%02d/%04d');
				if (
					!is_null($day) &&
					!is_null($month) &&
					!is_null($year)
				) {
					$startDate = new DateTime("$year-$month-$day");
					$formWhere[] = sprintf(
						"inv.data_vencimento >= '%s'",
						$startDate->format("Y-m-d")
					);
				}
			}

			if ($filterForm->elementExists('end_date')) {
				list($day, $month, $year) = sscanf($filterForm->exportValue('end_date'), '%02d/%02d/%04d');
				if (
					!is_null($day) &&
					!is_null($month) &&
					!is_null($year)
				) {
					$startDate = new DateTime("$year-$month-$day");
					$formWhere[] = sprintf(
						"inv.data_vencimento <= '%s'",
						$startDate->format("Y-m-d")
					);
				}

			}
			if ($filterForm->elementExists('courses')) {
				$courseID = $filterForm->exportValue('courses');
				if ($courseID > 0) {
					$formWhere[] = "c.id = " . $courseID;
				}
			}
			if ($filterForm->elementExists('classes')) {
			}

		}

		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$filterForm -> accept($renderer);

		$smarty -> assign('T_' . $this->getName() . '_FILTER_FORM', $renderer -> toArray());

		$this->addModuleData("list_is_group", $showGrouped);

        $smarty -> assign("T_PAGAMENTO_LIST_IS_GROUPED", $showGrouped);
   		//$smarty -> assign ("T_XPAYMENT_MOD_DATA", $this->getModuleData());

		return $formWhere;
	}



	protected function getIesPaymentDefaults($ies_id = null, $defaults = array())
	{
		count($defaults) == 0 ? $defaults = $this->getPaymentRootDefault() : $defaults;

		if (!is_null($ies_id)) {
			/*
				CREATE TABLE IF NOT EXISTS `module_xpayment_ies_defaults` (
				`ies_id` mediumint(8),
				`vencimento` mediumint(8) NOT NULL default '5',
				`desconto` decimal(15,4) NOT NULL default '5.0000',
				`parcelas` mediumint(8) NOT NULL default '10',
				`payment_type_id` mediumint(8) NOT NULL default '1',
				`emitir_vencidos` tinyint(1) default '0',
				PRIMARY KEY  (`ies_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
			 */
			$iesDefaults = sC_getTableData(
				"module_xpayment_ies_defaults",
				"vencimento, desconto, parcelas, payment_type_id, emitir_vencidos",
				"ies_id = " . $ies_id);

			if ($iesDefaults) {
				return array_merge($defaults, $iesDefaults[0]);
			}
		}
		return $defaults;
	}
	protected function getCoursePaymentDefaults($course_id = null, $defaults = array())
	{
		count($defaults) == 0 ? $defaults = $this->getPaymentRootDefault() : $defaults;

		if (!is_null($course_id)) {
			/*
			CREATE TABLE IF NOT EXISTS `module_xpayment_course_defaults` (
			  `course_id` mediumint(8) NOT NULL DEFAULT '0',
			  `vencimento` mediumint(8) NOT NULL DEFAULT '5',
			  `desconto` decimal(15,4) NOT NULL DEFAULT '5.0000',
			  `parcelas` mediumint(8) NOT NULL DEFAULT '10',
			  `payment_type_id` mediumint(8) NOT NULL DEFAULT '1',
			  `emitir_vencidos` tinyint(1) DEFAULT '0',
			  PRIMARY KEY (`course_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			*/

			$courseDefaults = sC_getTableData(
				"module_xpayment_course_defaults def " .
				"LEFT JOIN courses cour ON ( def.course_id = cour.id )",
				"cour.id as course_id, cour.start_date, def.vencimento, def.desconto, def.parcelas, def.payment_type_id, def.emitir_vencidos",
				"cour.id = " . $course_id
			);
			if (count($courseDefaults) > 0) {
				if (($courseDefaults[0]['start_date']) != 0) {
					$courseDefaults[0]['data_inicio'] = date('Y-m-d', $courseDefaults[0]['start_date']);
				}
				unset($courseDefaults[0]['start_date']);

				return array_merge($defaults, $courseDefaults[0]);
			} else {
				return array_merge($defaults, array('course_id' => $course_id));
			}
		}
		return $defaults;
	}
	protected function getClassePaymentDefaults($classe_id = null, $defaults = array())
	{
		count($defaults) == 0 ? $defaults = $this->getPaymentRootDefault() : $defaults;

		if (!is_null($course_id)) {
			//$classeDefaults = sC_getTableData("module_xpayment_classe_defaults", "*", "classe_id = " . $classe_id);
			if ($classeDefaults) {
				return array_merge($defaults, $classeDefaults);
			}
		}
		return $defaults;
	}
	public function getPaymentDefaults($ies_id = null, $courses_id = null, $classe_id = null)
	{
		if (is_null($ies_id)) {
			if (!is_null($courses_id)) {
				$courseObject = new MagesterCourse($courses_id);
				$ies_id = $courseObject->course['ies_id'];
			}
		}

		$defaults = $this->getPaymentRootDefault();

		$iesDefaults = $this->getIesPaymentDefaults($ies_id, $defaults);
		$courseDefaults = $this->getCoursePaymentDefaults($courses_id, $iesDefaults);
		$classeDetails = $this->getClassePaymentDefaults($classe_id, $courseDefaults);
		return $classeDetails;
	}
	protected function getPaymentRootDefault()
	{
		// GET IES DEFAULTS
		return array(
			/** @todo substituir todos os "IDs" somente pelo id da matricula or "enrollment" */
			'enrollment_id'		=> null,
			'user_id'			=> null,
			'course_id'			=> null,
			// GET FROM COURSE DEFAULT
			'vencimento'		=> 5,
			'desconto'			=> 5,
			'parcelas'			=> 10,
			'payment_type_id'	=> 1,
			/** @todo Inserir opção para selecionar estes dados abaixo de cobrança... Incluir "default" no registro de curso. */
			'data_inicio'		=> date_create_from_format('d/m/Y', sprintf('01/%s/%s', (date('m')+1 > 12 ? 1 : date('m')+1), date('Y')))->format('Y-m-01'),
			'emitir_vencidos'	=> '0'
		);
	}

    /* PAYMENT MANIPULATION FUNCTIONS */
	public function getPaymentTypeById($payment_type_id)
	{
		if (sC_checkParameter($payment_type_id, 'id')) {
			$paymentData = sC_getTableData(
				"module_pagamento_types",
				"*",
				sprintf("payment_type_id = %d AND active = 1", $payment_type_id)
			);

			if (count($paymentData) > 0) {
				$paymentData = $paymentData[0];
				$paymentData['tag'] = json_decode($paymentData['tag'], true);

				return $paymentData;
			}
		}
		// RETURN TRUE IF RECORD NOT EXISTS
		return false;
	}

	public static function getPaymentTypes()
	{
		$paymentData = sC_getTableData(
			"module_pagamento_types",
			"*",
			"active = 1"
		);

		if (count($paymentData) > 0) {
			$paymentResult = array();
			foreach ($paymentData as $paymentItem) {
				$paymentItem['tag'] = json_decode($paymentItem['tag'], true);

				$paymentResult[] = $paymentItem;
			}

			return $paymentResult;
		}
		// RETURN TRUE IF RECORD NOT EXISTS
		return false;
	}

	public function updatePaymentTypeById($payment_type_id, $fields)
	{
		if (sC_checkParameter($payment_type_id, 'id')) {
			$result = sC_updateTableData(
				"module_pagamento_types",
				$fields,
				sprintf("payment_type_id = %d", $payment_type_id)
			);

			if ($result) {
				return true;
			}
		}
		return false;
	}

	public function getPaymentIdByInvoiceId($invoice_id)
	{
		$invoiceResult = sC_getTableDataFlat(
			"`module_pagamento_invoices` inv",
			"inv.payment_id",
			sprintf("inv.invoice_id = '%s'", $invoice_id)
		);

		if (count($invoiceResult) > 0) {
			$paymentIDs = $invoiceResult['payment_id'];
			/*
			if ($loadAll === TRUE) {
				return $this->getPaymentById($paymentID);
			}
			*/
			return $paymentIDs;
		}
	}


	public function getPaymentById($payment_id)
	{
		if (sC_checkParameter($payment_id, 'id')) {
			// RETURS ONE REGISTER PER INVOICE.....
			$paymentResult = sC_getTableData("
				`module_pagamento` pag,
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
					$responsibleData 		= sC_getTableData("module_xuser_responsible", "*", "type='parent' AND id = ".  $paymentResult[0]['user_id']);
					$paymentData['cliente']	= $responsibleData[0];
					/*
					$paymentData['minor']					= $paymentData['usuario'];
					$paymentData['cliente']['has_minor']	= true;

					$paymentData['cliente']['surname']		= $responsibleData[0]['surname'];
					$paymentData['cliente']['email'] 		= $responsibleData[0]['email'];
					*/
				} elseif ($paymentData['send_to'] == 'financial') {
					$responsibleData 		= sC_getTableData("module_xuser_responsible", "*", "type='financial' AND id = ".  $paymentResult[0]['user_id']);
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

	public function getPaymentsByUserId($userID, $completeData = true)
	{
		if (sC_checkParameter($userID, 'id')) {
			$result = array();

			if (!$completeData) {
				return $this->getPayments('user_id = ' . $userID);
			}

			$paymentIDs = sC_getTableData("module_pagamento", 'payment_id', 'user_id = ' . $userID);

			foreach ($paymentIDs as $payment) {

				$result[] = $this->getPaymentById($payment['payment_id']);
			}
			return $result;
		}
		return false;
	}

	public function getPayments($filter = null)
	{
		$fields = array(
			"DISTINCT pag.payment_id",
			"pag.user_id",
			"pag.data_registro",
			"pag.send_to",
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
				WHERE pag.payment_id = inv.payment_id AND inv.status_id = 2
			) as total_parcelas_pagas"
		);
/*
		prepareGetTableData(
			"`module_pagamento` pag, `users`",
			implode(', ', $fields),
			sprintf("pag.user_id = users.id", $payment_id) . (!is_null($filter) ? ' AND ' . $filter : "")
		);
*/

		$paymentDbResult = sC_getTableData(
			"`module_pagamento` pag, `users`",
			implode(', ', $fields),
			sprintf("pag.user_id = users.id", $payment_id) . (!is_null($filter) ? ' AND ' . $filter : "")
		);
		$paymentResult = array();
		foreach ($paymentDbResult as $item) {
			$item['total_pago'] = is_null($item['total_pago']) ? 0 : $item['total_pago'];
			$item['total_parcelas_pagas'] = is_null($item['total_parcelas_pagas']) ? 0 : $item['total_parcelas_pagas'];

			$item['total_saldo']	= $item['total_valor']	- $item['total_pago'];

			$paymentResult[] = $item;
		}
		return $paymentResult;
	}

	public function updateInvoiceById($payment_id, $parcela_index, $fields)
	{
		if (sC_checkParameter($payment_id, 'id')) {
			$result = sC_updateTableData(
				"module_pagamento_invoices",
				$fields,
				sprintf("
					payment_id = %d AND parcela_index = %d",
					$payment_id,
					$parcela_index
				)
			);

			if ($result) {
				return true;
			}
		}
		return false;
	}

   	public function getCoursesByPaymentId($paymentID, $returnObjects = true)
   	{
   		$courses = MagesterCourse::getAllCourses(
   			array(
   				'condition' 		=> "id IN (SELECT course_id FROM module_pagamento_courses_to_payments WHERE payment_id = " . $paymentID . ')',
   				'return_objects'	=> $returnObjects
   			)
   		);

   		return $courses;
   	}

   	public static function getAllInvoiceStatus()
   	{
   		return sC_getTableData("module_pagamento_invoices_status", "*");
   	}

   	public function filterInvoiceByStatus($invoices, $filters, $include = true)
   	{
   		$result = array();
   		foreach ($invoices as $inv_index => $invoice) {
   			$matched = true;
   			foreach ($filters as $key => $value) {
   				if ($invoice[$key] != $value) {
   					$matched = false;
   				}
   			}
   			if ($matched && $include) {
   				$result[$inv_index] = $invoice;
   			} elseif (!$matched && !$include) {
   				$result[$inv_index] = $invoice;
   			}
   		}
   		return $result;
   	}

   	public function createCancelationInvoice($allInvoices)
   	{
   		$filterInvoices = $this->filterInvoiceByStatus(
   			$allInvoices,
   			array('pago' => 0, 'bloqueio' => 0, 'status_id' => 1),
   			false
   		);

   		$lastIndex = -1;

   		foreach ($filterInvoices as $key => $inv) {
   			$lastIndex = max($key, $lastIndex);
   		}

   		$cancelationInvoice = $allInvoices[$lastIndex];

   		$lastVencimento = strtotime($cancelationInvoice['data_vencimento']);

   		$invoicesSummary = $this->calculateInvoiceSummary($allInvoices);

   		$cancelPercent = 10; /** @todo Get this data from payment method data */

   		$cancelFee = $invoicesSummary['total_to_receive'] * ($cancelPercent / 100);

   		$cancelVencimento = mktime(
   			0, 0, 0, date('m', $lastVencimento)+1, date('d', $lastVencimento), date('Y', $lastVencimento)
   		);

   		$cancelationInvoice['invoice_index']	= null;
   		$cancelationInvoice['parcela_index']	= self::CANCEL_INVOICE_INDEX;
   		$cancelationInvoice['status_id'] 		= 1;
   		$cancelationInvoice['data_registro']	= date('Y-m-d H:i:s');
   		$cancelationInvoice['data_vencimento']	= date('Y-m-d H:i:s', $cancelVencimento);
   		$cancelationInvoice['valor']			= $cancelFee;
   		$cancelationInvoice['valor_desconto']	= $cancelFee;

   		$cancelationInvoice['status'] 			= 'Pendente';
   		$cancelationInvoice['pago'] 			= 0;
		$cancelationInvoice['bloqueio']			= 0;
		$cancelationInvoice['tag']				= null;

   		return $cancelationInvoice;
   	}

   	/* SUB-MODULE FUNCTIONS */
    protected function getAvaliableSubModules($loadModules = false)
    {
    	if (is_null($this->avaliableSubModules) || count($this->avaliableSubModules) == 0) {
    		$this->loadAvaliableSubModules();
    	}

    	$selectedIndex = $loadModules ? 'object' : 'title';

		$subModules = array();
		foreach ($this->avaliableSubModules as $moduleKey => $moduleValue) {
			$subModules[$moduleKey] = $moduleValue[$selectedIndex];
		}
		return $subModules;
    }

	protected function getSubModule($subModuleName)
	{
		if (is_null($this->avaliableSubModules) || count($this->avaliableSubModules) == 0) {
    		$this->loadAvaliableSubModules();
    	}
    	if (array_key_exists($subModuleName, $this->avaliableSubModules)) {
    		return $this->avaliableSubModules[$subModuleName]['object'];
    	}
    	return false;
	}

	private function loadAvaliableSubModules()
	{
		if (is_null($this->avaliableSubModules) || count($this->avaliableSubModules) == 0) {
			$existingModules = $this->loadAllModules(true);

			$this->avaliableSubModules = array();

			foreach ($existingModules as $moduleKey => $moduleObject) {
				if (
					strpos($moduleKey, __CLASS__) !== FALSE &&
					$moduleKey !== __CLASS__
				) {
					$moduleObject->setParentContext($this);
					$this->avaliableSubModules[$moduleKey] = array(
						'title'		=> $moduleObject->getTitle(),
						'object'	=> $moduleObject
					);
				}
			}
		}
		return $this->avaliableSubModules;
	}

	protected function calculateInvoiceSummary($invoices)
	{
	   	$invoiceSummary = array(
  	   		'total_debt'		=> 0,
  	   		'total_paid'		=> 0,
  	   		'total_sended'		=> 0,
	   		'total_delay'		=> 0,
  	   		'total_to_receive'	=> 0
  	   	);
  	   	if (is_array($invoices)) {
			foreach ($invoices as $key => $inv) {
				$invValue = floatval($inv['valor']);
				$invoiceSummary['total_debt'] += $invValue;
				if ($inv['pago']) {
					$invoiceSummary['total_paid'] += $invValue;
				} elseif (
					strtotime($inv['data_vencimento']) !== FALSE &&
					strtotime($inv['data_vencimento']) < time()
				) {
					$invoiceSummary['total_delay'] += $invValue;
				} elseif ($inv['status_id'] == 2) {
					$invoiceSummary['total_sended'] += $invValue;
				} else {
					$invoiceSummary['total_to_receive'] += $invValue;
				}
	  		}
  	   	}
  	   	return $invoiceSummary;

		/*
<div class="grid_4">
	<label>Débito Total:</label>
	<span class="invoice-summary-item">{$T_PAYMENT_INVOICES_SUMMARY.total_debt}</span>
</div>
<div class="grid_4">
	<label>Valor Pago:</label>
	<span class="invoice-summary-item">{$T_PAYMENT_INVOICES_SUMMARY.total_paid}</span>
</div>
<div class="grid_4">
	<label>Valor Emitido/em Atraso:</label>
	<span class="invoice-summary-item">{$T_PAYMENT_INVOICES_SUMMARY.total_sended}</span>
</div>
<div class="grid_4">
	<label>Valor a Receber:</label>
	<span class="invoice-summary-item">{$T_PAYMENT_INVOICES_SUMMARY.total_to_receive}</span>
</div>
		*/



	}

	private function makeEditUserPaymentForm($context = null)
	{
		$smarty = $this->getSmartyVar();

		if (is_null($context)) {
			$context = $this;
		}
   		// LOAD PAYMENT DATA FROM $data['user'].
   		$userPayments = $this->getPaymentsByUserId($context->getEditedUser()->user['id']);

   		$hasCourses = false;

   		$form = new HTML_QuickForm(__CLASS__ . "_payment_type_select", "post", $_SERVER['REQUEST_URI'], null, null, true);

   		$payment_types_data = self::getPaymentTypes();
   		$payment_types = array();
   		foreach ($payment_types_data as $payment_type) {
   			$payment_types[$payment_type['payment_type_id']] = $payment_type['title'];

   		}
   		$form -> addElement("select", "payment_type_id", _PAGAMENTO_SELECTTYPE, $payment_types, 'class = "large"');
   		$form -> addElement("text", "vencimento", _PAGAMENTO_TYPE_VENCIMENTO, 'class = "small"');
   		$form -> addElement("jquerydate", "data_matricula", __PAGAMENTO_FORM_DATA_MATRICULA, 'class = "medium"');
   		$form -> addElement("jquerydate", "data_inicio", __PAGAMENTO_FORM_DATA_INICIO, 'class = "medium"');
   		$form -> addElement("text", "desconto", _PAGAMENTO_TYPE_DESCONTO, 'class = "small"');
   		$form -> addElement("text", "parcelas", _PAGAMENTO_TYPE_PARCELAS, 'class = "small"');

   		if (count($userPayments) == 0) {
   			// USUÁRIO NÃO TEM PAGAMENTO REGISTRADO
   			// ENVIAR INFORMAÇÔES NECESSÁRIAS PARA MOSTRAR TABELA INICIAL
   			$userPayments = array(
   				array(
   					'courses'	=> null
   				)
  			);

  			$smarty -> assign("T_MODULE_PAGAMENTO_NOPAYMENTREGISTERED", true);

  			$form->setDefaults(array(
  				'payment_type'	=> 1,
  				'vencimento'	=> 5,
  				'desconto'		=> 5,
  				'parcelas'		=> 10
  			));

   		} else {
   			$smarty -> assign("T_MODULE_PAGAMENTO_NOPAYMENTREGISTERED", false);

   			/* GET FROM PAYMENT_DATA */
   			if (strtotime($userPayments[0]['invoices'][1]['data_vencimento'])) {
   				$form->setDefaults(array(
   					'data_matricula'	=> date('d/m/Y', strtotime($userPayments[0]['invoices'][1]['data_vencimento'])),
   				));
   			}

   			if (strtotime($userPayments[0]['data_inicio'])) {
   				$form->setDefaults(array(
   					'data_inicio'	=> date('d/m/Y', strtotime($userPayments[0]['data_inicio'])),
   				));
   			}
  			$form->setDefaults(array(
  				'payment_type'	=> $userPayments[0]['payment_type_id'],
  				'vencimento'	=> $userPayments[0]['vencimento'],
  				'desconto'		=> number_format($userPayments[0]['desconto'], 2, ',', ''),
  				'parcelas'		=> count($userPayments[0]['invoices'])
  			));

  			$form->getElement('parcelas')->freeze();
   		}

   		foreach ($userPayments as &$payment) {
   			if (is_numeric($payment['payment_id'])) {
   				$userCourses = $context->getEditedUser()->getUserCourses(
		   			array(
		   				'condition' 		=> sprintf("id = %d", $payment['course_id']),
		   				'return_objects'	=> false
   					)
				);

   			} else {
   				$userCourses = $context->getEditedUser()->getUserCourses(array('return_objects' => false));
   			}

   			foreach ($userCourses as $userCourse) {

   				if (MagesterUser :: isStudentRole($userCourse['user_type'])) {
   					$course = array(
   						'id'				=> $userCourse['id'],
   						'name'				=> $userCourse['name'],
   						'course_type'		=> $userCourse['course_type'],
   						'enable_presencial'	=> $userCourse['enable_presencial'],
   						'enable_web'		=> $userCourse['enable_web'],
   						'classes'			=> array()
   					);

   					if ($course['course_type'] == 'Via Web') {
   						$course['price'] = $userCourse['price_web'];
   					} elseif ($course['course_type'] == 'Presencial') {
   						$course['price'] = $userCourse['price_presencial'];
   					} else {
   						$course['price'] = $userCourse['price'];
   						$course['course_type'] = _PAGAMENTO_COURSETYPENOSELECTED;
   					}

   					$courseClass = MagesterCourseClass::getClassForUserCourse($context->getEditedUser()->user['id'], $userCourse['id'], array('return_objects' => false));

   					if (count($courseClass) > 0) {
   						foreach ($courseClass as $class) {
   							$course['classes'][] = array(
   								'id'			=> $class['id'],
   								'name'			=> $class['name'],
   								'start_date'	=> $class['start_date'],
   								'end_date'		=> $class['end_date'],
   								'schedules'		=> $class['schedules']
   							);
   						}
   					}
   					$courses[] = $course;
   				}
   			}

   		   	$payment['courses'] = $courses;

  		   	$hasCourses = count($payment['courses']) > 0;

  		   	// MAKE SUMMARY DATA AND INJECT INTO PAYMENTS

  		   	$payment['invoices_summary'] = $this->calculateInvoiceSummary($payment['invoices']);
   		}

		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
        $smarty -> assign('T_MODULE_PAGAMENTO_METHOD_SELECT_FORM', $renderer -> toArray());

        $smarty -> assign("T_MODULE_PAGAMENTO_HOOK_PAYMENTS", $userPayments);
	}


	// CAN RETURN A TEMPLATE
   	public function receiveEvent($context, $action, $data = null)
   	{
		$smarty = $this->getSmartyVar();

   		if (get_class($context) == 'module_xuser') {
   			if ($action == 'edit_xuser') {
   				// EDIÇÃO DE USUÁRIO, INCLUIR FORMULÁRIO COM INFORMAÇÔES FINANCEIRAS, CASO SEJA ALUNO
   				if (
   					$context->getEditedUser()->getType() != "administrator" &&
   					!($context->getEditedUser() instanceof MagesterAdministrator) &&
   					in_array($context->getEditedUser()->getType(), array_keys($context->getEditedUser()->getLessonsRoles()))
   				) {
   				//if ($context->getEditedUser()->isStudentRole($context->getEditedUser()->getType())) {
   					// CALL TO SET SMARTY TEMPLATES VAR
   					$this->getSmartyTpl();

   					$smarty -> assign("T_XPAYMENT_EDITED_USER", $context->getEditedUser()->user);

            		$this->makeEditUserPaymentForm($context);

            		$formUpdateInvoice = new HTML_QuickForm(__CLASS__ . "_update_invoice", "post", $_SERVER['REQUEST_URI'] . "#Detalhes_financeiros", null, null, true);

            		/*
            		$invoices_statuses_data = self::getAllInvoiceStatus();
   					$invoices_statuses = array();
   					foreach ($invoices_statuses_data as $invoices_status) {
   						if ($invoices_status['id'])
   						$invoices_statuses[$invoices_status['id']] = $invoices_status['descricao'];

   					}
   					$formUpdateInvoice -> addElement("select", "status_id", _PAGAMENTO_SELECTSTATUS, $invoices_statuses, 'class = "large"');
   					*/
   					$formUpdateInvoice -> addElement("text", "vencimento", _PAGAMENTO_SELECT_VENCIMENTO, 'class = "large" alt="date"');

					$rendererInvoice = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            		$formUpdateInvoice -> accept($rendererInvoice);
            		$smarty -> assign('T_PAGAMENTO_INVOICE_UPDATE_FORM', $rendererInvoice -> toArray());

   					$smarty -> assign("T_CURRENT_USER", $this->getCurrentUser());
   					$smarty -> assign("T_EDITED_USER", $data['editedUser']->user);



            		/*
            		$invoices_statuses_data = self::getAllInvoiceStatus();
   					$invoices_statuses = array();
   					foreach ($invoices_statuses_data as $invoices_status) {
   						if ($invoices_status['id'])
   						$invoices_statuses[$invoices_status['id']] = $invoices_status['descricao'];

   					}
   					$formUpdateInvoice -> addElement("select", "status_id", _PAGAMENTO_SELECTSTATUS, $invoices_statuses, 'class = "large"');
   					*/
					$defaults = array(
						"sender_sacado"				=> 'student',
   						"sender_student_ammount"	=> 100,
						"sender_parent_ammount"		=> 0,
						"sender_financial_ammount"	=> 0,
						"sender_student"			=> 1,
						"sender_parent"				=> 0,
						"sender_financial"			=> 0
					);


   					$userAmmount = sC_getTableData("module_xpayment_user_ammount_types", "user_id, ammount_type, value", "user_id = " . $context->getEditedUser()->user['id']);

					if (count($userAmmount) > 0) {
						foreach ($userAmmount as $item) {
							$type = $item['ammount_type'];
							$defaults['sender_' . $type] = 1;
							$defaults['sender_' . $type . "_ammount"] = $item['value'];
						}
					}

					$userPayments = $this->getPaymentsByUserId($context->getEditedUser()->user['id']);

					foreach ($userPayments as $payment) {
						// UPDATE send_to FIELD
						$defaults['sender_sacado'] = $payment['send_to'];
						break;
					}


   					$formSenderUpdate = new HTML_QuickForm(__CLASS__ . "_update_sender", "post", $_SERVER['REQUEST_URI'], null, null, true);
					$formSenderUpdate -> addElement("hidden", "sender_student_ammount");
					$formSenderUpdate -> addElement("hidden", "sender_parent_ammount");
					$formSenderUpdate -> addElement("hidden", "sender_financial_ammount");

					$senderSacados = array(
						'student'	=> __XPAYMENT_STUDENT,
						'parent'	=> __XPAYMENT_PARENT,
						'financial'	=> __XPAYMENT_FINANCIAL
					);

					$formSenderUpdate -> addElement("select", "sender_sacado", __XPAYMENT_SACADO, $senderSacados, 'class="sender_toogle inline"');

   					$formSenderUpdate -> addElement("advcheckbox", "sender_student", __XPAYMENT_SEND_TO_STUDENT, null, 'class="sender_toogle inline"', array(0,1));
					$formSenderUpdate -> addElement("advcheckbox", "sender_parent", __XPAYMENT_SEND_TO_PARENT,  null, 'class="sender_toogle inline"', array(0,1));
					$formSenderUpdate -> addElement("advcheckbox", "sender_financial", __XPAYMENT_SEND_TO_FINANCIAL,  null, 'class="sender_toogle inline"', array(0,1));

					$formSenderUpdate -> addElement('submit', 'sender_submit', __SAVE, 'class = "button_colour round_all"');

					if ($formSenderUpdate->isSubmitted() && $formSenderUpdate->validate()) {
						$values = $formSenderUpdate->exportValues();

						//$userPayments = $this->getPaymentsByUserId($context->getEditedUser()->user['id']);

						foreach ($userPayments as $payment) {
							// UPDATE send_to FIELD
							sC_updateTableData("module_pagamento", array(
								'send_to' => $values['sender_sacado'],
							), sprintf("payment_id = %d", $payment['payment_id']));
							$defaults['sender_sacado'] = $values['sender_sacado'];
						}

						// CHECK AMMOUNT
						if ($values['sender_student_ammount'] + $values['sender_parent_ammount'] + $values['sender_financial_ammount'] > 100) {
							if ($values['sender_parent_ammount'] + $values['sender_financial_ammount'] > 100) {
								$values['sender_parent_ammount']	= 0;
								$values['sender_financial_ammount'] = 0;
							}

							$values['sender_student_ammount'] = 100 -
									($values['sender_parent'] == 1 ? $values['sender_parent_ammount'] : 0) -
									($values['sender_financial'] == 1 ? $values['sender_financial_ammount'] : 0);
						}
						sC_deleteTableData("module_xpayment_user_ammount_types", "user_id = " . $context->getEditedUser()->user['id']);

						if ($values['sender_parent'] == 1 && $values['sender_parent_ammount'] > 0) {
							$data[] = array(
								'user_id'		=> $context->getEditedUser()->user['id'],
								'ammount_type'	=> 'parent',
								'value'			=> $values['sender_parent_ammount']
							);

							// SETTING DEFAULTS
							$defaults['sender_parent']	= 1;
							$defaults['sender_parent_ammount']	= $values['sender_parent_ammount'];

						}
						if ($values['sender_financial'] == 1 && $values['sender_financial_ammount'] > 0) {
							$data[] = array(
								'user_id'		=> $context->getEditedUser()->user['id'],
								'ammount_type'	=> 'financial',
								'value'			=> $values['sender_financial_ammount']
							);

							// SETTING DEFAULTS
							$defaults['sender_financial']	= 1;
							$defaults['sender_financial_ammount']	= $values['sender_financial_ammount'];
						}
						if ($values['sender_student'] == 1 && $values['sender_student_ammount'] > 0) {

							$values['sender_student_ammount'] = 100 -
								$values['sender_financial_ammount'] -
								$values['sender_parent_ammount'];

							$data[] = array(
								'user_id'		=> $context->getEditedUser()->user['id'],
								'ammount_type'	=> 'student',
								'value'			=> $values['sender_student_ammount']
							);

							// SETTING DEFAULTS
							$defaults['sender_student']	= 1;
							$defaults['sender_student_ammount']	= $values['sender_student_ammount'];
						}
						if (count($data) > 0) {
							sC_insertTableDataMultiple("module_xpayment_user_ammount_types", $data);
						}
					}
					$formSenderUpdate -> setDefaults($defaults);
					foreach ($defaults as $name => $value) {
						$elem=$formSenderUpdate->getElement($name);
						$elem->setValue($value);
					}

					//$formSenderUpdate -> setValue($defaults);


					$rendererSender = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
            		$formSenderUpdate -> accept($rendererSender);
            		$smarty -> assign('T_XPAYMENT_SENDER_UPDATE_FORM', $rendererSender -> toArray());

   					//if ($hasCourses) {

	   					$context->appendTemplate(array(
	   						'title'			=> _MODULE_PAGAMENTO_USER_PAYMENT_DETAILS,
	   						'template'		=> $this->moduleBaseDir . 'templates/hook/module_xuser.edit_xuser.hook.tpl',
	   						'contentclass'	=> 'block'
	   					));

   					//}
   				}
   			}
   		} elseif (get_class($context) == 'module_xcourse') {
   			if ($action == 'edit_xcourse') {
	   			return array(
	   				'title'			=> _PAGAMENTO_COURSE_PAYMENT_DETAILS,
	   				'template'		=> $this->moduleBaseDir . 'templates/hook/module_xcourse.edit_xcourse.hook.tpl',
	   				'contentclass'	=> 'block'
	   			);
   			}
   		}
		return false;
   	}

	/** REVISAR ESTAS FUNÇÔES */
/*
   	private function formatPaymentRow($dbRow, $vencido)
   	{
		if ($vencido) {
			$dbRow['valor_string'] = formatPrice($dbRow['valor']);
		} else {
			$dbRow['valor_string'] = formatPrice($dbRow['valor_desconto']);
		}

		$dbRow['data_emissao'] = strtotime($dbRow['data_emissao']);

		return $dbRow;
   	}

   	public function registerPayment($login, array $courses, $payment_type, $send_emails = true)
   	{
   		$user = MagesterUserFactory :: factory($login);

   	   	if (count($courses) == 0) {
   			return false;
   		}

		$payments_types = array(
			'BOLETO_DIA5',
			'BOLETO_DIA15',
			'BOLETO_VISTA',
			'VISA_6X',
			'VISA_3X',
			'VISA_VISTA'
		);
   		if (!in_array($payment_type, $payments_types)) {
   			return false;
   		}

   		//1. register payment
		$paymentData = array(
			'user_id'	=> $user->user['id']
		);

		$payment_id = sC_insertTableData("c_payments", $paymentData);

		// 2. link courses on payments
		foreach ($courses as $courseID) {
			$paymentCourseData = array(
				'payment_id'	=> $payment_id,
				'course_id'		=> $courseID
			);
			sC_insertTableData("c_payments_courses", $paymentCourseData);
		}

		// 3. inject parcelas and values
		// 3.1 Get courses values
		$courses = MagesterCourse ::getAllCourses(array('condition'	=> sprintf('id IN (%s)', implode(',', $courses))));

		$courseValues = array();
		foreach ($courses as $courseObject) {
			$courseValues[$courseObject->course['id']] = $courseObject->course['price'];
		}
		// 3.2 Calculate course values based on payment_type
		$selectedTotalValue = array_sum($courseValues);
		$selectedTotalCourses = count($courses);
		$percentual_desconto 	= ( $selectedTotalCourses > 1 ? 0.1 : 0.05);

		switch ($payment_type) {
			case 'BOLETO_VISTA' :
			case 'VISA_VISTA' : {
				$valor_vista_desconto = $selectedTotalValue - ( $selectedTotalValue * $percentual_desconto );

				$paymentParcelasData = array(
					'payment_id'		=> $payment_id,
					'parcela_index'		=> 1,
					'payment_module'	=> $payment_type == 'BOLETO_VISTA' ? 'module_pagamento_boleto' : 'module_pagamento_visa',
					'payment_type'		=> $payment_type,
					'valor_desconto'	=> $valor_vista_desconto,
					'valor'				=> $selectedTotalValue,
					'status'			=> 2 // 1. registrado, 2. Pendente, 3. Pago, 4. Cancelado
				);

				sC_insertTableData("c_payments_parcelas", $paymentParcelasData);


				break;
			}
			case 'BOLETO_DIA5' :
			case 'BOLETO_DIA15' : {

				$matricula				= $selectedTotalValue / 10;
				//$valor_vista_desconto 	= $selectedTotalValue - ( $selectedTotalValue * $percentual_desconto );
				// INSERE VALOR DE MATRICULA
				$paymentParcelasData = array(
					'payment_id'		=> $payment_id,
					'parcela_index'		=> 1,
					'payment_module'	=> 'module_pagamento_boleto',
					'payment_type'		=> $payment_type,
					'valor_desconto'	=> $matricula,
					'valor'				=> $matricula,
					'status'			=> 2 // 1. registrado, 2. Pendente, 3. Pago, 4. Cancelado
				);

				sC_insertTableData("c_payments_parcelas", $paymentParcelasData);

				// INSERE MENSALIDADES
				$mensalidade 			= ( $selectedTotalValue - $matricula ) / 9;
				$mensalidade_desconto 	= $mensalidade - ( $percentual_desconto * $mensalidade );

				for ($idx = 2; $idx <= 10; $idx++) {
					$paymentParcelasData = array(
						'payment_id'		=> $payment_id,
						'parcela_index'		=> $idx,
						'payment_module'	=> 'module_pagamento_boleto',
						'payment_type'		=> $payment_type,
						'valor_desconto'	=> $mensalidade_desconto,
						'valor'				=> $mensalidade,
						'status'			=> 1 // 1. registrado, 2. Pendente, 3. Pago, 4. Cancelado
					);

					sC_insertTableData("c_payments_parcelas", $paymentParcelasData);
				}

				break;
			}
			case 'VISA_3X' :
			case 'VISA_6X' : {
				if ($payment_type == 'VISA_3X') {
					$total_parcelas = 3;
				} elseif ($payment_type == 'VISA_6X') {
					$total_parcelas = 6;
				}
				$valorParcela				= $selectedTotalValue / $total_parcelas;
				//$valor_vista_desconto 	= $selectedTotalValue - ( $selectedTotalValue * $percentual_desconto );
				// INSERE VALOR DE MATRICULA
				$paymentParcelasData = array(
					'payment_id'		=> $payment_id,
					'parcela_index'		=> 1,
					'payment_module'	=> 'module_pagamento_visa',
					'payment_type'		=> $payment_type,
					'valor_desconto'	=> $valorParcela,
					'valor'				=> $valorParcela,
					'status'			=> 2 // 1. registrado, 2. Pendente, 3. Pago, 4. Cancelado
				);

				sC_insertTableData("c_payments_parcelas", $paymentParcelasData);

				// INSERE MENSALIDADES
				for ($idx = 2; $idx <= $total_parcelas; $idx++) {
					$paymentParcelasData = array(
						'payment_id'		=> $payment_id,
						'parcela_index'		=> $idx,
						'payment_module'	=> 'module_pagamento_visa',
						'payment_type'		=> $payment_type,
						'valor_desconto'	=> $valorParcela,
						'valor'				=> $valorParcela,
						'status'			=> 1 // 1. registrado, 2. Emitido, 3. Pago, 4. Cancelado
					);

					sC_insertTableData("c_payments_parcelas", $paymentParcelasData);
				}
				break;
			}
		}

		$result = array('success' => true);
		if (
			$payment_type == 'BOLETO_VISTA' ||
			$payment_type == 'BOLETO_DIA5'	||
			$payment_type == 'BOLETO_DIA15'
) {
			$result =  $this->hookPaymentSubModule($payment_id, $payment_type);
		}
		if ($send_emails) {
		}

		return $result;
   	}

   	private function hookPaymentSubModule($payment_id, $payment_type = null)
   	{
   		// GET SUB-MODULES AND CALL
   		if (is_null($payment_type)) {
   			// LOAD FROM $payment_id
   		}

   		$data = sC_getTableData(
   			"c_payments_types, c_payments_types_details",
   			"module_class_name",
   			"c_payments_types.payment_type_id = c_payments_types_details.payment_type_id
				AND index_name = '" . $payment_type . "'"
   		);

   		if (count($data) > 0 && isset($data[0]['module_class_name'])) {
   			$module_name = $data[0]['module_class_name'];

   			$modules = sC_loadAllModules(true);

   			if (array_key_exists($module_name, $modules)) {
				//$folder = $modules[$module_name]->moduleBaseDir;
				//$className = $modules[$module_name]->className;

       			//require_once ($folder.$className.".class.php");

       			$hookModule = $modules[$module_name];

       			return $hookModule->onNewPaymentRegistered($payment_id);
   			}
   		}
   		return false;
   	}
	*/
}
