<?php
namespace Sysclass\Models\Payments;

use Sysclass\Models\Enrollments\CourseUsers;

class Payment extends CourseUsers {
	public function initialize() {
		parent::initialize();

		$this->hasMany("id", "Sysclass\\Models\\Payments\\PaymentItem", "payment_id", array('alias' => 'paymentItems'));
	}

}
