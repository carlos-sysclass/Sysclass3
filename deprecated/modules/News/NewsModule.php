<?php
namespace Sysclass\Modules\News;

/**
 * Module Class File
 * @filesource
 */
use Sysclass\Models\Announcements\Announcement;
/**
 * [NOT PROVIDED YET]
 * @package Sysclass\Modules
 */
/**
 * @RoutePrefix("/module/news")
 */
class NewsModule extends \SysclassModule implements /* IWidgetContainer, ISummarizable, ILinkable, \ISectionMenu, */ \IBreadcrumbable, \IActionable
{
	/* IWidgetContainer */
	public function getWidgets($widgetsIndexes = array(), $caller = null) {
		if (in_array('news.latest', $widgetsIndexes)) {
			//$this->putModuleScript("models.news");
			$this->putModuleScript("widget.news");

			$this->putSectionTemplate("foot", "dialogs/news.view");

			return array(
				'news.latest' => array(
					'type'      => 'news', // USED BY JS SUBMODULE REFERENCE, REQUIRED IF THE WIDGET HAS A JS MODULE
					'id'        => 'news-widget',
					'title'     => $this->translate->translate('Announcements'),
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
			'text'  => $this->translate->translate('Announcements'),
			'link'  => array(
				'text'  => $this->translate->translate('View'),
				'link'  => $this->getBasePath() . 'view'
			)
		);
	}
	*/
	
	/* ISectionMenu */
	/*
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
                'text'      => $this->translate->translate('You have %s Announcements', $total),
                'external'  => array(
                    'link'  => $this->getBasePath(),
                    'text'  => $this->translate->translate('See my statement')
                ),
                'link'  => array(
                    'link'  => $this->getBasePath(),
                    'text'  => $this->translate->translate('News')
                ),
                'type'      => 'notification',
                'items'     => $items,
                'extended'  => true
            );

            return $menuItem;
        }
        return false;
    }
	*/
	/* ILinkable */
	/*
	public function getLinks() {
		$data = $this->getItemsAction();
		//if ($this->getCurrentUser(true)->getType() == 'administrator') {
			return array(
				'communication' => array(
					array(
						'count' => count($data),
						'text'  => $this->translate->translate('Announcements'),
						'icon'  => 'icon-bell',
						'link'  => $this->getBasePath() . 'view'
					)
				)
			);
		//}
	}
	*/
	/* IBreadcrumbable */
	/*
	public function getBreadcrumb() {
		$breadcrumbs = array(
			array(
				'icon'	=> 'icon-home',
				'link'	=> $this->getSystemUrl('home'),
				'text'	=> $this->translate->translate("Home")
			),
			array(
				'icon'	=> 'icon-bell',
				'link'	=> $this->getBasePath() . "view",
				'text'	=> $this->translate->translate("Announcements")
			)
		);

		$request = $this->getMatchedUrl();
		switch($request) {
			case "view" : {
				$breadcrumbs[] = array('text'	=> $this->translate->translate("View"));
				break;
			}
			case "add" : {
				$breadcrumbs[] = array('text'	=> $this->translate->translate("New Annoucement"));
				break;
			}
			case "edit/:id" : {
				$breadcrumbs[] = array('text'	=> $this->translate->translate("Edit Annoucement"));
				break;
			}
		}
		return $breadcrumbs;
	}
	*/
	/* IActionable */
	/*
	public function getActions() {
		$request = $this->getMatchedUrl();

		$actions = array(
			'view'	=> array(
				array(
					'text'      => $this->translate->translate('New Annoucement'),
					'link'      => $this->getBasePath() . "add",
					'class'		=> "btn-primary",
					'icon'      => 'icon-plus'
				),
				array(
					'separator'	=> true,
				),
				array(
					'text'      => 'Add New 2',
					'link'      => $this->getBasePath() . "add",
					//'class'		=> "btn-primary",
					//'icon'      => 'icon-plus'
				)
			)
		);

		return $actions[$request];
	}
	*/

	/**
	 * Get all news visible to the current user
	 *
	 * @url GET /item/me/:id
	 */
	/*
	public function getItemAction($id) {

		$editItem = $this->model("news")->getItem($id);
		// TODO CHECK IF CURRENT USER CAN VIEW THE NEWS
		return $editItem;
	}
	*/
	/**
	 * [ add a description ]
	 *
	 * @url POST /item/me
	 */
	/*
	public function addItemAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			$itemModel = $this->model("news");
			$data['login'] = $userData['login'];
			if (($data['id'] = $itemModel->addItem($data)) !== FALSE) {
				return $this->createRedirectResponse(
					$this->getBasePath() . "edit/" . $data['id'],
					$this->translate->translate("News saved with success"),
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
	*/
	/**
	 * [ add a description ]
	 *
	 * @url PUT /item/me/:id
	 */
	/*
	public function setItemAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			$itemModel = $this->model("news");
			if ($itemModel->setItem($data, $id) !== FALSE) {
				$response = $this->createAdviseResponse($this->translate->translate("News updated with success"), "success");
				return array_merge($response, $data);
			} else {
				// MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
				return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
			}
		} else {
			return $this->notAuthenticatedError();
		}
	}
	*/
	/**
	 * [ add a description ]
	 *
	 * @url DELETE /item/me/:id
	 */
	/*
	public function deleteItemAction($id)
	{
		if ($userData = $this->getCurrentUser()) {
			$data = $this->getHttpData(func_get_args());

			$itemModel = $this->model("news");
			if ($itemModel->deleteItem($id) !== FALSE) {
				$response = $this->createAdviseResponse($this->translate->translate("News removed with success"), "success");
				return $response;
			} else {
				// MAKE A WAY TO RETURN A ERROR TO BACKBONE MODEL, WITHOUT PUSHING TO BACKBONE MODEL OBJECT
				return $this->invalidRequestError("Não foi possível completar a sua requisição. Dados inválidos ", "error");
			}
		} else {
			return $this->notAuthenticatedError();
		}
	}
	*/
	/**
	 * Get all news visible to the current user
	 *
	 * @url GET /items/me
	 * @url GET /items/me/:datatable
	 */
	/*
	public function getItemsAction($datatable)
	{
		$currentUser    = $this->getCurrentUser(true);
		//$dropOnEmpty = !($currentUser->getType() == 'administrator' && $currentUser->user['user_types_ID'] == 0);

		$newsRS = Announcement::find();
		foreach($newsRS as $key => $item) {
			$news[$key] = $item->toArray();
			$news[$key]['user'] = $item->getUser()->toArray();;
		}
		//$news = $this->model("news")->getItems();


		if ($type === 'datatable') {
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
	*/
	/**
	 * [ add a description ]
	 *
	 * @url GET /data
	 * @deprecated 3.0.0.19 Use GET /items/me entry point
	 */
	/*
	public function dataAction()
	{
		$currentUser    = $this->getCurrentUser(true);

		
		$news = news :: getNews(0, false);

		
		$userClasses = $this->_getTableDataFlat(
			"users_to_courses",
			"classe_id",
			sprintf("users_LOGIN = '%s'", $currentUser->user['login'])
		);

		foreach ($news as $key => $noticia) {
			if ( !in_array( $noticia['classe_id'], $userClasses['classe_id'] ) && $noticia['classe_id']!=0 ) {
				unset($news[$key]);
			//} elseif ($ajax && $noticia['classe_id']==0) {
			//    unset($news[$key]);
			}
		}
		return array_values($news);
	}
	*/
}

