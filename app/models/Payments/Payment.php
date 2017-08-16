<?php
namespace Sysclass\Models\Payments;

use DateTime;
use Plico\Mvc\Model;
use Sysclass\Models\Content\ProgramPrice;

class Payment extends Model {
	public function initialize() {
		//parent::initialize();
		$this->setSource("mod_payment");

		$this->belongsTo("enroll_id", "Sysclass\\Models\\Enrollments\\CourseUsers", "id", array('alias' => 'enrollment'));

		$this->hasMany("id", "Sysclass\\Models\\Payments\\PaymentItem", "payment_id", array('alias' => 'paymentItems'));

		//$this->hasMany("id", "Sysclass\\Models\\Payments\\Currency", "payment_id", array('alias' => 'prices'));

	}
	/**
	 * Return the next unpaid invoice, generates if it's not exists
	 * @return [type] [description]
	 */
	public function getNextInvoice() {
		// CALCULATE BASED ON CURRENCY_CODE
		$enroll = $this->getEnrollment();

		if ($enroll && $program = $enroll->getProgram()) {
			$price = ProgramPrice::findFirst([
				'conditions' => 'program_id = ?0 AND currency_code = ?1',
				'bind' => [$program->id, $enroll->currency_code],
			]);

			$item = PaymentItem::findFirst([
				'conditions' => 'payment_id = ?0 AND status_id IN (1, 4)',
				'bind' => [$this->id],
				'order' => 'vencimento ASC',
			]);

			if (!$item) {
				$item = new PaymentItem();
				$item->payment_id = $this->id;
				$item->vencimento = $this->calculateNextInvoiceDate($price);
				$item->price = $this->calculateNextInvoicePrice();
				$item->status_id = 1;

				$item->save();
			} else {
				$item->price = $this->calculateNextInvoicePrice($price);
				$item->save();

			}
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

	protected function calculateNextInvoicePrice($price) {
		if ($price) {
			$total_price = $price->price_total;
		} else {
			$total_price = $this->price_total;
		}
		return floatval($total_price) / floatval($this->price_step_units);

	}
}
