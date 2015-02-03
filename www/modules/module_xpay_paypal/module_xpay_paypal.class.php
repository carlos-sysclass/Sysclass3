<?php
require_once (dirname(__FILE__) . '/../module_xpay/module_xpay_submodule.interface.php');

define("CIELO", "1001734898");
define("CIELO_CHAVE", "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");

define("PAYPAL_USER", "seller_1327709174_biz_api1.ult.com.br");
define("PAYPAL_PASS", "1327709198");
define("PAYPAL_SIGN", "AUlxWAwlqu8gTJlNPprpz1upbUEWAxrPyRm4fSiBbsxUWlO2Ne15SgJb");

class module_xpay_paypal extends MagesterExtendedModule implements IxPaySubmodule
{
	protected static $subModules = null;
/*
	protected $conf = array(
		// Opção => Autorizar transação autenticada e não-autenticada
		'authorization'					=> 2,
		'auto_capture'					=> "false",
		// [A - Débito, 1- Crédito, 2 - loja, 3 - Administradora]
		'payment_subdivision_method'	=> 3
	);
*/
	public function getName()
	{
		return "XPAY_PAYPAL";
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
			//'title'		=> __XPAY_PAYPAL_DO_PAYMENT,
			'baselink'	=> $this->moduleBaseLink,
			'options'	=> array (
				"paypal"		=> "Paypal"
			)
		);
	}

	public function initPaymentProccess($payment_id, $invoice_id, array $data)
	{
		// CREATE FORM
		$smarty = $this->getSmartyVar();

		if (!is_object($this->getParent())) {
			$currentContext = $this;
		} else {
			$currentContext = $this->getParent();
		}

		$form = new HTML_QuickForm("xpay_paypal_init_payment", "post", $_SERVER['REQUEST_URI'], "", null, true);
		$form -> registerRule('checkParameter', 'callback', 'sC_checkParameter');

		/*
		 $bandeiras = $this->getPaymentInstances();

		//$form -> addElement('select', 'bandeira' , __XPAY_CIELO_BANDEIRA, $bandeiras,'class="inputText" id="fatherBranch"');
		foreach ($bandeiras as $key => $item) {
		$form -> addElement('radio', 'bandeira', $item, null, $key, 'class="bandeiras"');
		}
		*/
/*
		$form -> addElement("hidden", "bandeira", $data['option']);

		$parcelas = array (
				"A"	=> "Débito",
				"1"	=> "Crédito À Vista",
				"2"	=> "2x s/ juros",
				"3"	=> "3x s/ juros",
				"4"	=> "4x s/ juros",
				"5"	=> "5x s/ juros",
				"6"	=> "6x s/ juros"
		);
		foreach ($parcelas as $key => $item) {
			$form -> addElement('radio', 'qtde_parcelas', $item, $item, $key, 'class="qtde_parcelas"');
		}

		$form -> addRule('qtde_parcelas', _THEFIELD.' "'.__XPAY_QTDE_PARCELAS.'" '._ISMANDATORY, 'required', null, 'client');
*/
		$form -> addElement('submit', 'xpay_paypal_submit', __XPAY_PAYPAL_MAKE, 'class = "button_colour round_all"');

		if ($form -> isSubmitted() && $form -> validate()) {
			/*
			$Pedido = $this->processPaymentForm($payment_id, $invoice_id, $form->exportValues());

			$this->injectJS("jquery/jquery.fancybox");

			$smarty -> assign("T_XPAY_CIELO_URL_AUTENTICACAO", $Pedido->urlAutenticacao);

			$invoiceData = $this->getParent()->getInvoiceById($payment_id, $invoice_id);

			// SAVE TRANSACTION DETAILS HERE
			$fields = array(
					"payment_id"	=> $invoiceData['payment_id'],
					"tid"			=> $Pedido->tid,
					"pedido_id"		=> $Pedido->dadosPedidoNumero,
					"valor"			=> floatval($Pedido->dadosPedidoValor) / 100,
					"data"			=> date("Y-m-d H:i:s", strtotime($Pedido->dadosPedidoData)),
					"descricao"		=> $Pedido->dadosPedidoDescricao,
					"bandeira"		=> $Pedido->formaPagamentoBandeira,
					"produto"		=> $Pedido->formaPagamentoProduto,
					"parcelas"		=> $Pedido->formaPagamentoParcelas,
					"status"		=> $Pedido->status,
			);
			$transactionID = sC_insertTableData("module_xpay_cielo_transactions", $fields);

			$fieldsLink = array(
					"payment_id"		=> $invoiceData['payment_id'],
					"parcela_index"		=> $invoiceData['parcela_index'],
					"transaction_id"	=> $transactionID
			);

			sC_insertTableData("module_xpay_cielo_transactions_to_invoices", $fieldsLink);
			*/
		}
		$renderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$form -> accept($renderer);
		$smarty -> assign('T_MODULE_XPAY_CIELO_FORM', $renderer -> toArray());

		$currentContext->appendTemplate(array(
			'title'			=> __XPAY_PAYPAL_DO_PAYMENT,
			'template'		=> $this->moduleBaseDir . 'templates/hook/xpay.do_payment.tpl',
			'contentclass'	=> 'blockContents'
		), $blockIndex);

		$this->assignSmartyModuleVariables();

		return true;
	}

	private function processPaymentForm($payment_id, $invoice_id, $values)
	{
		require_once (dirname(__FILE__) . '/includes/module_xpay_cielo.pedido.model.php');

		//header("Content-type: text/plain");

		$invoiceData = $this->getParent()->getInvoiceById($payment_id, $invoice_id);

		$Pedido = new Pedido_Model();

		$Pedido->formaPagamentoBandeira = $values["bandeira"];

		if ($values["qtde_parcelas"] != "A" && $values["qtde_parcelas"] != "1") {
			$Pedido->formaPagamentoProduto = $this->conf['payment_subdivision_method'];
			$Pedido->formaPagamentoParcelas = $values["qtde_parcelas"];
		} else {
			$Pedido->formaPagamentoProduto = $values["qtde_parcelas"];
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
		$Pedido->dadosPedidoValor = floor((floatval($invoiceData['valor']) + floatval($invoiceData['acrescimo'])) * 100);

		$ies = reset($this->getCurrentUserIes());

		$Pedido->dadosPedidoDescricao =
		sprintf("%s - %s - %s", $ies['nome'], $invoiceData['course'], $invoiceData['parcela_index'] == 1 ? "Matrícula" : "Mensalidade " . ($invoiceData['parcela_index'] - 1));

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
	}
	public function paymentCanBeDone($payment_id, $invoice_id)
	{
		return true;
	}
	public function getInvoiceStatusById($payment_id, $invoice_id)
	{
	}
}
/*
class module_xpay_cielo extends MagesterExtendedModule implements IxPaySubmodule
{
*/

