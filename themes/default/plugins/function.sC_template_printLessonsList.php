<?php
/**
* Smarty plugin: smarty_function_sC_template_printUsersList function.
*
*/
function smarty_function_sC_template_printLessonsList($params, &$smarty)
{

    $units_str     = '<option value = "-1">---- '._LESSONS.' ----</option>';

	foreach ($params['data'] as $key => $value) {
		for ($i = 0; $i < sizeof($params['data'][$key]); $i++) {
			$params['selected'] == $params['data'][$key][$i]['id'] ? $selected = 'selected' : $selected = '';
	        $units_str .=
    	    	'<option value = "'.$params['data'][$key][$i]['id'].'" '.$selected.'>'.$params['data'][$key][$i]['name'].' ('.$key.')</option>';
        }
	}

    return $units_str;
}
