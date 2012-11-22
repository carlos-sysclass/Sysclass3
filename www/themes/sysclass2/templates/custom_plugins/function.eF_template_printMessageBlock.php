<?php
/**
* prints a block
*
*/
function smarty_function_eF_template_printMessageBlock($params, &$smarty)
{
    !isset($params['type']) || !$params['type'] ? $params['type'] = 'failure' : null;
    if ($params['type'] == 'success') {
        $messageImage 	= '<img src = "images/32x32/success.png" alt = "'._SUCCESS.'" title = "'._SUCCESS.'">';
        $stringType		= 'sucesso';
    } elseif ($params['type'] == 'warning') {
        $messageImage = '<img src = "images/32x32/warning.png" alt = "'._FAILURE.'" title = "'._FAILURE.'">';
        $stringType		= 'Atenção';
    } elseif ($params['type'] == 'information') {
        $messageImage = '<img src = "images/32x32/warning.png" alt = "'._FAILURE.'" title = "'._FAILURE.'">';
        $stringType		= 'Informação';
	} elseif ($params['type'] == 'failure') {
        $messageImage = '<img src = "images/32x32/warning.png" alt = "'._FAILURE.'" title = "'._FAILURE.'">';
        $stringType		= 'Erro';
    } else {
    	$params['type'] = "Other";
    	
        $messageImage = '<img src = "images/32x32/warning.png" alt = "'._FAILURE.'" title = "'._FAILURE.'">';
        $stringType		= 'Aviso';
    }

	if (mb_strlen($params['content']) > 1000) {
        $prefix = mb_substr($params['content'], 0, 1000);
        $suffix = mb_substr($params['content'], mb_strlen($params['content']) - 300, mb_strlen($params['content']));
        $infix  = mb_substr($params['content'], 1001, mb_strlen($params['content']) - mb_strlen($prefix) - mb_strlen($suffix));
    	$params['content'] = $prefix.'<a href = "javascript:void(0)" onclick = "this.style.display = \'none\';Element.extend(this).next().show()"><br>[...]<br></a><span style = "display:none">'.$infix.'</span>'.$suffix;
    }
    
    if (!empty($params['class'])) {
    	$addClass = $params['class'];
    } else {
    	$addClass = 'grid_24';
    }
    
    $str .= '
        <div class = "' . $addClass . ' message message' . ucfirst($params['type']) . '" id = "messageBlock">
        	<span class="messageInner">
        	' . $messageImage . ' <strong>' . $stringType . '</strong>
        	</span> 
        	<span>' . $params['content'] . '</span>
        </div>
        <div class="clear"></div>
	';
    
    return $str;
}
