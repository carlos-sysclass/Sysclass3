<?php
namespace Sysclass\Models\Payments;

use Plico\Mvc\Model;

class PaymentItem extends Model {

	const IS_COMPLETED = 3;

	public function initialize() {
		$this->setSource("mod_payment_itens");

		$this->belongsTo("id_status", "Sysclass\\Models\\Payments\\PaymentStatus", "id", array('alias' => 'Status', 'reusable' => true));

		$this->belongsTo("payment_id", "Sysclass\\Models\\Payments\\Payment", "id", array('alias' => 'payment'));
	}

	public function beforeValidationOnCreate() {
		// GENERATE RANDOM
		$randomService = $this->getDI()->get("random");
		$this->invoice_number = $randomService->base58(32);
	}

	public function listByUser($params) {
		//pega o ID do usuario da sessao
		$di = \Phalcon\DI::getDefault();
		$user = $di->get('user');

		$payment = Payment::findFirst(array(
			'conditions' => 'user_id = ?0',
			'bind' => array($user->id),
		));

		return $payment->getItems();
	}
}
