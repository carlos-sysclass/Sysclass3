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
        $messages = $this->module("messages");

        var_dump($messages);
        exit;

		$this->putItem("page_layout", array(
			"rows" => array(
				array(
					1	=> array("weight" => "8"),
					2	=> array("weight" => "4")
				),
				array(
					3	=> array("weight" => "12")
				),
			),
			'sortable'	=> false
		));

        $this->addWidget("blank", array(
        	"title" => "User overview"
        ), 1);
        $this->addWidget("blank", array(
        	"title" => "messages"
        ), 1);
        $this->addWidget("blank", array(
        	"title" => "Course Overview"
        ), 1);
        $this->addWidget("blank", array(
        	"title" => "Forums"
        ), 1);

        $this->addWidget("blank", array(
        	"title" => "Institution Overview"
        ), 2);

        $this->addWidget("blank", array(
        	"title" => "Ads"
        ), 2);

        $this->addWidget("blank", array(
        	"title" => "Contact Us"
        ), 2);

        $this->addWidget("blank", array(
        	"title" => "We are here to help"
        ), 2);

        $this->addWidget("blank", array(
                "title" => "System Improvements"
        ), 2);

        $this->addWidget("blank", array(
               	"title" => "Calendar"
        ), 3);
        

        parent::display('pages/dashboard/student.tpl');
	}

}
