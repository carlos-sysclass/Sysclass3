<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//
session_cache_limiter('none');
session_start();

//Uncomment this to get a full list of errors
/*
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", true);
define("NO_OUTPUT_BUFFERING", true);
*/
$path = "../../../libraries/";

require_once $path."configuration.php";

$modulesDB = eF_getTableData("modules", "*", "className = 'module_language' AND active=1");
foreach ($modulesDB as $module) {
	$folder = $module['position'];
	$className = $module['className'];
	     	
	require_once G_MODULESPATH.$folder."/".$className.".class.php";
	if (class_exists($className)) {
		$modulesLanguage = new $className("", $folder);
	} else {
		$modulesLanguage = false;
	}
	break;
}
if (!$modulesLanguage) {
	exit;
}
$modulesLanguage->getLanguageFile();



$_POST['dir'] = urldecode($_POST['dir']);

$folderProcess = array('unknown');

if ($_SESSION['s_login']) {
	$userLogged = MagesterUserFactory::factory($_SESSION['s_login']);
	
 	$iesData = eF_getTableDataFlat("module_xies_to_users", "ies_id", "user_id = " . $userLogged->user['id']);
 	
 	if (count($iesData) > 0) {
 		$paymentTypes = eF_getTableDataFlat("module_xpayment_types_to_xies", "payment_type_id", "ies_id IN(" . implode(', ', $iesData['ies_id']) . ')');
 		$folderProcess = array_merge($folderProcess, $paymentTypes['payment_type_id']);
 		
 	}
}

$folderProcess = array_unique($folderProcess);

//eF_getTableData($table)


$root = dirname(__FILE__) . '/';

$config = array(
	'showheader'	=> true,
	'showfolders' 	=> false,
	'showfiles'		=> true,
);

$mapFolderNames = array(
	'1'			=> 'ULT',
	'2'			=> 'FAJAR/FATI',
	'unknown'	=> 'N/A'
);

if (file_exists($root . $_POST['dir'])) {
	foreach ($folderProcess as $middleFolder) {
		
		//echo $root . $_POST['dir'] . $middleFolder . '/';
		
		
		$currentDir = $_POST['dir'] . $middleFolder . '/';
		$absCurrentDir = $root . $currentDir;
		
		$files = scandir($currentDir, 1);
		
		/* The 2 accounts for . and .. */
		if (count($files) > 2) {
			
			echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
			//echo "<ul class=\"jqueryFileTree\">";
			if ($config['showheader']) {
				echo
					"<li class=\"file_header ext_header\">
						<a href=\"#\">" .
							"<div class=\"filepart fileprefix\">&nbsp;" . __XPAYMENT_FILE_PREFIX . "</div>" .
							"<div class=\"filepart filename\">" . __XPAYMENT_FILE_NAME . "</div>" .
							"<div class=\"filepart filetime\">" . __XPAYMENT_FILE_TIME . "</div>" .
							"<div class=\"filepart filesize\">" . __XPAYMENT_FILE_SIZE . "</div>" .
						"</a>
					</li>";
				$config['showheader'] = false;
			}
			// All dirs
			if ($config['showfolders']) {
				foreach ($files as $file) {
					if (file_exists($absCurrentDir . $file) && $file != '.' && $file != '..' && $file != '.svn' && is_dir($absCurrentDir . $file)) {
						echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($currentDir . $file) . "/\">" . htmlentities($file) . "</a></li>";
					}
				}
			}
			// All files
			if ($config['showfiles']) {
				foreach ($files as $file) {
					if (file_exists($absCurrentDir . $file) && $file != '.' && $file != '..' && !is_dir($absCurrentDir . $file) ) {
						$ext = preg_replace('/^.*\./', '', $file);
		
						$file_stat = stat($absCurrentDir . $file);
						
						echo
							"<li class=\"file ext_$ext\">
								<a href=\"#\" rel=\"" . htmlentities($absCurrentDir . $file) . "\">" .
									"<div class=\"filepart fileprefix\">" . $mapFolderNames[$middleFolder] . "</div>" .
									"<div class=\"filepart filename\">" . htmlentities($file) . "</div>" .
									"<div class=\"filepart filetime\">" . date('d/m/Y H:i', ($file_stat['mtime'])) . "</div>" .
									"<div class=\"filepart filesize\">" . sprintf("%.2fKb", ($file_stat['size'] / 1024)) . "</div>" .
								"</a>
							</li>";
					}
				}
			}
			echo "</ul>";
		}
	}
}
