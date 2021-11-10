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

    /**
     * Undocumented function
     *
     * @param [type] $url
     * @return void
     */
    protected function redirect($url)
    {
        /** @var Response $response */
        $response = $this->container->get("response");
        
        $response->setCode(301);
        $response->setUrl($url);

        return $response;
    }

    /**
     * Creating a view
     *
     * @param string $path
     * @param array $data
     * @return void
     */
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
}