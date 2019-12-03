<?php
// Thanks for great codes: https://github.com/crjoseabraham/php-mvc

namespace App;

use PDO;
use PDOException;

/**
 * PDO Database Class
 * Connect to database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */
class Database
{
    private static $host = DB_HOST;
    private static $name = DB_NAME;
    private static $user = DB_USER;
    private static $pass = DB_PASS;

    private static $db_handler;
    private static $stmt;

    public static function init()
    {
        // Set DSN
        $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$name;
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        // Create PDO Instance
        try {
            self::$db_handler = new PDO($dsn, self::$user, self::$pass, $options);
            self::$db_handler->exec("set names utf8mb4");
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    // Prepare statement with query
    public static function query($sql)
    {
        try {
            self::$stmt = self::$db_handler->prepare($sql);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    // Bind values
    public static function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }

        try {
            self::$stmt->bindValue($param, $value, $type);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    // Execute the prepared statement
    public static function execute()
    {
        try {
            self::$stmt->execute();
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    // Get result set as array
    public static function fetchAll()
    {
        try {
            self::$stmt->execute();
            return self::$stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    // Get single record as object
    public static function fetch()
    {
        try {
            self::$stmt->execute();
            return self::$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }

    // Get row count
    public static function rowCount()
    {
        try {
            return self::$stmt->rowCount();
        } catch (PDOException $exception) {
            echo 'PDO Error: ' . $exception->getMessage();
        }
    }
}

Database::init();
