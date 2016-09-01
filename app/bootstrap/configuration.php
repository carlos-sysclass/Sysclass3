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

	$plicoLibDir = realpath(PLICOLIB_PATH) . "/";

	$appRootDir = REAL_PATH . "/";

	$config = array(
		'default/theme'			=> 'default',
		'default/resource'		=> '/assets/%s/',
		'path/app'				=> realpath($appRootDir) . "/",
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
		'path/app/www'			=> $appRootDir . 'www',
		'path/files/public'		=> $appRootDir . 'files',
		'path/files'			=> $appRootDir . 'files',
		'path/files/private'	=> $appRootDir . 'files-private',
		'module/base_path'		=> '/module',
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
		'plugins/fa/css/font-awesome',
		'plugins/simple-line-icons/simple-line-icons',
		'plugins/themify-icons/themify-icons',
		'css/colors',
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

		'css/custom'
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
		'plugins/jquery-ui/jquery-ui-1.10.3.custom',
		'plugins/bootstrap/js/bootstrap.min',
		'plugins/moment/moment.min',
		'plugins/moment/locale/%locale$s',
		'plugins/numeral/numeral',
		'plugins/numeral/languages',
		//'plugins/jquery.pulsate.min',
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
		/*
		'plugins/strophe/strophe',
		'plugins/strophe/strophe.roster',
		'plugins/strophe/strophe.messaging',

		'plugins/strophe/strophe.chatstates',
		'plugins/strophe/strophe.ping',
		*/
		//'plugins/pageguide/pageguide.min',

		'scripts/app',
		'scripts/sysclass',
		'scripts/models',
		'scripts/fields',
		'scripts/ui', // TODO MERGE ui AND views
		'scripts/views',
		'scripts/utils',
		'scripts/utils.toastr',
		//'scripts/utils.strophe',
		'scripts/portlets'
	);
	$config['urls'] = array(
		'default'	=> '/dashboard',
		'home'		=> '/dashboard'
	);

	//$config['http/secure'] = $config['https'] == 'https';
	$config['http/host'] = $config['http_host'];
	$config['http/fqdn'] = ($config['http/secure'] ? "https://" : "http://") . $config['http_host'];

	$config['bing/client_id'] = 'SysClass';
	$config['bing/client_secret'] = 'vhhU0DhoV0jPdNmuUItYjFOyHHwfMSKGcu54n5rctJM=';

	$config['resources/components'] = array(
		'videojs' => array(
			'name'	=> 'videojs',
			'css'	=> array('css/videojs/video-js'),
			'js'	=> array("plugins/videojs/video")
		),
		'select2' => array(
			'name'	=> 'select2',
			'css'	=> array('plugins/select2/select2_metro'),
			'js'	=> array('plugins/sprintf/sprintf.min', 'plugins/select2/select2')
		),
		'data-tables' => array(
			'name'	=> 'data-tables',
			'css'	=> array('plugins/data-tables/DT_bootstrap'),
			'js'	=> array('plugins/bootstrap-confirmation/bootstrap-confirmation', 'plugins/data-tables/jquery.dataTables', 'plugins/data-tables/DT_bootstrap', 'scripts/utils.datatables')
		),
		'datatables' => array(
			'name'	=> 'datatables',
			'css'	=> array('plugins/datatables/datatables'),
			'js'	=> array('plugins/datatables/datatables', 'plugins/data-tables/DT_bootstrap', 'scripts/utils.datatables')
		),

		'bootstrap-switch' => array(
			'name'	=> 'bootstrap-switch',
			'css'	=> array('plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch'),
			'js'	=> array('plugins/bootstrap-switch/js/bootstrap-switch')
		),
		'datepicker' => array(
			'name'	=> 'datepicker',
			'css'	=> array('plugins/bootstrap-datepicker/css/datepicker'),
			'js'	=> array('plugins/bootstrap-datepicker/js/bootstrap-datepicker', 'plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.%locale$s')
		),
		'pwstrength' => array(
			'name'	=> 'pwstrength',
			'js'	=> array('plugins/jquery.pwstrength.bootstrap/src/pwstrength')
		),
		'wysihtml5' => array(
			'name'	=> 'wysihtml5',
			'css'	=> array('plugins/bootstrap-wysihtml5/bootstrap-wysihtml5', 'plugins/bootstrap-wysihtml5/wysiwyg-color'),
			'js'	=> array('plugins/bootstrap-wysihtml5/wysihtml5-0.3.0', 'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5')
		),
		"validation" => array(
			'name'	=> 'validation',
			'js'	=> array('plugins/jquery-validation/dist/jquery.validate', 'plugins/jquery-validation/dist/additional-methods.min')
		),
		"jquery-mask" => array(
			'name'	=> 'jquery-mask',
			'js'	=> array('plugins/jquery-mask/jquery.mask')
		),
		"phone-field" => array(
			'name'	=> 'phone-field',
			'js'	=> array('scripts/ui.field.phone'),
			'deps' 	=> array("validation", "jquery-mask") // DOES NOT WORKING YET!
		),
		"date-field" => array(
			'name'	=> 'date-field',
			'js'	=> array('scripts/ui.field.phone'),
			'deps' 	=> array("validation", "jquery-mask") // DOES NOT WORKING YET!
		),
		'easy-pie-chart' => array(
			'name'	=> 'easy-pie-chart',
			'css'	=> array('plugins/jquery-easy-pie-chart/jquery.easy-pie-chart'),
			'js'	=> array('plugins/jquery-easy-pie-chart/jquery.easy-pie-chart')
		),
		'icheck' => array(
			'name'	=> 'icheck',
			'css'	=> array('plugins/icheck/skins/square/_all'),
			'js'	=> array('plugins/icheck/icheck.min')
		),
		"jquery-file-upload-image" => array(
			'name'	=> 'jquery-file-upload-image',
			'css'	=> array(
				'plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min',
				'plugins/jquery-file-upload/css/jquery.fileupload',
				'plugins/jquery-file-upload/css/jquery.fileupload-ui'
			),
			'js'	=> array(
				'plugins/jquery-file-upload/js/vendor/jquery.ui.widget',
				//'plugins/jquery-file-upload/js/vendor/tmpl.min',
				'plugins/jquery-file-upload/js/vendor/load-image.min',
				'plugins/jquery-file-upload/js/vendor/canvas-to-blob.min',
				'plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min',
				'plugins/jquery-file-upload/js/jquery.iframe-transport',
				'plugins/jquery-file-upload/js/jquery.fileupload',
				'plugins/jquery-file-upload/js/jquery.fileupload-process',
				'plugins/jquery-file-upload/js/jquery.fileupload-image',
				//'plugins/jquery-file-upload/js/jquery.fileupload-audio',
				//'plugins/jquery-file-upload/js/jquery.fileupload-video',
				//'plugins/jquery-file-upload/js/jquery.fileupload-validate',
				'plugins/jquery-file-upload/js/jquery.fileupload-ui'
			)
		),
		'jquery-jcrop' => array(
			'name'	=> 'jquery-jcrop',
			'css'	=> array('plugins/jcrop/css/jquery.Jcrop'),
			'js'	=> array('plugins/jcrop/js/jquery.color', 'plugins/jcrop/js/jquery.Jcrop')
		),
		'bootstrap-confirmation' => array(
			'name'	=> 'bootstrap-confirmation',
			'js'	=> array(
				'plugins/bootstrap-confirmation/bootstrap-confirmation'
			)
		),
		'fullcalendar' => array(
			'name'	=> 'fullcalendar',
			'css'	=> array('plugins/fullcalendar/fullcalendar/fullcalendar'),
			'js'	=> array('plugins/fullcalendar/fullcalendar/fullcalendar', 'plugins/fullcalendar/fullcalendar/lang-all')
		),
		'bootstrap-editable' => array(
			'name'	=> 'bootstrap-editable',
			'css'	=> array('plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable'),
			'js'	=> array('plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable')
		),
		'autobahn' => array(
			'name' => 'autobahn',
			'js' => array('plugins/autobahn/autobahn1')
		),
		'websocket' => array(
			'name' => 'websocket',
			'js' => array('plugins/jquery-websocket/jquery.websocket')
		),
		'noui-slider' => array(
			'name' => 'noui-slider',
			'css'	=> array('plugins/nouislider/nouislider'),
			'js'	=> array('plugins/nouislider/nouislider')

		)
	);

	// MOVE TO module config.yml!!!
	// 
	/*
	$config['models/map'] = array(
		'areas'	=> array(
			'class' => "Sysclass\Models\Courses\Departament",
            'exportMethod'  => array(
                'toFullArray',
                array('')
            )
        ),
		'calendar'	=> array(
			'class' => 'Sysclass\Models\Calendar\Event',
            'exportMethod'  => array(
                'toFullArray',
                array()
            ),
		)
	);
	*/
    $configAdapter2 = new Config($config);

    $configAdapter->merge($configAdapter2);

    return $configAdapter;
});

$environment = $di->get("environment");

// GET DATA FROM SYSTEM RELEASE ENVIRONMENT DATABASE
$di->setShared("configuration", function() {
    return new Sysclass\Services\Utils\Settings();
});
