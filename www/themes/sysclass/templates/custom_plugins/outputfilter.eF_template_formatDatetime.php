<?php
/**

* Replaces occurences of the form #filter:datetime-1132843907# with the current date

*/
function smarty_outputfilter_eF_template_formatDatetime($compiled, &$smarty)
{
	
    switch ($GLOBALS['configuration']['date_format']) {
        case "YYYY/MM/DD": {
        	$date_format = 'Y/m/d'; break;
        }
        case "MM/DD/YYYY": {
        	$date_format = 'm/d/Y'; break;
        }
        case "DD/MM/YYYY": 
        default: {
        	$date_format = 'd/m/Y'; break;
        }
    }
    switch ($GLOBALS['configuration']['time_format']) {
        case "HH:mm": {
        	$time_format = 'h:i'; break;
        }
        case "HH:mm:SS": 
       	default: {
        	$time_format = 'h:i:s'; break;
        }
    }
    $datetime_format = $date_format . ' ' . $time_format;
    
    $new = $compiled;
    //$new = preg_replace("/#filter:datetime-(\s)#/e", "iconv(_CHARSET, 'UTF-8', strtotime('$format', '\$1'))", $compiled);
    $new = preg_replace("/#filter:date-(.*?)#/e", "strtotime('$1') === FALSE ? 'N/A' : iconv(_CHARSET, 'UTF-8', date('$date_format', strtotime('\$1')))", $new);
    $new = preg_replace("/#filter:time-(.*?)#/e", "iconv(_CHARSET, 'UTF-8', date('$time_format', strtotime('\$1')))", $new);
    $new = preg_replace("/#filter:datetime-(.*?)#/e", "iconv(_CHARSET, 'UTF-8', date('$datetime_format', strtotime('\$1')))", $new);
    $new = preg_replace("/#filter:isodatetime-(.*?)#/e", "iconv(_CHARSET, 'UTF-8', date('$datetime_format', strtotime('\$1')))", $new);
    
    return $new;
}
