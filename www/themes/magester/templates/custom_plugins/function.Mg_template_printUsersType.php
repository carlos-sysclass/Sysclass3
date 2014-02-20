<?php
/**
* Smarty plugin: smarty_function_sC_template_printUsersList function.
*
*/
function smarty_function_Mg_template_printUsersType($params, &$smarty)
{
	switch ($params['user_type']) {
		case 'administrator':
			return _ADMINISTRATOR;
		case 'professor':
			return _PROFESSOR;
		case 'student':
			return _STUDENT;
		default:
			return '';
 	}
}
