<?php

namespace Qss\Traits;

use Qss\Http\Middleware\AuthMiddleware;


trait RequestMethods
{
    /**
     * @throws \Exception
     */
    public function get($route, $handler)
    {
        $this->container->get("router")->setRoute($route, $handler, ["GET"]);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function post($route, $handler)
    {
        $this->container->get("router")->setRoute($route, $handler, ["POST"]);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $route
     * @return void
     */
    public function withAuthMiddleware(string $route)
    {
        /** @var RootMiddleware $middleware */
        $middleware = $this->container->get("middleware");

        $middleware->addMiddleware(new AuthMiddleware, $route);
    
        return true;
    }
}