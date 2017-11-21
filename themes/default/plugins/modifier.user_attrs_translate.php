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
	$mtz['area_of_study'] = 'Area of Study';
	$mtz['courses'] = 'courses';
	$mtz['english_communication'] = 'English Communication';
	$mtz['gender_id'] = 'Gender';
	$mtz['higher_school'] = 'Higher School';
	$mtz['how_did_you_learn_about'] = 'How did you learn about';
	$mtz['i_am_currently'] = 'I\'am currently';
	$mtz['language_name'] = 'Language';
	$mtz['my_calling'] = 'My Calling';
	$mtz['secondary_school'] = 'Secondary School';
	$mtz['skype'] = 'Skype';
	$mtz['whatsapp'] = 'Whatsapp';
	$mtz['zip_code'] = 'Zip Code';
	
	if( isset($mtz[$string]) ){
		return $mtz[$string];
	}else{
		return $string;
	}
}
?>