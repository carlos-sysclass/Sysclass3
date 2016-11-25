<?php
use Sysclass\Models\Users\User;
/**
* Smarty plugin: smarty_function_has_permission function
*/
function smarty_function_has_role($params, &$smarty)
{
    if ($params['user_id']) {
    	$user = User::findFirstById($params['user_id']);
    	$isRole = $user->hasRole($params['role']);
    } else {
    	$depinject = Phalcon\DI::getDefault();
	    $isRole = $depinject->get("user")->hasRole($params['role']);
    }
    $smarty->assign($params['assign'], $isRole);

    return;
}
