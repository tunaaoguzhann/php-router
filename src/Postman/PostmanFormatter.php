<?php

namespace App\Postman;

use App\Core\Route;

class PostmanFormatter
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $_ENV['BASE_URL'];
    }

    public function formatCollection(array $routes): array
    {
        return [
            'info' => [
                'name' => 'APP_NAME' . ' API Documentation',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
            ],
            'item' => array_map([$this, 'formatRoute'], $routes)
        ];
    }

    private function formatRoute(Route $route): array
    {
        return [
            'name' => $route->getPath(),
            'request' => [
                'method' => $route->getMethod(),
                'header' => [],
                'url' => [
                    'raw' => $this->baseUrl . $route->getPath(),
                    'host' => [parse_url($this->baseUrl, PHP_URL_HOST)],
                    'path' => array_filter(explode('/', trim($route->getPath(), '/')))
                ],
                'description' => $route->getOptions()['description'] ?? ''
            ]
        ];
    }
}