<?php
abstract class RequestManager extends DatabaseManager {

	const CODE_INVALID_PARAMS = "x0001";

	protected function createResponse($code, $message, $type, $intent, $callback = null)
	{
		http_response_code($code);
		$error = array(
			"_response_" => array(
				"code" 		=> $code,
				"message"	=> $message,
				"type"		=> $type,
				"intent"	=> $intent,
				"data"		=> $callback
			)
		);
		return $error;
	}

	protected function createRedirectResponse($location, $message = null, $type = null, $code = 200) {
		if (!is_null($message)) {
			$this->putMessage($message, $type);
		}
		return $this->createResponse($code, $message, $type, "redirect", $location);
	}

	protected function createAdviseResponse($message, $type)
	{
		return $this->createResponse(200, $message, $type, "advise");
	}

	protected function createNonAdviseResponse($message, $type = "ACK")
	{
		return $this->createResponse(200, $message, $type, 'info');
	}

	protected function defaultRequestSuccess($advise = true)
	{
		return $this->createResponse(200, "Informação salva com sucesso", "success", $advise ? "advise" : "info");
	}

	protected function userDoesnotHaveAccessError()
	{
		return $this->createResponse(401, "Acesso não autorizado.", "warning", "advise");
	}

	protected function entryPointNotFoundError($redirect = null)
	{
		if (!is_null($redirect)) {
			return $this->redirect($redirect, "Rota não encontrada", "error", 404);
		}
		return $this->createResponse(404, "Não encontrado", "error", "advise");
	}

	protected function serverFaultError()
	{
		return $this->createResponse(500, "Erro desconhecido", "error", "advise");
	}


	protected function notAuthenticatedError()
	{
		return $this->createResponse(403, "Acesso não autorizado.", "error", "callback");
	}

	protected function invalidRequestError($message = "", $type = "warning") {
		if (empty($message)) {
			$message = "Não foi possível completar a sua requisição.";
		}
		return $this->createResponse(200, $message, $type, "advise");
	}

	// UTILITY FUNCTIONS.
	public function redirect($location = null, $message="", $message_type="", $code = 301)
	{
		if (!empty($message)) {
			if (!in_array($message_type, array("info", "success", "warning", "error", ))) {
				$message_type = "info";
			}
			$this->putMessage($message, $message_type);
		}
		if (is_null($location)) {
			$location = $_SERVER['REQUEST_URI'];
		}
		if (strpos($location, "/") === 0 || strpos($location, "http://") == 0) {
			header("Location: " . $location);
		} else {
			header("Location: " . $this->getBasePath() . $location);
		}
		exit;
	}

	public function getHttpData($args)
	{
		return end($args);

	}

	public function getSystemUrl($who = null) {
		if (is_null($who)) {
			$who = 'default';
		}
		$plico = PlicoLib::instance();
		$urls = $plico->getArray('urls');
		if (array_key_exists($who, $urls)) {
			return $urls[$who];
		}
		return false;
	}
}
