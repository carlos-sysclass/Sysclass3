<?php

class module_language extends MagesterExtendedModule
{
	protected $sections;
    protected static $languageIncluded = false;
    protected static $parsedTokens = array();

	protected $totalLanguageTerms = array();
	protected $totalTerms = -1;

	protected $totalLanguageModules = array();
	protected $totalModules = -1;

	const GET_LANGUAGES				= 'get_languages';
	const GET_SECTION				= 'get_section';
//	const ADD_POLO					= 'add_polo';
//	const EDIT_POLO					= 'edit_polo';
//	const DELETE_POLO				= 'delete_polo';

    // Mandatory functions required for module function
    public function __construct($defined_moduleBaseUrl, $defined_moduleFolder)
    {
        parent::__construct($defined_moduleBaseUrl, $defined_moduleFolder);

        $this->preActions[] = array($this, "initTranslationSystem");
        
    }
    public function initTranslationSystem($baseLanguage) {
        if ($_SESSION['translation_mode']) {
            $smarty = $this->getSmartyVar();

            $smarty->assign("T_LANGUAGE_LOAD_DIALOG", true);
            $smarty->assign("T_BASE_LANGUAGE", $baseLanguage);
            
            $languages = $this->getDisponibleLanguages();
            $smarty -> assign("T_LANGUAGE_LANGUAGES", $languages); //Assign global configuration values to smarty
        }
    }

    public function getName()
    {
        return "LANGUAGE";
    }

    public function getPermittedRoles()
    {
        return array("administrator" ,"professor" ,"student");
    }

    public function isLessonModule()
    {
        return false;
    }
    	/*
    public function getCenterLinkInfo()
    {
        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getType() == "administrator") {
            return array('title' => _MODULE_LANGUAGE,
                         'image' => $this -> moduleBaseDir . 'images/language.png',
                         'link'  => $this -> moduleBaseUrl
            );
        }

    }
        */
    public function getNavigationLinks()
    {
    	$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_LANGUAGES;

        $basicNavArray = array (
			array ('title' => _HOME, 'link' => "administrator.php?ctg=control_panel"),
    		array ('title' => _MODULE_LANGUAGE_MANAGEMENT, 'link'  => $this -> moduleBaseUrl)
		);
        /*
		if ($selectedAction == self::EDIT_POLO) {
            $basicNavArray[] = array ('title' => _MODULE_POLOS_EDITPOLO, 'link'  => $this -> moduleBaseUrl . "&action=" . self::EDIT_POLO . "&polo_id=". $_GET['polo_id']);
		} elseif ($selectedAction == self::ADD_POLO) {
            $basicNavArray[] = array ('title' => _MODULE_POLOS_ADDPOLO, 'link'  => $this -> moduleBaseUrl . "&action=" . self::ADD_POLO);
		}
		*/

        return $basicNavArray;
    }
/*
    public function getSidebarLinkInfo()
    {
        $link_of_menu_clesson = array (
        	array (
        		'id' => 'language_link_id1',
            	'title' => $this->getName(),
            	'image' => $this -> moduleBaseDir . 'images/language16.png',
            	'_magesterExtensions' => '1',
            	'link'  => $this -> moduleBaseUrl
        	)
        );

        return array ( "system" => $link_of_menu_clesson);

    }
*/
    public function getLinkToHighlight()
    {
        return 'language_link_id1';
    }

    public function getSection($section_ID, $force = false)
    {
    	if (!is_array($this->sections)) {
    		$this->sections = array();
    	}
        // 1. LOAD SELECTED SECTION
        if (!$force && array_key_exists($section_ID, $this->sections)) {
        	 return $this->sections[$section_ID];
        }
        if (sC_checkParameter($section_ID, 'directory')) {
        	$filename = $this->moduleBaseDir . "sections/" . $section_ID . ".php.inc";
        	if (file_exists($filename)) {
        		return $this->sections[$section_ID] = require_once($filename);
        	}
        }

        return false;
    }

    public function getCurrentHolidays($year = null, $asObjects = true)
    {
    	$year = is_null($year) ? intval(date("Y")) : $year;

    	/** @todo Get these values from database */
    	$fixedHolidays = array (
    		"01/01" => "Ano Novo",
    		"21/04" => "Tiradentes",
    		"01/05" => "Dia do trabalho",
    		"07/09" => "Proclamação da Independência",
    		"12/10" => "Nossa Senhora Aparecida",
    		"02/11" => "Finados",
    		"15/11" => "Proclamação da República",
    		"25/12" => "Natal"
    	);

    	$holidays = array();

    	foreach ($fixedHolidays as $day => $descricao) {
    		$holiday = date_create_from_format("d/m/Y", $day . "/" . $year);
    		if (!$asObjects) {
    			$holidays[] = $holiday->format("y-m-d");
    		} else {
    			$holidays[] = $holiday;
    		}
    	}

    	return $holidays;

    }

