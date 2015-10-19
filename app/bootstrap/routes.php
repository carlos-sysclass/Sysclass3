<?php
use Phalcon\Mvc\Router\Annotations as Router,
	Phalcon\Mvc\Dispatcher as MvcDispatcher;

$di->set('router', function () use ($environment) {
	// Create the router without default routes
	$router = new Router();

	$router->setControllerSuffix("");

	$router->addResource("Sysclass\Controllers\LoginController");
	$router->addResource("Sysclass\Controllers\DashboardController");
	$router->addResource("Sysclass\Controllers\AgreementController");

    $moduledir = $environment["path/modules"];

    $modulesList = scandir($moduledir);

    foreach($modulesList as $mod) {

        if ($mod == '.' || $mod == '..') {
            continue;
        }

        $resource = sprintf("Sysclass\\Modules\\%s\\%sModule", $mod, $mod);
        $prefix = sprintf("/module/%s", strtolower($mod));
        //var_dump($resource , $prefix);
        $router->addResource($resource , $prefix);
    }

	return $router;

});

$di->set('dispatcher', function () use ($eventsManager, $di) {

    // Attach a listener for type "dispatch"
    // 
    $eventsManager->attach('dispatch:beforeExecuteRoute', $di->get("authentication"));
    /*
    $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher) {
        // Possible controller class name
        // HANDLE POSSIBLE CASE OF DISPATCHING MODULES
        if ($dispatcher->getNamespaceName() == "Sysclass\Modules") {

            $dispatcher->setNamespaceName("Sysclass\\Modules\\" . ucfirst($dispatcher->getParam("module_name")));
            $dispatcher->setControllerSuffix("Module");

        }
    });
    */
    /*
    $eventsManager->attach("dispatch:beforeExecuteRoute", function ($event, $dispatcher) use ($di) {
        // Possible controller class name
        // HANDLE POSSIBLE CASE OF DISPATCHING MODULES
                    var_dump($dispatcher-> getHandlerClass());
                    var_dump($dispatcher-> getActionName());
                    var_dump($dispatcher->  getParams());

                        $reader = new Phalcon\Annotations\Adapter\Memory();
                        // Reflect the annotations in the class Example
                        $reflector = $reader->get($dispatcher-> getHandlerClass());
                        // Read the annotations in the class' docblock
                        $annotations = $reflector->getMethodsAnnotations();

                        var_dump($annotations['getItemRequest']);


                    
                    var_dump($di->get('router')->getMatchedRoute());
            exit;
    });
    */
    $dispatcher = new MvcDispatcher();

    // Bind the eventsManager to the view component
    $dispatcher->setEventsManager($eventsManager);

    $dispatcher->setControllerSuffix("");
    $dispatcher->setActionSuffix("");

    return $dispatcher;

}, true);