/*
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
sC_updateTableData(
"module_xpay_cielo_transactions",
array("status" => $consultaArray['status']),
sprintf("tid = '%s'", $Pedido -> tid)
);

list($transaction) = sC_getTableData(
"module_xpay_cielo_transactions trn
LEFT JOIN module_xpay_cielo_transactions_to_invoices trn2inv ON (trn.id = trn2inv.transaction_id AND trn.payment_id = trn2inv.payment_id)
LEFT JOIN module_pagamento_invoices inv ON (trn2inv.payment_id = inv.payment_id AND trn2inv.parcela_index = inv.parcela_index)",
"trn.id, trn.tid, trn.payment_id, trn2inv.parcela_index, trn.data, trn.descricao, trn.bandeira, trn.status, trn.pedido_id, trn.valor as valor_total,
inv.valor, inv.data_vencimento",
sprintf("tid = '%s'", $Pedido -> tid));

$xpayModule = $this->loadModule("xpay");

$transaction = $xpayModule->calculateInvoiceDetails($transaction);

switch ($consultaArray['status']) {
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

$message 		= "Pagamento Autorizado";
$message_type	= "success";

// CALL EVENTS
$xpayModule->onPaymentReceivedEvent($this, array(
		'payment_id'	=> $transaction['payment_id'],
		'parcela_index'	=> $transaction['parcela_index']
		));

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
$status 		= "Em autenticação";
break;
}
default : {
$status = "Transação não encontrada";
}
}

$smarty -> assign("T_XPAY_CIELO_CONSULTA", $consultaArray);

$smarty -> assign("T_XPAY_CIELO_TRANS", $transaction);

} else {
$message 		= "Pagamento não efetuado";
$message_type	= "failure";
}
$smarty -> assign("T_XPAY_CIELO_STATUS", $status);
$smarty -> assign("T_XPAY_CIELO_MESSAGE_TYPE", $message_type);
$smarty -> assign("T_XPAY_CIELO_MESSAGE", $message);

//echo $this->moduleBaseDir . "templates/actions/return_payment.tpl";
$this->assignSmartyModuleVariables();
echo $smarty -> fetch($this->moduleBaseDir . "templates/actions/return_payment.tpl");
exit;
}
*/
/*
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
*/
