<?php
namespace Sysclass\Models\Payments;

use Plico\Mvc\Model;

class Currency extends Model {
	public function initialize() {
		$this->setSource("mod_payment_currencies");
	}
}
