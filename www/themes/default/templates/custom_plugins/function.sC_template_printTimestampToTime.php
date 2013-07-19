<?php
/**
* Smarty plugin: sC_template_printTimestampToTime function
*/
function smarty_function_sC_template_printTimestampToTime($params, &$smarty)
{
	//echo "<pre>";print_r($params[params]);echo "</pre>";
	echo $timestamp;
    if (!isset($params['timestamp']) || $params['timestamp'] <= 0) {
        $params['timestamp'] = time();
    }

    if (isset($params['onlytime']) && $params['onlytime']) {
        $str = date("H:m:s", $params['timestamp']);
    } else {
        $str = $params['timestamp'].', '.date("H:i:s", $params['timestamp']);
    }

    return $str;
}
