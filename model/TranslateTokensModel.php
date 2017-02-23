<?php
/**
 * @deprecated 3.2.0
 */
class TranslateTokensModel extends ModelManager {

	public function init()
	{
		$this->table_name = "mod_translate_tokens";
		$this->id_field = "language_code";
		/*
		$this->fieldsMap = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN'
		);
		*/
		//$this->selectSql = "SELECT `id`, `name`, `local_name`, `active`, `rtl` FROM `mod_translate_tokens`";
		//`units_ID`, `classe_id`,

		parent::init();

	}

	public function updateSystemTokens($tokens) {
		foreach($tokens as $token) {
            // UPDATE OR INSERT
            // CHECK IF EXISTS
            $this->addToken($token);
            /*
            $id = array(
				'language_id'	=> $token['language_id'],
				'token' 		=> $token['token']
            );
            if (!$this->exists($id)) {
            	$this->addItem($token);
            }
            */
        }
        return $tokens;
	}

	public function addToken($token, $force_update = false) {
        $lang_code = !isset($token['language_code']) ? $token['language_id'] : $token['language_code'];
        if (empty($lang_code)) {
            $lang_code = $this->model("translate")->getSystemLanguageCode();
        }
        $id = array(
			'language_code'	=> !isset($token['language_code']) ? $token['language_id'] : $token['language_code'],
			'token' 		=> $token['token'],
        );
        if (!$this->exists($id)) {
        	$this->addItem($token);
        } elseif ($force_update) {
        	$this->setItem($token, $id);
        }
        return $token;
	}

	public function getAssociativeLanguageTokens($language_code) {
		$langCodes = $this->model("translate")->getDisponibleLanguagesCodes();
		if (in_array($language_code, $langCodes)) {
			$tokens = $this->getItemsGroupByToken($language_code);
			$result = array();
			foreach($tokens as $item) {
				$result[$item['token']] = $item[$language_code];
			}
			return $result;
		}
		return array();
	}

	public function getItemsGroupByToken($language_code = null) {
		$langCodes = $this->model("translate")->getDisponibleLanguagesCodes();
		//$languages = $this->model("translate")->clear()->getItems();
		if (!is_null($language_code) && in_array($language_code, $langCodes)) {
			$keys = array($language_code);
		} else {
			$keys = $langCodes;
		}

		$cacheHash = __METHOD__ . "/" . serialize(func_get_args());

        $this->clearCache($cacheHash);

		if ($this->cacheable() && $this->hasCache($cacheHash)) {
				// TODO CHECK IF IS THERE A CACHE, AND RETURN IT.
			return $this->getCache($cacheHash);
		} else {
			$this->clearCache($cacheHash);
		}

		$grouped = array();
		$tokens = $this->addFilter(array(
			'language_code'	=> $keys
		))->getItems();

		foreach($tokens as $token) {
			if (!array_key_exists($token['token'], $grouped)) {
				$grouped[$token['token']] = array_combine(
					$keys,
					array_fill(0, count($keys), $token['token'])
				);
				//var_dump($token);
				$grouped[$token['token']]['token'] = $token['token'];
			}
			//if (in_array($token['language_code'])
			$grouped[$token['token']][$token['language_code']] = $token['text'];
		}
		if ($this->cacheable()) {
			// TODO CACHE RESULTS HERE
			$this->setCache($cacheHash, $grouped);
		}

		return $grouped;
	}
}
