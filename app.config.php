<?php 
//define("DEBUG", 1);

$plicoLib = PlicoLib::instance();

$plicoLib->set('theme', 'metronic');
$plicoLib->set('client_name', 'Smart Solution');
$plicoLib->set('app_name', 'Smart Solution');
//$plicoLib->set('db_dsn', 'postgres://ssra:fep7_58A#@localhost/ssra_root');
$plicoLib->set('default/resource', '/assets/%s/');
$plicoLib->add('path/themes', __DIR__ . '/themes/');
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
		'LoginController',
		'HomeController',
		'AdministratorController',
		'StudentController'
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
/*
// WEB THEME SPECIFIC
$plicoLib->add(
	'resources/web/css',
	'main'
);
*/
$plicoLib->concat(
	'resources/css',
	array(
		//<!-- BEGIN GLOBAL MANDATORY STYLES -->
		'plugins/font-awesome/css/font-awesome.min',
		'plugins/bootstrap/css/bootstrap.min',
		'plugins/uniform/css/uniform.default',
		//<!-- END GLOBAL MANDATORY STYLES -->
		//<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
		'plugins/gritter/css/jquery.gritter',
		'plugins/bootstrap-daterangepicker/daterangepicker-bs3',
		'plugins/fullcalendar/fullcalendar/fullcalendar',
		'plugins/jqvmap/jqvmap/jqvmap',
		'plugins/jquery-easy-pie-chart/jquery.easy-pie-chart',
		//<!-- END PAGE LEVEL PLUGIN STYLES -->
 		//<!-- BEGIN THEME STYLES -->
		'css/style-metronic',
		'css/style',
		'css/style-responsive',
		'css/plugins',
		'css/themes/light',
		'css/custom'
		//<!-- END THEME STYLES -->
	)
);

$plicoLib->concat(
	'resources/js',
	array(
		'plugins/jquery-1.10.2.min',
		'plugins/jquery-migrate-1.2.1.min',
		'plugins/jquery-ui/jquery-ui-1.10.3.custom.min',
		'plugins/bootstrap/js/bootstrap.min',
		'plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min',
		'plugins/jquery-slimscroll/jquery.slimscroll.min',
		'plugins/jquery.blockui.min',
		'plugins/jquery.cookie.min',
		'plugins/uniform/jquery.uniform.min',
		'plugins/jquery-validation/dist/jquery.validate',
		'plugins/backstretch/jquery.backstretch.min',
		'plugins/select2/select2.min',
		'scripts/app'
	)
);