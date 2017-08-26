<?php
namespace Sysclass\Modules\Payment;

use Sysclass\Models\Content\ProgramPrice;
use Sysclass\Models\Enrollments\CourseUsers;
use Sysclass\Models\Payments\Payment;
use Sysclass\Models\Payments\PaymentItem;
use Sysclass\Models\Payments\PaymentTransacao;

/**
 * @RoutePrefix("/module/payment")
 */
class PaymentModule extends \SysclassModule/*implements \ISummarizable,  \ILinkable *//* \IWidgetContainer */ {
	/* ISummarizable */
	public function getSummary() {
		$data = array(1);

		return array(
			'type' => 'warning',
			'count' => $data[0],
			'text' => $this->translate->translate('Payments'),
			'link' => array(
				'text' => $this->translate->translate('View'),
				'link' => $this->getBasePath(),
			),
		);
	}

	/* ILinkable */
	public function getLinks() {
		//$total_itens = User::count("active = 1");

		return array(
			'administration' => array(
				array(
					//'count' => 0,
					'text' => $this->translate->translate('Payments'),
					'icon' => 'fa fa-money',
					'link' => $this->getBasePath() . 'view',
				),
			),
		);
	}

	/**
	 * [ add a description ]
	 *
	 * @Post("/create")
	 * @Post("/create/{enroll_course_id}")
	 */
	public function createRequest($enroll_course_id) {

		if ($this->request->isAjax()) {
			$data = $this->request->getJsonRawBody(true);
		} else {
			$data = $this->request->getPost();
		}
		//$user_programs = $this->user->getUserPrograms();
		$payment = Payment::findFirst([
			'conditions' => 'enroll_id = ?0 AND user_id = ?1',
			'bind' => [$enroll_course_id, $this->user->id],
		]);

		if (!$payment) {
			$enroll = CourseUsers::findFirst([
				'conditions' => 'id = ?0 AND user_id = ?1',
				'bind' => [$enroll_course_id, $this->user->id],
			]);

			if ($enroll && $program = $enroll->getProgram()) {
				$price = ProgramPrice::findFirst([
					'conditions' => 'program_id = ?0 AND currency_code = ?1',
					'bind' => [$program->id, $enroll->currency_code],
				]);

				$payment = new Payment();
				$payment->user_id = $this->user->id;
				$payment->enroll_id = $enroll_course_id;
				if ($price) {
					$payment->price_total = $price->price_total;
				} else {
					$payment->price_total = $program->price_total;
				}
				$payment->currency_code = $enroll->currency_code;

				$payment->price_step_units = $program->price_step_units;
				$payment->price_step_type = $program->price_step_type;

				$payment->save();

				$payment->refresh();
			} else {
				$this->response->setJsonContent(
					$this->createAdviseResponse($this->translate->translate("A problem ocurred when retrieving your data. Please, try again."), "warning")
				);
				return true;
			}
		}

		$invoice = $payment->getNextInvoice();

		// SEND REQUEST TO THE
		//
		$response = $this->payments->create($invoice, $payment);

		if ($response['error']) {

		} else {
			$this->response->setJsonContent([
				'id' => $response['payment']->getId(),
			]);
		}

		return true;

	}

	/**
	 * [ add a description ]
	 *
	 * @Post("/execute")
	 * @Post("/execute/{enroll_course_id}")
	 */
	public function executeRequest($enroll_course_id) {

		$data = $this->request->getPost();

		$payment = Payment::findFirst([
			'conditions' => 'enroll_id = ?0 AND user_id = ?1',
			'bind' => [$enroll_course_id, $this->user->id],
		]);

		if ($payment) {
			$invoice = PaymentItem::findFirst([
				'conditions' => 'payment_id = ?0 AND backend_payment_id = ?1',
				'bind' => [$payment->id, $data['paymentID']],
				'limit' => 1,
			]);

			if ($invoice) {
				$response = $this->payments->execute($invoice, [
					'payment_id' => $data['paymentID'],
					'payer_id' => $data['payerID'],
				]);

				if (!$response['error'] && $response['approved']) {

					$enrollment = $payment->getEnrollment();

					$enrollment->status_id = CourseUsers::IS_PAID;
					$enrollment->save();

					$url = "/dashboard";

					$this->response->setJsonContent(
						$this->createRedirectResponse($url, $this->translate->translate("Payment received with sucess."), "success")
					);
					return $this->response;
				}
			}
		}

		$this->response->setJsonContent(
			$this->createAdviseResponse($this->translate->translate("The system cannot process your payment right now. Please, try again."), "error")
		);

		return $this->response;
	}

	/**
	 * [ add a description ]
	 *
	 * @Get("/profile")
	 */
	public function profileRequest() {
		//$response = $this->payments->profile();
	}

