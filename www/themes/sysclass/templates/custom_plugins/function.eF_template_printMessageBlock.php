<?php
/**
* prints a block
*
*/
function smarty_function_sC_template_printMessageBlock($params, &$smarty)
{
    !isset($params['type']) || !$params['type'] ? $params['type'] = 'failure' : null;
    if ($params['type'] == 'success') {
        $messageImage = '<img width="24" height="24" src = "/themes/sysclass/images/icons/small/white/alert_2.png" alt = "'._SUCCESS.'" title = "'._SUCCESS.'">';
        $message_class = "alert_green";
    } else {
        $messageImage = '<img width="24" height="24" src = "/themes/sysclass/images/icons/small/white/alarm_bell.png" alt = "'._FAILURE.'" title = "'._FAILURE.'">';
        $message_class = "alert_red";
    }

	if (mb_strlen($params['content']) > 1000) {
        $prefix = mb_substr($params['content'], 0, 1000);
        $suffix = mb_substr($params['content'], mb_strlen($params['content']) - 300, mb_strlen($params['content']));
        $infix  = mb_substr($params['content'], 1001, mb_strlen($params['content']) - mb_strlen($prefix) - mb_strlen($suffix));
    	$params['content'] = $prefix.'<a href = "javascript:void(0)" onclick = "this.style.display = \'none\';Element.extend(this).next().show()"><br>[...]<br></a><span style = "display:none">'.$infix.'</span>'.$suffix;
    }
    /*
    $str .= '
        <div class = "block" id = "messageBlock">
        <div class = "blockContents messageContents">
        	<table class = "messageBlock">
            	<tr><td>'.$messageImage.'</td>
            		<td class = "'.$params['type'].'Block">'.$params['content'].'</td>
            		<td><img src = "images/32x32/close.png" alt = "'._CLOSE.'" title = "'._CLOSE.'" onclick = "window.Effect ? new Effect.Fade($(\'messageBlock\')) : document.getElementById(\'messageBlock\').style.display = \'none\';"></td></tr>
            </table>
        </div>
        </div>';
    */

    $str = sprintf(
	    '<div class="messageBlock alert %s">
			%s
			%s
		</div>',
	    $message_class,
	    $messageImage,
	    $params['content']
    );

    return $str;
}
