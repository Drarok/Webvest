<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

error_reporting(E_ALL);

$app = require_once __DIR__ . '/../app/bootstrap.php';

$app['exceptionHandler']->install();

// TODO: Use a proper router.
// $_SERVER['REQUEST_URI'] => string '/'
// $_SERVER['REQUEST_URI'] => string '/target-hours'

$request = Request::createFromGlobals();

$path = $request->getPathInfo();

$controller = '';
$action = '';

$pathParts = explode('/', substr($path, 1));
if ($pathParts[0] === '') {
    $pathParts[0] = 'timers';
}
$controller =  'Webvest\\Controller\\' . str_replace('-', '', ucwords($pathParts[0], '-')) . 'Controller';
$action = ($pathParts[1] ?? 'index') . 'Action';

$response = [new $controller($app), $action]($request);
if (!is_object($response) || !($response instanceof Response)) {
    throw new UnexpectedValueException('Invalid response');
}
$response->send();
