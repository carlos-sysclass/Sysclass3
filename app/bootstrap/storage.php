<?php

$di->set("storage", function() use ($eventsManager) {
    $storage = new Sysclass\Services\Storage\Adapter();
    $storage->setEventsManager($eventsManager);
    $storage->initialize("local_storage");
	//$storage
    return $storage;
});

$di->setShared("remote_storage", function() use ($eventsManager) {
    $storage = new Sysclass\Services\Storage\Adapter();
    $storage->setEventsManager($eventsManager);
    $storage->initialize("remote_storage");
	//$storage
    return $storage;
});

$di->setShared("local_storage", function() use ($eventsManager) {
    $storage = new Sysclass\Services\Storage\Adapter();
    $storage->setEventsManager($eventsManager);
    $storage->initialize("local_storage");
    //$storage
    return $storage;
});

$di->set("storages", function() use ($eventsManager) {
    $storage = new Sysclass\Services\Storage\Adapter();
    $storage->setEventsManager($eventsManager);
    //$storage->initialize();
    //$storage
    return $storage;
});
