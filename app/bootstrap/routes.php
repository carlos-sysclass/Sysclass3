<?php
use Phalcon\Mvc\Router\Annotations as Router,
	Phalcon\Mvc\Dispatcher as MvcDispatcher;

$di->set('router', function () {
	// Create the router without default routes
	$router = new Router();

	$router->setControllerSuffix("");

	$router->addResource("Sysclass\Controllers\LoginController");
	$router->addResource("Sysclass\Controllers\DashboardController");
	$router->addResource("Sysclass\Controllers\AgreementController");

	$router->addResource("Sysclass\Modules\Users\UsersModule", "/module/users");

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



