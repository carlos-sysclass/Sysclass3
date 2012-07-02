<?php

$path = "../libraries/";
require_once $path."configuration.php";
$data = eF_getTableData("configuration", "value", "name='api'"); //Read current values
$api = $data[0]['value'];
if ($api == 1) {
	// RECEIVE BY POST
	
	
}
	if (isset($_GET['module'])) {
		$login = "api_user_service";

		global $currentUser;
		$currentUser = MagesterUserFactory :: factory($login);
		$password = $currentUser -> user['password'];
		$ok = $currentUser -> login($password, true);

		$modules = eF_loadAllModules(true);

		if (array_key_exists("module_" . $_GET['module'], $modules)) {
				
			$currentModule = $modules["module_" . $_GET['module']];
			$selectedActionFunction = $currentModule->camelCasefying($_GET['action'] . "_action");
				
			$result = $currentModule->$selectedActionFunction($_GET['mod_token'], $_POST);
				
			if ($_GET['output'] == 'json') {
				echo json_encode($result);
				exit;
			}
		}