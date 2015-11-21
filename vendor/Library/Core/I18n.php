<?php

/**
 *
 */

namespace Library\Core;

class I18n
{
    /**
     *
     */
    public static function __($key, $variables = null, $lang = null)
    {
        if ($lang && file_exists(FILES_ROOT.'config/'.$lang.'.json')) {
            return self::parse(json_decode(file_get_contents(FILES_ROOT.'config/'.$lang.'.json'))->{$key}, $variables);
        } elseif (defined('LANG') && file_exists(FILES_ROOT.'config/'.LANG.'.json')) {
            return self::parse(json_decode(file_get_contents(FILES_ROOT.'config/'.LANG.'.json'))->{$key}, $variables);
        } else {
            return self::parse(json_decode(file_get_contents(FILES_ROOT.'config/i18n.json'))->{$key}, $variables);
        }
    }
        /**
         *
         */
        public static function parse($text, $variables)
        {
            if (!empty($variables)) {
                foreach ($variables as $key => $variable) {
                    $variables['/%'.$key.'%/'] = $variable;
                    unset($variables[$key]);
                }

                return preg_replace(array_keys($variables), array_values($variables), $text);
            } else {
                return $text;
            }
        }
}
