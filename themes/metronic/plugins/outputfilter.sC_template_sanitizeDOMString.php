<?php
/**

* Replaces occurences of the form #filter:timestamp-1132843907# with the current date

*/
function smarty_outputfilter_sC_template_sanitizeDOMString($compiled, &$smarty)
{
    $compiled = preg_replace("/#filter:sanitizeDOMString-(.*)#/e", "str_replace(\".\", \"_\", \"\$1\")", $compiled);

    return $compiled;
}
