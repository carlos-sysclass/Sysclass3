<?php 
class NewsModule extends SysclassModule implements IWidgetContainer, ISummarizable, ILinkable, IBreadcrumbable, IActionable
{
	/* IWidgetContainer */
	public function getWidgets($widgetsIndexes = array()) {
		if (in_array('news.latest', $widgetsIndexes)) {
			$this->putModuleScript("models.news");
			$this->putModuleScript("widget.news");
			
			return array(
				'news.latest' => array(
					'type'      => 'news', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
					'id'        => 'news-widget',
					'title'     => self::$t->translate('Announcements'),
					'template'  => $this->template("news.widget"),
					'icon'      => 'bell',
					'box'       => 'dark-blue tabbable',
					
					'tools'		=> array(
						'search'        => true,
						'reload'	    => 'javascript:void(0);',
						'collapse'      => true,
						'fullscreen'    => true
					)
				)
			);
		}
	}
	/* ISummarizable */
	public function getSummary() {
		$data = $this->dataAction();
		return array(
			'type'  => 'primary',
			'count' => count($data),
			'text'  => self::$t->translate('Announcements'),
			'link'  => array(
				'text'  => self::$t->translate('View'),
				'link'  => $this->getBasePath() . 'view'
			)
		);
	}

	/* ILinkable */
	public function getLinks() {
		$data = $this->dataAction();
		//if ($this->getCurrentUser(true)->getType() == 'administrator') {
			return array(
				'communication' => array(
					array(
						'count' => count($data),
						'text'  => self::$t->translate('Announcements'),
						'icon'  => 'icon-bell',
						'link'  => $this->getBasePath() . 'view'
					)
				)
			);
		//}
	}
	/* IBreadcrumbable */
	public function getBreadcrumb() {
		$breadcrumbs = array(
			array(
				'icon'	=> 'icon-home',
				'link'	=> $this->getSystemUrl('home'),
				'text'	=> self::$t->translate("Home")
			),
			array(
				'icon'	=> 'icon-bell',
				'link'	=> $this->getBasePath() . "view",
				'text'	=> self::$t->translate("Announcements")
			)
		);

		$request = $this->getMatchedUrl();
		switch($request) {
			case "view" : {
				$breadcrumbs[] = array('text'	=> self::$t->translate("View"));
				break;
			}
			case "add" : {
				$breadcrumbs[] = array('text'	=> self::$t->translate("New Annoucement"));
				break;
			}
		}
		return $breadcrumbs;
	}
	/* IActionable */
	public function getActions() {
		$request = $this->getMatchedUrl();

		$actions = array(
			'view'	=> array(
				array(
					'text'      => self::$t->translate('New Annoucement'),
					'link'      => $this->getBasePath() . "add",
					'class'		=> "btn-primary",
					'icon'      => 'icon-plus'
				)/*,
				array(
					'separator'	=> true,
				),
				array(
					'text'      => 'Add New 2',
					'link'      => $this->getBasePath() . "add",
					//'class'		=> "btn-primary",
					//'icon'      => 'icon-plus'
				)*/
			)
		);
		


		return $actions[$request];
	}
	/**
	 * Module Entry Point
	 *
	 * @url GET /data
	 */
	public function dataAction()
	{
		$currentUser    = $this->getCurrentUser(true);

		# Carrega noticias da ultima licao selecionada
		$news = news :: getNews(0, false) /*+ news :: getNews($_SESSION['s_lessons_ID'], false)*/;

		# Filtra comunicado pela classe do aluno
		$userClasses = $this->_getTableDataFlat(
			"users_to_courses",
			"classe_id",
			sprintf("users_LOGIN = '%s'", $currentUser->user['login'])
		);
		/*
		$xentifyModule = $this->loadModule("xentify");
		$user = $this->getCurrentUser();
		*/
		foreach ($news as $key => $noticia) {
			if ( !in_array( $noticia['classe_id'], $userClasses['classe_id'] ) && $noticia['classe_id']!=0 ) {
				unset($news[$key]);
			//} elseif ($ajax && $noticia['classe_id']==0) {
			//    unset($news[$key]);
			}
			/*
			if (!$xentifyModule->isUserInScope($user, $noticia['xscope_id'], $noticia['xentify_id'])) {
				unset($news[$key]);
			}
			*/
		}
		return array_values($news);
	}
	/**
	 * Module Entry Point
	 *
	 * @url GET /items/me
	 * @url GET /items/me/:datatable
	 */
	public function itemsAction($datatable)
	{
		$currentUser    = $this->getCurrentUser(true);

		# Carrega noticias da ultima licao selecionada
		$news = news :: getNews(0, false) /*+ news :: getNews($_SESSION['s_lessons_ID'], false)*/;

		# Filtra comunicado pela classe do aluno
		$userClasses = $this->_getTableDataFlat(
			"users_to_courses",
			"classe_id",
			sprintf("users_LOGIN = '%s'", $currentUser->user['login'])
		);
		/*
		$xentifyModule = $this->loadModule("xentify");
		$user = $this->getCurrentUser();
		*/
		foreach ($news as $key => $noticia) {
			if ( !in_array( $noticia['classe_id'], $userClasses['classe_id'] ) && $noticia['classe_id']!=0 ) {
				unset($news[$key]);
			//} elseif ($ajax && $noticia['classe_id']==0) {
			//    unset($news[$key]);
			}
			/*
			if (!$xentifyModule->isUserInScope($user, $noticia['xscope_id'], $noticia['xentify_id'])) {
				unset($news[$key]);
			}
			*/
		}

		if ($datatable === 'datatable') {
			$news = array_values($news);
			foreach($news as $key => $item) {
				$news[$key]['options'] = array(
					'edit'	=> array(
						'icon'	=> 'icon-edit',
						'class'	=> 'btn-sm btn-primary'
					),
					'remove'	=> array(
						'icon'	=> 'icon-remove',
						'class'	=> 'btn-sm btn-danger'					)
				);
			}
			return array(
				'sEcho'					=> 1,
				'iTotalRecords'			=> count($news),
				'iTotalDisplayRecords'	=> count($news),
				'aaData' 				=> array_values($news)
			);
		}
		return array_values($news);
	}


