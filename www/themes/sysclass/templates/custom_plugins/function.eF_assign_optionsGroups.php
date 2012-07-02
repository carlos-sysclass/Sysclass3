<?php
/**

* prints the controlpanel Options Block

*

*/
function smarty_function_eF_assign_optionsGroups($params, &$smarty) {
	
	foreach ($params['groups'] as $groupId => $groupName) {
		$groupedParams = array(
			"title" => $groupName,
			"columns" => $params["columns"],
			"links" => array(),
			'settings'	=> array(
				"nohandle" => false
			)
		);
		foreach ($params['links'] as $linkId => $linkData) {
			if ($linkData['group'] == $groupId) {
				$groupedParams["links"][] = array(				
					"text"			=> $linkData["text"],
					"image"			=> $linkData["image"],
					"image_class"	=> $linkData["image_class"],
					"href"			=> $linkData["href"]
				);
			}
		}
		if (count($groupedParams["links"]) > 0) {
			$result[urlencode($groupedParams['title'])] = smarty_function_eF_template_printBlock($groupedParams, &$smarty);
		}
		
	}
	$smarty->assign($params['var'], $result);
}
?>