    public function getDefaultAction() {
        return "get_languages";
    }

    public function getLanguagesAction() {
        $smarty = $this -> getSmartyVar();

        $files = $this->getDisponibleLanguages();

        $languages = array();
        foreach ($files as $langName) {
            $languages[] = array_merge(
                array(
                    'english_name'  => ucfirst($langName)
                ),
                $this->getAttributesForLanguage($langName)
            );
        }

        $smarty -> assign ("T_LANGUAGES", $languages);    

    }

    public function setTranslationModeAction() {
        $_SESSION['translation_mode'] = true;

        header("Content-type: text/json");
        return array(
            'reload'    => true
        );
    }

    public function setNormalModeAction() {
        $_SESSION['translation_mode'] = false;
        
        $languages = $this->getDisponibleLanguages();
        foreach($languages as $language) {
            $this->updateFileAction($language);
        }
        

        header("Content-type: text/json");
        return array(
            'reload'    => true
        );
    }
    public function getTranslatedTokensAction($language = null) {
        if (is_null($language)) {
            $language = $_GET['language'];    
        }

        // DO A SUB-REQUEST TO ONLY DEFINE module_language TERMS
        $array_index = 'terms';
        $JsonUrl    = $this->moduleBaseLink . 'functions/terms.all.php?language=' . $language .'&index=' . $array_index;

        if ($stream = fopen($JsonUrl, 'r')) {
            $output = stream_get_contents($stream);
            fclose($stream);
            $result = json_decode($output, true);
        }

        // MERGE WITH DATABASE TERMS
        $tokensDB = sC_getTableData(
            "module_language_tokens",
            "token, translated",
            sprintf("language = '%s'", $language)
        );
        foreach($tokensDB as $tokenDB) {
            $result['terms'][$tokenDB['token']] = $tokenDB['translated'];
        }

        return $result;
    }

    public function setUsedPageTerms($language, $terms) {
        // CALLED BY AOUTPUT FILTER, SET PAGE USED TERMS
        $this->usedTerms[$language] = $terms;

        $this->setCache("usedTerms", $this->usedTerms);
    }
    public function setUsedDefaultLanguage($language) {
        // CALLED BY AOUTPUT FILTER, SET PAGE USED TERMS
        $this->setCache("language", $language);   
    }
    public function getUsedTermsAction() {
        return array(
            "terms"             => $this->getCache("usedTerms"),
            "language"          => $this->getCache("language"),
            "translation_mode"  => isset($_SESSION['translation_mode']) && $_SESSION['translation_mode'] ? true : false
        );
    }

    public function saveTermsAction() {
        $languages = $this->getDisponibleLanguages();

        if (in_array($_GET['language'], $languages)) {
            $language = $_GET['language'];
            $tokens = $_POST['token'];

            if (!is_array($tokens)) {
                return array(
                    'message'       => "Ocorreu um erro ao tentar salvar a sua informação. Por favor, tente novamente",
                    'message_type'  => "error"
                );
            }

            foreach($tokens as $token => $value) {
                list($result) = sC_countTableData(
                    "module_language_tokens",
                    "token",
                    sprintf("language = '%s' AND token = '%s'", $language, $token)
                );
                if ($result['count'] == 0) {
                    sC_insertTableData(
                        "module_language_tokens",
                        array(
                            "language"      => $language,
                            "token"         => $token,
                            "translated"    => $value
                        )
                    );
                } else {
                    sC_updateTableData(
                        "module_language_tokens",
                        array(
                            "translated" => $value
                        ),
                        sprintf("language = '%s' AND token = '%s'", $language, $token)
                    );
                }
            }

            return array(
                'message'       => "Salvo com sucesso",
                'message_type'  => "success"
            );
        } else {
            return array(
                'message'       => "Idioma não encontrado",
                'message_type'  => "error"
            );
        }
    }

