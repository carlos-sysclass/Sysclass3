<?php
function smarty_function_Plico_GetResource($params, $template)
{

	$file = $params['file'];

	if (strpos($file, "/") === 0) {
		return $file;
	} else {
		$plico = PlicoLib::instance();
		$templatedPath = $plico->get('default/resource') . $params['file'];

		$themedPath = sprintf($templatedPath, $plico->get('theme'));

		//var_dump($plico->get('path/app/www') . $themedPath);
		if (file_exists($plico->get('path/app/www') . $themedPath)) {
			return $themedPath;
		} else {
			return sprintf($templatedPath, $plico->get('default/theme'));
		}
	}

}
