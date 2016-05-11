<?php 
class DashboardController extends PageController
{
	
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public static function getMenuOption()
	{
		return array(
			'url'		=> '/painel/dashboard',
			'absolute'	=> true,
			'text'		=> 'Painel de Controle',
			'icon'		=> 'home',
			'on_header'	=> TRUE,
			'on_footer'	=> TRUE,
			'themes'	=> array('admin'),
			'selected'	=> PlicoLib::handler() instanceof self
		);
	}

	protected function onThemeRequest()
	{
		$this->setTheme('admin');

	}
	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url GET /dashboard
	 */
	public function dashboardPage()
	{
		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->putData(array(
			'page_title'		=> 'Painel de Controle',
			'page_description'	=> 'Resumo Geral'
		));

		$layoutManager = $this->module("layout");

		$layout = array(
			"rows" => array(
				/*
				array(
					1   => array("weight" => array(
						'lg' => "12",
						'md' => "12",
						'sm' => "12",
						'xs' => "12"
					))
				),
				*/
				array(
					2   => array("weight" => array(
						'lg' => "12",
						'md' => "12",
						'sm' => "12",
						'xs' => "12"
					))
				),
				array(
					3   => array("weight" => array(
						'lg' => "12",
						'md' => "12",
						'sm' => "12",
						'xs' => "12"
					))
				),
			),
			'widgets' => array(
				1 => array(
				//	"carousel.widget/home"
				),
				2 => array(
					'pages.widget/dashboard'
				),
				3 => array(
					'news.widget/dashboard'
				)
			),
			'sortable'  => false
		);
		// TODO CREATE A BLOCK
		//$this->putBlock("stats.page-header");
		/*
		$this->putComponent("daterangepicker");
		$this->putScript('js/views/section/page-header.stats');
		$this->putSectionTemplate('page-header', 'block/stats/header');
		*/

		$this->putCss("css/pages/home");

		$layoutManager->createLayout('home', $layout);

		$pageLayout = $layoutManager->getLayout('home');

		$this->putItem("page_layout", $pageLayout);
		$widgets = $layoutManager->getPageWidgets();

		foreach($widgets as $key => $widget) {
			call_user_func_array(array($this, "addWidget"), $widget);
		}

		//$this->setConfig();

		//$modules = $this->getModules("IManageable");
		//reset($modules)->getManageParams();
		//exit;
/*
		$totalClientes = 1;
		$totalPessoal = 2;
		$totalFornecedor = 3;
		$totalTrabalhe = 4;
		$totalContatos = 5;
		$totalContatosOrcamento = 6;
		$totalContatosFranquias = 7;

		$this->addWidget(
			"big-statuses", 
			array(
				'client' => array(
					'text'		=> 'Clientes',
					'href'		=> 'clientes',
					'icon' 		=> 'user_add',
					'count' 	=> $totalClientes,
					'weight'	=> 3
				),
				'pessoal' => array(
					'text'		=> 'Pessoal',
					'href'		=> 'pessoal',
					'icon' 		=> 'user_remove',
					'count' 	=> $totalPessoal,
					'weight'	=> 3
				),
				'franchise' => array(
					'text'		=> 'Franquias Solicitadas',
					'icon' 		=> 'bank',
					'href'		=> 'contatos',
					'count' 	=> $totalContatosFranquias,
					'weight'	=> 3
				),
				'fornecedor' => array(
					'text'		=> 'Fornecedores',
					'icon' 		=> 'cargo',
					'href'		=> 'fornecedores',
					'count' 	=> $totalFornecedor,
					'weight'	=> 3
				)
			)
		);

		$this->addWidget(
			"big-statuses", 
			array(
				'trabalhe' => array(
					'text'		=> 'Trabalhe Conosco',
					'icon' 		=> 'hand_saw',
					'href'		=> 'trabalhe',
					'count' 	=> $totalTrabalhe,
					'weight'	=> 3
				),
				'orcamento_solicitado' => array(
					'text'		=> 'Orçamentos Solicitados',
					'icon' 		=> 'message_plus',
					'href'		=> 'contatos',
					'count' 	=> $totalContatosOrcamento,
					'weight'	=> 3
				),
				'contato' => array(
					'text'		=> 'Contatos',
					'icon' 		=> 'chat',
					'href'		=> 'contatos',
					'count' 	=> $totalContatos,
					'weight'	=> 3
				)
			)
		);
		*/
		parent::display('pages/dashboard/home.tpl');

	}

}
