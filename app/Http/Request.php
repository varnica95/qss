<?php

namespace Qss\Http;

use Qss\Container;

class Request
{

    /** @var $container */
    private $container;

    /**
     * Undocumented function
     *
     * @param Container $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return void
     */
    public function get($name)
    {
        return $this->container->get("parameter_bag")->get($name, "post");
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return void
     */
    public function query($name)
    {
        return $this->container->get("parameter_bag")->get($name, "get");
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return boolean
     */
    public function has($name)
    {
        return $this->container->get("parameter_bag")->has($name);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getMatchedRoute()
    {
        return $this->container->get("router")->getMatchedRoute();
    }

    /**
     * Undocumented function
     *
     * @param [type] $url
     * @return void
     */
    public function redirect($url)
    {
        /** @var Response $response */
        $response = $this->container->get("response");
        
        $response->setCode(301);
        $response->setUrl($url);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function validateParameters()
    {
        $ret = array();
        $ret["message"] = null;
        $ret["error"] = true;

        $parameters = $this->container->get("parameter_bag")->getAll("post");

        if(empty($parameters)){
            return $ret;
        }

        if(isset($parameters["email"]) && !filter_var($parameters["email"], FILTER_VALIDATE_EMAIL)){
                $ret["message"] = "Invalid email address";
        }

        if(isset($parameters["password"]) && empty($parameters["password"])){
            $ret["message"] = "Password cannot be empty";
        }

        if(isset($parameters["author"]) && empty($parameters["author"])){
            $ret["message"] = "Author cannot be empty";
        }

        if(isset($parameters["author"]) && empty($parameters["author"])){
            $ret["message"] = "Author cannot be empty";
        }

        if(isset($parameters["release_date"]) && empty($parameters["release_date"])){
            $ret["message"] = "Release date cannot be empty";
        }

        if(isset($parameters["description"]) && empty($parameters["description"])){
            $ret["message"] = "Description cannot be empty";
        }

        if(isset($parameters["isbn"]) && empty($parameters["isbn"])){
            $ret["message"] = "Isbn date cannot be empty";
        }

        if(isset($parameters["format"]) && empty($parameters["format"])){
            $ret["message"] = "Format cannot be empty";
        }

        if(isset($parameters["number_of_pages"]) && empty($parameters["number_of_pages"])){
            $ret["message"] = "Number of pages cannot be empty";
        }



        if(!empty($ret["message"])){
            return $ret;
        }

        $ret["error"] = false;
        return $ret;	
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getQueryString() 
    {
        $parameters = $this->container->get("parameter_bag")->getAll("get");

        if(empty($parameters)){
            return null;
        }

        return "?" . http_build_query($parameters);
    }
}