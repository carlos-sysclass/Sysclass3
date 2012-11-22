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

$_POST['dir'] = urldecode($_POST['dir']);

$root = dirname(__FILE__) . '/';

$config = array(
	'showheader'	=> true,
	'showfolders' 	=> false,
	'showfiles'		=> true,
);

if (file_exists($root . $_POST['dir'])) {
	$files = scandir($root . $_POST['dir']);

	natcasesort($files);
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		//echo "<ul class=\"jqueryFileTree\">";
		if ($config['showheader']) {
			echo 
				"<li class=\"file_header ext_header\">
					<a href=\"#\">" . 
						"<div class=\"filepart filename\">" . _MODULE_PAGAMENTO_FILE_NAME . "</div>" .
						"<div class=\"filepart filetime\">" . _FILE_TIME . "</div>" . 
						"<div class=\"filepart filesize\">" . _FILE_SIZE . "</div>" .
					"</a>
				</li>";
		}
		// All dirs
		if ($config['showfolders']) {
			foreach( $files as $file ) {
				if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && $file != '.svn' && is_dir($root . $_POST['dir'] . $file) ) {
					echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
				}
			}
		}
		// All files
		if ($config['showfiles']) {
			foreach( $files as $file ) {
				if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) ) {
					$ext = preg_replace('/^.*\./', '', $file);
	
					$file_stat = stat($root . $_POST['dir'] . $file);
					
					echo 
						"<li class=\"file ext_$ext\">
							<a href=\"#\" rel=\"" . htmlentities($root . $_POST['dir'] . $file) . "\">" . 
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
