<?php 
class TranslateTokensModel extends ModelManager {

	public function init()
	{
		$this->table_name = "mod_translate_tokens";
		$this->id_field = "language_id";
		/*
		$this->fieldsMap = array(
			//"id"					=> false, // SET TO FALSE TO CLEAR FROM "TO-SAVE" RESOURCE
			"login"					=> 'users_LOGIN'
		);
		*/
		//$this->selectSql = "SELECT `id`, `name`, `local_name`, `active`, `rtl` FROM `mod_translate_tokens`";
		//`lessons_ID`, `classe_id`, 

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
        $id = array(
			'language_id'	=> !isset($token['language_id']) ? $this->model("translate")->getSystemLanguageCode() : $token['language_id'],
			'token' 		=> $token['token'],
        );
        if (!$this->exists($id)) {
        	$this->addItem($token);	
        } elseif ($force_update) {
        	$this->setItem($token, $id);	
        }
        return $token;
	}

	public function getItemsGroupByToken() {
		$languages = $this->model("translate")->getItems();
		$keys = array();
		foreach($languages as $lang) {
			$keys[] = $lang['id'];
		}

		$cacheHash = __METHOD__;

		if ($this->cacheable() && $this->hasCache($cacheHash)) {
				// TODO CHECK IF IS THERE A CACHE, AND RETURN IT.
			return $this->getCache($cacheHash);
		} else {
			$this->clearCache($cacheHash);
		}

		$grouped = array();
		$tokens = $this->getItems();

		foreach($tokens as $token) {
			if (!array_key_exists($token['token'], $grouped)) {
				$grouped[$token['token']] = array_combine(
					$keys,
					array_fill(0, count($keys), $token['token'])
				);
				//var_dump($token);
				$grouped[$token['token']]['token'] = $token['token'];
			}
			$grouped[$token['token']][$token['language_id']] = $token['text'];
		}
		if ($this->cacheable()) {
			// TODO CACHE RESULTS HERE
			$this->setCache($cacheHash, $grouped);
		}

		return $grouped;
	}
}
