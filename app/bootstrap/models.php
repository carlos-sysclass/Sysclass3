<?php
use 
    Phalcon\Logger,
	Phalcon\Logger\Adapter\File as FileLogger,
	Phalcon\Cache\Backend\Apc as BackendCache;

$di->set('db', function () use ($environment, $eventsManager) {
    $class = "Phalcon\\Db\\Adapter\\Pdo\\" . ucfirst($environment->database->dbtype);
    if (class_exists($class)) {
        $database = new $class(array(
            "host"     => $environment->database->dbhost,
            "username" => $environment->database->dbuser,
            "password" => $environment->database->dbpass,
            "dbname"   => $environment->database->dbname,
            "charset"  => 'utf8'
        ));

        $database->setEventsManager($eventsManager);

        return $database;
    } else {
        throw new Exception("Error estabilishing a database connection");
        exit;
    }
});

if (CONSOLE_APP === TRUE) {
    $logger = new FileLogger(REAL_PATH . "/logs/database-tasks.log");
} else {
    $logger = new FileLogger(REAL_PATH . "/logs/database.log");
}
// Listen all the database events
$eventsManager->attach('db', function ($event, $connection) use ($logger) {
    if ($event->getType() == 'beforeQuery') {
        $logger->log($connection->getSQLStatement(), Logger::INFO);
    }
});

// Set a models manager
$di->set('modelsManager', function ()  use ($eventsManager) {
    $modelsManager = new Plico\Mvc\Model\Manager();

    $modelsManager->setEventsManager($eventsManager);

    return $modelsManager;
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
