<?php
use Phalcon\DI;
/**
* Smarty plugin: sC_template_printBackButton function
*/
/// GET A GLOBAL REFERENCE FROM TranslateModel
if(!function_exists("smarty_function_translateToken")) {
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
	
	
	
	    $result = $di->get("translate")->translate($token, $vars, $language_id);
	
	    if (array_key_exists('assign', $params)) {
	        $smarty->assign($params['assign'], $result);
	        return;
	    }
	    return $result;
	}
}
