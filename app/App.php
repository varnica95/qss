<?php

namespace Qss;

use Qss\Container;
use Qss\Core\Router;
use ReflectionMethod;
use Qss\Http\Response;
use Qss\Traits\RequestMethods;

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
        } catch(\Exception $e){
            $this->container->get('response')->setCode(404);
            $handler = ["message" => $e->getMessage()];
        }
        
        $respond = $this->processHandler($handler);

        return $this->processRespond($respond);
    }

    /**
     * Method for processing a handler
     *
     * @param array $handler
     * @return void
     */
    private function processHandler(array $handler)
    {
        if(isset($handler["message"])){
            return $handler["message"];
        }
        /**
         * Handler cannot be a object
         */
        if(is_object($handler[0]) === 1){
            throw new \Exception("Handler cannot be a object");
        }

        /**
         * New up the object and check if it has a "setContainer" method
         */
        $handler[0] = new $handler[0]();
        if(method_exists($handler[0], "setContainer") === true){
            $handler[0]->setContainer($this->container);
        }

        /**
         * This part is like a dependency injection
         */
        $method = new ReflectionMethod($handler[0], $handler[1]);
        $properties = $method->getParameters();
        
        $methodPropertyInstances = $this->addMethodProperties($properties);

        return $handler(...$methodPropertyInstances);
    }

    /**
     * Add method properties from the container
     *
     * @param array $properties
     * @return void
     */
    private function addMethodProperties(array $properties)
    {
        $methodProperties = array();

        /** @var ReflectionParameter $property*/
        foreach ($properties as $property) {

            if(!$property->hasType()){

                $routeParameters = $this->container->get("router")->getParameters();

            
                if(!isset($routeParameters[$property->name])){
                    throw new \Exception("Error: Parameter does not exist");
                } 

                $methodProperties[] = $routeParameters[$property->name];
                continue;
            }

            $class = $property->getType()->getName();

            if(!class_exists($class)){
                throw new \Exception("Does not exist");
            }

            /**
             * creating a snake_case
             */
            $exploded = explode("\\", $class);
            
            $className = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', end($exploded)));

            

            /**
             * Property must be in the container
             */
            $prop = $this->container->get($className);

            if($className === "request"){
                $middleware = $this->container->get('middleware');

                $request = $middleware->handle($prop);
            }

            $methodProperties[] = $prop;
        }

        return $methodProperties;
    }

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
}