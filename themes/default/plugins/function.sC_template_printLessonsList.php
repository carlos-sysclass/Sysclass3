<?php
/**
* Smarty plugin: smarty_function_sC_template_printUsersList function.
*
*/
function smarty_function_sC_template_printUnitsList($params, &$smarty)
{

<<<<<<< HEAD
<<<<<<< HEAD
    $units_str     = '<option value = "-1">---- '._UNITS.' ----</option>';
=======
    $lessons_str     = '<option value = "-1">---- '._LESSONS.' ----</option>';
>>>>>>> parent of 7cdd908... lesson complete
=======
    $units_str     = '<option value = "-1">---- '._LESSONS.' ----</option>';
>>>>>>> parent of 7db341d... LESSON - UNIT

	foreach ($params['data'] as $key => $value) {
		for ($i = 0; $i < sizeof($params['data'][$key]); $i++) {
			$params['selected'] == $params['data'][$key][$i]['id'] ? $selected = 'selected' : $selected = '';
	        $lessons_str .=
    	    	'<option value = "'.$params['data'][$key][$i]['id'].'" '.$selected.'>'.$params['data'][$key][$i]['name'].' ('.$key.')</option>';
        }
	}

    return $lessons_str;
}
