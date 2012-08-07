<?php
//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? $protocol = 'https' : $protocol = 'http';
/** The protocol currently used*/

isset($_GET['theme']) ? $_SESSION['new-theme'] = $_GET['theme'] : '';

$configurationDefaults = array(
	'_default'			=> array(
		'server'	=> $protocol.'://'.$_SERVER["HTTP_HOST"].'/',
		'dbtype'	=> 'mysql',
		'dbhost'	=> 'localhost',
		'dbuser'	=> 'maguser',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'optional',
	),
/*
	'local.magester.net'		=> array(
		'dbname'	=> 'maguser_root' ,
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass')
		'overrideTheme'	=> 'sysclass3'
	),
	'magester.net'      => array(
    	'dbname'        => 'maguser_ult',
        'overrideTheme' => 'sysclass3'
	),
    'www.magester.net'      => array(
    	'dbname'        => 'maguser_ult',
        'overrideTheme' => 'sysclass3'
	),
	'ult.magester.net'	=> array(
		'dbname'	=> 'maguser_ult',
		'overrideTheme'	=> 'sysclass3'
	),	
	'www.ult.magester.net'	=> array(
		'dbname'	=> 'maguser_ult',
		'overrideTheme'	=> 'sysclass3'
	),
	*/
	'new.magester.net'	=> array(
		'dbname'		=> 'maguser_ult',
		'overrideTheme'	=> 'sysclass'
	),
	'www.new.magester.net'	=> array(
		'dbname'	=> 'maguser_ult',
		'overrideTheme'	=> 'sysclass'
	),
		/*
	'posult.magester.net'	=> array(
		'dbname'		=> 'maguser_ult',
		'overrideTheme'	=> 'sysclass3'
	),
	'dev.magester.net'	=> array(
		'dbname'	=> 'maguser_dev',
		'https'		=> 'optional', // 'none', 'optional', 'required'
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new')
	),
	*/
	'local.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass_root',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'none', // 'none', 'optional', 'required'
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3')
	),
	'sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass_root',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'optional', // 'none', 'optional', 'required'
		'overrideTheme' => 'sysclass3'
	),
	'www.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass_root',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'required', // 'none', 'optional', 'required'
		'overrideTheme' => 'sysclass3'
	),
	'fati.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_fati',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	/*
	'dev.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_dev',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'www.dev.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_dev',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'pelissari.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_pelissari',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> 'sysclass3',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'www.pelissari.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_pelissari',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> 'sysclass-new',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'fajar.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_fajar',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'www.fajar.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_fajar',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),	
	'fati.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_fati',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'www.fati.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'		=> 'sysclass_fati',
		//'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass-new'),
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3'),
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),		
	'idiompro.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass_idpro',
		'dbpass'	=> '159487@@',
		'dbname'		=> 'sysclass_idiompro',
		'overrideTheme'	=> 'sysclass-new',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'www.idiompro.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass_idpro',
		'dbpass'	=> '159487@@',
		'dbname'		=> 'sysclass_idiompro',
		'overrideTheme'	=> 'sysclass-new',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	
	
	
	'sandbox.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass_sandbox',
		'dbpass'	=> 'aq1sw2de3@@',
		'dbname'		=> 'sysclass_sandbox',
		'overrideTheme'	=> 'sysclass-new',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'www.sandbox.sysclass.com'	=> array(
		'dbuser'	=> 'sysclass_sandbox',
		'dbpass'	=> 'aq1sw2de3@@',
		'dbname'		=> 'sysclass_sandbox',
		'overrideTheme'	=> 'sysclass-new',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	'demo.ult.com.br'	=> array(
		'dbuser'	=> 'ultbr_sysclass',
		'dbpass'	=> 'fep7_58A#@',
		'dbname'		=> 'ultbr_sysclass',
		'overrideTheme'	=> 'sysclass3',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0'
	),
	*/
);

$configuration = array_merge($configurationDefaults['_default'], $configurationDefaults[$_SERVER["SERVER_NAME"]]);

if (array_key_exists('overrideTheme', $configuration)) {
	$overrideTheme = $configuration['overrideTheme'];
} else {
	$overrideTheme = null;
}


/** The database Host */
define('G_DBTYPE', $configuration['dbtype']);
/** The database Host */
define('G_DBHOST', $configuration['dbhost']);
/** The database user*/
define('G_DBUSER', $configuration['dbuser']);
/** The database user password*/
define('G_DBPASSWD', $configuration['dbpass']);
/** The database name*/
define('G_DBNAME', $configuration['dbname']);
/** The database tables prefix*/
define('G_DBPREFIX', $configuration['dbprefix']);

/* Access Protocol (http | https) */
if ($configuration['https'] == 'required' && $protocol != 'https' && $DO_NOT_REDIRECT !== true) {
	//eF_redirect($url)
	$url = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	header("Location: {$url}");
	exit;
} elseif ($configuration['https'] == 'none' && $protocol != 'http' && $DO_NOT_REDIRECT !== true) {
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	header("Location: {$url}");
	exit;
} else {
	define ("G_PROTOCOL", $protocol);
	/** The server name*/
	define('G_SERVERNAME', $configuration['server']);
	
	
	//var_dump($configuration);
	
	/**Software root path*/
	define("G_ROOTPATH", $configuration['root_path']);
	
	/**Current version*/
	define('G_VERSION_NUM', $configuration['version']);
	
	/**Include function files*/
	require_once('globals.php');
}
?>
