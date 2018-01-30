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
 * Name:     user_attrs_translate<br>
 * 
 * @author Paulo Nhaia
 * @param string  $string      input string
 * @return string translate
 */
function smarty_modifier_user_attrs_translate($string) {
    
	$mtz = array();
	$mtz['address'] = 'Address';
	$mtz['area_of_study'] = 'Area of study';
	$mtz['courses'] = 'Courses';
	$mtz['course_interest'] = 'Course of interest';
	$mtz['english_communication'] = 'English communication';
	$mtz['gender_id'] = 'Gender';
	$mtz['higher_school'] = 'Higher school';
	$mtz['how_did_you_learn_about'] = 'How did you learn about';
	$mtz['i_am_currently'] = 'I\'am currently';
	$mtz['language_name'] = 'Primary language';
	$mtz['my_calling'] = 'My calling';
	$mtz['secondary_school'] = 'Secondary school';
	$mtz['skype'] = 'Skype';
	$mtz['whatsapp'] = 'Whatsapp';
	$mtz['zip_code'] = 'Postal code';
	$mtz['particiate_translation'] = 'Participate in translation';
	
	if( isset($mtz[$string]) ){
		return $mtz[$string];
	}else{
		return $string;
	}
}
?>