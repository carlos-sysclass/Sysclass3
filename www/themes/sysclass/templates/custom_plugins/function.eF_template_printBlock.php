<?php
/**

* prints a block

*

*/
function smarty_function_eF_template_printBlock($params, &$smarty) {
 if ($params['title'] == "") {return '';}
    $params['link'] ? $params['title'] = '<a href = "'.$params['link'].'">'.$params['title'].'</a>' : null;
 	$params['data'] ? $params['content'] = $params['data'] : null; //'data' is used in printInnertable, and we put this here for compatibility
 	$classes = array();
 /**

	 * Cookies for remembering the open/close status of blocks, and to display status depending on lesson layout settings if it's the control panel

	 * @todo: Make it better, to comply with new blocks (this one's copied from old innerTable functions

	 */
    $innerTableIdentifier = $GLOBALS['innerTableIdentifier'];
    $cookieString = md5($_SESSION['s_login'].$_SESSION['s_lessons_ID'].$GLOBALS['innerTableIdentifier'].urlencode($params['title']));
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
    
	if (isset($params['options'])) {
		$optionsString = '';
		foreach ($params['options'] as $key => $value) {
			isset($value['onClick']) ? $value['onclick'] = $value['onClick'] : null; //sometimes onClick is used instead of onclick.
			$classstr = 'class = "'. implode(' ', $classes) . ' ' . $value['class'].'"';
			isset($value['target']) && $value['target'] ? $target = 'target = "'.$value['target'].'"' : $target = '';
			isset($value['id']) && $value['id'] ? $id = 'id = "'.$value['id'].'"' : $id = '';
			isset($value['href']) && $value['href'] ? $href = 'href = "'.$value['href'].'"' : $href = '';
			isset($value['href']) && $value['href'] ? $href = 'href = "'.$value['href'].'"' : $href = '';
			!isset($params['absoluteImagePath']) && $value['image'] ? $value['image'] = 'images/'.$value['image'] : null; //if absoluteImagePath is specified, it means that $params['image'] contains an absolute path (or anyway it refers to an image not under www/images/)
			if ($href) {
				$optionsString .= "<a $id $href $onclick $target $classstr>" . '<img src ="' . $value['image'] . '" title ="'.$value['text'].'" alt ="' .$value['text']. ' /></a>';
			} else {
				$optionsString .= "<img class = 'handle' $id $onclick $classstr src = '".$value['image']."' title = '".$value['text']."' alt = '".$value['text']."' />";
			}
		}
	}
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
	//if (isset($params['links']) && !isset($params['content'])) {
	
    $handleString = '';
    if ($params['help'] && $GLOBALS['configuration']['disable_help'] == 0) {
        $handleString .= '<a href = "javascript:void(0);"><img src = "images/16x16/help.png"  title = "'.$GLOBALS['configuration']['help_url'].'/'.$params['help'].'" onclick = "PopupCenter(\''.$GLOBALS['configuration']['help_url'].'?title='.$params['help'].'&useskin=cologneblue&printable=yes\', \'helpwindow\', \'800\', \'500\')"></a>';
    }
    if (!$nohandle) {
        if ($cookieValue == 'hidden' || (!$cookieValue && isset($expand) && !$expand)) {
            $handleString .= '<img class = "close" src = "images/16x16/navigate_down.png" alt = "'._EXPANDCOLLAPSEBLOCK.'" title = "'._EXPANDCOLLAPSEBLOCK.'" onclick = "toggleBlock(this, \''.$cookieString.'\')" id = "'.urlencode($params['title']).'_image">';
            $showContent = 'display:none';
        } else {
            $handleString .= '<img class = "open" src = "images/16x16/navigate_up.png" alt = "'._EXPANDCOLLAPSEBLOCK.'" title = "'._EXPANDCOLLAPSEBLOCK.'" onclick = "toggleBlock(this, \''.$cookieString.'\')"  id = "'.urlencode($params['title']).'_image">';
            $showContent = '';
        }
    }
    //This is hidden (css) unless it's inside a sortable ul
 	$handleString .= '<img class = "blockMoveHandle" src = "images/16x16/attachment.png" alt = "'._MOVEBLOCK.'" title = "'._MOVEBLOCK.'" onmousedown = "createSortable(\'firstlist\');createSortable(\'secondlist\');if (window.showBorders) showBorders(event)" onmouseup = "if (window.showBorders) hideBorders(event)">';
 
		
	if (isset($params['headerlinks'])) {
		
		!isset($params['columns']) || !$params['columns'] ? $params['columns'] = 4 : null;
		$width = round(100 / $params['columns']); //Divide available width so that it can be equally assigned to table cells
		//Use a default group, if none is specified. This way the algorithm for displaying groups is greatly simplified
		if (!isset($params['groups']) || sizeof($params['groups']) == 0) {
			$params['groups'] = array(0 => 0);
		}
		foreach ($params['groups'] as $groupId => $name) {
			$groupId ? $linksString[$groupId] .= '<fieldset class = "fieldsetSeparator"><legend>'.$name.'</legend>' : null;
			$linksString[$groupId] .= '<table class = "iconTable">';
			$counter = 0; //$counter is used to count how many icons are put in each group, so that the <tr>s are put in correct place, and empty <td>s are appended where needed
   //Print group separator, only if $groupId > 0. This way, the default group specified above, does not print any group separator
      //$groupId ? $linksString[$groupId] .= '<tr><td class = "group" colspan = "'.$params['columns'].'">'.$name.'</td></tr>' : null;
			foreach (array_values($params['headerlinks']) as $key => $value) { //array_values makes sure that entries are displayed correctly, even if keys are not sequential
				if ($value['group'] == $groupId) {
					$nonEmptySection[$groupId] = true;
					isset($value['onClick']) ? $value['onclick'] = $value['onClick'] : null; //sometimes onClick is used instead of onclick.
					isset($value['class']) && $value['class'] ? $classstr = 'class = "'.$value['class'].'"' : $classstr = '';
					isset($value['target']) && $value['target'] ? $target = 'target = "'.$value['target'].'"' : $target = '';
					isset($value['id']) && $value['id'] ? $id = 'id = "'.$value['id'].'"' : $id = '';
					isset($value['href']) && $value['href'] ? $href = 'href = "'.$value['href'].'"' : $href = 'href = "javascript:void(0)"';
					isset($value['onclick'])&& $value['onclick'] ? $onclick = 'onclick = "'.$value['onclick'].'"' : $onclick = '';
					isset($value['title']) && $value['title'] ? $title = 'title = "'.$value['title'].'" alt = "'.$value['title'].'"' : $title = 'title = "'.$value['text'].'" alt = "'.$value['text'].'"';
					isset($value['selected'])&& $value['selected'] ? $liClass = 'class="current"' : $liClass = '';
					
					/*
					return '
					
						
					<div class="box grid_16">
					
</div>';
					
					*/
					
					if ($counter++ % $params['columns'] == 0) {
						$linksString[$groupId] .= '<div class="toggle_container wizard"><div class="wizard_steps"><ul class="clearfix">';
					}
					$value['image'] && strpos($value['image'], "modules/") === false ? $value['image'] = 'images/'.$value['image'] : null; //Make sure that modules images are taken using absolute paths
					
					$linksString[$groupId] .= "
						<li $liClass>
							<a $id $href $onclick $target class=\"clearfix\">
								<img $classstr src = '".$value['image']."' $title />
								<span>" . $value['text'] . "</span>
								
							</a>
						</li>					
					";
					/*
						<td style = 'width:$width%;' class = 'iconData'>
							<a $id $href $onclick $target>
								
								".."
							</a>
						</td>";
					*/
					if ($counter % $params['columns'] == 0) {
						$linksString[$groupId] .= '</ul></div></div>';
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
		$params['headerlinks'] = /*$params['content'] = */ implode("", $linksString);
	}
	
	if (isset($params['links'])) {
		!isset($params['columns']) || !$params['columns'] ? $params['columns'] = 4 : null;
		$width = round(100 / $params['columns']); //Divide available width so that it can be equally assigned to table cells
		//Use a default group, if none is specified. This way the algorithm for displaying groups is greatly simplified
		if (!isset($params['groups']) || sizeof($params['groups']) == 0) {
			$params['groups'] = array(0 => 0);
		}
		
		foreach ($params['groups'] as $groupId => $name) {
			$counter = 0; //$counter is used to count how many icons are put in each group, so that the <tr>s are put in correct place, and empty <td>s are appended where needed
			foreach (array_values($params['links']) as $key => $value) { //array_values makes sure that entries are displayed correctly, even if keys are not sequential
				if ($value['group'] == $groupId) {
					$nonEmptySection[$groupId] = true;
					isset($value['onClick']) ? $value['onclick'] = $value['onClick'] : null; //sometimes onClick is used instead of onclick.
					isset($value['class']) && $value['class'] ? $classstr = 'class = "'.$value['class'].'"' : $classstr = '';
					isset($value['target']) && $value['target'] ? $target = 'target = "'.$value['target'].'"' : $target = '';
					isset($value['id']) && $value['id'] ? $id = 'id = "'.$value['id'].'"' : $id = '';
					isset($value['href']) && $value['href'] ? $href = 'href = "'.$value['href'].'"' : $href = 'href = "javascript:void(0)"';
					isset($value['onclick'])&& $value['onclick'] ? $onclick = 'onclick = "'.$value['onclick'].'"' : $onclick = '';
					isset($value['title']) && $value['title'] ? $title = 'title = "'.$value['title'].'" alt = "'.$value['title'].'"' : $title = 'title = "'.$value['text'].'" alt = "'.$value['text'].'"';
					isset($value['image_class']) && $value['image_class'] ? $value['image_class'] : '';
					
					$gridClass = "grid_" . floor(16 / $params['columns']); 
					isset($value['selected'])&& $value['selected'] ? $liClass = 'class="' . $gridClass . ' current"' : $liClass = 'class="' . $gridClass . '"';
					
					if ($counter++ % $params['columns'] == 0) {
						$linksString[$groupId] .= '<div class="block-links grid_16">';
					}
					$value['image'] && strpos($value['image'], "modules/") === false ? $value['image'] = 'images/'.$value['image'] : null; //Make sure that modules images are taken using absolute paths
					
					$linksString[$groupId] .= "
						<div $liClass>
							<a $id $href $onclick $target class=\"clearfix\" $title>
								<img align=\"middle\" $classstr src = '".$value['image']."' class='" . $value['image_class'] . "'  /><br />"
								
								 . $value['text'] . 
							"</a>
						</div>					
					";
					
					if ($counter % $params['columns'] == 0 || $counter >= count($params['links'])) {
						$linksString[$groupId] .= '</div>';
					}
				}
			}
		}
		$params['links'] = /*$params['content'] = */ implode("", $linksString);

 		$str = sprintf(
			'<div class="box grid_16 round_all %4$s" style="%5$s" id="%1$s">
				%2$s
				%3$s
				<a href="#" class="toggle">&nbsp;</a>
				<div class="toggle_container" style="%6$s" id="%1$s_content">
					%7$s
				</div>
			</div>',
			urlencode(clearStringSymbols($params['title'])),
			'<h2 class="box_head grad_colour">' . $params['title'] . '</h2>',
			'<a href="javascript: void(0);" class="grabber">&nbsp;</a>',
			(isset($params['class']) ? $params['class'] : '') . (isset($params['tabs']) ? " tabs" : ''),
			$params['style'] . ';',
			$showContent,
			$params['links']
		);
		
		return $str;
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
                        <span '.($value['selected'] ? 'class = "selected"': null).'>
                            <a href = "'.$value['link'].'"><img src = "images/'.$value['image'].'" alt = "'.$value['title'].'" title = "'.$value['title'].'"/></a>
                            <a href = "'.$value['link'].'" onclick = "'.$value['onclick'].'">'.$value['title'].'</a>
                        </span>';
     }
     $mainOptions = '<div class = "toolbar">'.$mainOptions.'</div>';
    }
    !isset($params['absoluteImagePath']) && $params['image'] ? $params['image'] = 'images/'.$params['image'] : null; //if absoluteImagePath is specified, it means that $params['image'] contains an absolute path (or anyway it refers to an image not under www/images/)
    isset($params['image']) && $params['image'] ? $image = '<img src = "'.$params['image'].'" alt = "'.strip_tags($params['title']).'" title = "'.strip_tags($params['title']).'" />' : $image = '';
    if ($GLOBALS['currentTheme'] -> options['images_displaying'] == 2 || ($GLOBALS['currentTheme'] -> options['images_displaying'] == 1 && basename($_SERVER['PHP_SELF']) == 'index.php')) {
     $image = '';
    }
    

 
 
	if ($params['tabs']) {
		$tabbedHeader .= '<ul class="tab_header grad_colour clearfix">';
		foreach($params['tabs'] as $tab) {
			$tabbedHeader .= sprintf(
				'<li><a href="#%1$s">%2$s</a></li>',
				urlencode(clearStringSymbols($tab['title'])),
				$tab['title']
			);
		}

		$tabbedHeader .= '</ul>';
	}
 
 
	if ($params['tabber']) {
		if($_GET['tab'] == $params['tabber']) {
			$tabberdefault = "tabbertabdefault";
		}
 		$str = sprintf(
			'<div id="%1$s" class="block %6$s tabber-item">
				%3$s
				%5$s
				<div class="">
					%2$s
					%4$s
				</div>
			</div>',
			urlencode(clearStringSymbols($params['tabber'])),
			(isset($params['sub_title']) ? '<div class="flat_area grid_16"><h2>' .  $params['sub_title'] . '</h2></div>' : ''),
			$params['headerlinks'],
			$params['content'],
			$params['links'],
			$params['class']
		);
	} elseif ($params['tabs']) {
		$str = sprintf(
			'<div class="box grid_16 round_all %4$s" style="%5$s" id="%1$s">
				%2$s
				%3$s
				<a href="#" class="toggle">&nbsp;</a>
				<div class="toggle_container">
					%8$s
					%10$s
					%6$s
					%9$s
				</div>
			</div>',
			urlencode(clearStringSymbols($params['title'])),
			isset($tabbedHeader) ? $tabbedHeader : '<h2 class="box_head grad_colour">' . $params['title'] . '</h2>',
			($nohandle) ? '' : '<a href="javascript: void(0);" class="grabber">&nbsp;</a>',
			(isset($params['class']) ? $params['class'] : '') . (isset($params['tabs']) ? " tabs" : ''),
			$params['style'] . ';',
			(isset($params['sub_title']) ? '<div class="flat_area grid_16"><h2>' .  $params['sub_title'] . '</h2></div>' : ''),
			$showContent,
			$params['headerlinks'],
			$params['content'],
			$params['links'],
			(isset($params['contentclass']) ? $params['contentclass'] : 'block')
		);
		
	} else { 
 		$str = sprintf(
			'<div class="box grid_16 round_all %4$s" style="%5$s" id="%1$s">
				%2$s
				%3$s
				<a href="#" class="toggle">&nbsp;</a>
				<div class="toggle_container" style="%7$s" id="%1$s_content">
					%8$s
					%10$s
					<div class="%11$s">
						%6$s
						%9$s
					</div>
				</div>
			</div>',
			urlencode(clearStringSymbols($params['title'])),
			isset($tabbedHeader) ? $tabbedHeader : '<h2 class="box_head grad_colour">' . $params['title'] . '</h2>',
			($nohandle) ? '' : '<a href="javascript: void(0);" class="grabber">&nbsp;</a>',
			(isset($params['class']) ? $params['class'] : '') . (isset($params['tabs']) ? " tabs" : ''),
			$params['style'] . ';',
			(isset($params['sub_title']) ? '<div class="flat_area grid_16"><h2>' .  $params['sub_title'] . '</h2></div>' : ''),
			$showContent,
			$params['headerlinks'],
			$params['content'],
			$params['links'],
			(isset($params['contentclass']) ? $params['contentclass'] : 'block')
		);

	}
 
	if (!$params['content'] && !$params['options'] && !$params['headerlinks'] && !$params['links']) {
		return '';
	} else {
        return $str;
 	}
}
