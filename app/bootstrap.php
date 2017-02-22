<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Webvest\ExceptionHandler;
use Webvest\Harvest\Cache\FilesystemCache;
use Webvest\Harvest\Client;

$app = new Pimple();

$app['exceptionHandler'] = $app->share(function ($app) {
    return new ExceptionHandler($app);
});

$app['config'] = $app->share(function ($app) {
    $configPathname = __DIR__ . '/config/harvest.json';

    if (! file_exists($configPathname)) {
        throw new Exception('Missing config file.');
    }

    return json_decode(file_get_contents($configPathname));
});

$app['client'] = $app->share(function ($app) {
    $config = $app['config'];

    $client = new Client(
        $config->harvest->url,
        $config->harvest->username,
        $config->harvest->password,
        new FilesystemCache($config->cache->path, $config->cache->expiry)
    );

    return $client;
});

$app['viewService'] = $app->share(function ($app) {
    $twig = new Twig_Environment(
        new Twig_Loader_Filesystem(__DIR__ . '/views'),
        array(
            'debug' => true,
        )
    );

    $twig->addExtension(new Twig_Extension_Debug());
    $twig->getExtension('core')->setDateFormat('d/m/Y H:i:s', '%d days');

    return $twig;
});

return $app;
