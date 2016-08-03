<?php
namespace Sysclass\Models\Payments;

use Plico\Mvc\Model;

class PaymentTransacao extends Model
{
    public function initialize()
    {
        $this->setSource("mod_payment_transacao");        
    }
}
