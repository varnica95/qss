<?php

namespace Qss\Core;

class Router
{
    /** @var $routes */
    protected $routes;
    /** @var $methods */
    protected $methods;
    /** @var $path */
    protected $path;

    /**
     * @param string $path
     * Method for setting the current path
     */
    public function setPath(string $path = "/")
    {
        $this->path = $path;
    }

    /**
     * @param string $route
     * @param array $handler
     * @param array|string[] $methods
     * Method for setting new route
     */
    public function setRoute(string $route, array $handler, array $methods = ["GET"])
    {
        $this->routes[$route] = $handler;
        $this->methods[$route] = $methods;
    }

    public function getResponse()
    {
        $route = $this->getMatchedRoute();

        if (in_array($_SERVER["REQUEST_METHOD"], $this->methods[$this->path]) === false){
            //TODO:
        }
    }

    private function getMatchedRoute()
    {
        if (isset($this->routes[$this->path])){
            dd($this->routes[$this->path]);
            return $this->routes[$this->path];
        }

        $routes = array_keys($this->routes);

        foreach ($routes as $route) {

            $expression = preg_replace('/\//', '\\/', $route);
            $expression = preg_replace('/{([a-z]+)}/i', '(?P<\1>[^\.]+)', $expression);

            if(empty(preg_match('/^' . $expression . '/', $this->path, $matched))){
                continue;
            }

            dd($matched);
        }
    }
}