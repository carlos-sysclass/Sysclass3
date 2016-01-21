<?php
namespace Sysclass\Services\Payment\Interfaces;

interface IPayment {
    public function sendPayment(array $data);    
}
