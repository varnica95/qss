# Q Symfony Skeleton Api project
Backend test  assignment for Q agency.
Created by: Marin Varnica


# Procedures
- The application starts from "public/index.php". To be more percise, it starts from the App's method "run".
    - It matches given routes and current path. (Path and request method)      
        - If the match was not found, it will throw an exception (404 error)     
        - If it was found, it will process the given handler (controller and its method)
- To process the given handler, firstly it makes an instance of the object.
- With the help of "Reflection" it takes all of the method's parameters and grabs them from the container.   
- Parameters without typehints are parameters from the matched route.
    - Example: author/{authorId} -> author/100
    - Value of "authorId" parameter in the method will be 10.
- Each method can have the Request as a parameter. For example, it can grab POST, GET parameters, it can also go through the auth middleware. 
- After the controller's method has successfully been started - each method has its own functionallity: 
    - LoginController::index - gives the login page where the user can enter login credentials
        - those credentials are passed to the QSS API's method token in the json format 
        - If everything is fine, from API's response, we grab token key.
        - Based on "remember_me" it uses Session or Cookie
            - If "remember_me" is not checked, Session will last until the browser is closed.
            - If it checked, cookie will be created and on the next visit, session will be generated based on the cookie.
    - LoginController::logout - destroys cookie and session
    - HomeController::index - gives the home page and shows first and last name.
        - It uses API's method "me" to grab currently logged user
    - AuthorController::index - shows the list of authors (with pagination)
        - it uses API's method "author" for fetching authors
    - AuthorController::showAuthor - shows author details
        - it uses API's method "author" for fetching details
    - AuthorController::deleteAuthor - deleting author
        - it uses API's method "author" for deleting
            	- The author is deletable if he/she does not have books.
    - BookController::index - fetching author list 
        - it uses API's method "author" for fetching
            - the list is used for "which author to use" in adding a book and showing
    - BookController::addNewBook - fetching author list 
        - it uses API's method "book" for adding
            - The data from POST (author, title, description, isbn, number_of_pages, format, release_date) is passed in the json format
    - BookController::deleteBook - delting the book
        - it uses API's method "book" for deleting


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
    - [Middleware](#middleware)
    - [Request](#request)
    - [Response](#response)
- [Includes](#http)
    - [Session](#session)
    - [Cookie](#cookie)
- [Front](#front)
    - [Views](#views)
    - [Resources](#resources)
        - [How to render a view?](#how-to-render-a-view-?)
- [QSS API](#qss-api)

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
        } catch(\Exception $e){
            $this->container->get('response')->setCode(404);
            $handler = ["message" => $e->getMessage()];
        }
        
        $respond = $this->processHandler($handler);

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
    - Middleware
    - Response

## Controllers

- Every created controller must have the method written in "config/web.php"
- Each method includes dependeny injection (autowiring)
- There is four different controllers:
    - LoginController, HomeController, AuthorController and BookController
- Each method can have Request as a parameter, and can return Response
- Also, it can redirect to certain page

```php
<?php

public function index(Request $request, QssApiService $qssApiService)
    {
        $userResponse = $qssApiService->getCurrentlyLoggedUser();

        if($userResponse["error"] === true) {    
            return $this->redirect("/login");
        }

        return $this->view('home.index', [
            "first_name" => $userResponse["response"]["first_name"],
            "last_name" => $userResponse["response"]["last_name"]
        ]);
    }
```

## Middleware

- Its power is to stay between request and response
- To add the middleware, go to "config/web.php"

```php
<?php

$app->get("/", [Qss\Http\Controllers\HomeController::class, "index"])->withAuthMiddleware("/");

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
```

## Request

- Request can grab POST or GET parameters with ParameterBag instance
- Validate form values

## Response

- Response is used from setting status code, url, content

# Includes 

The Includes folder contains Session and Cookie

## Session

- If the "remember_me" checkbox is not checked (login page), Session token will be created. It will be destroyed after the browser is closed

## Cookie

- If the "remember_me" checkbox is checked (login page), Cookie will be created. On the next visit, if the cookie exists, session will be generated.


# Front

- View class is responsible for rendering any html

## Views

- Method "render" returns variables: document_name, array data, file path.
- It also includes base app file app.phtml.

```php
<?php

public function render(string $path, array $data = [])
    {       
        $part = str_replace(".", "/", $path);
        $file = $this->getFile($part);
       
        $document_name = ucfirst(explode(".", $path)[0]);

        array_push($data, $document_name, $file);

        extract($data, EXTR_SKIP);
       
        ob_start();
        try {
            extract($data, EXTR_SKIP);
            require $this->getFile("app");
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        return $output;
    }
```

## Resources

- Resources/views are directory which contains all the markup.
- There is: login, home, author and book page

### How to render a view?

- In any controller, use the "view" method.
- Second parameter is associative array with data

 ```php
<?php

$this->view('home.index', ["id" => 5]);

protected function view(string $path, array $data = [])
    {
         /** @var View $view */
        $view = $this->container->get("view");
         /** @var Response $response */
        $response = $this->container->get("response");
         /** @var Router $response */
        $router = $this->container->get("router");

        $content = $view->render($path, $data);

        $response->setContent($content);

        return $response;
    }

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


 ```php
<?php if(isset($id)): ?>
       <p>  <?php echo $id; ?></p>
<?php endif; ?>

```

# QSS API

- This class is used for connecting to Q Symfony skeleton api
- It is using curl for it
- Used methods:
    - token = For auth
    - me = for getting currenly signed user
    - author = for fetching author list (it also uses query string) and for getting the author details, deleting authors
    - books = for inserting a new book and deleting books



 ```php
   private function callCUrl(string $url, string $method, array $headers = [], $data = null)
    {
        $ret = array();
        $ret["message"] = null;
        $ret["error"] = true;
        $ret["code"] = 200;
        $ret["response"] = null;

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            curl_close($curl);

        } catch (\Exception $e) {
            $ret["message"] = $e->getMessage();
        }
        

        $responseArray = json_decode($response, TRUE);

        if(isset($responseArray["error"])){
            $ret["message"] = $responseArray["error"];
            $ret["code"] = $responseArray["code"];
            return $ret;
        }

        $ret["error"] = false;
        $ret["response"] = $responseArray;
            
        return $ret;
    }

```


 ```php
public function deleteBook(string $bookId)
    {
        $url = self::API_URL . "books/" . $bookId;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "DELETE", $headers);
        
        return $response;
}
```