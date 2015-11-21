<?php

/**
 *
 */

namespace Library\Auth;

class User extends \Library\Crud\Model
{
    public static $attributes = array(
        'nom' => array (
            'type' => 'unique',
            'display' => 'nom d\'utilisateur',
            'required' => true,
        ),
        'password' => array (
            'type' => 'password',
            'display' => 'mot de passe',
            'required' => true,
        ),
        'role' => array (
            'type' => 'choice',
            'private' => true,
            'display' => 'rôle',
            'list' => array (
                1 => 'Anonymous',
                2 => 'User',
                3 => 'Admin',
            ),
            'required' => true,
        ),
    );

    /**
     *
     */
    public static function getPasswordColumn()
    {
        foreach (static::$attributes as $column => $attributes) {
            if ($attributes['type'] === 'password') return $column;
        }
    }
}
