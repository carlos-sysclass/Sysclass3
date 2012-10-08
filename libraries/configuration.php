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
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'none',
	),
	'new.magester.net'	=> array(
		'dbname'	=> 'sysclass_root',
		'overrideTheme'	=> 'sysclass'
	),
	'www.new.magester.net'	=> array(
		'dbname'	=> 'sysclass_root',
		'overrideTheme'	=> 'sysclass'
	),

	'local.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_root',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3')
	),
	'sysclass.local'        => array(
	    'dbname'        => 'sysclass_root',
	    'overrideTheme' => (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass3')
	),
	'sysclass.com'	=> array(
		'dbname'        => 'sysclass_root',
		'overrideTheme' => 'sysclass3',
		'https'         => 'required'
	),
	'www.sysclass.com'	=> array(
                'dbname'        => 'sysclass_root',
                'overrideTheme' => 'sysclass3',
		'https'         => 'required'
	),
        '173.193.157.162'      => array(
                'dbname'        => 'sysclass_root',
                'overrideTheme' => 'sysclass3',
                'https'         => 'none'
        ),
	'fati.sysclass.com'	=> array(
		'dbname'        => 'sysclass_fati',
                'overrideTheme' => 'sysclass3'
	),
	'www.fati.sysclass.com'	=> array(
		'dbname'        => 'sysclass_fati',
                'overrideTheme' => 'sysclass3'
	),
	'demo.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_demo',
		'overrideTheme' => 'sysclass3'
	),
	'www.demo.sysclass.com'     => array(
		'dbname'        => 'sysclass_demo',
		'overrideTheme' => 'sysclass3'
	),
	'fajar.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_fajar',
		'overrideTheme' => 'sysclass3'
	),
	'comptia.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_comptia',
		'overrideTheme' => 'sysclass3'
	),
	'spbc.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_spbc',
		'overrideTheme' => 'sysclass3'
	),
	'www.spbc.sysclass.com'     => array(
		'dbname'        => 'sysclass_spbc',
		'overrideTheme' => 'sysclass3'
	),
	'idiompro.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_idiompro',
		'overrideTheme' => 'sysclass3'
	),
	'www.idiompro.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_idiompro',
		'overrideTheme' => 'sysclass3'
	),
		'demo1.sysclass.com'	=> array(
				'dbname'	=> 'sysclass_demo1',
				'overrideTheme' => 'sysclass3'
		),
		'www.demo1.sysclass.com'     => array(
				'dbname'        => 'sysclass_demo1',
				'overrideTheme' => 'sysclass3'
		),
		'demo2.sysclass.com'	=> array(
				'dbname'	=> 'sysclass_demo2',
				'overrideTheme' => 'sysclass3'
		),
		'www.demo2.sysclass.com'     => array(
				'dbname'        => 'sysclass_demo2',
				'overrideTheme' => 'sysclass3'
		),
		'demo3.sysclass.com'	=> array(
				'dbname'	=> 'sysclass_demo3',
				'overrideTheme' => 'sysclass3'
		),
		'www.demo3.sysclass.com'     => array(
				'dbname'        => 'sysclass_demo3',
				'overrideTheme' => 'sysclass3'
		),
		'demo4.sysclass.com'	=> array(
				'dbname'	=> 'sysclass_demo4',
				'overrideTheme' => 'sysclass3'
		),
		'www.demo4.sysclass.com'     => array(
				'dbname'        => 'sysclass_demo4',
				'overrideTheme' => 'sysclass3'
		),
		
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
	require_once 'globals.php';
}
