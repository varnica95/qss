<?php

namespace Qss\Http\Middleware;

use Qss\Http\Request;
use Qss\Http\Middleware\AuthMiddleware;

class Middleware
{
    protected $root;

    public function __construct()
    {
        $this->root = function (Request $request){
            return $request;
        };
    }

    public function addMiddleware(AuthMiddleware $middleware, $route)
    {
        $next = $this->root;

        $this->root = function (Request $request) use ($next, $middleware, $route){
            return $middleware($next, $request, $route);
        };
    }

    public function handle(Request $request)
    {
        return call_user_func($this->root, $request);
    }
}