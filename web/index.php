<?php

error_reporting(E_ALL);

$app = require_once __DIR__ . '/../app/bootstrap.php';

$app['exceptionHandler']->install();

echo $app['viewService']->render(
    'index.html.twig',
    [
        'daily' => $app['client']->getDaily(),
    ]
);