    public function saveTermsFromFiles($language) {

    }
    public function updateFileAction($language = null) {
        if (is_null($language)) {
            $language = $_GET['language'];    
        }
        $languages = $this->getDisponibleLanguages();

        //if (in_array($language, $languages)) {
            



            // GET ALL LANGUAGE TERMS
            $tokens = $this->getTranslatedTokensAction($language);
            $toFile = array();
            $insertData = array();
            foreach($tokens['terms'] as $key => $value) {
                $value = addslashes(stripslashes($value));
                $toFile[] = sprintf("define('%s', '%s');", $key, $value);

                $insertData[] =  array(
                    "language"      => $language,
                    "token"         => $key,
                    "translated"    => $value
                );
            }
            
            // REMOVE OLD FILE
            $filename = sprintf(G_ROOTPATH . "libraries/language/lang-%s.php.inc", $language);
            if (file_exists($filename)) {
                unlink($filename);
            }
            
            //SAVE AS PHP define FILE
            file_put_contents($filename, "<?php\n" . implode("\n", $toFile) . "\n?>");
            chmod($filename, 0777);

            // REMOVE ENTRIES FROM DATABASE
            sC_deleteTableData(
                "module_language_tokens",
                sprintf("language = '%s'", $language)
            );
            // SAVE ENTRIES IN DATABASE
            sC_insertTableDataMultiple(
                "module_language_tokens",
                $insertData
            );
            return true;
        //} else {
        //    echo __LANGUAGE_NOT_FOUND;
        //    exit;
        //}
    }

    public function getTranslationFileAction() {
        $language = $_POST['language'];
        if (is_null($language)) {
            throw new Exception(__LANGUAGE_PLEASE_SELECT_A_LANGUAGE);
            exit;
        }
        // @todo: filter POST parameters
        $filename = sprintf(G_ROOTPATH . "libraries/language/lang-%s.php.inc", $language);
        if (file_exists($filename)) {
            $filecontent = file_get_contents($filename);
            echo $filecontent;
            exit;
        } else {
            throw new Exception(__LANGUAGE_LANGUAGE_DOES_NOT_EXIST);
        }
    }
    public function saveInlineEditorContentsAction() {
        if ($this->getCurrentUser()->getType() == 'administrator') {
            $language = $_POST['language'];
            $contents = $_POST['contents'];

            $contentsArray = explode("\n", $contents);

            foreach($contentsArray as $contentLine) {
                // SANITIZE AND REMOVE UNKNOW CODE
                $tokens = sscanf($contentLine, "define('%s', '%s');");
                var_dump($tokens);
            }
            $contents = addslashes(stripslashes(implode("\n", $contentsArray)));

            // @todo: filter POST parameters
            $filename = sprintf(G_ROOTPATH . "libraries/language/lang-%s.php.inc", $language);
            
            //if (file_put_contents($filename, $contents)) {
            if (true) {
                return array(
                    'message'       => __LANGUAGE_FILE_SAVE_SUCCESSFULL,
                    'message_type'  => 'success'
                );
            } else {
                return array(
                    'message'       => __LANGUAGE_FILE_WRITE_ERROR,
                    'message_type'  => 'error'
                );
            }
        } else {
            return array(
                'message'       => __LANGUAGE_PERMISSION_ERROR,
                'message_type'  => 'error'
            );
        }
    }

    public function parseTokensFromSource($reload = true) {
        // PARSE ALL SOURCE AN GET ALL {$smart.const.*} TERMS. USED WITH CAUTION
        if ($reload) {
            // TRY TO EXEC 
            $cmd = sprintf('egrep -h -o -r "[$]smarty\.const\.([_A-Z0-9]+)" %swww/themes/*/templates/* %swww/modules/*/* | cut -f3 -d . | sort -u', G_ROOTPATH, G_ROOTPATH);
            $tokens = array();
            $output = null;
            exec($cmd, $tokens, $output);
            if ($output == 0 && is_array($tokens)) {
                self::$parsedTokens = array_combine($tokens, $tokens);
            }
        }
        return self::$parsedTokens;
    }


    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule()
    {
		$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_LANGUAGES;

		$smarty = $this -> getSmartyVar();

		$smarty -> assign("T_MODULE_LANGUAGE_ACTION", $selectedAction);

        // Get smarty global variable
        $smarty = $this -> getSmartyVar();

        if ($selectedAction == self::GET_SECTION) {
        	unset($_SESSION['previousMainUrl']);

        	if ($output = $this->getSection($_GET['section_id'])) {
       			if ($_GET['output'] == 'json') {
        			echo json_encode($output['data']);
        			exit;
        		} else {
        			$smarty->assign("T_MODULE_LANGUAGE_SECTION_ID", $section_id);
        			$smarty->assign("T_MODULE_LANGUAGE_SECTION_OUTPUT", $output);
        		}
        	} else {
        		if ($_GET['output'] == 'json') {
        			/** @todo Get a way to return JSON errors */
        			echo json_encode(_MODULE_LANGUAGE_SECTION_INVALID);
					exit;
        		} else {
        			$this->setMessageVar(_MODULE_LANGUAGE_SECTION_INVALID, "failure");
        		}

        	}
        } else {
            return parent::getModule();
		}

        return true;
    }

