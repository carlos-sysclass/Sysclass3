<?php
function smarty_function_Plico_RelativePath($params, $template)
{
	$plico = PlicoLib::instance();

	$templatedPath = $plico->get('default/resource') . $params['file'];
	$themedPath = sprintf($templatedPath, $plico->get('theme'));
	
	if (file_exists($plico->get('path/app/www') . $themedPath)) {
		return $themedPath;
	} else {
		return sprintf($templatedPath, $plico->get('default/theme'));
	}

}
