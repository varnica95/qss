<?php

namespace Qss\Traits;

trait RequestMethods
{
    /**
     * @throws \Exception
     */
    public function get($route, $handler)
    {
        $this->container->get("router")->setRoute($route, $handler, ["GET"]);
    }

    /**
     * @throws \Exception
     */
    public function post($route, $handler)
    {
        $this->container->get("router")->setRoute($route, $handler, ["POST"]);
    }

    /**
     * @throws \Exception
     */
    public function map($route, $handler)
    {
        $this->container->get("router")->setRoute($route, $handler, ["GET", "POST"]);
    }
}