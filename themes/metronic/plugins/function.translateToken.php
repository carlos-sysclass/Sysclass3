<?php
/**
* Smarty plugin: sC_template_printBackButton function
*/
function smarty_function_translateToken($params, &$smarty)
{
    $token = $params['value'];
    /** @todo CHECK FOR TRANSLATION MODE */

    // FIRST CHECK ON TRANSLATION HASH TABLE

    // IF NOT FOUND, CHECK FOR CONSTANTS
    if (is_null(@constant($token))) {
        return $token;
    } else {
        return @constant($token);
    }
}
