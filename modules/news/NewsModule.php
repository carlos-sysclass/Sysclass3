<?php 
/**
 * Module Class File
 * @filesource
 */
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
class NewsModule extends SysclassModule implements IWidgetContainer, /* ISummarizable, */ ILinkable, IBreadcrumbable, IActionable, ISectionMenu
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
						//'reload'	    => 'javascript:void(0);',
						//'collapse'      => true,
						'fullscreen'    => true
					)
				)
			);
		}
	}

	/* ISummarizable */
	/*
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

	*/
	/* ISectionMenu */
	public function getSectionMenu($section_id) {
        if ($section_id == "topbar") {

            //$total = $this->getTotalUnviewed();
            
            $news = $this->getItemsAction();
            //var_dump($news);
            //exit;
            $total = count($news);

            $currentUser = $this->getCurrentUser();
            //$currentFolder = $this->getDefaultFolder($currentUser);
            
            //$messages = $this->getUnviewedMessages(array($currentFolder));

            $items = array();
            
            foreach($news as $new) {
                $items[] = array(
                    'link'	=> $this->getBasePath() . "view/" . $new['id'],
                    'text'	=> $new['title']
                );
            }
            
            $menuItem = array(
                'icon'      => 'bell',
                'notif'     => $total,
                'text'      => self::$t->translate('You have %s Announcements', $total),
                'external'  => array(
                    'link'  => $this->getBasePath(),
                    'text'  => self::$t->translate('See my statement')
                ),
                'link'  => array(
                    'link'  => $this->getBasePath(),
                    'text'  => self::$t->translate('News')
                ),
                'type'      => 'notification',
                'items'     => $items,
                'extended'  => true
            );

            return $menuItem;
        }
        return false;
    }
	/* ILinkable */
	public function getLinks() {
		$data = $this->getItemsAction();
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
			case "edit/:id" : {
				$breadcrumbs[] = array('text'	=> self::$t->translate("Edit Annoucement"));
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
	 * @deprecated Use GET /items/me entry point
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
	 * Get all news visible to the current user
	 *
	 * @url GET /item/me/:id
	 */
	public function getItemAction($id) {

		$editItem = $this->model("news")->getItem($id);		
		// TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
		return $editItem;
	}
	/**
	 * Insert a news model
	 *
	 * @url POST /item/me
	 */
	public function addItemAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			$itemModel = $this->model("news");
			$data['login'] = $userData['login'];
			if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
				return $this->createRedirectResponse(
					$this->getBasePath() . "edit/" . $data['id'],
					self::$t->translate("News saved with success"), 
					"success"
				);
			} else {
				// MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
				return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
			}
		} else {
			return $this->notAuthenticatedError();
		}
	}
	/**
	 * Update a news model
	 *
	 * @url PUT /item/me/:id
	 */
	public function setItemAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			$itemModel = $this->model("news");
			if ($itemModel->setItem($data, $id) !== FALSE) {
				$response = $this->createAdviseResponse(self::$t->translate("News updated with success"), "success");
				return array_merge($response, $data);
			} else {
				// MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
				return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
			}
		} else {
			return $this->notAuthenticatedError();
		}
	}

	/**
	 * DELETE a news model
	 *
	 * @url DELETE /item/me/:id
	 */
	public function deleteItemAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			$itemModel = $this->model("news");
			if ($itemModel->deleteItem($id) !== FALSE) {
				$response = $this->createAdviseResponse(self::$t->translate("News removed with success"), "success");
				return $response;
			} else {
				// MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
				return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
			}
		} else {
			return $this->notAuthenticatedError();
		}
	}

	/**
	 * Get all news visible to the current user
	 *
	 * @url GET /items/me
	 * @url GET /items/me/:datatable
	 */
	public function getItemsAction($datatable)
	{
		$currentUser    = $this->getCurrentUser(true);
		$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

		$newsItens = $this->model("news")->getItems();

		$news = $this->module("permission")->checkRules($newsItens, "news", 'permission_access_mode');

		if ($datatable === 'datatable') {
			$news = array_values($news);
			foreach($news as $key => $item) {
				$news[$key]['options'] = array(
					'edit'	=> array(
						'icon'	=> 'icon-edit',
						'link'	=> $this->getBasePath() . "edit/" . $item['id'],
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
			$this->putModuleScript("views.news.view");

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

		$this->putComponent("datepicker", "timepicker", "select2", "wysihtml5", "validation");
		$this->putModuleScript("models.news");
		$this->putModuleScript("views.news.add");

		$this->putItem("page_title", self::$t->translate('Announcements'));
		$this->putItem("page_subtitle", self::$t->translate('Manage, review and publish your Announcements'));

		//return array_values($news);
		$this->display("form.tpl");
	}

	/**
	 * Module Entry Point
	 *
	 * @url GET /edit/:id
	 */
	public function editPage($id)
	{
		$currentUser    = $this->getCurrentUser(true);

		$editItem = $this->model("news")->getItem($id);
		// TODO CHECK PERMISSION FOR OBJECT

		$this->putComponent("datepicker", "timepicker", "select2", "wysihtml5", "validation");

		// TODO CREATE MODULE BLOCKS, WITH COMPONENT, CSS, JS, SCRIPTS AND TEMPLATES LISTS TO INSERT
		// Ex: 
		// $this->putBlock("block-name") or $this->putCrossModuleBlock("permission", "block-name")
		$this->putBlock("permission.add");

		$this->putModuleScript("models.news");
		$this->putModuleScript("views.news.edit", array('id' => $id));

		$this->putItem("page_title", self::$t->translate('Announcements'));
		$this->putItem("page_subtitle", self::$t->translate('Manage, review and publish your Announcements'));

		$this->putItem("form_action", $_SERVER['REQUEST_URI']);
		//$this->putItem("entity", $editItem);

		//return array_values($news);
		$this->display("form.tpl");
	}

}

