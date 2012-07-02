<?php
/**

* prints the controlpanel Options Block

*

*/
function smarty_function_Mag_Json_Encode($params, &$smarty) {
	
	if (!is_null($params['data'])) {
		return str_replace('"', "'", json_encode($params['data']));
	}
	return json_encode(array());
}
?>