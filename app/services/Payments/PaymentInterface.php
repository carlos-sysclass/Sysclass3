<?php
namespace Sysclass\Services\Payments;

use Sysclass\Models\Payments\Payment;
use Sysclass\Models\Payments\PaymentItem;

//inicia a transacao
interface PaymentInterface {
	public function create(PaymentItem $item, Payment $payment);
	public function execute(PaymentItem $item, array $data);

	//public function initiatePayment(array $data);
	//public function authorizePayment(array $data);
	//public function checkDetailsPayment(array $data);
	//public function confirmPayment(array $data);
}