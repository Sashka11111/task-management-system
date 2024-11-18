<?php

declare(strict_types=1);

namespace Liamtseva\TaskManagementSystem\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=localhost;dbname=task-management-system";
                self::$connection = new PDO($dsn, 'root');
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Помилка підключення до бази даних: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
