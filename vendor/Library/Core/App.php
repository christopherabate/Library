<?php

/**
 *
 */

namespace Library\Core;
use Library\Core\Session;
use Library\Core\I18n;

class App
{
    const VERSION = '1.0';

    public $url;
    public $routes = array();
    public $error_route = '';
    public static $http_status_codes = array(
        '100' => 'Continue',
        '101' => 'Switching',
        '102' => 'Processing',
        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '207' => 'Multi-Status',
        '210' => 'Content Different',
        '226' => 'IM Used',
        '300' => 'Multiple Choices',
        '301' => 'Moved',
        '302' => 'Moved',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '307' => 'Temporary Redirect',
        '308' => 'Permanent Redirect',
        '310' => 'Too many Redirects',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '407' => 'Proxy Authentication Required',
        '408' => 'Request Time-out',
        '409' => 'Conflict',
        '410' => 'Gone',
        '411' => 'Length Required',
        '412' => 'Precondition Failed',
        '413' => 'Request Entity Too Large',
        '414' => 'Request-URI Too Long',
        '415' => 'Unsupported Media Type',
        '416' => 'Requested range unsatisfiable',
        '417' => 'Expectation failed',
        '418' => 'I’m a teapot',
        '422' => 'Unprocessable entity',
        '423' => 'Locked',
        '424' => 'Method failure',
        '425' => 'Unordered Collection',
        '426' => 'Upgrade Required',
        '428' => 'Precondition Required',
        '429' => 'Too Many Requests',
        '431' => 'Request Header Fields Too Large',
        '449' => 'Retry With',
        '450' => 'Blocked by Windows Parental Controls',
        '451' => 'Unavailable For Legal Reasons',
        '456' => 'Unrecoverable Error',
        '499' => 'client has closed connection',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '502' => 'Bad Gateway ou Proxy Error',
        '503' => 'Service Unavailable',
        '504' => 'Gateway Time-out',
        '505' => 'HTTP Version not supported',
        '506' => 'Variant also negociate',
        '507' => 'Insufficient storage',
        '508' => 'Loop detected',
        '509' => 'Bandwidth Limit Exceeded',
        '510' => 'Not extended',
        '511' => 'Network authentication required',
        '520' => 'Web server is returning an unknown error',
    );

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
    public function route($pattern, $callback, $exposed = true, $redirect_route = null)
    {
        $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

        if ($exposed === true) {
            $this->routes[$pattern] = $callback;
        } else {
            if ($redirect_route) $this->routes[$pattern] = function() use ($redirect_route) { self::redirect($redirect_route, 403, $_SERVER['QUERY_STRING']); };
            else $this->routes[$pattern] = function() { self::redirect($this->error_route, 403, $_SERVER['QUERY_STRING']); };
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
    public static function redirect($route, $code = null, $redirect_referer = null)
    {
        parse_str($redirect_referer);
        if (isset($url)) Session::set('redirect_referer', $url);
        
        if (in_array($code, self::$http_status_codes)) $http_status = self::$http_status_codes[$code];
        else $http_status = self::$http_status_codes[404];
        
        session_write_close();
        header($_SERVER['SERVER_PROTOCOL'].' '.$http_status);
        header('Location: '.APP_ROOT.$route);
        exit;
    }
}
