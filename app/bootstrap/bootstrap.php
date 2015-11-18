<?php

// CONFIG AUTOLOADERS
require(__DIR__ . "/autoloader.php");
	
if (APP_TYPE === "WEB" || APP_TYPE === "CONSOLE") {
	// SYSTEM AND ENVIRONMENT INFO
	require(__DIR__ . "/config.php");

	// ROUTES AND DISPATCHER'
	require(__DIR__ . "/routes.php");

	// MODEL AND DATABASE-RELATED
	require(__DIR__ . "/models.php");

	//Authentication, AUthorization and all security related
	require(__DIR__ . "/security.php");

	// STORAGE SERVICES AND FILE SERVICES RELATED.
	require(__DIR__ . "/storage.php");

	// UTILITY SERVICES, LIKE string, url, escaper, and so on...
	require(__DIR__ . "/utils.php");

	// VIEW RELATED
	require(__DIR__ . "/views.php");

	// BACKWARD COMPATIBILITY CODE, TO BE DEPRECATED..
	require(__DIR__ . "/compat.php");
}