<?php
use 
    Phalcon\DI,
    Phalcon\Mvc\Dispatcher as MvcDispatcher,
    Phalcon\Config\Adapter\Ini as ConfigIni,
    Phalcon\Mvc\Model\Metadata\Memory as MetaData,
    Phalcon\Mvc\Model\MetaData\Apc as ApcMetaData,
    Phalcon\Session\Adapter\Files as Session,
    Phalcon\Cache\Backend\Apc as BackendCache,
    Phalcon\Logger,
    Phalcon\Logger\Adapter\File as FileLogger,
    Phalcon\Crypt,
    Phalcon\Acl\Adapter\Memory as AclList;


$di->set('sysconfig', function()  {
    $config = new ConfigIni(__DIR__ . "/../../RELEASE");
    //echo $config->project->full_version, "\n";
    return $config;
}, true);


// LOAD ENVIROMENT CONFIG
$environment = $di->get("sysconfig")->deploy->environment;
$configuration = new ConfigIni(__DIR__ . "/../config/{$environment}.ini");

$di->set('router', function () {
    require __DIR__.'/routes.php';
    return $router;
});

$di->set('dispatcher', function () use ($eventsManager) {

    // Attach a listener for type "dispatch"
    $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher) {
        // Possible controller class name
        // HANDLE POSSIBLE CASE OF DISPATCHING MODULES
        if ($dispatcher->getNamespaceName() == "Sysclass\Modules") {

            $dispatcher->setNamespaceName("Sysclass\\Modules\\" . ucfirst($dispatcher->getParam("module_name")));
            $dispatcher->setControllerSuffix("Module");
        }
    });

    $dispatcher = new MvcDispatcher();

    // Bind the eventsManager to the view component
    $dispatcher->setEventsManager($eventsManager);

    $dispatcher->setControllerSuffix("");
    $dispatcher->setActionSuffix("");

    return $dispatcher;

}, true);

$di->set('db', function () use ($configuration, $eventsManager) {
    $class = "Phalcon\\Db\\Adapter\\Pdo\\" . ucfirst($configuration->database->dbtype);
    if (class_exists($class)) {
        $database = new $class(array(
            "host"     => $configuration->database->dbhost,
            "username" => $configuration->database->dbuser,
            "password" => $configuration->database->dbpass,
            "dbname"   => $configuration->database->dbname,
            "charset"  => 'utf8'
        ));

        $database->setEventsManager($eventsManager);

        return $database;
    } else {
        throw new Exception("Error estabilishing a database connection");
        exit;
    }
});

$logger = new FileLogger(__DIR__ . "/../../logs/database.log");
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

$di->set('modelsMetadata', new \Phalcon\Mvc\Model\Metadata\Files(array(
    'metaDataDir' => __DIR__ . '/../../cache/metadata/'
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

$di->setShared("url", function() use ($di) {
    $url = new Phalcon\Mvc\Url();
    $url->setDI($di);
    $url->setBasePath(realpath(__DIR__ . "/../../www"));

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

$di->set('view', function() use ($config) {

    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir('../app/views/');

    $view->registerEngines(
        array('.html' => function($view, $di) {

                $smarty = new \Phalcon\Mvc\View\Engine\Smarty($view, $di);

                $smarty->setOptions(array(
                    'template_dir'      => $view->getViewsDir(),
                    'compile_dir'       => '../app/viewscompiled',
                    'error_reporting'   => error_reporting() ^ E_NOTICE,
                    'escape_html'       => true,
                    '_file_perms'       => 0666,
                    '_dir_perms'        => 0777,
                    'force_compile'     => false,
                    'compile_check'     => true,
                    'caching'           => false,
                    'debugging'         => true,
                ));

                return $smarty;
            }
        )
    );

    return $view;
});

DI::setDefault($di);
