<?php
namespace Sysclass\Services\I18n;

use 
    Phalcon\Mvc\User\Component,
    Phalcon\Mvc\Model\Resultset,
    Sysclass\Models\I18n\Language,
    Sysclass\Models\I18n\Tokens;

class Translator extends Component 
{

    protected $source_lang;
    protected $js_source_lang;
    protected $languages;
    protected $base_tokens;
    protected $source_tokens;
    protected $source_tokens_index;

    protected $session_tokens = array();

    protected $backends = array();

    public function __construct($clearCache = false) {
        // GET ALL LANGUAGES CACHE
        $this->languages = Language::find("active = 1");
        $this->base_tokens = Tokens::find("language_code = '{$this->getSystemLanguageCode()}'");

        if ($this->inTranslationMode()) {
            $this->cache->delete("session_tokens");
        } elseif ($this->cache->exists("session_tokens")) {
            $this->session_tokens = $this->cache->get("session_tokens");
        }
    }

    public function getLastSessionToken() {
        return $this->session_tokens;
    }

    
    public function getSystemLanguageCode()
    {
        // TODO Include code to get default system language
        return "en";
    }

    public function getSource()
    {
        if (!is_null($this->source_lang)) {
            return $this->source_lang;
        }
        // TODO Include code to get default user language
        return $this->getSystemLanguageCode();
    }

    public function languageExists() {
        
    }

    public function setSource($language_code)
    {
        //$langCodes = $this->getDisponibleLanguagesCodes();

        $languages = $this->languages->toArray();

        foreach($this->languages as $lang) {
            if ($language_code == $lang->code) {
                $this->source_lang = $lang->code;
                $this->js_source_lang = $lang->js_code;

                $FOUND = true;

                break;
            }
        }

        if (!$FOUND) {
            $this->source_lang = $this->getSystemLanguageCode();
            $this->js_source_lang = $this->getSystemLanguageCode();
        }
        // RECREATE TOKENS CACHE
        $this->recreateCache();

        if ($this->source_tokens->count() > 0) {
            $this->session->set("session_language", $this->source_lang);
        }
        return false;
    }

    public function getJsSource()
    {
        if (!is_null($this->js_source_lang)) {
            return $this->js_source_lang;
        }
        // TODO Include code to get default user language
        return $this->getSystemLanguageCode();
    }


    public function recreateCache() {
        $this->source_tokens = Tokens::find(array(
            'conditions' => "language_code = ?0",
            'bind' => array($this->source_lang)/*,
            'hydration' => Resultset::HYDRATE_ARRAYS*/
        ));

        //var_dump($this->source_tokens->toArray());

        //$source_tokens = $this->source_tokens->toArray();

        $this->source_tokens_index = array();

        foreach($this->source_tokens as $item) {
            $this->source_tokens_index[$item->text] = $item->token;
        }
    }

    public function getDisponibleLanguagesCodes($column = 'code')
    {
        // TODO Include code to get default user language
        return \array_column($this->languages->toArray(), $column);
        /*
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
        */
    }
    /*
    public function __invoke($a, $b, $c) {
        var_dump($a, $b, $c);
        var_dump(func_get_args);
        exit;
    }
    */
   
