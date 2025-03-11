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

    // Yeni eklenen metodlar
    public function run(): void
    {
        // Önce senkronizasyonu yap
        $this->syncWithPostman();

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $route = $this->findRoute($method, $uri);

        if ($route === null) {
            $this->handleNotFound();
            return;
        }

        $this->handleRoute($route);
    }

    private function findRoute(string $method, string $path): ?Route
    {
        foreach ($this->routes->all() as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }
        return null;
    }

    private function handleRoute(Route $route): void
    {
        $handler = $route->getHandler();

        if (is_string($handler)) {
            // Controller@method formatını işle
            [$controllerClass, $method] = explode('@', $handler);

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller not found: $controllerClass");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method not found: $method in $controllerClass");
            }

            $response = $controller->$method();
        } elseif (is_callable($handler)) {
            // Closure veya callable'ı çalıştır
            $response = $handler();
        } else {
            throw new \RuntimeException('Invalid route handler');
        }

        // JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function handleNotFound(): void
    {
        header("HTTP/1.0 404 Not Found");
        echo json_encode([
            'error' => 'Route not found',
            'status' => 404
        ]);
    }

    public function syncWithPostman(): void
    {
        // shouldSync kontrolünü kaldırıyoruz, her zaman senkronize et
        if ($this->postmanSync) {
            $this->postmanSync->updateCollection($this->routes->all());
        }
    }

    public function __destruct()
    {
        $this->syncWithPostman();
    }
}