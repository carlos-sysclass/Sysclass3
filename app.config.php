<?php 
//define("DEBUG", 1);

$plicoLib = PlicoLib::instance();

$plicoLib->set('theme', 'sysclass.default');
$plicoLib->set('client_name', 'Sysclass');
$plicoLib->set('app_name', 'Sysclass');
$plicoLib->set('db_dsn', 'mysql://sysclass:WXubN7Ih@localhost/sysclass_layout?persist');
$plicoLib->set('db/charset', 'utf8');

$plicoLib->set('default/resource', '/assets/%s/');
$plicoLib->add('path/themes', __DIR__ . '/themes/');
$plicoLib->add('path/modules', __DIR__ . '/modules/');
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
		'DashboardController'
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
	'name'	=> 'validation',
	'js'	=> array('plugins/jquery-validation/dist/jquery.validate.min', 'plugins/jquery-validation/dist/additional-methods.min')
));
$plicoLib->add("resources/components", array(
	'name'	=> 'data-tables',
	'css'	=> array('plugins/data-tables/DT_bootstrap'),
	'js'	=> array('plugins/data-tables/jquery.dataTables.min', 'plugins/data-tables/DT_bootstrap')
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
	'css'	=> array('plugins/fuelux/css/tree-metronic'),
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


$plicoLib->concat(
	'resources/sysclass.default/css',
	array(
		//<!-- BEGIN GLOBAL MANDATORY STYLES -->
		'plugins/font-awesome/css/font-awesome.min',
		'plugins/font-awesome-more/css/font-awesome-ext',
		'plugins/bootstrap/css/bootstrap.min',
		'plugins/uniform/css/uniform.default',
		//<!-- END GLOBAL MANDATORY STYLES -->

		//<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
		// TODO GET ALL THIS CSS AND PUT ON A COMPONENTS BASIS
		'plugins/bootstrap-toastr/toastr.min',
		'plugins/gritter/css/jquery.gritter',
		'plugins/bootstrap-daterangepicker/daterangepicker-bs3',
		'plugins/fullcalendar/fullcalendar/fullcalendar',
		'plugins/jqvmap/jqvmap/jqvmap',
		'plugins/jquery-easy-pie-chart/jquery.easy-pie-chart',
		'plugins/bootstrap-fileupload/bootstrap-fileupload',
		//<!-- END PAGE LEVEL PLUGIN STYLES -->
 		//<!-- BEGIN THEME STYLES -->
		'css/style-metronic',
		'css/style',
		'css/style-responsive',
		'css/plugins',
		'css/themes/light',
		'css/custom',
		'css/videojs/video-js'
		//<!-- END THEME STYLES -->
	)
);

$plicoLib->concat(
	'resources/sysclass.default/js',
	array(
		'plugins/jquery-1.10.2.min',
		'plugins/jquery-migrate-1.2.1.min',
		'plugins/backbone/underscore',
		'plugins/backbone/backbone',
		'plugins/backbone/marionette',
		'plugins/jquery-ui/jquery-ui-1.10.3.custom.min',
		'plugins/bootstrap/js/bootstrap.min',
		'plugins/moment/moment.min',

		//<!-- JGROWL notifications -->

		// TODO GET ALL THIS JS AND PUT ON A COMPONENTS BASIS
		'plugins/bootstrap-toastr/toastr.min',
		'plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min',
		'plugins/jquery-slimscroll/jquery.slimscroll.min',
		'plugins/jquery.blockui.min',
		'plugins/jquery.cookie.min',
		'plugins/uniform/jquery.uniform.min',
		'plugins/jquery-validation/dist/jquery.validate',
		'plugins/backstretch/jquery.backstretch.min',
		
		'plugins/strophe/strophe',
		'plugins/strophe/strophe.roster',
		'plugins/strophe/strophe.messaging',
		
		'plugins/strophe/strophe.chatstates',
		'plugins/strophe/strophe.ping',

		'scripts/app',
		'scripts/sysclass',
		'scripts/models',
		'scripts/ui', // TODO MERGE ui AND views
		'scripts/views',
		'scripts/utils.toastr',
		'scripts/utils.strophe',
		'scripts/portlets'
	)
);