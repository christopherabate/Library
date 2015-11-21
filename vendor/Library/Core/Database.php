<?php

/**
 *
 */

namespace Library\Core;
use PDO;

class Database
{
    private static $db_connect = null;
    private static $db_driver = DB_DRIVER;
    private static $db_host = DB_HOST;
    private static $db_name = DB_NAME;
    private static $db_user = DB_USER;
    private static $db_password = DB_PASSWORD;
    private static $db_charset = DB_CHARSET;
    private static $db_options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    );

    /**
     *
     */
    public static function connect()
    {
        if (self::$db_connect == null) {
            try {
                self::$db_connect = new PDO(self::$db_driver.':host='.self::$db_host.';dbname='.self::$db_name, self::$db_user, self::$db_password, self::$db_options);
            } catch (PDOException $e) {
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }
        return self::$db_connect;
    }

    /**
     *
     */
    public static function disconnect()
    {
        self::$db_connect = null;
    }

    /**
     *
     */
    public static function selectMultiple($table, $column, $values = null, $order = null, $direction = 'ASC', $offset = 0, $limit = 1) {
        $values = array_filter($values);
        $db_connect = self::connect();
        $statement = $db_connect->prepare('SELECT '.$column.' FROM '.$table.' WHERE '.implode(' = ? AND ', array_keys($values)).' = ?');
        self::disconnect();

        if ($statement->execute(array_values($values))) return $statement->fetchAll();
        else return false;
    }

    /**
     *
     */
    public static function selectAll($table, $column) {
        $db_connect = self::connect();
        $statement = $db_connect->prepare('SELECT '.$column.' FROM '.$table);
        self::disconnect();

        if ($statement->execute()) return $statement->fetchAll();
        else return false;
    }

    /**
     *
     */
    public static function selectOne($table, $column, $id) {
        $db_connect = self::connect();
        $statement = $db_connect->prepare('SELECT * FROM '.$table.' WHERE `'.$column.'` = :id');
        self::disconnect();

        if ($statement->execute(array('id' => $id))) return $statement->fetch();
        else return false;
    }

    /**
     *
     */
    public static function insert($table, $values) {
        $db_connect = self::connect();
        $statement = $db_connect->prepare('INSERT INTO '.$table.' ('.implode(', ', array_keys($values)).') VALUES (:'.implode(', :', array_keys($values)).')');
        self::disconnect();

        if ($statement->execute($values) && $statement->rowCount()) return true;
        else return false;
    }

    /**
     *
     */
    public static function update($table, $column, $values, $id) {
        $db_connect = self::connect();
        $statement = $db_connect->prepare('UPDATE '.$table.' SET '.implode(' = ?, ', array_keys($values)).' = ? WHERE '.$column.' = '.$id);
        self::disconnect();

        if ($statement->execute(array_values($values))) return true;
        else return false;
    }

    /**
     *
     */
    public static function delete($table, $column, $id) {
        $db_connect = self::connect();
        $statement = $db_connect->prepare('DELETE FROM '.$table.' WHERE '.$column.' = '.$id);
        self::disconnect();

        if ($statement->execute() && $statement->rowCount()) return true;
        else return false;
    }
}
