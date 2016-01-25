<?php
namespace Sysclass\Services\Payment\Interfaces;

//inicia a transacao
interface IPayment {
    public function initiatePayment(array $data);
    public function authorizePayment(array $data);
    //public function checkDetailsPayment(array $data);  
    public function confirmPayment($token, $payerID, $payment_itens_id);     
}