<?php

namespace Controller;

use Pimple;

abstract class AbstractController
{
    protected $app;

    abstract public function render(): string;

    public function __construct(Pimple $app)
    {
        $this->app = $app;
    }
}
