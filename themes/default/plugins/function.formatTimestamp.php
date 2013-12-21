<?php
/**
* Smarty plugin: sC_template_printBackButton function
*/
/// GET A GLOBAL REFERENCE FROM TranslateModel
function smarty_function_formatTimestamp($params, &$smarty)
{

    $timestamp = $params['value'];
    $fmt = $params['fmt'];

    return date($fmt, intval($timestamp));
}
