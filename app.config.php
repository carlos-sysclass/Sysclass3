<?php
require('vendor/autoload.php');

//define("DEBUG", 1);
$plicoLib = PlicoLib::instance();

if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
	$HTTP_HOST = $_SERVER['HTTP_X_FORWARDED_HOST'];
	$disable_http_check = true;
} else {
	$HTTP_HOST = $_SERVER['HTTP_HOST'];
	$disable_http_check = false;
}

isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? $protocol = 'https' : $protocol = 'http';
/*
isset($_GET['theme']) ? $_SESSION['new-theme'] = $_GET['theme'] : '';

$themes = array('sysclass.default', 'sysclass.itaipu');

if (!in_array($_SESSION['new-theme'], $themes)) {
	unset($_SESSION['new-theme']);
} else {

}
*/
//$_SESSION['new-theme'] = 'sysclass.itaipu';

$configurationDefaults = array(
	'_default'			=> array(
		'server'	=> $protocol.'://'.$HTTP_HOST.'/',
		'dbtype'	=> 'mysql',
		'dbhost'	=> 'localhost',
		'dbuser'	=> 'sysclass',
		'dbpass'	=> 'WXubN7Ih',
		'dbname'	=> 'sysclass_demo',
		'dbprefix'	=> '',
		'root_path'	=> str_replace("\\", "/", dirname(dirname(__FILE__)))."/",
		'version'	=> '3.0.0',
		'https'		=> 'none',
		'theme'		=> (@isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass.default')
	),
	/*
	'local.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_demo',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass.default')
	),
	'local.beta.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_demo',
		'overrideTheme'	=> (isset($_SESSION['new-theme']) ? $_SESSION['new-theme'] : 'sysclass.default')
	),
	*/
	'develop.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_develop',
		'theme'		=> 'sysclass.default',
		'https'		=> 'required',
	),
	'enterprise.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_enterprise',
		'theme'		=> 'sysclass.default',
		'https'		=> 'required',
	),
	'www.enterprise.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_enterprise',
		'theme'		=> 'sysclass.default',
		'https'		=> 'required',
	),
	'itaipu.sysclass.com'	=> array(
		'dbname'	=> 'sysclass_itaipu',
		'theme'		=> 'sysclass.itaipu',
		'https'		=> 'required'
	)
);

if (array_key_exists($HTTP_HOST, $configurationDefaults)) {
	$configuration = array_merge($configurationDefaults['_default'], $configurationDefaults[$HTTP_HOST]);
} else {
	$configuration = $configurationDefaults['_default'];
}

//var_dump($configuration);
//exit;

$configuration['dsn'] = sprintf(
	'%s://%s:%s@%s/%s?persist',
	$configuration['dbtype'],
	$configuration['dbuser'],
	$configuration['dbpass'],
	$configuration['dbhost'],
	$configuration['dbname']
);

$plicoLib->set('theme', $configuration['theme']);
$plicoLib->set('client_name', 'Sysclass');
$plicoLib->set('app_name', 'Sysclass');
$plicoLib->set('db_dsn', $configuration['dsn']);
$plicoLib->set('db/charset', 'utf8');


$GLOBALS['db'] = DatabaseManager::db();

$plicoLib->set('default/resource', '/assets/%s/');
$plicoLib->add('path/themes', __DIR__ . '/themes/');
$plicoLib->add('path/modules', __DIR__ . '/modules/');

$plicoLib->set('bing/client_id', 'SysClass');
$plicoLib->set('bing/client_secret', 'vhhU0DhoV0jPdNmuUItYjFOyHHwfMSKGcu54n5rctJM=');

/*
$plicoLib->set('mail/send/isSMTP', FALSE);
$plicoLib->set('mail/send/debug', FALSE);
$plicoLib->set('mail/send/do_auth', FALSE);
$plicoLib->set('mail/send/host', "localhost");
$plicoLib->set('mail/send/port', 587);
$plicoLib->set('mail/send/user', "website@ssra.com.br");
$plicoLib->set('mail/send/pass', "JDEp3BMp98");
$plicoLib->set('mail/send/from/email', "website@ssra.com.br");
$plicoLib->set('mail/send/from/name', 'Web site ssra');
*/
// SETTING CONTROLLERS
$plicoLib->concat(
	'controller',
	array(
//		'FrontendController',
		'LoginController',
		//'HomeController',
		'DashboardController',
		'AgreementController'
		//'CrudController'
		//'AdministratorController',
		//'StudentController'
/*
		'TwitterController',
		'AboutUsController',
		'LicenciadosController',
		'SurveyController',
		'ContactController',
		'WebTrabalheConoscoController',
		array("DashboardController", "painel"),
		array('ProfileController', 'painel'),
		array("ClientesController", "painel"),
		array("PessoalController", "painel"),
		array("FranquiasController", "painel"),
		array("FornecedoresController", "painel"),
		array("ContatosController", "painel"),
		array("TrabalheConoscoController", "painel")
*/
	)
);

