<?php

/**
 *
 */

namespace Library\Auth;

class User extends \Library\Crud\Model
{
    public static $attributes = array(
        'username' => array (
            'type' => 'unique',
            'required' => true,
        ),
        'password' => array (
            'type' => 'password',
            'required' => true,
        ),
        'email' => array (
            'type' => 'email',
            'required' => true,
        ),
    );

    /**
     *
     */
    public static function getLoginAttributes()
    {
        return array_filter(static::$attributes, function($attribute) {
            if ($attribute['type'] === 'unique' || $attribute['type'] === 'password') return $attribute;
        });
    }

    /**
     *
     */
    public static function getRecoverAttributes()
    {
        return array_filter(static::$attributes, function($attribute) {
            if ($attribute['type'] === 'unique' || $attribute['type'] === 'email') return $attribute;
        });
    }

    /**
     *
     */
    public static function getPasswordColumn()
    {
        foreach (static::$attributes as $column => $attributes) {
            if ($attributes['type'] === 'password') return $column;
        }

        return false;
    }

    /**
     *
     */
    public static function getEmailColumn()
    {
        foreach (static::$attributes as $column => $attributes) {
            if ($attributes['type'] === 'email') return $column;
        }

        return false;
    }
}
