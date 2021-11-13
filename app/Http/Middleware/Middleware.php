<?php

namespace Qss\Http\Middleware;

use Qss\Http\Request;
use Qss\Http\Middleware\AuthMiddleware;

class Middleware
{
    /** @var $root */
    protected $root;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->root = function (Request $request){
            return $request;
        };
    }

    /**
     * Undocumented function
     *
     * @param AuthMiddleware $middleware
     * @param [type] $route
     * @return void
     */
    public function addMiddleware(AuthMiddleware $middleware, $route)
    {
        $next = $this->root;

        $this->root = function (Request $request) use ($next, $middleware, $route){
            return $middleware($next, $request, $route);
        };
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        return call_user_func($this->root, $request);
    }
}