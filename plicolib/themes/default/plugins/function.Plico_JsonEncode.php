<?php
function smarty_function_Plico_JsonEncode($params, $template)
{
	return json_encode($params['data']);

}
