<?php

error_reporting(E_ALL);

$app = require_once __DIR__ . '/../app/bootstrap.php';

use Controller\IndexController;
use Controller\ErrorController;

$app['exceptionHandler']->install();

// TODO: Use a proper router.
// $_SERVER['REQUEST_URI'] => string '/'
// $_SERVER['REQUEST_URI'] => string '/target-hours'

$uri = $_SERVER['REQUEST_URI'] ?? '/';

switch ($uri) {
    case '/':
        $controller = new IndexController($app);
        break;

    case '/target-hours':
        $controller = new Controller\TargetHoursController($app);
        break;

    default:
        $controller = new ErrorController($app, $uri);
        break;
}

echo $controller->render();
