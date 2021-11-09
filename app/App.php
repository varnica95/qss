<?php

namespace Qss;

use Qss\Container;
use Qss\Core\Router;
use Qss\Traits\RequestMethods;
use Qss\Http\Response;

class App
{
    use RequestMethods;

    /** @var Container $container */
    private $container;

    /**
     * App constructor
     */
    public function __construct()
    {
        $this->container = new Container();
    }

    public function run()
    {
        /** @var Router $router */
        $router = $this->container->get("router");

        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $router->setPath($currentPath);

        try{
            $handler = $router->getRouteHandler();
        } catch(Exception $e){
            dd($e);
        }
        
       
        $respond = $this->processHandler($handler);
    
        return $this->processRespond($respond);
    }

    private function processHandler(array $handler)
    {
        $handler[0] = new $handler[0]();
        if(method_exists($handler[0], "setContainer") === true){
            $handler[0]->setContainer($this->container);
        }

        return $handler();
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
}