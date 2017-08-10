<?php
namespace Sysclass\Controllers;

class WelcomeController extends \AbstractSysclassController {
	// ABSTRACT - MUST IMPLEMENT METHODS!
	//
	/**
	 * * Create login and reset password forms
	 * @Get("/welcome/{enroll_id}")
	 *
	 */
	public function index($enroll_id) {
		// LOAD RESOURCES (LIKE INVTARGET)

		// DISPLAY USING THE NEW SYSTEM(OR MAYBE ON 3.7)
		//

		// SHOW A PAGE CONTIAING THE PRE-REQUISITES FOR ENTERING THE SYSTEM,
		// STEPS:
		// 1. ADDITIONAL FIELDS
		// 2. FILLING PRE REQUISITES
		// 3. MAKING PAYMENTS

		//$this->user

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

		// LOAD COURSE INFO (PAYMENT VALUES)
		//$this->user->loadPaymentAccount($enroll_id);

		parent::display('pages/welcome/default.tpl');
	}

}
