<?php

namespace Qss\Bags;

use Qss\Container;

class ParameterBag
{
    /** @var Container $container */
    protected $container;
    /** @var $parameters  */
    protected $parameters;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->parameters["post"] = $_POST;
        $this->parameters["get"] = $_GET;
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return void
     */
    public function get($name, $method = "post")
    {
      return $this->parameters[$method][$name] ?? throw new \Exception("Does not exist");
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return boolean
     */
    public function has($name)
    {
      return isset($this->parameters["post"][$name]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAll(string $method)
    {
      return $this->parameters[$method];
    }

}