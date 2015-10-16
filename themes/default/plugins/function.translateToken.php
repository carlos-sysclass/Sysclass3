<?php
use Phalcon\DI;
/**
* Smarty plugin: sC_template_printBackButton function
*/
/// GET A GLOBAL REFERENCE FROM TranslateModel
function smarty_function_translateToken($params, &$smarty)
{
    $token = $params['value'];
    unset($params['value']);

    $language_id = null;
    if (array_key_exists('language', $params)) {
        $language_id = $params['language'];
        unset($params['language']);
    }

    $vars = null;
    if (count($params) > 0) {
        $vars = $params;
    }

    $di = DI::getDefault();
    return $di->get("translate")->translate($token, $vars, $language_id);
}
