<?php

namespace Webvest\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Webvest\Controller\IndexController;
use Webvest\Controller\TargetHoursController;

use Pimple;

abstract class AbstractController
{
    protected $app;

    public function __construct(Pimple $app)
    {
        $this->app = $app;
    }

    protected function render(string $name, array $data = []): Response
    {
        $response = new Response();
        $response->setContent($this->app['viewService']->render($name, $data));
        return $response;
    }

    protected function json(array $data): JsonResponse
    {
        $response = new JsonResponse();
        $response->setContent(json_encode($data));
        return $response;
    }
}