$plicoLib->set("urls",
	array(
		'default'	=> '/dashboard',
		'home'		=> '/dashboard'
	)
);

/*
// WEB THEME SPECIFIC
$plicoLib->add(
	'resources/web/css',
	'main'
);
*/
/*
$plicoLib->add("resources/components", array(
	'name'	=> 'icheck',
	'css'	=> array('css/plugins/icheck/all'),
	'js'	=> array('js/plugins/icheck/jquery.icheck.min')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'validate',
	'js'	=> array('js/plugins/validation/jquery.validate.min', 'js/plugins/validation/additional-methods.min')
));
*/
$plicoLib->add("resources/components", array(
	'name'	=> 'modal',
	'css'	=> array('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch', 'plugins/bootstrap-modal/css/bootstrap-modal'),
	'js'	=> array('plugins/bootstrap-modal/js/bootstrap-modalmanager', 'plugins/bootstrap-modal/js/bootstrap-modal')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'bootbox',
	'js'	=> array('plugins/bootbox/bootbox.min')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'icheck',
	'css'	=> array('plugins/icheck/skins/square/_all'),
	'js'	=> array('plugins/icheck/icheck.min')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'validation',
	'js'	=> array('plugins/jquery-validation/dist/jquery.validate', 'plugins/jquery-validation/dist/additional-methods.min')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'data-tables',
	'css'	=> array('plugins/data-tables/DT_bootstrap'),
	'js'	=> array('plugins/bootstrap-confirmation/bootstrap-confirmation', 'plugins/data-tables/jquery.dataTables.min', 'plugins/data-tables/DT_bootstrap')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'datepicker',
	'css'	=> array('plugins/bootstrap-datepicker/css/datepicker'),
	'js'	=> array('plugins/bootstrap-datepicker/js/bootstrap-datepicker')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'timepicker',
	'css'	=> array('plugins/bootstrap-timepicker/compiled/timepicker'),
	'js'	=> array('plugins/bootstrap-timepicker/js/bootstrap-timepicker')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'wysihtml5',
	'css'	=> array('plugins/bootstrap-wysihtml5/bootstrap-wysihtml5', 'plugins/bootstrap-wysihtml5/wysiwyg-color'),
	'js'	=> array('plugins/bootstrap-wysihtml5/wysihtml5-0.3.0', 'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'fuelux-tree',
	'css'	=> array('plugins/fuelux/css/tree-sysclass'),
	'js'	=> array('plugins/fuelux/js/tree.min')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'select2',
	'css'	=> array('plugins/select2/select2_metro'),
	'js'	=> array('plugins/select2/select2')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'pwstrength',
	'js'	=> array('plugins/jquery.pwstrength.bootstrap/src/pwstrength')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'jquery-nestable',
	'css'	=> array('css/jquery-nestable/jquery.nestable'),
	'js'	=> array('plugins/jquery-nestable/jquery.nestable')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'jquery-multi-select',
	'css'	=> array('css/jquery-multi-select/multi-select'),
	'js'	=> array('plugins/jquery-multi-select/jquery.multi-select')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'bootstrap-switch',
	'css'	=> array('plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch'),
	'js'	=> array('plugins/bootstrap-switch/js/bootstrap-switch')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'icheck',
	'css'	=> array('plugins/icheck/skins/all'),
	'js'	=> array('plugins/icheck/icheck')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'noui-slider',
	'css'	=> array('plugins/nouislider/nouislider'),
	'js'	=> array('plugins/nouislider/nouislider')
));


$plicoLib->add("resources/components", array(
	'name'	=> 'bootstrap-editable',
	'css'	=> array('plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable'),
	'js'	=> array('plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'jquery-file-upload',
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
		'plugins/jquery-file-upload/js/jquery.fileupload-audio',
		'plugins/jquery-file-upload/js/jquery.fileupload-video',
		'plugins/jquery-file-upload/js/jquery.fileupload-validate',
		'plugins/jquery-file-upload/js/jquery.fileupload-ui'
	)
));

$plicoLib->add("resources/components", array(
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
));

$plicoLib->add("resources/components", array(
	'name'	=> 'jquery-file-upload-video',
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
		'plugins/jquery-file-upload/js/jquery.fileupload-video',
		//'plugins/jquery-file-upload/js/jquery.fileupload-audio',
		//'plugins/jquery-file-upload/js/jquery.fileupload-video',
		//'plugins/jquery-file-upload/js/jquery.fileupload-validate',
		'plugins/jquery-file-upload/js/jquery.fileupload-ui'
	)
));

