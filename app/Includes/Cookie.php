<?php

namespace Qss\Includes;

class Cookie
{
    /**
     * @param $name
     * @param $value
     * @param $expiracy
     * @return bool
     */
    public static function set($name, $value)
    {
        return self::has($name) ? false : setcookie($name, $value, time() + 432000, "/"); // 432000s = 5 days
    }

    /**
     * @param $name
     * @return bool
     */
    public static function unset($name)
    {
        return self::has($name) ? setcookie($name, '', time() - 1) : null;
    }

    /**
     * @param $name
     * @return false|mixed
     */
    public static function get($name)
    {
        return self::has($name) ? $_COOKIE[$name] : null;
    }

    /**
     * @param $name
     * @return bool
     */
    protected static function has($name)
    {
        return isset($_COOKIE[$name]);
    }
}