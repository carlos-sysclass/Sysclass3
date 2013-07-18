<?php
/**
* Replaces occurences of the form #filter:user_login-asdfas# with a personal message link
*/

function smarty_outputfilter_sC_translationModeLinkify($compiled, &$smarty)
{
	global $currentUser;
	$currentLanguage = $smarty->get_template_vars("T_BASE_LANGUAGE");
	if ($_SESSION['translation_mode'] && !is_null($currentUser) && $currentUser->user['user_type'] == 'administrator') {
	
		$modules = sC_loadAllModules(true);
		$languages = $modules['module_language']->getDisponibleLanguages();

//		T_LANGUAGE_LANGUAGES
		foreach($languages as $language) {
			$tokens = $modules['module_language']->getTranslatedTokensAction($language);
			$tokens = $tokens['terms'];
			// CHECK TOKENS ON PAGE
			foreach($tokens as $key => $value) {
				if (!strpos($compiled, $key)) {
					unset($tokens[$key]);
				} else {
					//$compiled = str_replace($key, "@" . $key . "@", $compiled);
				}
			}
			$modules['module_language']->setUsedPageTerms($language, $tokens);
			
			$smarty->assign("T_" . strtoupper($language) . "_USED_TERMS", $tokens);
			if ($language == $currentLanguage) {
				$smarty->assign("T_CURRENT_USED_TERMS", $tokens);
				$modules['module_language']->setUsedDefaultLanguage($language);
			}

		}
	}
    return $compiled;
}