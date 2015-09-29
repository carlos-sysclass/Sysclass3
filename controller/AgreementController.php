<?php
class AgreementController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
    /*
    protected function startEnviroment($request) {
        // @todo put here the merge content from student.php administrator.php and professor.php

        $smarty = $this->getSmarty();

        //include "new_sidebar.php";
        //var_dump($GLOBALS['currentTheme']->options['sidebar_interface']);
        ($request == 0) ? $ctg = "control_panel" : $ctg = $request;

        $smarty->assign("T_CTG", $ctg); //As soon as we derive the current ctg, assign it to smarty.
        $smarty->assign("T_OP", isset($_GET['op']) ? $_GET['op'] : false);

        //Create shorthands for user type, to avoid long variable names
        $_student_ = $_professor_ = $_admin_ = 0;
        if ((isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] == 'student') || (!isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_type'] == 'student')) {
            $_student_ = 1;
        } elseif ((isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_lesson_user_type'] == 'professor') || (!isset($_SESSION['s_lesson_user_type']) && $_SESSION['s_type'] == 'professor')) {
            $_professor_ = 1;
        } else {
            $_admin_ = 1;
        }
        $smarty->assign("_student_", $_student_);
        $smarty->assign("_professor_", $_professor_);
        $smarty->assign("_admin_", $_admin_);

        return $ctg;
    }
    */
	/**
	 * Create login and reset password forms
	 *
	 * @url GET /agreement
	 */
	public function agreementPage()
	{
        $currentUser = $this->getCurrentUser(true);

        $this->putComponent("validation", "bootstrap-switch");
        $this->putScript("scripts/pages/agreement");

        $user_language = $currentUser->getLanguage()->code;

        if (!parent::template_exists("pages/agreement/{$user_language}.tpl")) {
            $user_language = self::$t->getSystemLanguageCode();
        } else {
            //parent::display('pages/agreement/default.tpl');
        }
        parent::display("pages/agreement/{$user_language}.tpl");
        
	}

}
