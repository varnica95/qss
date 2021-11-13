<?php


namespace Qss\Http\Middleware;

use Qss\Http\Request;
use Qss\Includes\Cookie;
use Qss\Includes\Session;

class AuthMiddleware
{

    /**
     * Undocumented function
     *
     * @param [type] $next
     * @param Request $request
     * @param [type] $route
     * @return void
     */
    public function __invoke($next, Request $request, $route)
    {
        if($route === $request->getMatchedRoute()){
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