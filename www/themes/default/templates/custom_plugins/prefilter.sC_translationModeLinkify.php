<?php
/**
* Replaces occurences of the form #filter:user_login-asdfas# with a personal message link
*/

function smarty_prefilter_sC_translationModeLinkify($source, &$smarty)
{
	$matches = array();
	var_dump(preg_match_all('/\$smarty.const.(.*)[`]/', $source, $matches));

	var_dump($matches);
	var_dump($source);
	exit;
	$compiled = preg_replace_callback("/\{\$smarty\.const\.(.*)\}/U", create_function('$matches', 'return putLink($matches[1]);'), $compiled);
	//$compiled = preg_replace_callback("/{$smarty.const.(.*)#/U", create_function('$matches', 'return formatLogin($matches[1]);'), $compiled);
	var_dump(1);
	exit;
    return $compiled;

}