<?php

namespace TunaOguzhan\Router\Core;

use TunaOguzhan\Router\Interfaces\RouterInterface;
use TunaOguzhan\Router\Postman\PostmanSync;
use Dotenv\Dotenv;

class Router implements RouterInterface
{
    private RouteCollection $routes;
    private ?PostmanSync $postmanSync;
    private bool $shouldSync = false;

    public function __construct()
    {
        $this->routes = new RouteCollection();

        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->safeLoad();

        $dotenv->required(['BASE_URL', 'POSTMAN_API_KEY', 'POSTMAN_COLLECTION_ID']);

        $this->postmanSync = new PostmanSync(
            $_ENV['POSTMAN_API_KEY'],
            $_ENV['POSTMAN_COLLECTION_ID']
        );
    }

    // callable yerine mixed kullanıyoruz
    public function get(string $path, mixed $handler = null, array $options = []): void
    {
        $this->addRoute('GET', $path, $handler, $options);
    }

    public function post(string $path, mixed $handler = null, array $options = []): void
    {
        $this->addRoute('POST', $path, $handler, $options);
    }

    public function put(string $path, mixed $handler = null, array $options = []): void
    {
        $this->addRoute('PUT', $path, $handler, $options);
    }

    public function delete(string $path, mixed $handler = null, array $options = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $options);
    }

    private function addRoute(string $method, string $path, mixed $handler, array $options): void
    {
        $route = new Route($method, $path, $handler, $options);
        $this->routes->add($route);
        $this->shouldSync = true;
    }

    public function syncWithPostman(): void
    {
        if ($this->shouldSync && $this->postmanSync) {
            $this->postmanSync->updateCollection($this->routes->all());
            $this->shouldSync = false;
        }
    }

    public function __destruct()
    {
        $this->syncWithPostman();
    }
}