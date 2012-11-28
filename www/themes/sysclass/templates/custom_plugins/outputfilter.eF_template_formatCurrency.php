<?php
/**

* Replaces occurences of the form #filter:datetime-1132843907# with the current date

*/
function smarty_outputfilter_eF_template_formatCurrency($compiled, &$smarty)
{
	$decimal_sep	= isset($GLOBALS['configuration']["decimal_point"]) ? $GLOBALS['configuration']["decimal_point"] : '.';
	$thousando_sep 	= isset($GLOBALS['configuration']["thousands_sep"]) ? $GLOBALS['configuration']["thousands_sep"] : '';

	$new = $compiled;

	global $CURRENCYSYMBOLS;
	$currencySymbol = $CURRENCYSYMBOLS[$GLOBALS['configuration']["currency"]];

    //$new = preg_replace("/#filter:datetime-(\s)#/e", "iconv(_CHARSET, 'UTF-8', strtotime('$format', '\$1'))", $compiled);
    $new = preg_replace("/#filter:currency-(.*?)-(.*?)#/e", "iconv(_CHARSET, 'UTF-8', '$currencySymbol' . ' ' . number_format(\$1, \$2, '$decimal_sep', '$thousando_sep'))", $new);
    $new = preg_replace("/#filter:currency-(.*?)#/e", "iconv(_CHARSET, 'UTF-8', '$currencySymbol' . ' ' . number_format('\$1', 2, '$decimal_sep', '$thousando_sep'))", $new);

    return $new;
}
