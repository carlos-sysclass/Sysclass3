<?php
use 
	Phalcon\Config,
    Phalcon\Config\Adapter\Ini as ConfigIni;
// GET DATA FROM SYSTEM RELEASE
$di->setShared('sysconfig', function()  {
    $config = new ConfigIni(__DIR__ . "/../../RELEASE");
    //echo $config->project->full_version, "\n";
    return $config;
});

/**
 * @todo Merge environment and configuration DI (who gets from settings table)
 *  in ONE datasource and possibly ONE datasource
 */
// GET DATA FROM SYSTEM RELEASE ENVIRONMENT
$di->setShared('environment', function() use ($di) {
    $environment = $di->get("sysconfig")->deploy->environment;
    $configAdapter = new ConfigIni(__DIR__ . "/../config/{$environment}.ini");

	$plicoLibDir = realpath(PLICOLIB_PATH);

	$appRootDir = REAL_PATH . "/";

	$config = array(
		'default/theme'			=> 'default',
		'default/resource'		=> '/assets/%s/',
		'path/themes'			=> $appRootDir . "themes/",

		# NOT USED YET
		'client_name'			=> 'Sysclass',
		'app_name'				=> 'Sysclass',
	    'app_email'				=> 'maintainer@localhost',
	    'app_license'			=> 'Comercial',
	    //'app_license_url'		=> '',
		'http/secure'			=> isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on',
		'http_host'				=> $_SERVER['HTTP_HOST'],
		'db_dsn'				=> NULL,
		'path/base'				=> $plicoLibDir,
		'path/lib'				=> $plicoLibDir . 'lib/',
		'path/core-modules'		=> $plicoLibDir . 'modules/',
		'path/modules'			=> $appRootDir . 'modules/',
		'path/template'			=> '%s/templates/',
		'path/plugins'			=> '%s/plugins/',
		'path/cache'			=> $plicoLibDir . 'cache/',
		'path/app'				=> realpath($appRootDir) . "/",
		'path/app/www'			=> $appRootDir . 'www',
		'path/files'			=> $appRootDir . 'files',
		'path/files/public'		=> $appRootDir . 'files',
		'path/files/private'	=> $appRootDir . 'files-private',
		'module/base_path'		=> 'module',
		"timestamp/format"		=> "d/m/Y \à\s H:i",
		//'controller'			=> array(),
		'mail/send/isSMTP'		=> FALSE,
		// Enables SMTP debug information (for testing).
		'mail/send/debug'  		=> 0,
		// Enable SMTP authentication.
		'mail/send/do_auth' 	=> FALSE,
		// Sets the SMTP server.
		'mail/send/host'    	=> "mail.yourhost.com",
		// Set the SMTP port for the GMAIL server.
		'mail/send/port'		=> 25,
		// SMTP account username.
		'mail/send/user'   		=> "user@mail.yourhost.com",
		// SMTP account password.
		'mail/send/pass'   		=> "********",
		'mail/send/from/email'	=> 'user@mail.yourhost.com',
		'mail/send/from/name'	=> 'First Last',
		'resources/css'			=> array(),
		'resources/js'			=> array(),
		'resources/components'	=> array(),
		'default/homepage'		=> 'dashboard',
		'urls'					=> array()
	);

	$config['resources/css'] = array(
		//<!-- BEGIN GLOBAL MANDATORY STYLES -->
		'plugins/font-awesome/css/font-awesome',
		'plugins/font-awesome-more/css/font-awesome-ext',
		'plugins/font-awesome-more/css/font-awesome-corp',
		'plugins/bootstrap/css/bootstrap',
		//'plugins/uniform/css/uniform.default',
		'plugins/fa/css/font-awesome',

		//<!-- END GLOBAL MANDATORY STYLES -->

		//<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
		// TODO GET ALL THIS CSS AND PUT ON A COMPONENTS BASIS
		'plugins/bootstrap-toastr/toastr.min',
		'plugins/gritter/css/jquery.gritter',
		'plugins/bootstrap-daterangepicker/daterangepicker-bs3',
		'plugins/jqvmap/jqvmap/jqvmap',
		//'plugins/jquery-easy-pie-chart/jquery.easy-pie-chart',
		'plugins/bootstrap-fileupload/bootstrap-fileupload',
		//<!-- END PAGE LEVEL PLUGIN STYLES -->
		'css/pageguide/pageguide',
 		//<!-- BEGIN THEME STYLES -->
 		'css/flags',
		'css/style-sysclass',
		'css/style',
		'css/style-responsive',
		'css/plugins',
		'css/layout',
		'css/themes/blue',

		'css/custom',
		'css/videojs/video-js'
		//<!-- END THEME STYLES -->
	);

	$config['resources/js'] = array(
		'plugins/jquery-1.10.2.min',
		'plugins/jquery-migrate-1.2.1.min',
		'plugins/backbone/underscore',
		'plugins/backbone/backbone',
		'plugins/backbone/marionette',
		'plugins/backbone/backbone-deep-model',
		'plugins/backbone/backbone-nested-attributes',
		'plugins/jquery-ui/jquery-ui-1.10.3.custom.min',
		'plugins/bootstrap/js/bootstrap.min',
		'plugins/moment/moment.min',
		'plugins/numeral/numeral',
		'plugins/numeral/languages',

		//<!-- JGROWL notifications -->

		// TODO GET ALL THIS JS AND PUT ON A COMPONENTS BASIS
		'plugins/bootstrap-toastr/toastr.min',
		'plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min',
		'plugins/jquery-slimscroll/jquery.slimscroll',
		'plugins/jquery.blockui.min',
		'plugins/jquery.cookie.min',
		//'plugins/uniform/jquery.uniform.min',
		'plugins/jquery-validation/dist/jquery.validate',
		'plugins/backstretch/jquery.backstretch.min',

		'plugins/jquery.blockui.min',

		'plugins/strophe/strophe',
		'plugins/strophe/strophe.roster',
		'plugins/strophe/strophe.messaging',

		'plugins/strophe/strophe.chatstates',
		'plugins/strophe/strophe.ping',

		'plugins/pageguide/pageguide.min',

		'scripts/app',
		'scripts/sysclass',
		'scripts/models',
		'scripts/ui', // TODO MERGE ui AND views
		'scripts/views',
		'scripts/utils',
		'scripts/utils.toastr',
		'scripts/utils.strophe',
		'scripts/portlets'
	);


	//$config['http/secure'] = $config['https'] == 'https';
	$config['http/host'] = $config['http_host'];
	$config['http/fqdn'] = ($config['http/secure'] ? "https://" : "http://") . $config['http_host'];

	$config['bing/client_id'] = 'SysClass';
	$config['bing/client_secret'] = 'vhhU0DhoV0jPdNmuUItYjFOyHHwfMSKGcu54n5rctJM=';

    $configAdapter2 = new Config($config);

    $configAdapter->merge($configAdapter2);

    return $configAdapter;
});

$environment = $di->get("environment");

// GET DATA FROM SYSTEM RELEASE ENVIRONMENT DATABASE
$di->setShared("configuration", function() {
    return new Sysclass\Services\Utils\Settings();
});