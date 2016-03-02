<?php

$di->set("tracking", function() use ($eventsManager) {
    $tracking = new Sysclass\Services\Tracking\Adapter();
    $tracking->setEventsManager($eventsManager);
    $tracking->initialize();
    //$storage
    return $tracking;
});

$environment = $di->get("environment");