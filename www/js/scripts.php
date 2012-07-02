<?php
error_reporting(E_ERROR);
// see http://web.archive.org/web/20071211140719/http://www.w3.org/2005/MWI/BPWG/techs/CachingWithPhp
// $lastModifiedDate must be a GMT Unix Timestamp
// You can use gmmktime(...) to get such a timestamp
// getlastmod() also provides this kind of timestamp for the last
// modification date of the PHP file itself
function cacheHeaders($lastModifiedDate) {
    if ($lastModifiedDate) {
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModifiedDate) {
            if (php_sapi_name()=='CGI') {
                Header("Status: 304 Not Modified");
            } else {
                Header("HTTP/1.0 304 Not Modified");
            }
            exit;
        } else {
            $gmtDate = gmdate("D, d M Y H:i:s \G\M\T",$lastModifiedDate);
            header('Last-Modified: '.$gmtDate);
        }
    }
}

// This function uses a static variable to track the most recent
// last modification time
function lastModificationTime($time=0) {
    static $last_mod ;
    if (!isset($last_mod) || $time > $last_mod) {
        $last_mod = $time ;
    }
    return $last_mod ;
}

lastModificationTime(filemtime(__FILE__));
cacheHeaders(lastModificationTime());
header("Content-type: text/javascript; charset: UTF-8");

//error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

ob_start ("ob_gzhandler");

foreach (explode(",", $_GET['load']) as $value) {
    lastModificationTime(filemtime("$value.js"));
	echo file_get_contents($value . ".js");
    echo "\n";
}
?>
