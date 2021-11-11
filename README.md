# Q Symfony Skeleton Api project
Backend test  assignment for Q agency.
Created by: Marin Varnica

## Table of Contents

- [Getting started](#getting-started)
- [App](#app)
    - [Running the web app](#running-the-web-app)
    - [Processing the handler](#processing-the-handler)
    - [Responding to the front](responding-to-the-front)
- [Container](#container)
    - [Add a container item](#add-a-container-item)
    - [Getting a container item](#getting-a-container-item)
- [Routes](#routes)
    - [Registering a route](#registering-a-route)
    - [Matching a route](#matching-a-route)
- [Http](#http)
    - [Controllers](#controllers)
    - [Matching a route](#matching-a-route)

# Getting started

Do not forget to run the following command.

```
composer install
```

# App 

The App class is the main class for the project. It is creating new Container class, running the web app and processing it.

## Running the web app

- This refers to App's method "run"
- In this method is passing the current path to the router, getting handler from the matched route, processing it and giving the respond

```php
<?php

/**
     * Undocumented function
     *
     * @return void
     */
    public function run()
    {
        /** @var Router $router */
        $router = $this->container->get("router");

        // same as PATH_INFO
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $router->setPath($currentPath);

        /**
         * Trying to get the handler from the route and getting a respond
         */
        try{
            $handler = $router->getRouteHandler();
            $respond = $this->processHandler($handler);
        } catch(\Exception $e){
            dd($e);

            /**
             * //todo: 404
             */
        }
        
        return $this->processRespond($respond);
    }
```

## Processing the handler

- This refers to App's method "processHandler"
- In this method it is instantiating the controller class and it's passing properties if there is any (Dependeny injection)

## Responding to the front

- This refers to App's method "processRespond"
- Every controller must return Response.
- In this method it is getting all the info from the Response

```php
<?php

    /**
     * Process respond
     *
     * @param [type] $respond
     * @return void
     */
    private function processRespond($respond)
    {

        if (!$respond instanceof Response){
            echo $respond;
            return true;
        }
    
        header("Location: " . $respond->getUrl(), true, $respond->getCode());

        if(is_readable($respond->getContent())){
            include $respond->getContent();
        } else {
            echo $respond->getContent();
        }

        return true;
    }
```

# Container

When the App class instance is instantiated, the Container adds its items to the property

## Add a container item

- To add an item, go to "config/container_items.php"
- The file returns associative array where the key must be a snake_case format.

```php
<?php

return [
  "router" => Qss\Core\Router::class
];
```

## Getting a container item

- When getting an item, it will create an instance of it.
- Also, it will save the object to the cache so it can be reusable
- If the instantiated class has a "getContainer" method, it will pass $this to it. 

```php
<?php

  /**
     * @throws \Exception
     * Method for getting the container item
     */
    public function get(string $name)
    {
        /**
         * Throw an exception if the item does not exist
         */
        if (isset($this->items[$name]) === false){
            throw new \Exception("Container item with the name: " . $name . " not found.");
        }

        /**
         * Return the cached item if it is set.
         * The point is to always return instantiated object
         */
        if (isset($this->cache[$name]) === true){
            return $this->cache[$name];
        }

        /**
         * Save container item to the cache
         */
        $item = $this->items[$name];
        if(method_exists($item, "setContainer") === true){
            $item->setContainer($this);
        }

        $this->cache[$name] = $item;

        return $item;
    }
```

# Routes

- Allowed methods are: GET and POST
- When matching a route with the given path, it uses regular expressions so it can receive these examples:
    - /login
    - /books/{id}/delete

## Registering a route

- To register a route, go to "config/web.php"
- First parameter is the path
- Second parameter is an array with Controller and its method.

```php
<?php

$app->get("/login", [Qss\Http\Controllers\LoginController::class, "index"]);
```

## Matching a route


- After registering all routes, it's matching current path (taken from $_SERVER) with them.
- If it does not match, it will return false.
- If it does, it will check REQUEST_METHOD

todo: 404


```php
<?php

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
```

# Http

- Http includes:
    - Controllers
    - Request
    - Response

## Controllers

- Every created controller must have the method written in "config/web.php"
- Each method includes dependeny injection (autowiring)
    - It can have Request
    - It must return Response