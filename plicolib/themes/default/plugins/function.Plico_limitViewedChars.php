<?php
function smarty_function_Plico_limitViewedChars($params, $template)
{
//	$plico = PlicoLib::instance();
	$text = $params['text'];
	$text = preg_replace('/(?:\s\s+|\t)/', ' ', $text);
	$text = preg_replace('/(?:\s\n+)/', '\n', $text);
	$text = nl2br($text);
	if (strlen($text) > $params['chars']) {
		$text = substr($text, 0, $params['chars']) . " ...";
	}
	return $text;
}
