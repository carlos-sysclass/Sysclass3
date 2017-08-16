<?php
namespace Sysclass\Services\Payments\Backend;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment as PayPayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Phalcon\Mvc\User\Component;
use Sysclass\Models\Payments\Payment;
use Sysclass\Models\Payments\PaymentItem;
use Sysclass\Services\Payments\PaymentInterface;

class Paypal extends Component implements PaymentInterface {

	protected static $apiContext = null;
	protected $debug;

	public function initialize($debug = false) {
		$this->debug = $debug;
	}

	public function getApiContext() {
		if (is_null(self::$apiContext)) {
			self::$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					'AVnYcJlI1BZMtTCb3c0_WItiOYT4BDu5GmD07Vs9YgexIZom6_vUgzDroLgUu9JlsSpbLE2zc9PdzEuz', // ClientID
					'EMnYy7LldGxzLBaIRFf8Bw5Ko6CU5Qes1Ps54jH4XjYipU-TKnvNURwQh_tFLK6SGe6viGGR-U7_C10h' // ClientSecret
				)
			);
		}
		return self::$apiContext;
	}

	public function create(PaymentItem $item, Payment $payment) {

		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		/*
			$item1 = new Item();
			$item1->setName('Ground Coffee 40 oz')
				->setCurrency('USD')
				->setQuantity(1)
				->setSku("123123") // Similar to `item_number` in Classic API
				->setPrice(7.5);

			$itemList = new ItemList();
			$itemList->setItems([$item1]);
		*/
		$amount = new Amount();
		$amount->setCurrency($payment->currency_code)
			->setTotal($item->price);

		//A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
		$transaction = new Transaction();
		$transaction
			->setAmount($amount)
			//->setItemList($itemList)
			->setDescription("Payment description")
			->setInvoiceNumber(uniqid());

		$baseUrl =
		($this->request->isSecure() ? "https://" : "http://") . $this->request->getHttpHost();

		$redirectUrls = new RedirectUrls();
		$redirectUrls
			->setReturnUrl($baseUrl . "/module/payment/return")
			->setCancelUrl($baseUrl . "/module/payment/cancel");

		$payment = new PayPayment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions(array($transaction));

		try {
			$payment->create($this->getApiContext());
		} catch (Exception $ex) {
			//var_dump("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
			return [
				'error' => true,
				'payment' => $payment,
			];
		}
//		$approvalUrl = $payment->getApprovalLink();
		//NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
		//var_dump("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);
		$item->backend_payment_id = $payment->getId();

		$item->save();

		return [
			'error' => false,
			'payment' => $payment,
		];
	}

	public function execute(array $data) {
		$payment = PayPayment::get($data['payment_id'], $this->getApiContext());

		$execution = new PaymentExecution();
		$execution->setPayerId($data['payer_id']);

//		$transaction = new Transaction();
		//		$transaction->setAmount($amount);

		//$execution->addTransaction($transaction);

		try {
			$result = $payment->execute($execution, $this->getApiContext());

			try {
				$payment = PayPayment::get($data['payment_id'], $this->getApiContext());
			} catch (Exception $ex) {
				return [
					'error' => true,
					'payment' => $payment,
				];
			}
		} catch (Exception $ex) {
			//var_dump("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
			return [
				'error' => true,
				'payment' => $payment,
			];
		}
		return [
			'error' => false,
			'payment' => $payment,
		];
	}

	public function initiatePayment(array $data) {
		//Endpoint da API
		$apiEndpoint = 'https://api-3t.' . ($this->debug ? 'sandbox.' : "");
		$apiEndpoint .= 'paypal.com/nvp';

		/*
			            Kint::dump($data);
			            Executando a operação
			            $curl = curl_init();

			            curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
			            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			            curl_setopt($curl, CURLOPT_POST, true);
			            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			            curl_setopt($curl, CURLOPT_VERBOSE, true);
		*/

		//Baseado no ambiente, sandbox ou produção, definimos as credenciais
		//credenciais da API para o Sandbox
		$user = $this->environment->paypal->user;
		$pswd = $this->environment->paypal->pass;
		$signature = $this->environment->paypal->signature;
		$paypalURL = $this->environment->paypal->paypalURL;

		//Campos da requisição da operação SetExpressCheckout, como ilustrado acima.

		$id = $data['id'];
		$valor = floatval($data['valor']);
		$valor = round($valor, 2);

		$url = $this->request->getScheme() . "://";
		$url .= $this->request->getHttpHost();

		$requestNvp = array(
			'USER' => $user,
			'PWD' => $pswd,
			'SIGNATURE' => $signature,
			'VERSION' => '108.0',
			'METHOD' => 'SetExpressCheckout',
			'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
			'PAYMENTREQUEST_0_AMT' => $valor,
			'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
			'PAYMENTREQUEST_0_ITEMAMT' => $valor,
			'PAYMENTREQUEST_0_INVNUM' => $id,

			//'L_PAYMENTREQUEST_0_NAME0' => 'Item A',
			'L_PAYMENTREQUEST_0_DESC0' => 'Curso',
			'L_PAYMENTREQUEST_0_AMT0' => $valor,
			'L_PAYMENTREQUEST_0_QTY0' => '1',

			'RETURNURL' => $url . '/module/payment/authorized/paypal/' . $id,
			'CANCELURL' => $url . '/module/payment/cancel/paypal/' . $id,
			'BUTTONSOURCE' => 'BR_EC_EMPRESA',
		);

		//Kint::dump($data);
		//Executando a operação
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));
		curl_setopt($curl, CURLOPT_VERBOSE, true);

		$response = urldecode(curl_exec($curl));

		curl_close($curl);

		//Tratando a resposta
		$responseNvp = array();

		//GRAVAR RETORNO DO PAYPAL EM TABELA PRÒPRIA DO PAYPAL

		if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
			foreach ($matches['name'] as $offset => $name) {
				$responseNvp[$name] = $matches['value'][$offset];
			}
		}

		/*
			            // TODO: Gravar aqui os dados de requisição e retorno
			            $item = PaymentTransacao::findFirst(
			                array(
			                        'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
			                            "bind"             => array(
			                            "token"            => $token,
			                            "payment_itens_id" => $payment_itens_id
			                        )
			                    )
			            );
			            $item->status = "authorized";
			            $item->save();
			            REQUISIÇÂO = $data
			            RETORNO = $responseNvp

			            $paypalTransation = new PaypalTransaction();
			            $paypalTransation->token =
			            $paypalTransation->timestamp =
			            $paypalTransation->request = json_encode($data);
			            $paypalTransation->response = json_encode($responseNvp);
			            $paypalTransation->save();
		*/

		//Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
		//gravamos um log para depuração.
		if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
			for ($i = 0;isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
				$message = sprintf("PayPal NVP %s[%d]: %s\n",
					$responseNvp['L_SEVERITYCODE' . $i],
					$responseNvp['L_ERRORCODE' . $i],
					$responseNvp['L_LONGMESSAGE' . $i]);

				error_log($message);
			}
		}

		$paypalURL = $this->environment->paypal->paypalURL;
		$query = array(
			'cmd' => '_express-checkout',
			'token' => $responseNvp['TOKEN'],
		);

		if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {
			$response = array(
				'continue' => true,
				'action' => 'redirect',
				'redirect' => sprintf('%s?%s', $paypalURL, http_build_query($query)),
				'status' => "initiated",
				'token' => $responseNvp['TOKEN'],
				'info' => $responseNvp,
			);
		} else {
			$response = array(
				'continue' => false,
				'action' => 'message',
				//'redirect' => sprintf('%s?%s', $paypalURL, http_build_query($query)),
				'token' => $responseNvp['TOKEN'],
				'message' => $responseNvp['L_LONGMESSAGE0'],
				'status' => $responseNvp['L_SHORTMESSAGE0'],
				'info' => $responseNvp,
			);
		}

		return $response;
	}

	public function authorizePayment(array $data) {

		/* $PayerID = $this->request->getQuery('PayerID');
			        $result = array(
			            'token' => $data['token']
			        );

			        //$details = $this->checkDetailsPayment($result);

			        //INSERIR ESTES DADOS NA TABELA ESPECIFICA DO PAYPAL, (SE HOUVER)
			        $paypalTransation = new PaypalTransactionLog();
			        $paypalTransation->token =
			        $paypalTransation->timestamp =
			        $paypalTransation->request = json_encode($data);
			        $paypalTransation->response = json_encode($responseNvp);
			        $paypalTransation->save();

			        //$result['email'] = $details['EMAIL'];
			        //TODO Check todos os status de retorno do paypal e retornar true or false

			        $result['continue'] = true;

			        $result['failreason'] = $details['CHECKOUTSTATUS'];

			        //RETORNAR DADOS NA ESTRUTURA ESPERADA PELO ADAPTER
		*/
	}

	public function confirmPayment(array $data) {

		$token = $this->request->getQuery('token');
		$PayerID = $this->request->getQuery('PayerID');
		$payment_itens_id = $data['payment_itens_id'];
		$valor = floatval($data['valor']);
		$valor = round($valor, 2);

		$user = $this->environment->paypal->user;
		$pswd = $this->environment->paypal->pass;
		$signature = $this->environment->paypal->signature;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');

		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
			'USER' => $user,
			'PWD' => $pswd,
			'SIGNATURE' => $signature,
			'METHOD' => 'DoExpressCheckoutPayment',
			'VERSION' => '108',
			'LOCALECODE' => 'pt_BR',
			'TOKEN' => $token,
			'PayerID' => $PayerID,
			'PROFILESTARTDATE' => '2016-01-22T12:00:00Z',
			'DESC' => 'Exemplo',
			'BILLINGPERIOD' => 'Month',
			'BILLINGFREQUENCY' => '1',
			'AMT' => 100,
			'CURRENCYCODE' => 'USD',
			'COUNTRYCODE' => 'US',
			'NOTIFYURL' => 'http://PayPalPartner.com.br/notifyme',

			'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
			'PAYMENTREQUEST_0_AMT' => $valor,
			'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
			'PAYMENTREQUEST_0_ITEMAMT' => $valor,
			'PAYMENTREQUEST_0_INVNUM' => $payment_itens_id,

			/*
				                PAYMENTREQUEST_0_SHIPTONAME'    =>'José Silva',
				                'PAYMENTREQUEST_0_SHIPTOSTREET'  =>'Rua Main, 150',
				                'PAYMENTREQUEST_0_SHIPTOSTREET2' =>'Centro',
				                'PAYMENTREQUEST_0_SHIPTOCITY'    =>'Rio De Janeiro',
				                'PAYMENTREQUEST_0_SHIPTOSTATE'   =>'RJ',
				                'PAYMENTREQUEST_0_SHIPTOZIP'     =>'22021-001',
				                'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' =>'BR',
			*/

			'MAXFAILEDPAYMENTS' => 3,
		)));

		$response = curl_exec($curl);

		curl_close($curl);

		$nvp = array();

		if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
			foreach ($matches['name'] as $offset => $name) {
				$nvp[$name] = urldecode($matches['value'][$offset]);
			}
		}

		if (isset($nvp['PAYMENTINFO_0_ACK']) && $nvp['PAYMENTINFO_0_ACK'] == 'Success') {
			$response = array(
				'continue' => true,
			);
		} else {
			$response = array(
				'continue' => false,
			);
		}
		return $response;
	}
}