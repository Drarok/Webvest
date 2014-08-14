<?php

namespace Harvest\Exception;

class Handler
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function install()
    {
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
    }

    public function handleError($errno, $errstr, $errfile = null, $errline = null)
    {
        if ($errno & error_reporting()) {
            throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
        }
    }

    public function handleException($exception)
    {
        try {
            echo $this->app['viewService']->render(
                'error.html.twig',
                array(
                    'exception' => $exception,
                )
            );
        } catch (\Exception $e) {
            echo (string) $e;
        }
    }
}
