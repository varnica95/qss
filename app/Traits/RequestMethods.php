<?php

namespace Qss\Traits;

use Qss\Http\Middleware\Middlewares\AuthMiddleware;
use Qss\Http\Middleware\Middlewares\CookieMiddleware;


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

    public function middleware(bool $auth = false, bool $cookie = false, string $route)
    {

        if($auth === true){
            $this->container->get("middleware")->addMiddleware(new AuthMiddleware, $route);
        }

        if($cookie === true){
            $this->container->get("middleware")->addMiddleware(new CookieMiddleware, $route);
        }

        return true;
    }
}