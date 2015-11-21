<?php

/**
 *
 */

namespace Library\Core;

class Session
{   
    /**
     * 
     */
    public static function set($key, $value)
    {
        if (!session_id()) session_start();

        $_SESSION[$key] = serialize($value);

        if (self::check($key)) return true;
        else return false;
    }
    
    /**
     * 
     */
    public static function get($key)
    {
        if (!session_id()) session_start();

        if (self::check($key)) return unserialize($_SESSION[$key]);
        else return false;
    }
    
    /**
     * 
     */
    public static function check($key)
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * 
     */
    public static function delete($key)
    {
        if (self::check($key)) {
            unset($_SESSION[$key]);
            return !self::check($key);
        } else return false;
    }
    
    /**
     * 
     */
    public static function flash($type = null, $message = null)
    {
        if (!$type && !$message) {
            $flash = self::get('flash');
            self::delete('flash');
            return $flash;
        }

        self::set('flash', array($type => $message));
    }
}