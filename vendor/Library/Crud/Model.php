<?php

/**
 *
 */

namespace Library\Crud;
use Library\Core\Database;

class Model
{
    public static $attributes;

    /**
     *
     */
    public static function getUniqueColumn()
    {
        foreach (static::$attributes as $column => $attributes) {
            if ($attributes['type'] === 'unique') return $column;
        }
    }

    /**
     *
     */
    public static function getOrderColumn()
    {
        foreach (static::$attributes as $column => $attributes) {
            if ($attributes['type'] === 'order') return $column;
        }
    }

    /**
     *
     */
    public static function getFormAttributes()
    {
        return array_filter(static::$attributes, function($attribute) {
            if (!isset($attribute['private'])) return $attribute;
        });
    }

    /**
     *
     */
    public static function search($conditions = null, $order = null, $direction = null, $offset = null, $limit = null)
    {
        $search = array();

        foreach (Database::selectMultiple((new \ReflectionClass(get_called_class()))->getShortName(), self::getUniqueColumn(), $conditions, $order, $direction, $offset, $limit) as $model) {
            array_push($search, static::get($model[self::getUniqueColumn()]));
        }

        return $search;
    }

    /**
     *
     */
    public static function collect()
    {
        $collection = array();

        foreach (Database::selectAll((new \ReflectionClass(get_called_class()))->getShortName(), self::getUniqueColumn()) as $model) {
            array_push($collection, static::get($model[self::getUniqueColumn()]));
        }

        return $collection;
    }

    /**
     *
     */
    public static function get($id)
    {
        if ($model = Database::selectOne((new \ReflectionClass(get_called_class()))->getShortName(), self::getUniqueColumn(), $id)) {

            foreach ($model as $key => $value) {
                static::$attributes[$key]['value'] = $value;

                if (static::$attributes[$key]['type'] === 'model') {
                    $model_attributes = static::$attributes[$key]['model'];
                    static::$attributes[$key]['attributes'] = $model_attributes::get(static::$attributes[$key]['value']);
                }
            }

            return static::$attributes;

        } else return false;
    }

    /**
     *
     */
    public static function save($attributes, $id = null)
    {
        foreach ($attributes as $column => $attribute) {
            $values[$column] = (isset($attribute['value'])) ? $attribute['value'] : ((isset($attribute['default'])) ? $attribute['default'] : null);
        }

        if ($id) {
            if (Database::update((new \ReflectionClass(get_called_class()))->getShortName(), self::getUniqueColumn(), $values, $id)) return true;
            else return false;
        } else {
            if (Database::insert((new \ReflectionClass(get_called_class()))->getShortName(), $values)) return true;
            else return false;
        }
    }

    /**
     *
     */
    public static function remove($id)
    {
        if (Database::delete((new \ReflectionClass(get_called_class()))->getShortName(), self::getUniqueColumn(), $id)) return true;
        else return false;
    }
}
