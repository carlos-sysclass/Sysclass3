<?php
use Phalcon\DI;

function smarty_function_Plico_RelativePath($params, $template)
{
	$plico = PlicoLib::instance();

	$environment = DI::getDefault()->get("environment");

	$templatedPath = $environment['default/resource'] . $params['file'];
	$themedPath = sprintf($templatedPath, $environment->view->theme);
	
	if (file_exists($environment['path/app/www'] . $themedPath)) {
		return $themedPath;
	} else {
		return sprintf($templatedPath, $environment['default/theme']);
	}

}