	/**
	 * [ add a description ]
	 *
	 * @Get("/initiate/{payment_item_id}")
	 */
	public function initiatePaymentRequest($paymentItemId) {
		//Kint::dump($paymentItemId);
		//var_dump($paymentItemId);

		$paymentItemObject = PaymentItem::findFirstById($paymentItemId);
		//exit;
		//SE DER ALGUM ERRO ENTRA NA CONDIÇÃO
		if (!$paymentItemObject) {
			$this->redirect("/module/payment/view", "Error Starting Transaction", "error");
			return;
		}

		$data = $paymentItemObject->toArray();
		$data['user'] = $paymentItemObject->getPayment()->getUser()->toArray();

		//Adapter.php => initiatePayment(array $data)
		$result = $this->payment->initiatePayment($data);

		if ($this->request->isAjax()) {

		} else {
			switch ($result['action']) {
			case "redirect":{
					$this->response->redirect($result['redirect']);
					break;
				}
			case "message":
			default:{
					$this->redirect("/module/payment/view", $this->translate->translate($result['message']), "warning");
				}
			}
		}
	}

	//função que chama o paypal
	protected function getDatatableSingleItemOptions($item) {

		if (empty($item->payment_date)) {

			//var_dump($item);
			//if (!$this->request->hasQuery('block') && $item->pending == 1) {
			return array(
				'aprove' => array(
					'icon' => 'fa fa-lock',
					'link' => "http://local.sysclass.com/module/payment/initiate/" . $item->id,
					'class' => 'btn-sm btn-info datatable-actionable tooltips',
					'attrs' => array(
						'data-original-title' => 'Make Payment',
					),
				),
			);
			//}
			return false;
		}
	}

	/**
	 * [ add a description ]
	 *
	 * @Get("/authorized/paypal/{payment_itens_id}")
	 */
	public function authorizePaymentRequest($payment_itens_id) {
		$token = $this->request->getQuery('token');
		$PayerID = $this->request->getQuery('PayerID');

		$continue = $this->payment->authorizePayment(array(
			'backend' => $backend,
			'payment_itens_id' => $payment_itens_id,
			'args' => $this->request->getQuery(),
		));

		// Adapter.php => confirmPayment
		if ($continue) {
			$this->payment->confirmPayment(array(
				'backend' => $backend,
				'payment_itens_id' => $payment_itens_id,
				'args' => $this->request->getQuery(),
			));
			$this->redirect("/module/payment/view", "Authorized by User", "sucess");
			return;
		} else {
			$this->redirect("/module/payment/view", "Error Authorize the User", "warning");
			return;
		}
	}

	/**
	 * [ add a description ]
	 *
	 * @Get("/cancel/paypal/{payment_itens_id}")
	 */
	public function cancelPaymentRequest($payment_itens_id) {
		$token = $this->request->getQuery('token');

		$item = PaymentTransacao::findFirst(
			array(
				'conditions' => "token = :token: AND payment_itens_id = :payment_itens_id:",
				"bind" => array(
					"token" => $token,
					"payment_itens_id" => $payment_itens_id,
				),
			)
		);
		$item->status = "cancel";
		$item->save();
		$this->redirect("/module/payment/view", $this->translate->translate("Cancelled by User"), "warning");
		return;
	}

	/**
	 * [ add a description ]
	 *
	 * @Get("/price/{enroll_id}/{currency_code}")
	 */
	public function getPriceRequest($enroll_id, $currency_code) {
		$payment = Payment::findFirst([
			'conditions' => 'enroll_id = ?0 AND user_id = ?1',
			'bind' => [$enroll_id, $this->user->id],
		]);

		if (!$payment) {
			$enroll = CourseUsers::findFirst([
				'conditions' => 'id = ?0 AND user_id = ?1',
				'bind' => [$enroll_id, $this->user->id],
			]);

			if ($enroll && $program = $enroll->getProgram()) {
				$payment = new Payment();
				$payment->user_id = $this->user->id;
				$payment->enroll_id = $enroll_id;
				$payment->price_total = $program->price_total;
				$payment->price_step_units = $program->price_step_units;
				$payment->price_step_type = $program->price_step_type;
				//$payment->currency_code = $enroll->enroll;

				$payment->save();
			}
		} else {
			$enroll = CourseUsers::findFirst([
				'conditions' => 'id = ?0 AND user_id = ?1',
				'bind' => [$enroll_id, $this->user->id],
			]);
		}

		if ($enroll) {

			$price = ProgramPrice::findFirst([
				'conditions' => 'program_id = ?0 AND LOWER(country) = ?1',
				'bind' => [$enroll->course_id, strtolower($this->user->country)],
			]);

			if ($price) {
				$enroll->currency_code = $currency_code;
				$enroll->save();

				// ON CAHNGE PRICE CURRENT, RECALCULATE INVOICE VALUES
				$payment->currency_code = $currency_code;
				$payment->save();

				$this->response->setJsonContent($price->toArray());
				return $this->response;
			} else {
				$this->response->setJsonContent(
					$this->createAdviseResponse($this->translate->translate("No price for selected currency"), "warning")
				);
				return true;
			}
		}
		$this->response->setJsonContent(
			$this->createAdviseResponse($this->translate->translate("A problem ocurred when retrieving your data. Please, try again."), "error")
		);
		return true;

	}

}