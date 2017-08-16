<?php
namespace Sysclass\Models\Payments;

use DateTime;
use Plico\Mvc\Model;

class Payment extends Model {
	public function initialize() {
		//parent::initialize();
		$this->setSource("mod_payment");

		$this->hasMany("id", "Sysclass\\Models\\Payments\\PaymentItem", "payment_id", array('alias' => 'paymentItems'));

	}
	/**
	 * Return the next unpaid invoice, generates if it's not exists
	 * @return [type] [description]
	 */
	public function getNextInvoice() {
		// CALCULATE BASED ON CURRENCY_CODE
		var_dump('calculate');
		exit;

		$item = PaymentItem::findFirst([
			'conditions' => 'payment_id = ?0 AND status_id IN (1, 4)',
			'bind' => [$this->id],
			'order' => 'vencimento ASC',
		]);

		if (!$item) {
			$item = new PaymentItem();
			$item->payment_id = $this->id;
			$item->vencimento = $this->calculateNextInvoiceDate();
			$item->price = $this->calculateNextInvoicePrice();
			$item->status_id = 1;

			$item->save();

		}

		return $item;
	}

	protected function calculateNextInvoiceDate() {
		/**
		 * @todo  Check for price_step_type (month, year, etc)
		 */

		if (intval(date("d")) <= $this->payday) {
			$dateTime = DateTime::createFromFormat("Y-m-d", date("Y") . "-" . date("m") . "-" . $this->payday);
		} else {
			$dateTime = DateTime::createFromFormat("Y-m-d", date("Y") . "-" . (intval(date("m")) + 1) . "-" . $this->payday);
		}

		return $dateTime->format("Y-m-d");
	}

	protected function calculateNextInvoicePrice() {
		return floatval($this->price_total) / floatval($this->price_step_units);

	}
}
