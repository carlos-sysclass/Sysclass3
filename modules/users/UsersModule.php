<?php 
class UsersModule extends SysclassModule implements ISectionMenu, IWidgetContainer
{

	// CREATE FUNCTION HERE
	public function getSectionMenu($section_id) {
		// PROVIDE ADDITIONAL ACCOUNTS MENU
		/*
		if ($section_id == "topbar") {
			$menuItem = array(
				'icon' 		=> 'comments',
				'notif' 	=> 20,
				'text'		=> self::$t->translate('You have %d new forum posts', 12),
				'external'	=> array(
					'link'	=> $this->getBasePath() . "/timeline",
					'text'	=> self::$t->translate('See all forums')
				),
				'type'		=> 'inbox',
				'items'		=> array(
					array(
						'link' 		=> $this->getBasePath() . "/timeline/1",
						
						'values' => array(
							'photo'		=> 'img/avatar2.jpg',
							'from'		=> 'Lisa Wong',
							'time'		=> 'Just Now',
							'message' 	=> 'Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh...'
						)
					)
				)
			);

			return $menuItem;
		}
		*/
		return false;
	}

	public function getWidgets() {
		$modules = $this->getModules("ISummarizable");

		$data = array();
		$data['notification'] = array();

		foreach($modules as $key => $mod) {
			$data['notification'][$key] = $mod->getSummary();
		}
		
		$data['notification'] = $this->module("layout")
			->sortModules("users.overview.notification.order", $data['notification']);

		$this->putModuleScript("users");

		return array(
			'users.overview' => array(
				'id'        => 'users-panel',
				'type'      => 'users',
				//'title' 	=> 'User Overview',
				'template'	=> $this->template("overview.widget"),
				'panel'		=> true,
				'data'      => $data
				//'box'       => 'blue'
			)
		);
	}
	/**
	 * Module Entry Point
	 *
	 * @url GET /profile
	 */
	public function profilePage()
	{
		$currentUser    = $this->getCurrentUser(true);
		// PUT HERE CHAT MODULE (CURRENTLY TUTORIA)
		$this->putComponent("select2");
		$this->putComponent("datepicker");
		$this->putComponent("pwstrength");

		// GET SUMMARY
		$summaryModules = $this->getModules("ISummarizable");
		$summary = array();
		foreach($summaryModules as $key => $mod) {
			$summary[$key] = $mod->getSummary();
		}
		$summary = $this->module("layout")->sortModules("users.overview.notification.order", $summary);

		$languages = MagesterSystem :: getLanguages(true, true);

		$timezones = sC_getTimezones();

		$userDetails = MagesterUserDetails::getUserDetails($currentUser->user['login']);
		$edit_user = array_merge($currentUser->user, $userDetails);

		$date_fmt = $this->module("settings")->get("php_date_fmt");
		$edit_user['data_nascimento'] = date_create_from_format("Y-m-d", $edit_user['data_nascimento'])->format($date_fmt);

		$userPolo = $currentUser->getUserPolo();

		$constraints = array('active' => true, 'return_objects' => false);
		$constraints['required_fields'] = array('location', 'active_in_course', 'user_type', 'completed', 'score', 'has_course', 'num_lessons');
		$userCourses = $currentUser->getUserCoursesAggregatingResults($constraints);

		$this->putData(array(
			'languages' 	=> $languages,
			'timezones' 	=> $timezones,
			'summary'   	=> $summary,
			'edit_user' 	=> $edit_user,
			'user_polo' 	=> $userPolo,
			'user_courses'	=> $userCourses
		));

		$form_actions = array(
			'personal'  => $this->getBasePath() . 'profile/personal',
			'password'  => $this->getBasePath() . 'profile/password'
		);
		$this->putItem("FORM_ACTIONS", $form_actions);
		
		$this->putCss("css/pages/profile");
		$this->display("profile.tpl");
	}

	/**
	 * Module Entry Point
	 *
	 * @url POST /profile/personal
	 */
	public function profilePersonalSaveAction()
	{
		/*
		["name"]=> string(8) "VINICIOS" 
		["surname"]=> string(13) "CUTRIM MENDES" 
		["email"]=> string(28) "vinicioscmendes@yahoo.com.br" 
		["language"]=> string(10) "portuguese" 
		["timezone"]=> string(20) "America/Buenos_Aires" 
		["short_description"]=> string(31) "dfdfdfsdfsdfsdfsdfdfsdfsdfsdfdf"
		// EXTENDED USER PROFILE
		["data_nascimento"]=> string(10) "07/19/1984" 
		*/
		$values = $_POST;
		// PUT ALL THIS DATA UNDER A MODEL, PLEASE!!!!!!
		// VALIDATE VALUES

		$currentUser    = $this->getCurrentUser(true);

		$currentUser->user['name'] = $values['name'];
		$currentUser->user['surname'] = $values['surname'];
		$currentUser->user['email'] = $values['email'];
		$currentUser->user['languages_NAME'] = $values['languages_NAME'];
		$currentUser->user['timezone'] = $values['timezone'];
		$currentUser->user['short_description'] = $values['short_description'];

		$status = $currentUser->persist();

		$extProperties = array();

		$date_fmt = $this->module("settings")->get("php_date_fmt");
		$data_nascimento = date_create_from_format($date_fmt, $values['data_nascimento']);
		if ($data_nascimento != false) {
			$extProperties['data_nascimento'] = $data_nascimento->format("Y-m-d");
			$this->_updateTableData("module_xuser", $extProperties, "id = " . $currentUser->user['id']);
		}

		$this->redirect(
			$this->getBasePath() . "profile",
			self::$t->translate("Your personal info has been saved successfully!"),
			"success"
		);
		exit;
	}
	/**
	 * Module Entry Point
	 *
	 * @url POST /profile/password
	 */
	public function profilePasswordSaveAction()
	{
		$currentUser = $this->getCurrentUser(true);
		$values = $_POST;

		$password = MagesterUser::createPassword($values['password']);
		if ($password != $currentUser->user['password']) {
			$this->redirect(
				$this->getBasePath() . "profile",
				self::$t->translate("Your old password is incorrect!"),
				"danger"
			);
			exit;
		}
		if ($values['new-password'] !== $values['new-password-confirm']) {
			$this->redirect(
				$this->getBasePath() . "profile",
				self::$t->translate("Your new password doesn't match!"),
				"warning"
			);
			exit;
		}
		$currentUser->user['password'] = MagesterUser::createPassword($values['new-password']);
		$currentUser->persist();

		$this->redirect(
			$this->getBasePath() . "profile",
			self::$t->translate("Your password has been changed successfully!"),
			"success"
		);
		exit;

	}


}
