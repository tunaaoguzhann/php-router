<?php

namespace App\Core;

class Route
{
    private string $method;
    private string $path;
    private $handler; // callable tipini kaldırdık
    private array $options;

    public function __construct(
        string $method,
        string $path,
               $handler = null, // nullable parameter
        array $options = []
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->options = $options;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler()  // return type hint'i kaldırdık
    {
        return $this->handler;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasHandler(): bool
    {
        return $this->handler !== null;
    }
}