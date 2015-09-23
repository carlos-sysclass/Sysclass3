<?php
class DashboardController extends AbstractSysclassController
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
	 * @url GET /dashboard
     * @url GET /dashboard/:dashboard_id
     * @url GET /dashboard/:dashboard_id/:clear
	 */
	public function dashboardPage($dashboard_id, $clear)
	{
        $currentUser = $this->getLoggedUser(true);

        $this->putScript("plugins/jquery.isonscreen/jquery.isonscreen");
        // CHECK IF USER EXISTS, AND IF THIS MATCH CURRENT USER TYPE
/*
        if ($user_type != 0 && ($user_type == $currentUser->user['user_types_ID'] || $user_type == $currentUser->getType())) {
        } else {
            $user_type = $currentUser->user['user_types_ID'] == 0 ? $currentUser->getType() : $currentUser->user['user_types_ID'];
        }
*/
        //$layout_index = $currentUser->user['user_types_ID'] == 0 ? $currentUser->getType() : $currentUser->getType() . "." . $currentUser->user['user_types_ID'];

        $dashboardManager = $this->module("dashboard");

        if ($dashboard_id !== 0 && $dashboardManager->layoutExists($dashboard_id)) {
        } else {
            $dashboard_id = $currentUser['dashboard_id'] == "default" ? $currentUser['user_type'] : $currentUser['dashboard_id'];
        }

        $pageLayout = $dashboardManager->loadLayout($dashboard_id, ($clear == "clear"));

        $widgets = $dashboardManager->getPageWidgets();

        foreach($widgets as $key => $widget) {
            call_user_func_array(array($this, "addWidget"), $widget);
        }

        //$pageLayout = $layoutManager->getLayout('dashboard.' . $currentUser['dashboard_id']);
        //$pageLayout = $layoutManager->getLayout('stats');

        $this->putItem("page_layout", $pageLayout);

        //if ($currentUser['user_type'] == "student") {
            $this->putBlock("institution.social-gadgets");
        //}

            $widgets = array_slice($widgets, 0, 1);

        //$topbarMenu = $layoutManager->getMenuBySection("topbar");

        //$this->putItem("topbar_menu", $topbarMenu);

        //$this->putBlock("institution.social-gadgets");
        parent::display('pages/dashboard/default.tpl');
	}

}
