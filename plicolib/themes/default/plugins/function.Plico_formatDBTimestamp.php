<?php
function smarty_function_Plico_formatDBTimestamp($params, $template)
{
	$plico = PlicoLib::instance();

	$time = strtotime($params['data']);
	return date($plico->get("timestamp/format"), $time);

}
