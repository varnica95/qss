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
}