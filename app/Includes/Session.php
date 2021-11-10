<?php

namespace Qss\Includes;

class Session
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function start()
    {
        if(!isset($_SESSION) || empty($_SESSION)){
            session_start();
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function destroy()
    {
        if(isset($_SESSION) || !empty($_SESSION)){
            session_unset();
            session_destroy();
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param [type] $value
     * @return void
     */
    public static function set($name, $value)
    {
        return self::has($name) ? null : $_SESSION[$name] = $value;
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return void
     */
    public static function get($name)
    {
        return self::has($name) ? $_SESSION[$name] : null;
    }

    /**
     * Undocumented function
     *
     * @param [type] $name
     * @return boolean
     */
    public static function has($name)
    {
       return isset($_SESSION[$name]); 
    }
}