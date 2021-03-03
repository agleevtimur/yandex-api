<?php

spl_autoload_register(static function($class) {
    $class = str_replace('\\', '/', $class);
    include dirname(__DIR__) . "/src/$class.php";
});

App::init();
App::getRouter()->resolveAction(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));