    public function translate($token, $vars = null, $language_code = null)
    {
        /** @todo CHECK FOR TRANSLATION MODE */
        // FIRST CHECK ON TRANSLATION HASH TABLE
        //$controller = PlicoLib::handler();

        //$controller->model("translate")
        //$translateModel = $controller->model("translate");
        //$translateTokensModel = $controller->model("translate/tokens");

        $langCodes = $this->getDisponibleLanguagesCodes();

        //$language_code = (is_null($language_code) || !in_array($language_code, $langCodes)) ? $translateModel->getUserLanguageCode() : $language_code;
        $language_selected = (is_null($language_code) || !in_array($language_code, $langCodes)) ? $this->getSource() : $language_code;

        $exists = array_search($token, $this->source_tokens_index);

        /*
        $exists = $this->source_tokens->filter(function($item) use ($token) {
            //if ($item['token'] === $token) {
            if ($item->token === $token) {
                return $item;
            }
        });
        */

        if ($exists !== FALSE) {
            $translated = $exists;
        } else {
            //REGISTER TOKEN HERE, TO TRANSLATE LATER
            if ($this->getSystemLanguageCode() == $this->source_lang) {
                $translated = $token;
                $tokenModel = new Tokens();
                $tokenModel->assign(array(
                    'language_code' => $this->getSystemLanguageCode(),
                    'token' => $token,
                    'text'  => $translated
                ));
                $tokenModel->save();
            } else {
                // JUST CALL THE REMOTE SYSTEM TRANSLATION METHOD
                if ($language_selected != $this->getSystemLanguageCode()) {
                    if (is_null($language_code)) {
                        $translated = $this->translateText($this->getSystemLanguageCode(), $this->source_lang, $token);
                    } else {
                        $translated = $this->translateText($language_selected, $this->source_lang, $token);

                       
                    }
                } else {
                    $translated = $token;
                }
                
                if ($translated !== FALSE && !is_object($translated)) {
                    $tokenModel = new Tokens();
                    $tokenModel->assign(array(
                        'language_code' => $this->source_lang,
                        'token' => $token,
                        'text'  => $translated
                    ));
                    $tokenModel->save();
                } else {
                    $translated = $token;
                }
            }
        }


        // IF NOT FOUND, CHECK FOR CONSTANTS
        if (!is_null($vars)) {
            if (!is_array($vars)) {
                $vars = array($vars);
            }
            $translated = vsprintf($translated, $vars);
        } else {
            $this->session_tokens[$token] = $translated;
        }
        try {
            $this->cache->save("session_tokens", $this->session_tokens);
        } catch(Exception $e) {
            return $translated;
        }

        return $translated;
    }

    public function getBackend($backend) {
        if (array_key_exists($backend, $this->backends)) {
            return $this->backends[$backend];
        }

        $class = "\\Sysclass\\Services\\I18n\\Backend\\" . ucfirst($backend);
        if (class_exists($class)) {
            return $this->backends[$backend] = new $class();
        }
        return false;
    }

    public function translateText($source, $dest, $text) {
        $langCodes = $this->getDisponibleLanguagesCodes();
        if (in_array($source, $langCodes) && in_array($dest, $langCodes)) {
            $result = $this->getBackend("bing")->translateText($text, $source, $dest);
            return $result;
        }
        return false;
    }

    /**
     * [translateTokens description]
     * @param  string $from   [description]
     * @param  string $to     [description]
     * @param  array $tokens [description]
     * @return array         [description]
     */
    public function translateTokens($source, $dest, $tokens = null, $source_column = null, $dest_column = "translated")
    {
        /*
        if ($force === 'false' || $force === "0") {
            $force = false;
        } else {
            $force = true;
        }
        */

        $langCodes = $this->getDisponibleLanguagesCodes();

        if (in_array($source, $langCodes) && in_array($dest, $langCodes)) {
            if (is_null($tokens)) {

                $sourcesTokens = Tokens::find(array(
                    'columns' => 'text',
                    'conditions' => "language_code = ?0 AND token NOT IN (
                        SELECT token FROM Sysclass\Models\I18n\Tokens WHERE 
                            language_code = ?1 AND 
                            (edited = 1 OR (UNIX_TIMESTAMP() - timestamp) < 300)
                    )",
                    'bind' => array($source, $dest)
                ));

                $tokens = array_column($sourcesTokens->toArray(), 'text');
            }

            // VALIDATE TOKEN
            //$bingTranslateModel = $this->model("bing/translate");
            if (is_string($source_column)) {
                $translateTokens = \array_column($tokens, $source_column);
            } else {
                $translateTokens = array_values($tokens);
            }

            $translatedTerms = $this->getBackend("bing")->translateArray($translateTokens, $source, $dest);

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

    public function inTranslationMode() {
        if (array_key_exists('_translate', $_GET)) {
            return $translateMode = true;
        } else {
            return $translateMode = false;
        }
    }
    public function getTranslatedTokens() {
        return $this->session_tokens;
    }
    /*
    public function init()
    {
        $this->table_name = "mod_translate";
        $this->id_field = "id";

        $this->selectSql = "SELECT `id`, `code`, `country_code`, `name`, `local_name`, `active`, `rtl` FROM `mod_translate`";
        //`lessons_ID`, `classe_id`,

        parent::init();
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
    */
   

}
