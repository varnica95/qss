<?php


namespace Qss\Http\Middleware\Middlewares;

use Qss\Http\Request;
use Qss\Includes\Session;
use Qss\Http\Middleware\Middleware;


class AuthMiddleware implements Middleware
{

    public function __invoke($next, Request $request, $route)
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (Session::get('session_token') === null && $currentPath === $request->getMatchedRoute()){
            $request->redirect("/login");
        }
    
        return $next($request);
    }
}