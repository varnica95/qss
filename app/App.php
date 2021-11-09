<?php

namespace Qss;

use Qss\Core\Router;
use Qss\Traits\RequestMethods;

class App
{
    use RequestMethods;

    /** @var Container $container */
    private $container;

    /**
     * App constructor
     */
    public function __construct()
    {
        $this->container = new Container();
    }

    public function run()
    {
        /** @var Router $router */
        $router = $this->container->get("router");

        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $router->setPath($currentPath);

        $handler = $router->getRouteHandler();

        dd($handler);
    }
}