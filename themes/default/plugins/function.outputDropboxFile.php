<?php
/**
 * Smarty plugin: smarty_function_has_permission function
 */
function smarty_function_outputDropboxFile($params, &$smarty) {

	$file = $params['file'];
	$type = $file['type'];
	$isAudio = strpos($type, 'audio/') === 0;

	if ($isAudio) {
		$result = sprintf('<audio controls>
            <source src="%s" type="%s">
        </audio>',
			$file['url'], $file['type']
		);
	} else {
		$result = sprintf('<a href="%s" target="_blank">Baixar</a>',
			$file['url'], $file['type']);
	}

	if (array_key_exists('assign', $params)) {
		$smarty->assign($params['assign'], $result);
		return;
	}
	return $result;
}
