<?php 
class StudentController extends AbstractSysclassController
{
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public function authorize()
	{
		if (parent::authorize()) {
			// USER IS LOGGED IN, CHECK FOR TYPE
			$this->current_user	= MagesterUser::checkUserAccess(false, 'student');
			return $this->current_user->user['user_type'] == 'student';
		}
		return false;
	}

	/**
	 * Create login and reset password forms
	 *
	 * @url GET /student
	 * @url GET /student/dashboard
	 */
	public function studentDashboardPage()
	{
		// DASHBOARD PAGE
        //require_once 'control_panel.php';
        //$this->putScript("scripts/portlet-draggable");
        $layoutManager = $this->module("layout");

        $pageLayout = $layoutManager->getLayout();

        $this->putItem("page_layout", $pageLayout);

        $topbarMenu = $layoutManager->getMenuBySection("topbar");

        $this->putItem("topbar_menu", $topbarMenu);

        $widgets = $layoutManager->getPageWidgets();

        foreach($widgets as $key => $widget) {
            call_user_func_array(array($this, "addWidget"), $widget);
        }

/*

        $this->addWidget("blank", array(
        	"title" => "User overview"
        ), 1);
        $this->addWidget("blank", array(
        	"title" => "Course Overview"
        ), 1);
        $this->addWidget("blank", array(
        	"title" => "Institution Overview"
        ), 2);

*/

        parent::display('pages/dashboard/student.tpl');
	}

}