    public function getSmartyTpl()
    {
        $smarty = $this -> getSmartyVar();
        $smarty -> assign("T_LANGUAGE_BASEDIR" , $this -> moduleBaseDir);
        $smarty -> assign("T_LANGUAGE_BASEURL" , $this -> moduleBaseUrl);
        $smarty -> assign("T_LANGUAGE_BASELINK" , $this -> moduleBaseLink);

        $selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_LANGUAGES;

        if ($selectedAction != self::GET_SECTION) {
	        $smarty -> assign("T_LANGUAGE_ACTION", $selectedAction);

	        return $this -> moduleBaseDir . "templates/default.tpl";
        }
    }

    public function getDisponibleLanguages()
    {
        $languages = MagesterSystem :: getLanguages();

        foreach($languages as $lang) {
            $return[] = $lang['name'];
        }

        return $return;
/*
		$directory = $this->moduleBaseDir . "languages/";

		$langFiles = scandir($directory, 1);
		natcasesort($langFiles);

		foreach ($langFiles as $file) {
    		if ($file == '.' || $file == '..' || strpos($file, '.') === 0) {
    			continue;
			} elseif (is_dir($directory . '/' . $file)) {
				$files[] = $file;
			}
		}

		return $files;
        */
    }

    public function getAttributesForLanguage($language_name)
    {
		return array(
			'localized_name'			=> $this->getLocalizedLanguageName($language_name),
		 	'translated_terms'			=> $this->getTotalTranslatedLanguageTerms($language_name),
		 	'translated_terms_total'	=> $this->getTotalTranslatedTerms(),
		 	'translated_modules'		=> $this->getTotalTranslatedLanguageModules($language_name),
		 	'translated_modules_total'	=> $this->getTotalTranslatedModules()/*,
		 	'translated_sections'		=> $this->getTotalTranslatedLanguageSections($language_name),
		 	'translated_sections_total'	=> $this->getTotalTranslatedSections(),
		 	*/
		);
    }
    public function getLocalizedLanguageName($language_name)
    {
    	$language_entry = sC_getTableData("languages", "translation", sprintf("name = '%s'", $language_name));
    	if (count($language_entry) == 0) {
			return $language_name;
    	} else {
    		return $language_entry[0]['translation'];
    	}
    }

    public function getTotalTranslatedLanguageTerms($language_name, $force = false)
    {
    	// DO A SUB-REQUEST TO ONLY DEFINE module_language TERMS
    	if (!is_array($this->totalLanguageTerms)) {
    		$this->totalLanguageTerms = array();
    	}
    	if (!array_key_exists($language_name, $this->totalLanguageTerms)) {
    		$this->totalLanguageTerms[$language_name] = -1;
    	}

    	if ($force || $this->totalLanguageTerms[$language_name] < 0) {
	    	$array_index = 'total';
	    	$JsonUrl	= $this->moduleBaseLink . 'functions/terms.total.php?language=' . $language_name . '&index=' . $array_index;

			if ($stream = fopen($JsonUrl, 'r')) {
				$output = stream_get_contents($stream);
				fclose($stream);
				$result = json_decode($output, true);

				$this->totalLanguageTerms[$language_name] = $result[$array_index];
			}
    	}
    	return $this->totalLanguageTerms[$language_name];
    }

    public function getTotalTranslatedTerms($force = false)
    {
    	// DO A SUB-REQUEST TO ONLY DEFINE module_language TERMS
    	if ($force || $this->totalTerms < 0) {
	    	$array_index = 'total';
	    	$JsonUrl	= $this->moduleBaseLink . 'functions/terms.total.php?index=' . $array_index;

			if ($stream = fopen($JsonUrl, 'r')) {
				$output = stream_get_contents($stream);
				fclose($stream);
				$result = json_decode($output, true);

				$this->totalTerms = $result[$array_index];
			}
    	}
    	return $this->totalTerms;
    }

