<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     eF_formatTitlePath<br>
 * Purpose:  Format the top tilebar path using an algorithm to
 *			 cut each inner link if necessary.
 *
 * @param string
 * @param integer
 * @param integer
 * @param string
 * @return string
 */
function smarty_modifier_eF_formatTitlePath($string, $length = 80, $pathLimit = 6, $etc = '...') {
	$piecesStart = explode("&raquo;&nbsp;", $string); // with tags
	$stripped = strip_tags($string); //remove tags to count characters
	$piecesStripped = explode("&raquo;&nbsp;", $stripped);
	 
	array_walk($piecesStripped, create_function('&$v, $k', '$v = str_replace("&nbsp;", "", $v);'));
	
	$finalString = array();
	foreach($piecesStart as $item) {
		$finalString[] .= sprintf('%s', $item);		
	}
	return implode("&raquo;&nbsp;", $finalString);
}
