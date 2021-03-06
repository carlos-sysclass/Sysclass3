<?php
use 
    Phalcon\Mvc\Model\Transaction\Manager as TransactionManager,
    Phalcon\Logger,
	Phalcon\Logger\Adapter\File as FileLogger,
	Phalcon\Cache\Backend\Apc as BackendCache;

$di->set('db', function () use ($environment, $eventsManager) {
    $class = "Phalcon\\Db\\Adapter\\Pdo\\" . ucfirst($environment->database->adapter);
    if (class_exists($class)) {
        $database = new $class(array(
            "host"     => $environment->database->host,
            "username" => $environment->database->username,
            "password" => $environment->database->password,
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

if (APP_TYPE === "CONSOLE") {
    $logger = new FileLogger(REAL_PATH . "/logs/database-tasks.log");
} elseif (APP_TYPE === "WEBSOCKET") {
    $logger = new FileLogger(REAL_PATH . "/logs/database-websocket.log");
} else {
    $logger = new FileLogger(REAL_PATH . "/logs/database.log");
}

// Listen all the database events
$eventsManager->attach('db', function ($event, $connection) use ($logger) {
    if ($event->getType() == 'beforeQuery') {
        $logger->log($connection->getSQLStatement(), Logger::INFO);
    }
});



$di->setShared('transactions', function () {
    return new TransactionManager();
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
    /*
    $cache = new BackendCache($frontCache, array(
        'prefix' => 'SYSCLASS-MODELS'
    ));
    */

    //Create a MongoDB cache
    $cache = new \Phalcon\Cache\Backend\Mongo($frontCache, [
        'server' => is_null($environment->mongo->server) ? 'mongodb://localhost' : 'mongodb://' . $environment->mongo->server,
        'db' => $environment->mongo->database . "-" . $environment_name,
        'collection' => 'cache'
    ]);

    return $cache;

    return $cache;
});

$di->set('modelsMetadata', new \Phalcon\Mvc\Model\Metadata\Files(array(
    'metaDataDir' => __DIR__ . '/../../cache/metadata/'
)));


if (APP_TYPE === "CONSOLE" || APP_TYPE === "WEBSOCKET") {
    $di->set('cache', function() use ($environment, $di) {
        $environment_name = $di->get("sysconfig")->deploy->environment;

        //Cache data for 1 hour
        $frontCache = new \Phalcon\Cache\Frontend\Data(array(
            'lifetime' => 3600
        ));

        //Create a MongoDB cache
        $cache = new \Phalcon\Cache\Backend\Mongo($frontCache, [
            'server' => is_null($environment->mongo->server) ? 'mongodb://localhost' : 'mongodb://' . $environment->mongo->server,
            'db' => $environment->mongo->database . "-" . $environment_name,
            'collection' => 'cache'
        ]);

        return $cache;
    });
} else {
    $di->set('cache', function() {

        //Cache data for 1 hour
        $frontCache = new \Phalcon\Cache\Frontend\Data(array(
            'lifetime' => 3600
        ));

        //Create a MongoDB cache
        $cache = new \Phalcon\Cache\Backend\Mongo($frontCache, [
            'server' => is_null($environment->mongo->server) ? 'mongodb://localhost' : 'mongodb://' . $environment->mongo->server,
            'db' => $environment->mongo->database . "-" . $environment_name,
            'collection' => 'cache'
        ]);

        return $cache;
    });
}

$di->set('mongo', function () use ($environment, $di) {
    $environment_name = $di->get("sysconfig")->deploy->environment;

    if (class_exists("MongoClient")) {
        $mongo = new MongoClient();    
    } else {
        $mongo = new Mongo();
    }
   
    return $mongo->selectDB($environment->mongo->database . "-" . $environment_name);
   
}, true);

$di->set('collectionManager', function(){
    return new Phalcon\Mvc\Collection\Manager();
}, true);