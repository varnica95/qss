<?php

namespace Qss;

class Container
{
    /** @var array $items */
    private array $items;

    /** @var array $cache */
    protected array $cache;

    /**
     * Container constructor
     * @throws \Exception
     */
    public function __construct()
    {
        $this->registerContainerItems();
    }

    /**
     * @return void
     * @throws \Exception
     * Method for adding container items to the Container
     */
    private function registerContainerItems(): void
    {
        /**
         * Requiring file
         */
        $containerItems = require dirname(__DIR__) . "/config/container_items.php";
        if (empty($containerItems)){
            throw new \Exception("Container items are not found.");
        }

        foreach ($containerItems as $key => $value)
        {
            /**
             * If the value is not an object, instantiate it
             */
            if (!is_object($value)){
                $value = new $value;
            }

            /**
             * Add to the array
             */
            $this->items[$key] = $value;
        }

    }

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
        $this->cache[$name] = $item;

        return $item;
    }
}