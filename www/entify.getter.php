<?php
/**
 *  This module implements the external entify loader procedure.
 *  Author: Andre Kucaniz
 *  Contact: andre@ult.com.br
 *  SysClass ver: 
 * 
*/
$path = "../libraries/";                //Define default path

error_reporting( E_ALL & ~E_NOTICE );ini_set("display_errors", true);define("NO_OUTPUT_BUFFERING", true);        //Uncomment this to get a full list of errors

/** The configuration file.*/
require_once $path."configuration.php";
if(
	empty($_GET['type']) || 
	empty($_GET['ID'])
) {
	exit;
} else {
	$type		= $_GET['type'];
	$ID			= $_GET['ID'];
	switch($type) {
		case "courses" : {
			if ($_GET['subtype'] == "terms") {
				$resultData = eF_getTableData("courses","terms","id=".$ID);
				echo $resultData[0]['terms'];
			}
			break;
		}
		default : {
		}
	}
}
exit;
