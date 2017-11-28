<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */
 
/**
 * Smarty modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     modifier_calculate_age<br>
 * 
 * @author Paulo Nhaia
 * @param string  $date      input date
 * @return string translate
 */
function smarty_modifier_calculate_age($date) {
    $year_diff = 0;
    
	if ($date != '' && $date != '0000-00-00'){
         list($year, $month, $day) = explode("-", $date);
         $year_diff = intval(date("Y")) - intval($year);
         $month_diff = intval(date("m")) - intval($month);
         $day_diff = intval(date("d")) - intval(substr($day, 0, 2));
         if ($month_diff < 0){
            $year_diff--;
         }else{
            if (($month_diff == 0) && ($day_diff < 0)){
               $year_diff--;
        	}
    	}
    }
	return $year_diff; 
}
?>