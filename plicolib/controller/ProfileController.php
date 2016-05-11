<?php 
/**
 * @package PlicoLib\Controllers\Utilities
 */
class ProfileController extends PageController
{
	
	// ABSTRACT - MUST IMPLEMENT METHODS!
	public static function getMenuOption()
	{
		return array(
			'url'		=> '/painel/profile',
			'absolute'	=> true,
			'text'		=> 'Minha Conta',
			'icon'		=> 'lock',
			'on_header'	=> TRUE,
			'on_footer'	=> TRUE,
			'themes'	=> array('admin'),
			'selected'	=> PlicoLib::handler() instanceof self
		);
	}

	protected function onThemeRequest()
	{
		

	}

	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url GET /profile
	 * @url GET /profile/edit
	 */
	public function profileEditPage()
	{
		// CREATE LOGIC AND CALL VIEW.
		// SET THEME (WEB SITE FRONT-END, MOBILE FRONT-END, OR ADMIN).
		$this->setTheme('admin');

		$currentUser = $this->getCurrentUser();

		$this->putData(array(
			'page_title'		=> 'Minha Conta'/*,
			'page_description'	=> 'Resumo Geral'*/
		));

		$this->treeLevel("dashboard");


		$model = $this->model("profile");
		$profileData = $model->getItemById($currentUser['id']);

		//var_dump($profileData);

		//$this->setConfig();

		$this->addBlock(
			"block",
			"profile/form",
			array(
				'action'	=> $this->getBasePath() . "profile/edit",
				'method'	=> 'post',
				'data'		=> $profileData
			),
			null,
			array('wysiwyg', 'validate', 'mask')
		);

		//var_dump($this->widgets);

		parent::display('pages/default/widget-container.tpl');

	}

	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url POST /profile/edit
	 */
	public function profileSaveForm()
	{
		$user = $this->getCurrentUser();
		$id = $user['id'];

		if (is_numeric($id) && $id != 0) {
			$mode = "UPDATE";
			$where = array("id" => $id);
		}

		
		$model = $this->model("profile");
		$result = $model->debug()->save($_POST, $mode, $where);

		if ($result === 0) {
			$passwdData = $_POST['password'];
			$updateResult = $model->updatePassword($id, $passwdData);
			var_dump($updateResult);

			if ($updateResult === TRUE) {
				$this->redirect(null, "Seu perfil foi salvo com sucesso, e sua senha atualizada", "success");
			} else {
				$this->redirect(null, "Seu perfil foi salvo com sucesso. Sua senha NÃO foi alterada.", "info");
			}
		} else {
			$this->redirect(null, sprintf("Não foi possível realizar a operação. Código do erro: (%s)", ErrorManager::CODE_INVALID_POSTDATA), "error");
		}

		exit;

	}

}
