<?php

class module_language extends MagesterModule {
	
	protected $sections;
	
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
    public function getName() {
        return _MODULE_LANGUAGE;
    }

    public function getPermittedRoles() {
        return array("administrator" ,"professor" ,"student");
    }

    public function isLessonModule() {
        return false;
    }
    	/*
    public function getCenterLinkInfo() {

        $currentUser = $this -> getCurrentUser();
        if ($currentUser -> getType() == "administrator") {
            return array('title' => _MODULE_LANGUAGE,
                         'image' => $this -> moduleBaseDir . 'images/language.png',
                         'link'  => $this -> moduleBaseUrl
            );
        }

    }
        */
    public function getNavigationLinks() {
    	$selectedAction = isset($_GET['action']) ? $_GET['action'] : self::GET_LANGUAGES;

        $basicNavArray = array (
			array ('title' => _HOME, 'link' => "administrator.php?ctg=control_panel"),
    		array ('title' => _MODULE_LANGUAGE_MANAGEMENT, 'link'  => $this -> moduleBaseUrl)
		);
        /*
		if ($selectedAction == self::EDIT_POLO) {
            $basicNavArray[] = array ('title' => _MODULE_POLOS_EDITPOLO, 'link'  => $this -> moduleBaseUrl . "&action=" . self::EDIT_POLO . "&polo_id=". $_GET['polo_id']);
		} else if ($selectedAction == self::ADD_POLO) {
            $basicNavArray[] = array ('title' => _MODULE_POLOS_ADDPOLO, 'link'  => $this -> moduleBaseUrl . "&action=" . self::ADD_POLO);
		}
		*/
        return $basicNavArray;
    }
/*
    public function getSidebarLinkInfo() {
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
    public function getLinkToHighlight() {
        return 'language_link_id1';
    }

    public function getSection($section_ID, $force = false) {
    	if (!is_array($this->sections)) {
    		$this->sections = array();
    	}
        // 1. LOAD SELECTED SECTION
        if (!$force && array_key_exists($section_ID, $this->sections)) {
        	 return $this->sections[$section_ID];
        }
        if (eF_checkParameter($section_ID, 'directory')) {
        	$filename = $this->moduleBaseDir . "sections/" . $section_ID . ".php.inc";
        	if (file_exists($filename)) {
        		return $this->sections[$section_ID] = require_once($filename);
        	}
        }
        return false;
    }
    
    public function getCurrentHolidays($year = null, $asObjects = true) {
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
    	
    	foreach($fixedHolidays as $day => $descricao) {
    		$holiday = date_create_from_format("d/m/Y", $day . "/" . $year);
    		if (!$asObjects) {
    			$holidays[] = $holiday->format("y-m-d");
    		} else {
    			$holidays[] = $holiday;
    		}
    	}
    	 
    	return $holidays;
    	
    	
    	
    }

    /* MAIN-INDEPENDENT MODULE PAGES */
    public function getModule() {
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
        	$files = $this->getDisponibleLanguages();
        	
        	$languages = array();
        	foreach($files as $langName) {
        		$languages[] = array_merge(
        			array(
        				'english_name'	=> ucfirst($langName)
        			),
        			$this->getAttributesForLanguage($langName)
        		);
        	}
        	
        	$smarty -> assign ("T_LANGUAGES", $languages);
		}
        return true;
    }

    public function getSmartyTpl() {
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
    
    public function getDisponibleLanguages() {
		$directory = $this->moduleBaseDir . "languages/";
        
		$langFiles = scandir($directory, 1);
		natcasesort($langFiles);
    
		foreach($langFiles as $file) {
    		if ($file == '.' || $file == '..' || strpos($file, '.') === 0) {
    			continue;
			} elseif (is_dir($directory . '/' . $file)) {
				$files[] = $file;
			}
		}
		
		return $files;
    }
    
    public function getAttributesForLanguage($language_name) {
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
    public function getLocalizedLanguageName($language_name) {
    	$language_entry = eF_getTableData("languages", "translation", sprintf("name = '%s'", $language_name));
    	if (count($language_entry) == 0) {
			return $language_name;
    	} else {
    		return $language_entry[0]['translation'];
    	}
    }

    public function getTotalTranslatedLanguageTerms($language_name, $force = false) {
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

    public function getTotalTranslatedTerms($force = false) {
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
    
	public function getTotalTranslatedLanguageModules($language_name, $force = false) {
		if (!is_array($this->totalLanguageModules)) {
    		$this->totalLanguageModules = array();
    	}
    	if (!array_key_exists($language_name, $this->totalLanguageModules)) {
    		$this->totalLanguageModules[$language_name] = -1;
    	}
    	
    	if ($force || $this->totalLanguageModules[$language_name] < 0) {
    		$totalmodules = 0;
    		
    		$modules = eF_getTableDataFlat("modules","name","active=1");
    		$moduleNames = $modules['name'];
    		
	    	$directory = $this->moduleBaseDir . "languages/" . $language_name;
	    	 
	    	$files = scandir($directory, 1);
	    	
	    	foreach($files as $file) {
	    		if (in_array($file, $moduleNames)) {
	    			$totalmodules++;
	    		}
	    	}
    		
			$this->totalLanguageModules[$language_name] = $totalmodules;
    	}
    	return $this->totalLanguageModules[$language_name];
	} 
	
	public function getTotalTranslatedModules($force = false) {
    	// DO A SUB-REQUEST TO ONLY DEFINE module_language TERMS
    	if ($force || $this->totalModules < 0) {
			$modules = eF_countTableData("modules","*","active=1");
			$this->totalModules = $modules[0]['count'];
    	}
    	return $this->totalModules;
	}
	/*
	public function getTotalTranslatedLanguageSections($language_name, $force = false) {} 
	public function getTotalTranslatedSections($force = false) {}
	*/
    public function getLanguageFile($language = null) {
    	if (is_null($language)) {
    		$language = $this->getCurrentLanguage();
    	}
    	
    	/** @todo CREATE A WRAPPER TO INCLUDE l10n DATA, LOCALIZED CONFIG DATA, ETC... */
    	
    	// REQUIRE ALL SECTIONS FILE TRANSLATED FILES
    	// READ DIRECTION language / $language /, AND INCLUDE FILES IN ORDER
    	$directory = $this->moduleBaseDir . "languages/" . $language;
    	
    	$this->includeLanguageFiles($directory);
	}
    
    private function includeLanguageFiles($directory = null) {
    	if (is_null($directory)) {
    		$directory = $this->moduleBaseDir . "languages/" . $this->getCurrentLanguage();
    	}
    	 
    	$files = scandir($directory, 1);
    	natcasesort($files);
    	
    	
    	foreach($files as $file) {
    		if ($file == '.' || $file == '..' || strpos($file, '.') === 0) {
    			continue;
			} elseif (is_dir($directory . '/' . $file)) {
    			$this->includeLanguageFiles($directory . '/' . $file);
    		} elseif (is_file($directory . '/' . $file)) {
    			include_once($directory . '/' . $file);
    		}
    	}
    }
    
	private function getCurrentLanguage() {
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
