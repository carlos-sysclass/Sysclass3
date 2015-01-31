<?php
class CrudController extends BaseSysclassModule
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public function authorize()
	{
		if (parent::authorize()) {
			// USER IS LOGGED IN, CHECK FOR TYPE
			$stats = self::$current_user	= MagesterUser::checkUserAccess(false);
			return true;
		}
		return false;
	}

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /crud/:module/add
	 */
	public function crudAddPage($module)
	{
        //echo "<pre>";
        $this->createContext($module);
        //exit;

        $currentUser    = $this->getCurrentUser(true);

        /* THINK A WAY OFF INJECT ESPECIFIC FILES FOR ESPECIFIC MODULES (MAYBE CALLING FROM THE MODULE ITSELF) */
        $this->putComponent("validation");
        $this->putModuleScript("models.users");
        $this->putModuleScript("views.users.add");

        $this->putItem("page_title", self::$t->translate('Users'));
        $this->putItem("page_subtitle", self::$t->translate('Create a new User'));

        //return array_values($news);
        $this->display("form.tpl");
	}

}
