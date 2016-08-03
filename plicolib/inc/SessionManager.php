<?php 
/**
 * @package PlicoLib\Managers
 */
abstract class SessionManager extends RequestManager {
	protected $currentUser = NULL;
	protected $context;

	public function authorize()
	{
		return $this->isAuthenticated();

	}

	public function init($url, $method, $format, $root=NULL, $basePath="", $urlMatch = null)
	{
		$this->context = array(
			'url'		=> $url,
			'method'	=> $method,
			'format'	=> $format,
			'root'		=> $root,
			'basePath'	=> "/" . $basePath,
			'urlMatch'	=> $urlMatch
		);
		parent::init();

	}

	protected function getRequestedUrl() {
		return $this->getContext('url');
	}

	protected function getRequestedFormat() {
		return $this->getContext('format');
	}

	public function getBasePath() {
		return $this->getContext('basePath');
	}
	public function getMatchedUrl() {
		return $this->getContext('urlMatch');
	}

	public function getContext($key) {
		return $this->context[$key];
	}
	

	protected function isAuthenticated($force=FALSE, $token=FALSE, $email=NULL)
	{
		// RETURN FALSE, OR THE USER BY THE TOKEN.
		@session_start();
		if ($token == FALSE) {
			if (!array_key_exists('token', $_SESSION)) {
				return FALSE;
			}

			$token = $_SESSION['token'];
		}

		if ($force || count($this->currentUser) == 0) {
			if (!empty($token)) {
				$sql = "SELECT id, nome, login, sex, saudacao, email, usr.ativo
					FROM users usr
					LEFT JOIN t_users_logins login ON (usr.id = login.cod_user)";

				$where = array();
				if (!empty($email)) {
					$where[] = sprintf("usr.email = %s", $this->db->Quote($email));
				}

				$where[] = sprintf("login.cript_key = %s", $this->db->Quote($token));
				$where[] = "login.ativo";

				$sql .= " WHERE " . implode(" AND ", $where);
				$this->currentUser = $this->db->GetRow($sql);
			}
		}

		if (count($this->currentUser) > 0) {
			return TRUE;
		}

		return FALSE;

	}

	protected function loginUser($email, $password, $remember = false)
	{
		$sql = "SELECT id, nome, md5(login) as id_crypto, last_logon, email, ativo FROM users";
		// WHERE FOR PACIENTES!
		$where = array();
		$where[] = sprintf(
			"email = %s AND passwd = %s AND ativo",
			$this->db->Quote($email),
			$this->db->Quote($password)
		);

		$sql .= " WHERE " . implode(" AND ", $where);

		$userArray = $this->db->GetRow($sql);

		if ($userArray && count($userArray) > 0) {
			// USER FOUND!
			$sql = sprintf(
				"UPDATE users SET last_logon = %s, date_time = '%s' WHERE id = %d",
				date('U'),
				date('d-m-Y H:i:s'),
				$userArray['id']
			);
			$this->db->Execute($sql);

			$sql = sprintf("UPDATE t_users_logins SET ativo = false WHERE cod_user = %d", $userArray['id']);
			$this->db->Execute($sql);


			$token = $this->createToken($userArray['id'], $userArray['last_logon']);
			$sql = sprintf(
				"INSERT INTO t_users_logins (codigo, cod_user, cript_key) VALUES (%s, %d, '%s')",
				strpos($this->db->databaseType, "postgres") !== FALSE ? "nextval('s_users_logins_codigo')" : "NULL",
				$userArray['id'],
				$token
			);

			$this->db->Execute($sql);

			// CREATE TOKEN AND LOAD USER!
			@session_start();
			$_SESSION['Id2'] = $_SESSION['token'] = $token;
			$_SESSION['Id1'] = md5($userArray['id'] . $userArray['last_logon']);

			$result = array(
				"name"			=> $userArray['nome'],
				"email" 		=> $userArray['email'],
				"token" 		=> $_SESSION['token'],
				"status"		=> TRUE,
				"redirect"		=> $this->getBasePath() . 'dashboard',
				"remembered"	=> $remember
			);

			if ($remember) {
				// SET REMEMBER COOOKIE
				$result['remembered'] = true;
				
			}
		} else {
			return false;
		}

		return $result;

	}

	protected function logoutUser()
	{
		$currentUser = $this->getCurrentUser();
		if ($currentUser) {
			$sql = sprintf("UPDATE t_users_logins SET ativo = 0 WHERE cod_user = %d", $currentUser['id']);
			$this->db->Execute($sql);
		} else if (array_key_exists('token', $_REQUEST)) {
			if ($token = filter_var($_REQUEST['token'], FILTER_SANITIZE_STRING)) {
				$sql = sprintf("UPDATE t_users_logins SET ativo = 0 WHERE cript_key = '%s'", $token);
				$this->db->Execute($sql);
			} else {
				return $this->createAdviseResponse("Não foi possível encontrar o token enviado!", "info");
			}
		} else {
			return $this->createAdviseResponse("Não foi possível encontrar o token enviado!", "info");
		}

		$this->clearSessionToken();

		return $this->createAdviseResponse("Você foi desconectado com sucesso!", "info");

	}

	protected function getCurrentUser()
	{
		if (!is_array($this->currentUser) || count($this->currentUser) == 0) {
			if (!$this->isAuthenticated(TRUE)) {
				return FALSE;
			}

		}

		return $this->currentUser;

	}
}
