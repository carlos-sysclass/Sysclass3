<?php
/**

* prints the controlpanel Options Block

*

*/
function smarty_function_eF_template_printOptions($params, &$smarty)
{

	//var_dump($params);

	foreach ($params['groups'] as $groupId => $groupName) {
		$groupedParams = array(
			"title" => $groupName,
			"columns" => $params["columns"],
			"links" => array(),
			"nohandle" => true
		);
		foreach ($params['links'] as $linkId => $linkData) {
			if ($linkData['group'] == $groupId) {
				$groupedParams["links"][] = array(
					"text"	=> $linkData["text"],
					"image"	=> $linkData["image"],
					"href"	=> $linkData["href"]
				);
			}
		}
		if (count($groupedParams["links"]) > 0) {
			$result[urlencode($groupedParams['title'])] = smarty_function_eF_template_printBlock($groupedParams, &$smarty);
		}
	}
	echo '<pre>';
	//var_dump($result);
	echo '</pre>';
	//exit;
	return $result;
}
