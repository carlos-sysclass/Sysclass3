<?php 
abstract class AbstractSysclassController extends AbstractDatabaseController
{
	protected $current_user = null;
	public static $t = null;
	public function init($url, $method, $format, $root=NULL, $basePath="")
	{
		parent::init($url, $method, $format, $root, $basePath);

		// LOAD LANGUAGE MODULE
		
		$modulesDB = $this->_getTableData("modules","*","className = 'module_language' AND active=1");

		foreach ($modulesDB as $module) {
		    $folder = $module['position'];
		    $className = $module['className'];

		    require_once G_MODULESPATH.$folder."/".$className.".class.php";
		    if (class_exists($className)) {
		        $languageModule = new $className("", $folder);
		    } else {
		        $languageModule = false;
		    }
		    break;
		}
		if (!$languageModule) {
		    echo "não foi possível carregar os arquivos de linguagem";
		    exit;
		}

		//Language settings. $GLOBALS['loadLanguage'] can be used to exclude language files from loading, for example during certain ajax calls
		if (!isset($GLOBALS['loadLanguage']) || $GLOBALS['loadLanguage']) {
		    if (isset($_GET['bypass_language']) && sC_checkParameter($_GET['bypass_language'], 'filename') ) {
		        // We can bypass the current language any time by specifing 'bypass_language=<lang>' in the query string
		        $setLanguage = $_GET['bypass_language'];
		        //require_once $path."language/lang-".$_GET['bypass_language'].".php.inc";
		    } else {
		        if (isset($_SESSION['s_language']) ) {
		            // If there is a current language in the session, use that
		            //require_once $path."language/lang-".$_SESSION['s_language'].".php.inc";
		            $setLanguage = $_SESSION['s_language'];
		        } else {
		            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		            preg_match_all('/(\W|^)([a-z]{2}(-[a-z]{2}){0,1})/six', $lang, $m, PREG_PATTERN_ORDER);

		            foreach($m[2] as $code) {
		                $language_entry = sC_getTableData("languages", "name", sprintf("code = '%s'", $code));
		                if (count($language_entry) > 0) {
		                    $setLanguage = $language_entry[0]['name'];
		                    break;
		                }
		            }
		            if (!isset($setLanguage)) {
		                foreach($m[2] as $code) {
		                    $code = substr($code, 0, 2);
		                    $language_entry = sC_getTableData("languages", "name", sprintf("code = '%s'", $code));
		                    if (count($language_entry) > 0) {
		                        $setLanguage = $language_entry[0]['name'];
		                        break;
		                    }
		                }
		                if (!isset($setLanguage)) {
		                    if ($GLOBALS['configuration']['default_language']) {
		                        // If there isn't a language in the session, use the default system language
		                        //require_once $path."language/lang-".$GLOBALS['configuration']['default_language'].".php.inc";
		                        $setLanguage = $GLOBALS['configuration']['default_language'];
		                    } else {
		                        //If there isn't neither a session language, or a default language in the configuration, use english by default
		                        //require_once $path."language/lang-english.php.inc";
		                        $setLanguage = "english";
		                    }
		                }
		            }
		        }
		    }
		    $languageModule->getLanguageFile($setLanguage);
		}
		if (is_null(self::$t)) {
			self::$t = $this->model("translate");
		}
	}
	
	public function authorize()
	{
		$smarty = $this->getSmarty();
		// INJECT HERE SESSION AUTHORIZATION CODE
		try {
		    $this->current_user 	= MagesterUser::checkUserAccess();
		    $smarty->assign("T_CURRENT_USER", $this->current_user);
		} catch (Exception $e) {
		    if ($e->getCode() == MagesterUserException :: USER_NOT_LOGGED_IN) {
		        setcookie('c_request', http_build_query($_GET), time() + 300);
		    }
		    $this->redirect("login", $e->getMessage() . ' (' . $e->getCode() . ')', "failure");
		    exit;
		}
		return TRUE;
	}

	protected function onThemeRequest()
	{
		$this->setTheme('metronic');
	}

}
