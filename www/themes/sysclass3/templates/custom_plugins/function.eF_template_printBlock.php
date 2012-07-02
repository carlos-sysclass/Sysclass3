<?php

/**

 * prints a block

 *

 */
function smarty_function_eF_template_printBlock($params, &$smarty) {
	if (empty($params['id'])) {
		$params['id'] = $params['title'];
	}
	if ($params['id'] == "") {
		return '';
	}
	$params['link'] ? $params['title'] = '<a href = "' . $params['link'] . '">' . $params['title'] . '</a>' : null;
	$params['data'] ? $params['content'] = $params['data'] : null; //'data' is used in printInnertable, and we put this here for compatibility
	/**

	 * Cookies for remembering the open/close status of blocks, and to display status depending on lesson layout settings if it's the control panel

	 * @todo: Make it better, to comply with new blocks (this one's copied from old innerTable functions

	 */
	$innerTableIdentifier = $GLOBALS['innerTableIdentifier'];
	$cookieString = md5($_SESSION['s_login'] . $_SESSION['s_lessons_ID'] . $GLOBALS['innerTableIdentifier'] . urlencode($params['title']));
	$cookieValue = $_COOKIE['innerTables'][$cookieString];
	/**

	 * $params['settings'] is an array that defines the way this block will appear. Currently supported

	 * settings are:

	 * - nohandle: Will not display an open/close handle, the block will be always visible

	 */
	if (isset($params['settings'])) {
		isset($params['settings']['nohandle']) ? $nohandle = true : $nohandle = false;
	}
	/**

	 * $params['expand'] is used to specify whether the block will show up expanded (default) or collapsed. This behavior is overriden

	 * by the user's preference (via cookie)

	 */
	if (isset($params['expand'])) {
		$params['expand'] ? $expand = true : $expand = false;
	}
	/**

	 * $params['options'] is an array with handles that are displayed on the block header,

	 * and are encompassing custom functionality. Each handle is an <a> element that contains an <img> element.

	 * The array is populated with any of the following entries:

	 * - image: The source of the handle's image

	 * - text: The text displayed in the title and alt fields of the image

	 * - href: The link that the handle points at

	 * - onclick: an action assigned to the onclick event of the <a> tag

	 * - class: The class name of the <a> tag

	 * - target: The target that the <a> link will open at

	 * - id: The id of the <a> tag

	 */
	/*
	  var_dump($params['options']);
	*/
	if (isset($params['options'])) {
		$optionsString = '';
		foreach ($params['options'] as $key => $value) {
			isset($value['onClick']) ? $value['onclick'] = $value['onClick'] : null; //sometimes onClick is used instead of onclick.
			isset($value['class']) && $value['class'] ? $classstr = 'class = "' . $value['class'] . '"' : $classstr = '';
			isset($value['target']) && $value['target'] ? $target = 'target = "' . $value['target'] . '"' : $target = '';
			isset($value['id']) && $value['id'] ? $id = 'id = "' . $value['id'] . '"' : $id = '';
			isset($value['href']) && $value['href'] ? $href = 'href = "' . $value['href'] . '"' : $href = '';
			isset($value['datasource']) && is_array($value['datasource']) ? $datasource = $value['datasource'] : $datasource = null;
			isset($value['onclick']) && $value['onclick'] ? $onclick = 'onclick = "' . $value['onclick'] . '"' : $onclick = '';
			!isset($params['absoluteImagePath']) && $value['image'] ? $value['image'] = 'images/' . $value['image'] : null; //if absoluteImagePath is specified, it means that $params['image'] contains an absolute path (or anyway it refers to an image not under www/images/)

			if ($datasource != null) {
				// PUT A COMBO


				/*

				  $optionsString .= sprintf("<li class=\"handles_combobox\">
				  <!--
				  <img height=\"19\" width=\"19\" class=\"sprite16 sprite16-arrow_down\" $id $onclick $classstr src = \"".$value['image']."\" title = \"".$value['text']."\" alt = \"".$value['text']."\" />
				  <span class=\"handles_combobox_label\">Módulos</span>
				  -->
				  <select %s name=\"%s\" %s %s>%s</select>
				  </li>",  $id, $value['id'], $classstr, $onclick, implode("\n", $option));

				 */
				/*
				  $optionsString .= sprintf("<li class=\"handles_combobox\">
				  <button class=\"event-conf\" type=\"button\">
				  <span class=\"label\">Add All</span>
				  <img height=\"19\" width=\"19\" class=\"sprite16 sprite16-arrow_down\" $id $onclick $classstr src = \"".$value['image']."\" title = \"".$value['text']."\" alt = \"".$value['text']."\" />

				  <ul class=\"dropdown\">
				  <li class="dropdown_group"><a href=\"javascript: changeAccount('admin');\">André Kucaniz. (admin)</a></li>
				  <li><a href=\"javascript: changeAccount('admin');\">André Kucaniz. (admin)</a></li>
				  <li><a href=\"javascript: changeAccount('admin');\">André Kucaniz. (admin)</a></li>
				  <li><a href=\"javascript: changeAccount('admin');\">André Kucaniz. (admin)</a></li>
				  <li><a href=\"javascript: changeAccount('admin');\">André Kucaniz. (admin)</a></li>
				  </ul>
				  </button>
				  </li>");
				 */
				foreach ($datasource as $data_key => $data_value) {
					if (is_array($data_value)) {
						$newoption = sprintf('<li class="combox-title"><a href="javascript: void(0);">%s</a></li>', $data_key);
						foreach ($data_value as $group_key => $group_value) {
							$newoption .= sprintf('<li><a href="%s">%s</a></li>', /* $group_key, */ $group_value['href'], $group_value['name']);
						}
						//$newoption .= '</optgroup>';
						$option[] = $newoption;
					} else {
						$option[] = sprintf('<li><a href="%s">%s</a></li>', /* $data_key, */ $data_value['href'], $data_value['name']);
					}
				}

				if (!$value['image']) {
					$value['image'] = "16x16/add.png";
				}
				/*
				  $optionsString .= sprintf("<li class=\"handles_combobox\">
				  <a href=\"javascript: void(0); \">
				  <button class=\"event-conf\">
				  <span class=\"label\">%s</span>
				  <img height=\"19\" width=\"19\" class=\"sprite28 sprite28-arrow_down\" $id $onclick $classstr src = \"".$value['image']."\" title = \"".$value['text']."\" alt = \"".$value['text']."\" />

				  <ul class=\"dropdown\">
				  %s
				  </ul>
				  </button>
				  </li>", $value['text'], implode("\n", $option));
				 */
				/*
				  $optionsString .= sprintf("<li class=\"has_dropdown handles_combobox\">
				  <a href=\"javascript: void(0); \">
				  %s
				  <img class=\"sprite28 sprite28-arrow_down\" $id $onclick $classstr src = \"images/others/transparent.png\" title = \"".$value['text']."\" alt = \"".$value['text']."\" />
				  </a>
				  <ul class=\"dropdown\">
				  %s
				  </ul>
				  </li>", $value['text'], implode("\n", $option));

				 */

				$optionsString .= sprintf("<div class=\"handles_combobox\">
												<ul class=\"clear\">
													<li class=\"has_dropdown\">
														<a href=\"javascript: void(0); \">
															<span style=\"float: left; margin: 0 10px;\">%s</span>
															<img class=\"sprite16 sprite16-arrow_down\" $id $onclick $classstr src = \"images/others/transparent.png\" title = \"" . $value['text'] . "\" alt = \"" . $value['text'] . "\" />
														</a>
						      			     			<ul class=\"dropdown\">%s</ul>
								        			</li>
												</ul></div>", $value['text'], implode("\n", $option));
			} else {
				if ($href) {
					$optionsString .= "<li class=\"" . $value['class'] . "\"><a $id $href $onclick $target $classstr><img src = \"" . $value['image'] . "\" class=\"" . $value['image-class'] . "\" border=\"0\" title = \"" . $value['text'] . "\" alt = \"" . $value['text'] . "\" /></a></li>";
				} else {
					$optionsString .= "<li class=\"" . $value['class'] . "\"><img class=\"" . $value['image-class'] . "\" $id $onclick $classstr src = \"" . $value['image'] . "\" title = \"" . $value['text'] . "\" alt = \"" . $value['text'] . "\" /></li>";
				}
			}
		}
	}
	/*
	  $optionsString .= '
	  <li class="has_dropdown">
	  <a href="javascript: void(0); ">Acesso</a>
	  <ul class="dropdown">
	  <li><a href="javascript: changeAccount("admin");">André Kucaniz. (admin)</a></li>
	  </ul>
	  </li>
	  ';
	 */
	//$optionsString .= "<a href = 'http://docs.magester.net' target = '_new'><img src = 'images/16x16/help.png' title = '"._HELP."' alt = '"._HELP."' /></a>";
	//$optionsString .= "<a href = 'javascript:void(0)' onclick = 'eF_js_showDivPopup(\"search\", 0, \"cse\")'><img src = 'images/16x16/help.png' title = '"._HELP."' alt = '"._HELP."' /></a>";
	/**

	 * The "links" parameter is used to create an icon table, that is a block content that consists of rows of icons

	 * The parameters available are the same as for the options case above, with the only difference being that this time

	 * there is a $params['links'] parameter instead of $params['options'].

	 * In addition, if an optional "groups" parameter is defined, then the available icons may be divided into groups

	 * Keep in mind that there can't be $params['content'] and $params['link'] at the same time; the former overwrites the latter.

	 * Each array entry consists of the following:

	 * - text: The text that accompanies the icon (mandatory)

	 * - image: The icon src (mandatory)

	 * - href: Where the icon's link will point to, defaults to javascript:void(0)

	 * - onclick: An action to perform

	 * - title: The alt/title to use for the icon, defaults to the same as 'text' above

	 * - group: If icons are separated to groups, which group to put this entry into

	 */
	if (isset($params['links']) && !isset($params['content'])) {
		!isset($params['columns']) || !$params['columns'] ? $params['columns'] = 4 : null;
		$width = round(100 / $params['columns']); //Divide available width so that it can be equally assigned to table cells
		//Use a default group, if none is specified. This way the algorithm for displaying groups is greatly simplified
		if (!isset($params['groups']) || sizeof($params['groups']) == 0) {
			$params['groups'] = array(0 => 0);
		}
		foreach ($params['groups'] as $groupId => $name) {
			$groupId ? $linksString[$groupId] .= '<fieldset class = "fieldsetSeparator"><legend>' . $name['title'] . '</legend>' : null;
			$linksString[$groupId] .= '
       <table class = "iconTable">';
			$counter = 0; //$counter is used to count how many icons are put in each group, so that the <tr>s are put in correct place, and empty <td>s are appended where needed
			//Print group separator, only if $groupId > 0. This way, the default group specified above, does not print any group separator
			//$groupId ? $linksString[$groupId] .= '<tr><td class = "group" colspan = "'.$params['columns'].'">'.$name.'</td></tr>' : null;
			foreach (array_values($params['links']) as $key => $value) { //array_values makes sure that entries are displayed correctly, even if keys are not sequential
				if ($value['group'] == $groupId) {
					$nonEmptySection[$groupId] = true;
					isset($value['onClick']) ? $value['onclick'] = $value['onClick'] : null; //sometimes onClick is used instead of onclick.
					isset($value['class']) && $value['class'] ? $classstr = 'class = "' . $value['class'] . '"' : $classstr = '';
					isset($value['target']) && $value['target'] ? $target = 'target = "' . $value['target'] . '"' : $target = '';
					isset($value['id']) && $value['id'] ? $id = 'id = "' . $value['id'] . '"' : $id = '';
					isset($value['href']) && $value['href'] ? $href = 'href = "' . $value['href'] . '"' : $href = 'href = "javascript:void(0)"';
					isset($value['onclick']) && $value['onclick'] ? $onclick = 'onclick = "' . $value['onclick'] . '"' : $onclick = '';
					isset($value['title']) && $value['title'] ? $title = 'title = "' . $value['title'] . '" alt = "' . $value['title'] . '"' : $title = 'title = "' . $value['text'] . '" alt = "' . $value['text'] . '"';
					if ($counter++ % $params['columns'] == 0) {
						$linksString[$groupId] .= '<tr>';
					}
					$value['image'] && strpos($value['image'], "modules/") === false ? $value['image'] = 'images/' . $value['image'] : null; //Make sure that modules images are taken using absolute paths
					$linksString[$groupId] .= "
                     <td style = 'width:$width%;' class = 'iconData'>
                         <a $id $href $onclick $target>
                          <img $classstr src = '" . $value['image'] . "' $title /><br>
                          " . $value['text'] . "
                         </a>
                        </td>";
					if ($counter % $params['columns'] == 0) {
						$linksString[$groupId] .= '</tr>';
					}
				}
			}
			//If the icons where not a factor of $params[columns'], then there are some gaps left in the table. We must fill these gaps with empty table cells
			if ($counter % $params['columns'] > 0) {
				for ($i = $params['columns']; $i > $counter % $params['columns']; $i--) {
					$linksString[$groupId] .= '<td></td>';
				}
			}
			$linksString[$groupId] .= '</table>';
			$groupId ? $linksString[$groupId] .= '</fieldset>' : null;
		}
		foreach ($linksString as $groupId => $foo) {
			if (!isset($nonEmptySection[$groupId])) {
				unset($linksString[$groupId]);
			}
		}
		$params['content'] = implode("", $linksString);
	}
	/**

	 * The "main_options" parameter is used to display an options menu (much like "tabs") on the top of the block

	 * Each option consists of the following values:

	 * - image: The image that will be displayed next to the option

	 * - title: The option title

	 * - link: the address that the href attribute will point to

	 * - selected: if it's 1, then the corresponding option will display as "selected"

	 */
	$mainOptions = '';
	if (isset($params['main_options']) && sizeof($params['main_options']) > 0) {
		foreach ($params['main_options'] as $key => $value) {
			isset($value['onClick']) ? $value['onclick'] = $value['onClick'] : null; //sometimes onClick is used instead of onclick.
			$mainOptions .= '
                        <span ' . ($value['selected'] ? 'class = "selected"' : null) . '>
                            <a href = "' . $value['link'] . '"><img src = "images/' . $value['image'] . '" alt = "' . $value['title'] . '" title = "' . $value['title'] . '"/></a>
                            <a href = "' . $value['link'] . '" onclick = "' . $value['onclick'] . '">' . $value['title'] . '</a>
                        </span>';
		}
		$mainOptions = '<div class = "toolbar">' . $mainOptions . '</div><div class="clear"></div>';
	}
	!isset($params['absoluteImagePath']) && $params['image'] ? $params['image'] = 'images/' . $params['image'] : null; //if absoluteImagePath is specified, it means that $params['image'] contains an absolute path (or anyway it refers to an image not under www/images/)
	isset($params['image']) && $params['image'] ? $image = '<img src = "' . $params['image'] . '" alt = "' . strip_tags($params['title']) . '" title = "' . strip_tags($params['title']) . '" />' : $image = '';
	if ($GLOBALS['currentTheme']->options['images_displaying'] == 2 || ($GLOBALS['currentTheme']->options['images_displaying'] == 1 && basename($_SERVER['PHP_SELF']) == 'index.php')) {
		$image = '';
	}
	$handleString = '';
	if ($params['help'] && $GLOBALS['configuration']['disable_help'] == 0) {
		//$handleString .= '<a href = "javascript:void(0);"><img src = "images/16x16/help.png"  title = "'.$GLOBALS['configuration']['help_url'].'/'.$params['help'].'" onclick = "PopupCenter(\''.$GLOBALS['configuration']['help_url'].'?title='.$params['help'].'&useskin=cologneblue&printable=yes\', \'helpwindow\', \'800\', \'500\')"></a>';
	}
	if (!$nohandle) {
		/* DISABLING EXPAND/COLLAPSE FUNCIONALITY
		  /*
		  if ($cookieValue == 'hidden' || (!$cookieValue && isset($expand) && !$expand)) {
		  $handleString .= '<img class = "close" src = "images/16x16/navigate_down.png" alt = "'._EXPANDCOLLAPSEBLOCK.'" title = "'._EXPANDCOLLAPSEBLOCK.'" onclick = "toggleBlock(this, \''.$cookieString.'\')" id = "'.urlencode($params['title']).'_image">';
		  $showContent = 'display:none';
		  } else {
		  $handleString .= '<img class = "open" src = "images/16x16/navigate_up.png" alt = "'._EXPANDCOLLAPSEBLOCK.'" title = "'._EXPANDCOLLAPSEBLOCK.'" onclick = "toggleBlock(this, \''.$cookieString.'\')"  id = "'.urlencode($params['title']).'_image">';
		  $showContent = '';
		  }
		 */
	}
	//This is hidden (css) unless it's inside a sortable ul

	$contentclass = array_key_exists('contentclass', $params) ? $params['contentclass'] : 'blockContents';
	//$handleString .= '<img class = "blockMoveHandle" src = "images/16x16/attachment.png" alt = "'._MOVEBLOCK.'" title = "'._MOVEBLOCK.'" onmousedown = "createSortable(\'firstlist\');createSortable(\'secondlist\');if (window.showBorders) showBorders(event)" onmouseup = "if (window.showBorders) hideBorders(event)">';
	/*
	  $handleString .=
	  '<li class="mover">
	  <img height="19" width="19" class="imgs_move blockMoveHandle" src="images/transp.png" alt = "'._MOVEBLOCK.'" title = "'._MOVEBLOCK.'" onmousedown = "createSortable(\'firstlist\');createSortable(\'secondlist\');if (window.showBorders) showBorders(event)" onmouseup = "if (window.showBorders) hideBorders(event)">
	  </li>';
	 */

	$str = '
	<div class = "block" style = "' . $params['style'] . ';" id = "' . urlencode($params['title']) . '" >
		<div class="block-title">
    	      <div class = "title">' . /* $image. */'' . $params['title'] .
			'<div class="min-text">' . $params['sub_title'] . '</div>' .
			'</div>' .
			'<ul class="clear handles">' . $optionsString . $handleString . '</ul>
	          
		</div>   
		<div class="clear"></div>
        <div class = " ' . $contentclass . '" >
          ' . $mainOptions . '
          <div class = "content" style = "' . $showContent . ';" id = "' . urlencode($params['title']) . '_content" onmousedown = "if ($(\'firstlist\')) {Sortable.destroy(\'firstlist\');}if ($(\'secondlist\')) {Sortable.destroy(\'secondlist\');}">
     ' . $params['content'] . '
    </div>
          <span style = "display:none">&nbsp;</span>
        </div>
		<div class="columns clear bt-space15"></div>
    </div>
    
    ';
	if ($params['tabber']) {
		if ($_GET['tab'] == $params['tabber']) {
			$tabberdefault = "tabbertabdefault";
		}
		$str = '<div class = "tabbertab ' . $tabberdefault . '"><h3>' . $params['title'] . '</h3>' . $str . '</div>';
	}
	if (!$params['content'] && !$params['options']) {
		return '';
	} else {
		return $str;
	}
}

/*

  <div class = "block content">

  <span class = "title">'.$params['title'].'</span>

  <span class = "toggle open" onclick = "toggleBlock(this)"></span>

  <span class = "subtitle">'.$params['sub_title'].'</span>

  <div class = "content">'.$params['content'].'</div>

  <span style = "display:none">&nbsp;</span>

  </div>

 */
?>
