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
    /** @var $matchedRoute */
    protected $matchedRoute;


    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $path
     * Method for setting the current path
     */
    public function setPath(string $path = "/")
    {
        $this->path = $path;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCurrentUrl()
    {
        return $_SERVER["HTTP_HOST"] . $this->path;
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

        /**
         * If it is false, the route does not exist
         */
        if($route === false){
            throw new \Exception("Route: " . $this->path . " not found");
        }

        /**
         * Checking if the request method is allowed
         */
        if (in_array($_SERVER["REQUEST_METHOD"], $this->methods[$route]) === false){
            throw new \Exception("Method: " . $_SERVER["REQUEST_METHOD"] . " not allowed");
        }

        /** return route's handler */
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
            $this->matchedRoute = $this->path;
            return $this->path;
        }

        /**
         * array_keys gives all route paths
         */
        $routes = array_keys($this->routes);
        foreach ($routes as $route) {

            // escape slashes
            $expression = preg_replace('/\//', '\\/', $route);
            // match what ever is between {} and save it as the given name
            $expression = preg_replace('/{([a-z]+)}/i', '(?P<\1>[^\.]+)', $expression);
        
            //Continue if it is not matched
            // Sometimes it matched the path without any parameters
                    // example: Route: /users, given /users/{id}
            if((preg_match('/^' . $expression . '/', $this->path, $matched) === 0) || count($matched) === 1){
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
           
            $this->matchedRoute = $matched[0];

            // return matched route
            return $route;
        }

        // return false if it is not matched
        return false;
    }
}