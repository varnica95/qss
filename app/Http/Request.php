<?php

namespace Qss\Http;

use Qss\Container;

class Response
{

    /** @var $container */
    private $container;
    /** @var $content */
    private $content;
    /** @var $code */
    private $code;
    /** @var $url */
    private $url;


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
     * @param string $content
     */
    public function __construct($content = '', $status = 200)
    {
       $this->content = $content;
       $this->code = $status;
    }

    /**
     * Undocumented function
     *
     * @param [type] $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param [type] $code
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getContent()
    {
        return $this->content; 
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCode()
    {
        return $this->code; 
    }

    /**
     * Undocumented function
     *
     * @param [type] $route
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUrl()
    {
        return $this->url;
    }
}