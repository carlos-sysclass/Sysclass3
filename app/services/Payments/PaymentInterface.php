<?php
namespace Sysclass\Services\Payments;

//inicia a transacao
interface PaymentInterface {
	public function create(array $data);

	public function initiatePayment(array $data);
	public function authorizePayment(array $data);
	//public function checkDetailsPayment(array $data);
	//public function confirmPayment(array $data);
}