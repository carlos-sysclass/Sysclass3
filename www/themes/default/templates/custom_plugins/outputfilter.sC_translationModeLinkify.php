<?php
/**
* Replaces occurences of the form #filter:user_login-asdfas# with a personal message link
*/

function smarty_outputfilter_sC_translationModeLinkify($compiled, &$smarty)
{
	global $currentUser;
	$currentLanguage = $smarty->get_template_vars("T_BASE_LANGUAGE");

	if ($_SESSION['translation_mode'] && !is_null($currentUser)) {
		$userGroups = $currentUser->getGroups();
		$userGroupsIDs = array_keys($userGroups);
		$translationModeGroup = 12;

		if (in_array($translationModeGroup, $userGroupsIDs)) {
		
			$modules = sC_loadAllModules(true);
			$languages = $modules['module_language']->getDisponibleLanguages();

			$allTokens = $modules['module_language']->parseTokensFromSource();


			foreach($allTokens as $key => $value) {
				if (!strpos($compiled, $key)) {
					unset($allTokens[$key]);
				}
			}
			$langTokens = array();
			foreach($languages as $language) {
				$langTokens[$language] = $modules['module_language']->getTranslatedTokensAction($language);
				$langTokens[$language] = $langTokens[$language]['terms'];
				foreach($langTokens[$language] as $key => $value) {
					if (!strpos($compiled, $key)) {
						unset($langTokens[$language][$key]);
					} else {
						// IF THIS TOKEN DOES NOT EXISTS ON allTokens, INSERT THEN
						if (!array_key_exists($key, $allTokens)) {
							$allTokens[$key] = $key;
						}
					}
				}
			}

	//		T_LANGUAGE_LANGUAGES
			foreach($languages as $language) {
				$tokens = array();
				// CHECK TOKENS ON PAGE
				foreach($allTokens as $key => $value) {
					if (array_key_exists($key, $langTokens[$language])) {
						$tokens[$key] = $langTokens[$language][$key];
					} else {
						$tokens[$key] = $allTokens[$key];
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
	}
    return $compiled;
}