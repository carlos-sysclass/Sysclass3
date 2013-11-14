<?php 
class TranslateTranslateModel extends ModelManager {

	public static function translate($token)
	{
	    /** @todo CHECK FOR TRANSLATION MODE */

	    // FIRST CHECK ON TRANSLATION HASH TABLE

	    // IF NOT FOUND, CHECK FOR CONSTANTS
	    if (is_null(@constant($token))) {
	        return $token;
	    } else {
	        return @constant($token);
	    }
	}
}
