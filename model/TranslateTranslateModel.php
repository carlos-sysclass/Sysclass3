<?php 
class TranslateTranslateModel extends ModelManager {

	public function init()
	{
		$this->table_name = "mod_translate";
		$this->id_field = "id";
		/*
		$this->fieldsMap = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN'
		);
		*/
		$this->selectSql = "SELECT `id`, `name`, `local_name`, `active`, `rtl` FROM `mod_translate`";
		//`lessons_ID`, `classe_id`, 

		parent::init();

	}

	public function getSystemLanguageCode() {
		// TODO Include code to get default system language
		return "us";
	}

	public function getUserLanguageCode() {
		if ($this->hasCache("user_language_id")) {
			return $this->getCache("user_language_id");
		}
		// TODO Include code to get default user language
		return $this->getSystemLanguageCode();
	}

	public function setUserLanguageCode($language_id) {
		$langCodes = $this->getDisponibleLanguagesCodes();
        if (in_array($language_id, $langCodes)) {
			$this->setCache("user_language_id", $language_id);
			return true;
		}
		return false;
	}

	public function getDisponibleLanguagesCodes() {
		// TODO Include code to get default user language
		$cacheHash = __METHOD__;

		if ($this->cacheable() && $this->hasCache($cacheHash)) {
				// TODO CHECK IF IS THERE A CACHE, AND RETURN IT.
			return $this->getCache($cacheHash);
		} else {
			$this->clearCache($cacheHash);
		}
		$languages = $this->getItems();
		$langcodes = array_column($languages, "id");

		if ($this->cacheable()) {
			// TODO CACHE RESULTS HERE
			$this->setCache($cacheHash, $langcodes);
		}
		return $langcodes;

	}

	public static function translate($token, $vars = null, $language_id = null)
	{
	 	/** @todo CHECK FOR TRANSLATION MODE */
	    if (array_key_exists('_translate', $_GET)) {
	    	$translateMode = true;
	    } else {
	    	$translateMode = false;
	    }

	    // FIRST CHECK ON TRANSLATION HASH TABLE
	    $controller = PlicoLib::handler();

	    //$controller->model("translate")
	    $translateModel = $controller->model("translate");
		$translateTokensModel = $controller->model("translate/tokens");

		$langCodes = $translateModel->getDisponibleLanguagesCodes();

	    $language_id = (is_null($language_id) || !in_array($language_id, $langCodes)) ? $translateModel->getUserLanguageCode() : $language_id;
	/*
	    $tokens = $translateTokensModel->addFilter(array(
	    	'language_id' => $language_id
	    ))->getItems();
	*/
	    $tokens = $translateTokensModel->cache(false)->getItemsGroupByToken();

	    if (array_key_exists($token, $tokens)) {
	    	$token = $tokens[$token][$language_id];
	    } else {
			//REGISTER TOKEN HERE, TO TRANSLATE LATER
			$translateTokensModel->addToken(array(
				'language_id'	=> $language_id,
				'token'			=> $token,
				'text'			=> $token
			));
		}

	    // IF NOT FOUND, CHECK FOR CONSTANTS
	    if (!is_null($vars)) {
	    	if (!is_array($vars)) {
	    		$vars = array($vars);
	    	} 
    		return vsprintf($token, $vars);
	    }
	    return $token;
	}
}
