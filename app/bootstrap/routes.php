<?php
//use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Annotations as Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

// Create the router without default routes
$router = new Router();

$router->setControllerSuffix("");

$router->addResource("Sysclass\Controllers\LoginController");
$router->addResource("Sysclass\Controllers\DashboardController");
$router->addResource("Sysclass\Controllers\AgreementController");

$router->addResource("Sysclass\Modules\Users\UsersModule", "/module/users");

return $router;
