<?php
function loadClasses($class_name)
{
	if ($class_name == "PlicoLib") {
		include_once __DIR__ . "/../PlicoLib.php";
		return TRUE;
	} else {
		$plicoLib = PlicoLib::instance();

		if (file_exists($plicoLib->get('path/app') . "/controller/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/app') . "/controller/" . $class_name . ".php";
			return TRUE;
		}

		if (file_exists($plicoLib->get('path/app') . "/inc/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/app') . "/inc/" . $class_name . ".php";
			return TRUE;
		}
		if (file_exists($plicoLib->get('path/base') . "/controller/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/base') . "/controller/" . $class_name . ".php";
			return TRUE;
		}

		if (file_exists($plicoLib->get('path/base') . "/inc/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/base') . "/inc/" . $class_name . ".php";
			return TRUE;
		}

		if (file_exists($plicoLib->get('path/app') . "model/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/app') . "model/" . $class_name . ".php";
			return TRUE;
		}
		if (file_exists($plicoLib->get('path/base') . "/model/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/base') . "/model/" . $class_name . ".php";
			return TRUE;
		}

		if (file_exists($plicoLib->get('path/app') . "helper/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/app') . "helper/" . $class_name . ".php";
			return TRUE;
		}
		if (file_exists($plicoLib->get('path/base') . "/helper/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/base') . "/helper/" . $class_name . ".php";
			return TRUE;
		}

		if (file_exists($plicoLib->get('path/app') . "/exception/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/app') . "/exception/" . $class_name . ".php";
			return TRUE;
		}
		if (file_exists($plicoLib->get('path/base') . "/exception/" . $class_name . ".php")) {
			include_once $plicoLib->get('path/base') . "/exception/" . $class_name . ".php";
			return TRUE;
		}

		// TRY TO FIND FROM MODULES, FOLDER
		if (strpos($class_name, "Module") !== FALSE) {
			$moduleFolder = $plicoLib->camelDiscasefying(str_replace("Module", "", $class_name));
			if (file_exists($plicoLib->get('path/modules') . $moduleFolder . "/" . $class_name . ".php")) {
				include_once $plicoLib->get('path/modules') . $moduleFolder . "/" . $class_name . ".php";
				return TRUE;
			}

			if (file_exists($plicoLib->get('path/core-modules') . $moduleFolder . "/" . $class_name . ".php")) {
				include_once $plicoLib->get('path/core-modules') . $moduleFolder . "/" . $class_name . ".php";
				return TRUE;
			}

		}

	}

}
// Don't load our classes unless we use them!
spl_autoload_register("loadClasses");
