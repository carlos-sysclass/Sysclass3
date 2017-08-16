<?php
namespace Sysclass\Controllers;

use Sysclass\Models\Enrollments\CourseUsers;
use Sysclass\Models\Payments\Currency;

class WelcomeController extends \AbstractSysclassController {
	// ABSTRACT - MUST IMPLEMENT METHODS!
	//
	/**
	 * @Get("/welcome")
	 * @Get("/welcome/{enroll_id}")
	 *
	 */
	public function index($enroll_id) {

		$test_id = 92;
		$test_is_done = false;

		// LOAD RESOURCES (LIKE INVTARGET)

		// DISPLAY USING THE NEW SYSTEM(OR MAYBE ON 3.7)
		//

		// SHOW A PAGE CONTIAING THE PRE-REQUISITES FOR ENTERING THE SYSTEM,
		// STEPS:
		// 1. ADDITIONAL FIELDS
		// 2. FILLING PRE REQUISITES
		// 3. MAKING PAYMENTS

		//$this->user
		//

		$this->putComponent("select2");
		$this->putComponent("bootstrap-wizard");

		$this->putCss("css/custom");

		$this->putBlock("tests.info.dialog");

		$this->putScript("scripts/pages/welcome");

		$this->putItem("user", $this->user);

		$attrs = [];
		foreach ($this->user->getAttrs() as $record) {
			//var_dump($record->toArray());
			$attrs[$record->field_name] = $record->field_value;
		}

		$this->putItem("user_attrs", $attrs);

		if (is_null($enroll_id)) {
			$enrollments = $this->user->getUserCourses([
				'conditions' => 'status_id IN (2,3)',
				'order' => 'created ASC',
				'limit' => 1,
			]);
			$enrollment = $enrollments->getFirst();
			$enroll_id = $enrollment->id;
		} else {
			$enrollment = CourseUsers::findFirstById($enroll_id);
		}

		$this->putItem("enroll_id", $enroll_id);

		// LOAD COURSE INFO (PAYMENT VALUES)
		$program = $enrollment->getProgram();

		$payment = $this->user->loadPaymentAccount($enroll_id);

		//$this->putItem("current_user", $this->user);
		$this->putItem("enrollment", $enrollment);
		$this->putItem("program", $program);
		$this->putItem("payment", $payment);

		$currencies = Currency::find();

		$this->putItem("currencies", $currencies);

		// GET THE TEST GRADE AND SET IF THE USER CAN PASS
		$execution = $this->user->getExecutions([
			'conditions' => 'pass = 1 AND test_id = ?0',
			'bind' => [$test_id],
		]);

		if ($execution) {
			$test_is_done = true;

			$this->putItem("execution", $execution->getFirst());
			$this->putItem("execution_is_done", $test_is_done);

		}

		parent::display('pages/welcome/default.tpl');
	}

}
