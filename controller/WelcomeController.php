<?php
namespace Sysclass\Controllers;

use Sysclass\Models\Enrollments\CourseUsers;
use Sysclass\Models\Enrollments\ProgramTests;
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

		$test_id = 94;
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
		//
		$preTests = ProgramTests::find([
			'conditions' => 'program_id = ?0',
			'bind' => [$enrollment->course_id],
		]);

		$testsInfo = [];

		if ($preTests->count() == 0) {
			$has_test = false;
			$has_undone_test = false;
			//$test_is_done = true;
		} else {
			$has_test = true;
			$has_undone_test = false;

			foreach ($preTests as $preTest) {
				if ($test = $preTest->getTest()) {
					$item = [
						'test' => $test->toArray(),
						'done' => false,
					];

					$execution = $this->user->getExecutions([
						'conditions' => 'pass = 1 AND test_id = ?0',
						'bind' => [$test->id],
					]);

					if (count($execution) > 0) {
						$item['done'] = true;
						$item['grade'] = $execution->getFirst()->user_grade;
					} else {
						$has_undone_test = true;
					}

					$testsInfo[] = $item;
				}
			}

		}
		//var_dump($testsInfo);
		//exit;

		$this->putItem("has_test", $has_test);
		$this->putItem("has_undone_test", $has_undone_test);
		$this->putItem("tests_info", $testsInfo);

		parent::display('pages/welcome/default.tpl');
	}

}
