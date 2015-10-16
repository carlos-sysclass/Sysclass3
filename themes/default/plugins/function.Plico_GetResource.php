<?php
use Phalcon\DI;

function smarty_function_Plico_GetResource($params, $template)
{

	$file = $params['file'];

	if (strpos($file, "/") === 0) {
		return $file;
	} else {
		$environment = DI::getDefault()->get("environment");

		$templatedPath = $environment['default/resource'] . $params['file'];

		$themedPath = sprintf($templatedPath, $environment->view->theme);

		//var_dump($plico->get('path/app/www') . $themedPath);
		if (file_exists($environment['path/app/www'] . $themedPath)) {
			return $themedPath;
		} else {
			return sprintf($templatedPath, $environment['default/theme']);
		}
	}
}
