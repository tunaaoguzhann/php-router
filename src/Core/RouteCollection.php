<?php

namespace App\Core;

class RouteCollection
{
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function all(): array
    {
        return $this->routes;
    }

    public function find(string $method, string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->getMethod() === $method && $route->getPath() === $path) {
                return $route;
            }
        }
        return null;
    }
}