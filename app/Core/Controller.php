<?php

namespace Qss\Core;

use Qss\Container;

class Controller
{
    /** @var $container */
    private $container;

    /**
     * Method for setting the container instance
     *
     * @param Container $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }
}