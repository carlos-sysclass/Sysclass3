<?php
/**

* Replaces occurences of the form #filter:user_login-asdfas# with a personal message link

*/
function smarty_outputfilter_sC_template_encryptQuery($compiled, &$smarty)
{
    $re = "/(href\s*=\s*['\"][^>]*\?)(.*)(['\"])/U";
    //preg_match_all($re, $compiled, $matches);		//This does nothing, but is left here commented-out in case we want to quickly check which urls are matched
    //pr($matches);
    $compiled = preg_replace_callback($re, "local_encryptQueryReplace", $compiled);

    return $compiled;
}
function local_encryptQueryReplace($matches)
{
    //pr(($matches));
    $parsedUrl = parse_url($matches[0]);
    //Convert only internal links
    if (stristr($parsedUrl['host'], 'http') === false || stristr('http://'.$parsedUrl['path'], G_SERVERNAME) !== false || stristr('https://'.$parsedUrl['path'], G_SERVERNAME) !== false) {
  $matches[2] = 'cru='.encryptString($matches[2]);
    }
    //pr($matches[1].$matches[2].$matches[3]);
    return $matches[1].$matches[2].$matches[3];
}
