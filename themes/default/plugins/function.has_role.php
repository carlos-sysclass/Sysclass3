<?php
/**
* Smarty plugin: smarty_function_has_permission function
*/
function smarty_function_has_role($params, &$smarty)
{
    $depinject = Phalcon\DI::getDefault();
    $isRole = $depinject->get("user")->hasRole($params['role']);
    $smarty->assign($params['assign'], $isRole);

    return;
}
