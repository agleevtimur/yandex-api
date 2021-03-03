<?php

declare(strict_types=1);

final class App
{
    private static bool $initialized = false;
    private static Router $router;
    private static PDO $db;

    private function __construct() {}

    public static function init()
    {
        if (self::$initialized) {
            return;
        }

        self::$router = new Router('routes.php');
        self::$db = new PDO(
            'mysql:host=localhost;port=3306;dbname=altocar;user=altocar;password=altocar'
        );

        self::$initialized = true;
    }

    public static function getRouter(): Router
    {
        self::init();
        return self::$router;
    }

    public static function getDB(): PDO
    {
        self::init();
        return self::$db;
    }
}