$plicoLib->add("resources/components", array(
	'name'	=> 'jquery-file-upload-audio',
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
		//'plugins/jquery-file-upload/js/jquery.fileupload-video',
		'plugins/jquery-file-upload/js/jquery.fileupload-audio',
		//'plugins/jquery-file-upload/js/jquery.fileupload-video',
		//'plugins/jquery-file-upload/js/jquery.fileupload-validate',
		'plugins/jquery-file-upload/js/jquery.fileupload-ui'
	)
));

$plicoLib->add("resources/components", array(
	'name'	=> 'bootstrap-confirmation',
	'js'	=> array(
		'plugins/bootstrap-confirmation/bootstrap-confirmation'
	)
));

$plicoLib->add("resources/components", array(
	'name'	=> 'fullcalendar',
	'css'	=> array('plugins/fullcalendar/fullcalendar/fullcalendar'),
	'js'	=> array('plugins/fullcalendar/fullcalendar/fullcalendar')
));

$plicoLib->add("resources/components", array(
	'name'	=> 'easy-pie-chart',
	'css'	=> array('plugins/jquery-easy-pie-chart/jquery.easy-pie-chart'),
	'js'	=> array('plugins/jquery-easy-pie-chart/jquery.easy-pie-chart')
));

		//,


$plicoLib->concat(
	'resources/css',
	array(
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
	)
);

$plicoLib->concat(
	'resources/js',
	array(
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
	)
);



/* BOOTSTRAP PHALCON */
use Phalcon\Loader,
	Phalcon\DI,
	Phalcon\DI\FactoryDefault,
	Phalcon\Config\Adapter\Ini as ConfigIni,
	Phalcon\Mvc\Model\Metadata\Memory as MetaData,
	Phalcon\Mvc\Model\MetaData\Apc as ApcMetaData,
	Phalcon\Session\Adapter\Files as Session,
	Phalcon\Cache\Backend\Apc as BackendCache,
	Phalcon\Logger,
	Phalcon\Logger\Adapter\File as FileLogger,
	Phalcon\Crypt,
	Phalcon\Acl\Adapter\Memory as AclList;

// Creates the autoloader
$loader = new Loader();

// Register some namespaces
$loader->registerNamespaces(
    array(
       "Sysclass\Models" => "../app/models/",
       "Sysclass\Services" => "../app/services/",
       "Plico" => "../app/plico/", // TODO: Move code to plicolib itself
       "Sysclass" => "../app/sysclass/"
    )
);
// Register autoloader
$loader->register();

$di = new FactoryDefault();
$eventsManager = new Phalcon\Events\Manager();
$di->set("eventManager", $eventsManager);

$di->set('db', function () use ($configuration, $eventsManager) {
	$class = "Phalcon\\Db\\Adapter\\Pdo\\" . ucfirst($configuration['dbtype']);
	if (class_exists($class)) {
	    $database = new $class(array(
	        "host"     => $configuration['dbhost'],
	        "username" => $configuration['dbuser'],
	        "password" => $configuration['dbpass'],
	        "dbname"   => $configuration['dbname'],
	        "charset"  => 'utf8'
	    ));

	    $database->setEventsManager($eventsManager);

	    return $database;
	} else {
		throw new Exception("Error estabilishing a database connection");
		exit;
	}
});

$di->set('sysconfig', function()  {
	$config = new ConfigIni(__DIR__ . "/RELEASE");
	//echo $config->project->full_version, "\n";
	return $config;
}, true);



$logger = new FileLogger(__DIR__ . "/logs/database.log");
// Listen all the database events
$eventsManager->attach('db', function ($event, $connection) use ($logger) {
    if ($event->getType() == 'beforeQuery') {
        $logger->log($connection->getSQLStatement(), Logger::INFO);
    }
});