	/**
	 * Module Entry Point
	 *
	 * @url GET /view
	 */
	public function viewPage()
	{
		$currentUser    = $this->getCurrentUser(true);

		// SHOW ANNOUCEMENTS BASED ON USER TYPE
		if ($currentUser->getType() == 'administrator') {
			$this->putItem("page_title", self::$t->translate('Announcements'));
			$this->putItem("page_subtitle", self::$t->translate('Manage, review and publish your Announcements'));

			$this->putComponent("select2");
			$this->putComponent("data-tables");
			$this->putModuleScript("models.news");
			$this->putModuleScript("page.news.view");

			# Carrega noticias da ultima licao selecionada
			$news = news :: getNews(0, true) /*+ news :: getNews($_SESSION['s_lessons_ID'], false)*/;

			foreach ($news as $key => $noticia) {
				//if ( !in_array( $noticia['classe_id'], $userClasses['classe_id'] ) && $noticia['classe_id']!=0 ) {
				//    unset($news[$key]);
				//} elseif ($ajax && $noticia['classe_id']==0) {
				//    unset($news[$key]);
				//}
				/*
				if (!$xentifyModule->isUserInScope($user, $noticia['xscope_id'], $noticia['xentify_id'])) {
					unset($news[$key]);
				}
				*/
			}
			//return array_values($news);
			$this->display("view.tpl");
		} else {
			$this->redirect($this->getSystemUrl('home'), "", 401);
		}
	}
	/**
	 * Module Entry Point
	 *
	 * @url GET /add
	 */
	public function addPage()
	{
		$currentUser    = $this->getCurrentUser(true);

		$this->putComponent("datepicker", "timepicker", "select2", "wysihtml5", "validation", "modal");

		$this->putCrossModuleScript("permission", "dialog.permission");
		$this->putCrossSectionTemplate("permission", "foot", "dialogs/add");


		$this->putModuleScript("page.news.add");

		$this->putItem("page_title", self::$t->translate('Announcements'));
		$this->putItem("page_subtitle", self::$t->translate('Manage, review and publish your Announcements'));

		//return array_values($news);
		$this->display("add.tpl");
	}

}

