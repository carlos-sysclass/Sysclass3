<?php
/**
 * @deprecated 3.2.0
 */
class TranslateModel extends ModelManager
{
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
        $this->selectSql = "SELECT `id`, `code`, `locale_code`, `country_code`, `name`, `local_name`, `active`, `rtl` FROM `mod_translate`";
        //`lessons_ID`, `classe_id`,

        parent::init();
    }

    public function getSystemLanguageCode()
    {
        // TODO Include code to get default system language
        return "en";
    }

    public function getUserLanguageCode()
    {
        if ($this->hasCache("user_language_code")) {
            return $this->getCache("user_language_code");
        }
        // TODO Include code to get default user language
        return $this->getSystemLanguageCode();
    }

    public function setUserLanguageCode($language_code)
    {
        $langCodes = $this->cache(false)->getDisponibleLanguagesCodes();
        if (in_array($language_code, $langCodes)) {
            $this->setCache("user_language_code", $language_code);
            return true;
        }
        return false;
    }

    
    public function getDisponibleLocalesCodes()
    {
        // TODO Include code to get default user language
        $cacheHash = __METHOD__;

        /*

        if ($this->cacheable() && $this->hasCache($cacheHash)) {
            // TODO CHECK IF IS THERE A CACHE, AND RETURN IT.
            return $this->getCache($cacheHash);
        } else {
            $this->clearCache($cacheHash);
        }
        */
        $languages = $this->getItems();
        $langcodes = \array_column($languages, "locale_code");
        /*
        if ($this->cacheable()) {
            // TODO CACHE RESULTS HERE
            $this->setCache($cacheHash, $langcodes);
        }
        */
        return $langcodes;
    }

    public function getDisponibleLanguagesCodes()
    {
        // TODO Include code to get default user language
        $cacheHash = __METHOD__;

        if ($this->cacheable() && $this->hasCache($cacheHash)) {
            // TODO CHECK IF IS THERE A CACHE, AND RETURN IT.
            return $this->getCache($cacheHash);
        } else {
            $this->clearCache($cacheHash);
        }
        $languages = $this->getItems();
        $langcodes = \array_column($languages, "code");

        if ($this->cacheable()) {
            // TODO CACHE RESULTS HERE
            $this->setCache($cacheHash, $langcodes);
        }
        return $langcodes;
    }

    public static function translate($token, $vars = null, $language_code = null)
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

        $language_code = (is_null($language_code) || !in_array($language_code, $langCodes)) ? $translateModel->getUserLanguageCode() : $language_code;

        $tokens = $translateTokensModel->clear()->cache(true)->getItemsGroupByToken();

        if (array_key_exists($token, $tokens)) {
            $token = $tokens[$token][$language_code];
        } else {
            //REGISTER TOKEN HERE, TO TRANSLATE LATER
            $translateTokensModel->addToken(array(
                'language_code'    => $translateModel->getSystemLanguageCode(),
                'token'            => $token,
                'text'            => $token
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
    /**
     * [translateTokens description]
     * @param  string $from   [description]
     * @param  string $to     [description]
     * @param  array $tokens [description]
     * @return array         [description]
     */
    public function translateTokens($source, $dest, $tokens, $source_column = null, $dest_column = "translated")
    {
        if ($force === 'false' || $force === "0") {
            $force = false;
        } else {
            $force = true;
        }

        $langCodes = $this->model("translate")->getDisponibleLocalesCodes();

        if (in_array($source, $langCodes) && in_array($dest, $langCodes)) {
            // VALIDATE TOKEN
            $bingTranslateModel = $this->model("bing/translate");
            if (is_string($source_column)) {
				$translateTokens = array_column($tokens, $source_column);
            } else {
            	$translateTokens = array_values($tokens);
            }

            $source = Locale::getPrimaryLanguage($source);
            $dest = Locale::getPrimaryLanguage($dest);
            
            $translatedTerms = $bingTranslateModel->translateArray($translateTokens, $source, $dest);

            if (is_string($source_column)) {
	            foreach ($tokens as $key => $value) {
	            	$tokens[$key][$dest_column] = $translatedTerms[$value[$source_column]];
	            }
	            return $tokens;
            } else {
            	 return $translatedTerms;
            }
        } else {
            return false;
        }
    }
}
