<?php

/**
 *
 */

namespace Library\Core;
use Library\Core\Session;

class Helper
{
    /**
     *
     */
    public static function rebuildFileArray($value)
    {
        foreach ($value as $file_attr_name => $file_value_array) {
            foreach ($file_value_array as $key => $attributes) {
                $file[$key][$file_attr_name] = $attributes;
            }
        }

        return call_user_func_array('array_merge', array_slice($file, 0, 1));
    }

    /**
     *
     */
    public static function getIP()
    {
        if ($_SERVER) {
            if ($_SERVER['HTTP_X_FORWARDED_FOR']) $IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            elseif ($_SERVER['HTTP_CLIENT_IP']) $IP = $_SERVER['HTTP_CLIENT_IP'];
            else $IP = $_SERVER['REMOTE_ADDR'];
        } else {
            if(getenv('HTTP_X_FORWARDED_FOR')) $IP = getenv('HTTP_X_FORWARDED_FOR');
            elseif(getenv('HTTP_CLIENT_IP')) $IP = getenv('HTTP_CLIENT_IP');
            else $IP = getenv('REMOTE_ADDR');
        }

        return $IP;
    }

    /**
     *
     */
    public static function checkIP($blacklist)
    {
        $IP = self::getIP();

        if (!empty($IP) && preg_match('#^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$#', $IP))
            if (in_array($IP, $blacklist)) return false;
            else return true;
        else return false;
    }

    /**
     *
     */
    public static function checkSpam($delay = 1)
    {
        if (Session::check('timeout') || Session::check('IP')) {
            if (Session::get('IP') === self::getIP()) {
                if (time() - Session::get('timeout') > $delay) {
                    Session::set('timeout', time());

                    return true;
                } else {
                    session_regenerate_id(true);
                    Session::set('timeout', time());

                    return false;
                }
            } else {
                return true;
            }
        } else {
            session_regenerate_id(true);
            Session::set('timeout', time());
            Session::set('IP', self::getIP());
            return true;
        }
    }

    /**
     *
     */
    public static function checkStrlen($value, $min = 0, $max = 250)
    {
        $strlen = mb_strlen($value);

        if ($strlen >= $min && $strlen <= $max) return true;
        else return false;
    }

    /**
     *
     */
    public static function checkEmail($value)
    {
        if (preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $value)) return true;
        else return false;
    }

    /**
     *
     */
    public static function checkUrl($value)
    {
        if (preg_match('#^(https?:\/\/+[\w\-]+\.[\w\-]+)#i', $value)) return true;
        else return false;
    }

    /**
     *
     */
    public static function checkPostCode($value)
    {
        if (preg_match('#^(2[abAB]|0[1-9]|[1-9][0-9])[0-9]{3}$#', $value)) return true;
        else return false;
    }

    /**
     *
     */
    public static function checkTelephone($value)
    {
        if (preg_match('#^0[1-68][0-9]{8}$#', $value)) return true;
        else return false;
    }

    /**
     *
     */
    public static function checkFile($value, $ext = array('jpg', 'jpeg', 'gif', 'tif', 'bmp', 'png', 'pdf', 'rtf', 'ppt', 'pps', 'pptx', 'pptm', 'xls', 'xlsx', 'doc', 'docx', 'txt'))
    {
        $file = self::rebuildFileArray();

        if ($file['error'] === 0) {
            if (in_array(strtolower(pathinfo(basename($file['name']), PATHINFO_EXTENSION)), $ext)) return true;
            else return false;
        } else {
            return false;
        }
    }

    /**
     *
     */
    public static function clean($value)
    {
        if (get_magic_quotes_gpc()) $value = stripslashes(htmlentities(trim($value), ENT_QUOTES, 'UTF-8'));
        else $value = htmlentities(trim($value), ENT_QUOTES, 'UTF-8');

        return $value;
    }

    /**
     *
     */
    public static function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
        else return true;
    }
}
