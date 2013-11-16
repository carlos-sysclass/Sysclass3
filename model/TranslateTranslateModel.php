<?php 
class TranslateTranslateModel extends ModelManager {

	public static function translate($token, $vars = null)
	{
	    /** @todo CHECK FOR TRANSLATION MODE */

	    // FIRST CHECK ON TRANSLATION HASH TABLE



	    // IF NOT FOUND, CHECK FOR CONSTANTS
	    if (!is_null(@constant($token))) {
	        $token = @constant($token);
	    }

	    if (!is_null($vars)) {
	    	if (!is_array($vars)) {
	    		$vars = array($vars);
	    	} 
    		return vsprintf($token, $vars);
	    }
	    return $token;
	}
}
