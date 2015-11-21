<?php

/**
 *
 */

namespace Library\Core;
use Library\Core\I18n;

class App
{
    const VERSION = '1.0';

    public $url;
    public $routes = array();
    public $error_route = '';

    /**
     *
     */
    public function __construct ()
    {
        $this->url = (!empty($_GET['url'])) ? $_GET['url'] : null;
        $this->route($this->error_route, function() { echo I18n::__('welcome');; });
    }

    /**
     *
     */
    public function route($pattern, $callback, $exposed = true, $redirect = null)
    {
        $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

        if ($exposed === true) {
            $this->routes[$pattern] = $callback;
        } else {
            if ($redirect) $this->routes[$pattern] = function() use ($redirect) { self::redirect($redirect, '403 Forbidden'); };
            else $this->routes[$pattern] = function() { self::redirect($this->error_route, '403 Forbidden'); };
        }
    }

    /**
     *
     */
    public function run()
    {
        foreach ($this->routes as $pattern => $callback) {
            if (preg_match($pattern, $this->url, $params)) {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }

        self::redirect($this->error_route);
    }

    /**
     *
     */
    public static function redirect($route, $code = '404 Not found')
    {
        session_write_close();
        header($_SERVER['SERVER_PROTOCOL'].' '.$code);
        header('Location: '.APP_ROOT.$route);
        exit;
    }
}