// Set a models manager
$di->set('modelsManager', function ()  use ($eventsManager) {
    $ModelsManager = new Plico\Mvc\Model\Manager();

	return $ModelsManager;
});

$di->set('modelsCache', function () {

	//Cache data for 1 hour
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
	    'lifetime' => 3600
	));

	$cache = new BackendCache($frontCache, array(
    	'prefix' => 'SYSCLASS-MODELS'
  	));

    return $cache;
});

// Use the memory meta-data adapter or other
//$di->set('modelsMetadata', new MetaData());

$di->set('modelsMetadata', new \Phalcon\Mvc\Model\Metadata\Files(array(
    'metaDataDir' => __DIR__ . '/cache/metadata/'
)));
$di->set('cache', function() {

	//Cache data for 1 hour
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
	    'lifetime' => 3600
	));

	$cache = new BackendCache($frontCache, array(
    	'prefix' => 'SYSCLASS'
  	));

	return $cache;
});


$di->setShared("acl", function() use ($di, $eventsManager) {
	// GET CURRENT USER
	$user = $di->get("authentication")->checkAccess();

	// CREATE THE ACL
	$acl = Sysclass\Acl\Adapter::getDefault($user);
	// Bind the eventsManager to the ACL component
	$acl->setEventsManager($eventsManager);

	return $acl;

});


// Attach a listener for type "acl"
$eventsManager->attach("acl", function ($event, $acl) {
	/*
    //if ($event->getType() == "beforeCheckAccess") {
    	var_dump(json_encode($acl));
    	echo $event->getType(),
        	$acl->getActiveRole(),
            $acl->getActiveResource(),
            $acl->getActiveAccess(),
            "<br />";
    //}
    */
});



$di->setShared("url", function() use ($di) {
	$url = new Phalcon\Mvc\Url();
	$url->setDI($di);
	$url->setBasePath("/var/www/sysclass/current/www");

	return $url;
});

$di->setShared("escaper", function() {
    $escaper = new \Phalcon\Escaper();
    return $escaper;
});

$di->setShared("assets", function() use ($di) {
	$assets = new Plico\Assets\Manager(array(
		"sourceBasePath" => __DIR__ . "/www/",
		"targetBasePath" => __DIR__ . "/www/"
	));
	//$assets->setDI($di);
	//$url->setBasePath("/var/www/local.sysclass.com/current/www");
	return $assets;
});


$session = new Session(array('uniqueId' => 'SYSCLASS'));
if (!$session->isStarted()) {
	$session->start();
}
$di->set('session', $session);

$di->set("configuration", function() {
	return new Sysclass\Services\Configuration();
});

// TODO: Load Autentication Backends, based on configuration
$di->set("authentication", function() use ($eventsManager) {
	$authentication = new Sysclass\Services\Authentication\Adapter();
	$authentication->setEventsManager($eventsManager);
	return $authentication;
});

$di->set('crypt', function () {
    $crypt = new Crypt();
    // Set a global encryption key
    //$crypt->setKey();
    return $crypt;
}, true);


$di->set('stringsHelper', function () {
    $strings = new \Plico\Php\Helpers\Strings();
    // Set a global encryption key
    //$crypt->setKey();
    return $strings;
});

DI::setDefault($di);

// TODO: PARSE MODULES FILES (aka config.yml), AND CHECK FOR EVENT LISTENERS

/*
class SomeListener
{
    public function afterLogin($event, $myComponent, $data)
    {
        var_dump($myComponent);
        exit;
    }
}
// Attach the listener to the EventsManager
$eventsManager->attach('authentication', new SomeListener());
*/



/*
$locale = Locale::acceptFromHttp($_SERVER["HTTP_ACCEPT_LANGUAGE"]);

// Locale could be something like "en_GB" or "en"
echo Locale::getPrimaryLanguage($locale);
exit;
*/
/*
use Phalcon\DI;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Db\Adapter\Pdo\Sqlite as Connection;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;

$di = new DI();

// Setup a connection
$di->set(
    'db',
    new Connection(
        array(
            "dbname" => "sample.db"
        )
    )
);

// Set a models manager
$di->set('modelsManager', new ModelsManager());

// Use the memory meta-data adapter or other
$di->set('modelsMetadata', new MetaData());
*/