	public function getTotalTranslatedLanguageModules($language_name, $force = false)
	{
		if (!is_array($this->totalLanguageModules)) {
    		$this->totalLanguageModules = array();
    	}
    	if (!array_key_exists($language_name, $this->totalLanguageModules)) {
    		$this->totalLanguageModules[$language_name] = -1;
    	}

    	if ($force || $this->totalLanguageModules[$language_name] < 0) {
    		$totalmodules = 0;

    		$modules = sC_getTableDataFlat("modules","name","active=1");
    		$moduleNames = $modules['name'];

	    	$directory = $this->moduleBaseDir . "languages/" . $language_name;

	    	$files = scandir($directory, 1);

	    	foreach ($files as $file) {
	    		if (in_array($file, $moduleNames)) {
	    			$totalmodules++;
	    		}
	    	}

			$this->totalLanguageModules[$language_name] = $totalmodules;
    	}
    	return $this->totalLanguageModules[$language_name];
	}

	public function getTotalTranslatedModules($force = false)
	{
    	// DO A SUB-REQUEST TO ONLY DEFINE module_language TERMS
    	if ($force || $this->totalModules < 0) {
			$modules = sC_countTableData("modules","*","active=1");
			$this->totalModules = $modules[0]['count'];
    	}
    	return $this->totalModules;
	}
	/*
	public function getTotalTranslatedLanguageSections($language_name, $force = false) {}
	public function getTotalTranslatedSections($force = false) {}
	*/
    public function getLanguageFile($language = null, $force = false)
    {
        if (self::$languageIncluded && !$force) {
            return;
        }
    	if (is_null($language)) {
    		$language = $this->getCurrentLanguage();
    	}

        if (isset($_SESSION['translation_mode']) && $_SESSION['translation_mode'] && !$force) {
            return;
        }

    	/** @todo CREATE A WRAPPER TO INCLUDE l10n DATA, LOCALIZED CONFIG DATA, ETC... */

        $filename = sprintf(G_ROOTPATH . "libraries/language/lang-%s.php.inc", $language);
        include_once($filename);
        /*
    	// REQUIRE ALL SECTIONS FILE TRANSLATED FILES
    	// READ DIRECTION language / $language /, AND INCLUDE FILES IN ORDER
    	$directory = $this->moduleBaseDir . "languages/" . $language;

    	$this->includeLanguageFiles($directory);
        */
        self::$languageIncluded = true;

	}

    public function getOldLanguageFile($language = null, $force = false)
    {
        if (self::$languageIncluded && !$force) {
            return;
        }
        if (is_null($language)) {
            $language = $this->getCurrentLanguage();
        }

        if (isset($_SESSION['translation_mode']) && $_SESSION['translation_mode'] && !$force) {
            return;
        }

        //$filename = sprintf(G_ROOTPATH . "libraries/language/lang-%s.php.inc", $language);
        //include_once($filename);
        
        // REQUIRE ALL SECTIONS FILE TRANSLATED FILES
        // READ DIRECTION language / $language /, AND INCLUDE FILES IN ORDER
        $directory = $this->moduleBaseDir . "languages/" . $language;

        $this->includeLanguageFiles($directory);
        
        self::$languageIncluded = true;
    }

    private function includeLanguageFiles($directory = null)
    {
    	if (is_null($directory)) {
    		$directory = $this->moduleBaseDir . "languages/" . $this->getCurrentLanguage();
    	}


    	$files = scandir($directory, 1);
    	natcasesort($files);

    	foreach ($files as $file) {
    		if ($file == '.' || $file == '..' || strpos($file, '.') === 0) {
    			continue;
			} elseif (is_dir($directory . '/' . $file)) {
                //echo $directory . '/' . $file . "<br />";
    			$this->includeLanguageFiles($directory . '/' . $file);
    		} elseif (is_file($directory . '/' . $file)) {
    			include_once($directory . '/' . $file);
    		}
    	}
    }

	private function getCurrentLanguage()
	{
		if (isset($_SESSION['s_language'])) {
			/** If there is a current language in the session, use that*/
			return $_SESSION['s_language'];
		} elseif ($GLOBALS['configuration']['default_language']) {
    		/** If there isn't a language in the session, use the default system language*/
			return $GLOBALS['configuration']['default_language'];
		} else {
    		//If there isn't neither a session language, or a default language in the configuration, use english by default
    		return "english";
		}
	}
}
