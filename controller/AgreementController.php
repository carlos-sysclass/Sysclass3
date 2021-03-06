<?php
namespace Sysclass\Controllers;

class AgreementController extends \AbstractSysclassController
{
    /**
	 * Create login and reset password forms
	 * @Get("/agreement")
	 */
	public function agreementPage()
	{
        $currentUser = $this->getCurrentUser(true);

        $this->putComponent("validation", "icheck", "select2");
        $this->putScript("scripts/pages/agreement");

        $user_language = $currentUser->getLanguage()->code;

        //if (!parent::template_exists("pages/agreement/{$user_language}.tpl")) {
            $user_language = $this->translate->getSystemLanguageCode();
        //} else {
            //parent::display('pages/agreement/default.tpl');
        //}
        parent::display("pages/agreement/{$user_language}.tpl");
        
	}

}
