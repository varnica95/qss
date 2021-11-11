<?php


namespace Qss\Http\Middleware;

use Qss\Http\Request;
use Qss\Includes\Cookie;
use Qss\Includes\Session;

class AuthMiddleware
{

    public function __invoke($next, Request $request, $route)
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if($currentPath === $request->getMatchedRoute()){
            if (Session::get('session_token') === null){
                $request->redirect("/login");
            }

            if (Cookie::get('cookie_token') !== null && empty($_SESSION)){
                Session::set('session_token', Cookie::get('cookie_token'));
                $request->redirect("/");
            }
        }

        return $next($request);
    }
}