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
    /** @var $parameters */
    protected $parameters;

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

    /**
     * Method for getting a route handler
     *
     * @return void
     */
    public function getRouteHandler()
    {
        $route = $this->matchRoute();

        if($route === false){
            throw new Exception("Route not found");
        }

        if (in_array($_SERVER["REQUEST_METHOD"], $this->methods[$route]) === false){
            throw new Exception("Method not allowed");
        }

        return $this->routes[$route];
    }

    /**
     * Method for getting a matched route
     *
     * @return void
     */
    protected function matchRoute()
    {
        /**
         * If the route is set in the array, return the route
         */
        if (isset($this->routes[$this->path])){
            return $this->path;
        }

        /**
         * array_keys gives all route paths
         */
        $routes = array_keys($this->routes);
        foreach ($routes as $route) {

            // escape slashes
            $expression = preg_replace('/\//', '\\/', $route);
            // match what ever is between {} and save it as the name
            $expression = preg_replace('/{([a-z]+)}/i', '(?P<\1>[^\.]+)', $expression);

        
            //Continue if it is not matched
            if(preg_match('/^' . $expression . '/', $this->path, $matched) === 0){
                continue;
            }

            // add parameters from matched to the property
            foreach ($matched as $key => $value) {
                
                /**
                 * Skip numerical keys
                 *  example: 
                 *      0 => users/5
                 *      "id" => 5
                 */
                if(is_numeric($key) === false) {
                    $this->parameters[$key] = $value;
                }
            }

            // return matched route
            return $route;
        }

        // return false if it is not matched
        return false;
    }
}