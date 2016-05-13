<?php
use Phalcon\Mvc\Router\Annotations as Router,
	Phalcon\Mvc\Dispatcher as MvcDispatcher,
    Phalcon\Mvc\Dispatcher\Exception as MvcDispatcherException;

if (APP_TYPE === "WEB") {
    $di->set('router', function () use ($environment) {
    	// Create the router without default routes
    	$router = new Router();

    	$router->setControllerSuffix("");

        $router->addResource("Sysclass\Controllers\ApiController", "/api");
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
            $router->addResource($resource , $prefix);


        }

    	return $router;

    });

    $di->set('dispatcher', function () use ($eventsManager, $di) {

        // Attach a listener for type "dispatch"
        // 
        $eventsManager->attach('dispatch:beforeExecuteRoute', $di->get("authentication"));
        //$eventsManager->attach('dispatch:beforeExecuteRoute', $di->get("queue"));

        $eventsManager->attach('dispatch:afterExecuteRoute', function ($event, $dispatcher) use ($di) {
            // Possible controller class name
            //$di->get("request")
            
            $response = $di->get("response");
            if ($dispatcher->isFinished() && is_null($response->getContent())) {
                $request = $di->get("request");
                if ($request->isAjax()) {
                    $response->setContentType('application/json', 'UTF-8');    
                    $response->setJsonContent($dispatcher->getReturnedValue());
                }
            }
        });

        $dispatcher = new MvcDispatcher();

        // Bind the eventsManager to the view component
        $dispatcher->setEventsManager($eventsManager);

        $dispatcher->setControllerSuffix("");
        $dispatcher->setActionSuffix("");

        return $dispatcher;

    }, true);
} else {
    $dispatcher = $di->get("dispatcher");
    $dispatcher-> setNamespaceName("Sysclass\Tasks");
}

