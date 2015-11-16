<?php

$di->set("storage", function() use ($eventsManager) {
    $storage = new Sysclass\Services\Storage\Adapter();
    $storage->setEventsManager($eventsManager);
    $storage->initialize();
	//$storage
    return $storage;
});