<?php
namespace Sysclass\Services\Payments\Backend;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
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
	protected $profile_experience_id = "XP-XR5Y-8EUY-ZNUP-6ZZZ";

	public function initialize($debug = false) {
		$this->debug = $debug;
	}

	public function getApiContext() {
		if (is_null(self::$apiContext)) {
			self::$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					// PRODUCTION
					'AToSA-xECTQrBrXirNtfmzhpKPbn5ekO0xkJPw6SIiPhPR0V_mWdcF2T62sxOuflL1znQNT4_y9CbyHE', // ClientID
					'EPEVptgjig5y0bGrAw1a7RtyjPC0-rJz9WTjPPWgzDtG7kozLlWllz6VenM5EuTeDKDpj5J2pXuX5rQ3' // ClientSecret

					// SANDBOX
					//'AVnYcJlI1BZMtTCb3c0_WItiOYT4BDu5GmD07Vs9YgexIZom6_vUgzDroLgUu9JlsSpbLE2zc9PdzEuz', // ClientID
					//'EMnYy7LldGxzLBaIRFf8Bw5Ko6CU5Qes1Ps54jH4XjYipU-TKnvNURwQh_tFLK6SGe6viGGR-U7_C10h' // ClientSecret
				)
			);

			self::$apiContext->setConfig([
				'mode' => 'live',
			]);

		}
		return self::$apiContext;
	}

	public function create(PaymentItem $item, Payment $payment) {

		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$item1 = new Item();
		$item1
			->setName('Lucent Education')
			->setCurrency($payment->currency_code)
			->setQuantity(1)

			->setPrice(number_format($item->price, 2, '.', ''));

		$itemList = new ItemList();
		$itemList->setItems([$item1]);

		$amount = new Amount();
		$amount->setCurrency($payment->currency_code)
			->setTotal(number_format($item->price, 2, '.', ''));

		//A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
		$transaction = new Transaction();
		$transaction
			->setAmount($amount)
			//->setItemList($itemList)
			->setDescription("Installment")
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
			->setExperienceProfileId($this->profile_experience_id)
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

	public function execute(PaymentItem $item, array $data) {
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
				// ["created", "approved", "failed", "partially_completed", "in_progress"]
				$approved = $payment->getState() == "approved";
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
			'approved' => $approved,
		];
	}

	public function profile() {
		$webProfile = new \PayPal\Api\WebProfile();
		$webProfile->setId($this->profile_experience_id);
		try {
			// Execute the delete method
			$webProfile->delete($this->getApiContext());
		} catch (\PayPal\Exception\PayPalConnectionException $ex) {
		}

		// ### Create Web Profile
		// Use the /web-profiles resource to create seamless payment experience profiles. See the payment experience overview for further information about using the /payment resource to create the PayPal payment and pass the experience_profile_id.
		// Documentation available at https://developer.paypal.com/webapps/developer/docs/api/#create-a-web-experience-profile
		// Lets create an instance of FlowConfig and add
		// landing page type information
		$flowConfig = new \PayPal\Api\FlowConfig();
		// Type of PayPal page to be displayed when a user lands on the PayPal site for checkout. Allowed values: Billing or Login. When set to Billing, the Non-PayPal account landing page is used. When set to Login, the PayPal account login landing page is used.
		$flowConfig->setLandingPageType("Login");
		// The URL on the merchant site for transferring to after a bank transfer payment.
		//$flowConfig->setBankTxnPendingUrl("http://www.yeowza.com/");
		// When set to "commit", the buyer is shown an amount, and the button text will read "Pay Now" on the checkout page.
		$flowConfig->setUserAction("commit");
		// Defines the HTTP method to use to redirect the user to a return URL. A valid value is `GET` or `POST`.
		//$flowConfig->setReturnUriHttpMethod("GET");
		// Parameters for style and presentation.
		$presentation = new \PayPal\Api\Presentation();
// A URL to logo image. Allowed vaues: .gif, .jpg, or .png.
		$presentation->setLogoImage("https://lucent.sysclass.com/assets/sysclass.lucent/img/logo-login.png")
//	A label that overrides the business name in the PayPal account on the PayPal pages.
			->setBrandName("Lucent Education")
//  Locale of pages displayed by PayPal payment experience.
		//->setLocaleCode("US")
		// A label to use as hypertext for the return to merchant link.
		//->setReturnUrlLabel("Return")
		// A label to use as the title for the note to seller field. Used only when `allow_note` is `1`.
			->setNoteToSellerLabel("Thanks!");
// Parameters for input fields customization.
		$inputFields = new \PayPal\Api\InputFields();
// Enables the buyer to enter a note to the merchant on the PayPal page during checkout.
		$inputFields->setAllowNote(true)
		// Determines whether or not PayPal displays shipping address fields on the experience pages. Allowed values: 0, 1, or 2. When set to 0, PayPal displays the shipping address on the PayPal pages. When set to 1, PayPal does not display shipping address fields whatsoever. When set to 2, if you do not pass the shipping address, PayPal obtains it from the buyer’s account profile. For digital goods, this field is required, and you must set it to 1.
			->setNoShipping(1)
		// Determines whether or not the PayPal pages should display the shipping address and not the shipping address on file with PayPal for this buyer. Displaying the PayPal street address on file does not allow the buyer to edit that address. Allowed values: 0 or 1. When set to 0, the PayPal pages should not display the shipping address. When set to 1, the PayPal pages should display the shipping address.
			->setAddressOverride(0);
		// #### Payment Web experience profile resource
		$webProfile = new \PayPal\Api\WebProfile();
		// Name of the web experience profile. Required. Must be unique
		$webProfile->setName("Lucent Web Profile")
		// Parameters for flow configuration.
			->setFlowConfig($flowConfig)
		// Parameters for style and presentation.
			->setPresentation($presentation)
		// Parameters for input field customization.
			->setInputFields($inputFields)
		// Indicates whether the profile persists for three hours or permanently. Set to `false` to persist the profile permanently. Set to `true` to persist the profile for three hours.
			->setTemporary(false);
		// For Sample Purposes Only.
		$request = clone $webProfile;

		try {
			// Use this call to create a profile.
			$createProfileResponse = $webProfile->create($this->getApiContext());
		} catch (\PayPal\Exception\PayPalConnectionException $ex) {
			// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
			var_dump("ERRO Created Web Profile", "Web Profile", null, $request, $ex);
			exit(1);
		}
// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
		var_dump("Created Web Profile", "Web Profile", $createProfileResponse->getId(), $request, $createProfileResponse);

		exit;
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