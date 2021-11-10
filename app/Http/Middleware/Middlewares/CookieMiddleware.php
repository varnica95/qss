<?php


namespace Qss\Http\Middleware\Middlewares;

use Qss\Http\Request;
use Qss\Includes\Cookie;
use Qss\Includes\Session;
use Qss\Http\Middleware\Middleware;


class CookieMiddleware implements Middleware
{

    public function __invoke($next, Request $request, $route)
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (Cookie::get('cookie_token') !== null && empty($_SESSION) && $currentPath === $request->getMatchedRoute()){
            Session::set('session_token', Cookie::get('cookie_token'));
           
            $request->redirect("/");
        }
        
        return $next($request);
    }
}