<?php
/**
* Smarty plugin: smarty_function_has_permission function
*/
function smarty_function_has_permission($params, &$smarty)
{
    $depinject = Phalcon\DI::getDefault();
    $allowed = $depinject->get("acl")->isUserAllowed(null, $params['resource'], $params['action']);
    $smarty->assign($params['assign'], $allowed);
    return;
}
