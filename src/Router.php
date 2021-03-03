<?php

declare(strict_types=1);

class Router
{
    private array $routes;
    public function __construct(string $routeFilePath)
    {
        $this->routes = require $routeFilePath;
    }
    public function resolveAction(string $url)
    {
        if (isset($this->routes[$url])) {
            $controller = new $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
            $controller->$action();
            return;
        }

        throw new Exception("no action found for $url path");
    }
}